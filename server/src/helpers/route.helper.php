<?php

function jsonResponse($response, $data, $message, $status = 200)
{
    $response->getBody()->write(json_encode([
        'message' => $message,
        'data' => $data,
    ]));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus($status);
}

function handleError($response, $e)
{
    $errorData = [
        'error' => array(
            'text' => $e->getMessage(),
            'code' => $e->getCode()
        )
    ];
    $response->getBody()->write(json_encode($errorData));
    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(500);
}
