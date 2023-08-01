<?php
class RegisterAdminModel {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "admin", "Questionnaires");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function register($login, $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO Administrator (Login_administrator, Password_administrator) VALUES (?, ?)");
        $stmt->bind_param("ss", $login, $hashed_password);

        if ($stmt->execute()) {
            return "Нового адміністратора додано успішно!";
        } else {
            return "Помилка: " . $stmt->error;
        }

        $stmt->close();
        $this->conn->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $plain_password = $_POST['password'];

    $registerModel = new RegisterAdminModel();
    $response = $registerModel->register($login, $plain_password);

    echo $response;
}
?>
