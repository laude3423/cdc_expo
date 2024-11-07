<?php 
require_once('../../../scripts/db_connect.php');
require('../../../scripts/session.php');
include '../../../histogramme/insert_logs.php';
$activite="Insertion d'un nouvel PV";
?>

<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id_societe_expediteur = $_POST['expediteur'];
        $id_societe_importateur = $_POST["importateur"];
        $id_data = $_POST["id_data_cc"];
        $mode_emballage = $_POST["mode_emballage"];
        $lieu_controle = $_POST["lieu_controle"];
        $lieu_embarquement = $_POST["lieu_emb"];
        $num_fiche_declaration = $_POST["declaration"];
        $date_fiche_declaration = $_POST["date_declaration"];
        $id_ancien_lp = $_POST['id_fiche_controle'];
        
        //nouveau
        $date_demande_autorisation = $_POST["date_demande_autorisation"];
        $date_demande = $_POST["date_demande_autorisation"];
        $date_engagement = $_POST["date_engagement"];
        $dateEngagement = $_POST["date_engagement"];
        $num_fiche_controle = $_POST["numero_fiche"];
        $date_fiche_controle = $_POST["date_fiche"];
        $numero_attestation = $_POST["numero_attestation"];
        $date_attestation = $_POST["date_attestation"];
        $lieu_empotage = $_POST["lieu_empotage"];

        $dateFormat = "Y-m-d";
        $date = date($dateFormat);
        $dateInsert = date($dateFormat);
        $anneeActuelle = date('Y');
        $moisActuel = date('m');

        $date_creation="";
        $num_pv_controle="";
        $num_pv="";$num_cc="";

        $sql ="SELECT * FROM direction WHERE id_direction=$id_direction";
        $resultDir = mysqli_query($conn, $sql);
        $rowDir = mysqli_fetch_assoc($resultDir);
        $sigle = $rowDir['sigle_direction'];
        $lieu_emission = $rowDir['lieu_emission'];
        $typeDirection = $rowDir['type_direction'];
        $nomDirection = $rowDir['nom_direction'];
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
        if($max_id_data_cc !== null){
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
            echo $nouvelle_incrementation_formattee;
            if ($anneeFacture == $anneeActuelle && $moisFacture == $moisActuel) {
                if($groupeID ===3){
                    $num_pv = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.PVCC";
                    $num_cc = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.CC";
                }else{
                    $num_pv = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/$sigle.PVCC";
                    $num_cc = $moisActuel.$nouvelle_incrementation_formattee."-".$anneeActuelle."MIM/SG/DGM/$sigle.CC"; 
                }
            }else{
                if($groupeID ===3){
                    $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.PVCC";
                    $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.CC";
                }else{
                    $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/$sigle.PVCC";
                    $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/$sigle.CC";
                }
            }
        }else{
            if($groupeID===3){
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.PVCC";
                $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.CC";
            }else{
                $num_pv = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/$sigle.PVCC";
                $num_cc = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/$sigle.CC";
            }
        }
        // recherche
        $requette="SELECT num_pv_controle FROM data_cc WHERE id_data_cc=$id_data";
        $result = mysqli_query($conn, $requette);
        $rows = mysqli_fetch_assoc($result);
        if (empty($rows['num_pv_controle'])) {
                 include "./generate_fichier.php";
                 include "./traitement.php";
            } else {
                $_SESSION['toast_message2'] = "Le numéro de facture que vous avez choisi est déjà enregistré.";
                header("Location: https://cdc.minesmada.org/view_user/pv_controle_gu/detail.php?id=" . $id_data);
                exit();
            }
        
    }