<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body class="register_main_div">
    <h1>РЕГИСТРАЦИЯ</h1>
    <form action="register.php" method="POST" class="register_form">
        <div>
            <label>Имя пользователя: </label>
            <input type="text" name="username" required>
            <label>Email: </label>
            <input type="email" name="email" required>
            <label>Пароль: </label>
            <input type="password" name="password" required>
        </div>
        <div>
        <label>Имя:</label>
        <input type="text" name="first_name">
        <label>Фамилия:</label>
        <input type="text" name="last_name">
        <label>Отчество:</label>
        <input type="text" name="middle_name">
        <div>
        <input type="submit" value="Зарегистрироваться" class="btn_go_main">
    </form>
    <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
</body>
</html>

<?
include 'db_connect.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $email = trim($_POST['email']);
    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $middle_name = trim($_POST['middle_name']);

    if (empty($username) || empty($password) || empty($email)) {
        $error = "Пожалуйста, заполните все обязательные поля.";
    }
    else {
        $sql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Пользователь с таким именем или email уже существует.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql_insert = "INSERT INTO users (username, password, email, first_name, last_name, middle_name) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("ssssss", $username, $hashed_password, $email, $first_name, $last_name, $middle_name);

            if ($stmt_insert->execute()) {
                $_SESSION['user_id'] = $stmt_insert->insert_id;
                
                header("Location: /olimpiada_laws/select_laws.php");
                exit();

            } else {
                $error = "Ошибка при регистрации. Пожалуйста, попробуйте снова.";
            }
        }
    }
}
?>