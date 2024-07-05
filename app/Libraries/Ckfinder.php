<?php

namespace App\Libraries;

class Ckfinder {

    public function index() {
        include "assets/admin/ckfinder/ckfinder.php";
    }

    public function connector() {
        include "assets/admin/ckfinder/core/connector/php/connector.php";
    }
}