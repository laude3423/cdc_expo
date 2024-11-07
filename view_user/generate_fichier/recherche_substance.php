<?php
include_once('../../scripts/db_connect.php');
$id_sub=array();
$query = "SELECT DISTINCT sds.id_substance FROM data_cc dcc
INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance WHERE dcc.id_data_cc=$id_data";
$result = mysqli_query($conn, $query);
while($row = mysqli_fetch_assoc($result)){
    $id_sub[] = $row['id_substance'];
}

//PF gemmes Brute

$pfb="";$pft="";$ppb="";$pppt="";$pimb="";$pimt="";$mpb="";$mpt="";$pa=""; $ft="";

$afficheWord_pfb= array();
$afficheWord_pft= array();
$afficheWord_ppb= array();
$afficheWord_ppt= array();
$afficheWord_mpb= array();
$afficheWord_mpt= array();
$afficheWord_pimb= array();
$afficheWord_pimt= array();
$afficheWord_ft = array();
$afficheWord_pa=array();
$afficheWord_pfb_kg=array();
$afficheWord_pft_kg=array();
$nom_substance_kg=array();


if(count($id_sub)> 0){
        //PF brute
        $queryR = "SELECT dcc.id_data_cc, ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM contenu_facture cfac
        INNER JOIN data_cc dcc ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND cfac.preforme =1 AND dcc.id_data_cc=$id_data";
        $result= mysqli_query($conn, $queryR);
        if(mysqli_num_rows($result)> 0){
            $unite =""; $unite2 =""; $unite_kg=''; $unite_kg2='';
            $pfb="existe";$sommePoids_kg_pfb=0; $sommePoids_g_pfb=0; $sommePoids_ct_pfb=0;
            while($row = mysqli_fetch_assoc($result)){
                $unite_poids_facture = floatval($row['poids_facture']);
                if($row['unite_poids_facture']=='g'){
                    $sommePoids_g_pfb  += $unite_poids_facture;
                    $unite ="grammes"; $unite2 = "g";
                }else{
                    $sommePoids_kg_pfb += $unite_poids_facture;
                    $unite_kg ="kilogrammes"; $unite_kg2 = "kg";
                    $nom_type_kg=$row['nom_type_substance'];
                    $nom_substance_kg [] = $row['nom_substance'];
                }
                 $nom_type=$row['nom_type_substance'];
                $nom_substance_pfb [] = $row['nom_substance'];
                if(empty($row['nom_couleur_substance'])){
                    $couleur_substance_pfb []='vide';
                }else{
                    $couleur_substance_pfb [] = $row['nom_couleur_substance'];
                }
                
            
            }
        $categorie='Brutes';
        $sommePoids=$sommePoids_g_pfb;
            if(($sommePoids_kg_pfb!=0)&&($sommePoids_g_pfb!=0)){
                $sommePoids = $sommePoids_kg_pfb + $sommePoids_g_pfb / 1000;
                $unite ="KILOGRAMMES";$unite2='kologrammes';
            }else if($sommePoids_kg_pfb!=0){
                $sommePoids = $sommePoids_kg_pfb;
                $unite ="KILOGRAMMES";$unite2='kologrammes';
            }
        // if($sommePoids_kg_pfb != 0){
        //    $afficheWord_pfb[] = generateAfficheWord($nom_type_kg,$categorie, $nom_substance_kg,$sommePoids_kg_pfb,$unite_kg,$unite_kg2, $couleur_substance_pfb);
        // }
        if($sommePoids != 0){
            $afficheWord_pfb[] = generateAfficheWord($nom_type,$categorie, $nom_substance_pfb,$sommePoids,$unite,$unite2, $couleur_substance_pfb);
        }else{
            echo"Consulter";
        }
        }
        
        //PF Taillé
        $queryR1 = "SELECT dcc.id_data_cc,ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND cfac.preforme =2 AND dcc.id_data_cc=$id_data";
        $result1= mysqli_query($conn, $queryR1);
        if(mysqli_num_rows($result1)> 0){
            $unite =""; $unite2 ="";$unite_kg=''; $unite_kg2='';
            $pft="existe"; $sommePoids_kg_pft = 0;$sommePoids_g_pft=0;
            while($row1 = mysqli_fetch_assoc($result1)){
                $unite_poids_facture = floatval($row1['poids_facture']);
                if($row1['unite_poids_facture']=='g'){
                    $sommePoids_g_pft  += $unite_poids_facture;
                    $unite ="grammes"; $unite2 = "g";
                    
                }else{
                    $sommePoids_kg_pft += $unite_poids_facture;
                    $unite_kg ="kilogrammes"; $unite_kg2 = "kg";
                }
                if(empty($row1['nom_couleur_substance'])){
                    $couleur_substance_pft []='vide';
                }else{
                    $couleur_substance_pft [] = $row1['nom_couleur_substance'];
                }
                $nom_substance_pft [] = $row1['nom_substance'];
                $nom_type=$row1['nom_type_substance'];
            }
            $categorie='Taillées';
            $sommePoids = $sommePoids_g_pft;

            if(($sommePoids_kg_pft!=0)&&($sommePoids!=0)){
                $sommePoids = $sommePoids_kg_pft + $sommePoids / 1000;
                $unite ="KILOGRAMMES";$unite2='kologrammes';
            }else if($sommePoids_kg_pft!=0){
                $sommePoids = $sommePoids_kg_pft;
                $unite ="KILOGRAMMES";$unite2='kologrammes';
            }
            
            if($sommePoids != 0){
                $afficheWord_pft[] = generateAfficheWord($nom_type,$categorie, $nom_substance_pft,$sommePoids,$unite,$unite2, $couleur_substance_pft);
            }
        }
        //PF preformé
        $queryPre = "SELECT dcc.id_data_cc,ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PF' AND cfac.preforme =3 AND dcc.id_data_cc=$id_data";
        $result1= mysqli_query($conn, $queryPre);
        if(mysqli_num_rows($result1)> 0){
            $unite =""; $unite2 ="";$unite_kg=''; $unite_kg2='';
            $pft="existe"; $sommePoids_kg_pft = 0;$sommePoids_g_pft=0;
            while($row1 = mysqli_fetch_assoc($result1)){
                $unite_poids_facture = floatval($row1['poids_facture']);
                if($row1['unite_poids_facture']=='g'){
                    $sommePoids_g_pft  += $unite_poids_facture;
                    $unite ="grammes"; $unite2 = "g";
                    
                }else{
                    $sommePoids_kg_pft += $unite_poids_facture;
                    $unite_kg ="kilogrammes"; $unite_kg2 = "kg";
                }
                if(empty($row1['nom_couleur_substance'])){
                    $couleur_substance_pft []='vide';
                }else{
                    $couleur_substance_pft [] = $row1['nom_couleur_substance'];
                }
                $nom_substance_pft [] = $row1['nom_substance'];
                $nom_type=$row1['nom_type_substance'];
            }
            $categorie='Préformées';
            $sommePoids = $sommePoids_g_pft;

            if(($sommePoids_kg_pft!=0)&&($sommePoids!=0)){
                $sommePoids = $sommePoids_kg_pft + $sommePoids / 1000;
                $unite ="KILOGRAMMES";$unite2='kologrammes';
            }else if($sommePoids_kg_pft!=0){
                $sommePoids = $sommePoids_kg_pft;
                $unite ="KILOGRAMMES";$unite2='kologrammes';
            }
            
            if($sommePoids != 0){
                $afficheWord_pft[] = generateAfficheWord($nom_type,$categorie, $nom_substance_pft,$sommePoids,$unite,$unite2, $couleur_substance_pft);
            }
        }
        //PP brute
        $queryR2 = "SELECT dcc.id_data_cc,ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PP' AND cfac.preforme =1 AND dcc.id_data_cc=$id_data";
        $result2= mysqli_query($conn, $queryR2);
        if(mysqli_num_rows($result2)> 0){
            $ppb="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_ppb = 0;$sommePoids_kg_ppb = 0;$sommePoids_g_ppb=0;
            while($row2 = mysqli_fetch_assoc($result2)){
                $unite_poids_facture = floatval($row2['poids_facture']);
                $sommePoids_g_ppb += $unite_poids_facture;
                $unite ="grammes"; $unite2 = "g";
                
                $nom_substance_ppb[] = $row2['nom_substance'];
                if(empty($row2['nom_couleur_substance'])){
                    $couleur_substance_ppb []='vide';
                }else{
                    $couleur_substance_ppb [] = $row2['nom_couleur_substance'];
                }
                $nom_type=$row2['nom_type_substance'];
            }
            $categorie='Brutes';
            $sommePoids = $sommePoids_g_ppb;
            $afficheWord_ppb[] = generateAfficheWord($nom_type,$categorie, $nom_substance_ppb,$sommePoids,$unite,$unite2, $couleur_substance_ppb);
        }
        //PP Taille
        $queryR3 = "SELECT dcc.id_data_cc,ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PP' AND cfac.preforme =2 AND dcc.id_data_cc=$id_data";
        $result3= mysqli_query($conn, $queryR3);
        if(mysqli_num_rows($result3)> 0){
            $ppt="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_ppt = 0;$sommePoids_kg_ppt = 0;$sommePoids_g_ppt=0;
            while($row3 = mysqli_fetch_assoc($result3)){
                $unite_poids_facture = floatval($row3['poids_facture']);
                
                $sommePoids_g_ppt  += $unite_poids_facture;
                $unite ="grammes"; $unite2 = "g";
                
                $nom_substance_ppt [] = $row3['nom_substance'];
                 
                if(empty($row3['nom_couleur_substance'])){
                    $couleur_substance_ppt []='vide';
                }else{
                    $couleur_substance_ppt [] = $row3['nom_couleur_substance'];
                }
                $nom_type=$row3['nom_type_substance'];
            }
            $categorie='Taillées';
            $sommePoids = $sommePoids_g_ppt;
             $afficheWord_ppt[] = generateAfficheWord($nom_type,$categorie, $nom_substance_ppt,$sommePoids,$unite,$unite2, $couleur_substance_ppt);
        }
        $queryPFE = "SELECT dcc.id_data_cc,ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PP' AND cfac.preforme =3 AND dcc.id_data_cc=$id_data";
        $result3= mysqli_query($conn, $queryPFE);
        if(mysqli_num_rows($result3)> 0){
            $ppt="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_ppt = 0;$sommePoids_kg_ppt = 0;$sommePoids_g_ppt=0;
            while($row3 = mysqli_fetch_assoc($result3)){
                $unite_poids_facture = floatval($row3['poids_facture']);
                
                $sommePoids_g_ppt  += $unite_poids_facture;
                $unite ="grammes"; $unite2 = "g";
                
                $nom_substance_ppt [] = $row3['nom_substance'];
                 
                if(empty($row3['nom_couleur_substance'])){
                    $couleur_substance_ppt []='vide';
                }else{
                    $couleur_substance_ppt [] = $row3['nom_couleur_substance'];
                }
                $nom_type=$row3['nom_type_substance'];
            }
            $categorie='Préformées';
            $sommePoids = $sommePoids_g_ppt;
             $afficheWord_ppt[] = generateAfficheWord($nom_type,$categorie, $nom_substance_ppt,$sommePoids,$unite,$unite2, $couleur_substance_ppt);
        }
        //PIM brute
         $queryR4 = "SELECT dcc.id_data_cc, ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PIM' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data";
        $result4= mysqli_query($conn, $queryR4);
        if(mysqli_num_rows($result4)> 0){
            $pimb="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_pimb = 0;$sommePoids_kg_pimb = 0;$sommePoids_g_pimb=0;
            while($row4 = mysqli_fetch_assoc($result4)){
                $unite_poids_facture = floatval($row4['poids_facture']);
                if($row4['unite_poids_facture']=='g'){
                    $unite ="grammes"; $unite2 = "g";
                    $sommePoids_g_pimb  += $unite_poids_facture;
                }else{
                    $sommePoids_kg_pimb += $unite_poids_facture;
                    $unite ="kilogrammes"; $unite2 = "kg";
                }
                $nom_substance_pimb [] = $row4['nom_substance'];
                if(empty($row4['nom_couleur_substance'])){
                    $couleur_substance_pimb []='vide';
                }else{
                    $couleur_substance_pimb [] = $row4['nom_couleur_substance'];
                }
                $nom_type=$row4['nom_type_substance'];
            }
            $categorie='Brutes';
            $sommePoids = $sommePoids_g_pimb + $sommePoids_kg_pimb;
            $afficheWord_pimb[] = generateAfficheWord($nom_type,$categorie, $nom_substance_pimb,$sommePoids,$unite,$unite2, $couleur_substance_pimb);
        }
        //PIM taillé
        $queryR5 = "SELECT dcc.id_data_cc, ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'PIM' AND c.id_categorie =2 AND dcc.id_data_cc=$id_data";
        $result5= mysqli_query($conn, $queryR5);
        if(mysqli_num_rows($result5)> 0){
            $pimt="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_pimt = 0;$sommePoids_kg_pimt = 0;$sommePoids_g_pimt=0;
            while($row5 = mysqli_fetch_assoc($result5)){
                $unite_poids_facture = floatval($row5['poids_facture']);
                if($row5['unite_poids_facture']=='g'){
                    $sommePoids_g_pimt  += $unite_poids_facture;
                    $unite ="grammes"; $unite2 = "g";
                }else{
                    $sommePoids_kg_pimt += $unite_poids_facture;
                    $unite ="kilogrammes"; $unite2 = "kg";
                }
                $nom_substance_pimt [] = $row5['nom_substance'];
                if(empty($row5['nom_couleur_substance'])){
                    $couleur_substance_pimt []='vide';
                }else{
                    $couleur_substance_pimt [] = $row5['nom_couleur_substance'];
                }
                $nom_type=$row5['nom_type_substance'];
            }
            $categorie='Taillées';
            $sommePoids = $sommePoids_g_pimt + $sommePoids_kg_pimt;
            $afficheWord_pimt[] = generateAfficheWord($nom_type,$categorie, $nom_substance_pimt,$sommePoids,$unite,$unite2, $couleur_substance_pimt);
        }
        //MP brute
        $queryR6 = "SELECT dcc.id_data_cc, ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'MP' AND c.id_categorie =1 AND dcc.id_data_cc=$id_data";
        $result6= mysqli_query($conn, $queryR6);
        if(mysqli_num_rows($result6)> 0){
            $mpb="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_mpb = 0;$sommePoids_kg_mpb = 0;$sommePoids_g_mpb=0;
            while($row6 = mysqli_fetch_assoc($result6)){
                $unite_poids_facture = floatval($row6['poids_facture']);
                $unite ="grammes"; $unite2 = "g";
                if($row6['unite_poids_facture']=='g'){
                    $unite ="grammes"; $unite2 = "g";
                    $sommePoids_g_mpb  += $unite_poids_facture;
                }else{
                    $sommePoids_kg_mpb += $unite_poids_facture;
                    $unite ="kilogrammes"; $unite2 = "kg";
                }
                $nom_type=$row6['nom_type_substance'];
                $nom_substance_mpb [] = $row6['nom_substance'];
                if(empty($row6['nom_couleur_substance'])){
                    $couleur_substance_mpb []='vide';
                }else{
                    $couleur_substance_mpb [] = $row6['nom_couleur_substance'];
                }
            }
            $categorie='Brutes';
            $sommePoids = $sommePoids_g_mpb;
            $afficheWord_mpb[] = generateAfficheWord($nom_type,$categorie, $nom_substance_mpb,$sommePoids,$unite,$unite2, $couleur_substance_mpb);
        }
        //MP Taille
        $queryR7 = "SELECT dcc.id_data_cc, ts.nom_type_substance, csub.nom_couleur_substance, s.nom_substance, cfac.poids_facture, cfac.unite_poids_facture FROM data_cc dcc 
        INNER JOIN contenu_facture cfac ON dcc.id_data_cc = cfac.id_data_cc
        LEFT JOIN substance_detaille_substance sds ON cfac.id_detaille_substance = sds.id_detaille_substance
        LEFT JOIN substance s ON s.id_substance = sds.id_substance
        LEFT JOIN type_substance ts ON ts.id_type_substance = s.id_type_substance
        LEFT JOIN categorie c ON c.id_categorie = sds.id_categorie
        LEFT JOIN couleur_substance csub ON csub.id_couleur_substance = sds.id_couleur_substance
        WHERE ts.code_type_substance = 'MP' AND c.id_categorie =2 AND dcc.id_data_cc=$id_data";
        $result7= mysqli_query($conn, $queryR7);
        if(mysqli_num_rows($result7)> 0){
            $mpt="existe";$unite =""; $unite2 ="";
            $sommePoids_ct_mpt = 0;$sommePoids_kg_mpt = 0;$sommePoids_g_mpt=0;
            while($row7 = mysqli_fetch_assoc($result7)){
                $unite_poids_facture = floatval($row7['poids_facture']);
                if($row7['unite_poids_facture']=='g'){
                    $sommePoids_ct_mpt  += $unite_poids_facture;
                }else{
                    $sommePoids_kg_mpt += $unite_poids_facture;
                    $unite ="kilogrammes"; $unite2 = "kg";
                }
                $nom_substance_mpt [] = $row7['nom_substance'];
                if(empty($row7['nom_couleur_substance'])){
                    $couleur_substance_mpt []='vide';
                }else{
                    $couleur_substance_mpt [] = $row7['nom_couleur_substance'];
                }
                $nom_type=$row7['nom_type_substance'];
            }
            $categorie='Taillées';
            $sommePoids = $sommePoids_g_mpt;
            $afficheWord_mpt[] = generateAfficheWord($nom_type,$categorie, $nom_substance_mpt,$sommePoids,$unite,$unite2, $couleur_substance_mpt );
        }

        $amorti="SELECT sds.*, cfac.id_data_cc, cfac.poids_facture, cfac.unite_poids_facture, sub.*, ts.* FROM contenu_facture AS cfac 
        INNER JOIN substance_detaille_substance AS sds ON cfac.id_detaille_substance= sds.id_detaille_substance
        LEFT JOIN substance AS sub ON sds.id_substance = sub.id_substance LEFT JOIN type_substance AS ts ON sub.id_type_substance = 
        ts.id_type_substance WHERE ts.code_type_substance = 'PA'  AND cfac.id_data_cc=$id_data";
        $result= mysqli_query($conn, $amorti);
        $poids_somme=0;
                $categorie = 'Taillé';
                $nom_substance =array();
                $couleur_substance =  array();
                $nom_type="";$unite ="kilogrammes"; $unite2 = "kg";
                if(mysqli_num_rows($result)> 0){
                    $pa="existe";
                    while($row = mysqli_fetch_assoc($result)){
                        $unite_poids_facture = floatval($row['poids_facture']);
                        $nom_substance [] = $row['nom_substance'];
                        $poids_somme += $unite_poids_facture;
                        $nom_type=$row['nom_type_substance'];
                        $couleur_substance [] ='vide';

                    }
                    $afficheWord_pa[] = generateAfficheWord($nom_type,$categorie, $nom_substance,$poids_somme,$unite,$unite2, $couleur_substance );
                }
                

        $amort="SELECT sds.*, cfac.id_data_cc, cfac.poids_facture, cfac.unite_poids_facture, sub.*, ts.* FROM contenu_facture AS cfac 
        INNER JOIN substance_detaille_substance AS sds ON cfac.id_detaille_substance= sds.id_detaille_substance
        LEFT JOIN substance AS sub ON sds.id_substance = sub.id_substance LEFT JOIN type_substance AS ts ON sub.id_type_substance = 
        ts.id_type_substance WHERE ts.code_type_substance = 'FT'  AND cfac.id_data_cc=$id_data";
        $result= mysqli_query($conn, $amort);
        $poids_somme=0;
                $categorie = 'Taillé';
                $nom_substance =array();
                $couleur_substance =  array();
                $nom_type="";$unite ="kilogrammes"; $unite2 = "kg";
                if(mysqli_num_rows($result)> 0){
                    $ft='existe';
                    while($row = mysqli_fetch_assoc($result)){
                        $unite_poids_facture = floatval($row['poids_facture']);
                        $nom_substance [] = $row['nom_substance'];
                        $poids_somme += $unite_poids_facture;
                        $nom_type=$row['nom_type_substance'];
                        $couleur_substance [] ='vide';

                    }
                    $afficheWord_ft[] = generateAfficheWord($nom_type,$categorie, $nom_substance,$poids_somme,$unite,$unite2, $couleur_substance );
                }
                

    
    
}


