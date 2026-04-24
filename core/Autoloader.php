<?php
spl_autoload_register(function (string $class): void {
    $dirs = [
        __DIR__ . '/',                          // core (صح)
        __DIR__ . '/../app/controllers/',       // controllers (صح)
        __DIR__ . '/../app/models/',            // models (صح)
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});
