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

		$backRouter[] = new Route("register", "Login:register");
		$backRouter[] = new Route("login", "Login:login");


		$backRouter[] = new Route("admin/page/<name>", "Page:edit");
		$backRouter[] = new Route("admin/<presenter>/<action>[/<id>][/<name>]", array(
			"presenter" => "Dashboard",
			"action" => "default",
		));

		$frontRouter = new RouteList("Front");


		$frontRouter[] = new Route("<name>", array(
			"presenter" => "Page",
			"action" => "default",
			"name" => [
				Route::FILTER_TABLE => [
					"o-projektu" => "about",
					"odkazy" => "links"
				]
			]
		));
		$frontRouter[] = new Route("navod/<id>", "Tutorial:detail");
		$frontRouter[] = new Route("<presenter>/<action>[/<id>]", array(
			"presenter" => [
				Route::VALUE => "Homepage",
				Route::FILTER_TABLE => [
					"navody" => "Tutorial",
				],
			],
			"action" => "default",
		));

		$router[] = $backRouter;
		$router[] = $frontRouter;


		return $router;
	}

}
