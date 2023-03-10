<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Login::index');

$routes->get('login/callback', 'Login::callback', ['as' => 'login.callback']);
$routes->get('login/auth', 'Login::auth', ['as' => 'login.auth']);
$routes->get('login/logout', 'Login::logout', ['as' => 'login.logout']);

$routes->group('patients', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Patient::index', ['as' => 'patient']);
    $routes->get('create', 'Patient::create', ['as' => 'patient.create']);
    $routes->post('store', 'Patient::store', ['as' => 'patient.store']);
    $routes->get('edit/(:num)', 'Patient::edit/$1', ['as' => 'patient.edit']);
    $routes->post('update/(:num)', 'Patient::update/$1', ['as' => 'patient.update']);
    $routes->get('delete/(:num)', 'Patient::delete/$1', ['as' => 'patient.delete']);
});

$routes->get('migrate', 'Migrate::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
