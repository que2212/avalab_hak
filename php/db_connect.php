<?php
$servername = "mysql";
$username = "my_user";
$password = "my_password";
$dbname = "your_database_name2";

$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
