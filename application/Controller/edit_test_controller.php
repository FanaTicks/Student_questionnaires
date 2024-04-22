<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "Questionnaires";

// Створення з'єднання
$conn = new mysqli($servername, $username, $password, $dbname);

// Перевірка з'єднання
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

$sql = "SELECT Id_tests, Name_test FROM Tests";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()) {
    echo "<option value='" . $row["Id_tests"] . "'>" . $row["Name_test"] . "</option>";
}

$conn->close();
?>