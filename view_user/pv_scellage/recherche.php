<?php
                    //affichage de poids
                    $sommePoids_ct=0;
                    $sommePoids_g=0;
                    $sommePoids_kg=0;
                    //recherche l'id_detaille_substance pour l'unité en carat
                    $queryDetaille_pp = "SELECT datacc.num_facture,cate.*, ts.*, contenu.poids_facture FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc 
                    LEFT JOIN substance_detaille_substance AS sds ON contenu.id_detaille_substance = sds.id_detaille_substance
                    LEFT JOIN substance AS sub ON sds.id_substance= sub.id_substance 
                    LEFT JOIN categorie AS cate ON sds.id_categorie= cate.id_categorie
                    LEFT JOIN type_substance AS ts ON ts.id_type_substance=sub.id_type_substance WHERE contenu.id_data_cc = $id_data_cc 
                    AND contenu.unite_poids_facture='g' AND ts.code_type_substance='PP'";
                    $resultDetaille_pp = mysqli_query($conn, $queryDetaille_pp);
                    $type_substance_pp='';$ecrit_ppt='';$ecrit_ppb='';
                    $unite1='g'; $unite="grammes";
                    if(mysqli_num_rows($resultDetaille_pp)> 0){
                        while($rowDetaille_pp = mysqli_fetch_assoc($resultDetaille_pp)){
                            $num_facture = $rowDetaille_pp['num_facture'];
                            $sommePoids_pp = $rowDetaille_pp['poids_facture'];
                            $type_substance_pp = $rowDetaille_pp['nom_type_substance'];
                            $categorie = $rowDetaille_pp['nom_categorie'];
                            if($categorie=='Taillée'){
                                $ecrit_ppt =ecrire($sommePoids_pp, $type_substance_pp, $unite1, $unite);
                            }else{
                                $ecrit_ppb =ecrire($sommePoids_pp, $type_substance_pp, $unite1, $unite);
                            }
                        }
                    }
                    //recherche l'id_detaille_substance pour l'unité en gramme
                    $queryDetaille_pfg = "SELECT datacc.num_facture,cate.*, ts.*, contenu.poids_facture FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc 
                    LEFT JOIN substance_detaille_substance AS sds ON contenu.id_detaille_substance = sds.id_detaille_substance
                    LEFT JOIN substance AS sub ON sds.id_substance= sub.id_substance 
                    LEFT JOIN categorie AS cate ON sds.id_categorie= cate.id_categorie
                    LEFT JOIN type_substance AS ts ON ts.id_type_substance=sub.id_type_substance WHERE contenu.id_data_cc = $id_data_cc 
                    AND contenu.unite_poids_facture='g' AND ts.code_type_substance='PF'";
                    $resultDetaille_pfg = mysqli_query($conn, $queryDetaille_pfg);
                    $type_substance_pfg='';
                    $unite1='g'; $unite="grammes";
                    $ecrit_pfgt='';$ecrit_pfgb='';
                    if(mysqli_num_rows($resultDetaille_pfg)> 0){
                        while($rowDetaille_pfg = mysqli_fetch_assoc($resultDetaille_pfg)){
                            $type_substance_pfg = $rowDetaille_pfg['nom_type_substance'];
                            $categorie = $rowDetaille_pfg['nom_categorie'];
                            if($categorie =='Taillée'){
                                $sommePoids_pfg = $rowDetaille_pfg['poids_facture'];
                                $ecrit_pfgt = ecrire($sommePoids_pfg, $type_substance_pfg, $unite1, $unite);
                            }else{
                                $sommePoids_pfg = $rowDetaille_pfg['poids_facture'];
                                $ecrit_pfgb = ecrire($sommePoids_pfg, $type_substance_pfg, $unite1, $unite);
                            }
                        }
                    }
                     $queryDetaille_pfkg = "SELECT datacc.num_facture,cate.*, ts.*, contenu.poids_facture FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc 
                    LEFT JOIN substance_detaille_substance AS sds ON contenu.id_detaille_substance = sds.id_detaille_substance
                    LEFT JOIN substance AS sub ON sds.id_substance= sub.id_substance 
                    LEFT JOIN categorie AS cate ON sds.id_categorie= cate.id_categorie
                    LEFT JOIN type_substance AS ts ON ts.id_type_substance=sub.id_type_substance WHERE contenu.id_data_cc = $id_data_cc 
                    AND contenu.unite_poids_facture='kg' AND ts.code_type_substance='PF'";
                    $resultDetaille_pfkg = mysqli_query($conn, $queryDetaille_pfkg);
                    $type_substance_pfkg='';$ecrit_pfkgt='';$ecrit_pfkgb='';
                    $unite1='kg'; $unite="likogrammes";
                     if(mysqli_num_rows($resultDetaille_pfkg)> 0){
                        while($rowDetaille_pfkg = mysqli_fetch_assoc($resultDetaille_pfkg)){
                            $sommePoids_pfkg = $rowDetaille_pfkg['poids_facture'];
                            $type_substance_pfkg = $rowDetaille_pfkg['nom_type_substance'];
                            $categorie = $rowDetaille_pfkg['nom_categorie'];
                            if($categorie=='Taillée'){
                                $ecrit_pfkgt =ecrire($sommePoids_pfkg, $type_substance_pfkg, $unite1, $unite);
                            }else{
                                $ecrit_pfkgb =ecrire($sommePoids_pfkg, $type_substance_pfkg, $unite1, $unite);
                            }
                        }
                    }
                    //recherche l'id_detaille_substance pour l'unité en kilogramme
                    $queryDetaille_mp = "SELECT datacc.num_facture, cate.*, ts.*, contenu.poids_facture FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc 
                    LEFT JOIN substance_detaille_substance AS sds ON contenu.id_detaille_substance = sds.id_detaille_substance
                    LEFT JOIN substance AS sub ON sds.id_substance= sub.id_substance 
                    LEFT JOIN categorie AS cate ON sds.id_categorie= cate.id_categorie
                    LEFT JOIN type_substance AS ts ON ts.id_type_substance=sub.id_type_substance WHERE contenu.id_data_cc = $id_data_cc 
                    AND contenu.unite_poids_facture='g' AND ts.code_type_substance='MP'";
                    $resultDetaille_mp = mysqli_query($conn, $queryDetaille_mp);
                    $ecrit_mpt='';$ecrit_mpb='';
                    $unite1='g'; $unite="grammes";
                    $type_substance_mp='';
                    if(mysqli_num_rows($resultDetaille_mp)> 0){
                        while($rowDetaille_mp = mysqli_fetch_assoc($resultDetaille_mp)){
                            $sommePoids_mp = $rowDetaille_mp['poids_facture'];
                            $type_substance_mp = $rowDetaille_mp['nom_type_substance'];
                            $categorie = $rowDetaille_mp['nom_categorie'];
                            if($categorie=='Taillée'){
                                $ecrit_mpt =ecrire($sommePoids_mp, $type_substance_mp, $unite1, $unite);
                            }else{
                                $ecrit_mpb =ecrire($sommePoids_mp, $type_substance_mp, $unite1, $unite);
                            }
                        }
                    }
                    $queryDetaille_pim = "SELECT datacc.num_facture, cate.*, ts.*, contenu.poids_facture FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc 
                    LEFT JOIN substance_detaille_substance AS sds ON contenu.id_detaille_substance = sds.id_detaille_substance
                    LEFT JOIN substance AS sub ON sds.id_substance= sub.id_substance
                    LEFT JOIN categorie AS cate ON sds.id_categorie= cate.id_categorie
                    LEFT JOIN type_substance AS ts ON ts.id_type_substance=sub.id_type_substance WHERE contenu.id_data_cc = $id_data_cc 
                    AND contenu.unite_poids_facture='kg' AND ts.code_type_substance='PIM'";
                    $resultDetaille_pim = mysqli_query($conn, $queryDetaille_pim);
                    $type_substance_pim='';$ecrit_pimt='';$ecrit_pimb='';
                    $unite1='kg'; $unite="kilogrammes";
                    if(mysqli_num_rows($resultDetaille_pim)> 0){
                        while($rowDetaille_pim = mysqli_fetch_assoc($resultDetaille_pim)){
                            $sommePoids_pim = $rowDetaille_pim['poids_facture'];
                            $type_substance_pim = $rowDetaille_pim['nom_type_substance'];
                            $categorie = $rowDetaille_pim['nom_categorie'];
                            if($categorie=='Taillée'){
                                $ecrit_pimt =ecrire($sommePoids_pim, $type_substance_pim, $unite1, $unite);
                            }else{
                                $ecrit_pimb =ecrire($sommePoids_pim, $type_substance_pim, $unite1, $unite);
                            }
                            
                        }
                    }
                    $queryDetaille_pa = "SELECT datacc.num_facture,cate.*, ts.*, sum(contenu.poids_facture) as sommePoids FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc 
                    LEFT JOIN substance_detaille_substance AS sds ON contenu.id_detaille_substance = sds.id_detaille_substance
                    LEFT JOIN substance AS sub ON sds.id_substance= sub.id_substance 
                    LEFT JOIN categorie AS cate ON sds.id_categorie= cate.id_categorie
                    LEFT JOIN type_substance AS ts ON ts.id_type_substance=sub.id_type_substance WHERE contenu.id_data_cc = $id_data_cc 
                    AND contenu.unite_poids_facture='kg' AND ts.code_type_substance='PA'";
                    $resultDetaille_pa = mysqli_query($conn, $queryDetaille_pa);
                    $type_substance_pa='';$ecrit_pat='';$ecrit_pab='';
                    $rowDetaille_pa = mysqli_fetch_assoc($resultDetaille_pa);
                    if(!empty($rowDetaille_pa['sommePoids'])){
                        $sommePoids_pa = $rowDetaille_pa['sommePoids'];
                        $type_substance_pa = $rowDetaille_pa['nom_type_substance'];
                        $categorie = $rowDetaille_pa['nom_categorie'];
                         if($categorie=='Taillée'){
                            $ecrit_pat =ecrire($sommePoids_pa, $type_substance_pa, $unite1, $unite);
                        }else{
                            $ecrit_pab =ecrire($sommePoids_pa, $type_substance_pa, $unite1, $unite);
                        }
                    }
                    $queryDetaille_ft = "SELECT datacc.num_facture,cate.*, ts.*, sum(contenu.poids_facture) as sommePoids FROM contenu_facture contenu
                    INNER JOIN data_cc datacc ON contenu.id_data_cc=datacc.id_data_cc 
                    LEFT JOIN substance_detaille_substance AS sds ON contenu.id_detaille_substance = sds.id_detaille_substance
                    LEFT JOIN substance AS sub ON sds.id_substance= sub.id_substance 
                    LEFT JOIN categorie AS cate ON sds.id_categorie= cate.id_categorie
                    LEFT JOIN type_substance AS ts ON ts.id_type_substance=sub.id_type_substance WHERE contenu.id_data_cc = $id_data_cc 
                    AND contenu.unite_poids_facture='kg' AND ts.code_type_substance='FT'";
                    $resultDetaille_ft = mysqli_query($conn, $queryDetaille_ft);
                    $type_substance_ft='';$ecrit_ftb='';$ecrit_ftt='';
                    $rowDetaille_ft = mysqli_fetch_assoc($resultDetaille_ft);
                    if(!empty($rowDetaille_ft['sommePoids'])){
                        $sommePoids_ft = $rowDetaille_ft['sommePoids'];
                        $type_substance_ft = $rowDetaille_ft['nom_type_substance'];
                        $categorie = $rowDetaille_ft['nom_categorie'];
                         if($categorie=='Taillée'){
                            $ecrit_ftt =ecrire($sommePoids_ft, $type_substance_ft, $unite1, $unite);
                        }else{
                            $ecrit_ftb =ecrire($sommePoids_ft, $type_substance_ft, $unite1, $unite);
                        }
                    }
                   $ecrit_t = '';

                    $categories = array(
                        $ecrit_ppt, $ecrit_pfgt, $ecrit_pfkgt, $ecrit_mpt, $ecrit_pimt, $ecrit_pat, $ecrit_ftt
                    );

                    foreach ($categories as $category) {
                        if (!empty($category)) {
                            if (empty($ecrit_t)) {
                                $ecrit_t = $category;
                            } else {
                                $ecrit_t .= ', ' . $category;
                            }
                        }
                    }
                    //contenu brute

                    $ecrit_b = '';

                    $categories_b = array(
                        $ecrit_ppb, $ecrit_pfgb, $ecrit_pfkgb, $ecrit_mpb, $ecrit_pimb, $ecrit_pab, $ecrit_ftb
                    );

                    foreach ($categories_b as $category_b) {
                        if (!empty($category_b)) {
                            if (empty($ecrit_b)) {
                                $ecrit_b = $category_b;
                            } else {
                                $ecrit_b .= ', ' . $category_b;
                            }
                        }
                    }

                    