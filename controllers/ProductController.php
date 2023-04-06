<?php

namespace app\controllers;

use app\models\Product;
use app\models\User;
use app\Router;

class ProductController
{
    public function index(Router $router)
    {
        $products = Product::getAll();

        $router->renderView('products/home', [
            'products' => $products,
        ]);
    }

    public function userProducts(Router $router)
    {
        AuthController::checkAuth();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $products = Product::getAllByUserId($_SESSION['user']['id']);

        $router->renderView('products/userProducts', [
            'products' => $products,
        ]);
    }

    public function productIndex($router)
    {
//        get the id from the url
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $product = Product::getOneById($id);
//            if the product is not found, redirect to the home page
            if (!$product) {
                header('Location: /products');
                exit;
            }
            $category_name = Product::getCategoryName($product['category_id']);
            $product['category_name'] = $category_name;
            $owner = User::getOneById($product['user_id']);
            $product['phone'] = $owner['phone'];
            $router->renderView('products/details', [
                'product' => $product,
            ]);

        } else {
            header('Location: /products');
        }
//        $router->renderView('products/details');
    }
    public function create($router)
    {
        AuthController::checkAuth();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $categories = Product::getAllCategoriesIdAndNames();
            $router->renderView('products/create', [
                'categories' => $categories,
                'user_id' => $_SESSION['user']['id'],
            ]);
            exit;
        }

        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $status = "available";
        $category_id = $_POST['category_id'];
        $user_id = $_POST['user_id'];
        $image = $_FILES['image'];

        $product = new Product(
            0,
            $name,
            $description,
            $price,
            $status,
            $category_id,
            $user_id
        );

        if (!is_dir(__DIR__ . '/../public/images')) {
            mkdir(__DIR__ . '/../public/images');
        }

        if (!is_dir(__DIR__ . '/../public/images/products')) {
            mkdir(__DIR__ . '/../public/images/products');
        }

        $product->image = 'images/products/' . UtilHelper::randomString(8) . '/' . $image['name'];
        mkdir(dirname(__DIR__ . "/../public/" . $product->image));
        move_uploaded_file($image['tmp_name'], __DIR__ . "/../public/" . $product->image);
//        check if image is present in the new folder
        if (!file_exists(__DIR__ . "/../public/" . $product->image)) {
            $router->renderView('products/create', [
                'errors' => ["Something went wrong"]
            ]);
            exit;
        }

        $created = $product->create();
        if ($created) {
            header('Location: /users/products');
            exit;
        } else {
            $router->renderView('products/create', [
                'errors' => ["Something went wrong"]
            ]);
        }
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