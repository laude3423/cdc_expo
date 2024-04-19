<?php
require_once('../../scripts/db_connect.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../mylibs/phpqrcode/qrlib.php';
include 'nombreEnLettre.php';

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
    $queryFa = "SELECT num_facture FROM data_cc WHERE id_data_cc=$facture";
    $resultFa= mysqli_query($conn, $queryFa);
    $rowFa = mysqli_fetch_assoc($resultFa);
    $num_facture=$rowFa['num_facture'];
    $categorie_pp='';
    $sommePoids_pp=0;
    $afficheWord_pp='';
    $unite_pp="";

    $categorie_pf='';
    $sommePoids_pf=0;
    $afficheWord_pf='';
    $unite_pf="";

    $categorie_mp='';
    $sommePoids_mp=0;
    $afficheWord_mp='';
    $unite_mp="";

    $categorie_pim='';
    $sommePoids_pim=0;
    $afficheWord_pim='';
    $unite_pim="";
    //somme de poids par type de substance PP
        $querypp = "SELECT dcc.*,cfac.poids_facture,cfac.unite_poids_facture,csub.nom_couleur_substance,cate.nom_categorie, s.nom_substance, cfac.poids_facture FROM contenu_facture cfac 
        LEFT JOIN  data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie cate ON cate.id_categorie = sds.id_categorie
        WHERE ts.code_type_substance = 'PP' AND dcc.id_data_cc=$facture";
        $resultpp= mysqli_query($conn, $querypp);
        if(mysqli_num_rows($resultpp)> 0){
                $pp="existe"; $unite2=''; $categorie_brute='';  $categorie_taille="";
                $sommePoids_ct_pp = 0;$sommePoids_kg_pp = 0;$sommePoids_g_pp=0;
                while($rowpp = mysqli_fetch_assoc($resultpp)){
                    $unite_poids_facture = intval($rowpp['poids_facture']);
                    if($rowpp['unite_poids_facture']=='ct'){
                        $sommePoids_ct_pp += $unite_poids_facture;
                        $unite_pp ="GRAMMES"; $unite2='grammes';
                    }elseif($rowpp['unite_poids_facture']=='g'){
                        $sommePoids_g_pp += $unite_poids_facture;
                        $unite_pp ="GRAMMES"; $unite2='grammes';
                    }else{
                        $sommePoids_kg_pp += $unite_poids_facture;
                        $unite_pp ="KILOGRAMMES"; $unite2='kilogrammes';
                    }
                    if($rowpp['nom_categorie']=="Brute"){
                        $categorie_brute="Brutes";
                    }else{
                        $categorie_taille="Taillées";
                    }
                    $nom_substance_pp[] = $rowpp['nom_substance'];
                    $couleur_substance_pp[] = $rowpp['nom_couleur_substance'];
                }
            if(!empty($categorie_brute)&&!empty($categorie_taille)) {
                $categorie = $categorie_brute .','. $categorie_taille;
            }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
                $categorie = $categorie_brute;
            }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
                $categorie = $categorie_taille;
            }
            $categorie_pp=$categorie;
            $type="Pierres Précieuses";
            $sommePoids_pp = $sommePoids_ct_pp * 0.2 + $sommePoids_g_pp + $sommePoids_kg_pp;
            $phrase_pp = poidsEnLettre($sommePoids_pp, $unite2, $type, $categorie_brute, $categorie_taille);
            $afficheWord_pp = generateAfficheWord($nom_substance_pp,$couleur_substance_pp);
            }
    //somme de poids par type de substance PF
    $queryPF = "SELECT dcc.*, cfac.poids_facture,csub.nom_couleur_substance,cate.nom_categorie, s.nom_substance, cfac.unite_poids_facture FROM contenu_facture cfac 
        INNER JOIN data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie cate ON cate.id_categorie = sds.id_categorie
        WHERE ts.code_type_substance = 'PF' AND dcc.id_data_cc=$facture";
        $resultpf= mysqli_query($conn, $queryPF);
        if(mysqli_num_rows($resultpf)> 0){
                $pf="existe";$unite2='';$categorie_brute='';  $categorie_taille="";
                $sommePoids_ct_pf = 0;$sommePoids_kg_pf = 0;$sommePoids_g_pf=0;
                while($rowpf = mysqli_fetch_assoc($resultpf)){
                    $unite_poids_facture = intval($rowpf['poids_facture']);
                    if($rowpf['unite_poids_facture']=='ct'){
                        $sommePoids_ct_pf += $unite_poids_facture;
                        $unite_pf ="GRAMMES";$_pf2='grammes';
                    }elseif($row2['unite_poids_facture']=='g'){
                        $sommePoids_g_pf += $unite_poids_facture;
                        $unite_pf ="GRAMMES";$unite2='grammes';
                    }else{
                        $sommePoids_kg_pf += $unite_poids_facture;
                        $unite_pf ="KILOGRAMMES";$unite2='kilogrammes';
                    }
                     if($rowS['nom_categorie']=="Brute"){
                        $categorie_brute="Brutes";
                    }else{
                        $categorie_taille="Taillées";
                    }
                $nom_substance_pf[] = $rowpf['nom_substance'];
                $couleur_substance_pf[] = $rowpf['nom_couleur_substance'];
                }
            if(!empty($categorie_brute)&&!empty($categorie_taille)) {
                $categorie = $categorie_brute .','. $categorie_taille;
            }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
                $categorie = $categorie_brute;
            }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
                $categorie = $categorie_taille;
            }
            $categorie_pp=$categorie;
            $type="Pierres Fines";
            $sommePoids_pf = $sommePoids_ct_pf * 0.2 + $sommePoids_g_pf + $sommePoids_kg_pf;
            $phrase_pf = poidsEnLettre($sommePoids_pf, $unite2, $type, $categorie_brute, $categorie_taille);
            $afficheWord_pf = generateAfficheWord($nom_substance_pf,$couleur_substance_pf);
            }
    //somme de poids par type de substance PIM
    $querypim = "SELECT dcc.*,cfac.unite_poids_facture,csub.nom_couleur_substance,cate.nom_categorie, s.nom_substance, cfac.poids_facture FROM contenu_facture cfac 
        INNER JOIN  data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie cate ON cate.id_categorie = sds.id_categorie
        WHERE ts.code_type_substance = 'PIM' AND dcc.id_data_cc=$facture";
        $resultpim= mysqli_query($conn, $querypim);
            if(mysqli_num_rows($resultpim)> 0){
                $pim="existe";$unite2=""; $categorie_brute='';  $categorie_taille="";
                $sommePoids_ct_pim = 0;$sommePoids_kg_pim = 0;$sommePoids_g_pim=0;
                while($rowpim = mysqli_fetch_assoc($resultpim)){
                    $unite_poids_facture = intval($rowpim['poids_facture']);
                    if($rowpim['unite_poids_facture']=='ct'){
                        $sommePoids_ct_pim += $unite_poids_facture;
                        $unite_pim ="GRAMMES";$unite2='grammes';
                    }elseif($row2['unite_poids_facture']=='g'){
                        $sommePoids_g_pim += $unite_poids_facture;
                        $unite_pim ="GRAMMES";$unite2='grammes';
                    }else{
                        $sommePoids_kg_pim += $unite_poids_facture;
                        $unite_pim ="KILOGRAMMES";$unite2='kilogrammes';
                    }
                     if($rowS['nom_categorie']=="Brute"){
                        $categorie_brute="Brutes";
                    }else{
                        $categorie_taille="Taillées";
                    }
                    $nom_substance_pim[] = $rowpim['nom_substance'];
                    $couleur_substance_pim[] = $rowpim['nom_couleur_substance'];
                }
            if(!empty($categorie_brute)&&!empty($categorie_taille)) {
                $categorie = $categorie_brute .','. $categorie_taille;
            }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
                $categorie = $categorie_brute;
            }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
                $categorie = $categorie_taille;
            }
            $categorie_pp=$categorie;
            $type="Pierres Industrielles et Minerais";
            $sommePoids_pim = $sommePoids_ct_pim * 0.2 + $sommePoids_g_pim + $sommePoids_kg_pim;
            $phrase_pim = poidsEnLettre($sommePoids_pim, $unite2, $type, $categorie_brute, $categorie_taille);
            $afficheWord_pim = generateAfficheWord($nom_substance_pim,$couleur_substance_pim);
            }
        //somme de poids par type de substance MP
        $queryMP = "SELECT dcc.*,cfac.unite_poids_facture,csub.nom_couleur_substance,cate.nom_categorie, s.nom_substance, cfac.poids_facture FROM contenu_facture cfac 
            INNER JOIN data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
            LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
            LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
            LEFT JOIN substance s ON s.id_substance = sds.id_substance
            LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
            LEFT JOIN categorie cate ON cate.id_categorie = sds.id_categorie
            WHERE ts.code_type_substance = 'PIM' AND dcc.id_data_cc=$facture";
            $resultMP= mysqli_query($conn, $queryMP);
            if(mysqli_num_rows($resultMP)> 0){
                $mp="existe";$categorie_brute='';  $categorie_taille="";
                $sommePoids_ct_mp = 0;$sommePoids_kg_mp = 0;$sommePoids_g_mp=0;
                while($rowMP = mysqli_fetch_assoc($resultMP)){
                    $unite_poids_facture = intval($rowMP['poids_facture']);
                    if($rowMP['unite_poids_facture']=='ct'){
                        $sommePoids_ct_mp += $unite_poids_facture;
                        $unite_mp ="GRAMMES";$unite2='grammes';
                    }elseif($row2['unite_poids_facture']=='g'){
                        $sommePoids_g_mp += $unite_poids_facture;
                        $unite_mp ="GRAMMES";$unite2='grammes';
                    }else{
                        $sommePoids_kg_mp += $unite_poids_facture;
                        $unite_mp ="KILOGRAMMES";$unite2='kilogrammes';
                    }
                     if($rowS['nom_categorie']=="Brute"){
                        $categorie_brute="Brutes";
                    }else{
                        $categorie_taille="Taillées";
                    }
                    $nom_substance_mp[] = $rowmp['nom_substance'];
                    $couleur_substance_mp[] = $rowmp['nom_couleur_substance'];
                }
            if(!empty($categorie_brute)&&!empty($categorie_taille)) {
                $categorie = $categorie_brute .','. $categorie_taille;
            }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
                $categorie = $categorie_brute;
            }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
                $categorie = $categorie_taille;
            }
            $categorie_pp=$categorie;
            $type="Méteaux Précieux";
            $sommePoids_mp = $sommePoids_ct_mp * 0.2 + $sommePoids_g_mp + $sommePoids_kg_mp;
            $phrase_mp = poidsEnLettre($sommePoids_mp, $unite2, $type, $categorie_brute, $categorie_taille);
            $afficheWord_mp = generateAfficheWord($nom_substance_mp,$couleur_substance_mp);
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
   
    
    $dateFormated = "d/m/Y";
    $date_modification = date($dateFormated);
    
    function generateAfficheWord($nom_substance, $couleur_substance) {
    $afficheWord = "";
    $substances_couleurs = array();
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
                    $afficheWord .= $substance .'(vide)';
                } else {
                    $afficheWord .= $substance . '(' . implode(', ', $couleurs_uniques) . ')';
                }
            }

        return $afficheWord;
    }
    
    $and = 'et';
    $totalePoidsEnLettres ='';
    $poidsEnLettre ='';
    //fonction poids en Lettre
    function poidsEnLettre($poids, $unite2, $type_substance, $categorie_brute, $categorie_taille){
        if(!empty($categorie_brute)&&!empty($categorie_taille)) {
            $categorie = $categorie_brute .','. $categorie_taille;
        }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
            $categorie = $categorie_brute;
        }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
            $categorie = $categorie_taille;
        }
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
        $poidsEnLettre = ' -'.$nombreAvantLettre.' '.$unite2.' '.$nombreApresLettre.' de ' .$type_substance. '('.$categorie.')';
        return $poidsEnLettre;
    }
    
    $templatePathScan = '../template/model_scan.docx';
    $templatePath =  '../template/model.docx';
    
    $templateScan = new TemplateProcessor($templatePathScan);
    $template = new TemplateProcessor($templatePath);
    $phrase='';
    if(!empty($pp)){
        $contenu='';
        $affiche_word=$afficheWord_pp;
        $totalePoids = number_format($sommePoids_pp, 2, '.', ''). $unite_pp;
        $contenu="Pierres Précieuses(". $categorie_pp . ')';
        $phrase = $phrase_pp;
        if(!empty($pf)){
            $contenu="Pierres Fines(". $categorie_pf . ') et ' . "Pierres Précieuses (" . $categorie_pp . ')';
            $affiche_word= $afficheWord_pp . 'et ' .$afficheWord_pf;
            $totalePoids .= number_format($sommePoids_pf, 2, '.', ''). $unite_pf;
            $phrase .= $phrase_pf;
        }
        if(!empty($pim)){
            $contenu="Pierres Précieuses(". $categorie_pp . ') et ' . "Pierres Industrielles et Minerais (" . $categorie_pim . ')';
            $affiche_word= $afficheWord_pp . 'et ' .$afficheWord_pim;
            $totalePoids .= number_format($sommePoids_pim, 2, '.', ''). $unite_pim;
            $phrase .= $phrase_pim;
        }
        if(!empty($mp)){
            $contenu="Pierres Précieuses(". $categorie_pp . ') et ' . "Méteaux Précieux (" . $categorie_mp . ')';
            $affiche_word= $afficheWord_pp . 'et ' .$afficheWord_mp;
            $totalePoids .= number_format($sommePoids_mp, 2, '.', ''). $unite_mp;
            $phrase .= $phrase_mp;
        }
        $templateScan->setValue('contenu', $contenu);
        $templateScan->setValue('afficheWord',$affiche_word);
        $templateScan->setValue('poidsTotal', $totalePoids);
        echo "Consulter";
        $template->setValue('contenu', $contenu);
        $template->setValue('afficheWord',$affiche_word);
        $template->setValue('poidsTotal', $totalePoids);
    }elseif(!empty($pf)){
        $contenu='';
        $phrase = $phrase_pf;
        $affiche_word=$afficheWord_pf;
        $totalePoids = number_format($sommePoids_pp, 2, '.', ''). $unite_pf;
        $contenu="Pierres Précieuses(". $categorie_pf . ')';
        if(!empty($pim)){
            $contenu="Pierres Fines(". $categorie_pf . ') et ' . "Pierres Industrielles et Minerais (" . $categorie_pim . ')';
            $affiche_word= $afficheWord_pf . 'et ' .$afficheWord_pim;
            $totalePoids .= number_format($sommePoids_pim, 2, '.', ''). $unite_pim;
            $phrase .= $phrase_pim;
        }
        if(!empty($mp)){
            $contenu="Pierres Fines(". $categorie_pf . ') et ' . "Méteaux Précieux (" . $categorie_mp . ')';
            $affiche_word= $afficheWord_pf . 'et ' .$afficheWord_mp;
            $totalePoids .= number_format($sommePoids_mp, 2, '.', ''). $unite_mp;
            $phrase .= $phrase_mp;
        }
        $templateScan->setValue('contenu', $contenu);
        $templateScan->setValue('afficheWord',$affiche_word);
        $templateScan->setValue('poidsTotal', $totalePoids);
        $template->setValue('contenu', $contenu);
        $template->setValue('afficheWord',$affiche_word);
        $template->setValue('poidsTotal', $totalePoids);
        
    }elseif(!empty($pim)){
        $contenu='';
        $phrase = $phrase_pim;
        $affiche_word=$categorie_pim;
        $totalePoids = number_format($sommePoids_pim, 2, '.', ''). $unite_pim;
        $contenu="Pierres Industrielles et Minerais(". $categorie_pim . ')';
        if(!empty($mp)){
            $contenu="Pierres Industrielles et Minerais(". $categorie_pim . ') et ' . "Pierres Précieuses(" . $categorie_mp . ')';
            $totalePoids .= number_format($sommePoids_pim, 2, '.', '') . $unite_mp;
            $phrase .= $phrase_mp;
        }
        $templateScan->setValue('contenu', $contenu);
        $templateScan->setValue('afficheWord',$affiche_word);
        $templateScan->setValue('poidsTotal', $totalePoids);
        $template->setValue('contenu', $contenu);
        $template->setValue('afficheWord',$affiche_word);
        $template->setValue('poidsTotal', $totalePoids);
    }elseif(!empty($mp)){
        $phrase = $phrase_mp;
        $contenu='';
        $affiche_word=$afficheWord_mp;
        $totalePoids = number_format($sommePoids_mp, 2, '.', '') . $unite_mp;
        $contenu="Méteaux Précieux(". $categorie_mp . ')';
        
        $templateScan->setValue('contenu', $contenu);
        $templateScan->setValue('afficheWord',$affiche_word);
        $templateScan->setValue('poidsTotal', $totalePoids);
        $template->setValue('contenu', $contenu);
        $template->setValue('afficheWord',$affiche_word);
        $template->setValue('poidsTotal', $totalePoids);
    }else{
        $templateScan->setValue('contenu', '');
        $templateScan->setValue('afficheWord','');
        $templateScan->setValue('poidsTotal', '');
        $template->setValue('contenu', '');
        $template->setValue('afficheWord','');
        $template->setValue('poidsTotal', '');
    }
    //societe
    $templateScan->setValue('num_pv', $num_pv);
    $templateScan->setValue('nom_societe', $nom_societe_expediteur);
    $templateScan->setValue('adresse_societe', $adresse_societe_expediteur);
    $templateScan->setValue('destination_finale', $pays_destination);
    $templateScan->setValue('visa', $visa);
    //numéro et date
    $templateScan->setValue('date', $dateEnTexte);
    $templateScan->setValue('poidsEnLettre', $phrase);
    $templateScan->setValue('date', $dateEnTexte);
    $templateScan->setValue('num_facture', $num_facture);
    $templateScan->setValue('date_facture', $date);
    $templateScan->setValue('num_fiche_declaration', $declaration);
    $templateScan->setValue('date_fiche_declaration', $date_declaration);
    $templateScan->setValue('num_domiciliation', $numDom);
    $templateScan->setValue('num_lp3e', $num_lp3);
    $templateScan->setValue('date_lp3e', $date_lp3);
    $templateScan->setValue('nombre_colis', $nombre);
    $templateScan->setValue('type_colis', $type_colis);
    $templateScan->setValue('lieu_scellage', $lieu_sce);
    $templateScan->setValue('lieu_embarquement', $lieu_emb);

    // Initialisez la variable en dehors de la boucle pour accumuler les valeurs
    $remplace_agent=array();
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
        
         //societe
        $template->setValue('num_pv', $num_pv);
        $template->setValue('nom_societe', $nom_societe_expediteur);
        $template->setValue('adresse_societe', $adresse_societe_expediteur);
        $template->setValue('destination_finale', $pays_destination);
        $template->setValue('visa', $visa);
        //numéro et date
        $template->setValue('date', $dateEnTexte);
        $template->setValue('poidsEnLettre', $phrase);
        $template->setValue('date', $dateEnTexte);
        $template->setValue('num_facture', $num_facture);
        $template->setValue('date_facture', $date);
        $template->setValue('num_fiche_declaration', $declaration);
        $template->setValue('date_fiche_declaration', $date_declaration);
        $template->setValue('num_domiciliation', $numDom);
        $template->setValue('num_lp3e', $num_lp3);
        $template->setValue('date_lp3e', $date_lp3);
        $template->setValue('nombre_colis', $nombre);
        $template->setValue('type_colis', $type_colis);
        $template->setValue('lieu_scellage', $lieu_sce);
        $template->setValue('lieu_embarquement', $lieu_emb);
        $template->cloneBlock('block_name', 0, true, false, $remplace_agent);

        // Enregistrer le nouveau document DOCX
        $nouveau_nom_fichierQR = $numPVClear  . '.docx';
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