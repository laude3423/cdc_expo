<?php 
require_once('../scripts/db_connect.php');
require('../scripts/session.php');
$currentYear = date('Y');
$years = range($currentYear - 6, $currentYear);
$annee = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;

include './scriptsCount.php';
include './scriptBenefice.php';
include './scriptsDroit.php';
include './scriptsDroit.php';
include './scriptsQuantite.php';

$sommeDroit=0;$sommeRed=0;$sommeRis=0;
$sql = "SELECT SUM(ristourne) AS sommeRistourne, SUM(redevance) AS sommeRedevance,
        SUM(droit_conformite) AS sommeConformite
        FROM data_cc WHERE YEAR(date_cc) = $annee";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
        $sommeRis = isset($row['sommeRistourne']) ? $row['sommeRistourne'] / $taux_conversion : 0;
        $sommeRed = isset($row['sommeRedevance']) ? $row['sommeRedevance'] / $taux_conversion : 0;
        $sommeDroit = isset($row['sommeConformite']) ? $row['sommeConformite'] / $taux_conversion : 0;
    
}
$sommeTotal=$sommeDroit + $sommeRed + $sommeRis;
$sommeTotal=number_format($sommeTotal, 2, ',', ' ');
$sommeDroit=number_format($sommeDroit, 2, ',', ' ');
$sommeRis=number_format($sommeRis, 2, ',', ' ');
$sommeRed=number_format($sommeRed, 2, ',', ' ');

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
<?php include '../shared/nav.php' ?>

