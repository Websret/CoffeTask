<?php

namespace Application\Models;

use Application\Core\Model;
use PDO;

class Product extends Model
{
    public function addProduct(array $params = []): void
    {
        $this->db->dbo
            ->prepare('INSERT INTO products (product_name, cost) VALUES (:product_name, :cost)')
            ->execute($params);
    }

    public function getUsersEmail(array $params = []): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT count(*) as total FROM products WHERE product_name = :name');
        $stmt->execute($params);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }
}
