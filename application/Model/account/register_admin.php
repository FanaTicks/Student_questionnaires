<?php
class RegisterAdminModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "admin", "Questionnaires");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Функція для валідації введених даних
    private function validateInput($login, $password) {
        // Перевірка, чи поля не пусті
        if (empty($login) || empty($password)) {
            return "Логін та пароль не можуть бути пустими.";
        }

        // Перевірка довжини пароля
        if (strlen($password) < 8) {
            return "Пароль повинен бути не менше 8 символів.";
        }

        // Перевірка на спеціальні вимоги до пароля може бути додана тут

        return true;
    }

    public function registerAdmin($login, $password) {
        // Виклик функції валідації
        $validationResult = $this->validateInput($login, $password);
        if ($validationResult !== true) {
            return $validationResult;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO Administrator (Login_administrator, Password_administrator) VALUES (?, ?)");
        $stmt->bind_param("ss", $login, $hashed_password);

        if ($stmt->execute()) {
            $stmt->close();
            $this->conn->close();
            return "Нового адміністратора додано успішно!";
        } else {
            $error = $stmt->error;
            $stmt->close();
            $this->conn->close();
            return "Помилка: " . $error;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = isset($_POST['login']) ? $_POST['login'] : '';
    $plain_password = isset($_POST['password']) ? $_POST['password'] : '';

    $registerModel = new RegisterAdminModel();
    $response = $registerModel->registerAdmin($login, $plain_password);

    echo $response;
}
?>
