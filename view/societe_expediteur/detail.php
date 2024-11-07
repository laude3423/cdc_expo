<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    $validation ="";
    $chec_validation=$fonctionUsers.' '.$nom_user.' '.$prenom_user;
    if (isset($_GET['id'])) {
        $id_societe = $_GET['id'];
        $sql = "SELECT * FROM societe_expediteur WHERE id_societe_expediteur=$id_societe";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();

        $nom_societe = $row["nom_societe_expediteur"] ?? "";
        $adresse_societe = $row["adresse_societe_expediteur"] ?? "";
        $nif_societe = $row["nif_societe_expediteur"] ?? "";
        $contact_societe = $row["contact_societe_expediteur"] ?? "";
        $email_societe = $row["email_societe_expediteur"] ?? "";
        $responsable = $row["responsable"] ?? "";
        $nif = $row['nif_societe_expediteur'] ?? "";
        $instat = $row["instat"] ?? "";
        $rcs = $row["rcs"] ?? "";
        $pj_rcs = $row["pj_rcs"] ?? "";
        $pj_nif = $row["pj_nif"] ?? "";
        $pj_instat = $row["pj_instat"] ?? "";
        $validation =$row['validation'] ?? "En attente";
        $nom_users = $row['nom_users'] ?? "";
        $date_octroi_nif = $row['date_octroi_nif'];
        $date_fin_nif = $row['date_fin_nif'];
        $date_octroi_rcs = $row['date_octroi_rcs'];
        $date_octroi_stat = $row['date_octroi_stat'];
        $affilie = $row['affilie'];

        $stmt->close();
    } else {
        echo "<p>Aucune information trouvée pour cet ID LP.</p>";
    }
if (isset($_POST['submit'])) {
        $id = $_POST['id_societe'];
        echo "ID:".$id;
        $action = $_POST['action'];
        $sql="UPDATE `societe_expediteur` SET `validation`='$action', `nom_users`='$chec_validation' WHERE id_societe_expediteur=$id";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $_SESSION['toast_message'] = "Modification réussie.";
             header("Location: https://cdc.minesmada.org/view/societe_expediteur/detail.php?id=" . $id);
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
                <img src="../images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
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
    </style>
    <title>Ministère des mines</title>
    <?php include_once('../../view/shared/navBar.php'); ?>

</head>


<body>
    <div class="info  container">

        <?php
        if (($code_fonction == 'B' || $code_fonction == 'A') && ($validation == "En attente" || empty($validation))) {?>
        <hr>
        <form action="" method="post">
            <?php
            // Supposons que $selectedValue contient la valeur récupérée de la base de données.
            $selectedValue = $validation; // Exemple de valeur
            function isSelected($value, $selectedValue) {
                return $value === $selectedValue ? 'selected' : '';
            }
            ?>
            <div class="row">
                <div class="col">
                    <input type="hidden" value="<?php echo $id_societe; ?>" name="id_societe" id="id_societe">
                    <select class="form-control" name="action" id="action" required>
                        <option value="">Séléctionner</option>
                        <option value="À Refaire" <?= isSelected('À Refaire', $selectedValue) ?>>À Refaire</option>
                        <option value="Validé" <?= isSelected('Validé', $selectedValue) ?>>Validé</option>
                        <option value="En attente" <?= isSelected('En attente', $selectedValue) ?>>En attente</option>
                    </select>
                </div>
                <div class="col text-end">
                    <button class="btn btn-dark btn-sm rounded-pill px-3" type="submit"
                        name="submit">Enregistrer</button>
                </div>
            </div>
        </form>
        <?php
        }else if($validation != 'En attente'){
            echo '<hr><p class="alert alert-info">Status: '.$validation.' par '.$nom_users.'.</p>';
        }else if(($validation == 'En attente') || ($code_fonction=='C')){
            echo '<hr><p class="alert alert-info">Status: En attente</p>';
        }?>

        <hr>
        <div class="info1">
            <div class="alert alert-light" role="alert">
                <h5 id="list-item-1">Détails d'une société expéditeur</h5>
                <hr>
                <p><strong>Nom de la société:</strong> <?php echo $nom_societe; ?></p>
                <p><strong>Type:</strong><?php echo $row['type']; ?></p>
                <p><strong>contact:</strong><?php echo $contact_societe; ?></p>
                <p><strong>Email:</strong><?php echo $row['email_societe_expediteur']; ?></p>
                <p><strong>Adresse:</strong><?php echo $adresse_societe; ?></p>
                <p><strong>Responsable:</strong><?php echo $responsable; ?></p>
                <p><strong>NIF :</strong> <?php echo $nif; ?> octroyé le
                    <?php echo date('d/m/Y', strtotime($row['date_octroi_nif'])); ?> et suspendu le
                    <?php echo date('d/m/Y', strtotime($row['date_fin_nif'])); ?></p>
                <?php $fileName = basename($row['pj_nif']);?>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars($fileName, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($nif, ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                <p><strong>STAT:</strong><?php echo $instat; ?> du
                    <?php echo date('d/m/Y', strtotime($row['date_octroi_stat'])); ?></p>

                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars(basename($row['pj_instat']), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($instat, ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                <?php if(!empty($rcs)){ ?>
                <p><strong>RCS:</strong><?php echo $rcs; ?> du
                    <?php echo date('d/m/Y', strtotime($row['date_octroi_rcs'])); ?></p>
                <p><strong>Télécharger:</strong> <a
                        href="../upload/<?php echo htmlspecialchars(basename($row['pj_rcs']), ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($rcs, ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>
                <?php }?>
            </div>
        </div>
        <div class="info2">
            <div class="alert alert-light" role="alert">
                <?php
                                // Emplacement du fichier PDF
                               
                                $pdfFilePath = $pj_instat;
                                
                                include "../../view_user/cdc/convert.php";
                            ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.toast').toast('show');
    });

    function validation(userId) {
        console.log(userId);
        $.ajax({
            url: 'update_validation.php',
            type: 'POST',
            data: {
                userId: userId
            },
            success: function(response) {
                location.reload(); // Par exemple, recharger la page pour refléter les changements
                alert('Vous avez validé une société.');
            },
            error: function(xhr, status, error) {
                console.error('Erreur lors de la mise à jour du statut:', error);
            }
        });
    }
    </script>
</body>

</html>