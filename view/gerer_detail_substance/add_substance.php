<?php 
include "../db_connect.php";
?>
<?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
     $substance = $_POST['substance'];
    //$id_substance = $_POST['id_substance'];
    $forme = isset($_POST["forme"]) ? $_POST["forme"] : array(); 
    $durete = isset($_POST["durete"]) ? $_POST["durete"] : array(); 
    $dimension = isset($_POST["dimension"]) ? $_POST["dimension"] : array();
    $granulo = isset($_POST["granulo"]) ? $_POST["granulo"] : array();
    $transparence = isset($_POST["transparence"]) ? $_POST["transparence"] : array();
    $degre = isset($_POST["degre"]) ? $_POST["degre"] : array();
    $durete = isset($_POST["durete"]) ? $_POST["durete"] : array();
    $categorie = isset($_POST["categorie"]) ? $_POST["categorie"] : array();
    $dimension = isset($_POST["dimension"]) ? $_POST["dimension"] : array();
    $couleur = isset($_POST["couleur"]) ? $_POST["couleur"] : array(); 
    $unite = $_POST['unite'];
    $id_detail = $_POST['id'];
    $id_type_substance = $_POST['type_substance'];

    //Préparer la requête SQL
    $id_substance="";
    $sqlR="SELECT * FROM substance WHERE nom_substance = '$substance'";
    $resultR = mysqli_query($conn, $sqlR);
    $rowR = mysqli_fetch_assoc($resultR);
     if(empty($rowR['nom_substance'])){
        $subInsertQuery = "INSERT INTO `substance`( `nom_substance`, `id_type_substance`) VALUES ('$substance','$id_type_substance')";
        $conn->query($subInsertQuery);
        $id_substance = $conn->insert_id; 
     }else{
        $id_substance = $rowR['id_substance'];
     }

    // Parcourir les tableaux pour insérer les données non vides
    if(count($categorie) > 0){
        for ($i = 0; $i < count($categorie); $i++) {
            if(count($granulo) > 0){
                for ($j = 0; $j < count($granulo); $j++) {
                    if(count($couleur) > 0){
                        for ($k = 0; $k < count($couleur); $k++) {
                            if(count($transparence) > 0){
                                for ($l = 0; $l < count($transparence); $l++) {
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                            $stmt = $conn->prepare($query);
                                                            $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//forme
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                     $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//forme dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`,  `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $durete[$o], $categorie[$i],  $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//forme durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//forme durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_categorie`,`id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`,  `id_forme_substance`, `id_durete`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_forme_substance`,`id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{ //degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_forme_substance`,`id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{// tsisy forme degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`,  `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//tsis fprme degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`,  `id_durete`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//tsis forme degre  durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//tsis forme degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                                    
                                }
                            }else{//tsis transparence
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{// transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//transparence durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//transparence durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//transparence forme
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//forme transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//durete forme transparence
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                               $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $degre[$m], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//durete forme transparence dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $degre[$m], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//transparence degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//degre transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $forme[$n], $durete[$o], $categorie[$i],  $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//transparence degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//transparence degre durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//transparence forme degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//4
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//transparence durete forme degre
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//transparence dimension durete forme degre
                                                           $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $granulo[$j], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                            }
                            
                        }
                    }else{// tsis couleur
                            if(count($transparence) > 0){
                                for ($l = 0; $l < count($transparence); $l++) {
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//couleur dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//couleur durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//couleur durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                            $stmt = $conn->prepare($query);
                                                            $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//couleur forme
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                     $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $durete[$o], $categorie[$i], $dimension[$p],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//couleur forme dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`,  `id_durete`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $durete[$o], $categorie[$i],    $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//couleur forme durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $categorie[$i], $dimension[$p],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//couleur forme durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $transparence[$l],$degre[$m], $categorie[$i],   $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//couleur degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//couleur degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`,  `id_forme_substance`, `id_durete`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//couleur degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_forme_substance`,`id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{ //couleur degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_forme_substance`,`id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{// tsisy couleur forme degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`,  `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//tsis couleur forme degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`,  `id_durete`, `id_categorie`,  `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//tsis couleur forme degre  durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//tsis couleur forme degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_categorie`,  `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                                    
                                }
                            }else{//tsis couleur transparence
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//couleur transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//couleur transparence durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//couleur transparence durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $degre[$m], $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{// couleur transparence forme
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//couleur forme transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//couleur durete forme transparence
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                               $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $degre[$m], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//couleur durete forme transparence dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_degre_couleur`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $granulo[$j], $degre[$m], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//couleur transparence degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $granulo[$j], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p],   $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//couleur degre transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $forme[$n], $durete[$o], $categorie[$i],  $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//couleur transparence degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $forme[$n], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//couleur transparence degre durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_forme_substance`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $granulo[$j], $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//couleur transparence forme degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//5
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $granulo[$j], $degre[$m], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//couleur transparence durete forme degre
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $granulo[$j], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//tsis  couleur transparence dimension durete forme degre
                                                           $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_granulo`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiis", $id_substance, $granulo[$j], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                            }
                    }
            }
            }else{// tsis granulo
                    if(count($couleur) > 0){
                        for ($k = 0; $k < count($couleur); $k++) {
                            if(count($transparence) > 0){
                                for ($l = 0; $l < count($transparence); $l++) {
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiiis", $id_substance, $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//granulo dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?,?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//granulo durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                            $stmt = $conn->prepare($query);
                                                            $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//granulo forme
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                     $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $transparence[$l],$degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{// granulo forme dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`,  `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l],$degre[$m], $durete[$o], $categorie[$i],  $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{// granulo forme durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l],$degre[$m], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{// granulo forme durete dimension
                                                            echo $id_substance; echo "\n"; echo $transparence[$l];echo "\n"; echo $degre[$m];echo "\n"; echo $categorie[$i];echo "\n"; echo $couleur[$k]; echo "\n"; echo $unite;
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l],$degre[$m], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//granulo degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//granulo degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`,  `id_forme_substance`, `id_durete`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_forme_substance`,`id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{ //granulo degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_forme_substance`,`id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l], $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{// tsisy granulo forme degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`,  `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//tsis granulo forme degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`,  `id_durete`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//tsis granulo forme degre  durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//tsis granulo forme degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $transparence[$l], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                                    
                                }
                            }else{//tsis granulo transparence
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//granulo transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{// granulo transparence durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $degre[$m], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//granulo transparence durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//granulo transparence forme
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//granulo forme transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $degre[$m], $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo durete forme transparence
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                               $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $degre[$m], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//granulo durete forme transparence dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $degre[$m], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//granulo transparence degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k],   $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//granulo degre transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $forme[$n], $durete[$o], $categorie[$i],  $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo transparence degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//granulo transparence degre durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $forme[$n], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//granulo transparence forme degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//5
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_durete`, `id_categorie`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $durete[$o], $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo transparence durete forme degre
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $categorie[$i], $dimension[$p], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//granulo transparence dimension durete forme degre
                                                           $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_categorie`,  `id_couleur_substance`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiis", $id_substance, $categorie[$i], $couleur[$k], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                            }
                            
                        }
                    }else{// tsis granulo couleur
                            if(count($transparence) > 0){
                                for ($l = 0; $l < count($transparence); $l++) {
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiiis", $id_substance, $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p],$unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//granulo couleur dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l],$degre[$m], $forme[$n], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{// granulo couleur durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{// granulo couleur durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                            $stmt = $conn->prepare($query);
                                                            $stmt->bind_param("iiiiis", $id_substance, $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//forme granulo couleur 
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                     $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l],$degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//forme granulo couleur dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`,  `id_durete`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l],$degre[$m], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//forme granulo couleur durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l],$degre[$m], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//forme granulo couleur durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $transparence[$l],$degre[$m], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//granulo couleur degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//granulo couleur degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`,  `id_forme_substance`, `id_durete`, `id_categorie`,  `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo couleur degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_forme_substance`,`id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{ //granulo couleur degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_forme_substance`,`id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $transparence[$l], $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{// tsisy forme granulo couleur degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`,  `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//tsis granulo couleur forme degre dimension
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`,  `id_durete`, `id_categorie`,  `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance,  $transparence[$l], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//tsis granulo couleur forme degre  durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                 $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance,  $transparence[$l], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//tsis granulo couleur forme degre durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiis", $id_substance,  $transparence[$l], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                                    
                                }
                            }else{//tsis granulo couleur transparence
                                    if(count($degre) > 0){
                                        for ($m = 0; $m < count($degre); $m++) {
                                            if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiiis", $id_substance, $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//granulo couleur transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo couleur transparence durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $degre[$m], $forme[$n], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//granulo couleur transparence durete dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_transparence`, `id_degre_couleur`, `id_forme_substance`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $degre[$m], $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//granulo couleur transparence forme
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                    $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `id_couleur_substance`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $degre[$m], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            }else{//granulo couleur forme transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $degre[$m], $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo couleur durete forme transparence
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                               $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $degre[$m], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{// granulo couleur durete forme transparence dimension
                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_degre_couleur`, `id_categorie`, `unite_prix_substance`) VALUES ( ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiis", $id_substance, $degre[$m], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                        }
                                    }else{//granulo couleur transparence degre
                                        if(count($forme) > 0){
                                                for ($n = 0; $n < count($forme); $n++) {
                                                    if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiiis", $id_substance, $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//granulo couleur degre transparence dimension
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $forme[$n], $durete[$o], $categorie[$i],  $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo couleur transparence degre durete
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $forme[$n], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{// granulo couleur transparence degre durete dimension
                                                            $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_forme_substance`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiis", $id_substance, $forme[$n], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                                }
                                            }else{//granulo couleur transparence forme degre
                                                if(count($durete) > 0){
                                                        for ($o = 0; $o < count($durete); $o++) {
                                                            if(count($dimension) > 0){
                                                                for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_durete`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiiis", $id_substance, $durete[$o], $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                                }
                                                            }else{//6
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_durete`, `id_categorie`, `unite_prix_substance`) VALUES (?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiis", $id_substance, $durete[$o], $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                            }
                                                            
                                                        }
                                                    }else{//granulo couleur transparence durete forme degre
                                                        if(count($dimension) > 0){
                                                            for ($p = 0; $p < count($dimension); $p++){
                                                                $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_categorie`, `id_dimension_diametre`, `unite_prix_substance`) VALUES (?, ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iiis", $id_substance, $categorie[$i], $dimension[$p], $unite);
                                                                    $stmt->execute();
                                                            }
                                                        }else{//tsis granulo couleur transparence dimension durete forme degre
                                                           $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_categorie`,  `unite_prix_substance`) VALUES ( ?, ?, ?)";
                                                                    $stmt = $conn->prepare($query);
                                                                    $stmt->bind_param("iis", $id_substance, $categorie[$i], $unite);
                                                                    $stmt->execute();
                                                        }
                                                    }
                                            }
                                    }
                            }
                    }
                  
            }
        }
     }
     //else{//categorie
    //     if(count($granulo) > 0){
    //         for ($j = 0; $j < count($granulo); $j++) {
    //                 if(count($couleur) > 0){
    //                     for ($k = 0; $k < count($couleur); $k++) {
    //                         if(count($transparence) > 0){
    //                             for ($l = 0; $l < count($transparence); $l++) {
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i],  $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
                                    
    //                             }
    //                         }else{//transparence
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i],  $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
    //                         }
                            
    //                     }
    //                 }else{//couleur
                        
    //                         if(count($transparence) > 0){
    //                             for ($l = 0; $l < count($transparence); $l++) {
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i],  $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
                                    
    //                             }
    //                         }else{//transparence
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i],  $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
    //                         }
    //                 }
                    
    //         }
    //     }else{//granulo
    //                 if(count($couleur) > 0){
    //                     for ($k = 0; $k < count($couleur); $k++) {
    //                         if(count($transparence) > 0){
    //                             for ($l = 0; $l < count($transparence); $l++) {
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i],  $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
                                    
    //                             }
    //                         }else{//transparence
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i],  $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
    //                         }
                            
    //                     }
    //                 }else{//couleur
    //                         if(count($transparence) > 0){
    //                             for ($l = 0; $l < count($transparence); $l++) {
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                               $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $forme[$n], $categorie[$i],  $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
                                    
    //                             }
    //                         }else{//transparence
    //                                 if(count($degre) > 0){
    //                                     for ($m = 0; $m < count($degre); $m++) {
    //                                         if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                                 $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                         }else{//dimension
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $durete[$o], $categorie[$i], $couleur[$k], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//direte
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $stmt->bind_param("iiiiiiiiis", $id_substance, $granulo[$j], $transparence[$l], $degre[$m], $forme[$n], $categorie[$i],$dimension[$p], $couleur[$k], $unite);
    //                                                                 $stmt->execute(); 
    //                                                         }
    //                                                     }else{//dimension
    //                                                          $query = "INSERT INTO `substance_detaille_substance` (`id_substance`,`id_durete`, `id_forme_substance`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance,$durete[$o], $forme[$n], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                     }
    //                                 }else{//degre
    //                                     if(count($forme) > 0){
    //                                             for ($n = 0; $n < count($forme); $n++) {
    //                                                 if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`,`id_forme_substance`,`id_durete`, `id_dimension`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance,$forme[$n], $durete[$o], $dimension[$p], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`,`id_durete`, `id_forme_substance`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance,$durete[$o], $forme[$n], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`,`id_forme_sbstance`, `id_dimension`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance,$forme[$n], $dimension[$p], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $query = "INSERT INTO `substance_detaille_substance` (`id_substance`,`id_forme_substance`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance, $forme[$n], $unite);
    //                                                                 $stmt->execute();
    //                                                     }
    //                                                 }
    //                                             }
    //                                         }else{//forme
    //                                             if(count($durete) > 0){
    //                                                     for ($o = 0; $o < count($durete); $o++) {
    //                                                         if(count($dimension) > 0){
    //                                                             for ($p = 0; $p < count($dimension); $p++){
    //                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`,`id_durete`, `id_dimension`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance,$durete[$o], $dimension[$p], $unite);
    //                                                                 $stmt->execute();
    //                                                             }
    //                                                         }else{//dimension
    //                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_durete`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance, $durete[$o], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
                                                            
    //                                                     }
    //                                                 }else{//durete
    //                                                     if(count($dimension) > 0){
    //                                                         for ($p = 0; $p < count($dimension); $p++){
    //                                                             $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `id_dimension`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                             $stmt = $conn->prepare($query);
    //                                                             $stmt->bind_param("iiiiiiiiis",$id_substance, $dimension[$p], $unite);
    //                                                                 $stmt->execute();
    //                                                         }
    //                                                     }else{//dimension
    //                                                         $query = "INSERT INTO `substance_detaille_substance` (`id_substance`, `unite_prix_substance`) VALUES (?, ?)";
    //                                                         $stmt = $conn->prepare($query);
    //                                                         $stmt->bind_param("iiiiiiiiis",$id_substance, $unite);
    //                                                             $stmt->execute();
    //                                                     }
    //                                                 }
    //                                         }
    //                                 }
    //                         }
    //                 }
    //         }
    //     }
            
     

    // Fermer la requête
    $stmt->close();
    // Fermer la connexion à la base de données
    $conn->close();
}
?>

