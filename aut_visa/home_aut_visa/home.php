<?php 
$taux_conversion=4950;
$currentYear = date('Y');
$years = range($currentYear - 6, $currentYear);
$annee = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;

include '../aut_visa/home_aut_visa/scriptsCount.php';
include '../aut_visa/home_aut_visa/scriptsQuantite.php';

$result = $conn->query("SELECT COUNT(*) AS nb_users_connectes FROM sessions_actives");
$row = $result->fetch_assoc();
$nombre_users= $row['nb_users_connectes'];

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <link rel="icon" href="../../logo/favicon.ico">
    <title>Ministere des mines</title>
    <meta name="description" content="" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <style>
    .main {
        margin-left: 8%;
        margin-right: 8%;
        position: absolute;
        width: calc(84%);
        min-height: calc(100vh - 60px);
        background: #f5f5ff;
    }

    .cards {
        width: 100%;
        padding: 20px 20px;
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        grid-gap: 20px;
    }

    .cards .card {
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 7px 25px 0 rgba(0, 0, 0, 0.08);
    }

    .card-enLigne {
        width: 80px;
        align-items: center;
        justify-content: space-between;
        background: #fff;
        border-radius: 3rem;
        float: right;
        border: 2px solid rgba(0, 0, 0, 0.08);
        /* Définit une épaisseur, un style et une couleur pour le border */
    }

    .number {
        font-size: 20px;
        font-weight: 500;
        color: #299B63;
    }

    .card-name {
        color: #888;
        font-weight: 600;
    }

    .icon-box i {
        font-size: 45px;
        color: #299B63;
    }

    .icon-box2 i {
        font-size: 15px;
        color: #299B63;
    }

    .charts {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        grid-template-columns: 2fr 1fr;
        grid-gap: 20px;
        width: 100%;
        padding: 20px;
        padding-top: 0;
    }

    .chart {
        flex: 1;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 7px 25px 0 rgba(0, 0, 0, 0.08);
        width: 100%;
        min-width: 300px;
        min-width: 66%;

    }

    .chart2 {
        flex: 1;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 7px 25px 0 rgba(0, 0, 0, 0.08);
        width: 100%;
        min-width: 300px;
        min-width: 30%;

    }

    .progress-bars {
        width: 100%;
    }

    .progress-container {
        margin-bottom: 20px;
    }

    .progress {
        background-color: #e0e0e0;
        border-radius: 5px;
        position: relative;
        height: 20px;
        width: 100%;
        margin-bottom: 10px;
    }

    .progress-bar {
        background-color: #4caf50;
        height: 100%;
        border-radius: 5px;
        text-align: center;
        line-height: 20px;
        color: white;
        font-weight: bold;
    }
    </style>

</head>

<body>
    <div class="main">
        <div>
            <h2 style="text-align: center; color: #299B63;">Poste de contrôle Minier IVATO</h2>
        </div>
        <div class="row">
            <div class="col">
                <div class="charts">
                    <form method="GET" action="">
                        <select id="yearSelect" class="form-select" name="id" onchange="this.form.submit()">
                            <?php foreach ($years as $year): ?>
                            <option value="<?php echo $year; ?>" <?php echo ($year == $annee) ? 'selected' : ''; ?>>
                                <?php echo $year; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </form>
                </div>
            </div>
            <div class="col text-center">
                <div class="card-enLigne">
                    <div class="card-content">
                        <div class="icon-box2">
                            <i class="fas fa-circle green-dot"></i>
                        </div>
                        <div class="card-name"><?php echo $nombre_users;?><small>En ligne</small></div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(intval($nombre_users)>0){ ?>
        <div class="charts">
            <div class="chart">
                <h2>Quantité de la substance par mois</h2>
                <canvas id="lineChart"></canvas>
                <p style="font-size:small;">Unité en kg</p>
            </div>
            <div class="chart2" id="doughnut-chart">
                <h2>Information générale</h2>
                <canvas id="doughnut"></canvas>
            </div>
        </div>
        <?php }else{ ?>
        <p class="alert alert-info">Aucun résultat trouvé pour l'année <?php echo $annee; ?>.</>
            <?php } ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    window.onload = function() {
        // Premier graphique - Ligne
        var dataLine = <?php echo $data_jsonQuantite; ?>;
        var labelLine = <?php echo $labels_jsonMoi; ?>;

        var ctxLine = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: labelLine, // Ajout du tableau des labels
                datasets: [{
                    label: 'Quantite',
                    data: dataLine, // Données pour Ristourne (par mois)
                    backgroundColor: 'rgba(41,155,99,1)', // Couleur pour Ristourne
                    borderColor: 'rgba(41,155,99,1)', // Bordure pour Ristourne
                    borderWidth: 1,
                    fill: false // Ligne sans remplissage
                }]
            },
            options: {
                responsive: true
            }
        });


        // Deuxième graphique - Doughnut
        var labels = <?php echo $labels_json; ?>;
        var data = <?php echo $data_json; ?>;

        var ctxDoughnut = document.getElementById('doughnut').getContext('2d');
        var doughnutChart = new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    label: 'nombre',
                    data: data,
                    backgroundColor: [
                        'rgba(41,155,99,1)', // Couleur existante
                        'rgba(54,162,235,1)', // Couleur existante
                        'rgba(255,206,86,1)', // Couleur existante
                        'rgba(120,46,139,1)', // Couleur existante
                        'rgba(255,99,132,1)', // Nouvelle couleur
                        'rgba(75,192,192,1)', // Nouvelle couleur
                        'rgba(153,102,255,1)', // Nouvelle couleur
                        'rgba(255,159,64,1)', // Nouvelle couleur
                    ],
                    borderColor: [
                        'rgba(41,155,99,1)', // Couleur existante
                        'rgba(54,162,235,1)', // Couleur existante
                        'rgba(255,206,86,1)', // Couleur existante
                        'rgba(120,46,139,1)', // Couleur existante
                        'rgba(255,99,132,1)', // Nouvelle couleur
                        'rgba(75,192,192,1)', // Nouvelle couleur
                        'rgba(153,102,255,1)', // Nouvelle couleur
                        'rgba(255,159,64,1)', // Nouvelle couleur
                    ],
                    borderWidth: 1
                }],
            },
            options: {
                responsive: true
            }
        });



    }
    </script>

</body>

</html>