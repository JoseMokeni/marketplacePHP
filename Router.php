<?php

namespace app;

use app\controllers\AuthController;
use app\controllers\ProductController;
use app\controllers\UserController;
use app\controllers\AdminController;


class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];
    public function get($url, $fn){
        $this->getRoutes[$url] = $fn;
    }
    public function post($url, $fn){
        $this->postRoutes[$url] = $fn;
    }
    public function resolve(){
        $url = $_SERVER['REQUEST_URI'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];
        if($method === 'GET'){
            $fn = $this->getRoutes[$url] ?? null;
        } else {
            $fn = $this->postRoutes[$url] ?? null;
        }
        if($fn){
            call_user_func($fn, $this);
        } else {
            $this->renderView('notfound');
            // echo "Page not found";
        }
    }

    public function renderView($view, $params = []){
        // console log params
        $paramsString = json_encode($params);
        echo "<script>console.log('params: $paramsString')</script>";
        foreach($params as $key => $value){
            $$key = $value;
            echo "<script>console.log('key: $key, value: $value')</script>";
        }
        ob_start();

        include_once __DIR__ . "/views/$view.php";
        $content = ob_get_clean();
        include_once __DIR__."/views/_layout.php";
    }

}