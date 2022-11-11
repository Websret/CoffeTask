<?php

namespace Application\Helper;

class OrderTransformer
{
    public static function changeData(array $array): array
    {
        $ot = new OrderTransformer();

        foreach ($array as $value) {
            $clientData = $ot->getClientData($value);
            $employeeData = $ot->getEmployeeData($value);
        }
        return [];
    }

    private function getClientData(array $array): string
    {
        $data = '';
        foreach ($array as $key => $value) {
            if ($key == 'client_first_name' || $key == 'client_last_name') {
                $data .= $value . " ";
            }
        }
        return $data;
    }

    private function getEmployeeData(array $array): string
    {
        $data = '';
        foreach ($array as $key => $value) {
            if ($key == 'first_name' || $key == 'last_name') {
                $data .= $value . " ";
            }
            if ($key == 'departament_name') {
                $data .= "(" . $value . ")";
            }
        }
    }
}
