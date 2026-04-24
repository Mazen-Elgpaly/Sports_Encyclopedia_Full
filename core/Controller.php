<?php
abstract class Controller
{
    protected function render(string $view, array $data = [], string $layout = 'main'): void
    {
        extract($data);
        ob_start();
        $viewFile = __DIR__ . '/../app/views/' . $view . '.php';
        if (!file_exists($viewFile)) { http_response_code(500); die("View not found: $view"); }
        require $viewFile;
        $content = ob_get_clean();
        $layoutFile = __DIR__ . '/../app/views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) require $layoutFile;
        else echo $content;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
        exit;
    }

    protected function json(mixed $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function isPost(): bool   { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
    protected function isLoggedIn(): bool { return isset($_SESSION['user_id']); }
    protected function isAdmin(): bool  { return ($_SESSION['user_role'] ?? '') === 'admin'; }

    protected function requireLogin(): void
    {
        if (!$this->isLoggedIn()) $this->redirect('login');
    }

    protected function requireAdmin(): void
    {
        if (!$this->isLoggedIn()) { $this->redirect('login'); }
        if (!$this->isAdmin())    { http_response_code(403); $this->render('pages/403'); exit; }
    }

    protected function post(string $key, mixed $default = null): mixed { return $_POST[$key] ?? $default; }
    protected function get(string $key, mixed $default = null): mixed  { return $_GET[$key]  ?? $default; }

    protected function sanitize(string $val): string
    {
        return htmlspecialchars(trim($val), ENT_QUOTES, 'UTF-8');
    }
}
