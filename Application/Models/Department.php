<?php

namespace Application\Models;

use Application\Core\Model;

class Department extends Model
{
    public function addDepartment(array $params = []): void
    {
        $this->db->dbo
            ->prepare('INSERT INTO departaments (departament_name)
                                        VALUES (:departament_name)')
            ->execute($params);
    }

    public function getUsersEmail(array $params = []): array
    {
        $stmt = $this->db->dbo
            ->prepare('SELECT count(*) as total FROM departaments WHERE departament_name = :name');
        $stmt->execute($params);

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result[0];
    }
}
