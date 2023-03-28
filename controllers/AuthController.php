<?php

namespace app\controllers;
use app\Router;
use app\models\User;
use app\models\Admin;

class AuthController
{
    public array $errors = [
        'loginErr' => '',
        'signupErr' => ''
    ];

    /*public function login()
    {
        //echo "<script>console.log('AuthController::login()')</script>";
        $login = $_POST['login'];
        $password = $_POST['password'];
        // console log login and password
        //echo "<script>console.log('login: $login, password: $password')</script>";
        $adminLogged = Admin::authenticate($login, $password);
        if ($adminLogged) {
            $_SESSION['role'] = 'admin';
            header('Location: /');
            exit;
        }
        $userLogged = User::authenticate($login, $password);
        if ($userLogged) {
            $_SESSION['role'] = 'user';
            $_SESSION['user'] = User::getOneByEmail($login);
            header('Location: /');
            exit;
        }
        $this->errors['loginErr'] = "Wrong login or password";
        // alert error
        echo "<script>alert('Wrong login or password')</script>";
        header('Location: /signin');
    }*/

    public function register()
    {
        echo 'AuthController::register()';
    }

    public function logout()
    {
        echo 'AuthController::logout()';
    }

    public function signin(Router $router)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $router->renderView('auth/signin', $this->errors);
            exit;
        }
        $login = $_POST['login'];
        $password = $_POST['password'];
        $adminLogged = Admin::authenticate($login, $password);
        if ($adminLogged) {
            $_SESSION['role'] = 'admin';
            header('Location: /');
            exit;
        }
        $userLogged = User::authenticate($login, $password);
        if ($userLogged) {
            $_SESSION['role'] = 'user';
            $_SESSION['user'] = User::getOneByEmail($login);
            header('Location: /');
            exit;
        }
        $this->errors['loginErr'] = "Wrong login or password";
        //header('Location: /signin');
        $router->renderView('auth/signin', $this->errors);

    }

    public function signup(Router $router)
    {

        $router->renderView('auth/signup', $this->errors);
    }
}