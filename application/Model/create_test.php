<?php
$data = json_decode(file_get_contents('php://input'), true);

$conn = new mysqli("localhost", "root", "admin", "Questionnaires");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert test
$question_count = count($data['questions']);
$stmt = $conn->prepare("INSERT INTO Tests (Name_test, Question_quantity, Time_tests) VALUES (?, ?, ?)");
$stmt->bind_param("sii", $data['test_name'], $question_count, $data['test_time']);
if (!$stmt->execute()) {
    die("Error inserting test: " . $stmt->error);
}
$testId = $conn->insert_id;
$stmt->close();

// Insert questions and answers
foreach ($data['questions'] as $question) {
    $stmt = $conn->prepare("INSERT INTO Questions (Text_question, ID_tests) VALUES (?, ?)");
    $stmt->bind_param("si", $question['text'], $testId);
    if (!$stmt->execute()) {
        die("Error inserting question: " . $stmt->error);
    }
    $questionId = $conn->insert_id;
    $stmt->close();

    foreach ($question['answers'] as $answer) {
        $isCorrect = $answer['is_correct'] ? 1 : 0;
        $stmt = $conn->prepare("INSERT INTO Answers (Answers_text, ID_question, Tr_answer) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $answer['text'], $questionId, $isCorrect);
        if (!$stmt->execute()) {
            die("Error inserting answer: " . $stmt->error);
        }
        $stmt->close();
    }
}

echo "Тест успішно створено!";
$conn->close();
return "Тест успішно створено!";
?>
