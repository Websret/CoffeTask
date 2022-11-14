<?php

namespace Application\Helper;

class OrderTransformer
{
    public static function changeOrdersData(array $array): array
    {
        $ot = new OrderTransformer();
        $newArray = [];

        $key = 1;
        foreach ($array as $value) {
            $clientData = $ot->getClientData($value);
            $employeeData = $ot->getEmployeeData($value);
            $newArray[$key++] = [
                'id' => $value['id'],
                'clientName' => $clientData,
                'order' => $value['product_name'],
                'price' => $value['cost'],
                'employee' => $employeeData,
                'dateTime' => $value['dateTime'],
            ];
        }

        return $newArray;
    }

    public static function additionData(array $array1, array $array2): array
    {
        $ot = new OrderTransformer();
        $newArray1 = [];
        $newArray2 = [];
        foreach ($array1 as $value) {
            $clientsData = $ot->getClientData($value);
            $newArray1[$value['id']] = $clientsData;
        }
        foreach ($array2 as $value) {
            $product = $ot->getProduct($value);
            $newArray2[$value['id']] = $product;
        }
        return [
            'clients' => $newArray1,
            'products' => $newArray2,
        ];
    }

    private function getClientData(array $array): string
    {
        $data = "";
        foreach ($array as $key => $value) {
            if ($key == 'client_first_name') {
                $data .= $value . " ";
            }
            if ($key == 'client_last_name') {
                $data .= $value;
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

        return $data;
    }

    private function getProduct(array $array): string
    {
        $data = '';
        foreach ($array as $key => $value) {
            if ($key == 'product_name'){
                $data = $value;
            }
        }
        return $data;
    }
}
