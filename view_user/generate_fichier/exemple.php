<?php
require_once('../../scripts/db_connect.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../mylibs/phpqrcode/qrlib.php';
include 'nombre_en_lettre.php';
$facture=72;
$id_data=72;
    $categorie_pp='';
    $sommePoids_pp=0;
    $afficheWord_pp='';
    $unite_pp="";

    $categorie_pf='';
    $sommePoids_pf=0;
    $afficheWord_pf='';
    $unite_pf="";
    $phrase_pf2='';
    $sommePoids_kg_pf = 0;

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
                    $unite_poids_facture = floatval($rowpp['poids_facture']);
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
                    if(empty($rowpp['nom_couleur_substance'])){
                        $couleur_substance_pp []='vide';
                    }else{
                        $couleur_substance_pp[] = $rowpp['nom_couleur_substance'];
                    }
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
                    $unite_poids_facture = floatval($rowpf['poids_facture']);
                    if($rowpf['unite_poids_facture']=='ct'){
                        $sommePoids_ct_pf += $unite_poids_facture;
                        $unite_pf ="GRAMMES";$_pf2='grammes';
                    }elseif($rowpf['unite_poids_facture']=='g'){
                        $sommePoids_g_pf += $unite_poids_facture;
                        $unite_pf ="GRAMMES";$unite2='grammes';
                    }else{
                        $sommePoids_kg_pf += $unite_poids_facture;
                        $unite_pf_kg ="KILOGRAMMES";$unite_kg2='kilogrammes';
                    }
                     if($rowpf['nom_categorie']=="Brute"){
                        $categorie_brute="Brutes";
                    }else{
                        $categorie_taille="Taillées";
                    }
                $nom_substance_pf[] = $rowpf['nom_substance'];
                if(empty($rowpf['nom_couleur_substance'])){
                        $couleur_substance_pf []='vide';
                    }else{
                        $couleur_substance_pf[] = $rowpf['nom_couleur_substance'];
                    }
                }
            if(!empty($categorie_brute)&&!empty($categorie_taille)) {
                $categorie = $categorie_brute .','. $categorie_taille;
            }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
                $categorie = $categorie_brute;
            }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
                $categorie = $categorie_taille;
            }
            $categorie_pf=$categorie;
            $type="Pierres Fines";
            $sommePoids_pf = $sommePoids_ct_pf * 0.2 + $sommePoids_g_pf;
            if($sommePoids_kg_pf != 0){
                $phrase_pf2 = poidsEnLettre($sommePoids_kg_pf, $unite_kg2, $type, $categorie_brute, $categorie_taille);
            }
            if(($sommePoids_kg_pf!=0)&&($sommePoids_pf!=0)){
                $sommePoids_pf = $sommePoids_kg_pf + $sommePoids_pf / 1000;
                $unite_pf ="KILOGRAMMES";$unite2='kologrammes';
            }else if($sommePoids_kg_pf!=0){
                $sommePoids_pf = $sommePoids_kg_pf;
                $unite_pf ="KILOGRAMMES";$unite2='kologrammes';
            }
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
                    $unite_poids_facture = floatval($rowpim['poids_facture']);
                    if($rowpim['unite_poids_facture']=='ct'){
                        $sommePoids_ct_pim += $unite_poids_facture;
                        $unite_pim ="GRAMMES";$unite2='grammes';
                    }elseif($rowpim['unite_poids_facture']=='g'){
                        $sommePoids_g_pim += $unite_poids_facture;
                        $unite_pim ="GRAMMES";$unite2='grammes';
                    }else{
                        $sommePoids_kg_pim += $unite_poids_facture;
                        $unite_pim ="KILOGRAMMES";$unite2='kilogrammes';
                    }
                     if($rowpim['nom_categorie']=="Brute"){
                        $categorie_brute="Brutes";
                    }else{
                        $categorie_taille="Taillées";
                    }
                    $nom_substance_pim[] = $rowpim['nom_substance'];
                    if(empty($rowpim['nom_couleur_substance'])){
                        $couleur_substance_pim []='vide';
                    }else{
                        $couleur_substance_pim[] = $rowpim['nom_couleur_substance'];
                    }
                }
            if(!empty($categorie_brute)&&!empty($categorie_taille)) {
                $categorie = $categorie_brute .','. $categorie_taille;
            }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
                $categorie = $categorie_brute;
            }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
                $categorie = $categorie_taille;
            }
            $categorie_pim=$categorie;
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
            WHERE ts.code_type_substance = 'MP' AND dcc.id_data_cc=$facture";
            $resultMP= mysqli_query($conn, $queryMP);
            if(mysqli_num_rows($resultMP)> 0){
                $mp="existe";$categorie_brute='';  $categorie_taille="";
                $sommePoids_ct_mp = 0;$sommePoids_kg_mp = 0;$sommePoids_g_mp=0;
                while($rowMP = mysqli_fetch_assoc($resultMP)){
                    $unite_poids_facture = floatval($rowMP['poids_facture']);
                    if($rowMP['unite_poids_facture']=='ct'){
                        $sommePoids_ct_mp += $unite_poids_facture;
                        $unite_mp ="GRAMMES";$unite2='grammes';
                    }elseif($rowMP['unite_poids_facture']=='g'){
                        $sommePoids_g_mp += $unite_poids_facture;
                        $unite_mp ="GRAMMES";$unite2='grammes';
                    }else{
                        $sommePoids_kg_mp += $unite_poids_facture;
                        $unite_mp ="KILOGRAMMES";$unite2='kilogrammes';
                    }
                     if($rowMP['nom_categorie']=="Brute"){
                        $categorie_brute="Brutes";
                    }else{
                        $categorie_taille="Taillées";
                    }
                    $nom_substance_mp[] = $rowMP['nom_substance'];
                    if(empty($rowMP['nom_couleur_substance'])){
                        $couleur_substance_mp []='vide';
                    }else{
                        $couleur_substance_mp[] = $rowMP['nom_couleur_substance'];
                    }
                }
            if(!empty($categorie_brute)&&!empty($categorie_taille)) {
                $categorie = $categorie_brute .','. $categorie_taille;
            }elseif(!empty($categorie_brute)&& empty($categorie_taille)) {
                $categorie = $categorie_brute;
            }elseif(empty($categorie_brute)&& !empty($categorie_taille)) {
                $categorie = $categorie_taille;
            }
            $categorie_mp=$categorie;
            $type="Méteaux Précieux";
            $sommePoids_mp = $sommePoids_ct_mp * 0.2 + $sommePoids_g_mp + $sommePoids_kg_mp;
            $phrase_mp = poidsEnLettre($sommePoids_mp, $unite2, $type, $categorie_brute, $categorie_taille);
            $afficheWord_mp = generateAfficheWord($nom_substance_mp,$couleur_substance_mp);
        }
    $amort="SELECT sds.*, cfac.id_data_cc, cfac.poids_facture, cfac.unite_poids_facture, sub.*, ts.* FROM contenu_facture AS cfac 
        INNER JOIN substance_detaille_substance AS sds ON cfac.id_detaille_substance= sds.id_detaille_substance
        LEFT JOIN substance AS sub ON sds.id_substance = sub.id_substance LEFT JOIN type_substance AS ts ON sub.id_type_substance = 
        ts.id_type_substance WHERE ts.code_type_substance = 'PA'  AND cfac.id_data_cc=$id_data";
        $result= mysqli_query($conn, $amort);
        $poids_somme=0;
                $categorie_pa = 'Travaillées';$unite_pa='KILOGRAMMES';
                $nom_substance =array();
                $couleur_substance =  array();
                $nom_type="";$unite ="KILOGRAMMES"; $unite2 = "kgs";
                if(mysqli_num_rows($result)> 0){
                    $pa="existe";
                    while($row = mysqli_fetch_assoc($result)){
                        $unite_poids_facture = floatval($row['poids_facture']);
                        $nom_substance [] = $row['nom_substance'];
                        $poids_somme += $unite_poids_facture;
                        $nom_type=$row['nom_type_substance'];
                        $couleur_substance [] ='vide';

                    }
                    $categorie_brute='';
                $phrase_pa = poidsEnLettre($poids_somme, $unite2, $type, $categorie_brute, $categorie_pa);
                $afficheWord_pa = generateAfficheWord($nom_substance,$couleur_substance);
                }

        $amorti="SELECT sds.*, cfac.id_data_cc, cfac.poids_facture, cfac.unite_poids_facture, sub.*, ts.* FROM contenu_facture AS cfac 
        INNER JOIN substance_detaille_substance AS sds ON cfac.id_detaille_substance= sds.id_detaille_substance
        LEFT JOIN substance AS sub ON sds.id_substance = sub.id_substance LEFT JOIN type_substance AS ts ON sub.id_type_substance = 
        ts.id_type_substance WHERE ts.code_type_substance = 'FT'  AND cfac.id_data_cc=$id_data";
        $result_amort= mysqli_query($conn, $amorti);
        $poids_somme=0;
                $categorie_ft = 'Taillé';
                $nom_substance =array();
                $couleur_substance =  array();
                $nom_type="";$unite ="KILOGRAMMES"; $unite2 = "kgs";$unite_ft='KILOGRAMMES';
                if(mysqli_num_rows($result_amort)> 0){
                    $ft='existe';
                    while($row = mysqli_fetch_assoc($result_amort)){
                        $unite_poids_facture = floatval($row['poids_facture']);
                        $nom_substance [] = $row['nom_substance'];
                        $poids_somme += $unite_poids_facture;
                        $nom_type=$row['nom_type_substance'];
                        $couleur_substance [] ='vide';

                    }
                $categorie_brute='';
                $phrase_pa = poidsEnLettre($poids_somme, $unite2, $type, $categorie_brute, $categorie_ft);
                $afficheWord_pa = generateAfficheWord($nom_substance,$couleur_substance);
                }
                

    //recherche de nom et adresse de societe expediteur
    // $queryExpediteur = "SELECT * FROM societe_expediteur WHERE id_societe_expediteur=$expediteur";
    // $resultExpediteur = mysqli_query($conn, $queryExpediteur);
    // $rowExpediteur = mysqli_fetch_assoc($resultExpediteur);
    // $nom_societe_expediteur= $rowExpediteur['nom_societe_expediteur'];
    // $adresse_societe_expediteur= $rowExpediteur['adresse_societe_expediteur'];
    // //recherche de nom et adresse de societe expediteur
    // $queryImportateur = "SELECT pays_destination FROM societe_importateur WHERE id_societe_importateur=$destination";
    // $resultImportateur = mysqli_query($conn, $queryImportateur);
    // $rowImportateur = mysqli_fetch_assoc($resultImportateur);
    // $pays_destination= $rowImportateur['pays_destination'];
    // $pays_destination = htmlspecialchars($pays_destination, ENT_QUOTES, 'UTF-8');
    // $visa= "";
   
    
    $dateFormated = "d/m/Y";
    $date_modification = date($dateFormated);
    
    function generateAfficheWord($nom_substance, $couleur_substance) {
    $afficheWord = "";
    $substances_couleurs = array();
        for ($i = 0; $i < count($nom_substance); $i++) {
                $substance = $nom_substance[$i];
                $couleur = $couleur_substance[$i];
                echo $couleur;
                echo $substance;
                // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
                if (array_key_exists($substance, $substances_couleurs)) {
                    $substances_couleurs[$substance][] = $couleur;
                } else {
                    $substances_couleurs[$substance] = array($couleur);
                }
            }

            // Affichage des résultats
            // foreach ($substances_couleurs as $substance => $couleurs) {
            //     $couleurs_uniques = array_unique($couleurs);
            //     if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
            //         $afficheWord .= $substance. PHP_EOL;
            //     } else {
            //         $afficheWord .= $substance . '(' . implode(', ', $couleurs_uniques) . ')'. PHP_EOL;
            //     }
            // }
            // Création d'une liste formatée des substances avec leurs couleurs
            $liste_substances = array();
            foreach ($substances_couleurs as $substance => $couleurs) {
                $couleurs_uniques = array_unique($couleurs);
                if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
                    $liste_substances[] = $substance;
                } else {
                    $liste_substances[] = $substance . '(' . implode(', ', $couleurs_uniques) . ')';
                }
            }
            if (count($liste_substances) > 1) {
                $afficheWord = implode(', ', array_slice($liste_substances, 0, -1)) . ' et ' . end($liste_substances);
            } else {
                $afficheWord = $liste_substances[0];
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
    
    $phrase='';

    function updateValues(&$contenu, &$affiche_word, &$totalePoids, &$phrase, $categorie, $afficheWord, $sommePoids, $unite, $phrasePart, $isFirst = false) {
        if ($isFirst) {
            $contenu = $categorie;
            $affiche_word = $afficheWord;
            $totalePoids = number_format($sommePoids, 2, '.', '') . $unite;
        } else {
            $contenu .= ' et ' . $categorie;
            $affiche_word .= ' et ' . $afficheWord;
            $totalePoids .= ' et ' . number_format($sommePoids, 2, '.', '') . $unite;
        }
        $phrase .= $phrasePart;
        echo $affiche_word;
    }
    $contenu = '';
        $affiche_word = '';
        $totalePoids = '';
        $phrase = '';
        $first=true;

        if (!empty($pim)) {
            updateValues($contenu, $affiche_word, $totalePoids, $phrase, 'Pierres Industrielles et Minerais(' . $categorie_pim . ')', $afficheWord_pim, $sommePoids_pim, $unite_pim, $phrase_pim, $first);
            $first=false;
        }

        if (!empty($pa)) {
            updateValues($contenu, $affiche_word, $totalePoids, $phrase, 'Pierres Assorties(' . $categorie_pa . ')', $afficheWord_pa, $sommePoids_pa, $unite_pa, $phrase_pa, $first);
            $first=false;
        }

        if (!empty($ft)) {
            updateValues($contenu, $affiche_word, $totalePoids, $phrase, 'Fossille(' . $categorie_ft . ')', $afficheWord_ft, $sommePoids_ft, $unite_ft, $phrase_ft, $first);
            $first=false;
        }
        if (!empty($pp)) {
            updateValues($contenu, $affiche_word, $totalePoids, $phrase, 'Pierres Précieuses(' . $categorie_pp . ')', $afficheWord_pp, $sommePoids_pp, $unite_pp, $phrase_pp, $first);
            $first=false;
        } 
        if (!empty($pf)) {
            updateValues($contenu, $affiche_word, $totalePoids, $phrase, 'Pierres Fines(' . $categorie_pf . ')', $afficheWord_pf, $sommePoids_pf, $unite_pf, $phrase_pf,$first);
            $first=false;
        }

        if (!empty($mp)) {
            updateValues($contenu, $affiche_word, $totalePoids, $phrase, 'Méteaux Précieux(' . $categorie_mp . ')', $afficheWord_mp, $sommePoids_mp, $unite_mp, $phrase_mp,$first);
            $first=false;
        }

        // if ($sommePoids_kg_pft != 0) {
        //     $phrase .= $phrase_pf2;
        //     $totalePoids .= ' ' . $sommePoids_kg_pft . ' ' . $unite_pf_kg;
        // }

    
?>