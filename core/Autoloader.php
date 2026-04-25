<?php
spl_autoload_register(function (string $class): void {
    $dirs = [
        __DIR__ . '/',                          
        __DIR__ . '/../app/controllers/',       
        __DIR__ . '/../app/models/',            
    ];
    foreach ($dirs as $dir) {
        $file = $dir . $class . '.php';
        if (file_exists($file)) { require_once $file; return; }
    }
});
