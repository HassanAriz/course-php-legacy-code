<?php

declare(strict_types=1);

namespace Controller;
// use Core\Routing;

class PageController
{
    public function defaultAction()
    {
        $view = new View("homepage", "back");
        $view->assign("pseudo", "prof");
    }
}