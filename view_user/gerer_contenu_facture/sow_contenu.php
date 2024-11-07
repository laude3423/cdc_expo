<?php
    require_once('../../scripts/db_connect.php');
    require(__DIR__ . '/../../scripts/session.php');
if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT cfa.*, g.*,s.*,ts.*,sds.prix_substance, an.*, sds.unite_prix_substance, tr.*,dim.*,di.*,cate.*,cou.*,dg.* ,dcc.* FROM contenu_facture cfa
        INNER JOIN substance_detaille_substance AS sds ON cfa.id_detaille_substance=sds.id_detaille_substance
        LEFT JOIN data_cc AS dcc ON dcc.id_data_cc=cfa.id_data_cc
        LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
        LEFT JOIN substance AS s ON s.id_substance= sds.id_substance
        LEFT JOIN type_substance AS ts ON ts.id_type_substance=s.id_type_substance
        LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_couleur_substance
        LEFT JOIN granulo AS g ON sds.id_granulo=g.id_granulo
        LEFT JOIN dimension_diametre AS dim ON dim.id_dimension_diametre=sds.id_dimension_diametre
        LEFT JOIN transparence AS tr ON sds.id_transparence=tr.id_transparence
        LEFT JOIN degre_couleur AS dg ON sds.id_degre_couleur=dg.id_degre_couleur
        LEFT JOIN forme_substance AS f ON f.id_forme_substance=sds.id_forme_substance
        LEFT JOIN ancien_lp AS an ON an.id_ancien_lp = cfa.id_ancien_lp
        LEFT JOIN durete AS di ON di.id_durete=sds.id_durete WHERE cfa.id_contenu_facture = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $num_facture = $row['num_facture'] ?? "";
        $date_facture = $row['date_facture']??"";
        $nom_granulo=$row['nom_granulo'] ?? "";
        $nom_substance=$row['nom_substance'] ?? "";
        $nom_type_substance=$row['nom_type_substance'] ?? "";
        $nom_transparence=$row['nom_transparence'] ?? "";
        $nom_dimension_diametre=$row['nom_dimension_diametre'] ?? "";
        $nom_degre_couleur=$row['nom_degre_couleur'] ?? "";
        $nom_forme_substance=$row['nom_forme_substance'] ?? "";
        $nom_couleur_substance=$row['nom_couleur_substance'] ?? "";
        $nom_durete=$row['nom_durete'] ?? "";
        $prix_unitaire=$row['prix_unitaire_facture'];
        $poids_facture=$row['poids_facture'] ?? "";
        $prix_normale=$row['prix_substance'] ?? "";
        $unite_substance=$row['unite_substance'] ?? "";
        $num_lp1=$row['id_lp1_info'];
        $nom_categorie=$row['nom_categorie'];
        $code = $row['code_type_substance'];
        $performe = $row['preforme'];
        $qte_finale = $row['quantite_lp1_actuel_lp1_suivis'];
        if(($code=="PIM")&&($nom_categorie=="Taillée")){
            $nom_categorie="Travaillée";
        }

    }

