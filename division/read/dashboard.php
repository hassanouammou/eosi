<?php 
    require_once '../DIVISION.php';
    $userpath = <<<HTML
        <li><a href="dashboard.php">Tableau de Board</a></li>
    HTML;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?php echo Page::get_head() ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../asset/style/read/dashboard.css">
    <title>Tableau de Board | Bureau d'Ordre</title>
</head>
<body>
    <div id="container" class="container">
        <header id="header"><?php echo Page::get_header($userpath) ?></header>
        <aside id="aside"><?php echo Page::get_aside() ?></aside>
        <main id="main">
            <div id="first-statitique-groupe">
                <span class="heading">
                    Nombre des Courrier (de Départ et à Arrivée) Pour Chaque Division Pendant Les Année 2020 et 2024
                </span>
                <canvas id="first-chart"></canvas>
            </div>
            <div id="last-statitique-groupe">
                <div id="title-for-divisions-list">Les Employés</div>
                <div id="first-item-for-last">
                    <img src="../asset/image/employees.svg" alt="Logo de Division">
                    <div class="description">
                        <strong>Nombre des Employés</strong>
                        <small><?php echo count($division -> get_employees()) ?></small>
                    </div>
                </div>
                <div id="last-item-for-last">
                    <?php
                        foreach ($division -> get_employees() as $employee) {
                            echo "
                            <div class='card'>
                                <div class='expand'>
                                    <strong>{$employee -> get('firstname')} {$employee -> get('lastname')}</strong>
                                    <button class='expand-card'><i class='fa-solid fa-chevron-right'></i></button>
                                </div>
                                <div class='sub-content'>
                                    <span>
                                        <strong>Email:</strong> 
                                        <small>{$employee -> get('email')}</small></span>
                                    <span>
                                        <strong><abbr title='Numéro de Téléphone'>GSM</abbr>:</strong> 
                                        <small>{$employee -> get('phone_number')}</small></span>
                                    <span>
                                        <strong>Date de Naissance :</strong>
                                        <small>{$employee -> get('birth_date')}</small>
                                    </span>
                                </div>
                            </div>
                            "; 
                        }
                    ?>
                </div>
            </div>
        </main>
    </div>
    <script src="../asset/javascript/read/dashboard.js"></script>
    <script type="text/javascript">
        Chart.defaults.borderColor = "#000000";
        Chart.defaults.color = "#000000";
        Chart.defaults.font.size = 15;
        const ctx = document.getElementById('first-chart');
        new Chart(ctx, {
            animation: false,
            type: 'bar',
            data: {
                <?php $statistics = $division -> get_mails_statistics_between(start_year: 2020, end_year: 2024); ?>
                labels: <?php echo json_encode($statistics["years_interval"]) ?>,
                datasets: [
                    {
                        label: 'Courriers de Départ',
                        data: <?php echo json_encode($statistics["outgoing_mails_interval"]) ?>,
                        backgroundColor: '#115f9a',
                    },
                    {
                        label: 'Courriers d\'Arrivée',
                        data: <?php echo json_encode($statistics["incoming_mails_interval"]) ?>,
                        backgroundColor: '#48b5c4',
                    },
                ]
            },
            options: {
                events: ["mouseout", "touchstart", "touchmove", "touchend"],
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: false,
                        },
                        ticks: {
                            stepSize: 2,
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            },
        });
    </script>
</body>
</html>