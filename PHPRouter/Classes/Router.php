<?php

namespace PHPRouter\Classes;

use Closure;
use Exception;
use PDO;
use PHPRouter\Attributes\Route;
use PHPRouter\Exception\PageNotFoundException;
use PHPRouter\Response\HtmlResponse;
use ReflectionClass;
use ReflectionMethod;

class Router {

    private $routes;
    private $base_url;
    private $route_error;
    
    public function __construct($base_url = "/") {
        $this->routes = [];
        $this->base_url = $base_url;
        $this->route_error = null;

        if (AUTOLOAD_CONTROLLER) {
            $this->autoLoadControllers();
        }
    }

    public function scanController(string $class):void {
        foreach((new ReflectionClass($class))->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            $class = new $class;
            foreach($method->getAttributes(Route::class) as $attribute) {
                $route = $attribute->newInstance();
                $route->setCallable(Closure::fromCallable([$class, $method->getName()]));
                $this->registerRoute($route);
            }
        }

    }

    public function autoLoadControllers():void {
        foreach (glob("Controller/*.php") as $filename) {
            $class = "App\Controller\\".basename($filename, ".php");
            $this->scanController($class);
        }
    }

    public function get(string $path, $callback):void {
        $this->registerRoute(new Route("GET", $path, $callback));
    }

    public function post(string $path, $callback):void {
        $this->registerRoute(new Route("GET", $path, $callback));
    }

    public function error($callback):void {
        $this->route_error = new Route("GET", "", $callback);
    }

    public function run():void {
        $method = $_SERVER["REQUEST_METHOD"];
        $path = $_SERVER["REDIRECT_URL"] ?? $_SERVER['REQUEST_URI'];
        foreach($this->routes[$method] as $route) {
            if($route->matchURI($path)) {
                
                if (AUTOWIRING) {
                    $this->autowiring($route);
                }
                
                try {
                    $route->call();
                } catch (Exception $e) {
                    $this->handleError($e);
                }
                
                return;
            }
        }
        
        $this->handleError(new PageNotFoundException());
    }

    public function handleError(Exception $e):void {
        if ($this->route_error) {
            $this->route_error->addParams(array($e));
            $this->route_error->call();
        } else {
            (new HtmlResponse("<h1>{$e->getMessage()}</h1>", $e->getCode()))->send();
        }
    }

    private function autowiring(Route $route) {
        

        $funct = new \ReflectionFunction($route->getCallable());

        if($funct->getNumberOfParameters() === count($route->getParams())) {
            return;
        }

        $list_params = array();

        foreach($funct->getParameters() as $param) {

            $parameter = null;

            switch($param->getType()->getName()) {
                case "PDO":
                    $parameter = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
                    break;
                default:
                    throw new Exception("Type not found");
            }

            $list_params[$param->getName()] = $parameter;
        }

        $route->addParams($list_params);
        
    }

    private function registerRoute(Route $route):void {
        $this->routes[$route->getMethod()][] = $route;
    }

}