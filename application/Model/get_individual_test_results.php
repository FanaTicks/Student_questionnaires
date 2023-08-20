<?php
// Отримання ідентифікаторів тесту та слухача з параметрів запиту
$test_id = $_GET['test_id'];

// Підключення до бази даних
$conn = new mysqli("localhost", "root", "admin", "Questionnaires");
if ($conn->connect_error) {
    die("Помилка з'єднання: " . $conn->connect_error);
}

$query = "SELECT Name_test FROM Tests WHERE Id_tests = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $test_id);
$stmt->execute();
$result = $stmt->get_result();
$test_name = $result->fetch_assoc()["Name_test"]; // Save the test name here

// Отримання спроб слухача з конкретного тесту
$sql_attempts = "SELECT Rating_results, Time_results, ID_listeners FROM Results WHERE ID_tests = ?";
$stmt_attempts = $conn->prepare($sql_attempts);
$stmt_attempts->bind_param('i', $test_id);
$stmt_attempts->execute();
$result_attempts = $stmt_attempts->get_result();
$attempts = array();
while ($row = $result_attempts->fetch_assoc()) {
    $attempts[] = array("score" => $row["Rating_results"], "time" => $row["Time_results"], "listener_id" => $row["ID_listeners"]);
}

// Підготовка запиту для отримання імені слухача
$sql_listener_name = "SELECT CONCAT(Name_listeners, ' ', Surname_listeners) AS FullName FROM Listeners WHERE Id_listeners = ?";
$stmt_listener_name = $conn->prepare($sql_listener_name);

// Прохід по всім спробам і отримання імені слухача для кожної спроби
foreach ($attempts as $key => $attempt) {
    $stmt_listener_name->bind_param('i', $attempt['listener_id']);
    $stmt_listener_name->execute();
    $result_listener_name = $stmt_listener_name->get_result();
    $listener_name = $result_listener_name->fetch_assoc()['FullName'];

    // Додавання імені слухача до відповідного елементу масиву спроб
    $attempts[$key]['listenerName'] = $listener_name;
}

// Fetching the specific data (ID_Results, test_id, ID_listeners)
$sql_data = "SELECT ID_Results, ID_tests, ID_listeners FROM Results WHERE ID_tests = ?";
$stmt_data = $conn->prepare($sql_data);
$stmt_data->bind_param('i', $test_id);
$stmt_data->execute();
$result_data = $stmt_data->get_result();
$data_array = array();
while ($row = $result_data->fetch_assoc()) {
    $data_array[] = array("ID_Results" => $row["ID_Results"], "test_id" => $row["ID_tests"], "ID_listeners" => $row["ID_listeners"]);
}

// Forming the response
$response = array(
    "testName" => $test_name,
    "attempts" => $attempts,
    "data" => $data_array
);
echo json_encode($response);

$conn->close();
?>
