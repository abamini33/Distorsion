<?php

declare(strict_types=1);
require_once 'ControllerInterface.php';


class AboutController implements ControllerInterface
{
// La fonction display() est en charge de l'affichage dans chacun des controllers.
    public function display()
    {
        $view = 'views/about/about.phtml';
        require './views/layout.phtml';
    }
}
