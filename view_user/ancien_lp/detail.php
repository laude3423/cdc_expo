<?php
//
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    require('../../histogramme/insert_logs.php');
    $activite = "Validation d'un ancien LP";
    $validation_sce = $fonctionUsers. ' ' . $nom_user. ' '.$prenom_user;
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM `ancien_lp` WHERE id_ancien_lp = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        $validation_aut=$row['validation_lp'];
        $user_validation = $row['user_validation_lp'];

        $stmt->close();
   } else {
        echo "<p>Aucune information trouvée pour cet ID LP.</p>";
    }

    if (isset($_POST['submit'])) {
        $id_aut = $_POST['id_data'];
        $action = $_POST['action'];
        $sql="UPDATE `ancien_lp` SET `validation_lp`='$action', `user_validation_lp`='$validation_sce' WHERE id_ancien_lp=$id_aut";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            insertLogs($conn, $userID, $activite);
            $_SESSION['toast_message'] = "Validation réussie.";
             header("Location: https://cdc.minesmada.org/view_user/ancien_lp/detail.php?id=" . $id_aut);
            exit();
        } else {
            echo "Erreur d'enregistrement" . mysqli_error($conn);
        }
    }  
if(isset($_SESSION['toast_message'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="../../view/images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
                <strong class="me-auto">Notifications</strong>
                <small class="text-muted">Maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['toast_message'] . '
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
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
        <p class="text-center mb-0">Détails d'un ancien LP</p>
        <hr>
        <?php 
        if(($code_fonction=="A")||($code_fonction=="B")){ ?>

        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <?php
                    $selectedValue = $validation_aut; // Exemple de valeur
                        function isSelected($value, $selectedValue) {
                        return $value === $selectedValue ? 'selected' : '';
                    }
                    ?>
                    <input type="hidden" value="<?php echo $id; ?>" name="id_data" id="id_data">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire
                        </option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En
                            attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php }else if($groupeID===2){ ?>
        <form action="" method="post">
            <div class="row">
                <div class="col">
                    <?php
                    $selectedValue = $validation_aut; // Exemple de valeur
                        function isSelected($value, $selectedValue) {
                        return $value === $selectedValue ? 'selected' : '';
                    }
                    ?>
                    <input type="hidden" value="<?php echo $id; ?>" name="id_data" id="id_data">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire
                        </option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En
                            attente
                        </option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php }else { 
                if(empty($validation_aut)){
                    echo '<p class="alert alert-info">Status:En attente.</p>';
                }else{
                    echo '<p class="alert alert-info">Status:'.$validation_aut.' Validateur: '.$user_validation.'.</p>';
                }
             } ?>
        <hr>
        <div class="info1">
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Information sur un ancien LP</h5>
                <hr>
                <p><strong>Type du LP:</strong> <?php echo $row['type_lp']; ?></p>
                <p><strong>Numéro du LP:</strong> <?php echo $row['numero_lp']; ?></p>
                <p><strong>Numéro du folio:</strong> <?php echo $row['numero_folio']; ?></p>
                <p><strong>Nom du titulaire:</strong> <?php echo $row['titulaire_lp']; ?></p>
                <p><strong>Quantité:</strong> <?php echo $row['quantite'].$row['unite']; ?></p>
                <p><strong>Date de création:</strong><?php echo date('d/m/Y', strtotime($row['date_creation'])); ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($row['scan_lp'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['numero_lp'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                <?php if($row['type_lp']=="LPS"){ ?>
                <p><strong>Nom de l'autorisation:</strong> <?php echo $row['numero_autorisation']; ?></p>
                <?php } else if($row['type_lp']=="LPIIIC"){ ?>
                <p><strong>Nom du commerçant:</strong> <?php echo $row['nom_commercant']; ?></p>
                <?php }else if($row['type_lp']=="LPIFOLIO"){ ?>
                <p><strong>Type du permis:</strong> <?php echo $row['type_permis']; ?></p>
                <p><strong>Numéro du permis:</strong> <?php echo $row['numero_permis']; ?></p>
                <p><strong>Nom de la substance:</strong> <?php echo $row['nom_substance']; ?></p>
                <?php }else if($row['type_lp']=="LPIIIE"){ ?>
                <p><strong>Nom de l'exportateur:</strong> <?php echo $row['nom_exportateur']; ?></p>
                <?php }else if($row['type_lp']=="LPII"){ ?>
                <p><strong>Nom du transformateur:</strong> <?php echo $row['nom_transformateur']; ?></p>
                <?php } ?>
            </div>
        </div>
        <div class="info2">
            <div class="alert alert-light" role="alert">
                <?php
                        // Emplacement du fichier PDF
                        
                        $pdfFilePath = $row['scan_lp'];
                        
                        include "../../view_user/cdc/convert.php";
                    ?>
            </div>
        </div>
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
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('nouveau_scan_form', '../pv_controle_gu/nouveau_scan.php?id=' + id_data_cc,
                'staticBackdrop3');

        });
        $(".btn-modifier-scan").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('modifier_scan_form', '../pv_controle_gu/edit_scan.php?id=' + id_data_cc,
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
</body>

</html>