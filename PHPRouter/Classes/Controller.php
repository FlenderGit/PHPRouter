<?php
namespace PHPRouter\Classes;

use PHPRouter\Response\HtmlResponse;


abstract class Controller {

    public function render(string $template, array $context = []): void {
        foreach ($context as $key => $value) {
            $$key = $value;
        }
        require_once(dirname(__FILE__) ."\..\..\\templates\\$template.tpl.php");
    }    

}