<?php
include_once 'Model/edit_test.php';

$testId = $_GET['testId'];
$testData = getTest($testId);
$questions = getQuestions($testId);

foreach ($questions as $key => $question) {
    $answers = getAnswers($question['Id_question']);
    $questions[$key]['answers'] = $answers;
}

$testData['questions'] = $questions;

header('Content-Type: application/json');
echo json_encode($testData);

?>