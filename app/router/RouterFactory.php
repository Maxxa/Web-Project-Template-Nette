<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;
use Nette\Application\IRouter;

class RouterFactory {

    /**
     * @return IRouter
     */
    public static function createRouter() {
        $router = new RouteList();
        $router[] = self::createAdminRoute();
        $router[] = self::createFrontRoute();
        return $router;
    }

    /**
     * @return array|RouteList
     */
    private static function createAdminRoute() {
        $adminList = new RouteList("Admin");
        $adminList[] = new Route('admin/<presenter>/<action>[/<id>]', "Homepage:default");
        return $adminList;
    }

    /**
     * @return array|RouteList
     */
    private static function createFrontRoute() {
        $frontList = new RouteList("Front");
        $frontList[] = new Route('<presenter>/<action>[/<id>]', "Homepage:default");
        return $frontList;
    }

}