function generateAfficheWord($nom_type, $categorie, $nom_substance, $sommePoids, $unite, $unite2, $couleur_substance) {
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
        if(($nom_type=="Pierres industrielles et minerais")&&($categorie=="Taillées")){
            $afficheWord = "- " . $nom_type .'(Travaillées): '.$nombreAvantLettre . ' ' . $unite . ' ' . $nombreApresLettre . ' (' . $nombreFormat . ' ' . $unite2 . ') ';
        }else if(($nom_type=="Fossiles Travaillées")||($nom_type=="Pierres Assorties")){
            $afficheWord = "- " . $nom_type .': '.$nombreAvantLettre . ' ' . $unite . ' ' . $nombreApresLettre . ' (' . $nombreFormat . ' ' . $unite2 . ') ';
        }else{
            $afficheWord = "- " . $nom_type .' ('.$categorie.'): '.$nombreAvantLettre . ' ' . $unite . ' ' . $nombreApresLettre . ' (' . $nombreFormat . ' ' . $unite2 . ') ';
        }
        $parts = [];$afficheWord1="";
        // Affichage des résultats
        foreach ($substances_couleurs as $substance => $couleurs) {
            $couleurs_uniques = array_unique($couleurs);
            $voyelles = ['a', 'e', 'i', 'o', 'u', 'y'];
            $premiere_lettre = strtolower(substr($substance, 0, 1));
            $debut_substance = (in_array($premiere_lettre, $voyelles)) ? " d'" : " de ";

            if (empty($couleurs_uniques) || in_array('vide', $couleurs_uniques, true)) {
                $parts[] = $debut_substance . $substance;
            } else {
                $parts[] = $debut_substance . $substance . ' (' . implode(', ', $couleurs_uniques) . ')';
            }
        }
        $count = count($parts);

        if ($count > 1) {
            $afficheWord1 = implode(', ', array_slice($parts, 0, -1)) . ' et ' . end($parts);
        } else {
            $afficheWord1 = $parts[0];
        }
        $afficheWord .=$afficheWord1;
    }

    return $afficheWord.'.';
}


?>