<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Helper\OrderTransformer;
use Application\Lib\Auth;
use Application\Lib\Validator;
use Application\Models\Client;

class ClientController extends Controller
{
    public function showAction(): void
    {
        $this->isAuth();
        $clients = $this->model->getAllClients();
        $vars = [
            'clientsArray' => $clients,
        ];
        $this->view->render($vars);
    }

    public function createAction(): void
    {
        $this->isAuth();
        $this->view->render();
    }

    public function addAction(): void
    {
        $validator = new Validator([
            'formFirstName' => 'onlyString|min:2|required',
            'formLastName' => 'onlyString|min:2|required',
            'formMobilePhone' => 'isMobilePhone|required',
            'formEmail' => 'isEmail|min:4|emailExist:' . Client::class . ',email|required',
        ]);

        if (!$validator->validate()) {
            $this->view->redirect('/client/create');
        }

        $params = [
            'first_name' => $_POST["formFirstName"],
            'last_name' => $_POST["formLastName"],
            'mobile_phone' => $_POST["formMobilePhone"],
            'email' => $_POST["formEmail"],
        ];
        $this->model->addClient($params);
        $this->redirect();
    }

    public function updateAction(array $params): void
    {
        $this->isAuth();
        $order = $this->model->getClient(['id' => $params[0]]);
        $vars = [
            'clientArray' => $order,
        ];
        $this->view->render($vars);
    }

    public function changeAction(): void
    {
        $validator = new Validator([
            'formId' => 'onlyInt|required',
            'formFirstName' => 'onlyString|min:2|required',
            'formLastName' => 'onlyString|min:2|required',
            'formMobilePhone' => 'isMobilePhone|required',
            'formEmail' => 'required',
        ]);

        if (!$validator->validate()) {
            $this->view->redirect('/client/update/' . $_POST['formId']);
        }

        $params = [
            'id' => $_POST["formId"],
            'first_name' => $_POST["formFirstName"],
            'last_name' => $_POST["formLastName"],
            'mobile_phone' => $_POST["formMobilePhone"],
            'email' => $_POST["formEmail"],
        ];
        $this->model->updateClient($params);
        $this->view->redirect('/client/show');
    }

    public function deleteAction(array $id): void
    {
        $this->model->deleteClient(['id' => $id[0]]);
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
        $this->view->redirect('/client/create');
    }
}
