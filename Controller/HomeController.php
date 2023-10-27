<?php

namespace App\Controller;

use PHPRouter\Attributes\Route;
use PHPRouter\Classes\Controller;
use PHPRouter\Response\JsonResponse;

class HomeController extends Controller {

    #[Route("GET", "/otakuiz/home/:id")]
    public function index(int $id) {
        return new JsonResponse(["id" => $id]);
    }

    #[Route("GET", "/otakuiz/home")]
    public function home() {
        return $this->render("home", [
            "title" => "Home Test"
        ]);
    }

}