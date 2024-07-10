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
        $stmt = $this->db->prepare('
            SELECT 
                users.*, 
                addresses.street, addresses.suite, addresses.city, addresses.zipcode, addresses.geo_lat, addresses.geo_lng,
                companies.name as company_name, companies.catchPhrase, companies.bs
            FROM users
            LEFT JOIN addresses ON users.id = addresses.userId
            LEFT JOIN companies ON users.id = companies.userId
        ');
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result = [];

        foreach ($users as $user) {
            $userId = $user['id'];
            if (!isset($result[$userId])) {
                $result[$userId] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'website' => $user['website'],
                    'address' => [
                        'street' => $user['street'],
                        'suite' => $user['suite'],
                        'city' => $user['city'],
                        'zipcode' => $user['zipcode'],
                        'geo' => [
                            'lat' => $user['geo_lat'],
                            'lng' => $user['geo_lng']
                        ]
                    ],
                    'company' => [
                        'name' => $user['company_name'],
                        'catchPhrase' => $user['catchPhrase'],
                        'bs' => $user['bs']
                    ]
                ];
            }
        }

        return array_values($result);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare('
            SELECT 
                users.*, 
                addresses.street, addresses.suite, addresses.city, addresses.zipcode, addresses.geo_lat, addresses.geo_lng,
                companies.name as company_name, companies.catchPhrase, companies.bs
            FROM users
            LEFT JOIN addresses ON users.id = addresses.userId
            LEFT JOIN companies ON users.id = companies.userId
            WHERE users.id = ?
        ');
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $result = [
                'id' => $user['id'],
                'name' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'website' => $user['website'],
                'address' => [
                    'street' => $user['street'],
                    'suite' => $user['suite'],
                    'city' => $user['city'],
                    'zipcode' => $user['zipcode'],
                    'geo' => [
                        'lat' => $user['geo_lat'],
                        'lng' => $user['geo_lng']
                    ]
                ],
                'company' => [
                    'name' => $user['company_name'],
                    'catchPhrase' => $user['catchPhrase'],
                    'bs' => $user['bs']
                ]
            ];

            return $result;
        }

        return null;
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
