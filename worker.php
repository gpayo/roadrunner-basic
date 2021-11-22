<?php

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

        if (!($request instanceof ServerRequestInterface)) { // Termination request received
            break;
        }
    } catch (\Throwable $e) {
        $psr7->respond(new Response(400, [], $e->getMessage())); // Bad Request
        continue;
    }

    try {
        // Application code logic
        $date = new DateTimeImmutable();
        $psr7->respond(new Response(200, [], $date->format(DateTimeImmutable::ATOM)));
    } catch (\Throwable) {
        $psr7->respond(new Response(500, [], 'Something Went Wrong!'));
    }
}