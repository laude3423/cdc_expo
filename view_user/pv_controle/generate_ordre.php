<?php
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');
include_once('../../scripts/connect_db_lp1.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../mylibs/phpqrcode/qrlib.php';
include '../generate_fichier/nombre_en_lettre.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $montant_redevance=0;$montant_ristourne=0;
    $dateFormat = "d/m/Y";
    $dateInsert = date($dateFormat);
    $dateForma = "Y-m-d";
    $date = date($dateForma);
    $anneeActuelle = date('Y');
    $moisActuel = date('m');
    $id_data_cc = $_POST['id_data_cc'];
    $codeSql="SELECT num_pv_controle, num_cc, date_cc FROM data_cc WHERE id_data_cc=$id_data_cc";
    $resultCode = mysqli_query($conn, $codeSql);
    $num_ov=null;
    $num_cc='';
    $date_cc='';
    while ($row = mysqli_fetch_assoc($resultCode)) {
        $num_ov = $row['num_pv_controle'];
        $num_cc = $row['num_cc'];
        $date_cc = $row['date_cc'];
    }
    if ($num_ov !== null) {
        $num_ov = preg_replace('/\.[^.]+$/', '.OV', $num_ov);
    }else{
        $num_ov = $moisActuel."001-".$anneeActuelle."MIM/SG/DGM/DEV/GUE.OV";
    }
    $regions_affiche = array();
    $commune_affiche = array();
    $rist_commune = array();
    $rist_region = array();
    $montant_ris =0; $montant_red=0;
    $confirme_lp1 = $_POST['confirme_lp1'];
    $confirme_ancien = $_POST['confirme_ancien'];
    if(($confirme_lp1=='avec') && isset($_POST['assiette']) && !empty($_POST['assiette'])){
        $assiettes = $_POST['assiette'];
        $id_lp1_infos = $_POST['id_lp1_info'];
        $regions = $_POST['region'];
        $communes = $_POST['commune'];

        if(count($assiettes)){
            $type_lp='nouveau';
            for ($i = 0; $i < count($assiettes); $i++) {
                $assiette = floatval($assiettes[$i]);
                $id_lp1_info = intval($id_lp1_infos[$i]);
                $region = $regions[$i];
                $commune = $communes[$i];
                $regions_affiche[] = array('region' => $region);
                $communes_affiche[] = array('commune' => $commune);
                $sql = "SELECT num_LP FROM lp_info WHERE id_lp = ?";
                $stmt = $conn_lp1->prepare($sql);
                $stmt->bind_param("i", $id_lp1_info); // "i" pour entier, ou "s" pour chaîne si c'est une chaîne
                $stmt->execute();
                $resu = $stmt->get_result();
                if ($row = $resu->fetch_assoc()) {
                    $numero_lp1 = $row['num_LP'];
                }
                $sql = "SELECT SUM(poids_facture) AS sum_poids FROM contenu_facture WHERE id_lp1_info=$id_lp1_info";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row_lp = $result->fetch_assoc();
                    $redevance = floatval($row_lp['sum_poids']) * 0.006 * $assiette;
                    $ristourne = floatval($row_lp['sum_poids']) * 0.014 * $assiette;
                    $montant_redevance += $redevance;
                    $montant_ristourne += $ristourne;
                    $ristourne1 = (floatval($ristourne)* 39.3)/100;
                    $ristourne_1 = (floatval($ristourne)* 60.7)/100;
                    $ristourne_ ='--Ar'.$ristourne1.'--';
                    $ristourn ='--Ar'.$ristourne_1.'--';
                    $rist_region[] = array('ristregion' => $ristourne_);
                    $rist_commune[] = array('ristcommune' => $ristourn);
                    // Vérifier si l'enregistrement existe déjà (par exemple par l'ID LP)
                    $sql_check = "SELECT * FROM revenu WHERE id_lp='$id_lp1_info'";
                    $result_check = $conn->query($sql_check);
                    if ($result_check->num_rows > 0) {
                        // Mise à jour si l'enregistrement existe
                        $sql_update = "UPDATE revenu SET assiette=?, nom_region=?, ristourne=?, redevance=?, nom_commune=?, numero_lp=? WHERE id_lp=?";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bind_param("dsddssi", $assiette, $region, $ristourne, $redevance, $commune, $numero_lp1, $id_lp1_info);
                        
                        if ($stmt_update->execute()) {
                            // Mise à jour réussie
                        } else {
                            echo "Erreur de mise à jour : " . $stmt_update->error;
                        }
                    } else {
                        $sql_insert = "INSERT INTO revenu (id_lp, assiette, nom_region, nom_commune, id_data_cc, ristourne, redevance, type_lp, numero_lp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_insert = $conn->prepare($sql_insert);
                        $stmt_insert->bind_param("idssiddss", $id_lp1_info, $assiette, $region, $commune, $id_data_cc, $ristourne, $redevance, $type_lp, $numero_lp1);
                        
                        if ($stmt_insert->execute()) {
                            // Ajout réussi
                        } else {
                            echo "Erreur d'ajout : " . $stmt_insert->error;
                        }
                    }
                    // $sql = "INSERT INTO `revenu` (`id_data_cc`, `id_lp`, `type_lp`,`assiette`,`ristourne`,`redevance`, `nom_commune`,`nom_region`, `numero_cc`) VALUES (?, ?, ?,?, ?, ?, ?, ?, ?)";
                    // $stmt = $conn->prepare($sql);
                    // $stmt->bind_param("iisdddsss", $id_data_cc, $id_lp1_info, $type_lp, $assiette, $ristourne,$redevance, $commune, $region, $num_cc);
                    // $result = $stmt->execute();

                } else {
                    echo "Aucune résultat.";
                }

            }
        }
    }
    if(($confirme_ancien=='avec') && isset($_POST['assiette_ancien']) && !empty($_POST['assiette_ancien'])){
        $assiettes = $_POST['assiette_ancien'];
        $id_anciens = $_POST['id_ancien_lp'];
        $regions = $_POST['region_ancien'];
        $communes = $_POST['commune_ancien'];
        
        if(count($id_anciens)){
            $type_lp='ancien';
            for ($i = 0; $i < count($assiettes); $i++) {
                $assiette = floatval($assiettes[$i]);
                $id_lp1 = intval($id_anciens[$i]);
                $region = $regions[$i];
                $commune = $communes[$i];
                $regions_affiche[] = array('region' => $regions[$i]);
                $communes_affiche[] = array('commune' => $communes[$i]);
                $sql = "SELECT numero_lp FROM ancien_lp WHERE id_ancien_lp = ?";
                $stmt = $conn_lp1->prepare($sql);
                $stmt->bind_param("i", $id_lp1); // "i" pour entier, ou "s" pour chaîne si c'est une chaîne
                $stmt->execute();
                $resu = $stmt->get_result();
                if ($row = $resu->fetch_assoc()) {
                    $numero_lp1 = $row['num_LP'];
                }
                $sql = "SELECT SUM(poids_facture) AS sum_poids FROM contenu_facture WHERE id_ancien_lp=$id_lp1";
                $result = $conn_lp1->query($sql);
                if ($result->num_rows > 0) {
                    $row_lp = $result->fetch_assoc();
                    $redevance = floatval($row_lp['sum_poids']) * 0.006 * $assiette;
                    $ristourne = floatval($row_lp['sum_poids']) * 0.014 * $assiette;
                    $montant_redevance += $redevance;
                    $montant_ristourne += $ristourne;
                    $ristourne1 = (floatval($ristourne)* 39.3)/100;
                    $ristourne_1 = (floatval($ristourne)* 60.7)/100;
                    $ristourne_ ='--Ar'.$ristourne1.'--';
                    $ristourn ='--Ar'.$ristourne_1.'--';
                    $rist_region[] = array('ristregion' => $ristourne_);
                    $rist_commune[] = array('ristcommune' => $ristourn);
                    // $sql = "INSERT INTO `revenu` (`id_data_cc`, `id_lp`, `type_lp`,`assiette`,`ristourne`,`redevance`, `nom_commune`,`nom_region`, `numero_cc`) VALUES (?, ?, ?,?, ?, ?, ?, ?, ?)";
                    // $stmt = $conn->prepare($sql);
                    // $stmt->bind_param("iisdddsss", $id_data_cc, $id_lp1_info, $type_lp, $assiette, $ristourne,$redevance, $commune, $region, $num_cc);
                    // $result = $stmt->execute();

                    $sql_check = "SELECT * FROM revenu WHERE id_lp='$id_lp1'";
                    $result_check = $conn->query($sql_check);
                    if ($result_check->num_rows > 0) {
                        // Mise à jour si l'enregistrement existe
                        $sql_update = "UPDATE revenu SET assiette=?, nom_region=?, ristourne=?, redevance=?, nom_commune=?, numero_lp=? WHERE id_lp=?";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bind_param("dsddssi", $assiette, $region, $ristourne, $redevance, $commune, $numero_lp1, $id_lp1_info);
                        
                        if ($stmt_update->execute()) {
                            // Mise à jour réussie
                        } else {
                            echo "Erreur de mise à jour : " . $stmt_update->error;
                        }
                    } else {
                        $sql_insert = "INSERT INTO revenu (id_lp, assiette, nom_region, nom_commune, id_data_cc, ristourne, redevance, type_lp, numero_lp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $stmt_insert = $conn->prepare($sql_insert);
                        $stmt_insert->bind_param("idssiddss", $id_lp1_info, $assiette, $region, $commune, $id_data_cc, $ristourne, $redevance, $type_lp, $numero_lp1);
                        
                        if ($stmt_insert->execute()) {
                            // Ajout réussi
                        } else {
                            echo "Erreur d'ajout : " . $stmt_insert->error;
                        }
                    }
                    

                } else {
                    echo "Aucune résultat.";
                }
            }
        }
    }
    

    $id_societe_expediteur = $_POST['id_societe_expediteur'];
    $num_cc = $_POST['num_cc'];
    $nom_mois = $_POST['nom_mois'];
    $numero_compte = $_POST['numero_compte'];
    $numero_ordre = $_POST['numero_ordre'];
    $id_region = $_POST['region'];
    $nom_commune = $_POST['commune'];
    $droit_conformite = floatval($_POST['droit_conformite']);
    $montant_en_lettre = nombreEnLettres($droit_conformite);
    $montant_majuscule = strtoupper($montant_en_lettre);
    
    
    $sql = "SELECT * FROM societe_expediteur WHERE  id_societe_expediteur=$id_societe_expediteur";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resu = $stmt->get_result();
    $row = $resu->fetch_assoc();
    $nom_societe=$row['nom_societe_expediteur'];
    $stat=$row['instat'];
    $nif= $row['nif_societe_expediteur'];

    $sql = "SELECT * FROM direction WHERE  id_direction=$id_direction";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $resu = $stmt->get_result();
    $row = $resu->fetch_assoc();
    $nom_emplacement=$row['lieu_emission'];
    
    if(($confirme_lp1 == "avec")||($confirme_ancien =="avec")){
        $template = '../template/ordre.docx';
    }else{
        $template = '../template/ov2.docx';
    }
    $dix_pourcent = (floatval($montant_redevance) * 10) /100;
    $cinq_pourcent = (floatval($montant_redevance) * 5) /100;
    $deux_pourcent = (floatval($montant_redevance) * 2) /100;
    $quatre_vint_trois = (floatval($montant_redevance) * 83) /100;
    $ristourne1=$ristourne2=$ristourne3=$ristourne_1=$ristourne_2=$ristourne_3=0;
    $dateFormated = "d/m/Y";
    $exercice = $anneeActuelle." ".$numero_ordre."/".$nom_mois;
    $template = new TemplateProcessor($template);
    $date_format = date('d-m-Y', strtotime($dateInsert));
    $date_format_cc = date('d/m/Y', strtotime($date_cc));
    //numéro et date
    $template->cloneBlock('block_name3', 0, true, false, $communes_affiche);
    $template->cloneBlock('block_name1', 0, true, false, $regions_affiche);
    $template->cloneBlock('block_name2', 0, true, false, $rist_commune);
    $template->cloneBlock('block_name', 0, true, false, $rist_region);

    $template->setValue('exercice', $exercice);
    $template->setValue('stat', $stat);
    $template->setValue('numero_compte', $numero_compte);
    $template->setValue('nif', $nif);
    $template->setValue('nom_societe', $nom_societe);

    $template->setValue('montant_redevance', $montant_redevance);
    $template->setValue('montant_ristourne', $montant_ristourne);

    $template->setValue('dix_pourcent', $dix_pourcent);
    $template->setValue('cinq_pourcent', $cinq_pourcent);
    $template->setValue('deux_pourcent', $deux_pourcent);
    $template->setValue('quatre_vint_trois', $quatre_vint_trois);

    $template->setValue('ristourne1', $ristourne1);
    $template->setValue('ristourne2', $ristourne2);
    $template->setValue('ristourne3', $ristourne3);

    $template->setValue('ristourne_1', $ristourne_1);
    $template->setValue('ristourne_2', $ristourne_2);
    $template->setValue('ristourne_3', $ristourne_3);
    
    $template->setValue('nom_emplacement', $nom_emplacement);
    $template->setValue('droit', $droit_conformite);
    $template->setValue('nom_mois', $nom_mois);
    $template->setValue('date-conformite', $date_format_cc);
    $template->setValue('ordre', $numero_ordre);
    $template->setValue('montant_en_lettre', $montant_en_lettre);
    $template->setValue('date_format', $date_format);
    $template->setValue('num_cc', $num_cc);
    $template->setValue('montant_majuscule', $montant_majuscule);

    $destinationFolder =  '../fichier/';
    $numPVClear=preg_replace('/[^a-zA-Z0-9]/', '-', $num_ov);
    $nouveau_nom_fichier2 = $numPVClear . '.docx';

    $outputFilePath = $destinationFolder . $nouveau_nom_fichier2;
    

        $directory = "../fichier";
        $pathToSave = $directory . '/' . $numPVClear . '.pdf';
        $template->saveAs($outputFilePath);
    // Utiliser soffice pour convertir le DOCX en PDF
        $commande = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $outputFilePath . '"';
        shell_exec($commande);

        // Générer un lien de tléchargement vers le fichier PDF
        echo 'Le publipostage a été généré avec succès : <a href="' . $pathToSave . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $outputFilePath . '" download>Télécharger ici DOCX 1 </a>';
        echo $outputFilePath;
        if (unlink($outputFilePath)) {
            echo 'Le fichier a été supprimé avec succès.';
        } else {
            echo 'Une erreur s\'est produite lors de la suppression du fichier.';
        }

        $sql = "UPDATE `data_cc` SET `droit_conformite` ='$droit_conformite', `num_ov` ='$num_ov',`numero_ordre` ='$numero_ordre',`numero_compte` ='$numero_compte', `date_ov`='$date',`mois_payement`='$nom_mois',`scan_ov`='$pathToSave' WHERE id_data_cc='$id_data_cc'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
        $_SESSION['toast_message'] = "Mis à jour réussie.";
        header("Location: https://cdc.minesmada.org/view_user/pv_controle/detail.php?id=" . $id_data_cc);
        exit();
        } else {
        echo "Erreur d'enregistrement" . mysqli_error($conn);
        }

}    
?>