<?php
$data = json_decode(file_get_contents('php://input'), true);

// Підключення до бази даних
$conn = new mysqli("localhost", "root", "admin", "Questionnaires");
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

$response = array();

// First Query
$sql = "SELECT Tests.Id_tests as test_id, Tests.Name_test, COUNT(Results.Id_results) AS TestPasses, AVG(Results.Rating_results) AS AverageRating
FROM Results
JOIN Tests ON Results.ID_tests = Tests.Id_tests
JOIN Listeners ON Results.ID_listeners = Listeners.Id_listeners
GROUP BY Tests.Id_tests;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $testResults = array();
    while ($row = $result->fetch_assoc()) {
        $testResults[] = array(
            "test_id" => $row["test_id"],
            "testName" => $row["Name_test"],
            "testPasses" => $row["TestPasses"],
            "averageRating" => $row["AverageRating"]
        );
    }
    $response['testResults'] = $testResults;
}

echo json_encode($response);

$conn->close();
?>
