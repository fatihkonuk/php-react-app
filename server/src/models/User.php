<?php

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function find()
    {
        $stmt = $this->db->prepare('SELECT * FROM users');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function create($user)
    {
        $stmt = $this->db->prepare('INSERT INTO users (name, username, email, phone, website) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            $user['name'],
            $user['username'],
            $user['email'],
            $user['phone'],
            $user['website']
        ]);
        $userId = $this->db->lastInsertId();
        $this->addCompany($userId, $user['company']);
        $this->addAddress($userId, $user['address']);

        return $userId;
    }

    public function insertMany(array $users)
    {
        foreach ($users as $user) {
            $this->create($user);
        }
        return;
    }

    public function updateById($id, $user)
    {
        $stmt = $this->db->prepare('UPDATE users SET name = ?, username = ?, email = ?, phone = ?, website = ? WHERE id = ?');
        $stmt->execute([
            $user['name'],
            $user['username'],
            $user['email'],
            $user['phone'],
            $user['website'],
            $id
        ]);

        $this->updateCompany($id, $user['company']);
        $this->updateAddress($id, $user['address']);

        return $stmt->rowCount();
    }


    public function deleteById($id)
    {
        $stmt = $this->db->prepare('DELETE FROM users WHERE id = ?');
        $result = $stmt->execute([$id]);

        return $result;
    }

    public function addCompany($userId, $company)
    {
        $stmt = $this->db->prepare('INSERT INTO companies (userId, name, catchPhrase, bs) VALUES (?, ?, ?, ?)');
        $stmt->execute([
            $userId,
            $company['name'],
            $company['catchPhrase'],
            $company['bs']
        ]);
    }

    public function addAddress($userId, $address)
    {
        $stmt = $this->db->prepare('INSERT INTO addresses (userId, street, suite, city, zipcode, geo_lat, geo_lng) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $userId,
            $address['street'],
            $address['suite'],
            $address['city'],
            $address['zipcode'],
            $address['geo']['lat'],
            $address['geo']['lng']
        ]);
    }

    public function updateCompany($userId, $company)
    {
        $stmt = $this->db->prepare('UPDATE companies SET name = ?, catchPhrase = ?, bs = ? WHERE userId = ?');
        $stmt->execute([
            $company['name'],
            $company['catchPhrase'],
            $company['bs'],
            $userId
        ]);

        return $stmt->rowCount();
    }

    public function updateAddress($userId, $address)
    {
        $stmt = $this->db->prepare('UPDATE addresses SET street = ?, suite = ?, city = ?, zipcode = ?, geo_lat = ?, geo_lng = ? WHERE userId = ?');
        $stmt->execute([
            $address['street'],
            $address['suite'],
            $address['city'],
            $address['zipcode'],
            $address['geo']['lat'],
            $address['geo']['lng'],
            $userId
        ]);

        return $stmt->rowCount();
    }
}
