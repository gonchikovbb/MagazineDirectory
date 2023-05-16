<?php

include '../Autoloader.php';

Autoloader::register(dirname(__DIR__));

use banana\App;
use banana\Container;
use banana\Controllers\AuthorController;
use banana\Controllers\MagazineController;

$dependencies = require_once '../Configs/dependencies.php';
$configs = require_once '../Configs/settings.php';

$data = array_merge($configs, $dependencies);

$container = new Container($data);

$app = new App($container);

$app->post('/author/add', [AuthorController::class, 'add']);
$app->post('/author/update', [AuthorController::class, 'update']);
$app->post('/author/delete', [AuthorController::class, 'delete']);
$app->get('/author/list', [AuthorController::class, 'index']);

$app->post('/magazine/add', [MagazineController::class, 'add']);
$app->post('/magazine/update', [MagazineController::class, 'update']);
$app->post('/magazine/delete', [MagazineController::class, 'delete']);
$app->get('/magazine/list', [MagazineController::class, 'index']);

$app->run();