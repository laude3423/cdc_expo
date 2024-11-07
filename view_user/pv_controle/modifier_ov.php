<?php
include_once('../../scripts/db_connect.php');
include_once('../../scripts/connect_db_lp1.php');
include_once('../../scripts/connect_db_lp1.php');
require(__DIR__ . '/../../scripts/session.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql="SELECT num_cc, id_societe_expediteur, numero_ordre,droit_conformite, mois_payement, numero_compte FROM data_cc WHERE id_data_cc = $id_data_cc";
         $result = $conn->query($sql);
         $row = $result->fetch_assoc();
         $id_societe_expediteur = $row['id_societe_expediteur'];
         $num_cc = $row['num_cc'];
         $numero_ordre = $row['numero_ordre'];
         $numero_compte = $row['numero_compte'];
         $droit_conformite = intval($row['droit_conformite']);
         $mois_payement = $row['mois_payement'];
         $confirme_modification="oui";
        
    }
?>
<div class="modal fade" id="staticBackdrop_ov2" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier un ordre de versement</h1>
                <button type="button" class="btn-close" onclick="closeModal()"></button>
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
                        <?php $sql = "SELECT * FROM revenu WHERE type_lp='nouveau'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {?>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_lp1_info">Numero LP:</label>
                                <select name="id_lp1_info[]" id="id_lp1_info" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <?= $options; // Vos options dynamiques ?>
                                    <option value="<?= $row['id_lp']; ?>" selected><?= $row['numero_lp']; ?></option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="assiette">Assiette en ariary:</label>
                                <input type="number" class="form-control" name="assiette[]"
                                    value="<?= $row['assiette']; ?>" required>
                            </div>
                            <div class="col">
                                <label for="region">Région bénéficiaire:</label>
                                <input type="text" class="form-control" name="region[]"
                                    value="<?= $row['nom_region']; ?>" required>
                            </div>
                            <div class="col">
                                <label for="commune">Commune bénéficiaire:</label>
                                <input type="text" class="form-control" name="commune[]"
                                    value="<?= $row['nom_commune']; ?>" required>
                            </div>
                            <div class="col">
                                <label for="action">Suppression:</label><br>
                                <button type="button" class="btn btn-danger btn-delete"
                                    data-id="<?= $row['id_lp']; ?>">Supprimer</button>
                            </div>
                        </div>
                        <?php 
                        }
                        } else {
                                echo "Aucune donnée trouvée.";
                        }?>

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
                        <?php $sql = "SELECT * FROM revenu WHERE type_lp='ancien'";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {?>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="id_ancien_lp">Numero LP:</label>
                                <select name="id_ancien_lp[]" id="id_ancien_lp" class="form-select" required>
                                    <option value="">Sélectionner...</option>
                                    <?= $options2; ?>
                                    <option value="<?= $row['id_lp']; ?>" selected><?= $row['numero_cc']; ?>
                                    </option>
                                </select>
                            </div>
                            <div class="col">
                                <label for="assiette_ancien">Assiette en ariary:</label>
                                <input type="text" class="form-control" name="assiette_ancien[]"
                                    value="<?= $row['assiette']; ?>" required>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="region_ancien">Région bénéficiaire:</label>
                                    <input type="text" class="form-control" name="region_ancien[]"
                                        value="<?= $row['nom_region']; ?>" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label for="commune_ancien">Commune bénéficiaire:</label>
                                    <input type="text" class="form-control" name="commune_ancien[]"
                                        value="<?= $row['nom_commune']; ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        }
                    } else {
                                echo "Aucune donnée trouvée.";
                    }?>
                    <!-- Boutons Ajouter / Supprimer -->
                    <button type="button" id="add-field2">Ajouter</button>
                    <button type="button" id="remove-field2">Supprimer</button>
                    <?php } ?>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="droit_conformite">Droit de conformité en ariary:</label>
                                <input type="number" class="form-control" name="droit_conformite" id="droit_conformite"
                                    value="<?php echo $droit_conformite; ?>" required>
                            </div>
                        </div>
                        <div class="col">
                            <?php 
                            $selectedValue2 = $mois_payement;
                            function isSelected_mois($value, $selectedValue2) {
                                return $value === $selectedValue2 ? 'selected' : '';
                            } ?>

                            <div class="mb-3">
                                <label for="nom_mois">Mois:</label>
                                <select name="nom_mois" id="nom_mois" class="form-select" required>
                                    <option value="">Choisir...</option>
                                    <option value="Janvier" <?= isSelected_mois('Janvier', $selectedValue2) ?>>Janvier
                                    </option>
                                    <option value="Février" <?= isSelected_mois('Février', $selectedValue2) ?>>Février
                                    </option>
                                    <option value="Mars" <?= isSelected_mois('Mars', $selectedValue2) ?>>Mars</option>
                                    <option value="Avril" <?= isSelected_mois('Avril', $selectedValue2) ?>>Avril
                                    </option>
                                    <option value="Mai" <?= isSelected_mois('Mai', $selectedValue2) ?>>Mai</option>
                                    <option value="Juin" <?= isSelected_mois('Juin', $selectedValue2) ?>>Juin</option>
                                    <option value="Juillet" <?= isSelected_mois('Juillet', $selectedValue2) ?>>Juillet
                                    </option>
                                    <option value="Août" <?= isSelected_mois('Août', $selectedValue2) ?>>Août</option>
                                    <option value="Septembre" <?= isSelected_mois('Septembre', $selectedValue2) ?>>
                                        Septembre</option>
                                    <option value="Octobre" <?= isSelected_mois('Octobre', $selectedValue2) ?>>Octobre
                                    </option>
                                    <option value="Novembre" <?= isSelected_mois('Novembre', $selectedValue2) ?>>
                                        Novembre</option>
                                    <option value="Décembre" <?= isSelected_mois('Décembre', $selectedValue2) ?>>
                                        Décembre</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label for="numero_ordre">Numéro d'ordre</label>
                                <input type="text" class="form-control" name="numero_ordre"
                                    value="<?= $numero_ordre; ?>" required>
                            </div>
                        </div>
                        <?php if(($lp1=='avec')||($ancien_lp=='avec')) {?>
                        <div class="col">
                            <div class="mb-3">
                                <label for="numero_ordre">Numéro de compte</label>
                                <input type="number" class="form-control" name="numero_compte"
                                    value="<?= $numero_compte; ?>" required>
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
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()"
                            aria-label="Close">Close</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Lorsque l'utilisateur clique sur un bouton de suppression
    $('.btn-delete').on('click', function() {
        var id_lp = $(this).data('id'); // Récupérer l'id de l'enregistrement à supprimer
        if (confirm("Êtes-vous sûr de vouloir supprimer cet enregistrement ?")) {
            $.ajax({
                url: 'delete_revenu.php', // Fichier PHP pour traiter la suppression
                type: 'POST',
                data: {
                    id_lp: id_lp
                },
                success: function(response) {
                    // Si la suppression est réussie, actualiser le contenu du modal ou recharger la partie nécessaire
                    alert('Enregistrement supprimé avec succès.');
                    // Recharger le contenu du modal
                    $('#dynamic-fields').load(location.href + ' #dynamic-fields > *');
                },
                error: function(xhr, status, error) {
                    // Afficher un message en cas d'erreur
                    alert('Erreur lors de la suppression : ' + error);
                }
            });
        }
    });
});
</script>
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