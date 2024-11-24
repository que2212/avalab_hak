<?php
$host = 'localhost'; // Хост базы данных
$db = 'olimp_law3'; // Имя базы данных
$user = 'root'; // Имя пользователя
$pass = ''; // Пароль

try {
    // Подключение к базе данных
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Путь к вашему CSV-файлу
    $filePath = 'C:\OSPanel\domains\localhost\add_sql_data\df_topics.csv';

    // Открываем файл
    $file = fopen($filePath, 'r');

    // Читаем первую строку (заголовки)
    $headers = fgetcsv($file);

    // Начинаем транзакцию
    $pdo->beginTransaction();

    // Перебираем строки CSV
    while (($row = fgetcsv($file)) !== false) {
        // Вставляем данные в таблицу
        $stmt = $pdo->prepare("INSERT INTO users (`id`, `site`,`text`,`date`,`title`,`subtitle`,`support`,`class`,`category`,`requirements`,`participate`,`level`,`cleaned`,`tokens`,`bigram_tokens`,`topic`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute($row);
    }

    // Закрываем файл
    fclose($file);

    // Фиксируем транзакцию
    $pdo->commit();

    echo "CSV успешно импортирован в базу данных!";
} catch (Exception $e) {
    // Откат транзакции при ошибке
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Ошибка: " . $e->getMessage();
}
?>