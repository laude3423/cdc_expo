<?php
require_once('../../scripts/db_connect.php');
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT aut.*, vo.*, pay.*, cont.*, agent.* FROM `autorisation` AS aut 
            LEFT JOIN vol AS vo ON vo.id_vol = aut.id_vol 
            LEFT JOIN pays AS pay ON pay.id_pays = aut.id_pays 
            LEFT JOIN continent AS cont ON cont.id_continent = pay.id_continent 
            LEFT JOIN agent_controle AS agent ON agent.id_agent_controle = aut.id_agent_controle
            WHERE id_autorisation = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resu = $stmt->get_result();
    $row = $resu->fetch_assoc();

    $stmt->close();
}else{
    echo "vide";
}
?>

<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier autorisation</h1>
                <button type="button" class="btn-close" onclick="closeModal1()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="./update.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">

                    <!-- Section Civilité -->
                    <div class="row">
                        <div class="col">
                            <label for="civiliste_edit" class="col-form-label">Civilité:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="civilite_edit" id="civilite_v"
                                    value="M" <?php echo ($row['civilite'] == 'M') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="civilite_v">Mademoiselle</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="civilite_edit" id="civilite_a"
                                    value="MM" <?php echo ($row['civilite'] == 'MM') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="civilite_a">Madame</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="civilite_edit" id="civilite_m"
                                    value="MS" <?php echo ($row['civilite'] == 'MS') ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="civilite_m">Monsieur</label>
                            </div>
                        </div>
                        <div class="col">
                            <label for="date_depart_edit" class="col-form-label">Date de départ:</label>
                            <input type="date" class="form-control" value="<?php echo $row['date_depart']; ?>"
                                name="date_depart_edit" id="date_depart_edit" style="font-size:90%">
                        </div>
                    </div>

                    <!-- Section Nom et Prénom -->
                    <div class="row">
                        <div class="col">
                            <label for="nom_edit" class="col-form-label">Nom de l'expéditeur:</label>
                            <input type="text" class="form-control" name="nom_edit" id="nom_edit"
                                placeholder="Nom de l'expéditeur" value="<?php echo $row['nom_porteur']; ?>" required
                                style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="prenom_edit" class="col-form-label">Prénom(s) de l'expéditeur:</label>
                            <input type="text" class="form-control" name="prenom_edit" id="prenom_edit"
                                value="<?php echo $row['prenom_porteur']; ?>" placeholder="Prénom de l'agent"
                                style="font-size:90%">
                        </div>
                    </div>

                    <!-- Section Passeport -->
                    <div class="row">
                        <div class="col">
                            <label for="passeport_edit" class="col-form-label">Passeport:</label>
                            <input type="text" class="form-control" name="passeport_edit" id="passeport_edit"
                                value="<?php echo $row['numero_passeport']; ?>" placeholder="Numéro du passeport"
                                required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="scan_passeport_edit" class="col-form-label">Scan du
                                passeport(facultatif):</label>
                            <input type="file" class="form-control" accept=".pdf" name="scan_passeport_edit"
                                id="scan_passeport_edit" placeholder="Scan du passeport" style="font-size:90%">
                        </div>
                    </div>
                    <!-- Section facture -->
                    <div class="row">
                        <div class="col">
                            <label for="facture_edit" class="col-form-label">Numéro de la facture:</label>
                            <input type="text" class="form-control" name="facture_edit" id="facture_edit"
                                value="<?php echo $row['numero_facture']; ?>" placeholder="Numéro du facture" required
                                style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="scan_facture_edit" class="col-form-label">Scan de la
                                facture(facultatif):</label>
                            <input type="file" class="form-control" name="scan_facture_edit" id="scan_facture_edit"
                                placeholder="Scan de la facture" style="font-size:90%">
                        </div>
                    </div>
                    <!-- Section Vol -->
                    <div class="row">
                        <div class="col">
                            <label for="num_vol_edit" class="col-form-label">Numéro du vol:</label>
                            <select id="num_vol_edit" class="form-select" name="num_vol_edit" placeholder="Choisir ..."
                                autocomplete="off" required style="font-size:90%" onchange="updateFlightDetails1()">
                                <option value="">Choisir ...</option>
                                <?php
                                $query = "SELECT * FROM vol";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                while ($rowSub = $resu->fetch_assoc()) {
                                    $selected = ($rowSub["id_vol"] == $row['id_vol']) ? "selected" : "";
                                    echo "<option value='" . $rowSub['id_vol'] . "' data-compagnie='" . $rowSub['nom_compagnie'] . "' data-escale='" . $rowSub['destination_vol'] . "' $selected>" . $rowSub['numero_vol'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="compagnie_edit" class="col-form-label">Nom de la compagnie:</label>
                            <input type="text" class="form-control" name="compagnie_edit" id="compagnie_edit"
                                value="<?php echo $row['nom_compagnie']; ?>" placeholder="Nom de la compagnie" required
                                style="font-size:90%" readonly>
                        </div>
                    </div>

                    <!-- Section Escale et Continent -->
                    <div class="row">
                        <div class="col">
                            <label for="escale_edit" class="col-form-label">Escale:</label>
                            <input type="text" class="form-control" name="escale_edit" id="escale_edit"
                                placeholder="Escale" value="<?php echo $row['destination_vol']; ?>" required
                                style="font-size:90%" readonly>
                        </div>
                        <div class="col">
                            <label for="continent_edit" class="col-form-label">Continent:</label>
                            <select id="continent_edit" class="form-select" name="continent_edit"
                                placeholder="Choisir ..." autocomplete="off" required style="font-size:90%"
                                onchange="updateFlightDetails1()">
                                <option value="">Choisir ...</option>
                                <?php
                                $query = "SELECT * FROM continent";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                while ($rowSub = $resu->fetch_assoc()) {
                                    $selected = ($rowSub["id_continent"] == $row['id_continent']) ? "selected" : "";
                                    echo "<option value='" . $rowSub['id_continent'] . "' $selected>" . $rowSub['nom_continent'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Section Destination et Matricule du responsable -->
                    <div class="row">
                        <div class="col">
                            <label for="id_pays_edit" class="col-form-label">Destination:</label>
                            <select class="form-select" id="id_pays_edit" name="id_pays_edit" required>
                                <?php echo "<option value='" . $row['id_pays'] . "'>" . $row['nom_pays'] . "</option>";?>
                                <!-- Options seront remplies via AJAX -->
                            </select>
                        </div>
                        <div class="col">
                            <label for="matricule_edit" class="col-form-label">Matricule du responsable:</label>
                            <select id="matricule_edit" class="form-select" name="matricule_edit"
                                placeholder="Choisir ..." autocomplete="off" required style="font-size:90%"
                                onchange="updateFlightDetails_agent1()">
                                <option value="">Choisir ...</option>
                                <?php
                                $query = "SELECT * FROM agent_controle";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                while ($rowSub = $resu->fetch_assoc()) {
                                    $selected = ($rowSub["id_agent_controle"] == $row['id_agent_controle']) ? "selected" : "";
                                    echo "<option value='" . $rowSub['id_agent_controle'] . "' data-nom='" . $rowSub['nom_agent'] . "' data-prenom='" . $rowSub['prenom_agent'] . "' $selected>" . $rowSub['matricule'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Section Nom et Prénom du responsable -->
                    <div class="row">
                        <div class="col">
                            <div class="col">
                                <label for="nom_agent_edit" class="col-form-label">Nom du responsable:</label>
                                <input type="text" class="form-control" name="nom_agent_edit" id="nom_agent_edit"
                                    value="<?php echo $row['nom_agent']; ?>" placeholder="Nom du responsable" required
                                    style="font-size:90%" readonly>
                            </div>
                        </div>
                        <div class="col">
                            <label for="prenom_agent_edit" class="col-form-label">Prénom(s) de l'agent:</label>
                            <input type="text" class="form-control" name="prenom_agent_edit" id="prenom_agent_edit"
                                value="<?php echo $row['prenom_agent']; ?>" placeholder="Nom du responsable" required
                                style="font-size:90%" readonly>
                        </div>
                    </div>

                    <!-- Section Colis -->
                    <div class="row">
                        <div class="col">
                            <label for="designation_edit" class="col-form-label">Colis:</label>
                            <textarea name="designation_edit" id="designation_edit"
                                class="form-control"><?php echo $row['designation']; ?></textarea>
                        </div>
                        <div class="col">
                            <div class="row">
                                <div class="col">
                                    <label for="poids_edit" class="col-form-label">Poids:</label>
                                    <input type="number" value="<?php echo $row['poids']; ?>" class="form-control"
                                        name="poids_edit" id="poids_edit" step="any" required style="font-size:90%">
                                </div>
                                <div class="col">
                                    <label for="unite_edit" class="col-form-label">Unité:</label>
                                    <select class="form-select" id="unite_edit" name="unite_edit" required>
                                        <option value="">Sélectionner...</option>
                                        <option value="Kg" <?php echo ($row['unite'] == 'Kg') ? 'selected' : ''; ?>>Kg
                                        </option>
                                        <option value="g" <?php echo ($row['unite']  == 'g') ? 'selected' : ''; ?>>g
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal1()">Fermer</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateFlightDetails1() {
    var selectVol = document.getElementById('num_vol_edit');
    var compagnieInput = document.getElementById('compagnie_edit');
    var escaleInput = document.getElementById('escale_edit');

    var selectedOption = selectVol.options[selectVol.selectedIndex];
    var compagnie = selectedOption.getAttribute('data-compagnie');
    var escale = selectedOption.getAttribute('data-escale');

    compagnieInput.value = compagnie;
    escaleInput.value = escale;
}

function updateFlightDetails_agent1() {
    var selectVol = document.getElementById('matricule_edit');
    var nomAgentInput = document.getElementById('nom_agent_edit');
    var prenomAgentInput = document.getElementById('prenom_agent_edit');

    var selectedOption = selectVol.options[selectVol.selectedIndex];
    var nom = selectedOption.getAttribute('data-nom');
    var prenom = selectedOption.getAttribute('data-prenom');

    nomAgentInput.value = nom;
    prenomAgentInput.value = prenom;
}
$(document).ready(function() {
    // Lorsqu'une option est sélectionnée dans le premier menu
    $("#continent_edit").change(function() {
        var id_continent = $(this).val();
        if (id_continent !== "") {
            $("#id_pays_edit").prop("disabled", false);
            // Charger les pays en fonction du continent sélectionné
            ajax2(id_continent);
        } else {
            // Désactiver et réinitialiser le deuxième menu déroulant
            $("#id_pays_edit").prop("disabled", true).html(
                "<option value=''>Sélectionner d'abord un continent...</option>");
        }
    });
});

function closeModal1() {
    var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop2'), {
        backdrop: 'static',
        keyboard: false
    });
    myModal.hide();
}

function ajax2(id_continent) {
    $.ajax({
        url: "get.php",
        method: "POST",
        data: {
            continent: id_continent
        },
        dataType: "json",
        success: function(data) {
            const dropdowns = [{
                id: "#id_pays_edit",
                options: data.options_pays,
                emptyMessage: "Aucune..."
            }];

            dropdowns.forEach(dropdown => {
                if (dropdown.options === "<option value=''>Sélectionner...</option>") {
                    $(dropdown.id).prop("disabled", true).html(
                        `<option value=''>${dropdown.emptyMessage}</option>`);
                } else {
                    $(dropdown.id).prop("disabled", false).html(dropdown.options);
                }
            });
        },
        error: function(xhr, status, error) {
            console.log("An error occurred:", error);
            console.log("Response text:", xhr.responseText);
        }
    });
}
</script>