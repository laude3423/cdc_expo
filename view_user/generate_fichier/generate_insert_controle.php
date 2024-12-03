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
    // $agent[]=$chef;
    // $agent[]=$qualite;
    //  //données utilisés
    // if(count($agent) > 0){
    //         for ($i = 0; $i < count($agent); $i++){
    //             $query = "SELECT * FROM agent WHERE id_agent = ?";
    //             $stmt = $conn->prepare($query);
    //             $stmt->bind_param("i",$agent[$i]);
    //             $stmt->execute();
    //             $resu = $stmt->get_result();
    //             $row = $resu->fetch_assoc();
    //             $grade_agents["agent_" . $agent[$i]] = $row['grade_agent'];
    //             $noms_agents["agent_" . $agent[$i]] = $row['nom_agent'];
    //             $prenoms_agents["agent_" . $agent[$i]] = $row['prenom_agent'];
    //             $fonction_agents["agent_" . $agent[$i]] = $row['fonction_agent'];
    //         }
    //     }
    //formater date
    

    $queryC = "SELECT * FROM data_cc WHERE id_data_cc=$id_data";
    $resultC = mysqli_query($conn, $queryC);
    $rowC = mysqli_fetch_assoc($resultC);
    $num_facture = $rowC['num_facture'];
    $date_facture = $rowC['date_facture'];
    $num_fiche_declaration = $rowC['num_fiche_declaration_pv'];
    $date_declaration = $rowC['date_fiche_declaration_pv'];
    $num_lp3e = $rowC['num_lp3e_pv'];
    $date_lp3e = $rowC['date_lp3e'];
    $expediteur= $rowC['id_societe_expediteur'];
    $importateur= $rowC['id_societe_importateur'];
    $num_cc= $rowC['num_cc'];
    $num_pv = $rowC['num_pv_controle'];

    // Création de l'objet IntlDateFormatter pour la France
    $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Paris');

    // Formater les dates
    $date_format_facture = $formatter->format(new DateTime($date_facture));
    $date_format_declaration = $formatter->format(new DateTime($date_declaration));
    $date_format_lp3e = $formatter->format(new DateTime($date_lp3e));

    //recherche sur les société
    $queryS1 = "SELECT * FROM societe_expediteur WHERE id_societe_expediteur=$expediteur";
    $resultS1 = mysqli_query($conn, $queryS1);
    $rowS1 = mysqli_fetch_assoc($resultS1);
    $nom_societe_expediteur = $rowS1['nom_societe_expediteur'];
    $adresse_societe_expediteur = $rowS1['adresse_societe_expediteur'];
    $nom_responsable = $rowS1['responsable'];
    $type_societe = $rowS1['type'];
    
    
    $queryS2 = "SELECT * FROM societe_importateur WHERE id_societe_importateur=$importateur";
    $resultS2 = mysqli_query($conn, $queryS2);
    $rowS2 = mysqli_fetch_assoc($resultS2);
    $nom_societe_importateur = $rowS2['nom_societe_importateur'];
    $adresse_societe_importateur = $rowS2['adresse_societe_importateur'];
    $pays_destination = $rowS2['pays_destination'];

    //$nom_societe_importateur = htmlspecialchars($nom_societe_importateur, ENT_QUOTES, 'UTF-8');
    //$adresse_societe_importateur = htmlspecialchars($adresse_societe_importateur, ENT_QUOTES, 'UTF-8');
    //$pays_destination = htmlspecialchars($pays_destination, ENT_QUOTES, 'UTF-8');

    //$nom_societe_expediteur = htmlspecialchars($nom_societe_expediteur, ENT_QUOTES, 'UTF-8');
    //$adresse_societe_expediteur = htmlspecialchars($adresse_societe_expediteur, ENT_QUOTES, 'UTF-8');
    //$nom_responsable = htmlspecialchars($nom_responsable, ENT_QUOTES, 'UTF-8');
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

    //recherche de poids total
    $poids_total_g=0;
    $poids_total_kg=0;
    $poidsTotal="";
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
        $poidsTotal = $poidsTotal1 . ' et ' . $poidsTotal2;
    } elseif (($poids_total_kg > 0) && ($poids_total_g == 0)) {
        $poidsTotal = poids_total($poids_total_kg, $type2);
    } elseif (($poids_total_g > 0) && ($poids_total_kg == 0)) {
        $poidsTotal = poids_total($poids_total_g, $type1);
    } else {
        $poidsTotal = "Aucune";
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
            $poidsTotal=$nombreAvantLettre." grammes ". $nombreApresLettre . '('.$nombreFormat.'g) de pierres gemmes';
        }else{
            $poidsTotal=$nombreAvantLettre." kilogrammes ". $nombreApresLettre . '('.$nombreFormat.'kg) de pierres';
        }
        return $poidsTotal;
    }
    
    $templatePathScan="";
    $templatePath ='';
    $categorie_existe='';
    //création de fichier
    
    $templatePathScanCdc =  '../template/model_scan_cdc.docx';
    $templatePathCdc =  '../template/model_cdc.docx';
    $templateCdcScan = new TemplateProcessor($templatePathScanCdc);
    $templateCdc = new TemplateProcessor($templatePathCdc);
    
    if (!empty($pft)) {
        $affiche_word = $afficheWord_pft;

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
            'boit' => $afficheWord_boite,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

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
            'boit' => $afficheWord_boite,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

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
            'boit' => $afficheWord_boite,
        ];

        foreach ($wordArrays as $key => $wordArray) {
           if (!empty($wordArray)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

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

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($pfb)){
        $affiche_word=$afficheWord_pfb;
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

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($pimb)){
        $affiche_word=$afficheWord_pimb;

        $wordArrays = [
            'mpb' => $afficheWord_mpb,
        ];

        foreach ($wordArrays as $key => $wordArray) {
            if (!empty($key)) {
                $affiche_word = array_merge($affiche_word, $wordArray);
            }
        }

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else if(!empty($mpb)){
        $affiche_word=$afficheWord_mpb;

        $remplace = generat_file($affiche_word);
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }else{
        $remplace = array();
        $remplace[] = array('substance'=>'');
        $templateCdcScan->cloneBlock('block_name', 0, true, false, $remplace);
        $templateCdc->cloneBlock('block_name', 0, true, false, $remplace);
    }

   
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
                                                    DIRECTION DES EXPORTATIONS ET DE LA VALEUR
                                                        --------------------- 
                                                            GUICHET UNIQUE D'EXPORTATION
                                                                ---------------------
";
$nom_entete1="Directeur des Mines et de la Géologie";
$nom_entete2="Directeur des Exportations et de la Valeur";
$nom_direction1 = "la DIRECTION ".$typeDirection." ".$nomDirection;
$nom_direction2 = "la Direction des Exportations et de la Valeur";
$entete="";
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
    $nom_societe_expediteur = $nom_societe_expediteur." ".$type_societe;
    $nom_societe_expediteur=htmlspecialchars($nom_societe_expediteur);
    $nom_societe_importateur = htmlspecialchars($nom_societe_importateur, ENT_QUOTES, 'UTF-8');

    //-------------------------------------------------------
    //generate file certificat de conformité
    $templateCdcScan->setValue('entete', $entete);
    $templateCdcScan->setValue('num_cc', $num_cc);
    $templateCdcScan->setValue('date_maintenant', $date_maintenant);
    $templateCdcScan->setValue('num_declaration', $num_fiche_declaration);
    $templateCdcScan->setValue('date_declaration', $date_format_declaration);
    $templateCdcScan->setValue('num_pv_controle', $num_pv);
    $templateCdcScan->setValue('num_lp3e', $num_lp3e);
    $templateCdcScan->setValue('date_lp3e', $date_format_lp3e);
    $templateCdcScan->setValue('num_facture', $num_facture);
    $templateCdcScan->setValue('date_facture', $date_format_facture);
    $templateCdcScan->setValue('num_dom', $num_domiciliation);
    $templateCdcScan->setValue('total_general', $poidsTotal);
    $templateCdcScan->setValue('date_pv_controle', $date_maintenant);
    $templateCdcScan->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateCdcScan->setValue('addresse_societe_exp', $adresse_societe_expediteur);
    $templateCdcScan->setValue('nom_societe_imp', $nom_societe_importateur);
    $templateCdcScan->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $templateCdcScan->setValue('nom_responsable', $nom_responsable);
    $templateCdcScan->setValue('destination_finale', $pays_destination);
    $templateCdcScan->setValue('nom_entete', $nom_entete);//$lieu_emission
    $templateCdcScan->setValue('nom_emplacement', $lieu_emission);
    $templateCdcScan->setValue('vrai_nom_direction', $vrai_nom_direction);
    $numCCClear=preg_replace('/[^a-zA-Z0-9]/', '-', $num_cc);
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
    $templateCdc->setValue('date_maintenant', $date_maintenant);
    $templateCdc->setValue('num_declaration', $num_fiche_declaration);
    $templateCdc->setValue('date_declaration', $date_format_declaration);
    $templateCdc->setValue('num_pv_controle', $num_pv);
    $templateCdc->setValue('numero_lp3e', $num_lp3e);
    $templateCdc->setValue('date_pl3e', $date_format_lp3e);
    $templateCdc->setValue('num_facture', $num_facture);
    $templateCdc->setValue('date_facture', $date_format_facture);
    $templateCdc->setValue('num_dom', $num_domiciliation);
    $templateCdc->setValue('total_general', $poidsTotal);
    $templateCdc->setValue('date_pv_controle', $date_maintenant);
    $templateCdc->setValue('nom_societe_exp', $nom_societe_expediteur);
    $templateCdc->setValue('addresse_societe_exp', $adresse_societe_expediteur);
    $templateCdc->setValue('nom_societe_imp', $nom_societe_importateur);
    $templateCdc->setValue('adresse_societe_imp', $adresse_societe_importateur);
    $templateCdc->setValue('nom_responsable', $nom_responsable);
    $templateCdc->setValue('nom_responsable_imp', $nom_societe_importateur);
    $templateCdc->setValue('destination_finale', $pays_destination);
    $templateCdc->setValue('nom_entete', $nom_entete);
    $templateCdc->setValue('vrai_nom_direction', $vrai_nom_direction);
    $templateCdc->setValue('nom_emplacement', $lieu_emission);
    $nouveau_nom2 = $numCCClear  . '.docx';
    $fileCdc = $destinationFolder . $nouveau_nom2;
    $templateCdc->saveAs($fileCdc);

    // $tempDir = '../fichier_scan/';
    // $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsCdc.php?id_data_cc='.$id_data;
    // $qrcode_name = 'qrcode_test';
    // QRcode::png($lien, $tempDir.''.$qrcode_name.'.png', QR_ECLEVEL_L, 5);
    // $qrCodePath2 = $tempDir . $qrcode_name . '.png';
    // $logoWidth = 35; // Largeur souhaitée du logo
    // $logoHeight = 35; // Hauteur souhaitée du logo
    // $qrCode = imagecreatefrompng($qrCodePath2);

    // if ($qrCode === false) {
    //         die('Erreur : Impossible de créer une image à partir du QR code.');
    //     }

    //     // Créer une image à partir du logo (qui est en PNG)
    //     $logo = imagecreatefrompng($logoPath);

    //     if ($logo === false) {
    //         die('Erreur : Impossible de créer une image à partir du logo.');
    //     }

    //     // Dimensions actuelles du logo
    //     $logoActualWidth = imagesx($logo);
    //     $logoActualHeight = imagesy($logo);

    //     // Redimensionner le logo aux dimensions souhaitées
    //     $logoResized = imagecreatetruecolor($logoWidth, $logoHeight);
    //     imagecopyresampled($logoResized, $logo, 0, 0, 0, 0, $logoWidth, $logoHeight, $logoActualWidth, $logoActualHeight);

    //     // Dimensions du QR code
    //     $qrWidth = imagesx($qrCode);
    //     $qrHeight = imagesy($qrCode);

    //     // Positionnement du logo au centre du QR code
    //     $logoX = ($qrWidth / 2) - ($logoWidth / 2);
    //     $logoY = ($qrHeight / 2) - ($logoHeight / 2);

    //     // Fusionner le logo redimensionné sur le QR code
    //     imagecopy($qrCode, $logoResized, $logoX, $logoY, 0, 0, $logoWidth, $logoHeight);

    //     // Chemin pour l'image fusionnée
    //     $mergedImagePath = $tempDir . $qrcode_name . '_with_logo.png';

    //     // Sauvegarder l'image fusionnée
    //     imagepng($qrCode, $mergedImagePath);

    //     // Libérer la mémoire
    //     imagedestroy($qrCode);
    //     imagedestroy($logo);
    //     imagedestroy($logoResized);

    // $templateProcessor2 = new TemplateProcessor($fileCdc);

    // $templateProcessor2->setImageValue(
    //     'qrcode',
    //     [
    //         'path' => $mergedImagePath,
    //         'width' => 150, //=4cm
    //         'height' => 150,
            
    //     ]
    // );

    // $nomQr = $numCCClear . '_QR.docx';
    // $pathToSaveNewCC = $destinationFolder . $nomQr;
    // $templateProcessor2->saveAs($pathToSaveNewCC);
    // // // Nom du fichier PDF résultant
    // $pdfFileName = $numCCClear . '_QR.pdf';
    // $pj_cc = $directory . '/' . $pdfFileName;

    $tempDir = '../fichier_scan/';
    $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsCdc.php?id_data_cc=' . $id_data;
    $qrcode_name = 'qrcode_test';

    // Génération du QR code
    QRcode::png($lien, $tempDir . $qrcode_name . '.png', QR_ECLEVEL_L, 5);
    $qrCodePath2 = $tempDir . $qrcode_name . '.png';

    // Traitement pour le document Word
    $templateProcessor2 = new TemplateProcessor($fileCdc);
    $templateProcessor2->setImageValue(
        'qrcode',
        [
            'path' => $qrCodePath2,
            'width' => 140, // = 4cm
            'height' => 140,
        ]
    );

    // Nom du fichier Word généré
    $nomQr = $numCCClear . '_QR.docx';
    $pathToSaveNewCC = $destinationFolder . $nomQr;
    $templateProcessor2->saveAs($pathToSaveNewCC);

    // Nom du fichier PDF résultant
    $pdfFileName = $numCCClear . '_QR.pdf';
    $pj_cc = $directory . '/' . $pdfFileName;


    // Convertir le fichier Word en PDF en utilisant la commande "soffice"
    $command2 = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $pathToSaveNewCC . '"';
    shell_exec($command2);

    echo 'Le publipostage a été généré avec succès : <a href="' . $pj_cc . '" download>Télécharger scan CDC ici PDF</a>';
    echo 'Le publipostage a ét généré avec succès : <a href="' . $pathToSaveNewCC . '" download>Télécharger ici DOCX 1 </a>';
    unlink($pathToSaveNewCC);
    unlink($fileCdc);
    function generat_file($affiche) {
        $replacements = array();
        foreach ($affiche as $valeur) {
            $replacements[] = array('contenu' => $valeur);
        }
        return $replacements;
    }
?>