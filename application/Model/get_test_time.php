<?php
$testName = $_GET['Name_test'];

$conn = new mysqli("localhost", "root", "admin", "Questionnaires");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT Time_tests FROM Tests WHERE Name_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $testName);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['Time_tests'];
} else {
    echo "Помилка завантаження часу на тест: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
