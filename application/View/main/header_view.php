<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../CSS/styles_main.css">
    <style>
        .button-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: nowrap;
            align-items: center;
            flex-direction: row;
        }

        .button-container form {
            margin-right: 10px; /* Додає відступи між формами */
        }

        .button-container form:last-child {
            margin-right: 0; /* Видаляє відступ для останньої форми */
        }

        button {
            padding: 10px 15px; /* Збільшує відступи всередині кнопки */
            text-align: center; /* Центрує текст всередині кнопки */
            background-color: #007bff; /* Додає колір фону кнопкам */
            color: white; /* Задає білий колір тексту для кнопок */
            border: none; /* Видаляє рамку з кнопок */
            border-radius: 5px; /* Згладжує кути кнопок */
            cursor: pointer; /* Додає курсор у вигляді руки при наведенні на кнопку */
        }

        button:hover {
            background-color: #0056b3; /* Змінює колір кнопок при наведенні курсору */
        }
    </style>
</head>
<body>
<h1>Адміністраторська панель</h1>
<div class="button-container">
    <?php if (isset($_SESSION['authorized']) && $_SESSION['authorized']): ?>
        <form action="../../Controller/test/create_test_controller.php" method="post">
            <button type="submit">Створення нового тесту</button>
        </form>
        <form action="../../View/edit_test.php" method="post">
            <button type="submit">Редагування тесту</button>
        </form>
        <form action="../../View/delete_test.php" method="post">
            <button type="submit">Видалення тесту</button>
        </form>
        <form action="../../View/view_results.html" method="post">
            <button type="submit">Перегляд результатів тестів</button>
        </form>
    <?php else: ?>
        <p>Будь ласка, <a href="/application/controller/account/login_controller.php">авторизуйтеся</a>, щоб отримати доступ до адміністраторської панелі.</p>
    <?php endif; ?>
</div>
</body>
</html>