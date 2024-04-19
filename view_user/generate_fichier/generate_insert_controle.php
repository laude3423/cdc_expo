<?php
require_once('../../scripts/db_connect.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../mylibs/phpqrcode/qrlib.php';
include 'nombreEnLettre.php';
include 'recherche_substance.php';
$agent = array();
 $dateFormat = "d-m-Y";
$dateMaintenant = date($dateFormat);

$query = "SELECT * FROM pv_agent_assister WHERE id_data_cc=$id_data";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $resu = $stmt->get_result();
        while ($rowSub = $resu->fetch_assoc()) {
            $agent[]=$rowSub['id_agent'];
        }

     //données utilisés
    if(count($agent) > 0){
            for ($i = 0; $i < count($agent); $i++){
                $query = "SELECT * FROM agent WHERE id_agent = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("i",$agent[$i]);
                $stmt->execute();
                $resu = $stmt->get_result();
                $row = $resu->fetch_assoc();
                $grade_agents["agent_" . $agent[$i]] = $row['grade_agent'];
                $noms_agents["agent_" . $agent[$i]] = $row['nom_agent'];
                $fonction_agents["agent_" . $agent[$i]] = $row['fonction_agent'];
            }
        }
    $queryC = "SELECT * FROM data_cc WHERE id_data_cc=$id_data";
    $resultC = mysqli_query($conn, $queryC);
    $rowC = mysqli_fetch_assoc($resultC);
    $num_facture = $rowC['num_facture'];
    $date_facture = $rowC['date_facture'];
    $date_format_facture = date('d-m-Y', strtotime($date_facture));
    $num_fiche_declaration = $rowC['num_fiche_declaration_pv'];
    $date_fiche_declaration = $rowC['date_fiche_declaration_pv'];
    $date_format_declaration = date('d-m-Y', strtotime($date_fiche_declaration));
    $num_domiciliation = $rowC['num_domiciliation'];
    $num_lp3e = $rowC['num_lp3e_pv'];
    $date_lp3e = $rowC['date_lp3e'];
    $date_format_lp3e = date('d-m-Y', strtotime($date_lp3e));
    $lieu_embarquement = $rowC['lieu_embarquement_pv'];
    $id_societe_expediteur = $rowC['id_societe_expediteur'];
    $id_societe_importateur = $rowC['id_societe_importateur'];
    //recherche sur les société
    $queryS1 = "SELECT * FROM societe_expediteur WHERE id_societe_expediteur=$id_societe_expediteur";
    $resultS1 = mysqli_query($conn, $queryS1);
    $rowS1 = mysqli_fetch_assoc($resultS1);
    $nom_societe_expediteur = $rowS1['nom_societe_expediteur'];
    $adresse_societe_expediteur = $rowS1['adresse_societe_expediteur'];
    
    $queryS2 = "SELECT * FROM societe_importateur WHERE id_societe_importateur=$id_societe_importateur";
    $resultS2 = mysqli_query($conn, $queryS2);
    $rowS2 = mysqli_fetch_assoc($resultS2);
    $nom_societe_importateur = $rowS2['nom_societe_importateur'];
    $adresse_societe_importateur = $rowS2['adresse_societe_importateur'];
    $pays_destination = $rowS2['pays_destination'];
        //type et categorie
    $categorie_brute="";
    $categorie_taille="";
    $type_categorie1="";
    $type_categorie2="";
    $queryR1 = "SELECT c.nom_categorie FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie WHERE c.nom_categorie ='Brute' AND dcc.id_data_cc=$id_data";
        $result1= mysqli_query($conn, $queryR1);
        if(mysqli_num_rows($result1)> 0){
            $categorie_brute="existe";
        }
    $queryR2 = "SELECT c.nom_categorie FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie WHERE c.nom_categorie ='Taillé' AND dcc.id_data_cc=$id_data";
        $result1= mysqli_query($conn, $queryR2);
        if(mysqli_num_rows($result1)> 0){
            $categorie_taille="existe";
        }
    //création de fichier
    if(!empty($categorie_brute)&&!empty($categorie_taille)){
        $templatePathScan =  '../template/model_controleScan2.docx';
        $templatePath =  '../template/model_controle2.docx';
        
    }elseif(!empty($categorie_taille)){
        $templatePathScan =  '../template/model_controleScan.docx';
        $templatePath =  '../template/model_controle.docx';
    }else{
        $templatePathScan =  '../template/model_controleScan.docx';
        $templatePath =  '../template/model_controle.docx';
    }
    $templatePathScanCdc =  '../template/model_scan_cdc.docx';
    $templatePathCdc =  '../template/model_cdc.docx';
    $templateScan = new TemplateProcessor($templatePathScan);
    $template = new TemplateProcessor($templatePath);
    $templateCdcScan = new TemplateProcessor($templatePathScanCdc);
    $templateCdc = new TemplateProcessor($templatePathCdc);
    $test="";
    
    if(!empty($pft)){
        $affiche_word=$afficheWord_pft;
        $type_categorie1='Pierres Fines Taillées:';
        $template->setValue('afficheWord_taille', implode(', ', $afficheWord_pft));
        $templateScan->setValue('afficheWord_taille', implode(', ', $afficheWord_pft));
        if(!empty($ppt)){
            $type_categorie1='Pierres Fines et Pierres Précieuses Taillées:';
            $template->setValue('afficheWord_taille2', implode(', ', $afficheWord_ppt));
            $templateScan->setValue('afficheWord_taille2', implode(', ', $afficheWord_ppt));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_ppt));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_ppt));
            $test="double";
            $affiche_word= array_merge($afficheWord_pft,$afficheWord_ppt);
        }
        if(!empty($pimt)){
            $type_categorie1='Pierres Fines, Pierres industrielles et minerais travaillées';
            $template->setValue('afficheWord_taille2', implode(', ', $afficheWord_pimt));
            $templateScan->setValue('afficheWord_taille2', implode(', ', $afficheWord_pimt));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_pimt));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_pimt));
            $test="double";
            $affiche_word= array_merge($afficheWord_pft,$afficheWord_pimt);
        }
        if(!empty($mpt)){
            $type_categorie1='Pierres Fines et Métaux Précieux Taillées:';
            $template->setValue('afficheWord_taille2', implode(', ', $afficheWord_mpt));
            $templateScan->setValue('afficheWord_taille2', implode(', ', $afficheWord_mpt));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_mpt));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_mpt));
            $test="double";
            $affiche_word= array_merge($afficheWord_pft,$afficheWord_mpt);
        }
        $template->setValue('afficheWord', implode(', ', $afficheWord_pft));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_pft));
        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name_taille', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_taille', 0, true, false, $remplace);
    }elseif(!empty($ppt)){
        $affiche_word=$afficheWord_ppt;
        $type_categorie1='Pierres Précieuses Taillées';
        $template->setValue('afficheWord_taille', implode(', ', $afficheWord_ppt));
        $templateScan->setValue('afficheWord_taille', implode(', ', $afficheWord_ppt));
        $template->setValue('afficheWord', implode(', ', $afficheWord_ppt));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_ppt));
        if(!empty($mpt)){
            $type_categorie1='Métaux Précieux et Pierres Précieuses Taillées';
            $template->setValue('afficheWord_taille2', implode(', ', $afficheWord_mpt));
            $templateScan->setValue('afficheWord_taille2', implode(', ', $afficheWord_mpt));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_mpt));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_mpt));
            $test="double";
            $affiche_word= array_merge($afficheWord_ppt,$afficheWord_mpt);
        }
        if(!empty($pimt)){
            $type_categorie1='Pierres Précieuses et Pierres industrielles et minerais Taillées';
            $template->setValue('afficheWord_taille2', implode(', ', $afficheWord_pimt));
            $templateScan->setValue('afficheWord_taille2', implode(', ', $afficheWord_pimt));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_pimt));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_pimt));
            $test="double";
            $affiche_word= array_merge($afficheWord_ppt,$afficheWord_pimt);
        }
        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name_taille', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_taille', 0, true, false, $remplace);
    }elseif(!empty($pimt)){
        $affiche_word=$afficheWord_pimt;
        $type_categorie1='Pierres Industrielles et Minerais  Travaillées:';
        $template->setValue('afficheWord_taille', implode(', ', $afficheWord_pimt));
        $templateScan->setValue('afficheWord_taille', implode(', ', $afficheWord_pimt));
        $template->setValue('afficheWord', implode(', ', $afficheWord_pimt));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_pimt));
        if(!empty($mpt)){
            $type_categorie1='Métaux Précieux et Pierres Industrielles et Minerais  Travaillées:';
            $template->setValue('afficheWord_taille2', implode(', ', $afficheWord_mpt));
            $templateScan->setValue('afficheWord_taille2', implode(', ', $afficheWord_mpt));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_mpt));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_mpt));
            $test="double";
            $affiche_word= array_merge($afficheWord_mpt,$afficheWord_pimt);
        }
        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name_taille', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_taille', 0, true, false, $remplace);
    }elseif(!empty($mpt)){
        $type_categorie1='Métaux Précieux Taillées:';
        $template->setValue('afficheWord_taille', implode(', ', $afficheWord_mpt));
        $templateScan->setValue('afficheWord_taille', implode(', ', $afficheWord_mpt));
        $template->setValue('afficheWord', implode(', ', $afficheWord_mpt));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_mpt));
        $remplace = generat_file($afficheWord_mpt);
        $templateCdcScan->cloneBlock('block_name_taille', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_taille', 0, true, false, $remplace);
    }else{
        $template->setValue('afficheWord_taille', '');
        $templateScan->setValue('afficheWord_taille', '');
        $template->setValue('afficheWord', '');
        $templateScan->setValue('afficheWord', '');
        $remplace = array();
        $remplace[] = array('substance'=>'');
        $templateCdcScan->cloneBlock('block_name_taille', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_taille', 0, true, false, $remplace);
    }
    if(!empty($pfb)){
        $affiche_word=$afficheWord_pfb;
        $type_categorie2='Pierres Fines Brutes:';
        $template->setValue('afficheWord_brute', implode(', ', $afficheWord_pfb));
        $templateScan->setValue('afficheWord_brute', implode(', ', $afficheWord_pfb));
        $template->setValue('afficheWord', implode(', ', $afficheWord_pfb));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_pfb));
        if(!empty($ppb)){
            $type_categorie2='Pierres Fines et Pierres Précieuses Brutes:';
            $template->setValue('afficheWord_brute2', implode(', ', $afficheWord_ppb));
            $templateScan->setValue('afficheWord_brute2', implode(', ', $afficheWord_ppb));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_ppb));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_ppb));
            $test="double";
            $affiche_word= array_merge($afficheWord_pfb,$afficheWord_ppb);
        }
        if(!empty($pimb)){
            $type_categorie2='Pierres Fines et Pierres Industrielles et Minerais Brutes:';
            $template->setValue('afficheWord_brute2', implode(', ', $afficheWord_pimb));
            $templateScan->setValue('afficheWord_brute2', implode(', ', $afficheWord_pimb));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_pimb));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_pimb));
            $test="double";
            $affiche_word= array_merge($afficheWord_pfb,$afficheWord_pimb);
        }
        if(!empty($mpb)){
            $type_categorie2='Métaux Précieux et Pierres Fines Brutes:';
            $template->setValue('afficheWord_brute2', implode(', ', $afficheWord_mpb));
            $templateScan->setValue('afficheWord_brute2', implode(', ', $afficheWord_mpb));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_mpb));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_mpb));
            $test="double";
            $affiche_word= array_merge($afficheWord_pfb,$afficheWord_mpb);
        }
        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name_brute', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_brute', 0, true, false, $remplace);
    }elseif(!empty($ppb)){
        $affiche_word=$afficheWord_ppb;
        $type_categorie2='Pierres Précieuses Brutes:';
        $template->setValue('afficheWord_brute', implode(', ', $afficheWord_ppb));
        $templateScan->setValue('afficheWord_brute', implode(', ', $afficheWord_ppb));
        $template->setValue('afficheWord', implode(', ', $afficheWord_ppb));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_ppb));
        if(!empty($pimb)){
            $type_categorie2='Pierres Précieuses, Pierres Industrielles et Minerais Brutes:';
            $template->setValue('afficheWord_brute2', implode(', ', $afficheWord_pimb));
            $templateScan->setValue('afficheWord_brute2', implode(', ', $afficheWord_pimb));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_pimb));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_pimb));
            $test="double";
            $affiche_word= array_merge($afficheWord_ppb,$afficheWord_pimb);
        }
        if(!empty($mpb)){
            $type_categorie1='Métaux Précieux Pierres Pierres Précieuses Brutes:';
            $template->setValue('afficheWord_brute2', implode(', ', $afficheWord_mpb));
            $templateScan->setValue('afficheWord_brute2', implode(', ', $afficheWord_mpb));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_mpb));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_mpb));
            $test="double";
            $affiche_word= array_merge($afficheWord_ppb,$afficheWord_mpb);
        }
        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name_brute', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_brute', 0, true, false, $remplace);
    }elseif(!empty($pimb)){
        $type_categorie2='Pierres industrielles et minerais Brutes:';
        $template->setValue('afficheWord_brute', implode(', ', $afficheWord_pimb));
        $templateScan->setValue('afficheWord_brute', implode(', ', $afficheWord_pimb));
        $template->setValue('afficheWord', implode(', ', $afficheWord_pimb));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_pimb));
        if(!empty($mpb)){
            $type_categorie2='Métaux Précieux etPierres Industrielles et Minerais Brutes:';
            $template->setValue('afficheWord_brute2', implode(', ', $afficheWord_mpb));
            $templateScan->setValue('afficheWord_brute2', implode(', ', $afficheWord_mpb));
            $template->setValue('afficheWord2', implode(', ', $afficheWord_mpb));
            $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_mpb));
            $test="double";
        }
        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name_brute', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_brute', 0, true, false, $remplace);
    }elseif(!empty($mpb)){
        $type_categorie2='Métaux Précieux Brutes:';
        $template->setValue('afficheWord_brute', implode(', ', $afficheWord_mpb));
        $templateScan->setValue('afficheWord_brute', implode(', ', $afficheWord_mpb));
        $template->setValue('afficheWord', implode(', ', $afficheWord_mpb));
        $templateScan->setValue('afficheWord', implode(', ', $afficheWord_mpb));
        $remplace = generat_file($afficheWord_mpb);
        $templateScan->cloneBlock('block_name_brute', 0, true, false, $remplace);
        $template->cloneBlock('block_name_brute', 0, true, false, $remplace);
    }else{
        $remplace = array();
        $remplace[] = array('substance'=>'');
        $templateCdcScan->cloneBlock('block_name_brute', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name_brute', 0, true, false, $remplace);
        $template->setValue('afficheWord_brute', '');
        $templateScan->setValue('afficheWord_brute', '');
        $template->setValue('afficheWord', '');
        $templateScan->setValue('afficheWord', '');
    }

    //generate fichier
    if(empty($test)){
        $templateScan->setValue('afficheWord_brute2', '');
        $template->setValue('afficheWord_brute2', '');
        $templateScan->setValue('afficheWord_taille2', '');
        $template->setValue('afficheWord_taille2', '');
        $templateScan->setValue('afficheWord2', '');
        $template->setValue('afficheWord2', '');
    }
    //pv
    $categorie_existe=$type_categorie1.$type_categorie2;

    $templateScan->setValue('num_pv', $num_pv);
    $templateScan->setValue('num_pv2', $num_pv);
    //societe
    $templateScan->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateScan->setValue('nom_societe_imp', $nom_societe_importateur);
    $templateScan->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $templateScan->setValue('adresse_societe_exp', $adresse_societe_expediteur);
    $templateScan->setValue('destination_finale', $pays_destination);
    $templateScan->setValue('type_categorie1', $type_categorie1);
    $templateScan->setValue('type_categorie2', $type_categorie2);
    $templateScan->setValue('date', $dateEnTexte);
    $templateScan->setValue('num_facture', $num_facture);
    $templateScan->setValue('date_facture', $date_format_facture);
    $templateScan->setValue('num_fiche_declaration', $num_fiche_declaration);
    $templateScan->setValue('date_fiche_declaration', $date_format_declaration);
    $templateScan->setValue('num_domiciliation', $num_domiciliation);
    $templateScan->setValue('num_lp3e', $num_lp3e);
    $templateScan->setValue('categorie', $categorie_existe);
    $templateScan->setValue('date_lp3e', $date_format_lp3e);
    $templateScan->setValue('lieu_embarquement', $lieu_embarquement);
    $templateScan->setValue('mode_emballage', $mode_emballage);
    $templateScan->setValue('date_creation', $dateMaintenant);
    $templateScan->setValue('lieu_controle', $lieu_controle);
    $remplace_agent = array();
    foreach ($agent as $agent_id) {
        $agent_concat = "- " . $grade_agents["agent_" . $agent_id] . " " . $noms_agents["agent_" . $agent_id] . ", " . $fonction_agents["agent_" . $agent_id] . "\n";
        $remplace_agent[] = array('nom'=>$agent_concat);
    }
    $templateScan->cloneBlock('block_name', 0, true, false, $remplace_agent);

    $destinationFolder =  '../fichier/';
    $numPVClear=preg_replace('/[^a-zA-Z0-9]/', '-', $num_pv);
    $nouveau_nom_fichier2 = $numPVClear . '.docx';

    $outputFilePath = $destinationFolder . $nouveau_nom_fichier2;
    $templateScan->saveAs($outputFilePath);



        // Chemin pour enregistrer le fichier PDF
        $directory = "../fichier";
        $pathToSave = $directory . '/' . $numPVClear . '.pdf';
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
    $template->setValue('num_pv', $num_pv);
    $template->setValue('num_pv2', $num_pv);
    //societe
    $template->setValue('nom_societe_exp', $nom_societe_expediteur);
    $template->setValue('nom_societe_imp', $nom_societe_importateur);
    $template->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $template->setValue('adresse_societe_exp', $adresse_societe_expediteur);
    $template->setValue('destination_finale', $pays_destination);
    $template->setValue('type_categorie1', $type_categorie1);
    $template->setValue('type_categorie2', $type_categorie2);
    $template->setValue('date', $dateEnTexte);
    $template->setValue('num_facture', $num_facture);
    $template->setValue('date_facture', $date_facture);
    $template->setValue('num_fiche_declaration', $num_fiche_declaration);
    $template->setValue('date_fiche_declaration', $date_fiche_declaration);
    $template->setValue('num_domiciliation', $num_domiciliation);
    $template->setValue('num_lp3e', $num_lp3e);
    $template->setValue('date_lp3e', $date_lp3e);
    $template->setValue('categorie', $categorie_existe);
    $template->setValue('lieu_embarquement', $lieu_embarquement);
    $template->setValue('mode_emballage', $mode_emballage);
    $template->setValue('date_creation', $dateMaintenant);
    $template->setValue('lieu_controle', $lieu_controle);
    $template->cloneBlock('block_name', 0, true, false, $remplace_agent);
    

        // Enregistrer le nouveau document DOCX
        $nouveau_nom_fichierQR = $numPVClear  . '.docx';
        $outputFilePathQR = $destinationFolder . $nouveau_nom_fichierQR;
        $template->saveAs($outputFilePathQR);

        // //------------------------------------------------------------------------------------------------------------------------------

        // //Generer le QR COde
        $tempDir = '../fichier_scan/';
         //$lien = 'https://lp1.minesmada.org/' .$pathToSave;
        $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsControle.php?id_data_cc='.$id_data;
        $qrcode_name = 'qrcode_test';
        QRcode::png($lien, $tempDir.''.$qrcode_name.'.jpg', QR_ECLEVEL_L, 5);


        // // Mettre le QR Code dans le fichier Word
        $templateProcessor = new TemplateProcessor($outputFilePathQR);

        $directoryQR = '../fichier_scan/';

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

        // Convertir le fichier Word en PDF en utilisant la commande "soffice"
        $command = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $pathToSaveNew . '"';
        shell_exec($command);

        echo 'Le publipostage a été généré avec succès : <a href="' . $pathToSavePDF . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $pathToSaveNew . '" download>Télécharger ici DOCX 1 </a>';
        unlink($pathToSaveNew);
    //-------------------------------------------------------
    //generate file certificat de conformité
    $templateCdcScan->setValue('num_cc', $num_cc);
    $templateCdcScan->setValue('date_maintenant', $dateMaintenant);
    $templateCdcScan->setValue('num_declaration', $num_fiche_declaration);
    $templateCdcScan->setValue('date_declaration', $date_format_declaration);
    $templateCdcScan->setValue('num_pv_controle', $num_pv);
    $templateCdcScan->setValue('date_pv_controle', $dateMaintenant);
    $templateCdcScan->setValue('nom_responsable', '');
    $templateCdcScan->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateCdcScan->setValue('responsable_mandate', '');
    $templateCdcScan->setValue('type_categorie1', $type_categorie1);
    $templateCdcScan->setValue('type_categorie2', $type_categorie2);
    
    $destinationFolder =  '../fichier/';
    $numCCClear=preg_replace('/[^a-zA-Z0-9]/', '-', $num_cc);
    $nouveau_nom = $numCCClear . '.docx';

    $outputFilePathCC = $destinationFolder . $nouveau_nom;
    $templateCdcScan->saveAs($outputFilePathCC);

        $directory = "../fichier";
        $lien_cc = $directory . '/' . $numCCClear . '.pdf';

        $commande = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $outputFilePathCC . '"';
        shell_exec($commande);

        echo 'Le publipostage a été généré avec succès : <a href="' . $lien_cc . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $outputFilePathCC . '" download>Télécharger ici DOCX 1 </a>';
        unlink($outputFilePathCC);
        //deuxième fichier
        $templateCdc->setValue('num_cc', $num_cc);
        $templateCdc->setValue('date_maintenant', $dateMaintenant);
        $templateCdc->setValue('num_declaration', $num_fiche_declaration);
        $templateCdc->setValue('date_declaration', $date_format_declaration);
        $templateCdc->setValue('num_pv_controle', $num_pv);
        $templateCdc->setValue('date_pv_controle', $dateMaintenant);
        $templateCdc->setValue('nom_responsable', '');
        $templateCdc->setValue('nom_societe_exp', $nom_societe_expediteur);
        $templateCdc->setValue('responsable_mandate', '');
        $templateCdc->setValue('type_categorie1', $type_categorie1);
        $templateCdc->setValue('type_categorie2', $type_categorie2);
        $nouveau_nom2 = $numCCClear  . '.docx';
        $outputFilePathQRCC = $destinationFolder . $nouveau_nom2;
        $templateCdc->saveAs($outputFilePathQRCC);

        $tempDir = '../fichier_scan/';
        $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsControle.php?id_data_cc='.$id_data;
        $qrcode_name = 'qrcode_test';
        QRcode::png($lien, $tempDir.''.$qrcode_name.'.jpg', QR_ECLEVEL_L, 5);

        $templateProcessor2 = new TemplateProcessor($outputFilePathQRCC);

        $directoryQR = '../fichier_scan/';

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

        // Convertir le fichier Word en PDF en utilisant la commande "soffice"
        $command = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $pathToSaveNewCC . '"';
        shell_exec($command);

        echo 'Le publipostage a été généré avec succès : <a href="' . $pj_cc . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $pathToSaveNewCC . '" download>Télécharger ici DOCX 1 </a>';
        unlink($pathToSaveNewCC);
    function generat_file($affiche) {
        $replacements = array();
        foreach ($affiche as $valeur) {
            $replacements[] = array('substance' => $valeur);
        }

        return $replacements;
    }
?>