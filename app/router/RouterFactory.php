<?php

namespace App;

use Nette;
use Nette\Application\Routers\RouteList;
use Nette\Application\Routers\Route;


class RouterFactory
{
	use Nette\StaticClass;

	/**
	 * @return Nette\Application\IRouter
	 */
	public static function createRouter()
	{
		$router = new RouteList;

		$backRouter = new RouteList("Back");
		$backRouter[] = new Route("admin/<presenter>/<action>[/<id>]", array(
			"presenter" => "Dashboard",
			"action" => "default",
		));

		$frontRouter = new RouteList("Front");
		$frontRouter[] = new Route("<presenter>/<action>[/<id>]", array(
			"presenter" => [
				Route::VALUE => "Homepage",
				Route::FILTER_TABLE => [
					"navody" => "Tutorial",
					"odkazy" => "Link",
					"o-projektu" => "About",
				],
			],
			"action" => "default",
		));

		$router[] = $backRouter;
		$router[] = $frontRouter;


		return $router;
	}

}
