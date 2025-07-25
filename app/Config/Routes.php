<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/blog', 'BlogController::index');
$routes->get('/blog/load-more', 'BlogController::loadMore');
$routes->get('/blog/create', 'BlogController::create');
$routes->post('/blog/save-draft', 'BlogController::saveDraft');
$routes->post('/blog/create', 'BlogController::savePost');
$routes->get('/login', 'AuthController::login');
$routes->post('/do-login', 'AuthController::doLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->group('admin', function($routes) {
});
$routes->get('/register', 'UserController::registerForm');
$routes->post('/register', 'UserController::register');