<?php
require_once('../../scripts/db_connect.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../mylibs/phpqrcode/qrlib.php';
include 'nombre_en_lettre.php';

$agent = array();
// Vérification et traitement de $chef
if ($chef) {
    $agent[] = $chef;
}
// Vérification et traitement de $qualite
if ($qualite) {
    //$agent_scellage = array_push($agent_scellage, $qualite);
    $agent[] = $qualite;
}

//vérification et traitement de $agent_scellage
if(count($agent_scellage)> 0){
    for ($i = 0; $i < count($agent_scellage); $i++){
        $agent[] = $agent_scellage[$i];
    }
}
// Vérification et traitement de $douane
if ($douane) {
    $agent[] = $douane;
}
//Vérification et traitement de $police
if ($police) {
    //$agent_scellage = array_push($agent_scellage, $police);
    $agent[] = $police;
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
    $sommePoids_ct=0;
    $sommePoids_g=0;
    $sommePoids_kg=0;
    //recherche l'id_detaille_substance pour l'unité en carat
    $queryDetaille_ct = "SELECT datacc.num_facture, sum(contenu.poids_facture) as sommePoids FROM contenu_facture contenu
    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc WHERE contenu.id_data_cc = $facture AND contenu.unite_poids_facture='ct'";
    $resultDetaille_ct = mysqli_query($conn, $queryDetaille_ct);
    $rowDetaille_ct = mysqli_fetch_assoc($resultDetaille_ct);
    $num_facture = $rowDetaille_ct['num_facture'];
    if(!empty($rowDetaille_ct['sommePoids'])){
        $sommePoids_ct = $rowDetaille_ct['sommePoids'];
    }
    //recherche l'id_detaille_substance pour l'unité en gramme
    $queryDetaille_g = "SELECT  sum(contenu.poids_facture) as sommePoids FROM contenu_facture contenu
    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc WHERE contenu.id_data_cc = $facture AND contenu.unite_poids_facture='g'";
    $resultDetaille_g = mysqli_query($conn, $queryDetaille_g);
    $rowDetaille_g = mysqli_fetch_assoc($resultDetaille_g);
    if(!empty($rowDetaille_g['sommePoids'])){
        $sommePoids_g = $rowDetaille_g['sommePoids'];
    }
    //recherche l'id_detaille_substance pour l'unité en kilogramme
    $queryDetaille_kg = "SELECT  sum(contenu.poids_facture) as sommePoids, id_detaille_substance FROM contenu_facture contenu
    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc WHERE contenu.id_data_cc = $facture AND contenu.unite_poids_facture='kg'";
    $resultDetaille_kg = mysqli_query($conn, $queryDetaille_kg);
    $rowDetaille_kg = mysqli_fetch_assoc($resultDetaille_kg);
    $id_detaille_substance_kg = $rowDetaille_kg['id_detaille_substance'];
    if(!empty($rowDetaille_kg['sommePoids'])){
        $sommePoids_kg = $rowDetaille_kg['sommePoids'];
    }
    //recherche de nom et adresse de societe expediteur
    $queryExpediteur = "SELECT * FROM societe_expediteur WHERE id_societe_expediteur=$expediteur";
    $resultExpediteur = mysqli_query($conn, $queryExpediteur);
    $rowExpediteur = mysqli_fetch_assoc($resultExpediteur);
    $nom_societe_expediteur= $rowExpediteur['nom_societe_expediteur'];
    $adresse_societe_expediteur= $rowExpediteur['adresse_societe_expediteur'];
    //recherche de nom et adresse de societe expediteur
    $queryImportateur = "SELECT pays_destination, visa FROM societe_importateur WHERE id_societe_importateur=$destination";
    $resultImportateur = mysqli_query($conn, $queryImportateur);
    $rowImportateur = mysqli_fetch_assoc($resultImportateur);
    $pays_destination= $rowImportateur['pays_destination'];
    $visa= $rowImportateur['visa'];
    //recherche de l'id_detaille_substance
    $queryR = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $facture AND unite_poids_facture != 'kg'";
    $resultR = mysqli_query($conn, $queryR);
    $id_detaille_substance = array();
    $index1 = 0;
    while($rowR = mysqli_fetch_assoc($resultR)){
         $id_detaille_substance[$index1] = $rowR['id_detaille_substance'];
         $index1++;
    }
    
    $nom_substance = array();
    $nom_sub_kg = array();
    $categorie_existe1="";
    $categorie_existe2= "";
    $categorie_existe="";
    $couleur_substance = array();
    if (count($id_detaille_substance) > 0) {
        for ($i = 0; $i < count($id_detaille_substance); $i++) {
            $queryD = "SELECT sub.*, couleur.* 
                    FROM substance_detaille_substance AS detail
                    LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                    LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                    WHERE detail.id_detaille_substance = " . $id_detaille_substance[$i];
            $resultD = mysqli_query($conn, $queryD);
            if ($rowD = mysqli_fetch_assoc($resultD)) {
                $nom_substance[] = $rowD['nom_substance'];
                if (!empty($rowD['nom_couleur_substance'])) {
                    $couleur_substance[] = $rowD['nom_couleur_substance'];
                } else {
                    $couleur_substance[] = "vide";
                }
            }
            $QueryCate="SELECT cate.nom_categorie FROM substance_detaille_substance AS detail
            INNER JOIN categorie AS cate ON cate.id_categorie=detail.id_categorie WHERE cate.nom_categorie='Brute'";
            $resultCate = mysqli_query($conn, $QueryCate);
            $rowCate = mysqli_fetch_assoc($resultCate);
            if(!empty($rowCate["nom_categorie"])) {
                $categorie_existe1=$rowCate['nom_categorie'];
            }
            $QueryCate="SELECT cate.nom_categorie FROM substance_detaille_substance AS detail
            INNER JOIN categorie AS cate ON cate.id_categorie=detail.id_categorie WHERE cate.nom_categorie='Taillée'";
            $resultCate = mysqli_query($conn, $QueryCate);
            $rowCate = mysqli_fetch_assoc($resultCate);
            if(!empty($rowCate["nom_categorie"])) {
                $categorie_existe2=$rowCate['nom_categorie'];
            }
        }
    }
    
    if(!empty($categorie_existe1)&&!empty($categorie_existe2)) {
        $categorie_existe = $categorie_existe1 .','. $categorie_existe2;
    }elseif(!empty($categorie_existe1)&& empty($categorie_existe2)) {
        $categorie_existe = $categorie_existe1;
    }elseif(empty($categorie_existe1)&& !empty($categorie_existe2)) {
        $categorie_existe = $categorie_existe2;
    }
    else{$categorie_existe="";}
    $dateFormated = "d/m/Y";
    $date_modification = date($dateFormated);
    $afficheWord= array();
    $afficheWord_kg = array();
    $substances_couleurs = array();
    if (count($nom_substance) > 0) {
        for ($i = 0; $i < count($nom_substance); $i++) {
            $substance = $nom_substance[$i];
            $couleur = $couleur_substance[$i];
            echo $couleur;
            // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
            if (array_key_exists($substance, $substances_couleurs)) {
                $substances_couleurs[$substance][] = $couleur;
            } else {
                $substances_couleurs[$substance] = array($couleur);
            }
        }

        // Affichage des résultats
        foreach ($substances_couleurs as $substance => $couleurs) {
            $couleurs_uniques = array_unique($couleurs);
            if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
                $afficheWord[] = $substance .'(vide)';
            } else {
                $afficheWord[] = $substance . '(' . implode(', ', $couleurs_uniques) . ')';
            }
        }
    }
    //recherche de pierres non gemme
    $queryS = "SELECT  id_detaille_substance FROM contenu_facture WHERE id_data_cc = $facture AND unite_poids_facture = 'kg'";
    $resultS = mysqli_query($conn, $queryS);
    $num_detaille_substance = array();
    $index2 = 0;
    while($rowS = mysqli_fetch_assoc($resultS)){
         $num_detaille_substance[$index2] = $rowS['id_detaille_substance'];
         $index2++;
    }
    $nom_type_substance='';
    if($id_detaille_substance_kg){
        $Querry="SELECT id_substance FROM substance_detaille_substance";
            $resu = mysqli_query($conn, $Querry);
            $roww = mysqli_fetch_assoc($resu);
            if(!empty($roww["id_substance"])) {
                $num_substance=$roww['id_substance'];
                $Querry2="SELECT nom_type_substance FROM type_substance";
                $resu2 = mysqli_query($conn, $Querry2);
                $roww2 = mysqli_fetch_assoc($resu2);
                $nom_type_substance=$roww['nom_type_substance'];
            }
    }
    if (count($num_detaille_substance) > 0) {
        for ($i = 0; $i < count($num_detaille_substance); $i++) {
            $queryE = "SELECT sub.*, couleur.* 
                    FROM substance_detaille_substance AS detail
                    LEFT JOIN substance AS sub ON sub.id_substance = detail.id_substance
                    LEFT JOIN couleur_substance AS couleur ON couleur.id_couleur_substance = detail.id_couleur_substance 
                    WHERE detail.id_detaille_substance = " . $num_detaille_substance[$i];
            $resultE = mysqli_query($conn, $queryE);
            if ($rowE = mysqli_fetch_assoc($resultE)) {
                $nom_sub_kg[] = $rowE['nom_substance'];
                if (!empty($rowE['nom_couleur_substance'])) {
                    $couleur_substance_kg[] = $rowE['nom_couleur_substance'];
                } else {
                    $couleur_substance_kg[] = "vide";
                }
            }
            $QueryCate_kg="SELECT cate.nom_categorie FROM substance_detaille_substance AS detail
            INNER JOIN categorie AS cate ON cate.id_categorie=detail.id_categorie WHERE cate.nom_categorie='Brute'";
            $resultCate_kg = mysqli_query($conn, $QueryCate_kg);
            $rowCate_kg = mysqli_fetch_assoc($resultCate_kg);
            if(!empty($rowCate_kg["nom_categorie"])) {
                $categorie_existe_kg=$rowCate_kg['nom_categorie'];
            }
            $QueryCate_kg2="SELECT cate.nom_categorie FROM substance_detaille_substance AS detail
            INNER JOIN categorie AS cate ON cate.id_categorie=detail.id_categorie WHERE cate.nom_categorie='Taillée'";
            $resultCate_kg2 = mysqli_query($conn, $QueryCate_kg2);
            $rowCate_kg2 = mysqli_fetch_assoc($resultCate_kg2);
            if(!empty($rowCate_kg2["nom_categorie"])) {
                $categorie_existe_kg2=$rowCate_kg2['nom_categorie'];
            }
        }
    }
    if(!empty($categorie_existe_kg)&&!empty($categorie_existe_kg2)) {
        $categorie_kg = $categorie_existe_kg .','. $categorie_existe2;
    }elseif(!empty($categorie_existe_kg)&& empty($categorie_existe_kg2)) {
        $categorie_kg = $categorie_existe_kg;
    }elseif(empty($categorie_existe_kg)&& !empty($categorie_existe_kg2)) {
        $categorie_kg = $categorie_existe_kg2;
    }
    else{$categorie_kg="";}
    
    $substances_couleurs_kg = array();
    if (count($nom_sub_kg) > 0) {
        for ($i = 0; $i < count($nom_sub_kg); $i++) {
            $substance_kg = $nom_sub_kg[$i];
            $couleur_kg = $couleur_substance_kg[$i];
            // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
            if (array_key_exists($substance_kg, $substances_couleurs_kg)) {
                $substances_couleurs_kg[$substance_kg][] = $couleur_kg;
            } else {
                $substances_couleurs_kg[$substance_kg] = array($couleur_kg);
            }
        }

        // Affichage des résultats
        foreach ($substances_couleurs_kg as $substance_kg => $couleurs_kg) {
            $couleurs_uniques_kg = array_unique($couleurs_kg);
            if (empty($couleurs_uniques_kg) || in_array('vide', $couleurs_uniques_kg, true)) {
                $afficheWord_kg[] = $substance_kg .'(vide)';
            } else {
                $afficheWord_kg[] = $substance_kg . '(' . implode(', ', $couleurs_uniques_kg) . ')';
            }
        }
    }
   $and = 'et';
    $unite = "GRAMME";
    $unite2 = "KILOGRAMME";
    $totalePoids =0;
    $totalePoidsEnLettres ='';
    $poidsEnLettre ='';
    //forme de poids en gramme
    if(!empty($sommePoids_ct) || !empty($sommePoids_g)) {
        $totalePoids=$sommePoids_ct * 5 + $sommePoids_g;
        $totalePoidsFormate = number_format($totalePoids, 2, '.', '');
        $partieDecimale = fmod($totalePoidsFormate, 1);
        $nombreApres=0;
        $parts = explode(".", $partieDecimale);
    
        $nombreApres = substr($partieDecimale, 2);
        $nombreCompare='';
        $nombreCompareLettre='';
        if($nombreApres > 0) {
            $nombreCompare = comparer($nombreApres);
            $nombreCompareLettre=nombreEnLettres($nombreCompare);
        }
        $totalePoidsEnLettres = nombreEnLettres($totalePoidsFormate);
        $poidsEnLettre = $totalePoidsEnLettres.' '.$nombreCompareLettre.' de Pierres gemmes ('.$categorie_existe.')';
    }

    //forme de poids en kg
    $nombreCompare_kg='';
    $nombreCompareLettre_kg='';
    $totalePoidsEnLettres_kg='';
    $totalePoidsFormate_kg='';
    if(!empty($sommePoids_kg)){
        $totalePoidsFormate_kg = number_format($sommePoids_kg, 2, '.', '');
        $partieDecimale_kg = fmod($totalePoidsFormate_kg, 1);
        $nombreApres_kg=0;
        $parts_kg = explode(".", $partieDecimale_kg);
    
        $nombreApres_kg = substr($partieDecimale_kg, 2);
        
        if($nombreApres_kg > 0) {
            $nombreCompare_kg = comparer($nombreApres_kg);
            $nombreCompareLettre_kg=nombreEnLettres($nombreCompare_kg);
        }
        $totalePoidsEnLettres_kg = nombreEnLettres($totalePoidsFormate_kg);
    }
    $poidsEnLettre_kg = $totalePoidsEnLettres_kg.' '.$nombreCompareLettre_kg.' de' .$nom_type_substance.'('.$categorie_kg.')';
    $templatePathScan ="";
    if(count($agent)==5){
        $templatePathScan =  '../template/model_scan5.docx';
        $templatePath =  '../template/model5.docx';
    }elseif(count($agent)== 6){
        $templatePathScan = '../template/model_scan6.docx';
        $templatePath =  '../template/model6.docx';
    }elseif(count($agent)== 7){
        $templatePathScan = '../template/model_scan7.docx';
        $templatePath =  '../template/model7.docx';
    }elseif(count($agent)== 8){
        $templatePathScan = "../template/model_scan8.docx";
        $templatePath =  '../template/model8.docx';
    }elseif(count($agent)==9){
        $templatePathScan = "../template/model_scan9.docx";
        $templatePath =  '../template/model9.docx';
    }elseif(count($agent)== 10){
        $templatePathScan = "../template/model_scan10.docx";
        $templatePath =  '../template/model10.docx';
    }else{
        $templatePathScan = "../template/model_scan10.docx";
        $templatePath =  '../template/model0.docx';
    }
     if($nom_type_substance){
        $contenu = $nom_type_substance.'('. $categorie_kg.')';
    }else{
        $contenu = "Pierres gemmes".'('. $categorie_existe.')';
    }
    
    $templateScan = new TemplateProcessor($templatePathScan);
    
    //societe
    $templateScan->setValue('num_pv', $num_pv);
    $templateScan->setValue('nom_societe', $nom_societe_expediteur);
    $templateScan->setValue('adresse_societe', $adresse_societe_expediteur);
    $templateScan->setValue('destination_finale', $pays_destination);
    $templateScan->setValue('visa', $visa);
    //numéro et date
    $templateScan->setValue('date', $dateEnTexte);
    $templateScan->setValue('poidsEnLettre', $poidsEnLettre);
    $templateScan->setValue('date', $dateEnTexte);
    $templateScan->setValue('num_facture', $num_facture);
    $templateScan->setValue('date_facture', $date);
    if(!empty($afficheWord_kg)&&(!empty($afficheWord))){
        $templateScan->setValue('and', $and);
    }else{
        $templateScan->setValue('and', '');
    }
    if(!empty($afficheWord_kg)){
        $templateScan->setValue('poidsEnLettre_kg', $poidsEnLettre_kg);
        $templateScan->setValue('unite2', $unite2);
    }else{
        $templateScan->setValue('poidsEnLettre_kg', '');
        $templateScan->setValue('unite2', '');
    }
    $templateScan->setValue('unite', $unite);
    $templateScan->setValue('num_fiche_declaration', $declaration);
    $templateScan->setValue('date_fiche_declaration', $date_declaration);
    $templateScan->setValue('num_domiciliation', $numDom);
    $templateScan->setValue('num_lp3e', $num_lp3);
    $templateScan->setValue('date_lp3e', $date_lp3);
    $templateScan->setValue('afficheWord', implode(', ', $afficheWord));
    $templateScan->setValue('afficheWord2', implode(', ', $afficheWord_kg));
    $templateScan->setValue('contenu', $contenu);
    $templateScan->setValue('poidsTotal', $totalePoidsFormate);
    $templateScan->setValue('poidsTotal_kg', $totalePoidsFormate_kg);
    $templateScan->setValue('nombre_colis', $nombre);
    $templateScan->setValue('type_colis', $type_colis);
    $templateScan->setValue('lieu_scellage', $lieu_sce);
    $templateScan->setValue('lieu_embarquement', $lieu_emb);

    // Initialisez la variable en dehors de la boucle pour accumuler les valeurs
    $agent_concat = "";
    $indexBoucle = 0;
    foreach ($agent as $agent_id) {
        $agent_concat .= "- " . $grade_agents["agent_" . $agent_id] . " " . $noms_agents["agent_" . $agent_id] . ", " . $fonction_agents["agent_" . $agent_id] . "\n";
         // Remplace la valeur dans le template à chaque itération
        $agent_concat .= "<w:br/>";
        $templateScan->setValue('agent_loop' . $indexBoucle, $agent_concat);
        $agent_concat = "";
    $indexBoucle++;
    }
    
    

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
        echo 'Le publipostage a été généré avec succès : <a href="' . $pathToSave . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $outputFilePath . '" download>Télécharger ici DOCX 1 </a>';
        unlink($outputFilePath);

        //------------------------------------------------------------------------------------------
         //Deuxieme template
        
         $template = new TemplateProcessor($templatePath);

         //societe
        $template->setValue('num_pv', $num_pv);
        $template->setValue('nom_societe', $nom_societe_expediteur);
        $template->setValue('adresse_societe', $adresse_societe_expediteur);
        $template->setValue('destination_finale', $pays_destination);
        $template->setValue('visa', $visa);
        //numéro et date
        $template->setValue('date', $dateEnTexte);
        $template->setValue('poidsEnLettre', $poidsEnLettre);
        $template->setValue('date', $dateEnTexte);
        $template->setValue('num_facture', $num_facture);
        $template->setValue('date_facture', $date);
        if(!empty($afficheWord_kg)&&(!empty($afficheWord))){
            $template->setValue('and', $and);
        }else{
            $template->setValue('and', '');
        }
        if(!empty($afficheWord_kg)){
            $template->setValue('poidsEnLettre_kg', $poidsEnLettre_kg);
            $template->setValue('unite2', $unite2);
        }else{
            $template->setValue('poidsEnLettre_kg', '');
            $template->setValue('unite2', '');
        }
        $template->setValue('unite', $unite);
        $template->setValue('num_fiche_declaration', $declaration);
        $template->setValue('date_fiche_declaration', $date_declaration);
        $template->setValue('num_domiciliation', $numDom);
        $template->setValue('num_lp3e', $num_lp3);
        $template->setValue('date_lp3e', $date_lp3);
        $template->setValue('afficheWord', implode(', ', $afficheWord));
        $template->setValue('afficheWord2', implode(', ', $afficheWord_kg));
        $template->setValue('contenu', $contenu);
        $template->setValue('poidsTotal', $totalePoidsFormate);
        $template->setValue('poidsTotal_kg', $totalePoidsFormate_kg);
        $template->setValue('nombre_colis', $nombre);
        $template->setValue('type_colis', $type_colis);
        $template->setValue('lieu_scellage', $lieu_sce);
        $template->setValue('lieu_embarquement', $lieu_emb);

        // Initialisez la variable en dehors de la boucle pour accumuler les valeurs
        $agent_concat = "";
        $indexBoucle = 0;
        foreach ($agent as $agent_id) {
            $agent_concat .= "- " . $grade_agents["agent_" . $agent_id] . " " . $noms_agents["agent_" . $agent_id] . ", " . $fonction_agents["agent_" . $agent_id] . "\n";
            // Remplace la valeur dans le template à chaque itération
            $agent_concat .= "<w:br/>";
            $template->setValue('agent_loop' . $indexBoucle, $agent_concat);
            $agent_concat = "";
        $indexBoucle++;
        }

        // Enregistrer le nouveau document DOCX
        $nouveau_nom_fichierQR = $numPVClear  . '_.docx';
        $outputFilePathQR = $destinationFolder . $nouveau_nom_fichierQR;
        $template->saveAs($outputFilePathQR);

        // //------------------------------------------------------------------------------------------------------------------------------

        // //Generer le QR COde
        $tempDir = '../fichier_scan/';
         //$lien = 'https://lp1.minesmada.org/' .$pathToSave;
        $lien = 'https://cdc.minesmada.org/view_user/generate_fichier/scriptsPdf.php?id_data_cc='.$facture;
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
    
?>