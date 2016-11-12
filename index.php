<?php

define("APP_DIR", __DIR__ . "/app/");

$container = require __DIR__ . '/app/bootstrap.php';

$container->getByType(Nette\Application\Application::class)
	->run();
