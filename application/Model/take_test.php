<?php
require_once 'functions.php'; // Підключаємо functions.php
$data = json_decode(file_get_contents('php://input'), true);
$selectedTestName = $data['test_name'];
$firstName = $data['first_name'];
$lastName = $data['last_name'];
$email = $data['email'];
$answers = $data['answers'];

// Підключення до бази даних
$conn = new mysqli("localhost", "root", "admin", "Questionnaires");
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

$idListeners = getIdListeners($firstName, $lastName, $email, $conn);
$idTests = getTestId($selectedTestName, $conn);
$totalQuestions = getTotalQuestions($idTests, $conn);
$questionsArray = getQuestionsByTestName($selectedTestName, $conn);
$correctAnswers = calculateCorrectAnswers($answers, $questionsArray);
$ratingResults = calculateRating($correctAnswers, $totalQuestions);
$interestResults = calculateInterest($correctAnswers, $totalQuestions);
// Обчислюємо час, витрачений на тест у секундах
$timeSpent = secondsToTimeFormat($data['time_spent']);


// Підготовка даних
$hours = floor($timeSpent / 3600);
$minutes = floor(($timeSpent % 3600) / 60);
$seconds = $timeSpent % 60;
$timeSpentFormatted = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);

// Вставляємо результати в таблицю Results
$stmt = $conn->prepare("INSERT INTO Results (ID_listeners, ID_tests, Rating_results, Answers_results, Interest_results, Time_results) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iiidis", $idListeners, $idTests, $ratingResults, $correctAnswers, $interestResults, $timeSpent);

// Перевіряємо, чи вдалося правильно зв'язати параметри
if (!$stmt->execute()) {
    die("Помилка при додаванні результатів: " . $stmt->error);
}

echo "Результати успішно збережено!";
$idResults = $conn->insert_id; // Отримуємо ID останнього вставленого рядка

if (!$idResults) {
    die("Помилка при отриманні ID результату: " . $conn->error);
}

$stmt->close();

// Отримуємо ID відповіді на основі тексту відповіді
foreach ($data['answers'] as $answer) {
    $questionId = $answer['question_id'];
    $answerId = $answer['answer_id'];

    // Вставляємо дані в таблицю UserAnswers
    $stmt = $conn->prepare("INSERT INTO UserAnswers (ID_listeners, ID_tests, ID_answer, ID_Results) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $idListeners, $idTests, $answerId, $idResults);

    if (!$stmt->execute()) {
        die("Помилка при додаванні відповіді користувача: " . $stmt->error);
    }
    $stmt->close();
}

$conn->close();



function secondsToTimeFormat($seconds) {
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;
    return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
}

function getTotalQuestions($testId, $conn) {
    $sql = "SELECT Question_quantity FROM Tests WHERE Id_tests = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $testId);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Question_quantity'];
    } else {
        die("Помилка при пошуку кількості питань: " . $stmt->error);
    }
}

function calculateCorrectAnswers($answers, $questionsArray) {
    $correctAnswers = 0;

    foreach ($answers as $answer) {
        $answerId = $answer['answer_id'];
        $questionId = $answer['question_id'];

        foreach ($questionsArray[$questionId]['answers'] as $storedAnswer) {
            if ($storedAnswer['id'] == $answerId && $storedAnswer['correct'] == 1) {
                $correctAnswers++;
                break;
            }
        }
    }
    return $correctAnswers;
}



function calculateRating($correctAnswers, $totalQuestions) {
    if ($totalQuestions == 0) return 0;
    $percentage = ($correctAnswers / $totalQuestions) * 100;
    $rating = ($percentage * 12) / 100;
    return round($rating);
}

function calculateInterest($correctAnswers, $totalQuestions) {
    if ($totalQuestions == 0) return 0;
    $percentage = ($correctAnswers / $totalQuestions) * 100;
    return round($percentage, 2);
}

function getIdListeners($firstName, $lastName, $email, $conn) {
    $sql = "SELECT Id_listeners FROM Listeners WHERE Name_listeners = ? AND Surname_listeners = ? AND Contact_Information = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $firstName, $lastName, $email);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Id_listeners'];
    } else {
        die("Помилка при пошуку слухача: " . $stmt->error);
    }
}
function getAnswerId($answerText, $conn) {
    $sql = "SELECT Id_answer FROM Answers WHERE Answers_text = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $answerText);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Id_answer']; // Повертаємо ID відповіді
    } else {
        return null; // Повертаємо null у випадку помилки
    }
}

function getTestId($testName, $conn) {
    $sql = "SELECT Id_tests FROM Tests WHERE Name_test = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $testName);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['Id_tests'];
    } else {
        die("Помилка при пошуку тесту: " . $stmt->error);
    }
}
?>