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
        if (!isset($_GET['id'])) {
            header('Location: /products');
            exit;
        }
        $id = $_GET['id'];
        $oldProductArray = Product::getOneById($id);
        $categories = Product::getAllCategoriesIdAndNames();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        AuthController::checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (!Product::checkProductOwner($_SESSION['user']['id'], $_GET['id'])){
                header('Location: /products');
                exit;
            }

            $product = $oldProductArray;
            $router->renderView('products/update', [
                'product' => $product,
                'categories' => $categories,
            ]);
            exit;
        }
        $product = new Product(
            $id,
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['status'],
            $_POST['category_id'],
            $oldProductArray['user_id']
        );
//        check if image is present
        var_dump($_FILES['image']['name']);
        if (!empty($_FILES['image']['name'])) {
            $image = $_FILES['image'];
            if (!is_dir(__DIR__ . '/../public/images')) {
                mkdir(__DIR__ . '/../public/images');
            }

            if (!is_dir(__DIR__ . '/../public/images/products')) {
                mkdir(__DIR__ . '/../public/images/products');
            }
            $oldImg = __DIR__ . "/../public/" . $oldProductArray['imagePath'];


            $product->image = 'images/products/' . UtilHelper::randomString(8) . '/' . $image['name'];
            var_dump($product->image);
            var_dump($oldProductArray['imagePath']);
            mkdir(dirname(__DIR__ . "/../public/" . $product->image));
            move_uploaded_file($image['tmp_name'], __DIR__ . "/../public/" . $product->image);
//        check if image is present in the new folder
            if (!file_exists(__DIR__ . "/../public/" . $product->image)) {
                $product->image = $oldImg;
                $router->renderView('products/update', [
                    'errors' => ["Something went wrong"],
                    'product' => $oldProductArray,
                    'categories' => $categories,
                ]);
                exit;
            }
            //            delete old image and its folder
            if (file_exists($oldImg)) {
                unlink($oldImg);
                rmdir(dirname($oldImg));
            }
            $updated = $product->update();
            if ($updated) {
                header('Location: /users/products');
                exit;
            } else {
                $router->renderView('products/update', [
                    'errors' => ["Something went wrong"]
                ]);
            }

        } else {
            $product->image = $oldProductArray['imagePath'];
            $updated = $product->update();
            if ($updated) {
                header('Location: /users/products');
                exit;
            } else {
                $router->renderView('products/update', [
                    'errors' => ["Something went wrong"],
                    'product' => $oldProductArray,
                    'categories' => $categories,
                ]);
            }
        }
        $router->renderView('products/update');
    }

    public function delete()
    {
        if (!isset($_GET['id'])) {
            header('Location: /products');
            exit;
        }
        $id = $_GET['id'];
        $oldProductArray = Product::getOneById($id);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        AuthController::checkAuth();
        if (!Product::checkProductOwner($_SESSION['user']['id'], $_GET['id'])){
            echo "<script>alert('You are not the owner of this product!')</script>";
            header('Location: /products');
            exit;
        }
        $product = new Product(
            $id
        );
        $oldImg = __DIR__ . "/../public/" . $oldProductArray['imagePath'];
        $deleted = $product->delete();
//        ask user confirmation

        if ($deleted) {
            if (file_exists($oldImg)) {
                unlink($oldImg);
                rmdir(dirname($oldImg));
            }
            echo "<script>alert('Product deleted successfully!')</script>";
            header('Location: /users/products');
            exit;
        } else {
            echo "<script>alert('Something went wrong!')</script>";
            header('Location: /users/products');
            exit;
        }



    }




}