<?php
class Router
{
    private array $routes = [];

    public function get(string $path, string $ctrl, string $action): void  { $this->routes['GET'][$path]  = compact('ctrl','action'); }
    public function post(string $path, string $ctrl, string $action): void { $this->routes['POST'][$path] = compact('ctrl','action'); }

    public function dispatch(): void
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $base   = parse_url(BASE_URL, PHP_URL_PATH);
        if ($base && str_starts_with($uri, $base)) $uri = substr($uri, strlen($base));
        $uri = '/' . trim($uri, '/');
        if ($uri === '/') $uri = '/home';

        if (isset($this->routes[$method][$uri])) {
            $r = $this->routes[$method][$uri];
            $this->run($r['ctrl'], $r['action'], []);
            return;
        }

        foreach ($this->routes[$method] ?? [] as $pattern => $r) {
            $regex = '#^' . preg_replace('/\{[a-z_]+\}/', '([^/]+)', $pattern) . '$#';
            if (preg_match($regex, $uri, $m)) {
                array_shift($m);
                $this->run($r['ctrl'], $r['action'], $m);
                return;
            }
        }

        http_response_code(404);
        $this->run('PagesController', 'notFound', []);
    }

    private function run(string $name, string $action, array $params): void
    {
        $file = __DIR__ . '/../app/controllers/' . $name . '.php';
        if (!file_exists($file)) { http_response_code(404); die("Controller not found: $name"); }
        require_once $file;
        $ctrl = new $name();
        if (!method_exists($ctrl, $action)) { http_response_code(404); die("Action not found: $action"); }
        $ctrl->$action(...$params);
    }
}
