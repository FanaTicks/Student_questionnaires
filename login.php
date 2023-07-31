<?php
// login.php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "Questionnaires";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check login and password
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT Password_administrator  FROM Administrator  WHERE Login_administrator  = ?");
    $stmt->bind_param("s", $login);

    if ($stmt->execute()) {
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        print "$password  $hashed_password";

        // Verify password using password_verify
        if (password_verify($password, $hashed_password)) {
            // Login success
            header("Location: admin_dashboard.php"); // Redirect to admin dashboard
            exit;
        } else {
            echo "Неправильний логін або пароль";
        }
    } else {
        echo "Помилка запиту";
    }

    $stmt->close();
}

$conn->close();
?>
