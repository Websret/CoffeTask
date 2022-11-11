<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Lib\Auth;
use Application\Lib\Validator;
use Application\Models\Department;

class DepartmentController extends Controller
{
    public function createAction(): void
    {
        $this->isAuth();
        $this->view->render();
    }

    public function addAction(): void
    {
        $validator = new Validator([
            'formFirstName' => 'onlyString|min:3|emailExist:' . Department::class . ',name|required',
        ]);

        if (!$validator->validate()) {
            $this->view->redirect('/department/create');
        }

        $params = [
            'departament_name' => $_POST["formFirstName"],
        ];
        $this->model->addDepartment($params);
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
        $this->view->redirect('/user/login');
    }
}
