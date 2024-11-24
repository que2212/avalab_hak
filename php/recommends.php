<?php
// session_start();
// include 'db_connect.php';

// if (!isset($_SESSION['user_id'])) {
//     header("Location: /olimpiada_laws/login.php");
//     exit();
// }

// // Get the user ID from the session
// $user_id = $_SESSION['user_id'];

// // Fetch the username from the database
// $sql = "SELECT username FROM users WHERE id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_assoc();
// $username = $row['username'];


// // Получаем выбранные пользователем типы законов
// $sql_types = "
//     SELECT type_id FROM user_preferences
//     WHERE user_id = ?
// ";
// $stmt_types = $conn->prepare($sql_types);
// $stmt_types->bind_param("i", $user_id);
// $stmt_types->execute();
// $result_types = $stmt_types->get_result();

// $type_ids = [];
// while ($row = $result_types->fetch_assoc()) {
//     $type_ids[] = $row['type_id'];
// }

// $laws = [];
// if (!empty($type_ids)) {
//     // Получаем законы, соответствующие выбранным типам
//     $in = str_repeat('?,', count($type_ids) - 1) . '?';
//     $sql_laws = "SELECT laws.*, law_types.type_name FROM laws
//                  INNER JOIN law_types ON laws.type_id = law_types.id
//                  WHERE laws.type_id IN ($in)";
//     $stmt_laws = $conn->prepare($sql_laws);
//     $stmt_laws->bind_param(str_repeat('i', count($type_ids)), ...$type_ids);
//     $stmt_laws->execute();
//     $result_laws = $stmt_laws->get_result();

//     while ($law = $result_laws->fetch_assoc()) {
//         $laws[] = $law;
//     }
// }

//     if (!empty($laws)) {
//         foreach ($laws as $law) {
//             echo '<div class="law-container">';
//             echo '<h3>' . htmlspecialchars($law['type_name']) . '</h3>';
//             echo '<p>' . nl2br(htmlspecialchars($law['law_text'])) . '</p>';
//             echo '</div>';
//         }
//     } else {
//         echo '<p>У вас нет выбранных законов. Пожалуйста, выберите типы законов на странице выбора.</p>';
//     }
    ?>

<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch the user's filter sets
$sql_blocks = "SELECT id, set_name FROM filter_sets WHERE user_id = ?";
$stmt_blocks = $conn->prepare($sql_blocks);
$stmt_blocks->bind_param("i", $user_id);
$stmt_blocks->execute();
$result_blocks = $stmt_blocks->get_result();

$filtered_laws = [];
if (isset($_GET['set_id'])) {
    $set_id = intval($_GET['set_id']);

    // Fetch the selected filter set
    $sql_set = "SELECT * FROM filter_sets WHERE id = ?";
    $stmt_set = $conn->prepare($sql_set);
    $stmt_set->bind_param("i", $set_id);
    $stmt_set->execute();
    $result_set = $stmt_set->get_result();
    $filter_set = $result_set->fetch_assoc();

    if ($filter_set) {
        // Start building the query
        $query = "
            SELECT laws.*, law_types.type_name
            FROM laws
            INNER JOIN law_types ON laws.type_id = law_types.id
            WHERE 1=1
        ";
        $params = [];
        $types = "";

        // Fetch type IDs linked to the filter set
        $sql_type_ids = "SELECT type_id FROM filter_set_items WHERE filter_set_id = ?";
        $stmt_type_ids = $conn->prepare($sql_type_ids);
        $stmt_type_ids->bind_param("i", $set_id);
        $stmt_type_ids->execute();
        $result_type_ids = $stmt_type_ids->get_result();

        $type_ids = [];
        while ($row = $result_type_ids->fetch_assoc()) {
            $type_ids[] = $row['type_id'];
        }

        // Add type_id filtering if available
        if (!empty($type_ids)) {
            $in = str_repeat('?,', count($type_ids) - 1) . '?';
            $query .= " AND laws.type_id IN ($in)";
            $params = array_merge($params, $type_ids);
            $types .= str_repeat("i", count($type_ids));
        }

        // Debugging the query and parameters
        echo "<pre>Query: $query</pre>";
        echo "<pre>Types: $types</pre>";
        echo "<pre>Params: ";
        print_r($params);
        echo "</pre>";
        // Uncomment the above lines to debug the query and exit if needed.

        // Prepare and execute the final query
        $stmt_laws = $conn->prepare($query);
        if (!$stmt_laws) {
            die("Ошибка подготовки SQL-запроса: " . $conn->error);
        }
        if (!empty($params)) {
            $stmt_laws->bind_param($types, ...$params);
        }
        $stmt_laws->execute();
        $result_laws = $stmt_laws->get_result();

        while ($law = $result_laws->fetch_assoc()) {
            $filtered_laws[] = $law;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Рекомендации</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Рекомендации</h1>

    <div>
        <h2>Выберите блок фильтров</h2>
        <?php if ($result_blocks->num_rows > 0): ?>
            <ul>
                <?php while ($block = $result_blocks->fetch_assoc()): ?>
                    <li>
                        <a href="recomends.php?set_id=<?= $block['id'] ?>">
                            <?= htmlspecialchars($block['set_name']) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>У вас нет созданных фильтров.</p>
        <?php endif; ?>
    </div>

    <div id="filtered-results">
        <h2>Результаты фильтрации</h2>
        <?php if (!empty($filtered_laws)): ?>
            <?php foreach ($filtered_laws as $law): ?>
                <div>
                    <h3><?= htmlspecialchars($law['type_name']) ?></h3>
                    <p><?= nl2br(htmlspecialchars($law['law_text'])) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет результатов для выбранного фильтра.</p>
        <?php endif; ?>
    </div>
</body>
</html>








