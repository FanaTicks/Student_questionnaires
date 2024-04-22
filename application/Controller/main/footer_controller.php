<?php
session_start();

class FooterController {
    public function showFooter() {
        include '../../view/main/footer_view.php';
    }
}

