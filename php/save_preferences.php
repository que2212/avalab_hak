<?php
session_start();
include 'db_connect.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header("Location: /olimpiada_laws/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['law_types'], $_POST['scale'], $_POST['branch'])) {
    $law_types = $_POST['law_types'];
    $scale = $_POST['scale'];
    $branch = $_POST['branch'];

    // Удаляем текущие настройки
    $sql_delete = "DELETE FROM user_preferences WHERE user_id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $user_id);
    $stmt_delete->execute();

    // Добавляем новые категории
    $sql_insert = "INSERT INTO user_preferences (user_id, type_id, scale, branch) VALUES (?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    foreach ($law_types as $type_id) {
        $stmt_insert->bind_param("iiss", $user_id, $type_id, $scale, $branch);
        $stmt_insert->execute();
    }

    header("Location: /olimpiada_laws/select_laws.php");
    exit();
} else {
    header("Location: /olimpiada_laws/select_laws.php");
    exit();
}
?>
