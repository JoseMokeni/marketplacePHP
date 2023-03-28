<?php

namespace app\controllers;

use app\Router;

class ProductController
{
    public function index(Router $router)
    {
        $router->renderView('products/index', [
            'name' => 'John',
            'surname' => 'Doe'
        ]);
    }

    public function productIndex($router)
    {
        $router->renderView('products/productIndex');
    }
    public function create($router)
    {
        $router->renderView('products/create');
    }

    public function update($router)
    {
        $router->renderView('products/update');
    }

    public function delete()
    {
        echo 'ProductController::delete()';
    }


}