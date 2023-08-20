<?php
function getQuestionsByTestName($testName, $conn) {
// Знаходимо ідентифікатор тесту за його назвою
$sql = "SELECT Id_tests FROM Tests WHERE Name_test = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $testName);
$stmt->execute();
$stmt->bind_result($testId);
$stmt->fetch();
$stmt->close();

// Отримуємо питання та відповіді для вибраного тесту в випадковому порядку
$sql = "SELECT q.Id_question, q.Text_question, a.Id_answer, a.Answers_text, a.Tr_answer FROM Questions q JOIN Answers a ON q.Id_question = a.ID_question WHERE q.ID_tests = ? ORDER BY RAND()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $testId);

if ($stmt->execute()) {
$result = $stmt->get_result();
$questions = [];

while ($row = $result->fetch_assoc()) {
$questionId = $row['Id_question'];
if (!isset($questions[$questionId])) {
$questions[$questionId] = [
'id' => $questionId,
'text' => $row['Text_question'],
'answers' => []
];
}

$questions[$questionId]['answers'][] = [
'id' => $row['Id_answer'],
'text' => $row['Answers_text'],
'correct' => $row['Tr_answer']
];
}

return $questions;
} else {
return [];
}
}
?>