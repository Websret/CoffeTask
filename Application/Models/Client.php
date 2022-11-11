<?php

namespace Application\Models;

use Application\Core\Model;
use PDO;

class Client extends Model
{
    public function addClient(array $params = []): void
    {
        $this->db->dbo
            ->prepare('INSERT INTO clients (first_name, last_name, mobile_phone, email)
                                        VALUES (:first_name, :last_name, :mobile_phone, :email)')
            ->execute($params);
    }

    public function getUsersEmail(array $params = []): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT count(*) as total FROM clients WHERE email = :email');
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }
}
