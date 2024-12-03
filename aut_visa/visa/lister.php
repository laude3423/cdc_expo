<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
$currentYear = date('Y');
$years = range($currentYear - 6, $currentYear);
$annee = isset($_GET['id']) ? (int)$_GET['id'] : $currentYear;
?>
<?php 
if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $num_vol = $_POST['num_vol']?? null;
        $civilite = $_POST['civilite'] ?? '';
        $passeport = $_POST['passeport'] ?? '';
        $scan_passeport = $_POST['scan_passeport'] ?? '';
        $compagnie = $_POST['compagnie'] ?? '';
        $escale = $_POST['escale'] ?? '';
        $designation = $_POST['designation'] ?? '';
        $poids = $_POST['poids'] ?? '';
        $unite = $_POST['unite'] ?? '';
        $id_vol=$_POST['num_vol'] ?? '';
        $matricule = $_POST['matricule'] ?? '';
        $nom_agent = $_POST['nom_agent'] ?? '';
        $date_depart = $_POST['date_depart'] ?? '';
        $matricule_fret = $_POST['matricule_fret'] ?? '';
        $date_depart_fret = $_POST['date_depart_fret'] ?? '';
        $id_pays = $_POST['id_pays'] ?? '';
        $id_fret=$_POST['nom_fret'] ?? '';
        // $id_data = $_POST['id_data'] ?? '';
        // $id_data_cc = $_POST['id_data_cc'] ?? '';
        $facture = $_POST['facture_A'] ?? '';
        echo $date_cc.$numero_cc;
        $type_decl = $_POST['type_decl'] ?? '';
        $uploadPath_PAS="";$uploadPath_FAC="";$accompagne="";
        if($type_decl=="ACC"){
            $accompagne="OUI";
            $numero_cc = $_POST['numero_cc_A'] ?? '';
            $date_cc = $_POST['date_cc_A'] ?? '';
            
        }else{
            $accompagne='NON';
            $numero_cc = $_POST['numero_cc_B'] ?? '';
            $date_cc = $_POST['date_cc_B'] ?? '';
        }
        //date
        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $dateInsert = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');
        
        $max_id_aut = null;
        $date_creation = null;
        $num_visa=null;
        $num_visa="";

        $codeSql = "
                SELECT numero_visa, date_modification, id_visa 
                FROM visa 
                WHERE id_visa = (SELECT MAX(id_visa) FROM visa)
            ";
            $resultCode = mysqli_query($conn, $codeSql);

            if ($resultCode) {
                $row = mysqli_fetch_assoc($resultCode);
                $num_visa = $row['numero_visa'];
                $max_id_aut = $row['id_visa'];
                $date_creation = $row['date_modification'];
            } else {
                echo "Erreur dans la requête : " . mysqli_error($conn);
            }

            //code
            if($max_id_aut !== null){
                $parts = explode("-", $num_visa);
                // Si la chaîne a bien été divisée
                if(count($parts) === 2) {
                $incrementation = substr($parts[0], 2); // "0001"
                } else {
                echo "La chaîne n'a pas pu être divisée comme prévu.";
                }
                $nouvelle_incrementation = intval($incrementation) + 1;
                $nouvelle_incrementation_formattee = sprintf("%03d", $nouvelle_incrementation);
                $anneeFacture = date('Y', strtotime($date_creation));
                $moisFacture = date('m', strtotime($date_creation));
                echo $nouvelle_incrementation_formattee;
                if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
                    $num_visa = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.VISA";
                    
                }else{
                    $num_visa = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.VISA";
                        
                }
            }else{
                $num_visa = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.VISA";
                
            }
        if($type_decl=="ACC"){
            
            $uploadDir = '../upload/';
        if (!empty($_FILES['scan_facture_A']['name'])) {
                $num_facture = preg_replace('/[^a-zA-Z0-9]/', '-', $facture);
                $fileName_FAC = "SCAN_FACTURE_" .$num_facture.".".
                pathinfo($_FILES['scan_facture_A']['name'], PATHINFO_EXTENSION);
                $uploadPath_FAC = $uploadDir . $fileName_FAC;
                //deplacement des fichier
                if (move_uploaded_file($_FILES['scan_facture_A']['tmp_name'], $uploadPath_FAC)) {
                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
            }
            if (!empty($_FILES['scan_passeport']['name'])) {
                $num_passeport = preg_replace('/[^a-zA-Z0-9]/', '-', $passeport);
                $fileName_PAS = "SCAN_PASSEPORT_" .$num_passeport.".".
                pathinfo($_FILES['scan_passeport']['name'], PATHINFO_EXTENSION);
                $uploadPath_PAS = $uploadDir . $fileName_PAS;
                //deplacement des fichier
                if (move_uploaded_file($_FILES['scan_passeport']['tmp_name'], $uploadPath_PAS)) {
                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
            }
            if (!empty($_FILES['pj_cc_A']['name'])) {
                $numero_cc_clean = preg_replace('/[^a-zA-Z0-9]/', '-', $numero_cc);
                $fileName_CC = "SCAN_CC_" .$numero_cc_clean.".".
                pathinfo($_FILES['pj_cc_A']['name'], PATHINFO_EXTENSION);
                $uploadPath_CC = $uploadDir . $fileName_CC;
                //deplacement des fichier
                if (move_uploaded_file($_FILES['pj_cc_A']['tmp_name'], $uploadPath_CC)) {
                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
            }
            $data_visa="OUI";

                $sql = "INSERT INTO `visa`(`date_depart`,`accompagne`,`numero_visa`,`date_creation`,`date_modification`, `nom_porteur`, `prenom_porteur`, `id_agent_controle`, `id_vol`, 
                `numero_passeport`, `scan_passport`, `numero_facture`, `scan_facture`, `civilite`, `numero_cc`, `date_cc`, `scan_cc`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssiissssssss", $date_depart, $accompagne, $num_visa, $dateInsert, 
                $dateInsert, $nom, $prenom, $matricule, $id_vol, $passeport, $uploadPath_PAS, $facture, $uploadPath_FAC, $civilite, $numero_cc, $date_cc, $uploadPath_CC);
                $result = $stmt->execute();
                if ($result) {
                    // $sql2 = "UPDATE data_cc SET visa=? WHERE id_data_cc=?";
                    // $stmt2 = $conn->prepare($sql2);
                    // $stmt2->bind_param("si",$data_visa, $id_data);
                    // $stmt2->execute();
                    // $stmt2->close();

                    // $id = $stmt->insert_id;
                    $_SESSION['toast_message'] = "Insertion réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Erreur d'enregistrement" . mysqli_error($conn);
                }

        }else{
            // $codeSql="SELECT date_creation, id_visa, numero_visa FROM visa";
            // $resultCode = mysqli_query($conn, $codeSql);
            
            // while ($row = mysqli_fetch_assoc($resultCode)) {
            //     // Vérifier si c'est le premier élément ou si l'actuel id_aut est supérieur au max actuel
            //     if ($max_id_aut === null || $row['id_visa'] > $max_id_aut) {
            //         $max_id_aut = $row['id_visa'];
            //         $date_creation = $row['date_creation'];
            //         $num_visa = $row['numero_visa'];

            //     }
            // }
            // //code
            // if($max_id_aut !== null){
            //     $parts = explode("-", $num_visa);
            //     // Si la chaîne a bien été divisée
            //     if(count($parts) === 2) {
            //     $incrementation = substr($parts[0], 2); // "0001"
            //     } else {
            //     echo "La chaîne n'a pas pu être divisée comme prévu.";
            //     }
            //     $nouvelle_incrementation = intval($incrementation) + 1;
            //     $nouvelle_incrementation_formattee = sprintf("%03d", $nouvelle_incrementation);
            //     $anneeFacture = date('Y', strtotime($date_creation));
            //     $moisFacture = date('m', strtotime($date_creation));
            //     echo $nouvelle_incrementation_formattee;
            //     if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
            //         $num_visa = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.VISA";
                    
            //     }else{
            //         $num_visa = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.VISA";
                        
            //     }
            // }else{
            //     $num_visa = $moisActuel."001-".$anneeActuelle."-MIM/SG/DGM/DEV/GUE.VISA";
                
            // }
            $uploadDir = '../upload/';
            if (!empty($_FILES['pj_cc_B']['name'])) {
                $numero_cc_clean = preg_replace('/[^a-zA-Z0-9]/', '-', $numero_cc);
                $fileName_CC = "SCAN_CC_" .$numero_cc_clean.".".
                pathinfo($_FILES['pj_cc_B']['name'], PATHINFO_EXTENSION);
                $uploadPath_CC = $uploadDir . $fileName_CC;
                //deplacement des fichier
                if (move_uploaded_file($_FILES['pj_cc_B']['tmp_name'], $uploadPath_CC)) {
                } else {
                echo "Erreur lors de l'upload du fichier.";
                }
            }

                $sql = "INSERT INTO `visa`(`date_depart`,`accompagne`,`numero_visa`,`date_creation`,`date_modification`, 
                `id_agent_controle`, `id_fret`, `civilite`,`numero_cc`, `date_cc`, `scan_cc`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)";
                // Préparation de la requête
                $stmt = $conn->prepare($sql);
                // Liaison des variables aux placeholders
                $stmt->bind_param("sssssiissss",$date_depart_fret,$accompagne, $num_visa, $dateInsert,$dateInsert, 
                $matricule_fret, $id_fret, $civilite, $numero_cc, $date_cc, $uploadPath_CC);
                $result = $stmt->execute();
                if ($result) {
                    // Récupérer l'ID de l'enregistrement inséré
                    // $sql2 = "UPDATE data_cc SET visa=? WHERE id_data_cc=?";
                    // $stmt2 = $conn->prepare($sql2);
                    // $stmt2->bind_param("si",$data_visa, $id_data_cc);
                    // $stmt2->execute();
                    // $stmt2->close();

                    $_SESSION['toast_message'] = "Insertion réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
        }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const spinner = document.getElementById('loadingSpinner');
        const table = document.getElementById('agentTable');

        // Afficher le spinner
        spinner.style.display = 'block';
        table.style.display = 'none';

        // Simulation de chargement des données
        setTimeout(() => {
            spinner.style.display = 'none';
            table.style.display = 'table';
        }, 2000); // Changer le délai selon vos besoins
    });
    </script>
    <style>
    td {
        font-size: small;
    }

    #agentTable {
        display: none;
    }
    </style>


    <title>Ministere des mines</title>
    <?php 
    include "../header.php";
    ?>
