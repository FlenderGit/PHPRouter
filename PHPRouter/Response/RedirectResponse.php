<?php

namespace PHPRouter\Response;

class RedirectResponse extends Response {

    public function __construct($url, int $status = 302) {
        parent::__construct("", $status, [
            "Location: $url"
        ]);
    }

}