<?php
require_once('../../scripts/db_connect.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../mylibs/phpqrcode/qrlib.php';
include 'nombre_en_lettre.php';
include 'recherche_substance.php';
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
$query = "SELECT pv.*, ag.* FROM pv_agent_assister AS pv INNER JOIN agent AS ag ON pv.id_agent = ag.id_agent 
WHERE id_data_cc=$id_data";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $resu = $stmt->get_result();
        while ($rowSub = $resu->fetch_assoc()) {
            if(($rowSub['fonction_agent']=='Chef de Division Exportation Minière')||($rowSub['fonction_agent']=='Responsable qualité Laboratoire des Mines'))
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
    $nom_responsable=$rowS1['responsable'];
    
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
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie WHERE c.nom_categorie ='Taillée' AND dcc.id_data_cc=$id_data";
        $result1= mysqli_query($conn, $queryR2);
        if(mysqli_num_rows($result1)> 0){
            $categorie_taille="existe";
        }
    //recherche poids_total
    $poids_total_g=0;
    $poids_total_kg=0;
    $poidsTotal_generale="";
    $queryR5 = "SELECT SUM(poids_facture) AS sommePoids_g FROM contenu_facture WHERE unite_poids_facture='g' AND id_data_cc=$id_data";
        $result5= mysqli_query($conn, $queryR5);
        if(mysqli_num_rows($result5)> 0){
            $row5 = mysqli_fetch_assoc($result5);
            $poids_total_g=$row5['sommePoids_g'];
        }
     $queryR6 = "SELECT SUM(poids_facture) AS sommePoids_kg FROM contenu_facture WHERE unite_poids_facture='kg' AND id_data_cc=$id_data";
        $result6= mysqli_query($conn, $queryR6);
        if(mysqli_num_rows($result6)> 0){
            $row6 = mysqli_fetch_assoc($result6);
            $poids_total_kg=$row6['sommePoids_kg'];
        }
    $type1="gemme";
    $type2="ordianire";
    if (($poids_total_g > 0) && ($poids_total_kg > 0)) {
        $poidsTotal1 = poids_total($poids_total_g, $type1);
        $poidsTotal2 = poids_total($poids_total_kg, $type2);
        $poidsTotal_generale = $poidsTotal1 . ' et ' . $poidsTotal2;
    } elseif (($poids_total_kg > 0) && ($poids_total_g == 0)) {
        $poidsTotal_generale = poids_total($poids_total_kg, $type2);
    } elseif (($poids_total_g > 0) && ($poids_total_kg == 0)) {
        $poidsTotal_generale = poids_total($poids_total_g, $type1);
    } else {
        $poidsTotal_generale = "Aucune";
       
    }
    function poids_total($poids, $type){
         $nombreFormat = number_format($poids, 2, '.', '');
            // Séparer la partie avant et après la virgule
        $nombreExplode = explode('.', $nombreFormat);
        $nombreAvant = $nombreExplode[0];
        $nombreApres = $nombreExplode[1];
        $nombreApresLettre='';
        if ($nombreApres > 0) {
            $nombreCompare = comparer($nombreApres);
            $nombreApresLettre = nombreEnLettres($nombreCompare);
        }
        $nombreAvantLettre = nombreEnLettres($nombreAvant);
        if($type=="gemme"){
            $poidsTotal=$nombreAvantLettre." grammes ". $nombreApresLettre . '('.$nombreFormat.'grs) de pierres gemmes et ou méteaux précieux';
        }else{
            $poidsTotal=$nombreAvantLettre." kilogrammes ". $nombreApresLettre . '('.$nombreFormat.'kgs) de pierres ordinaires';
        }
        return $poidsTotal;
    }
    //création de fichier
    
    $templatePathScan =  '../template/model_controleScan.docx';
    $templatePath =  '../template/model_controle.docx';
    
    $templatePathScanCdc =  '../template/model_scan_cdc.docx';
    $templatePathCdc =  '../template/model_cdc.docx';
    $templateScan = new TemplateProcessor($templatePathScan);
    $template = new TemplateProcessor($templatePath);
    $templateCdcScan = new TemplateProcessor($templatePathScanCdc);
    $templateCdc = new TemplateProcessor($templatePathCdc);
    $test="";
    
    if (!empty($pft)) {
        $affiche_word = $afficheWord_pft;
        echo 'consulter3';

        $wordArrays = [
            'ppt' => $afficheWord_ppt,
            'pimt' => $afficheWord_pimt,
            'mpt' => $afficheWord_mpt,
            'ft' => $afficheWord_ft,
            'pa' => $afficheWord_pa,
            'ppb' => $afficheWord_ppb,
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
            'pfb' => $afficheWord_pfb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }elseif(!empty($ppt)){
        $affiche_word=$afficheWord_ppt;
        $wordArrays = [
            'pimt' => $afficheWord_pimt,
            'mpt' => $afficheWord_mpt,
            'ft' => $afficheWord_ft,
            'pa' => $afficheWord_pa,
            'ppb' => $afficheWord_ppb,
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
            'pfb' => $afficheWord_pfb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }elseif(!empty($pimt)){
        $affiche_word=$afficheWord_pimt;
        $wordArrays = [
            'mpt' => $afficheWord_mpt,
            'ft' => $afficheWord_ft,
            'pa' => $afficheWord_pa,
            'ppb' => $afficheWord_ppb,
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
            'pfb' => $afficheWord_pfb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }elseif(!empty($mpt)){
        $affiche_word=$afficheWord_mpt;

        $wordArrays = [
            'ft' => $afficheWord_ft,
            'pa' => $afficheWord_pa,
            'ppb' => $afficheWord_ppb,
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
            'pfb' => $afficheWord_pfb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($pa)){
        $affiche_word=$afficheWord_pa;

        $wordArrays = [
            'ft' => $afficheWord_ft,
            'ppb' => $afficheWord_ppb,
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
            'pfb' => $afficheWord_pfb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($ft)){
        $affiche_word=$afficheWord_ft;

        $wordArrays = [
            'ppb' => $afficheWord_ppb,
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
            'pfb' => $afficheWord_pfb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($pfb)){
        $affiche_word=$afficheWord_pfb;
        echo "consulter4";

        $wordArrays = [
            'ppb' => $afficheWord_ppb,
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($ppb)){
        $affiche_word=$afficheWord_ppb;

        $wordArrays = [
            'pimb' => $afficheWord_pimb,
            'mpb' => $afficheWord_mpb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($pimb)){
        $affiche_word=$afficheWord_mpt;

        $wordArrays = [
            'mpb' => $afficheWord_mpb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($mpb)){
        $affiche_word=$afficheWord_mpt;

        $remplace1 = generat_file($affiche_word);
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else{
        $remplace = array();
        $remplace[] = array('substance'=>'');
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    
        $remplace1 = array();
        $remplace1[] = array('substance'=>'');
        $templateScan->cloneBlock('block_name', 0, true, false, $remplace1);
        $template->cloneBlock('block_name', 0, true, false, $remplace1);
    }

    //pv
    $entete="
            MINISTERE DES MINES                
            -----------------------                
                SECRETARIAT GENERAL DES MINES                 
                                ----------------------                
                                        DIRECTION GENERALE DES MINES
                                                ---------------------
                                                    DIRECTION DES EXPORTATIONS ET VALEURS
                                                        --------------------- 
                                                            GUICHET UNIQUE
                                                                ---------------------
";
    $categorie_existe=$type_categorie1.$type_categorie2;

    $templateScan->setValue('num_pv', $num_pv);
    $templateScan->setValue('num_pv2', $num_pv);
    $templateScan->setValue('entete', $entete);
    //societe
    $templateScan->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateScan->setValue('nom_societe_imp', $nom_societe_importateur);
    $templateScan->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $templateScan->setValue('adresse_societe_exp', $adresse_societe_expediteur);
    $templateScan->setValue('destination_finale', $pays_destination);
    // $templateScan->setValue('type_categorie1', $type_categorie1);
    // $templateScan->setValue('type_categorie2', $type_categorie2);
    $templateScan->setValue('date', $dateEnTexte);
    $templateScan->setValue('num_facture', $num_facture);
    $templateScan->setValue('date_facture', $date_format_facture);
    $templateScan->setValue('num_fiche_declaration', $num_fiche_declaration);
    $templateScan->setValue('date_fiche_declaration', $date_format_declaration);
    $templateScan->setValue('num_domiciliation', $num_domiciliation);
    $templateScan->setValue('num_lp3e', $num_lp3e);
    $templateScan->setValue('date_lp3e', $date_format_lp3e);
    $templateScan->setValue('lieu_embarquement', $lieu_embarquement);
    $templateScan->setValue('mode_emballage', $mode_emballage);
    $templateScan->setValue('date_creation', $dateMaintenant);
    $templateScan->setValue('lieu_controle', $lieu_controle);
    $templateScan->setValue('total_general', $poidsTotal_generale);
    $remplace_agent = array();
    foreach ($agent as $agent_id) {
        $agent_concat = "- " . $grade_agents["agent_" . $agent_id] . " " . $noms_agents["agent_" . $agent_id] . ", " . $fonction_agents["agent_" . $agent_id] . "\n";
        $remplace_agent[] = array('nom'=>$agent_concat);
    }
    $templateScan->cloneBlock('block_name_nom', 0, true, false, $remplace_agent);

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
    $template->setValue('entete', $entete);
    $template->setValue('num_pv', $num_pv);
    $template->setValue('num_pv2', $num_pv);
    //societe
    $template->setValue('nom_societe_exp', $nom_societe_expediteur);
    $template->setValue('nom_societe_imp', $nom_societe_importateur);
    $template->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $template->setValue('adresse_societe_exp', $adresse_societe_expediteur);
    $template->setValue('destination_finale', $pays_destination);
    // $template->setValue('type_categorie1', $type_categorie1);
    // $template->setValue('type_categorie2', $type_categorie2);
    $template->setValue('date', $dateEnTexte);
    $template->setValue('num_facture', $num_facture);
    $template->setValue('date_facture', $date_facture);
    $template->setValue('num_fiche_declaration', $num_fiche_declaration);
    $template->setValue('date_fiche_declaration', $date_fiche_declaration);
    $template->setValue('num_domiciliation', $num_domiciliation);
    $template->setValue('num_lp3e', $num_lp3e);
    $template->setValue('date_lp3e', $date_lp3e);
    $template->setValue('total_general', $poidsTotal_generale);
    $template->setValue('lieu_embarquement', $lieu_embarquement);
    $template->setValue('mode_emballage', $mode_emballage);
    $template->setValue('date_creation', $dateMaintenant);
    $template->setValue('lieu_controle', $lieu_controle);
    $template->cloneBlock('block_name_nom', 0, true, false, $remplace_agent);
    

        // Enregistrer le nouveau document DOCX
        $nouveau_nom_fichierQR = $numPVClear  . '_QR.docx';
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
    $templateCdcScan->setValue('entete', $entete);
    $templateCdcScan->setValue('num_cc', $num_cc);
    $templateCdcScan->setValue('entete', $entete);
    $templateCdcScan->setValue('num_cc', $num_cc);
    $templateCdcScan->setValue('num_lp3e', $num_lp3e);
    $templateCdcScan->setValue('date_lp3e', $date_format_lp3e);
    $templateCdcScan->setValue('num_facture', $num_facture);
    $templateCdcScan->setValue('date_facture', $date_format_facture);
    $templateCdcScan->setValue('num_dom', $num_domiciliation);
    $templateCdcScan->setValue('nom_user', "Direction des Exportations et de la Valeur");
    $templateCdcScan->setValue('date_maintenant', $date_maintenant);
    $templateCdcScan->setValue('num_declaration', $num_fiche_declaration);
    $templateCdcScan->setValue('date_declaration', $date_format_declaration);
    $templateCdcScan->setValue('num_pv_controle', $num_pv);
    $templateCdcScan->setValue('date_pv_controle', $dateMaintenant);
    $templateCdcScan->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateCdcScan->setValue('addresse_societe_exp', $adresse_societe_expediteur);
    $templateCdcScan->setValue('nom_societe_imp', $nom_societe_importateur);
    $templateCdcScan->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $templateCdcScan->setValue('nom_responsable', $nom_responsable);
    $templateCdcScan->setValue('nom_responsable_imp', $nom_responsable);
    $templateCdcScan->setValue('total_general', $poidsTotal_generale);
    $templateCdcScan->setValue('destination_finale', $pays_destination);
    
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
        $templateCdc->setValue('entete', $entete);
        $templateCdc->setValue('num_cc', $num_cc);
        $templateCdc->setValue('num_lp3e', $num_lp3e);
        $templateCdc->setValue('date_lp3e', $date_format_lp3e);
        $templateCdc->setValue('num_facture', $num_facture);
        $templateCdc->setValue('date_facture', $date_format_facture);
        $templateCdc->setValue('num_dom', $num_domiciliation);
        $templateCdc->setValue('total_general', $poidsTotal_generale);
        $templateCdc->setValue('nom_societe_exp', $nom_societe_expediteur);
        $templateCdc->setValue('nom_user', "Direction des Exportations et de la Valeur");
        $templateCdc->setValue('date_maintenant', $date_maintenant);
        $templateCdc->setValue('num_declaration', $num_fiche_declaration);
        $templateCdc->setValue('date_declaration', $date_format_declaration);
        $templateCdc->setValue('num_pv_controle', $num_pv);
        $templateCdc->setValue('date_pv_controle', $dateMaintenant);
        $templateCdc->setValue('addresse_societe_exp', $adresse_societe_expediteur);
        $templateCdc->setValue('nom_societe_imp', $nom_societe_importateur);
        $templateCdc->setValue('adresse_societe_imp', $adresse_societe_importateur);
        $templateCdc->setValue('nom_responsable', $nom_responsable);
        $templateCdc->setValue('nom_responsable_imp', $nom_responsable);
        $templateCdc->setValue('destination_finale', $pays_destination);
        $nouveau_nom2 = $numCCClear  . '_QR.docx';
        $outputFilePathQRCC = $destinationFolder . $nouveau_nom2;
        $templateCdc->saveAs($outputFilePathQRCC);

        $tempDir = '../fichier_scan/';
        $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsCdc.php?id_data_cc='.$id_data;
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
            $replacements[] = array('contenu' => $valeur);
        }

        return $replacements;
    }
?>