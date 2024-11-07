<?php 
require_once('../scripts/db_connect.php');
require('../scripts/session.php');

if (isset($_GET['id'])) {
    $currentYear = date('Y');
    $years = range($currentYear - 5, $currentYear);
    $id = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;
    
    $dataSubstance=array();
    $labelsSubstance = array();
    // Première requête
    $totalCC = 0;
    $sql = "SELECT sub.*, dcc.date_cc,
                SUM(CASE 
                    WHEN cfac.unite_poids_facture = 'g' THEN cfac.poids_facture / 1000 
                    ELSE cfac.poids_facture 
                END) AS quantite_kg
            FROM contenu_facture AS cfac 
            LEFT JOIN data_cc AS dcc ON cfac.id_data_cc = dcc.id_data_cc 
            LEFT JOIN substance_detaille_substance AS sds ON sds.id_detaille_substance = cfac.id_detaille_substance
            LEFT JOIN substance AS sub ON sds.id_substance = sub.id_substance 
            WHERE YEAR(dcc.date_cc) = $id
            GROUP BY sub.id_substance
            ORDER BY quantite_kg DESC";  // Limite les résultats aux 9 substances avec les quantités les plus élevées

    $result = $conn->query($sql);
    $quantiteMax = 0; // Variable pour stocker la quantité maximale

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $labelsSubstance[] = $row['nom_substance'];  // Stocke le nom de la substance
            $dataSubstance[$row['nom_substance']] = isset($row['quantite_kg']) ? $row['quantite_kg'] : 0;
            // Mettre à jour la quantité maximale
            if ($dataSubstance[$row['nom_substance']] > $quantiteMax) {
                $quantiteMax = $dataSubstance[$row['nom_substance']];
            }
        }
    }
}
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
        <div class="charts">
            <form method="GET" action="">
                <label for="yearSelect">Sélectionnez une année :</label>
                <select id="yearSelect" class="form-select" name="id" onchange="this.form.submit()">
                    <?php foreach ($years as $year): ?>
                    <option value="<?php echo $year; ?>" <?php echo ($year == $id) ? 'selected' : ''; ?>>
                        <?php echo $year; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <?php if (empty($dataSubstance)): ?>
        <p class="alert alert-info">Aucun résultat trouvé pour l'année <?php echo $id; ?>.</>
            <?php endif; ?>
        <div class="charts">
            <div class="chart">
                <div class="progress-bars">
                    <?php foreach ($dataSubstance as $nom_substance => $quantite): ?>
                    <?php 
                        // Calculer la largeur de la barre en fonction de la quantité maximale
                        $largeur = ($quantiteMax > 0) ? ($quantite / $quantiteMax) * 100 : 0; // Convertir la quantité en pourcentage de la quantité maximale
                    ?>
                    <div class="progress-container">
                        <span><?php echo htmlspecialchars($nom_substance); ?>
                            (<?php echo number_format($quantite, 2, ',', ' ');; ?> kg)</span>
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
                    <a href='./details_substance.php?id=' $annee></a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>