<?php
include_once('../../scripts/db_connect.php');
include_once('../../scripts/connect_db_lp1.php');
require(__DIR__ . '/../../scripts/session.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql="SELECT num_cc, id_societe_expediteur FROM data_cc WHERE id_data_cc = $id_data_cc";
         $result = $conn->query($sql);
         $row = $result->fetch_assoc();
         $id_societe_expediteur = $row['id_societe_expediteur'];
         $num_cc = $row['num_cc'];
        
    }
?>
<div class="modal fade" id="staticBackdrop_ov" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Création de l'ordre de versement</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="./generate_ordre.php" method="post">
                    <?php 
                    
                    $data = array();
                    $sql = "SELECT DISTINCT id_lp1_info FROM contenu_facture WHERE id_data_cc=$id_data_cc AND id_lp1_info IS NOT NULL";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        // Boucler à travers les colonnes et afficher les noms
                        while ($row = $result->fetch_assoc()) {
                            $id_lp1_info = $row['id_lp1_info'];
                            $sql = "SELECT lp.*, rv.*, tr.* FROM lp_info AS lp LEFT JOIN revenu AS rv ON
                            lp.id_revenu = rv.id_revenu LEFT JOIN tresor AS tr ON lp.id_tresor = tr.id_tresor WHERE id_lp=$id_lp1_info";
                            $result = $conn_lp1->query($sql);

                            // Vérifier si des colonnes existent
                            if ($result->num_rows > 0) {
                                // Boucler à travers les colonnes et afficher les noms
                                echo '<h5 id="list-item-5">Information sur LPI</h5>';
                                while ($row_lp = $result->fetch_assoc()) {
                                    echo 'Numéro LP:'.$row_lp['num_LP']. ", assiette:". $row_lp['assiette_rrm'].', sistourne:'.$row_lp['ristourne'].', redevance'.$row_lp['redevance'].'</br>';
                                }
                            } else {
                                echo "Aucune résultat.";
                            }
                        }
                        $lp1='avec';
                    } else {
                        $lp1='aucune';
                    }
                    $options2='';
                    $sql = "SELECT DISTINCT cfac.id_ancien_lp, an.numero_lp
                                FROM contenu_facture AS cfac
                                LEFT JOIN ancien_lp AS an ON cfac.id_ancien_lp = an.id_ancien_lp 
                                WHERE cfac.id_data_cc=$id_data_cc AND cfac.id_ancien_lp IS NOT NULL";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        // Boucler à travers les colonnes et afficher les noms
                        while ($row_ancien = $result->fetch_assoc()) {
                            $options2 .= "<option value='" . $row_ancien['id_ancien_lp'] . "'>" . $row_ancien['numero_lp'] . "</option>";
                        }
                        $ancien_lp='avec';
                    } else {
                        $ancien_lp='aucune';
                    }  
                    if($lp1=='avec') {?>
                    <?php
                        
                        $options = '';
                        $query = "SELECT DISTINCT id_lp1_info FROM contenu_facture WHERE id_lp1_info IS NOT NULL";
                        $result = $conn->query($query);
                        while ($row = $result->fetch_assoc()) {
                            $id_lp_info = $row['id_lp1_info'];
                            $query = "SELECT id_lp, num_LP FROM lp_info WHERE id_lp=$id_lp_info";
                            $result = $conn_lp1->query($query);
                            $row_lp = $result->fetch_assoc();
                            $options .= "<option value='" . $row_lp['id_lp'] . "'>" . $row_lp['num_LP'] . "</option>";
                        }
                        ?>
                    <div id="dynamic-fields">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_lp1_info">Numero LP:</label>
                                <select name="id_lp1_info[]" id="id_lp1_info" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <?= $options; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="assiette">Assiette en ariary:</label>
                                <input type="number" class="form-control" name="assiette[]" required>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="region">Région bénéficiaire:</label>
                                    <input type="text" class="form-control" name="region[]" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="commune">Commune bénéficiaire:</label>
                                    <input type="text" class="form-control" name="commune[]" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Boutons Ajouter / Supprimer -->
                    <button type="button" id="add-field">Ajouter</button>
                    <button type="button" id="remove-field">Supprimer</button>

                    <?php } 
                    if(($lp1 == 'avec')&&($ancien_lp=='avec')){
                        echo '<hr>';
                    }
                    if($ancien_lp=='avec') { ?>
                    <h5 id="list-item-5">Information sur LP</h5>
                    <div id="dynamic-fields2">
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_ancien_lp">Numero LP:</label>
                                <select name="id_ancien_lp[]" id="id_ancien_lp" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <?= $options2; ?>
                                </select>
                            </div>
                            <div class="col">
                                <label for="assiette_ancien">Assiette en ariary:</label>
                                <input type="text" class="form-control" name="assiette_ancien[]" required>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="region_ancien">Région bénéficiaire:</label>
                                    <input type="text" class="form-control" name="region_ancien[]" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="commune_ancien">Commune bénéficiaire:</label>
                                    <input type="text" class="form-control" name="commune_ancien[]" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons Ajouter / Supprimer -->
                    <button type="button" id="add-field2">Ajouter</button>
                    <button type="button" id="remove-field2">Supprimer</button>
                    <?php } ?>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="droit_conformite">Droit de conformité en ariary:</label>
                                <input type="number" class="form-control" name="droit_conformite" id="droit_conformite">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label for="nom_mois">Mois:</label>
                                <select name="nom_mois" id="nom_mois" class="form-select" required>
                                    <option value="">Choisir...</option>
                                    <option value="Janvier">Janvier</option>
                                    <option value="Février">Février</option>
                                    <option value="Mars">Mars</option>
                                    <option value="Avril">Avril</option>
                                    <option value="Mai">Mai</option>
                                    <option value="Juin">Juin</option>
                                    <option value="Juillet">Juillet</option>
                                    <option value="Août">Août</option>
                                    <option value="Septembre">Septembre</option>
                                    <option value="Octobre">Octobre</option>
                                    <option value="Novembre">Novembre</option>
                                    <option value="Décembre">Décembre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="numero_ordre">Numéro d'ordre</label>
                                <input type="text" class="form-control" name="numero_ordre">
                            </div>
                        </div>
                        <?php if(($lp1=='avec')||($ancien_lp=='avec')) {?>
                        <div class="col">
                            <div class="mb-3">
                                <label for="numero_ordre">Numéro de compte</label>
                                <input type="number" class="form-control" name="numero_compte" required>
                            </div>
                        </div>
                        <?php }?>
                    </div>
                    <input type="hidden" class="form-control" name="id_societe_expediteur"
                        value="<?php echo $id_societe_expediteur ?>">
                    <input type="hidden" class="form-control" name="confirme_lp1" id="confirme_lp1"
                        value="<?php echo $lp1 ?>">
                    <input type="hidden" class="form-control" name="confirme_ancien" id="confirme_ancien"
                        value="<?php echo $ancien_lp ?>">
                    <input type="hidden" class="form-control" name="num_cc" id="num_cc" value="<?php echo $num_cc ?>">
                    <input type="hidden" class="form-control" name="id_data_cc" id="id_data_cc"
                        value="<?php echo $id_data_cc ?>">
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
    var selectOptions = `<?= $options; ?>`;

    $('#add-field').click(function() {
        var newRow = $(`
        <div class="row mt-3">
            <div class="col">
                <label for="id_lp1_info" class="fw-bold">Numero LP</label>
                <select class="form-select" name="id_lp1_info[]" required>
                    <option value="">Sélectionner...</option>
                    ${selectOptions}
                </select>
            </div>
            <div class="col">
                <label for="assiette" class="fw-bold">Assiette en ariary</label>
                <input type="number" class="form-control" step="0.01" name="assiette[]" placeholder="Assiette" required style="font-size:90%">
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="region">Région bénéficiaire:</label>
                    <input type="text" class="form-control" name="region[]" required>
                </div>
            </div>
            <div class="col">
                <div class="mb-3">
                    <label for="commune">Commune bénéficiaire:</label>
                    <input type="text" class="form-control" name="commune[]" required>
                </div>
            </div>
        </div>
    `);
        $('#dynamic-fields').append(newRow);
    });
    $('#remove-field').click(function() {
        $('#dynamic-fields .row').last().remove();
    });

    var selectOptions2 = `<?= $options2; ?>`;

    // Ajouter un nouveau groupe de champs
    $('#add-field2').click(function() {
        var newRow = $(`
            <div class="row mt-3">
                <div class="col">
                    <label for="id_ancien_lp" class="fw-bold">Numero LP<span class="required">*</span></label>
                    <select class="form-select" name="id_ancien_lp[]" required>
                        <option value="">Sélectionner...</option>
                        ${selectOptions2}
                    </select>
                </div>
                <div class="col">
                    <label for="assiette_ancien" class="fw-bold">Assiette en ariary<span class="required">*</span></label>
                    <input type="number" class="form-control" step="0.01" name="assiette_ancien[]" placeholder="Assiette" required style="font-size:90%">
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="region_ancien">Région bénéficiaire:</label>
                        <input type="text" class="form-control" name="region_ancien[]" required>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="commune_ancien">Commune bénéficiaire:</label>
                        <input type="text" class="form-control" name="commune_ancien[]" required>
                    </div>
                </div>
            </div>
        `);
        $('#dynamic-fields2').append(newRow);
    });

    // Supprimer le dernier groupe de champs
    $('#remove-field2').click(function() {
        $('#dynamic-fields2 .row').last().remove();
    });
});
</script>