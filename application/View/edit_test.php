<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редагування тесту</title>
    <link rel="stylesheet" href="../../CSS/style_edit_test.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../Controller/edit_test_controller.js"></script>
</head>
<body>
<div class="register-container">
    <h1>Редагування тесту</h1>

    <label for="test-select">Виберіть тест:</label>
    <select name="test_id">
        <!-- Тут має бути запит до бази даних на отримання усіх тестів -->
        <?php include '../Controller/edit_test_controller.php'; ?>
    </select>
    <button id="load-test">Завантажити</button>

    <form id="edit-test-form" method="post">
        <label for="test-name">Назва тесту:</label>
        <input type="text" name="test_name" id="test-name" required>

        <label for="question-count">Кількість питань:</label>
        <input type="number" name="question_count" id="question-count" required>
        <button type="button" id="create-questions">Створити</button>

        <div id="questions-container"></div>

        <label for="test-time">Час на тест:</label>
        <input type="text" name="test_time" id="test-time" required>

        <button type="submit" id="update-test">Оновити</button>
    </form>

    <form action="main/main_admin.php" method="post">
        <button type="submit">Головне меню</button>
    </form>
</div>
</body>
</html>
