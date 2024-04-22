<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизація адміністратора</title>
    <link rel="stylesheet" href="../../../CSS/styles.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php $header; ?>
<div class="login-container">
    <h1>Вхід для адміністратора</h1>
    <form id="login-form" method="post">
        Логін <input name="login" type="text" id="login" required></br>
        Пароль <input name="password" type="password" id="password" required></br>
        <input name="submit" type="submit" value="Увійти">
    </form>
    <div class="additional-actions">
        <a href="/application/controller/account/register_controller.php">Реєстрація</a>
        <a href="">Відновлення паролю</a>
    </div>
    <?php if (isset($errorMessage)) { echo "<p>$errorMessage</p>"; } ?>
</div>
<?php $footer; ?>
</body>
</html>
