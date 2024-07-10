<?php

use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../models/User.php'; // Model dosyasını dahil ediyoruz.
require __DIR__ . '/../helpers/route.helper.php';

$app->group('/api/users', function (RouteCollectorProxy $group) {
    $group->get('', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $users = $userModel->find();

            return jsonResponse($response, $users, 'Users listed successfuly');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('find-users');

    $group->get('/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $userModel = new User($db);

            $id = $args['id'];

            $user = $userModel->findById($id);

            return jsonResponse($response, $user, 'User listed successfuly');
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

            return jsonResponse($response, ['userId' => $userId], 'User created successfuly', 201);
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
            $message = sizeof($users) . ' User created successfuly';
            return jsonResponse($response, null, $message, 201);
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
                return jsonResponse($response, null, 'User updated successfully', 200);
            } else {
                return jsonResponse($response, null, 'User not found or no changes made', 404);
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
                return jsonResponse($response, null, 'User not found', 404);
            }

            return jsonResponse($response, null, 'User deleted successfuly', 200);
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('delete-user-by-id');
});
