<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['set_name'])) {
    $set_name = $_POST['set_name'];
//     $region = $_POST['region'] ?? null;
//     $scale = $_POST['scale'] ?? null;
//     $branch = $_POST['branch'] ?? null;
    $law_types = $_POST['law_types'] ?? [];

    // Insert the new filter set
    $sql_insert_set = "INSERT INTO filter_sets (user_id, set_name) VALUES (?, ?)";
    $stmt_insert_set = $conn->prepare($sql_insert_set);
    $stmt_insert_set->bind_param("is", $user_id, $set_name);

    if ($stmt_insert_set->execute()) {
        $filter_set_id = $stmt_insert_set->insert_id;

        if (!empty($law_types)) {
            $sql_insert_items = "INSERT INTO filter_set_items (filter_set_id, law_types ) VALUES (?, ?)";
            $stmt_insert_items = $conn->prepare($sql_insert_items);

            foreach ($law_types as $type_id) {
                $stmt_insert_items->bind_param("ii", $filter_set_id, $type_id);
                $stmt_insert_items->execute();
            }
        }

        header("Location: recomends.php?success=1");
        exit();
    } else {
        die("Ошибка при сохранении фильтров: " . $conn->error);
    }
} else {
    header("Location: select_laws.php?error=1");
    exit();
}
?>