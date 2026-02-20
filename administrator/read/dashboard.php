<?php 
    require_once '../ADMINISTRATOR.php';
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
                <div id="title-for-divisions-list">Les Divisions</div>
                <div id="first-item-for-last">
                    <img src="../asset/image/division.svg" alt="Logo de Division">
                    <div class="description">
                        <strong>Nombre des Divisions</strong>
                        <small id="nombre-de-divisions"><?php echo count($administrator -> get_divisions()) ?></small>
                    </div>
                </div>
                <div id="last-item-for-last">
                    <?php 
                        foreach ($administrator -> get_divisions() as $division) {
                            echo "
                            <div class='card'>
                                <div class='expand'>
                                    <strong>{$division -> get('name')}</strong>
                                    <button class='expand-card'><i class='fa-solid fa-chevron-right'></i></button>
                                </div>
                                <div class='sub-content'>
                                    <span>
                                        <strong>Courriers de Départ:</strong> 
                                        <small>". count($division -> get_outgoing_mails()) ."</small></span>
                                    <span>
                                        <strong>Courriers à Arrivée:</strong> 
                                        <small>". count($division -> get_incoming_mails()) ."</small></span>
                                    <span>
                                        <strong>Employés :</strong>
                                        <small>". count($division -> get_employees()) ."</small>
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
                <?php $statistics = $administrator -> get_mails_statistics_between(start_year: 2020, end_year: 2024); ?>
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