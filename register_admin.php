<?php
// register_admin.php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $plain_password = $_POST['password'];
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT); // Hash the password

    // Prepare statement to add the new administrator
    $stmt = $conn->prepare("INSERT INTO Administrator  (Login_administrator , Password_administrator ) VALUES (?, ?)");
    $stmt->bind_param("ss", $login, $hashed_password);

    if ($stmt->execute()) {
        echo "Нового адміністратора додано успішно!";
    } else {
        echo "Помилка: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
