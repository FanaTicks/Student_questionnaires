<?php
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$email = $_POST['email'];

$conn = new mysqli("localhost", "root", "admin", "Questionnaires");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Перевірити, чи існує користувач з такою поштою
$sql = "SELECT * FROM Listeners WHERE Contact_Information = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Такий користувач вже існує!";
        exit();
    }
}

// Зберегти нового користувача
$sql = "INSERT INTO Listeners (Name_listeners, Surname_listeners, Contact_Information) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $firstName, $lastName, $email);

if ($stmt->execute()) {
    echo "Користувач успішно збережений!";
} else {
    echo "Помилка збереження користувача: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
