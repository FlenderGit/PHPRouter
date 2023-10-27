<?php

namespace PHPRouter\Classes;

use Closure;
use Method;
use PDO;
use PHPRouter\Attributes\Route;
use ReflectionClass;
use ReflectionMethod;

class Router {

    private $routes;
    private $base_url;
    private PDO $db;
    
    public function __construct($base_url = "/") {
        $this->routes = [];
        $this->base_url = $base_url;
        $this->db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
    }

    public function scanController(string $class):void {
        foreach((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            foreach($method->getAttributes(Route::class) as $attribute) {
                $route = $attribute->newInstance();
                $route->setCallable(Closure::fromCallable([new $class, $method->getName()]));
                $this->registerRoute($route);
            }
        }

    }

    public function get(string $path, $callback):void {
        $this->registerRoute(new Route("GET", $path, $callback));
    }

    public function post(string $path, $callback):void {
        $this->registerRoute(new Route("GET", $path, $callback));
    }

    public function run():void {
        $method = $_SERVER["REQUEST_METHOD"];
        $path = $_SERVER["REQUEST_URI"];
        foreach($this->routes[$method] as $route) {
            if($route->matchURI($path)) {
                if (AUTOWIRING) {
                    $this->autowiring($route);
                }
                $route->call();
                return;
            }
        }
        echo "404";
    }

    private function autowiring(Route $route) {
        

        $funct = new \ReflectionFunction($route->getCallable());

        if($funct->getNumberOfParameters() === count($route->getParams())) {
            return;
        }

        /*
        $t = array(
            'PDO' => $this->db
        );
        $route->addParams(array_intersect($funct->getParameters(), $t));
        */

        $route->addParams(array(
            'db' => $this->db
        ));
    }

    private function registerRoute(Route $route):void {
        $this->routes[$route->getMethod()][] = $route;
    }

}