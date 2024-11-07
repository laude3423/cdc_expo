<?php
require_once('../../../scripts/db_connect.php');
require '../../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../../mylibs/phpqrcode/qrlib.php';
include '../../generate_fichier/nombre_en_lettre.php';
$agent = array();
$dateFormat = "d-m-Y";
$dateMaintenant = date($dateFormat);

$date = new DateTime();
$dateFormate = $date->format("d F Y");
$date_maintenant = strftime("%e %B %Y", $date->getTimestamp());

// Remplacer le nom du mois anglais par le nom du mois français
$mois_anglais = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$mois_francais = array('janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
$date_maintenant = str_replace($mois_anglais, $mois_francais, $date_maintenant);

echo $date_maintenant;

    $queryS1 = "SELECT * FROM societe_expediteur WHERE id_societe_expediteur=$id_societe_expediteur";
    $resultS1 = mysqli_query($conn, $queryS1);
    $rowS1 = mysqli_fetch_assoc($resultS1);
    $nom_societe_expediteur = $rowS1['nom_societe_expediteur'];
    $adresse_societe_expediteur = $rowS1['adresse_societe_expediteur'];
    $nom_responsable=$rowS1['responsable'];
    
    $queryS2 = "SELECT * FROM societe_importateur WHERE id_societe_importateur=$id_societe_importateur";
    $resultS2 = mysqli_query($conn, $queryS2);
    $rowS2 = mysqli_fetch_assoc($resultS2);
    $nom_societe_importateur = $rowS2['nom_societe_importateur'];
    $adresse_societe_importateur = $rowS2['adresse_societe_importateur'];
    $pays_destination = $rowS2['pays_destination'];

    $query = "SELECT sub.nom_substance FROM contenu_attestation AS catt 
          LEFT JOIN substance2 AS sub ON sub.id_substance = catt.id_substance 
          WHERE catt.id_data_cc = ?";

            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_data);
            $stmt->execute();
            $result = $stmt->get_result();

            $substances = [];
            while ($row = $result->fetch_assoc()) {
                $nom_substance = $row['nom_substance'];
                
                // Vérifie si le premier caractère est une voyelle (en minuscule ou majuscule)
                if (preg_match('/^[aeiouAEIOU]/', $nom_substance)) {
                    $substances[] = "d'" . $nom_substance;
                } else {
                    $substances[] = "de " . $nom_substance;
                }
            }

            // Join the substance names with commas, and replace the last comma with ' et '
            $substances_sentence = implode(', ', $substances);
            $substances_sentence = preg_replace('/, ([^,]+)$/', ' et $1', $substances_sentence);

            
            $query= "SELECT SUM(poids_attestation) AS somme, unite FROM contenu_attestation WHERE id_data_cc=$id_data";
            $result= mysqli_query($conn, $query);
            $row= mysqli_fetch_assoc($result);
            $somme = floatval($row['somme']);
            $unite = $row['unite'];
            $nombre = nombreEnLettres($somme);
            $unite_affiche="";
            if(($unite=='kg')&&($somme < 2)){
                $unite_affiche="kilogramme";
            }else if(($unite=='kg')&&($somme >= 2)){
                $unite_affiche="kilogrammes";
            }else if(($unite=='g')&&($somme < 2)){
                $unite_affiche="gramme";
            }else if(($unite=='g')&&($somme >= 2)){
                $unite_affiche="grammes";
            }
            $texte = '-'.$nombre .' '.$unite_affiche.' '.$substances_sentence.'.';

    //création de fichier
    
    $date_format_declaration = date('d-m-Y', strtotime($date_fiche_declaration));
    $date_attestation = date('d-m-Y', strtotime($date_attestation));
    $date_fiche_controle = date('d-m-Y', strtotime($date_fiche_controle));
    $date_demande_autorisation = date('d-m-Y', strtotime($date_demande_autorisation));
    $date_engagement = date('d-m-Y', strtotime($date_engagement));
    
    $templatePathScan =  '../../template/model_controleScan_nc.docx';
    $templatePath =  '../../template/model_controle_nc.docx';
    
    $templatePathScanCdc =  '../../template/model_scan_cdc_nc.docx';
    $templatePathCdc =  '../../template/model_cdc_nc.docx';
    $templateScan = new TemplateProcessor($templatePathScan);
    $template = new TemplateProcessor($templatePath);
    $templateCdcScan = new TemplateProcessor($templatePathScanCdc);
    $templateCdc = new TemplateProcessor($templatePathCdc);
   
