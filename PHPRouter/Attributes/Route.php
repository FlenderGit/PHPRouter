<?php

namespace PHPRouter\Attributes;
use AbstractResponse;
use Attribute;
use PHPRouter\Response\JsonResponse;
use PHPRouter\Response\Response;


#[Attribute(Attribute::TARGET_METHOD)]
class Route {

    private string $method;
    private $path;
    private $callable;
    private $params;

    public function __construct(string $method, string $path, $callable = null) {
        $this->method = $method;
        $this->path = $path;
        $this->callable = $callable;
        $this->params = [];
    }

    public function getMethod():string {
        return $this->method;
    }

    public function matchURI(string $uri): bool {
        $path = preg_replace('#:([\w]+)#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if(!preg_match($regex, $uri, $matches)) {
            return false;
        }
        array_shift($matches);

        $params = [];
        preg_match_all('#:([\w]+)#', $this->path, $paramNames);
        foreach ($paramNames[1] as $index => $paramName) {
            $params[$paramName] = $matches[$index];
        }

        $this->params = $params;

        return true;
    }

    public function call() {
        $response = call_user_func_array($this->callable, $this->params);
        if($response instanceof Response) {
            $response->send();
        } else {
            echo $response;
        }
    }

    public function getParams():array {
        return $this->params;
    }

    public function addParams($params):void {
        $this->params += $params;
    }

    public function getCallable() {
        return $this->callable;
    }

    public function setCallable($callable):void {
        $this->callable = $callable;
    }

}
