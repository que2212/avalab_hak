<?php
session_start();
include 'db_connect.php';

$error = '';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $error = "Пожалуйста, заполните все поля.";
    } else {
        $sql = "SELECT id, password FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Ошибка подготовки запроса: ' . $conn->error);
}
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($user_id, $hashed_password);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                $_SESSION['user_id'] = $user_id;
                
            header("Location: main.php");
            exit();

            } else {
                $error = "Неверное имя пользователя или пароль.";
            }
        } else {
            $error = "Неверное имя пользователя или пароль.";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="auth_main_div">
    <h1>АВТОРИЗАЦИЯ</h1>
    <form action="login.php" method="POST" class="auth_form">
        <?php if ($error) { echo '<p class="error">'.$error.'</p>'; } ?>
        <label>Имя пользователя:</label>
        <input type="text" name="username" required>
        <label>Пароль:</label>
        <input type="password" name="password" required>
        <input type="submit" value="Войти" class="btn_go_main">
    </form>
    <p>Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a></p>
</body>
</html>


