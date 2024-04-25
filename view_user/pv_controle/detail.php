<?php
    require_once('../../scripts/db_connect.php');
    require_once('../generate_fichier/nombreEnLettre.php');
    require('../../scripts/session.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql = "SELECT datacc.*, societe_imp.*, societe_exp.*
        FROM data_cc datacc
        LEFT JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
        LEFT JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
        WHERE id_data_cc = $id_data_cc";
        $sql1 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Chef de Division Exportation Minière' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql2 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Responsable qualité Laboratoire des Mines' AND assiste_agent.id_data_cc=$id_data_cc";
        
        $sql4 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Douanier' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql5 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Officier de Police' AND assiste_agent.id_data_cc=$id_data_cc";

        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        //
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $resu1 = $stmt1->get_result();
        $row1 = $resu1->fetch_assoc();
        //
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $resu2 = $stmt2->get_result();
        $row2 = $resu2->fetch_assoc();
        //
        $sql3 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de Scellage' AND assiste_agent.id_data_cc=$id_data_cc";
        $result3= mysqli_query($conn, $sql3);
        //
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute();
        $resu4 = $stmt4->get_result();
        $row4 = $resu4->fetch_assoc();
        //
        $stmt5 = $conn->prepare($sql5);
        $stmt5->execute();
        $resu5 = $stmt5->get_result();
        $row5 = $resu5->fetch_assoc();
        
        $num_domiciliation = $row["num_domiciliation"] ?? "";
        $pj_domiciliation = $row["pj_domiciliation"] ?? "";
        $nombre_colis = $row["nombre_colis"] ?? "";   
        $lieu_scellage = $row["lieu_scellage_pv"] ?? "";   
        $lieu_embarquement = $row["lieu_embarquement_pv"] ?? "";   
        $type_colis = $row["type_colis"] ?? "";   
        $num_fiche_declaration = $row["num_fiche_declaration_pv"] ?? "";   
        $date_fiche_declaration = $row["date_fiche_declaration_pv"] ?? "";   
        $num_lp3e = $row["num_lp3e_pv"] ?? "";  
        $date_lp3e = $row["date_lp3e"] ?? "";   
        $pj_lp3e = $row["pj_lp3e_pv"] ?? ""; 
        $num_cc = $row['num_cc'] ?? "";
        $date_cc = $row['date_cc'] ?? "";
        $lien_cc = $row['lien_cc'] ?? "";
        $pj_cc = $row['pj_cc'] ?? "";
        $num_pv_scellage =$row['num_pv_scellage'] ?? "";
        $pj_fiche_declaration = $row["pj_fiche_declaration_pv"] ?? "";
        $id_societe_expediteur = $row["id_societe_expediteur"] ?? "";
        $id_societe_importateur = $row["id_societe_importateur"] ?? "";

        //
        $id_agent_chef = $row1["id_agent"] ?? "";
        $id_agent_qualite = $row2["id_agent"] ?? "";
        $id_agent_scellage = $row3["id_agent"] ?? "";
        $id_agent_douane = $row4["id_agent"] ?? "";
        $id_agent_police = $row5["id_agent"] ?? "";
        
        $id_agent_scellage = array();

        if($result3){
            while($row3 = mysqli_fetch_assoc($result3)){
                $id_agent_scellage[] = $row3['id_agent'];
            }
        }
        $stmt->close();
        $stmt1->close();
        $stmt2->close();
        $stmt4->close();
        $stmt5->close();
    

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        width: 47%;
        float: left;

    }

    .info2 {
        width: 50%;
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
    <?php 
    include "../../shared/header.php";
    ?>
</head>


<body>
    <div class="info">
        <div class="info1">
            <?php 
                if (!empty($num_cc)) {
                    echo '<div class="alert alert-light" role="alert">
                            <h5 id="list-item-1">Information sur le certificat de conformité</h5>
                            <hr>
                            <p><strong>Numéro de certificat de conformité:</strong> ' . $row["num_cc"] . '</p>
                            <p><strong>Date de création:</strong> ' . date("d/m/Y", strtotime($row["date_cc"])) . '</p>
                            <p><strong>Télécharger:</strong> <a href="../view_user/' . htmlspecialchars($row["pj_cc"], ENT_QUOTES, "UTF-8") . '">' . htmlspecialchars($row["num_cc"], ENT_QUOTES, "UTF-8") . '.pdf</a></p>
                        </div>';
                }
            ?>

            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur le PV de contrôle</h5>
                <hr>
                <p><strong>Numéro de PV de controle:</strong> <?php echo $row['num_pv_controle']; ?></p>
                <p><strong>Date de création:</strong>
                    <?php echo date('d/m/Y', strtotime($row['date_creation_pv_controle'])); ?>
                </p>
                <p><strong>Date de modification:</strong>
                    <?php echo date('d/m/Y', strtotime($row['date_modification_pv_controle'])); ?>
                </p>
                <p><strong>Télécharger:</strong> <a
                        href="../view_user/<?php echo htmlspecialchars($row['pj_pv_controle'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['num_pv_controle'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
            </div>
            <?php 
                if (!empty($num_pv_scellage)) {
                    echo '<div class="alert alert-light" role="alert">
                            <h5 id="list-item-1">Information sur le PV de scellage</h5>
                            <hr>
                            <p><strong>Numéro de PV de scellage:</strong> ' . $row["num_pv_scellage"] . '</p>
                            <p><strong>Date de création:</strong> ' . date("d/m/Y", strtotime($row["date_creation_pv_scellage"])) . '</p>
                            <p><strong>Date de modification:</strong> ' . date("d/m/Y", strtotime($row["date_modification_pv_scellage"])) . '</p>
                            <p><strong>Télécharger:</strong> <a href="../view_user/' . htmlspecialchars($row["pj_pv_scellage"], ENT_QUOTES, "UTF-8") . '">' . htmlspecialchars($row["num_pv_scellage"], ENT_QUOTES, "UTF-8") . '.pdf</a></p>
                        </div>';
                }
            ?>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur les sociétés</h5>
                <hr>
                <p><strong>Société:</strong>Expéditeur</p>
                <p><strong>Nom de la société:</strong><?php echo $row['nom_societe_expediteur']; ?></p>
                <p><strong>Adresse de la société:</strong><?php echo $row['adresse_societe_expediteur']; ?></p>
                <p><strong>Nif de la société:</strong><?php echo $row['nif_societe_expediteur']; ?></p>
                <p><strong>Contact de la société:</strong><?php echo $row['contact_societe_expediteur']; ?></p>
                <p><strong>Email de la société:</strong> <?php echo $row['email_societe_expediteur']; ?></p>
                <hr>
                <p><strong>Société:</strong> Importateur</p>
                <p><strong>Nom de la société:</strong><?php echo $row['nom_societe_importateur']; ?></p>
                <p><strong>Adresse de la société:</strong><?php echo $row['adresse_societe_importateur']; ?></p>
                <p><strong>Contact de la société:</strong><?php echo $row['contact_societe_importateur']; ?></p>
                <p><strong>Email de la société:</strong> <?php echo $row['email_societe_importateur']; ?></p>
                <p><strong>Pays de destination:</strong> <?php echo $row['pays_destination']; ?></p>
                <p><strong>Visa du responsable:</strong> <?php echo $row['visa']; ?></p>

            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-3">CONTENUS</h5>
                <hr>
                <div class="contenu">
                    <?php 
                    $nom_substance = array();
                    $couleur_substance = array();
                    $afficheWord= array();
                    $substances_couleurs = array();
                    $queryR = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $id_data_cc";
                    $resultR = mysqli_query($conn, $queryR);
                    $id_detaille_substance = array();

                    $index1 = 0;
                    while($rowR = mysqli_fetch_assoc($resultR)){
                        $id_detaille_substance[$index1] = $rowR['id_detaille_substance'];
                        $index1++;
                    }
                    if (count($id_detaille_substance) > 0) {
                        $tableau_resultats = array();
                        for ($i = 0; $i < count($id_detaille_substance); $i++) {
                            $queryD = "SELECT sub.*, couleur.*, cate.*
                            FROM substance_detaille_substance AS detail
                            LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                            LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                            LEFT JOIN categorie AS cate ON cate.id_categorie = detail.id_categorie WHERE cate.nom_categorie='Taillée' AND detail.id_detaille_substance = " . $id_detaille_substance[$i] . " GROUP BY couleur.nom_couleur_substance";
                            $resultD = mysqli_query($conn, $queryD);
                            if ($rowD = mysqli_fetch_assoc($resultD)) {
                                $nom_substance[] = $rowD['nom_substance'];
                                if (!empty($rowD['nom_couleur_substance'])) {
                                    $couleur_substance[] = $rowD['nom_couleur_substance'];
                                } else {
                                    $couleur_substance[] = "vide";
                                }
                            }
                        
                        }
                        }
                    if (count($nom_substance) > 0) {
                        for ($i = 0; $i < count($nom_substance); $i++) {
                            $substance = $nom_substance[$i];
                            $couleur = $couleur_substance[$i];
                            // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
                            if (array_key_exists($substance, $substances_couleurs)) {
                                $substances_couleurs[$substance][] = $couleur;
                            } else {
                                $substances_couleurs[$substance] = array($couleur);
                            }
                        }

                        // Affichage des résultats
                        foreach ($substances_couleurs as $substance => $couleurs) {
                            $couleurs_uniques = array_unique($couleurs);
                            if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
                                $afficheWord[] = $substance .'(vide)';
                            } else {
                                $afficheWord[] = $substance . '(' . implode(', ', $couleurs_uniques) . ')';
                            }
                        }
                    }
                    if(count($afficheWord) > 0) {
                        echo "Catégorie Taillé : ";
                        for ($i = 0; $i < count($afficheWord); $i++) {
                            echo $afficheWord[$i] .' ';
                        }
                    }
                    ?>
                    <?php
                    $nom_substance_brute = array();
                    $couleur_substance_brute = array();
                    $afficheWord_brute= array();
                    $substances_couleurs_brute = array();
                    $queryR = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $id_data_cc";
                    $resultR = mysqli_query($conn, $queryR);
                    $id_detaille_substance = array();

                    $index1 = 0;
                    while($rowR = mysqli_fetch_assoc($resultR)){
                        $id_detaille_substance[$index1] = $rowR['id_detaille_substance'];
                        $index1++;
                    }
                    if (count($id_detaille_substance) > 0) {
                        $tableau_resultats = array();
                        for ($i = 0; $i < count($id_detaille_substance); $i++) {
                            $queryD = "SELECT sub.*, couleur.*, cate.*
                            FROM substance_detaille_substance AS detail
                            LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                            LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                            LEFT JOIN categorie AS cate ON cate.id_categorie = detail.id_categorie WHERE cate.nom_categorie='Brute' AND detail.id_detaille_substance = " . $id_detaille_substance[$i] . " GROUP BY couleur.nom_couleur_substance";
                            $resultD = mysqli_query($conn, $queryD);
                            if ($rowD = mysqli_fetch_assoc($resultD)) {
                                $nom_substance_brute[] = $rowD['nom_substance'];
                                if (!empty($rowD['nom_couleur_substance'])) {
                                    $couleur_substance_brute[] = $rowD['nom_couleur_substance'];
                                } else {
                                    $couleur_substance_brute[] = "vide";
                                }
                            }
                        
                        }
                        }
                        if (count($nom_substance_brute) > 0) {
                        for ($i = 0; $i < count($nom_substance_brute); $i++) {
                            $substance_brute = $nom_substance_brute[$i];
                            $couleur_brute = $couleur_substance_brute[$i];
                            // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
                            if (array_key_exists($substance_brute, $substances_couleurs_brute)) {
                                $substances_couleurs_brute[$substance_brute][] = $couleur_brute;
                            } else {
                                $substances_couleurs_brute[$substance_brute] = array($couleur_brute);
                            }
                        }

                        // Affichage des résultats
                        foreach ($substances_couleurs_brute as $substance_brute => $couleurs_brute) {
                            $couleurs_uniques_brute = array_unique($couleurs_brute);
                            if (empty($couleurs_uniques_brute) || in_array('vide', $couleurs_uniques_brute, true)) {
                                $afficheWord_brute[] = $substance_brute .'(vide)';
                            } else {
                                $afficheWord_brute[] = $substance_brute . '(' . implode(', ', $couleurs_uniques_brute) . ')';
                            }
                        }
                    }
                    if(count($afficheWord_brute) > 0) {
                        echo "Catégorie Brute : ";
                        for ($i = 0; $i < count($afficheWord_brute); $i++) {
                            echo $afficheWord_brute[$i] .' ';
                        }
                    }
                    
                    ?>
                </div>
                <?php
                    //affichage de poids
                    $sommePoids_ct=0;
                    $sommePoids_g=0;
                    $sommePoids_kg=0;
                    //recherche l'id_detaille_substance pour l'unité en carat
                    $queryDetaille_ct = "SELECT datacc.num_facture, sum(contenu.poids_facture) as sommePoids FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc WHERE contenu.id_data_cc = $id_data_cc AND contenu.unite_poids_facture='ct'";
                    $resultDetaille_ct = mysqli_query($conn, $queryDetaille_ct);
                    $rowDetaille_ct = mysqli_fetch_assoc($resultDetaille_ct);
                    $num_facture = $rowDetaille_ct['num_facture'];
                    if(!empty($rowDetaille_ct['sommePoids'])){
                        $sommePoids_ct = $rowDetaille_ct['sommePoids'];
                    }
                    //recherche l'id_detaille_substance pour l'unité en gramme
                    $queryDetaille_g = "SELECT  sum(contenu.poids_facture) as sommePoids FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc WHERE contenu.id_data_cc = $id_data_cc AND contenu.unite_poids_facture='g'";
                    $resultDetaille_g = mysqli_query($conn, $queryDetaille_g);
                    $rowDetaille_g = mysqli_fetch_assoc($resultDetaille_g);
                    if(!empty($rowDetaille_g['sommePoids'])){
                        $sommePoids_g = $rowDetaille_g['sommePoids'];
                    }
                    //recherche l'id_detaille_substance pour l'unité en kilogramme
                    $queryDetaille_kg = "SELECT  sum(contenu.poids_facture) as sommePoids, id_detaille_substance FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc WHERE contenu.id_data_cc = $id_data_cc AND contenu.unite_poids_facture='kg'";
                    $resultDetaille_kg = mysqli_query($conn, $queryDetaille_kg);
                    $rowDetaille_kg = mysqli_fetch_assoc($resultDetaille_kg);
                    $id_detaille_substance_kg = $rowDetaille_kg['id_detaille_substance'];
                    if(!empty($rowDetaille_kg['sommePoids'])){
                        $sommePoids_kg = $rowDetaille_kg['sommePoids'];
                    }
                    $totalEnGramme = $sommePoids_ct * 0.2 + $sommePoids_g;
                    $totalePoidsFormate = number_format($totalEnGramme, 2, '.', '');
                    $nombreExplode = explode(".", $totalePoidsFormate);
                        $nombreAvant = $nombreExplode[0];
                        $nombreApres = $nombreExplode[1];
                        $nombreCompare='';
                        $nombreCompareLettre='';
                    if($nombreApres > 0) {
                        $nombreCompare = comparer($nombreApres);
                        $nombreCompareLettre=nombreEnLettres($nombreCompare);
                    }
                    $totalePoidsEnLettres = nombreEnLettres($nombreAvant);
                    $poidsEnLettre = $totalePoidsFormate.'('. $totalePoidsEnLettres.' virgule '.$nombreCompareLettre.' de Pierres gemmes)';
                    echo 'Poids:'.$poidsEnLettre;
                    if($sommePoids_kg !=0){
                        $totalePoidsFormate_kg = number_format($sommePoids_kg, 2, '.', '');
                        $nombreExplode = explode(".", $totalePoidsFormate_kg);
                        $nombreAvant = $nombreExplode[0];
                        $nombreApres = $nombreExplode[1];
                        $nombreCompare='';
                        $nombreCompareLettre='';
                        if($nombreApres > 0) {
                            $nombreCompare_kg = comparer($nombreApres);
                            $nombreCompareLettre_kg=nombreEnLettres($nombreCompare_kg);
                        }
                        $totalePoidsEnLettres_kg = nombreEnLettres($nombreAvant);
                        $poidsEnLettre_kg = $totalePoidsFormate_kg.'('. $totalePoidsEnLettres_kg.' '.$nombreCompareLettre_kg.' de Pierres ordinaires)';
                        echo 'Poids :'.$poidsEnLettre_kg;
                    }
                ?>
            </div>

            <div class="alert alert-light" role="alert">
                <h5 id="list-item-4">Informations sur DOM</h5>
                <hr>
                <p><strong>Numéro de domiciliation:</strong> <?php echo $row['num_domiciliation']; ?></p>
                <p><strong>Scan du fichier DOM:</strong> <a
                        href="../view_user/<?php echo htmlspecialchars($row['pj_domiciliation_pv'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['num_domiciliation'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-4">Informations sur le fichier de déclaration</h5>
                <hr>
                <p><strong>Numéro de fiche de déclaration :</strong> <?php echo $row['num_fiche_declaration_pv']; ?></p>
                <p><strong>Scan de fiche de déclaration :</strong> <a
                        href="../view_user/<?php echo $row['pj_fiche_declaration_pv']; ?>"><?php echo $row['num_fiche_declaration_pv']; ?>.pdf</a>
                    du <?php echo date('d/m/Y', strtotime($row['date_fiche_declaration_pv'])); ?></p>

            </div>

            <div class="alert alert-light" role="alert">
                <h5 id="list-item-5">Information sur LP3 E</h5>
                <hr>
                <p><strong>Numéro LP3 E:</strong> <?php echo $row['num_lp3e_pv']; ?></p>
                <p><strong>Scan de LP III E:</strong> <a
                        href="../view_user/<?php echo $row['pj_lp3e_pv']; ?>"><?php echo $row['num_lp3e_pv']; ?>.pdf</a>
                    du
                    <?php echo date('d/m/Y', strtotime($row['date_lp3e'])); ?></p>

            </div>
        </div>
        <div class="info2">
            <div class="d-flex justify-content-center mt-3">
                <?php if(!empty($num_cc)): ?>
                <button onclick="refreshIframe('<?php echo $row['pj_cc']; ?>')" class="btn btn-outline-warning">Cliquer
                    ici pour afficher PV en PDF</button>
                <?php else: ?>
                <button onclick="refreshIframe('<?php echo $row['pj_pv_controle']; ?>')"
                    class="btn btn-outline-warning">Cliquer ici pour afficher PV en PDF</button>
                <?php endif; ?>
            </div>
            <div class="alert alert-light" role="alert">
                <?php
                                // Emplacement du fichier PDF
                                $pj="";
                                if(!empty($pj_cc)){
                                    $pj=$pj_cc;
                                }else{
                                    $pj=$row['pj_pv_controle'];
                                }
                                $pj_pv = str_replace('../', '', $pj);
                                $pdfFilePath = 'cdc.minesmada.org/view_user/' .$pj_pv;
                            ?>
                <iframe id="pdfIframe"
                    src="https://docs.google.com/gview?url=<?php echo urlencode($pdfFilePath); ?>&embedded=true"
                    style="width:100%; height:750px;" frameborder="0"></iframe>
            </div>
        </div>
    </div>
    <?php
    } else {
        echo "<p>Aucune information trouvée pour cet ID LP.</p>";
    }
    ?>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    function refreshIframe(pj) {
        var pj_pv_scellage = pj.replace('../', '');
        var pdfFilePathSc = 'cdc.minesmada.org/view_user/' + pj_pv_scellage;
        // Mettre à jour l'attribut src de l'iframe avec le nouveau lien PDF
        $('#pdfIframe').attr('src', 'https://docs.google.com/gview?url=' + encodeURIComponent(
                pdfFilePathSc) +
            '&embedded=true');
    }
    </script>
</body>

</html>