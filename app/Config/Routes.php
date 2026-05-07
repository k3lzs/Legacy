<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// --- Public / Authentication Routes ---
$routes->get('/', 'Home::index');
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('registration', 'AuthController::registration');
$routes->post('registration', 'AuthController::registrationPost');
$routes->get('logout', 'FeedController::logout');

// --- Feed & Social Routes ---
$routes->get('feed', 'FeedController::index');
$routes->post('post', 'FeedController::createPost');
$routes->post('follow/(:num)', 'ProfileController::follow/$1');
$routes->post('unfollow/(:num)', 'ProfileController::unfollow/$1');
$routes->post('comment', 'FeedController::addComment');

// --- Profile Routes (Order Matters!) ---
// 1. Static edit routes
$routes->get('profile/edit', 'ProfileController::edit');
$routes->post('profile/update', 'ProfileController::update');

// 2. Numeric ID route (used by your search results)
$routes->get('profile/(:num)', 'ProfileController::index/$1');

// 3. Username/Segment route (fallback for strings)
$routes->get('profile/(:segment)', 'ProfileController::view/$1');

// 4. Base profile route (logged-in user)
$routes->get('profile', 'ProfileController::index');