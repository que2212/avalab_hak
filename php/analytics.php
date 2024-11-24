<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Диаграмма</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Диаграмма</h1>
    <canvas id="myChart"></canvas>

    <?php
    $data = [12, 19, 3, 5, 2];
    $labels = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май'];
    ?>

    <script>
        const dataFromPHP = <?php echo json_encode($data); ?>;
        const labelsFromPHP = <?php echo json_encode($labels); ?>;

        document.addEventListener("DOMContentLoaded", function () {
            const ctx = document.getElementById('myChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labelsFromPHP,
                    datasets: [{
                        label: 'Продажи',
                        data: dataFromPHP,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        });

        const dataFromPHP = <?php echo json_encode($data); ?>;
        const labelsFromPHP = <?php echo json_encode($labels); ?>;
        console.log("Data from PHP:", dataFromPHP);
        console.log("Labels from PHP:", labelsFromPHP);
    </script>
</body>
</html>