$entete1="
            MINISTERE DES MINES                
            -----------------------                
                SECRETARIAT GENERAL DES MINES                 
                                ----------------------
                                            DIRECTION ".$typeDirection." ".$nomDirection."
                                                                ---------------------
";
 $entete2="
            MINISTERE DES MINES                
            -----------------------                
                SECRETARIAT GENERAL DES MINES                 
                                ----------------------                
                                        DIRECTION GENERALE DES MINES
                                                ---------------------
                                                    DIRECTION DES EXPORTATIONS ET VALEURS
                                                        --------------------- 
                                                            GUICHET UNIQUE D'EXPORTATION
                                                                ---------------------
";
    $nom_entete1="Directeur des Mines et de la Géologie";
    $nom_entete2="Directeur des Exportations et de la Valeur";
    $nom_direction1 = "la DIRECTION ".$typeDirection." ".$nomDirection;
    $nom_direction2 = "la Direction des Exportations et de la Valeur";
    $entete="";
    $num_fiche_declaration = $num_fiche_declaration . " ";
    $nom_entete="";
    $vrai_nom_direction="";
    if($groupeID === 1){
        $entete=$entete1;
        $nom_entete=$nom_entete1;
        $vrai_nom_direction=$nom_direction1;
    }else{
        $entete=$entete2;
        $nom_entete=$nom_entete2;
        $vrai_nom_direction = $nom_direction2;
    }

    $templateScan->setValue('entete', $entete);
    $templateScan->setValue('num_pv', $num_pv);
    $templateScan->setValue('num_pv2', $num_pv);
    //societe
    $templateScan->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateScan->setValue('nom_societe_imp', $nom_societe_importateur);
    $templateScan->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $templateScan->setValue('adresse_societe_exp', $adresse_societe_expediteur);
    $templateScan->setValue('destination_finale', $pays_destination);
    $templateScan->setValue('texte', $texte);
    // $templateScan->setValue('type_categorie2', $type_categorie2);
    $templateScan->setValue('numero_attestation', $numero_attestation);
    $templateScan->setValue('date_attestation', $date_attestation);
    $templateScan->setValue('num_fiche_declaration', $num_fiche_declaration);
    $templateScan->setValue('date_fiche_declaration', $date_format_declaration);

    //$templateScan->setValue('total_general', $poidsTotal_generale);
    $templateScan->setValue('lieu_embarquement', $lieu_embarquement);
    $templateScan->setValue('mode_emballage', $mode_emballage);
    $templateScan->setValue('date_creation', $dateMaintenant);
    $templateScan->setValue('lieu_controle', $lieu_controle);
    $templateScan->setValue('lieu_empotage', $lieu_empotage);
    $templateScan->setValue('date_demande_autorisation', $date_demande_autorisation);
    $templateScan->setValue('date_engagement', $date_engagement);
    $templateScan->setValue('date_fiche', $date_fiche_controle);
    $templateScan->setValue('numero_fiche', $num_fiche_controle);
   

    $destinationFolder =  '../../fichier/';
    $destinationFolder2 =  '../fichier';
    $numPVClear=preg_replace('/[^a-zA-Z0-9]/', '-', $num_pv);
    $nouveau_nom_fichier2 = $numPVClear . '.docx';

    $outputFilePath = $destinationFolder . $nouveau_nom_fichier2;
    $templateScan->saveAs($outputFilePath);



        // Chemin pour enregistrer le fichier PDF
        $directory = "../../fichier";
        $pathToSave = $directory . '/' . $numPVClear . '.pdf';
        $pathToSavePV = $destinationFolder2. '/' . $numPVClear . '.pdf';
    // Utiliser soffice pour convertir le DOCX en PDF
        $commande = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $outputFilePath . '"';
        shell_exec($commande);

        // Générer un lien de tléchargement vers le fichier PDF
        echo 'Le publipostage a été généré avec succès : <a href="' . $pathToSave . '" download>Télécharger Scan ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $outputFilePath . '" download>Télécharger ici DOCX 1 </a>';
        unlink($outputFilePath);

        //------------------------------------------------------------------------------------------
         //Deuxieme template
        
         //societe
    $template->setValue('entete', $entete);
    $template->setValue('num_pv', $num_pv);
    //$template->setValue('num_pv2', $num_pv);
    //societe
    $template->setValue('nom_societe_exp', $nom_societe_expediteur);
    $template->setValue('nom_societe_imp', $nom_societe_importateur);
    $template->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $template->setValue('adresse_societe_exp', $adresse_societe_expediteur);
    $template->setValue('destination_finale', $pays_destination);
    $template->setValue('texte', $texte);
    // $template->setValue('type_categorie2', $type_categorie2);
    $template->setValue('numero_attestation', $numero_attestation);
    $template->setValue('date_attestation', $date_attestation);
    $template->setValue('num_fiche_declaration', $num_fiche_declaration);
    $template->setValue('date_fiche_declaration', $date_format_declaration);

    //$template->setValue('total_general', $poidsTotal_generale);
    $template->setValue('lieu_embarquement', $lieu_embarquement);
    $template->setValue('mode_emballage', $mode_emballage);
    $template->setValue('date_creation', $dateMaintenant);
    $template->setValue('lieu_controle', $lieu_controle);
    $template->setValue('lieu_empotage', $lieu_empotage);
    $template->setValue('date_demande_autorisation', $date_demande_autorisation);
    $template->setValue('date_engagement', $date_engagement);
    $template->setValue('date_fiche', $date_fiche_controle);
    $template->setValue('numero_fiche', $num_fiche_controle);

        // Enregistrer le nouveau document DOCX
        $nouveau_nom_fichierQR = $numPVClear  . '_QR.docx';
        $outputFilePathQR = $destinationFolder . $nouveau_nom_fichierQR;
        $template->saveAs($outputFilePathQR);

        // //------------------------------------------------------------------------------------------------------------------------------

        // //Generer le QR COde
        $tempDir = '../../fichier_scan/';
         //$lien = 'https://lp1.minesmada.org/' .$pathToSave;
        $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsControle.php?id_data_cc='.$id_data;
        $qrcode_name = 'qrcode_test';
        QRcode::png($lien, $tempDir.''.$qrcode_name.'.jpg', QR_ECLEVEL_L, 5);


        // // Mettre le QR Code dans le fichier Word
        $templateProcessor = new TemplateProcessor($outputFilePathQR);

        $directoryQR = '../../fichier_scan/';

        $templateProcessor->setImageValue(
            'qrcode',
            [
                'path' => $directoryQR.$qrcode_name.'.jpg',
                'width' => 156, //=4cm
                'height' => 156,
                
            ]
        );

        $newNameQR = $numPVClear . '_QR.docx';
        $pathToSaveNew = $destinationFolder . $newNameQR;
        $templateProcessor->saveAs($pathToSaveNew);
        // //---------------------------------------------------------------------------------------------------------------------------


        // // Nom du fichier PDF résultant
        $pdfFileName = $numPVClear . '_QR.pdf';
        $pathToSavePDF = $directory . '/' . $pdfFileName;
        $pathToSavePDFPV = $destinationFolder2 .'/'.$pdfFileName;
        // Convertir le fichier Word en PDF en utilisant la commande "soffice"
        $command = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $pathToSaveNew . '"';
        shell_exec($command);

        echo 'Le publipostage a été généré avec succès : <a href="' . $pathToSavePDF . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $pathToSaveNew . '" download>Télécharger ici DOCX 1 </a>';
        unlink($pathToSaveNew);
    //-------------------------------------------------------
    //generate file certificat de conformité
    $templateCdcScan->setValue('num_cc', $num_cc);
    $templateCdcScan->setValue('entete', $entete);
    $templateCdcScan->setValue('num_cc', $num_cc);
    $templateCdcScan->setValue('texte', $texte);
    $templateCdcScan->setValue('vrai_nom_direction', $vrai_nom_direction);
    $templateCdcScan->setValue('nom_user', "Direction des Exportations et de la Valeur");
    $templateCdcScan->setValue('date_maintenant', $date_maintenant);
    $templateCdcScan->setValue('num_declaration', $num_fiche_declaration);
    $templateCdcScan->setValue('date_declaration', $date_format_declaration);
    $templateCdcScan->setValue('num_pv_controle', $num_pv);
    $templateCdcScan->setValue('date_attestation', $date_attestation);
    $templateCdcScan->setValue('date_pv_controle', $dateMaintenant);
    $templateCdcScan->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateCdcScan->setValue('addresse_societe_exp', $adresse_societe_expediteur);
    $templateCdcScan->setValue('nom_societe_imp', $nom_societe_importateur);
    $templateCdcScan->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $templateCdcScan->setValue('nom_responsable', $nom_responsable);
    $templateCdcScan->setValue('nom_responsable_imp', $nom_responsable);
    $templateCdcScan->setValue('destination_finale', $pays_destination);
    $templateCdcScan->setValue('nom_entete', $nom_entete);
    
    $destinationFolder =  '../../fichier/';
    $numCCClear=preg_replace('/[^a-zA-Z0-9]/', '-', $num_cc);
    $nouveau_nom = $numCCClear . '.docx';

    $outputFilePathCC = $destinationFolder . $nouveau_nom;
    $templateCdcScan->saveAs($outputFilePathCC);

        $directory = "../../fichier";
        $lien_cc = $directory . '/' . $numCCClear . '.pdf';
        $lien_CDC = $destinationFolder2 . '/' . $numCCClear . '.pdf';

        $commande = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $outputFilePathCC . '"';
        shell_exec($commande);

        echo 'Le publipostage a été généré avec succès : <a href="' . $lien_cc . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $outputFilePathCC . '" download>Télécharger ici DOCX 1 </a>';
        unlink($outputFilePathCC);
        //deuxième fichier
        $templateCdc->setValue('entete', $entete);
        $templateCdc->setValue('num_cc', $num_cc);
        $templateCdc->setValue('texte', $texte);
        $templateCdc->setValue('vrai_nom_direction', $vrai_nom_direction);
        $templateCdc->setValue('nom_user', "Direction des Exportations et de la Valeur");
        $templateCdc->setValue('date_maintenant', $date_maintenant);
        $templateCdc->setValue('num_declaration', $num_fiche_declaration);
        $templateCdc->setValue('date_declaration', $date_format_declaration);
        $templateCdc->setValue('num_pv_controle', $num_pv);
        $templateCdc->setValue('date_attestation', $date_attestation);
        $templateCdc->setValue('date_pv_controle', $dateMaintenant);
        $templateCdc->setValue('nom_societe_exp', $nom_societe_expediteur);
        $templateCdc->setValue('addresse_societe_exp', $adresse_societe_expediteur);
        $templateCdc->setValue('nom_societe_imp', $nom_societe_importateur);
        $templateCdc->setValue('adresse_societe_imp', $adresse_societe_importateur);
        $templateCdc->setValue('nom_responsable', $nom_responsable);
        $templateCdc->setValue('nom_responsable_imp', $nom_responsable);
        $templateCdc->setValue('destination_finale', $pays_destination);
        $templateCdc->setValue('nom_entete', $nom_entete);
        
        $nouveau_nom2 = $numCCClear  . '_QR.docx';
        $outputFilePathQRCC = $destinationFolder . $nouveau_nom2;
        $templateCdc->saveAs($outputFilePathQRCC);

        $tempDir = '../../fichier_scan/';
        $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsCdc.php?id_data_cc='.$id_data;
        $qrcode_name = 'qrcode_test';
        QRcode::png($lien, $tempDir.''.$qrcode_name.'.jpg', QR_ECLEVEL_L, 5);

        $templateProcessor2 = new TemplateProcessor($outputFilePathQRCC);

        $directoryQR = '../../fichier_scan/';

        $templateProcessor2->setImageValue(
            'qrcode',
            [
                'path' => $directoryQR.$qrcode_name.'.jpg',
                'width' => 156, //=4cm
                'height' => 156,
                
            ]
        );

        $nomQr = $numCCClear . '_QR.docx';
        $pathToSaveNewCC = $destinationFolder . $nomQr;
        $templateProcessor2->saveAs($pathToSaveNewCC);
        // //---------------------------------------------------------------------------------------------------------------------------


        // // Nom du fichier PDF résultant
        $pdfFileName = $numCCClear . '_QR.pdf';
        $pj_cc = $directory . '/' . $pdfFileName;
        $pj_CDC = $destinationFolder2 . '/' . $pdfFileName;

        // Convertir le fichier Word en PDF en utilisant la commande "soffice"
        $command = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $pathToSaveNewCC . '"';
        shell_exec($command);
        echo $pj_CDC;
        echo 'Le publipostage a été généré avec succès : <a href="' . $pj_cc . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $pathToSaveNewCC . '" download>Télécharger ici DOCX 1 </a>';
        unlink($pathToSaveNewCC);
    function generat_file($affiche) {
        $replacements = array();
        foreach ($affiche as $valeur) {
            $replacements[] = array('contenu' => $valeur);
        }

        return $replacements;
    }
?>