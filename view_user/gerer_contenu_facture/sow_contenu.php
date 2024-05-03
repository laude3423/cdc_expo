<?php
    require_once('../../scripts/db_connect.php');
if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT cfa.*, g.*,s.*,ts.*,sds.prix_substance, sds.unite_prix_substance, tr.*,dim.*,di.*,cate.*,cou.*,dg.* ,dcc.* FROM contenu_facture cfa
        INNER JOIN substance_detaille_substance AS sds ON cfa.id_detaille_substance=sds.id_detaille_substance
        LEFT JOIN data_cc AS dcc ON dcc.id_data_cc=cfa.id_data_cc
        LEFT JOIN categorie AS cate ON cate.id_categorie= sds.id_categorie
        LEFT JOIN substance AS s ON s.id_substance= sds.id_substance
        LEFT JOIN type_substance AS ts ON ts.id_type_substance=s.id_type_substance
        LEFT JOIN couleur_substance AS cou ON cou.id_couleur_substance=sds.id_couleur_substance
        LEFT JOIN granulo AS g ON sds.id_granulo=g.id_granulo
        LEFT JOIN dimension_diametre AS dim ON dim.id_dimension_diametre=sds.id_dimension_diametre
        LEFT JOIN transparence AS tr ON sds.id_transparence=tr.id_transparence
        LEFT JOIN degre_couleur AS dg ON sds.id_degre_couleur=dg.id_degre_couleur
        LEFT JOIN forme_substance AS f ON f.id_forme_substance=sds.id_forme_substance
        LEFT JOIN durete AS di ON di.id_durete=sds.id_durete WHERE cfa.id_contenu_facture = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $num_facture = $row['num_facture'] ?? "";
        $date_facture = $row['date_facture']??"";
        $nom_granulo=$row['nom_granulo'] ?? "";
        $nom_substance=$row['nom_substance'] ?? "";
        $nom_type_substance=$row['nom_type_substance'] ?? "";
        $nom_transparence=$row['nom_transparence'] ?? "";
        $nom_dimension_diametre=$row['nom_dimension_diametre'] ?? "";
        $nom_degre_couleur=$row['nom_degre_couleur'] ?? "";
        $nom_forme_substance=$row['nom_forme_substance'] ?? "";
        $nom_couleur_substance=$row['nom_couleur_substance'] ?? "";
        $nom_durete=$row['nom_durete'] ?? "";
        $prix_unitaire=$row['prix_unitaire_facture'];
        $poids_facture=$row['poids_facture'] ?? "";
        $prix_normale=$row['prix_substance'] ?? "";
        $unite_substance=$row['unite_substance'] ?? "";
        $num_lp1=$row['id_lp1_info'];
        $nom_categorie=$row['nom_categorie'];
        $code = $row['code_type_substance'];
        $qte_finale = $row['quantite_lp1_actuel_lp1_suivis'];
        if(($code=="PIM")&&($nom_categorie=="Taillée")){
            $nom_categorie="Travaillée";
        }

    }

?>
<?php    
    require '../../scripts/connect_db_lp1.php';
    $query = "SELECT lp.*, pd.* FROM lp_info AS lp INNER JOIN produits AS pd ON lp.id_produit= pd.id_produit WHERE id_lp=$num_lp1";
    $result1 = $conn_lp1->query($query);
    $row = $result1->fetch_assoc();
    $num_lp1_suivis=$row['num_LP'];
    $date_lp1=$row['date_modification'];
    $pj_lp1=$row['link_lp'];
    $unite_lp1=$row['unite'];
    $quantite_init=$row['quantite_en_chiffre'];
 ?>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Détails d'un contenu de facture</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p><strong>Numéro de facture:</strong><?php echo "\t".$num_facture; ?></p>
                <p><strong>Date de création de facture:</strong><?php echo $date_facture; ?></p>
                <hr>
                <p><strong>Type de substance:</strong><?php echo "\t".$nom_type_substance; ?></p>
                <p><strong>Nom de substance:</strong><?php echo "\t".$nom_substance; ?></p>
                <?php
                            if($nom_categorie){
                                echo '<p><strong>Nom de catégorie:</strong> ' . $nom_categorie . '</p>';
                            }
                            if($nom_couleur_substance){
                                echo '<p><strong>Nom de couleur de la substance:</strong> ' . $nom_couleur_substance . '</p>';
                            }
                            if($nom_degre_couleur){
                                echo '<p><strong>Nom de degré de couleur:</strong> ' . $nom_degre_couleur. '</p>';
                            }
                            if($nom_transparence){
                                echo '<p><strong>Nom de transparence:</strong> ' . $nom_transparence . '</p>';
                            }
                            if($nom_durete){
                                echo '<p><strong>Nom de dureté:</strong> ' . $nom_durete . '</p>';
                            }
                            if($nom_dimension_diametre){
                                echo '<p><strong>Nom de dimension ou diamètre:</strong> ' . $nom_dimension_diametre . '</p>';
                            }
                            if($nom_forme_substance){
                                echo '<p><strong>Nom de granulomètrie:</strong> ' . $nom_forme_substance . '</p>';
                            }
                            if($nom_granulo){
                                echo '<p><strong>Nom de granulomètrie:</strong> ' . $nom_granulo . '</p>';
                            }
                        ?>

                </p>

                <p><strong>Granulomètrie:</strong><?php echo "\t".$nom_granulo ?></p>
                <p><strong>Poids:</strong><?php echo "\t".$poids_facture ?></p>
                <p><strong>Prix entré par
                        l'utilisateur:</strong><?php echo "\t".$prix_unitaire ?>
                </p>
                <p><strong>Prix normale:</strong> <?php echo "\t".$prix_normale ?></p>
                <p><strong>Prix totale:</strong> <?php echo $prix_unitaire * $poids_facture ?></p>
                <hr>
                <p><strong>Numéro LP1:</strong> <?php echo $num_lp1_suivis ?></p>
                <p><strong>Date de création LP1:</strong> <?php echo $date_lp1 ?></p>
                <p><strong>Quantité LP1 initiale :</strong> <?php echo $quantite_init. ' '.$unite_lp1; ?></p>
                <p><strong>Quantité LP1 actuel :</strong> <?php echo $qte_finale; ?></p>
                <p><strong>Scan LP1:</strong> <a
                        href="https://lp1.minesmada.org/<?php echo htmlspecialchars($row['link_lp'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($row['num_LP'], ENT_QUOTES, 'UTF-8'); ?>.pdf</a>
                </p>

                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {


});
</script>