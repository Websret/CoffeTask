<?php

namespace Application\Models;

use Application\Core\Model;

class Main extends Model
{
    public function getAllOrders(): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT orders.id, client_first_name, client_last_name, product_name, cost, first_name, last_name, departament_name, dateTime
                            FROM orders
                             JOIN products on products.id = orders.order_product_id
                             JOIN clients on clients.id = orders.order_client_id
                             JOIN workers on workers.id = orders.employee_id
                             JOIN departaments on workers.dep_id = departaments.id
                             ORDER BY orders.id ASC');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllClients(): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT id, client_first_name, client_last_name FROM clients');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllProducts(): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT id, product_name FROM products');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getOrder(array $params): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT orders.id, client_first_name, client_last_name, product_name, cost, first_name, last_name, departament_name, dateTime
                            FROM orders
                             JOIN products on products.id = orders.order_product_id
                             JOIN clients on clients.id = orders.order_client_id
                             JOIN workers on workers.id = orders.employee_id
                             JOIN departaments on workers.dep_id = departaments.id
                             WHERE orders.id = :id');
        $stmt->execute($params);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0];
    }

    public function updateOrder(array $params = []): void
    {
        $this->db->dbo
            ->prepare('UPDATE orders SET order_product_id = :order_product_id, order_client_id = :order_client_id WHERE id = :id')
            ->execute($params);
    }

    public function createOrder(array $params = []): void
    {
        $this->db->dbo
            ->prepare('INSERT INTO orders (order_product_id, order_client_id, employee_id, dateTime)
                                        VALUES (:order_product_id, :order_client_id, :employee_id, :dateTime)')
            ->execute($params);
    }

    public function getEmployee(array $params = []): array
    {
        $stmt = $this->db->dbo
            ->prepare("SELECT id FROM workers WHERE email = :email");
        $stmt->execute($params);
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $result[0];
    }

    public function deleteOrder(array $params = []): void
    {
        $this->db->dbo
            ->prepare('DELETE FROM orders WHERE id = :id')
            ->execute($params);
    }
}
