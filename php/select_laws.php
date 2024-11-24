<?php
// session_start();
// include 'db_connect.php';

// // Проверяем, авторизован ли пользователь
// if (!isset($_SESSION['user_id'])) {
//     header("Location: login.php");
//     exit();
// }

// $user_id = $_SESSION['user_id'];
// // Получаем уже выбранные типы пользователем
// $sql_selected = "SELECT type_id FROM user_preferences WHERE user_id = ?";
// $stmt_selected = $conn->prepare($sql_selected);
// $stmt_selected->bind_param("i", $user_id);
// $stmt_selected->execute();
// $result_selected = $stmt_selected->get_result();

// $selected_types = [];
// while ($row = $result_selected->fetch_assoc()) {
//     $selected_types[] = $row['type_id'];
// }

// // Получаем все типы законов
// $sql = "SELECT * FROM law_types";
// $result = $conn->query($sql);
?>
<!-- <!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ваши фильтры</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class=''>
        <h1>Ваши фильтры</h1>
        <div>
            <form action="save_preferences.php" method="POST">
                <div>
                    <input type="checkbox" id="select-all"> <label for="select-all">Выбрать все</label>
                </div>
                <?php
                // while ($row = $result->fetch_assoc()) {
                //     $checked = in_array($row['id'], $selected_types) ? 'checked' : '';
                //     echo '<div>';
                //     echo '<input type="checkbox" name="law_types[]" value="' . $row['id'] . '" ' . $checked . '>';
                //     echo '<label>' . $row['type_name'] . '</label>';
                //     echo '</div>';
                // }
                ?>
                <input type="submit" value="Сохранить выбор">
            </form>
        </div>
        <a href="main.php">На главную страницу</a>
        <script>
        document.getElementById('select-all').addEventListener('change', function() {
            var checkboxes = document.querySelectorAll('input[name="law_types[]"]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
        </script>
    </div>
</body>
</html> -->





<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Выбор фильтров</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Выбор фильтров</h1>

    <!-- Форма для нового набора фильтров -->
    <form action="save_filter_set.php" method="POST">
        <label for="set-name">Название набора:</label>
        <input type="text" id="set-name" name="set_name" required>

        <h3>Введите регион (оставьте пустым для всех):</h3>
        <input type="text" id="region" name="region" placeholder="Введите регион">

        <h3>Масштаб (выберите один или оставьте пустым для всех):</h3>
        <select name="scale">
            <option value="">Все</option>
            <option value="local">Местный</option>
            <option value="regional">Региональный</option>
            <option value="federal">Федеральный</option>
        </select>

        <h3>Отрасль (выберите один или оставьте пустым для всех):</h3>
        <select name="branch">
            <option value="">Все</option>
            <option value="health">Здравоохранение</option>
            <option value="education">Образование</option>
            <option value="security">Безопасность</option>
        </select>

        <h3>Типы законов (выберите один или несколько):</h3>
        <?php
        session_start();
        include 'db_connect.php';

        $result_law_types = $conn->query("SELECT id, type_name FROM law_types");
        while ($law_type = $result_law_types->fetch_assoc()): ?>
            <label>
                <input type="checkbox" name="law_types[]" value="<?= $law_type['id'] ?>">
                <?= htmlspecialchars($law_type['type_name']) ?>
            </label><br>
        <?php endwhile; ?>

        <button type="submit">Сохранить</button>
    </form>
</body>
</html>



