<?php

namespace PHPRouter\Exception;
use Exception;

class PageNotFoundException extends Exception {
 
    function __construct() {
        parent::__construct("Page not found", 404);
    }

}