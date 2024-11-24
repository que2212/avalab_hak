<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Главная страница</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class='recomend_header'>
        <a href="select_laws.php">Профиль</a>
        <input type="text" placeholder='ПОИСК' class='recomend_header_search'/>
        <ul class="recomend_header_ul">
            <li class="nav_item" onclick="loadContent('chat-bot')">Чат-бот</li>
            <li class="nav_item" id='nav_item_rec' onclick="loadContent('recomends')">Рекомендации</li>
            <li class="nav_item" onclick="loadContent('analytics')">Аналитика</li>
        </ul>
    </div>
    


    <script src="./main.js"></script>
    <main id="main_content">

    </main>
</body>
</html>