</head>

<body>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des visas</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche...">
            </div>
            <div class="col-2">
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
            <div class="col text-end">
                <a class="btn btn-dark btn-sm rounded-pill px-3 " href="#" onclick="openModal()"><i
                        class="fa-solid fa-add me-1"></i>Ajouter nouveau</a>
                <a class="btn btn-success btn-sm rounded-pill px-3 " href="./exporter.php?"><i
                        class="fas fa-file-excel"></i> Exporter</a>
            </div>
        </div>
        <hr>
        <?php 
                $sql="SELECT vi.*, fr.* FROM visa AS vi LEFT JOIN fret AS fr ON vi.id_fret=fr.id_fret 
                WHERE YEAR(vi.date_creation) = $annee ORDER BY date_creation DESC";
                $result= mysqli_query($conn, $sql);
                $result= mysqli_query($conn, $sql);
                 if ($result->num_rows > 0) { ?>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table id="agentTable" class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Numéro</th>
                    <th scope="col">Date</th>
                    <th class="masque2" scope="col">Expéditeur</th>
                    <th class="masque2" scope="col">Fret</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)){
                ?>
                <tr>
                    <td>✅</td>
                    <td><?php echo $row['numero_visa'] ?></td>
                    <td><?php echo date('d/m/Y', strtotime($row['date_creation'])) ?></td>
                    <td class="masque2"><?php if(!empty($row['nom_porteur'])){
                        echo $row['nom_porteur'];
                    } else{
                        echo 'Fret';
                    } ?></td>
                    <td class="masque2"><?php if(!empty($row['nom_fret'])){
                        echo $row['nom_fret'];
                    } else{
                        echo 'Accompagné';
                    }  ?></td>
                    <td>
                        <a class="link-dark" href="./detail.php?id=<?php echo $row['id_visa']; ?>">détails</a>
                        <a href="#" class="link-dark btn_edit_visa" data-id="<?= htmlspecialchars($row["id_visa"])?>">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>
                        <a href="#" class="link-dark" onclick="confirmerSuppression(<?php echo $row['id_visa']?>)"><i
                                class="fa-solid fa-trash "></i></a>
                    </td>
                </tr>
                <?php   
                    }
                 }else{
                    echo '<p class="alert alert-info">Aucun résultat trouvé.</p>';
                 }
                ?>


                <tr>
            </tbody>
        </table>
        <div>
            <?php
                include('../../shared/pied_page.php');
            ?>
        </div>
    </div>

    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouveau visa</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="transport" class="col-form-label">Moyen de transport:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type_decl" id="accompagne"
                                    value="ACC" onchange="toggleFields()">
                                <label class="form-check-label" for="accompagne">Accompagné</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type_decl" id="fret" value="FRE"
                                    onchange="toggleFields()">
                                <label class="form-check-label" for="fret">Fret</label>
                            </div>
                        </div>

                        <!-- Section Accompagné -->
                        <div id="accompagne_fields" class="hidden" style="display:none;">
                            <div class="mb-3">
                                <label for="civilite" class="col-form-label">Civilité:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="civilite" id="mademoiselle"
                                        value="M">
                                    <label class="form-check-label" for="mademoiselle">Mademoiselle</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="civilite" id="madame" value="MM">
                                    <label class="form-check-label" for="madame">Madame</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="civilite" id="monsieur"
                                        value="MS">
                                    <label class="form-check-label" for="monsieur">Monsieur</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="nom" class="col-form-label">Nom de l'expéditeur:</label>
                                    <input type="text" class="form-control" name="nom" id="nom"
                                        placeholder="Nom de l'agent" style="font-size:90%">
                                </div>
                                <div class="col">
                                    <label for="prenom" class="col-form-label">Prénom(s) de l'expéditeur:</label>
                                    <input type="text" class="form-control" name="prenom" id="prenom"
                                        placeholder="Prénom de l'agent" style="font-size:90%">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="passeport" class="col-form-label">Passeport:</label>
                                    <input type="text" class="form-control" name="passeport" id="passeport"
                                        placeholder="Numéro du passeport" style="font-size:90%">
                                </div>
                                <div class="col">
                                    <label for="scan_passeport" class="col-form-label">Scan du passeport:</label>
                                    <input type="file" class="form-control" name="scan_passeport" id="scan_passeport"
                                        placeholder="Scan du passeport" style="font-size:90%">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="num_vol" class="col-form-label">Numéro du vol:</label>
                                    <select id="num_vol" class="form-select" name="num_vol" placeholder="Choisir ..."
                                        autocomplete="off" style="font-size:90%" onchange="updateFlightDetails()">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                        $query = "SELECT * FROM vol";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo "<option value='" . $rowSub['id_vol'] . "' data-compagnie='" . $rowSub['nom_compagnie'] . "' data-escale='" . $rowSub['destination_vol'] . "'>" . $rowSub['numero_vol'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="compagnie" class="col-form-label">Nom de la compagnie:</label>
                                    <input type="text" class="form-control" name="compagnie" id="compagnie"
                                        placeholder="Nom de la compagnie" style="font-size:90%" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="escale" class="col-form-label">Escale:</label>
                                    <input type="text" class="form-control" name="escale" id="escale"
                                        placeholder="Escale" style="font-size:90%" readonly>
                                </div>
                                <div class="col">
                                    <label for="date_depart" class="col-form-label">Date de départ:</label>
                                    <input type="date" class="form-control" name="date_depart" id="date_depart"
                                        style="font-size:90%">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="matricule" class="col-form-label">Matricule du responsable:</label>
                                    <select id="matricule" class="form-select" name="matricule"
                                        placeholder="Choisir ..." autocomplete="off" style="font-size:90%"
                                        onchange="updateFlightDetails_agent()">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                        $query = "SELECT * FROM agent_controle";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo "<option value='" . $rowSub['id_agent_controle'] . "' data-nom='" . $rowSub['nom_agent'] . "' data-prenom='" . $rowSub['prenom_agent'] . "'>" . $rowSub['matricule'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="col">
                                        <label for="nom_agent" class="col-form-label">Nom du responsable:</label>
                                        <input type="text" class="form-control" name="nom_agent" id="nom_agent"
                                            placeholder="Nom du responsable" style="font-size:90%" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="prenom_agent" class="col-form-label">Prénom(s) du responsable:</label>
                                    <input type="text" class="form-control" name="prenom_agent" id="prenom_agent"
                                        placeholder="Nom du responsable" style="font-size:90%" readonly>
                                </div>
                                <div class="col">
                                    <label for="numero_cc_A" class="col-form-label">Numéro du C.C:</label>
                                    <input type="text" class="form-control" name="numero_cc_A" id="numero_cc_A"
                                        placeholder="Numéro du certificat de conformité" style="font-size:90%">
                                    <?php    
                                        // $query = "SELECT * FROM data_cc WHERE visa !='OUI'";
                                        // $stmt = $conn->prepare($query);
                                        // $stmt->execute();
                                        // $resu = $stmt->get_result();
                                        
                                        // while ($rowSub = $resu->fetch_assoc()) {
                                        //     echo "<option value='" . $rowSub['id_data_cc'] ."'>" . $rowSub['num_cc'] . "</option>";
                                        // }
                                        ?>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="date_cc_A" class="col-form-label">Date du C.C:</label>
                                        <input type="date" class="form-control" name="date_cc_A" id="date_cc_A"
                                            style="font-size:90%">
                                    </div>
                                    <div class="col">
                                        <label for="pj_cc_A" class="col-form-label">Scan du C.C:</label>
                                        <input type="file" class="form-control" name="pj_cc_A" id="pj_cc_A"
                                            style="font-size:90%" accept=".pdf">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <label for="facture_A" class="col-form-label">Numero de la facture:</label>
                                        <input type="text" class="form-control" placeholder="Numéro de la facture"
                                            name="facture_A" id="facture_A" style="font-size:90%">
                                    </div>
                                    <div class="col">
                                        <label for="scan_facture_A" class="col-form-label">Scan de la facture:</label>
                                        <input type="file" class="form-control" name="scan_facture_A"
                                            id="scan_facture_A" style="font-size:90%" accept=".pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Section Fret -->
                        <div id="fret_fields" class="hidden" style="display:none;">
                            <div class="row">
                                <div class="col">
                                    <label for="nom_fret" class="col-form-label">Numéro du fret:</label>
                                    <select id="nom_fret" name="nom_fret" class="form-select" placeholder="Choisir ..."
                                        autocomplete="off" style="font-size:90%" onchange="updateFlightDetails_fret()">
                                        <option value="">Choisir ...</option>
                                        <?php
                                            $query = "SELECT * FROM fret";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $resu = $stmt->get_result();
                                            while ($rowSub = $resu->fetch_assoc()) {
                                                echo "<option value='" . $rowSub['id_fret'] . "' data-fret='" . $rowSub['lieu_depart'] . "'>" . $rowSub['nom_fret'] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="lieu_depart" class="col-form-label">Lieu de départ:</label>
                                    <input type="text" class="form-control" name="lieu_depart" id="lieu_depart"
                                        placeholder="Lieu de départ" style="font-size:90%" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="matricule_fret" class="col-form-label">Matricule du responsable:</label>
                                    <select id="matricule_fret" class="form-select" name="matricule_fret"
                                        placeholder="Choisir ..." autocomplete="off" style="font-size:90%"
                                        onchange="updateFlightDetails_agent_fret()">
                                        <option value="">Choisir ...</option>
                                        <?php    
                                        $query = "SELECT * FROM agent_controle";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo "<option value='" . $rowSub['id_agent_controle'] . "' data-nom_fret='" . $rowSub['nom_agent'] . "' data-prenom_fret='" . $rowSub['prenom_agent'] . "'>" . $rowSub['matricule'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col">
                                    <div class="col">
                                        <label for="nom_agent_fret" class="col-form-label">Nom du responsable:</label>
                                        <input type="text" class="form-control" name="nom_agent_fret"
                                            id="nom_agent_fret" placeholder="Nom du responsable" style="font-size:90%"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="prenom_agent_fret" class="col-form-label">Prénom(s) du
                                        responsable:</label>
                                    <input type="text" class="form-control" name="prenom_agent_fret"
                                        id="prenom_agent_fret" placeholder="Nom du responsable" style="font-size:90%"
                                        readonly>
                                </div>
                                <div class="col">
                                    <label for="date_depart_fret" class="col-form-label">Date de depart:</label>
                                    <input type="date" class="form-control" name="date_depart_fret"
                                        id="date_depart_fret" placeholder="Nom du responsable" style="font-size:90%">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="numero_cc_B" class="col-form-label">Numéro du C.C:</label>
                                    <input type="text" class="form-control" name="numero_cc_B" id="numero_cc_B"
                                        placeholder="Numéro du certificat de conformité" style="font-size:90%">
                                    <?php    
                                            // $query = "SELECT * FROM data_cc WHERE visa IS NULL";
                                            // $stmt = $conn->prepare($query);
                                            // $stmt->execute();
                                            // $resu = $stmt->get_result();
                                            
                                            // while ($rowSub = $resu->fetch_assoc()) {
                                            //     echo "<option value='" . $rowSub['id_data_cc'] ."'>" . $rowSub['num_cc'] . "</option>";
                                            // }
                                        ?>
                                </div>
                                <div class="col">
                                    <label for="date_cc_B" class="col-form-label">Date du C.C:</label>
                                    <input type="date" class="form-control" name="date_cc_B" id="date_cc_B"
                                        style="font-size:90%">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="pj_cc_B" class="col-form-label">Scan du C.C:</label>
                                <input type="file" class="form-control" name="pj_cc_B" id="pj_cc_B"
                                    style="font-size:90%" accept=".pdf">
                            </div>
                        </div>
                </div>
                <input type="hidden" id="id" name="id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                    <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div id="edit_visa_form"></div>
    </div>
    <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    var myModal;
    var closeModalAfterSubmit = false; // Variable pour vérifier si la modal doit être fermée

    // Fonction pour fermer la modal et actualiser la page si nécessaire
    function closeModal() {
        console.log("Fermeture de la modal");
        if (myModal) {
            myModal.hide();
            if (closeModalAfterSubmit) {
                location.reload(); // Actualiser la page après la fermeture de la modal
            }
        }
    }
    // Fonction pour confirmer la suppression
    function confirmDeletion() {
        // Ici, vous pouvez ajouter le code PHP pour effectuer la suppression
        // Par exemple, vous pouvez utiliser une requête AJAX pour appeler un script PHP de suppression
        console.log("Suppression confirmée");
        closeModal(); // Fermer la modale après la confirmation
    }
    $(document).ready(function() {
        $('.toast').toast('show');
        $(".btn_edit_visa").click(function() {
            var id_data_cc = $(this).data('id');
            console.log(id_data_cc);
            showEditForm('edit_visa_form', './edit.php?id=' + id_data_cc,
                'staticBackdrop2');

        });

        function showEditForm(editFormId, scriptPath, modalId) {
            $("#" + editFormId).load(scriptPath, function() {
                // Après le chargement du contenu, initialisez le modal manuellement
                $("#" + modalId).modal('show');
            });
        }
    });

    $(document).ready(function() {
        $('#accompagne').click(function() {

            $('#nom input, #prenom input, #passeport select, #scan_passeport input, #num_vol input, #date_depart input, #matricule select')
                .attr(
                    'required', true);
            $('#nom_fret select, #matricule_fret select, #date_depart_fret input')
                .attr(
                    'required', false);
        });

        $('#accompagne').click(function() {

            $('#nom input, #prenom input, #passeport select, #scan_passeport input, #num_vol input, #date_depart input, #matricule select')
                .attr(
                    'required', false);
            $('#nom_fret select, #matricule_fret select, #date_depart_fret input')
                .attr(
                    'required', true);
        });
        try {
            var selectElement = $('#id_data');
            if (selectElement.length) {
                new TomSelect(selectElement[0], {
                    // Options de Tom Select
                });
            } else {
                console.error('Element #id_data not found');
            }

            var selectElement1 = $('#id_data_cc');
            if (selectElement1.length) {
                new TomSelect(selectElement1[0], {
                    // Options de Tom Select
                });
            } else {
                console.error('Element #id_data_cc not found');
            }
        } catch (error) {
            console.error('An error occurred:', error);
        }
    });

    function confirmerSuppression(id) {
        // Utilisation de la fonction confirm pour afficher une boîte de dialogue
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");

        // Si l'utilisateur clique sur "OK", la suppression est effectuée
        if (confirmation) {
            $.ajax({
                url: 'delete.php',
                method: 'POST', // Utilisez la méthode POST pour la suppression
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // Traitez la réponse du serveur ici
                    if (response.success) {
                        // La suppression a réussi
                        alert('Suppression réussie.');
                        // Vous pouvez également effectuer d'autres actions nécessaires après la suppression
                        location.reload();
                    } else {
                        // La suppression a échoué
                        alert('Erreur lors de la suppression : ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression : ' + error);
                }
            });
        } else {
            // Sinon, rien ne se passe
        }
    }

    function openModal(edit_id = null) {
        myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });
        // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
        document.getElementById('staticBackdropLabel').innerText = 'Nouveau visa';
        document.getElementById('nom_fret').value = '';
        document.getElementById('lieu_depart').value = '';
        document.getElementById('date_depart').value = '';
        document.getElementById('num_vol').value = '';
        document.getElementById('matricule').value = '';
        document.getElementById('nom_agent').value = '';
        document.getElementById('prenom_agent').value = '';
        document.getElementById('nom').value = '';
        document.getElementById('prenom').value = '';
        document.getElementById('passeport').value = '';
        document.getElementById('scan_passeport').value = '';
        document.getElementById('num_vol').value = '';
        document.getElementById('compagnie').value = '';
        document.getElementById('escale').value = '';
        myModal.show();
    }

    function updateFlightDetails_agent() {
        var selectVol = document.getElementById('matricule');
        var compagnieInput = document.getElementById('nom_agent');
        var escaleInput = document.getElementById('prenom_agent');

        var selectedOption = selectVol.options[selectVol.selectedIndex];
        var compagnie = selectedOption.getAttribute('data-nom');
        var escale = selectedOption.getAttribute('data-prenom');

        // Mettre à jour les champs compagnie et escale
        compagnieInput.value = compagnie;
        escaleInput.value = escale;
    }

    function updateFlightDetails_agent_fret() {
        var selectVol = document.getElementById('matricule_fret');
        var compagnieInput = document.getElementById('nom_agent_fret');
        var escaleInput = document.getElementById('prenom_agent_fret');

        var selectedOption = selectVol.options[selectVol.selectedIndex];
        var compagnie = selectedOption.getAttribute('data-nom_fret');
        var escale = selectedOption.getAttribute('data-prenom_fret');

        // Mettre à jour les champs compagnie et escale
        compagnieInput.value = compagnie;
        escaleInput.value = escale;
    }

    function updateFlightDetails_fret() {
        var selectVol = document.getElementById('nom_fret');
        var fretInput = document.getElementById('lieu_depart');

        var selectedOption = selectVol.options[selectVol.selectedIndex];
        var fret = selectedOption.getAttribute('data-fret');

        // Mettre à jour les champs fret et escale
        fretInput.value = fret;
    }

    function updateFlightDetails() {
        var selectVol = document.getElementById('num_vol');
        var compagnieInput = document.getElementById('compagnie');
        var escaleInput = document.getElementById('escale');

        var selectedOption = selectVol.options[selectVol.selectedIndex];
        var compagnie = selectedOption.getAttribute('data-compagnie');
        var escale = selectedOption.getAttribute('data-escale');

        // Mettre à jour les champs compagnie et escale
        compagnieInput.value = compagnie;
        escaleInput.value = escale;
    }
    </script>
    <script>
    function toggleFields() {
        var accompagneFields = document.getElementById('accompagne_fields');
        var fretFields = document.getElementById('fret_fields');
        var isAccompagne = document.getElementById('accompagne').checked;

        if (isAccompagne) {
            accompagneFields.classList.remove('hidden');
            fretFields.classList.add('hidden');
        } else {
            fretFields.classList.remove('hidden');
            accompagneFields.classList.add('hidden');
        }
    }
    </script>
    <script>
    function toggleFields() {
        var accomagneFields = document.getElementById('accompagne_fields');
        var fretFields = document.getElementById('fret_fields');

        // Hide both sections initially
        accomagneFields.style.display = 'none';
        fretFields.style.display = 'none';

        // Show the correct section based on the selected radio button
        if (document.getElementById('accompagne').checked) {
            accomagneFields.style.display = 'block';
        } else if (document.getElementById('fret').checked) {
            fretFields.style.display = 'block';
        }
    }

    // Call the function to set the initial state when the page loads
    window.onload = function() {
        toggleFields();
    };

    // Function to close the modal
    function closeModal() {
        var modal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
        modal.hide();
    }
    </script>
</body>

</html>