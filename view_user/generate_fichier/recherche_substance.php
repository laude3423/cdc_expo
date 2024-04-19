<?php
include_once('../../scripts/db_connect.php');
$id_sub=array();
$query = "SELECT DISTINCT sds.id_substance FROM data_cc dcc
INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance WHERE dcc.id_data_cc=$id_data";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$id_sub[] = $row['id_substance'];
//PF gemmes Brute

$pfb="";$pft="";$ppb="";$pppt="";$pimb="";$pimt="";$mpb="";$mpt="";

$afficheWord_pfb= array();
$afficheWord_pft= array();
$afficheWord_ppb= array();
$afficheWord_ppt= array();
$afficheWord_mpb= array();
$afficheWord_mpt= array();
$afficheWord_pimb= array();
$afficheWord_pimt= array();


if(count($id_sub)> 0){
    for($i= 0;$i<count($id_sub);$i++){
        //PF brute
        $queryR = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result= mysqli_query($conn, $queryR);
        if(mysqli_num_rows($result)> 0){
            $unite =""; $unite2 ="";
            $pfb="existe";$sommePoids_kg_pfb=0; $sommePoids_g_pfb=0; $sommePoids_ct_pfb=0;
            while($row = mysqli_fetch_assoc($result)){
                $unite_poids_facture = floatval($row['poids_facture']);
                if($row['unite_poids_facture']=='ct'){
                    $sommePoids_ct_pfb  += $unite_poids_facture;
                     $unite ="GRAMMES"; $unite2 = "grs";
                }elseif($row['unite_poids_facture']=='g'){
                    $sommePoids_g_pfb  += $unite_poids_facture;
                    $unite ="GRAMMES"; $unite2 = "grs";
                }else{
                    $sommePoids_kg_pfb += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                
                $nom_substance_pfb [] = $row1['nom_substance'];
                $couleur_substance_pfb [] = $row1['nom_couleur_substance'];
            
            }
        $sommePoids = $sommePoids_ct_ppb * 5 + $sommePoids_ct_ppb + $sommePoids_kg_ppb;
        $afficheWord_pfb[] = generateAfficheWord($nom_substance_pfb,$sommePoids,$unite,$unite2, $couleur_substance_pfb);
        }
        
        //PF Taillé
        $queryR1 = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.unite_poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND c.id_categorie =2 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result1= mysqli_query($conn, $queryR1);
        if(mysqli_num_rows($result1)> 0){
            $unite =""; $unite2 ="";
            $pft="existe";$sommePoids_ct_pft = 0;$sommePoids_kg_pft = 0;$sommePoids_g_pft=0;
            while($row1 = mysqli_fetch_assoc($result1)){
                $unite_poids_facture = floatval($row1['poids_facture']);
                if($row1['unite_poids_facture']=='ct'){
                    $sommePoids_ct_pft  += $unite_poids_facture;
                     $unite ="GRAMMES"; $unite2 = "grs";
                }elseif($row1['unite_poids_facture']=='g'){
                    $sommePoids_g_pft  += $unite_poids_facture;
                    $unite ="GRAMMES"; $unite2 = "grs";
                }else{
                    $sommePoids_kg_pft += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                $nom_substance_pft [] = $row1['nom_substance'];
                $couleur_substance_pft [] = $row1['nom_couleur_substance'];
            }
            $sommePoids = $sommePoids_ct_pft * 5 + $sommePoids_g_pft + $sommePoids_kg_pft;
            $afficheWord_pft[] = generateAfficheWord($nom_substance_pft,$sommePoids,$unite,$unite2, $couleur_substance_pft);
        }
        //PP brute
        $queryR2 = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PP' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result2= mysqli_query($conn, $queryR2);
        if(mysqli_num_rows($result2)> 0){
            $ppb="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_ppb = 0;$sommePoids_kg_ppb = 0;$sommePoids_g_ppb=0;
            while($row2 = mysqli_fetch_assoc($result2)){
                $unite_poids_facture = floatval($row2['poids_facture']);
                if($row2['unite_poids_facture']=='ct'){
                    $sommePoids_ct_ppb += $unite_poids_facture;
                    $unite ="GRAMMES"; $unite2 = "grs";
                }elseif($row2['unite_poids_facture']=='g'){
                    $sommePoids_g_ppb += $unite_poids_facture;
                    $unite ="GRAMMES"; $unite2 = "grs";
                }else{
                    $sommePoids_kg_ppb += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                $nom_substance_ppb[] = $row2['nom_substance'];
                $couleur_substance_ppb[] = $row2['nom_couleur_substance'];
            }
            $sommePoids = $sommePoids_ct_ppb * 5 + $sommePoids_g_ppb + $sommePoids_kg_ppb;
            $afficheWord_ppb[] = generateAfficheWord($nom_substance_ppb,$sommePoids,$unite,$unite2, $couleur_substance_ppb);
        }
        //PP Taille
        $queryR3 = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PP' AND c.id_categorie =2 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result3= mysqli_query($conn, $queryR3);
        if(mysqli_num_rows($result3)> 0){
            $ppt="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_ppt = 0;$sommePoids_kg_ppt = 0;$sommePoids_g_ppt=0;
            while($row3 = mysqli_fetch_assoc($result3)){
                $unite_poids_facture = floatval($row3['poids_facture']);
                if($row3['unite_poids_facture']=='ct'){
                    $unite ="GRAMMES"; $unite2 = "grs";
                    $sommePoids_ct_ppt  += $unite_poids_facture;
                }elseif($row3['unite_poids_facture']=='g'){
                    $sommePoids_g_ppt  += $unite_poids_facture;
                    $unite ="GRAMMES"; $unite2 = "grs";
                }else{
                    $sommePoids_kg_ppt += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                $nom_substance_ppt [] = $row3['nom_substance'];
                $couleur_substance_ppt [] = $row3['nom_couleur_substance'];
            }
            $sommePoids = $sommePoids_ct_ppt * 5 + $sommePoids_g_ppt + $sommePoids_kg_ppt;
             $afficheWord_ppt[] = generateAfficheWord($nom_substance_ppt,$sommePoids,$unite,$unite2, $couleur_substance_ppt);
        }
        //PIM brute
         $queryR4 = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result4= mysqli_query($conn, $queryR4);
        if(mysqli_num_rows($result4)> 0){
            $pimb="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_pimb = 0;$sommePoids_kg_pimb = 0;$sommePoids_g_pimb=0;
            while($row4 = mysqli_fetch_assoc($result4)){
                $unite_poids_facture = floatval($row4['poids_facture']);
                if($row4['unite_poids_facture']=='ct'){
                    $sommePoids_ct_pimb  += $unite_poids_facture;
                    $unite ="GRAMMES"; $unite2 = "grs";
                }elseif($row4['unite_poids_facture']=='g'){
                    $unite ="GRAMMES"; $unite2 = "grs";
                    $sommePoids_g_pimb  += $unite_poids_facture;
                }else{
                    $sommePoids_kg_pimb += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                $nom_substance_pimb [] = $row4['nom_substance'];
                $couleur_substance_pimb [] = $row4['nom_couleur_substance'];
            }
            $sommePoids = $sommePoids_ct_pimb * 5 + $sommePoids_g_pimb + $sommePoids_kg_pimb;
            $afficheWord_pimb[] = generateAfficheWord($nom_substance_pimb,$sommePoids,$unite,$unite2, $couleur_substance_pimb);
        }
        //PIM taillé
        $queryR5 = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result5= mysqli_query($conn, $queryR5);
        if(mysqli_num_rows($result5)> 0){
            $pimt="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_pimt = 0;$sommePoids_kg_pimt = 0;$sommePoids_g_pimt=0;
            while($row5 = mysqli_fetch_assoc($result5)){
                $unite_poids_facture = floatval($row5['poids_facture']);
                if($row5['unite_poids_facture']=='ct'){
                    $unite ="GRAMMES"; $unite2 = "grs";
                    $sommePoids_ct_pimt  += $unite_poids_facture;
                }elseif($row5['unite_poids_facture']=='g'){
                    $sommePoids_g_pimt  += $unite_poids_facture;
                    $unite ="GRAMMES"; $unite2 = "grs";
                }else{
                    $sommePoids_kg_pimt += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                $nom_substance_pimt [] = $row5['nom_substance'];
                $couleur_substance_pimt [] = $row5['nom_couleur_substance'];
            }
            $sommePoids = $sommePoids_ct_pimt * 5 + $sommePoids_g_pimt + $sommePoids_kg_pimt;
            $afficheWord_pimt[] = generateAfficheWord($nom_substance_pimt,$sommePoids,$unite,$unite2, $couleur_substance_pimt);
        }
        //MP brute
        $queryR6 = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result6= mysqli_query($conn, $queryR6);
        if(mysqli_num_rows($result6)> 0){
            $mpb="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_mpb = 0;$sommePoids_kg_mpb = 0;$sommePoids_g_mpb=0;
            while($row6 = mysqli_fetch_assoc($result6)){
                $unite_poids_facture = floatval($row6['poids_facture']);
                $unite ="GRAMMES"; $unite2 = "grs";
                if($row6['unite_poids_facture']=='ct'){
                    $sommePoids_ct_mpb  += $unite_poids_facture;
                }elseif($row6['unite_poids_facture']=='g'){
                    $unite ="GRAMMES"; $unite2 = "grs";
                    $sommePoids_g_mpb  += $unite_poids_facture;
                }else{
                    $sommePoids_kg_mpb += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                $nom_substance_mpb [] = $row6['nom_substance'];
                $couleur_substance_mpb [] = $row6['nom_couleur_substance'];
            }
            $sommePoids = $sommePoids_ct_mpb * 5 + $sommePoids_g_mpb + $sommePoids_kg_mpb;
            $afficheWord_mpb[] = generateAfficheWord($nom_substance_mpb,$sommePoids,$unite,$unite2, $couleur_substance_mpb);
        }
        //MP Taille
        $queryR7 = "SELECT dcc.id_data_cc, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data AND s.id_substance=" . $id_sub[$i];
        $result7= mysqli_query($conn, $queryR7);
        if(mysqli_num_rows($result7)> 0){
            $mpt="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_mpt = 0;$sommePoids_kg_mpt = 0;$sommePoids_g_mpt=0;
            while($row7 = mysqli_fetch_assoc($result7)){
                $unite_poids_facture = floatval($row7['poids_facture']);
                if($row7['unite_poids_facture']=='ct'){
                    $sommePoids_ct_mpt  += $$unite_poids_facture;
                }elseif($row7['unite_poids_facture']=='g'){
                    $sommePoids_ct_mpt  += $$unite_poids_facture;
                }else{
                    $sommePoids_kg_mpt += $unite_poids_facture;
                    $unite ="KILOGRAMMES"; $unite2 = "kgs";
                }
                $nom_substance_mpt [] = $row7['nom_substance'];
                $couleur_substance_mpt [] = $row7['nom_couleur_substance'];
            }
            $sommePoids = $sommePoids_ct_mpt * 5 + $sommePoids_g_mpt + $sommePoids_kg_mpt;
            $afficheWord_mpt[] = generateAfficheWord($nom_substance_mpt,$sommePoids,$unite,$unite2, $couleur_substance_mpt );
        }
        
    }
}

function generateAfficheWord($nom_substance, $sommePoids, $unite, $unite2, $couleur_substance) {
    $afficheWord = "";
    $substances_couleurs = array();
    $nombreApresLettre='';
     $nombreFormat = number_format($sommePoids, 2, '.', '');
            // Séparer la partie avant et après la virgule
    $nombreExplode = explode('.', $nombreFormat);
    $nombreAvant = $nombreExplode[0];
    $nombreApres = $nombreExplode[1];

    if ($nombreApres > 0) {
        $nombreCompare = comparer($nombreApres);
        $nombreApresLettre = nombreEnLettres($nombreCompare);
    }
    $nombreAvantLettre = nombreEnLettres($nombreAvant);

    if (count($nom_substance) > 0) {
        for ($i = 0; $i < count($nom_substance); $i++) {
            $substance = $nom_substance[$i];
            $couleur = $couleur_substance[$i];

            // Si la substance existe déjà dans le tableau, ajoutez la couleur, sinon créez une nouvelle entrée
            if (array_key_exists($substance, $substances_couleurs)) {
                $substances_couleurs[$substance][] = $couleur;
            } else {
                $substances_couleurs[$substance] = array($couleur);
            }
            // Construction de la chaîne et ajout à $afficheWord
        }
    $afficheWord = '-'.$nombreAvantLettre . ' ' . $unite . ' ' . $nombreApresLettre . ' (' . $nombreFormat . ' ' . $unite2 . ') de ';
        // Affichage des résultats
        foreach ($substances_couleurs as $substance => $couleurs) {
            $couleurs_uniques = array_unique($couleurs);
            if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
                $afficheWord .= $substance . '(vide)' . PHP_EOL;
            } else {
                $afficheWord .= $substance . '(' . implode(', ', $couleurs_uniques) . ')' . PHP_EOL;
            }
        }
    }

    return $afficheWord;
}


?>