<?php
    require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_data = $_POST["id"];
    $mode_emballage = $_POST["nombre"];
    $lieu_controle = $_POST["lieu_controle"];
    $num_pv='';$num_cc="";
    //recherche
    $query = "SELECT num_pv_controle FROM data_cc WHERE id_data_cc = $id_data AND num_pv_controle IS NOT NULL";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $resu = $stmt->get_result();
        if ($resu->num_rows === 0) {
            //construction de num pv de controle
            $dateFormat = "Y-m-d";
            $dateInsert = date($dateFormat);
            $anneeActuelle = date('Y');
            $moisActuel = date('m');
            $codeSql="SELECT dcc.date_creation_pv_controle, dcc.id_data_cc, dcc.num_pv_controle FROM data_cc AS dcc
            INNER JOIN users AS us ON dcc.id_user=us.id_user
            LEFT JOIN direction AS di ON us.id_direction=di.id_direction
            WHERE dcc.num_pv_controle IS NOT NULL AND di.id_direction=$id_direction";
            $resultCode = mysqli_query($conn, $codeSql);

            $max_id_data_cc = null;
            $date_creation = null;
            $num_pv_controle=null;

            while ($row = mysqli_fetch_assoc($resultCode)) {
                // Vérifier si c'est le premier élément ou si l'actuel id_data_cc est supérieur au max actuel
                if ($max_id_data_cc === null || $row['id_data_cc'] > $max_id_data_cc) {
                    $max_id_data_cc = $row['id_data_cc'];
                    $date_creation = $row['date_creation_pv_controle'];
                    $num_pv_controle = $row['num_pv_controle'];

                }
            }
            if($max_id_data_cc !==null){
                $parts = explode("-", $num_pv_controle);
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
                if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
                $num_pv = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/DEV/GU/PCC";
                $num_cc = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/DEV/GU/CDC";
                }else{
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GU/PCC";
                $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GU/CDC";
                }
            }else{
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GU/PCC";
                $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GU/CDC";
            }
            //appelle de création de fichier
            include '../generate_fichier/generate_insert_controle.php';
             // Mise à jour
            $sql = "UPDATE `data_cc` SET `lieu_controle_pv`='$lieu_controle',`lien_pv_controle`='$pathToSave',`pj_pv_controle`='$pathToSavePDF',`mode_emballage`='$mode_emballage',`date_creation_pv_controle`='$dateInsert',
            `date_modification_pv_controle`='$dateInsert',`num_pv_controle`='$num_pv',
            `num_cc`='$num_cc',`date_cc`='$dateInsert',`lien_cc`='$lien_cc',`pj_cc`='$pj_cc' WHERE id_data_cc='$id_data'";
            $result = mysqli_query($conn, $sql);
            if ($result) {
                        $_SESSION['toast_message'] = "Inertion réussie.";
                        header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
                        exit();
                } else {
                        echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
        } else {
                $_SESSION['toast_message2'] = "Le numéro de facture que vous avez choisi est déjà enregistré.";
                header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
                exit();
        }
            
if(isset($_SESSION['toast_message2'])) {
    echo '
    <div style="left=50px;top=50px">
        <div class="toast-container"">
            <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="../../view/images/warning.jpeg" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                    <small class="text-muted">Maintenant</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ' . $_SESSION['toast_message2'] . '
                </div>
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message2']);
}
            //création de fichier
            
}