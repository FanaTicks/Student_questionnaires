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

$test_id = $_POST["test_id"];

// SQL запит на видалення тесту. Допоміжні записи видалятимуться автоматично завдяки ON DELETE CASCADE
$sql = "DELETE FROM Tests WHERE Id_tests = $test_id";

if ($conn->query($sql) === TRUE) {
    header("Location: ../View/main_admin.php"); // Перенаправлення на сторінку main_admin.php
    echo "Тест успішно видалено!";
    exit; // Зупинити виконання скрипта після перенаправлення
} else {
    header("Location: main_admin.php"); // Перенаправлення на сторінку main_admin.php
    echo "Помилка при видаленні тесту: " . $conn->error;
}

$conn->close();
?>
