<?php

class Post
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function find()
    {
        $stmt = $this->db->prepare('SELECT * FROM posts');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);
        $result = [];
        if ($post) {
            $result = [
                'id' => $post['id'],
                'userId' => $post['userId'],
                'title' => $post['title'],
                'body' => $post['body'],
            ];

            return $result;
        }

        return null;
    }

    public function findByUserId($userId)
    {
        $stmt = $this->db->prepare('SELECT * FROM posts WHERE userId = ?');
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($post)
    {
        $stmt = $this->db->prepare('INSERT INTO posts (userId, title, body) VALUES (?, ?, ?)');
        $stmt->execute([
            $post['userId'],
            $post['title'],
            $post['body']
        ]);
        $userId = $this->db->lastInsertId();
        return $userId;
    }

    public function insertMany(array $posts)
    {
        foreach ($posts as $post) {
            $this->create($post);
        }
        return;
    }

    public function updateById($id, $post)
    {
        $stmt = $this->db->prepare('UPDATE posts SET title = ?, body = ? WHERE id = ?');
        $stmt->execute([
            $post['title'],
            $post['body'],
            $id
        ]);

        return $stmt->rowCount();
    }


    public function deleteById($id)
    {
        $stmt = $this->db->prepare('DELETE FROM posts WHERE id = ?');
        $result = $stmt->execute([$id]);

        return $result;
    }
}
