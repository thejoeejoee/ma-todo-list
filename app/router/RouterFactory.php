<?php

namespace App;

use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


class RouterFactory {

    /**
     * @return Nette\Application\IRouter
     */
    public static function createRouter() {
        $router = new RouteList();
        $router[] = new Route('login', 'Sign:in');
        $router[] = new Route('<presenter>/<action>', 'Homepage:default');
        return $router;
    }

}
