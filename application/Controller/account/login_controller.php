<?php
session_start();
include '../../Model/account/login_model.php';
include '../../Controller/main/header_controller.php';
include '../../Controller/main/footer_controller.php';
class LoginController{
    public function handleLogin(){
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = isset($_POST['login']) ? $_POST['login'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            $loginModel = new LoginModel();
            $response = $loginModel->authenticate($login, $password);

            if ($response === "success") {
                $_SESSION['authorized'] = true;
                $authorization = true;
                header("Location: ../../Controller/main/main_controller.php");
                exit();
            } else {
                $this->showView("Неправильний логін або пароль. Будь ласка, спробуйте знову.");
                exit();
            }
        } else {
            $headerController = new HeaderController();
            $header = $headerController->showHeader();
            $footerController = new FooterController();
            $footer = $footerController->showFooter();
            $this->showView();
        }
    }
    private function showView($errorMessage = '') {
        include '../../View/account/login_view.php';
    }


}
$controller = new LoginController();
$controller->handleLogin();
?>