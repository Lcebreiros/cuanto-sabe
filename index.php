<?php

define('LARAVEL_START', microtime(true));

// Si la aplicación está en mantenimiento, cargar la página correspondiente
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Cargar el autoload de Composer
require __DIR__.'/vendor/autoload.php';

// Bootstrapping de la aplicación
$app = require_once __DIR__.'/bootstrap/app.php';

// Crear el kernel HTTP
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Capturar la petición y obtener la respuesta
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Enviar la respuesta al navegador
$response->send();

// Finalizar el kernel (limpieza, eventos, etc)
$kernel->terminate($request, $response);
