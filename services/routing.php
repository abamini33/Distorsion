<?php

declare(strict_types=1);

require_once './configs/settings.php';

class Router
{
    private $controllersBasePath;
    private $controller;

    public function __construct($page)
    {
        $this->controllersBasePath = 'controllers/';
        $this->controller =  AVAILABLE_ROUTES[$page];
    }

    public function autoloadController(): void
    {
        include $this->controllersBasePath . $this->controller . ".php";
    }

    public function getController(): Object
    {
        return new $this->controller;
    }

    
}