<body>
    <div class="main">
        <div class="row">
            <div class="col">
                <div class="charts">
                    <form method="GET" action="">
                        <label for="yearSelect">Sélectionnez une année :</label>
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
        <?php if(intval($sommeTotal)>0){ ?>
        <div class="cards">
            <div class="card">
                <div class="card-content">
                    <div class="number">$<?php echo $sommeDroit;?></div>
                    <div class="card-name">Droit de Certificat</div>
                </div>
                <div class="icon-box">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="number">$<?php echo $sommeRis;?></div>
                    <div class="card-name">Ristourne</div>
                </div>
                <div class="icon-box">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="number">$<?php echo $sommeRed;?></div>
                    <div class="card-name">Redevance</div>
                </div>
                <div class="icon-box">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="number">$<?php echo $sommeTotal;?></div>
                    <div class="card-name">Total</div>
                </div>
                <div class="icon-box">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
        <div class="charts">
            <div class="chart">
                <h2>Ristourne et redevance par mois</h2>
                <canvas id="lineChart"></canvas>
                <p style="font-size:small;">Unité en dollar</p>
            </div>
            <div class="chart2" id="doughnut-chart">
                <h2>Information générale</h2>
                <canvas id="doughnut"></canvas>
            </div>
        </div>
        <div class="charts">
            <div class="chart">
                <h2>Droit de certificat de conformité par mois</h2>
                <canvas id="barChart"></canvas>
                <p style="font-size:small;">Unité en dollar</p>
            </div>
            <div class="chart2" id="pie-chart">
                <h2>Quantité par Direction</h2>
                <canvas id="pie"></canvas>
                <p style="font-size:small;">Unité en kg</p>
            </div>
        </div>
        <div class="charts">
            <div class="chart">
                <p>Nombre de certificat par direction</p>
                <div class="progress-bars">
                    <?php 
                    foreach ($dataCountDire as $nom_direction => $countCC): ?>
                    <?php 
                            // Vérifier que le total des certificats est défini et supérieur à 0
                            $pourcentage = ($totalCC > 0) ? ($countCC / $totalCC) * 100 : 0;
                        ?>
                    <div class="progress-container">
                        <span><?php echo htmlspecialchars($nom_direction); ?> (<?php echo (int)$countCC; ?>
                            certificats)</span>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: <?php echo round($pourcentage, 2) . '%'; ?>;"
                                aria-valuenow="<?php echo round($pourcentage, 2); ?>" aria-valuemin="0"
                                aria-valuemax="100">
                                <?php echo round($pourcentage, 2); ?>%
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="chart2">
                <p>Détails de quantité par substance</p>
                <div class="progress-bars">
                    <?php foreach ($dataSubstance as $nom_substance => $quantite): ?>
                    <?php 
                            // Calculer la largeur de la barre en fonction de la quantité maximale
                            $largeur = ($quantiteMax > 0) ? ($quantite / $quantiteMax) * 100 : 0; // Convertir la quantité en pourcentage de la quantité maximale
                        ?>
                    <div class="progress-container">
                        <span><?php echo htmlspecialchars($nom_substance); ?> (<?php echo (int)$quantite; ?> kg)</span>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar"
                                style="width: <?php echo round($largeur, 2) . '%'; ?>;"
                                aria-valuenow="<?php echo round($quantite, 2); ?>" aria-valuemin="0"
                                aria-valuemax="<?php echo $quantiteMax; ?>">
                                <?php echo round($quantite, 2); ?> kg
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <a href="./details_substance?id=<?php echo $annee; ?>">Voir plus</a>
                </div>
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
        var dataLineRis = <?php echo $data_jsonRis; ?>;
        var dataLineRed = <?php echo $data_jsonRed; ?>;

        var ctxLine = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                        label: 'Ristourne',
                        data: dataLineRis, // Données pour Ristourne (par mois)
                        backgroundColor: 'rgba(41,155,99,1)', // Couleur pour Ristourne
                        borderColor: 'rgba(41,155,99,1)', // Bordure pour Ristourne
                        borderWidth: 1,
                        fill: false // Ligne sans remplissage
                    },
                    {
                        label: 'Redevance',
                        data: dataLineRed, // Données pour Redevance (par mois)
                        backgroundColor: 'rgba(233,30,99,1)', // Couleur pour Redevance
                        borderColor: 'rgba(233,30,99,1)', // Bordure pour Redevance
                        borderWidth: 1,
                        fill: false // Ligne sans remplissage
                    }
                ]
            },
            options: {
                responsive: true
            }
        });
        var labels = <?php echo $labels_json; ?>;
        var data = <?php echo $data_json; ?>;
        // Deuxième graphique - Doughnut
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
                        'rgba(201,203,207,1)', // Nouvelle couleur
                        'rgba(255,87,34,1)', // Nouvelle couleur
                        'rgba(63,81,181,1)', // Nouvelle couleur
                        'rgba(233,30,99,1)' // Nouvelle couleur
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
                        'rgba(201,203,207,1)', // Nouvelle couleur
                        'rgba(255,87,34,1)', // Nouvelle couleur
                        'rgba(63,81,181,1)', // Nouvelle couleur
                        'rgba(233,30,99,1)' // Nouvelle couleur
                    ],
                    borderWidth: 1
                }],
            },
            options: {
                responsive: true
            }
        });

        // Troisième graphe en bar
        var dataBarDroit = <?php echo $data_jsonDroit; ?>;

        var ctxBar = document.getElementById('barChart').getContext('2d');
        var barChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août',
                    'Septembre', 'Octobre', 'Novembre', 'Décembre'
                ],
                datasets: [{
                    label: 'Droit de conformité',
                    data: dataBarDroit,
                    backgroundColor: [
                        'rgba(54,162,235,1)'
                    ],
                    borderColor: [
                        'rgba(54,162,235,1)'
                    ],
                    borderWidth: 1
                }],
            },
            options: {
                responsive: true
            }
        });

        // Quatrième graphique - Pie
        var labelsDire = <?php echo $labels_jsonDirection; ?>;
        var dataDire = <?php echo $data_jsonDirection; ?>;

        var ctxPie = document.getElementById('pie').getContext('2d');
        var pieChart = new Chart(ctxPie, {
            type: 'pie', // Remplacez 'doughnut' par 'pie'
            data: {
                labels: labelsDire,
                datasets: [{
                    label: 'Quantité',
                    data: dataDire,
                    backgroundColor: [
                        'rgba(41,155,99,1)', // Couleur existante
                        'rgba(54,162,235,1)', // Couleur existante
                        'rgba(255,206,86,1)', // Couleur existante
                        'rgba(120,46,139,1)', // Couleur existante
                        'rgba(255,99,132,1)', // Nouvelle couleur
                        'rgba(75,192,192,1)', // Nouvelle couleur
                        'rgba(153,102,255,1)', // Nouvelle couleur
                        'rgba(255,159,64,1)', // Nouvelle couleur
                        'rgba(201,203,207,1)', // Nouvelle couleur
                        'rgba(233,30,99,1)',
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
                        'rgba(201,203,207,1)', // Nouvelle couleur
                        'rgba(233,30,99,1)',
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