<?php
session_start();
include '../../Model/account/register_admin.php';
include '../../Controller/main/header_controller.php';
include '../../Controller/main/footer_controller.php';
class RegisterAdminController {
    public function handleRegistration() {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $login = isset($_POST['login']) ? $_POST['login'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';

            $registerModel = new RegisterAdminModel();
            $response = $registerModel->registerAdmin($login, $password);

            if ($response === "Нового адміністратора додано успішно!") {
                // Очищаємо логін з сесії після успішної реєстрації
                unset($_SESSION['login']);
                header("Location: ../../Controller/account/login_controller.php");
                exit();
            } else {
                $this->showView($response); // Показуємо повідомлення про помилку
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
        include '../../View/account/register_view.php'; // Шлях до вашого виду реєстрації
    }
}

$controller = new RegisterAdminController();
$controller->handleRegistration();
?>
