<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    require('../../view_user/generate_fichier/nombre_en_lettre.php');
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT vi.*, vo.*, agent.*, fr.* FROM `visa` AS vi 
            LEFT JOIN vol AS vo ON vo.id_vol = vi.id_vol 
            LEFT JOIN fret AS fr ON fr.id_fret=vi.id_fret
            LEFT JOIN agent_controle AS agent ON agent.id_agent_controle = vi.id_agent_controle
            WHERE id_visa = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();

        $stmt->close();

        // $sql3 = "SELECT * FROM contenu_facture WHERE id_data_cc=?";
        // $stmt3 = $conn->prepare($sql3);
        // $stmt3->bind_param("i", $id_data_cc);
        // $stmt3->execute();
        // $resu3 = $stmt3->get_result();
        // $row3 = $resu3->fetch_assoc();

        // $scan_lp1=$row3['scan_lp1'];
        // $numero_lp1 = $row3['numero_lp1'];
        // $id_lp1_info = $row3['id_lp1_info'];

        // $id_data=$row['id_data_cc'];
        // $sql2 = "SELECT dcc.*, s.* FROM `data_cc` AS dcc
        //     LEFT JOIN societe_importateur AS s ON s.id_societe_importateur = dcc.id_societe_importateur 
        //     WHERE dcc.id_data_cc= ?";
    
        // $stmt2 = $conn->prepare($sql2);
        // $stmt2->bind_param("i", $id_data);
        // $stmt2->execute();
        // $resu2 = $stmt2->get_result();
        // $row2 = $resu2->fetch_assoc();

        // $stmt2->close();   
        // require '../../scripts/connect_db_lp1.php';
    // if(!empty($id_lp1_info)){
    //         $query = "SELECT lp.*, pd.* FROM lp_info AS lp INNER JOIN produits AS pd ON lp.id_produit= pd.id_produit WHERE id_lp=$id_lp1_info";
    //         $result1 = $conn_lp1->query($query);
    //         $row_lp = $result1->fetch_assoc();
    //         $num_lp1_suivis=$row_lp['num_LP'];
    //         $date_lp1=$row_lp['date_modification'];
    //         $pj_lp1=$row_lp['link_lp'];
    //         $unite_lp1=$row_lp['unite'];
    //         $quantite_init=$row_lp['quantite_en_chiffre'];
    // }
   } else {
        echo "<p>Aucune information trouvée pour cet visa.</p>";
    }
 
 if(isset($_SESSION['toast_message'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="../images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                    <small class="text-muted">Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . $_SESSION['toast_message'] . '
                </div>
            </div>
        </div>
    </div>';
    unset($_SESSION['toast_message']);
 }
function ecrire($totalEnGramme, $type_substance, $unite1, $unite){
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
            if($nombreApres=='00'){
                $poidsEnLettre = $totalePoidsFormate.$unite1.'('. $totalePoidsEnLettres.''.$nombreCompareLettre.' '.$unite.' de '.$type_substance.')';
            }else{
                $poidsEnLettre = $totalePoidsFormate.$unite1.'('. $totalePoidsEnLettres.' virgule '.$nombreCompareLettre.' '.$unite.' de '.$type_substance.')';
            }

            return $poidsEnLettre;
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
    <?php include_once('../header.php'); ?>

</head>

<body>
    <div class="info  container">
        <p class="text-center mb-0">Détails d'un visa</p>
        <hr>
        <?php if(empty($row['scan_visa'])){ ?>
        <a class="btn btn-dark rounded-pill px-3 btn-nouveau-scan" data-id="<?php echo $id;?>">Insérer scan</a>
        <?php } else {?>
        <a class="btn btn-dark rounded-pill px-3 btn-nouveau-scan" data-id="<?php echo $id;?>">Modifier scan</a>
        <?php } ?>
        <hr>
        <div class="info1">
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur le visa</h5>
                <hr>
                <p><strong>Numéro du visa:</strong> <?php echo $row['numero_visa']; ?></p>
                <p><strong>Date de création:</strong><?php echo date('d/m/Y', strtotime($row['date_creation'])); ?></p>
                <p><strong>Date de
                        modification:</strong><?php echo date('d/m/Y', strtotime($row['date_modification'])); ?></p>
                <p><strong>Date de départ:</strong><?php echo date('d/m/Y', strtotime($row['date_depart'])); ?></p>
                </p><button class="btn btn-success" id="btnVisa">Voir contenu du fichier</button>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur CC</h5>
                <hr>
                <p><strong>Numréro :</strong><?php echo $row['numero_cc']; ?></p>
                <p><strong>Date:</strong><?php echo date('d/m/Y', strtotime($row['date_cc']));?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($row['scan_cc'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['numero_cc'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                </p><button class="btn btn-success" id="btnCc">Voir contenu du fichier</button>
            </div>
            <?php if($row['accompagne']=="OUI"){ ?>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur la facture</h5>
                <hr>
                <p><strong>Numéro de la facture:</strong> <?php echo $row['numero_facture']; ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($row['scan_facture'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['numero_facture'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                </p><button class="btn btn-success" id="btnFacture">Voir contenu du fichier</button>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur l'éxpediteur</h5>
                <hr>
                <p><strong>Nom et prénom(s):</strong> <?php echo $row['nom_porteur'].' '.$row['prenom_porteur']; ?></p>
                <p><strong>Numéro du passeport:</strong><?php echo $row['numero_passeport']; ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($row['scan_passport'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['numero_passeport'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p><button class="btn btn-success" id="btnPassport">Voir contenu du fichier</button>
            </div>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur le vol</h5>
                <hr>
                <p><strong>Numréro du vol:</strong><?php echo $row['numero_vol']; ?></p>
                <p><strong>Nom de la compagnie:</strong><?php echo $row['nom_compagnie']; ?></p>
                <p><strong>Destination du vol:</strong><?php echo $row['destination_vol']; ?></p>
            </div>
            <?php }else { ?>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur le fret</h5>
                <hr>
                <p><strong>Nom du fret:</strong><?php echo $row['nom_fret']; ?></p>
                <p><strong>Lieu de départ:</strong><?php echo $row['lieu_depart']; ?></p>
            </div>
            <?php } ?>
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur le responsable</h5>
                <hr>
                <p><strong>Numréro matricule:</strong><?php echo $row['matricule']; ?></p>
                <p><strong>Nom:</strong><?php echo $row['nom_agent']; ?></p>
                <p><strong>Prénom(s):</strong><?php echo $row['prenom_agent']; ?></p>
            </div>
            <!-- <div class="alert alert-light" role="alert">
                <h5 id="list-item-2">Information sur le colis</h5>
                <hr> -->
            <?php
                // $id_data_cc=$id_data;
                // $nom_substance = array();
                //     $couleur_substance = array();
                //     $afficheWord= array();
                //     $substances_couleurs = array();
                //     $queryR = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $id_data_cc";
                //     $resultR = mysqli_query($conn, $queryR);
                //     $id_detaille_substance = array();

                //     $index1 = 0;
                //     while($rowR = mysqli_fetch_assoc($resultR)){
                //         $id_detaille_substance[$index1] = $rowR['id_detaille_substance'];
                //         $index1++;
                //     }
                //     if (count($id_detaille_substance) > 0) {
                //         $tableau_resultats = array();
                //         for ($i = 0; $i < count($id_detaille_substance); $i++) {
                //             $queryD = "SELECT sub.*, couleur.*, cate.*
                //             FROM substance_detaille_substance AS detail
                //             LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                //             LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                //             LEFT JOIN categorie AS cate ON cate.id_categorie = detail.id_categorie WHERE cate.nom_categorie='Taillée' AND detail.id_detaille_substance = " . $id_detaille_substance[$i] . " GROUP BY couleur.nom_couleur_substance";
                //             $resultD = mysqli_query($conn, $queryD);
                //             if ($rowD = mysqli_fetch_assoc($resultD)) {
                //                 $nom_substance[] = $rowD['nom_substance'];
                //                 if (!empty($rowD['nom_couleur_substance'])) {
                //                     $couleur_substance[] = $rowD['nom_couleur_substance'];
                //                 } else {
                //                     $couleur_substance[] = "vide";
                //                 }
                //             }
                        
                //         }
                //         }
                //     if (count($nom_substance) > 0) {
                //         for ($i = 0; $i < count($nom_substance); $i++) {
                //             $substance = $nom_substance[$i];
                //             $couleur = $couleur_substance[$i];
                //             // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
                //             if (array_key_exists($substance, $substances_couleurs)) {
                //                 $substances_couleurs[$substance][] = $couleur;
                //             } else {
                //                 $substances_couleurs[$substance] = array($couleur);
                //             }
                //         }

                //         // Affichage des résultats
                //         foreach ($substances_couleurs as $substance => $couleurs) {
                //             $couleurs_uniques = array_unique($couleurs);
                //             if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
                //                 $afficheWord[] = $substance;
                //             } else {
                //                 $afficheWord[] = $substance . '(' . implode(', ', $couleurs_uniques) . ')';
                //             }
                //         }
                //     }
                //     $nom_substance_brute = array();
                //     $couleur_substance_brute = array();
                //     $afficheWord_brute= array();
                //     $substances_couleurs_brute = array();
                //     $queryR = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $id_data_cc";
                //     $resultR = mysqli_query($conn, $queryR);
                //     $id_detaille_substance = array();

                //     $index1 = 0;
                //     while($rowR = mysqli_fetch_assoc($resultR)){
                //         $id_detaille_substance[$index1] = $rowR['id_detaille_substance'];
                //         $index1++;
                //     }
                //     if (count($id_detaille_substance) > 0) {
                //         $tableau_resultats = array();
                //         for ($i = 0; $i < count($id_detaille_substance); $i++) {
                //             $queryD = "SELECT sub.*, couleur.*, cate.*
                //             FROM substance_detaille_substance AS detail
                //             LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                //             LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                //             LEFT JOIN categorie AS cate ON cate.id_categorie = detail.id_categorie WHERE cate.nom_categorie='Brute' AND detail.id_detaille_substance = " . $id_detaille_substance[$i] . " GROUP BY couleur.nom_couleur_substance";
                //             $resultD = mysqli_query($conn, $queryD);
                //             if ($rowD = mysqli_fetch_assoc($resultD)) {
                //                 $nom_substance_brute[] = $rowD['nom_substance'];
                //                 if (!empty($rowD['nom_couleur_substance'])) {
                //                     $couleur_substance_brute[] = $rowD['nom_couleur_substance'];
                //                 } else {
                //                     $couleur_substance_brute[] = "vide";
                //                 }
                //             }
                        
                //         }
                //         }
                //         if (count($nom_substance_brute) > 0) {
                //         for ($i = 0; $i < count($nom_substance_brute); $i++) {
                //             $substance_brute = $nom_substance_brute[$i];
                //             $couleur_brute = $couleur_substance_brute[$i];
                //             // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
                //             if (array_key_exists($substance_brute, $substances_couleurs_brute)) {
                //                 $substances_couleurs_brute[$substance_brute][] = $couleur_brute;
                //             } else {
                //                 $substances_couleurs_brute[$substance_brute] = array($couleur_brute);
                //             }
                //         }

                //         // Affichage des résultats
                //         foreach ($substances_couleurs_brute as $substance_brute => $couleurs_brute) {
                //             $couleurs_uniques_brute = array_unique($couleurs_brute);
                //             if (empty($couleurs_uniques_brute) || in_array('vide', $couleurs_uniques_brute, true)) {
                //                 $afficheWord_brute[] = $substance_brute;
                //             } else {
                //                 $afficheWord_brute[] = $substance_brute . '(' . implode(', ', $couleurs_uniques_brute) . ')';
                //             }
                //         }
                //     }
                // include '../../view_user/pv_scellage/recherche.php';
                // if(count($afficheWord_brute) > 0) {
                // echo "Catégorie Brute : ";
                // for ($i = 0; $i < count($afficheWord_brute); $i++) { echo $afficheWord_brute[$i] .', ';
                //         }
                //         echo ' </br> POIDS: '.$ecrit_b;
                //     }
                //     if(count($afficheWord) > 0) {
                //     echo "Catégorie Taillée ou Travaillée : ";
                //     for ($i = 0; $i < count($afficheWord); $i++) { if($i==count($afficheWord) - 1){ echo
                //         $afficheWord[$i]; } else { echo $afficheWord[$i] . ', ' ; } } echo '<br> POIDS: ' .$ecrit_t; }
                //         ?>

            <!-- </div> -->
        </div>
        <div class="info2">
            <div class="alert alert-light" role="alert">
                <div id="pdfViewer" style="margin-top: 20px;">
                    <?php $pdfFilePath=$row['scan_visa'];
                if (!empty($pdfFilePath)) { ?>
                    <iframe src="../upload/<?php echo htmlspecialchars($pdfFilePath, ENT_QUOTES, 'UTF-8'); ?>"
                        style="width:100%; height:600px;" frameborder="0"></iframe>
                    <?php } else { ?>
                    <p class="alert alert-info">Aucun scan disponible.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="nouveau_scan_form"></div>
    </div>
    <?php
    
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
    $(document).ready(function() {
        $('.toast').toast('show');
        $(".btn-nouveau-scan").click(function() {
            var id_data = $(this).data('id');
            console.log(id_data);
            showEditForm('nouveau_scan_form', './nouveau_scan.php?id=' + id_data,
                'staticBackdrop3');

        });

        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });
    </script>
    <script>
    // Variables des boutons
    const btnVisa = document.getElementById('btnVisa');

    const btnCc = document.getElementById('btnCc');
    const pdfViewer = document.getElementById('pdfViewer');

    // Afficher le scan de visa
    btnVisa.addEventListener('click', function() {
        pdfViewer.innerHTML = `
            <iframe src="../upload/<?php echo htmlspecialchars($row['scan_visa'], ENT_QUOTES, 'UTF-8'); ?>"
                    style="width:100%; height:1000px;" frameborder="0"></iframe>
        `;
    });
    btnCc.addEventListener('click', function() {
        pdfViewer.innerHTML = `
            <iframe src="../upload/<?php echo htmlspecialchars($row['scan_cc'], ENT_QUOTES, 'UTF-8'); ?>"
                    style="width:100%; height:1000px;" frameborder="0"></iframe>
        `;
    });
    const btnPassport = document.getElementById('btnPassport');
    const btnFacture = document.getElementById('btnFacture');
    // Afficher le scan de passeport
    btnPassport.addEventListener('click', function() {
        console.log('echo');
        pdfViewer.innerHTML = `
            <iframe src="../upload/<?php echo htmlspecialchars($row['scan_passport'], ENT_QUOTES, 'UTF-8'); ?>"
                    style="width:100%; height:1000px;" frameborder="0"></iframe>
        `;
    });
    btnFacture.addEventListener('click', function() {
        pdfViewer.innerHTML = `
            <iframe src="../upload/<?php echo htmlspecialchars($row['scan_facture'], ENT_QUOTES, 'UTF-8'); ?>"
                    style="width:100%; height:1000px;" frameborder="0"></iframe>
        `;
    });
    </script>
</body>

</html>