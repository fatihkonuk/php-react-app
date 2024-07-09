<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;


require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, array $args) {
    $db = new Db();

    try {
        $db = $db->connect();
        $response->getBody()->write("database connected");
        return $response;
    } catch (PDOException $e) {
        $response->getBody()->write(
            json_encode(array(
                "error" => $e->getMessage(),
                "code" => $e->getCode()
            ))
        );
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
});

$app->run();
