<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Реєстрація адміністратора</title>
    <link rel="stylesheet" href="../../../CSS/styles_register.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php $header; ?>
<div class="register-container">
    <h1>Реєстрація адміністратора</h1>
    <form id="register-form" method="post">
        Логін <input name="login" type="text" id="login" required></br>
        Пароль <input name="password" type="password" id="password" required></br>
        <input name="submit" type="submit" value="Зареєструватися">
    </form>
    <div class="additional-actions">
        <a href="/application/controller/account/login_controller.php">Назад</a>
    </div>
    <?php if (isset($errorMessage)) { echo "<p>$errorMessage</p>"; } ?>
</div>
<?php $footer; ?>
</body>
</html>