?>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- TomSelect JS -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">

<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<!-- Votre script personnalisé -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!--Font awesome-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" type="text/css" href="style.css">
<!--Bootstrap JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
</script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
// Attend que le document soit prêt
$(document).ready(function() {
    // Affiche le modal automatiquement
    $('#staticBackdrop').modal('show');
});
</script>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class=" modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle substance</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col">
                            <label for="type_substance" name="type_substance" class="col-form-label">Type de la
                                substance: <span style="color:rgb(247, 62, 6)">*</span></label>
                            <select id="type_substance" name="type_substance" placeholder="Choisir ..."
                                autocomplete="off" required>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT * FROM type_substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_type_substance'] . "'>" . $rowSub['nom_type_substance'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="substance" name="substance" class="col-form-label">Nom de la
                                substance:</label>
                            <input type="text" class="form-control" name="substance" id="substance"
                                placeholder="Nom de la substance" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="couleur" name="couleur" class="col-form-label">Couleur de la
                                substance:</label>
                            <select id="couleur" name="couleur[]" placeholder="Choisir ..." autocomplete="off" multiple
                                required>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT * FROM couleur_substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_couleur_substance'] . "'>" . $rowSub['nom_couleur_substance'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="categorie" name="categorie" class="col-form-label">Catégorie:</label>
                            <select id="categorie" name="categorie[]" placeholder="Choisir ..." autocomplete="off"
                                multiple>
                                <option value="">Choisir</option>
                                <?php    
                                $query = "SELECT id_categorie, nom_categorie FROM categorie";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_categorie'] . "'>" . $rowSub['nom_categorie'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="granulo" name="granulo" class="col-form-label">Granulomètrie:</label>
                            <select id="granulo" name="granulo[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_granulo, nom_granulo FROM granulo";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_granulo'] . "'>" . $rowSub['nom_granulo'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="transparence" name="transparence" class="col-form-label">Transparence:</label>
                            <select id="transparence" name="transparence[]" placeholder="Choisir ..." autocomplete="off"
                                multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_transparence, nom_transparence FROM transparence";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_transparence'] . "'>" . $rowSub['nom_transparence'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="degre" name="degre" class="col-form-label">Degré de la
                                couleur:</label>
                            <select id="degre" name="degre[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_degre_couleur, nom_degre_couleur FROM degre_couleur";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_degre_couleur'] . "'>" . $rowSub['nom_degre_couleur'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="durete" name="durete" class="col-form-label">Dureté de la substance:</label>
                            <select id="durete" name="durete[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_durete, nom_durete FROM durete";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_durete'] . "'>" . $rowSub['nom_durete'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="dimension" name="dimension" class="col-form-label">Dimension ou
                                Diamètre:</label>
                            <select id="dimension" name="dimension[]" placeholder="Choisir ..." autocomplete="off"
                                multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                $query = "SELECT id_dimension_diametre, nom_dimension_diametre FROM dimension_diametre";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_dimension_diametre'] . "'>" . $rowSub['nom_dimension_diametre'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="forme" name="forme" class="col-form-label">Forme de la substance:</label>
                            <select id="forme" name="forme[]" placeholder="Choisir ..." autocomplete="off" multiple>
                                <option value="">Choisir...</option>
                                <?php    
                                $query = "SELECT id_forme_substance, nom_forme_substance FROM forme_substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_forme_substance'] . "'>" . $rowSub['nom_forme_substance'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col">
                            <label for="unite" name="unite" class="col-form-label">Unité de la substance:</label>
                            <select class="form-select" id="unite" name="unite" aria-label="Default select example"
                                required>
                                <option selected>Choisir ...</option>
                                <option value="ct">US $ / Carat</option>
                                <option value="g">US $ / Gramme</option>
                                <option value="kg">US $ / Kilogramme</option>
                            </select>
                        </div>
                        <input type="hidden" id="id" name="id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    selectTom();
});

function selectTom() {
    // Initialisez TomSelect pour chaque élément select


    var selectOptions = {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    };
    new TomSelect("#type_substance", selectOptions);
    new TomSelect("#granulo", selectOptions);
    new TomSelect("#transparence", selectOptions);
    new TomSelect("#degre", selectOptions);
    new TomSelect("#forme", selectOptions);
    new TomSelect("#durete", selectOptions);
    new TomSelect("#categorie", selectOptions);
    new TomSelect("#dimension", selectOptions);
    new TomSelect("#couleur", selectOptions);

};
</script>
<script>
new TomSelect('select[multiple]', {
    plugins: {
        remove_button: {
            title: 'Supprimer'
        }
    }
})
</script>