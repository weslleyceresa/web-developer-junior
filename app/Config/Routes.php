<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/blog', 'BlogController::index');
$routes->get('/login', 'AuthController::login');
$routes->post('/do-login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->group('admin', function($routes) {
    $routes->get('posts', 'Admin\PostsController::index');
    $routes->get('posts/new', 'Admin\PostsController::new');
    $routes->post('posts/create', 'Admin\PostsController::create');
    $routes->get('posts/edit/(:num)', 'Admin\PostsController::edit/$1');
    $routes->post('posts/update/(:num)', 'Admin\PostsController::update/$1');
    $routes->get('posts/delete/(:num)', 'Admin\PostsController::delete/$1');
});
$routes->get('/register', 'UserController::registerForm');
$routes->post('/register', 'UserController::register');
