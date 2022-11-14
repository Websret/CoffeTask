<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Helper\OrderTransformer;
use Application\Lib\Auth;
use Application\Lib\Validator;
use Application\Models\User;

class MainController extends Controller
{
    public function indexAction(): void
    {
        $this->isAuth();
        $data = $this->model->getAllOrders();
        $data = OrderTransformer::changeOrdersData($data);
        $vars = [
            'orderArray' => $data,
        ];
        $this->view->render($vars);
    }

    public function updateAction(array $params): void
    {
        $this->isAuth();
        $order = $this->model->getOrder(['id' => $params[0]]);
        $clients = $this->model->getAllClients();
        $products = $this->model->getAllProducts();
        $vars = [
            'orderArray' => $order,
            'dataArray' => OrderTransformer::additionData($clients, $products),
        ];
        $this->view->render($vars);
    }

    public function changeAction(): void
    {
        $validator = new Validator([
            'formId' => 'onlyInt|required',
            'formSelectClient' => 'onlyInt|required',
            'formSelectProduct' => 'onlyInt|required',
        ]);

        if (!$validator->validate()) {
            $this->view->redirect('/main/update/' . $_POST['formId']);
        }

        $params = [
            'order_product_id' => $_POST["formSelectProduct"],
            'order_client_id' => $_POST["formSelectClient"],
            'id' => $_POST["formId"],
        ];
        $this->model->updateOrder($params);
        $this->redirect();
    }

    public function createAction(): void
    {
        $this->isAuth();
        $clients = $this->model->getAllClients();
        $products = $this->model->getAllProducts();
        $vars = [
            'dataArray' => OrderTransformer::additionData($clients, $products),
        ];
        $this->view->render($vars);
    }

    public function addAction(): void
    {
        $validator = new Validator([
            'formSelectClient' => 'onlyInt|required',
            'formSelectProduct' => 'onlyInt|required',
        ]);

        if (!$validator->validate()) {
            $this->view->redirect('/main/create/');
        }

        $employee = $this->model->getEmployee(['email' => $_SESSION['data']['user']['email']]);

        $params = [
            'order_product_id' => $_POST["formSelectProduct"],
            'order_client_id' => $_POST["formSelectClient"],
            'employee_id' => $employee['id'],
            'dateTime' => date('Y-m-d H:i:s'),
        ];
        $this->model->createOrder($params);
        $this->redirect();
    }

    public function deleteAction(array $id): void
    {
        $this->model->deleteOrder(['id' => $id[0]]);
        $this->view->redirect('/');
    }

    private function isAuth(): void
    {
        if (!Auth::isAuth()) {
            $this->view->redirect('/user/login');
        }
    }

    private function redirect(): void
    {
        if (Auth::isAuth()) {
            $this->view->redirect('/');
        }
        $this->view->redirect('/main/update/' . $_POST['formId']);
    }
}
