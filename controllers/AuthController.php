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

    public function termsandconditions(Router $router)
    {
        $router->renderView('auth/termsAndConditions');
    }


    public function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: /');
    }

    public function signin(Router $router)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['role'])) {
            header('Location: /');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $router->renderView('auth/signin', $this->errors);
            exit;
        }
        $email = $_POST['email'];
        $password = $_POST['password'];
        $adminLogged = Admin::authenticate($email, $password);
        if ($adminLogged) {
            $_SESSION['role'] = 'admin';
            header('Location: /');
            exit;
        }
        $userLogged = User::authenticate($email, $password);
        if ($userLogged) {
            $_SESSION['role'] = 'user';
            $_SESSION['user'] = User::getOneByEmail($email);
            header('Location: /');
            exit;
        }
        $this->errors['loginErr'] = "Wrong login or password";
        //header('Location: /signin');
        $router->renderView('auth/signin', $this->errors);

    }

    public function signup(Router $router)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION['role'])) {
            header('Location: /');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $router->renderView('auth/signup', $this->errors);
            exit;
        }
        // get data from form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $dob = $_POST['dob'];
        $password = $_POST['password'];


        // check if user exists
        $userExists = User::getOneByEmail($email);
        if ($userExists) {
            $this->errors['signupErr'] = "User with this email already exists";
            $router->renderView('auth/signup', $this->errors);
            exit;
        }
        // create new user
        $user = new User(
            0,
            $name,
            $email,
            $password,
            true,
            $dob,
            $phone
        );
        $created = $user->create();
        if ($created) {
            $this->signin($router);
        } else {
            $this->errors['signupErr'] = "Something went wrong";
            $router->renderView('auth/signup', $this->errors);
        }

    }

    public static function checkAuth()
    {
//        if session is not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['role'])) {
            header('Location: /signin');
            exit;
        }
    }
}