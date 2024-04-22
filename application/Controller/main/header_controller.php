<?php
session_start();

class HeaderController {
    public function showHeader() {
        include '../../view/main/header_view.php';
    }
}

