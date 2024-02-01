<?php

declare(strict_types=1);

require_once './services/routing.php'; 
require_once './configs/settings.php';

// Determine the requested page
$page = $_GET['page'] ?? DEFAULT_ROUTE;

// Create router
$router = new Router($page);

// Include controller
$router->autoloadController();

// Instanciate controller
$controllerInstance = $router->getController();

// Depending on the page, handle accordingly
if ($page === 'toggle_pin') {
    $controllerInstance->togglePin();
} else {
    $controllerInstance->display();
}
