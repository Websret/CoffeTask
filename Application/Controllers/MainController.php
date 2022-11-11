<?php

namespace Application\Controllers;

use Application\Core\Controller;
use Application\Helper\OrderTransformer;
use Application\Lib\Auth;
use Application\Lib\FileSystem;

class MainController extends Controller
{
    public function indexAction(): void
    {
        $this->isAuth();
        $data = $this->model->getAllOrders();
        $data = OrderTransformer::changeData($data);
        $vars = [
            'orderArray' => $data,
        ];
        $this->view->render($vars);
    }

    public function uploadAction(): void
    {
        $data = FileSystem::uploadFile();
        $vars = [
            'dataArray' => $data,
        ];
        $this->view->render($vars);
    }

    private function isAuth(): void
    {
        if (!Auth::isAuth()) {
            $this->view->redirect('/user/login');
        }
    }
}