?>
<?php    
    require '../../scripts/connect_db_lp1.php';
   if(!empty($num_lp1)){
        $query = "SELECT lp.*, pd.* FROM lp_info AS lp INNER JOIN produits AS pd ON lp.id_produit= pd.id_produit WHERE id_lp=$num_lp1";
        $result1 = $conn_lp1->query($query);
        $row = $result1->fetch_assoc();
        $num_lp1_suivis=$row['num_LP'];
        $date_lp1=$row['date_modification'];
        $pj_lp1=$row['link_lp'];
        $unite_lp1=$row['unite'];
        $quantite_init=$row['quantite_en_chiffre'];
   }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <style>
    body {
        margin: 0;
    }
    </style>
    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <style>
    .container {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .btn {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .dropdown-item {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .form-control {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .form-select {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .info {
        padding-left: 8.5%;
        padding-right: 8.5%;
        font-size: small;
    }

    #infon1 #info2 {
        display: inline-block;
    }

    .info1 {
        width: 40%;
        float: left;

    }

    .info2 {
        width: 57%;
        float: right;

    }

    @media screen and (max-width: 800px) {

        .infon1,
        .info2 {
            display: block;
        }

        .info1 {
            width: 100%;
        }

        .info2 {
            width: 100%;
        }
    }
    </style>
    <title>Information sur un PV</title>
    <?php include_once('../../view/shared/navBar.php'); ?>

</head>

<body>
    <div class="info  container">
        <p class="text-center mb-0">Détails d'un contenu de facture</p>
        <hr>
        <div class="info1">
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur une facture</h5>
                <hr>
                <p><strong>Numéro de facture:</strong><?php echo "\t".$num_facture; ?></p>
                <p><strong>Date de création de facture:</strong><?php echo $date_facture; ?></p>
                <hr>
                <p><strong>Type de substance:</strong><?php echo "\t".$nom_type_substance; ?></p>
                <p><strong>Nom de substance:</strong><?php echo "\t".$nom_substance; ?></p>
                <?php
                            
                                if($nom_categorie=="Brute"){
                                echo '<p><strong>Nom de catégorie:</strong> ' . $nom_categorie . '</p>';
                                }else{
                                    if($performe=="3"){
                                        echo '<p><strong>Nom de catégorie:</strong>Preformé</p>';
                                    }else{
                                        echo '<p><strong>Nom de catégorie:</strong>Travaillée</p>';
                                    }
                                    
                                }
                            if($nom_couleur_substance){
                                echo '<p><strong>Nom de couleur de la substance:</strong> ' . $nom_couleur_substance . '</p>';
                            }
                            if($nom_degre_couleur){
                                echo '<p><strong>Nom de degré de couleur:</strong> ' . $nom_degre_couleur. '</p>';
                            }
                            if($nom_transparence){
                                echo '<p><strong>Nom de transparence:</strong> ' . $nom_transparence . '</p>';
                            }
                            if($nom_durete){
                                echo '<p><strong>Nom de dureté:</strong> ' . $nom_durete . '</p>';
                            }
                            if($nom_dimension_diametre){
                                echo '<p><strong>Nom de dimension ou diamètre:</strong> ' . $nom_dimension_diametre . '</p>';
                            }
                            if($nom_forme_substance){
                                echo '<p><strong>Forme de la substance:</strong> ' . $nom_forme_substance . '</p>';
                            }
                            if($nom_granulo){
                                echo '<p><strong>Nom de granulomètrie:</strong> ' . $nom_granulo . '</p>';
                            }
                        ?>

                </p>
                <p><strong>Poids:</strong><?php echo "\t".$poids_facture ?></p>
                <p><strong>Prix entré par
                        l'utilisateur:</strong><?php echo "\t".$prix_unitaire ?>
                </p>
                <p><strong>Prix normale:</strong> <?php echo "\t".$prix_normale ?></p>
                <p><strong>Prix totale:</strong> <?php echo $prix_unitaire * $poids_facture ?></p>
                <hr>
                <?php if(!empty($num_lp1)){ ?>
                <p><strong>Numéro LP1:</strong> <?php echo $num_lp1_suivis ?></p>
                <p><strong>Date de création LP1:</strong> <?php echo $date_lp1 ?></p>
                <p><strong>Quantité LP1 initiale :</strong> <?php echo $quantite_init. ' '.$unite_lp1; ?></p>
                <p><strong>Quantité LP1 actuel :</strong> <?php echo $qte_finale; ?></p>
                <p><strong>Scan LP1:</strong> <a
                        href="https://lp1.minesmada.org/view_user/<?php echo htmlspecialchars($pj_lp1, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['num_LP'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                <?php }else{ ?>
                <p><strong>Numéro LP1:</strong> <?php echo $row['numero_lp']; ?></p>
                <p><strong>Date de création LP1:</strong> <?php echo $row['date_creation']; ?></p>
                <p><strong>Quantité LP1 initiale :</strong>
                    <?php echo $row['quantite_lp1_initial_lp1_suivis']. ' '.$row['unite_substance_lp1']; ?></p>
                <p><strong>Quantité LP1 actuel :</strong> <?php echo $qte_finale. ' '.$row['unite_poids_facture'];; ?>
                </p>
                <p><strong>Scan LP1:</strong><a
                        href="../view_user/<?php echo $row['scan_lp']; ?>"><?php echo $row['numero_lp']; ?>.pdf</a>
                </p>
                <?php }?>
            </div>
        </div>
        <div class="info2">
            <div class="alert alert-light" role="alert">
                <?php
                        // Emplacement du fichier PDF
                        if(!empty($num_lp1)){
                            $pdfFilePath = $pj_lp1;
                            include "../../view_user/cdc/convert2.php";
                        }else{
                            $pdfFilePath = $row['scan_lp'];
                            include "../../view_user/cdc/convert.php";
                        }
                        
                    ?>
            </div>
        </div>
    </div>
</body>

</html>