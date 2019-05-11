<?php

use Model\User;
use Controller\PageController;
use Controller\UserController;

var_dump(PageController::class);
return [
    User::class => function ($container) {
        $host = $container['config']['database']['host'];
        $driver = $container['config']['database']['driver'];
        $name = $container['config']['database']['name'];
        $user = $container['config']['database']['user'];
        $password = $container['config']['database']['password'];

        return new User($driver, $host, $name, $user, $password);
    },

    UserController::class => function ($container) {
        $userModel = $container[User::class]($container);
        return new UserController($userModel);
    },
    PageController::class => function ($container) {
        return new PageController();
    }
];