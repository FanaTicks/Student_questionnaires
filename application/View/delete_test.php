<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Видалити Тест</title>
    <link rel="stylesheet" href="../../CSS/style_delete_test.css">
</head>
<body>
<div class="register-container">
    <h1>Видалити Тест</h1>
    <form action="../Model/delete_test.php" method="POST">
        <label>Виберіть тест для видалення:</label>
        <select name="test_id">
            <!-- Тут має бути запит до бази даних на отримання усіх тестів -->
            <?php include '../Controller/delete_test_controller.php'; ?>
        </select>
        <button type="submit">Видалити</button>
    </form>
    <form action="main/main_admin.php" method="post">
        <button type="submit">Головне меню</button>
    </form>
</div>
</body>
</html>
