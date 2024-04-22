

<?php

// Make sure to replace 'dbhost', 'dbname', 'dbuser', 'dbpass' with your actual database details
$dbh = new mysqli("localhost", "root", "admin", "Questionnaires");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $dbh->prepare('SELECT * FROM Tests WHERE Id_tests = :testId');
    $stmt->execute([':testId' => $_GET['testId']]);
    $test = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch the questions
    $stmt = $dbh->prepare('SELECT * FROM Questions WHERE ID_tests = :testId');
    $stmt->execute([':testId' => $_GET['testId']]);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch the answers for each question
    foreach ($questions as &$question) {
        $stmt = $dbh->prepare('SELECT * FROM Answers WHERE ID_question = :questionId');
        $stmt->execute([':questionId' => $question['Id_question']]);
        $question['answers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $test['questions'] = $questions;

    echo json_encode($test);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // This is a simplified example. You'd want to validate the input data before updating the database.

    $data = json_decode(file_get_contents('php://input'), true);

    // Update Tests table
    // Update Questions table
    // Update Answers table

    // Respond with a message
    echo json_encode(['message' => 'Тест успішно оновлено']);
}

