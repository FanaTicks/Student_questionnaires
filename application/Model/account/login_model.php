<?php
class LoginModel {
    private $conn;
    public function __construct() {
        $this->conn = new mysqli("localhost", "root", "admin", "Questionnaires");
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function authenticate($login, $password) {
        $stmt = $this->conn->prepare("SELECT Password_administrator FROM Administrator WHERE Login_administrator = ?");
        $stmt->bind_param("s", $login);
        if ($stmt->execute()) {
            $stmt->bind_result($hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                return "success";
            } else {
                return "Неправильний логін або пароль";
            }
        } else {
            return "Помилка запиту";
        }

        $stmt->close();
        $this->conn->close();
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $plain_password = $_POST['password'];
    $registerModel = new LoginModel();
    $response = $registerModel->authenticate($login, $plain_password);
    echo $response;
}
?>
