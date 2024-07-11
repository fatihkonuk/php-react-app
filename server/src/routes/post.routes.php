<?php

use Slim\Routing\RouteCollectorProxy;

require __DIR__ . '/../models/Post.php'; // Model dosyasını dahil ediyoruz.

$app->group('/api/posts', function (RouteCollectorProxy $group) {
    $group->get('', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $postModel = new Post($db);

            $posts = $postModel->find();

            return jsonResponse($response, $posts, 'Posts listed successfuly');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('find-posts');

    $group->get('/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $postModel = new Post($db);

            $id = $args['id'];

            $post = $postModel->findById($id);

            return jsonResponse($response, $post, 'Post listed successfuly');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('find-post-by-id');

    $group->get('/user/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $postModel = new Post($db);

            $userId = $args['id'];

            $post = $postModel->findByUserId($userId);

            return jsonResponse($response, $post, 'Posts listed successfuly');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('find-post-by-user-id');

    $group->post('', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $postModel = new Post($db);

            $json = $request->getBody();
            $post = json_decode($json, true);

            $postId = $postModel->create($post);

            return jsonResponse($response, ['postId' => $postId], 'Post created successfuly', 201);
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('create-post');

    $group->post('/many', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $postModel = new Post($db);

            $json = $request->getBody();
            $posts = json_decode($json, true);

            $postModel->insertMany($posts);

            $message = sizeof($posts) . ' Post created successfuly';
            return jsonResponse($response, null, $message, 201);
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('insert-many-post');

    $group->put('/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $postModel = new Post($db);

            $id = $args['id'];
            $json = $request->getBody();
            $post = json_decode($json, true);

            $postUpdated = $postModel->updateById($id, $post);

            if ($postUpdated) {
                return jsonResponse($response, $post, 'Posts updated successfuly');
            } else {
                return jsonResponse($response, $post, 'Post not found or no changes made', 404);
            }
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('update-post-by-id');

    $group->delete('/{id}', function ($request, $response, array $args) {
        try {
            $db = new Db();
            $db = $db->connect();
            $postModel = new Post($db);

            $id = $args['id'];
            $success = $postModel->deleteById($id);

            if (!$success) {
                return jsonResponse($response, null, 'Post not found', 404);
            }

            return jsonResponse($response, null, 'Post deleted successfuly');
        } catch (PDOException $e) {
            return handleError($response, $e);
        }
    })->setName('delete-post-by-id');
});
