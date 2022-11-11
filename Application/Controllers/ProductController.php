<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Lib\Auth;
use Application\Lib\Validator;
use Application\Models\Product;

class ProductController extends Controller
{
    public function createAction(): void
    {
        $this->isAuth();
        $this->view->render();
    }

    public function addAction(): void
    {
        $validator = new Validator([
            'formProductName' => 'onlyString|min:3|emailExist:' . Product::class . ',name|required',
            'formProductCost' => 'onlyInt|minValue:5|required',
        ]);

        if (!$validator->validate()) {
            $this->view->redirect('/product/create');
        }

        $params = [
            'product_name' => $_POST["formProductName"],
            'cost' => $_POST["formProductCost"],
        ];
        $this->model->addProduct($params);
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
