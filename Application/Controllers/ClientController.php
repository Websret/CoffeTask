<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Lib\Auth;
use Application\Lib\Validator;
use Application\Models\Client;

class ClientController extends Controller
{
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
