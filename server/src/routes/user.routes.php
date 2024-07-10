<?php

use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../models/User.php'; // Model dosyasını dahil ediyoruz.

$app->group('/api/users', function (RouteCollectorProxy $group) {
    $group->get('', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $users = $userModel->find();
            $payload = json_encode($users);

            $response->getBody()->write($payload);
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('');

    $group->get('/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $id = $args['id'];

            $user = $userModel->findById($id);
            $payload = json_encode($user);

            $response->getBody()->write($payload);
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('find-user-by-id');

    $group->post('', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $json = $request->getBody();
            $user = json_decode($json, true);

            $userId = $userModel->create($user);

            $response->getBody()->write(json_encode(['message' => 'User created', 'userId' => $userId]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('create-user');

    $group->post('/many', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $json = $request->getBody();
            $users = json_decode($json, true);

            $userModel->insertMany($users);

            $response->getBody()->write(json_encode(['message' => 'Users created', 'count' => sizeof($users)]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(201);
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('insert-many-user');

    $group->put('/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $id = $args['id'];
            $json = $request->getBody();
            $user = json_decode($json, true);

            $userUpdated = $userModel->updateById($id, $user);

            if ($userUpdated) {
                $response->getBody()->write(json_encode(['message' => 'User updated successfully']));
                return $response
                    ->withStatus(200)
                    ->withHeader('Content-Type', 'application/json');
            } else {
                $response->getBody()->write(json_encode(['error' => 'User not found or no changes made']));
                return $response
                    ->withStatus(404)
                    ->withHeader('Content-Type', 'application/json');
            }
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('update-user-by-id');

    $group->delete('/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $id = $args['id'];
            $success = $userModel->deleteById($id);

            if (!$success) {
                return $response
                    ->withStatus(404)
                    ->withHeader('Content-Type', 'application/json')
                    ->getBody()->write(json_encode(['error' => 'User not found']));
            }

            $response->getBody()->write(json_encode(['message' => 'User deleted successfuly']));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('delete-user-by-id');
});

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
