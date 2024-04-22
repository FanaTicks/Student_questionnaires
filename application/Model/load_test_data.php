<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "Questionnaires";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => $conn->connect_error]));
}

if (isset($_POST['testId'])) {
    $testId = $_POST['testId'];

    $sql = "SELECT * FROM Tests WHERE Id_tests = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $testId);
    $stmt->execute();

    $result = $stmt->get_result();
    $test = $result->fetch_assoc();

    $sql = "SELECT * FROM Questions WHERE ID_tests = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $testId);
    $stmt->execute();

    $result = $stmt->get_result();
    $questions = $result->fetch_all(MYSQLI_ASSOC);

    foreach ($questions as $index => $question) {
        $sql = "SELECT * FROM Answers WHERE ID_question = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $question['ID_question']);
        $stmt->execute();

        $result = $stmt->get_result();
        $answers = $result->fetch_all(MYSQLI_ASSOC);
        $questions[$index]['answers'] = $answers;
    }

    $test['questions'] = $questions;

    echo json_encode($test);
}

$conn->close();
?>
