<?php
use App\Controller\RandomController;

require_once(dirname(__FILE__)."/config.php");
require_once(dirname(__FILE__) ."/autoload.php");

use App\Controller\HomeController;
use PHPRouter\Response\HtmlResponse;
use PHPRouter\Classes\Router;

$router = new Router();

// $router->scanController(HomeController::class);
// $router->scanController(RandomController::class);

// $router->get("/otakuiz/home/:id", 'HomeController::index');

/*
$router->error(function(Exception $e) {
    return new HtmlResponse("<h1>{$e->getMessage()}</h1>", 403);
});
*/

$router->get("/otakuiz/quiz/:id", function($id) {
    return new HtmlResponse("<em>Je suis un test de réponse HTML : $id</em>");
});

$router->run();
