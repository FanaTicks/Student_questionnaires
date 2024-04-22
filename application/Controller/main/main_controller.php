<?php
session_start();
include '../../Model/account/login_model.php';
include '../../Controller/main/header_controller.php';
include '../../Controller/main/footer_controller.php';
class MainController{
    public function handleMain(){

            $headerController = new HeaderController();
            $header = $headerController->showHeader();
            $footerController = new FooterController();
            $footer = $footerController->showFooter();
            $this->showView();

    }
    private function showView($errorMessage = '') {
        include '../../View/main/main_admin.php';
    }

}
$controller = new MainController();
$controller->handleMain();
?>