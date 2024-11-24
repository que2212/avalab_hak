<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "law_olimp";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8"); // Устанавливаем кодировку UTF-8

// Проверка соединения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>