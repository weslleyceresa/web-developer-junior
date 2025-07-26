<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/login', 'AuthController::login');
$routes->post('/do-login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');

$routes->get('/blog', 'BlogController::index');
$routes->get('/blog/load-more', 'BlogController::loadMore');
$routes->get('/blog/create', 'BlogController::create');
$routes->post('/blog/save-draft', 'BlogController::saveDraft');
$routes->post('/blog/create', 'PostsController::store');
$routes->get('/blog/search', 'BlogController::search');
$routes->get('/blog/(:segment)', 'BlogController::show/$1');


$routes->get('/user/profile', 'UserController::profile');
$routes->get('/user/edit', 'UserController::edit');
$routes->post('/user/update', 'UserController::update');
$routes->get('/user/(:segment)', 'UserController::publicProfile/$1');

$routes->group('admin', function ($routes) {
    $routes->get('dashboard', 'AdminController::dashboard');
    $routes->get('posts', 'PostsController::index');
    $routes->get('posts/new', 'PostsController::new');
    $routes->post('posts/create', 'PostsController::store');
    $routes->get('posts/edit/(:num)', 'PostsController::edit/$1');
    $routes->post('posts/update/(:num)', 'PostsController::update/$1');
});

$routes->get('/register', 'UserController::registerForm');
$routes->post('/register', 'UserController::register');
