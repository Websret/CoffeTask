<?php

namespace Application\Models;

use Application\Core\Model;
use PDO;

class Client extends Model
{
    public function getAllClients(): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT * FROM clients');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClient(array $params = []): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT * FROM clients WHERE id = :id');
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result[0];
    }

    public function updateClient(array $params = []): void
    {
        $this->db->dbo
            ->prepare('UPDATE clients SET client_first_name = :first_name, client_last_name = :last_name,
               mobile_phone = :mobile_phone, email = :email WHERE id = :id')
            ->execute($params);
    }

    public function addClient(array $params = []): void
    {
        $this->db->dbo
            ->prepare('INSERT INTO clients (client_first_name, client_last_name, mobile_phone, email)
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

    public function deleteClient(array $params = []): void
    {
        $this->db->dbo
            ->prepare('DELETE FROM clients WHERE id = :id')
            ->execute($params);
    }
}
