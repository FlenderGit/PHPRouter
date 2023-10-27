<?php

namespace App\Controller;

use PDO;
use PHPRouter\Attributes\Route;
use PHPRouter\Classes\Controller;
use PHPRouter\Response\JsonResponse;

class RandomController extends Controller {

    #[Route("GET", "/otakuiz/api/random")]
    public function index(PDO $db) {

        $stmt = $db->query("SELECT id, text FROM question ORDER BY RAND() LIMIT 1");
        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt2 = $db->prepare("SELECT text FROM answer WHERE id_question = :id");
        $stmt2->execute(["id" => $question["id"]]);
        $question["answers"] = $stmt2->fetchAll(PDO::FETCH_ASSOC);

        return new JsonResponse($question);
    }

}