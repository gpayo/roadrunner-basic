<?php

/**
 * Esta es una versión adaptada del fichero PHP que aparece en la
 * documentación de Roadrunner:
 *
 * @link https://roadrunner.dev/docs/php-worker
 */

use Nyholm\Psr7\Factory\Psr17Factory;
use Spiral\RoadRunner;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;

include "vendor/autoload.php";

$worker = RoadRunner\Worker::create();
$psrFactory = new Psr17Factory();

$psr7 = new RoadRunner\Http\PSR7Worker($worker, $psrFactory, $psrFactory, $psrFactory);

while (true) {
    try {
        $request = $psr7->waitRequest();

        // Se recibió una petición de finalización
        if (!($request instanceof ServerRequestInterface)) {
            break;
        }
    } catch (\Throwable $e) {
        $psr7->respond(new Response(400, [], $e->getMessage())); // Petición incorrecta
        continue;
    }

    try {
        // ¡Aquí está la lógica de tu aplicación!
        $date = new DateTimeImmutable();


        $psr7->respond(new Response(200, [], $date->format(DateTimeImmutable::ATOM)));
    } catch (\Throwable) {
        $psr7->respond(new Response(500, [], '¡Ups! Algo ha ido mal'));
    }
}