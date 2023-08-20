<?php
// Підключення до бази даних
$conn = new mysqli("localhost", "root", "admin", "Questionnaires");
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

$test_id = $_GET['test_id'];
$ID_listeners = $_GET['ID_listeners'];
$ID_Results = $_GET['ID_Results'];

function executeQuery($stmt, $query) {
    global $conn;
    if (!$stmt) {
        die("Помилка підготовки запиту: " . $query . " - " . $conn->error);
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Fetch the test details
$test_query = "SELECT * FROM Tests WHERE ID_tests = ?";
$stmt = $conn->prepare($test_query);
$stmt->bind_param("i", $test_id);
$test_result = executeQuery($stmt, $test_query);
$test_details = $test_result->fetch_assoc();

// Fetch the user name and surname
$name_query = "SELECT Name_listeners, Surname_listeners FROM Listeners WHERE Id_listeners = ?";
$stmt = $conn->prepare($name_query);
$stmt->bind_param("i", $ID_listeners);
$name_result = executeQuery($stmt, $name_query);
$user_name = $name_result->fetch_assoc();

// Fetch the user's answers IDs from UserAnswers table based on ID_Results
$userAnswersQuery = "SELECT ID_answer FROM UserAnswers WHERE ID_Results = ?";
$stmt = $conn->prepare($userAnswersQuery);
$stmt->bind_param("i", $ID_Results);
$userAnswersResult = executeQuery($stmt, $userAnswersQuery);
$userAnswersIDs = [];
while ($row = $userAnswersResult->fetch_assoc()) {
    $userAnswersIDs[] = $row['ID_answer'];
}

// Fetch the user's answers
$answers_query = "SELECT * FROM Results WHERE ID_tests = ? AND ID_listeners = ?";
$stmt = $conn->prepare($answers_query);
$stmt->bind_param("ii", $test_id, $ID_listeners);
$answers_result = executeQuery($stmt, $answers_query);
$user_answers = $answers_result->fetch_assoc();

// Fetch the correct answers
$correct_query = "SELECT a.Id_answer, a.Answers_text
FROM Answers a
INNER JOIN Questions q ON a.ID_question = q.Id_question
WHERE q.ID_tests = ? AND a.Tr_answer = 1
";
$stmt = $conn->prepare($correct_query);
$stmt->bind_param("i", $test_id);
$correct_result = executeQuery($stmt, $correct_query);
$correct_answers = [];
while ($row = $correct_result->fetch_assoc()) {
    $correct_answers[] = $row;
}

// Fetch all questions and their answers for the test
$questions_query = "SELECT q.Id_question, q.Text_question, a.Id_answer, a.Answers_text, a.Tr_answer
FROM Questions q
LEFT JOIN Answers a ON q.Id_question = a.ID_question
WHERE q.ID_tests = ?
ORDER BY q.Id_question, a.Id_answer
";
$stmt = $conn->prepare($questions_query);
$stmt->bind_param("i", $test_id);
$questions_result = executeQuery($stmt, $questions_query);
$questions = [];
$current_question_id = null;
while ($row = $questions_result->fetch_assoc()) {
    if ($current_question_id !== $row['Id_question']) {
        $current_question_id = $row['Id_question'];
        $questions[$current_question_id] = [
            'question' => $row['Text_question'],
            'answers' => []
        ];
    }
    $questions[$current_question_id]['answers'][] = [
        'id' => $row['Id_answer'],
        'text' => $row['Answers_text'],
        'correct' => $row['Tr_answer']
    ];
}

$conn->close();

// Prepare the response
$response = array(
    "testDetails" => $test_details,
    "userName" => $user_name["Name_listeners"] . " " . $user_name["Surname_listeners"],
    "userAnswersIDs" => $userAnswersIDs,
    "userAnswers" => $user_answers,
    "correctAnswers" => $correct_answers,
    "questions" => $questions
);
echo json_encode($response);

?>
