<?php

namespace Application\Models;

use Application\Core\Model;

class Main extends Model
{
    public function getAllOrders(): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT client_first_name, client_last_name, product_name, cost, first_name, last_name, departament_name, dateTime
                            FROM orders
                             JOIN products on products.id = orders.order_product_id
                             JOIN clients on clients.id = orders.order_client_id
                             JOIN workers on workers.id = orders.employee_id
                             JOIN departaments on workers.dep_id = departaments.id');
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
