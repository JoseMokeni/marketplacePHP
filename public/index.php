<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use app\Router;
    use app\controllers\ProductController;
    use app\controllers\UserController;
    use app\controllers\AdminController;
    use app\controllers\AuthController;



    $router = new Router();

    // main and product routes
    $router->get('/', [new ProductController(), 'index']);
    $router->get('/products', [new ProductController(), 'index']);
    $router->get('/product', [new ProductController(), 'productIndex']);
    $router->get('/products/create', [new ProductController(), 'create']);
    $router->post('/products/create', [new ProductController(), 'create']);
    $router->get('/products/update', [new ProductController(), 'update']);
    $router->post('/products/update', [new ProductController(), 'update']);
    $router->post('/products/delete', [new ProductController(), 'delete']);
    $router->get('/products/delete', [new ProductController(), 'delete']);

    //user routes
    $router->get('/users', [new UserController(), 'index']);
    $router->get('/users/create', [new UserController(), 'create']);
    $router->post('/users/create', [new UserController(), 'create']);
    $router->get('/users/update', [new UserController(), 'update']);
    $router->post('/users/update', [new UserController(), 'update']);
    $router->post('/users/delete', [new UserController(), 'delete']);
    $router->get('/users/deactivate', [new UserController(), 'deactivate']);
    $router->post('/users/deactivate', [new UserController(), 'deactivate']);
    $router->get('/users/activate', [new UserController(), 'activate']);
    $router->post('/users/activate', [new UserController(), 'activate']);

    //admin routes

    $router->get('/admin', [new AdminController(), 'index']);
    $router->get('/admin/update', [new AdminController(), 'update']);

    //login routes
    /*$router->post('/login', [new AuthController(), 'login']);
    $router->post('/register', [new AuthController(), 'register']);*/
    $router->get('/signin', [new AuthController(), 'signin']);
    $router->post('/signin', [new AuthController(), 'signin']);
    $router->get('/signup', [new AuthController(), 'signup']);
    $router->post('/signup', [new AuthController(), 'signup']);
    $router->get('/logout', [new AuthController(), 'logout']);



    $router->resolve();

