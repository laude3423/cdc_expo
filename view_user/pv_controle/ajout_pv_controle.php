<?php
include_once('../../scripts/db_connect.php');
require(__DIR__ . '/../../scripts/session.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql = "SELECT datacc.*, societe_imp.*, societe_exp.*
        FROM data_cc datacc
        LEFT JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
        LEFT JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
        WHERE datacc.id_data_cc = $id_data_cc";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        $id_societe_expediteur= $row['id_societe_expediteur'];
        $id_societe_importateur= $row['id_societe_importateur'];
    }
?>
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouveau PV de controle</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="../pv_controle/lister.php" method="post" enctype="multipart/form-data">
                    <div class="row" style="display: none;">
                        <div class="col">
                            <label for="expediteur" name="expediteur" class="col-form-label">Société
                                expéditeur:</label>
                            <select id="expediteur" name="expediteur" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT * FROM societe_expediteur";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        $selected = ($rowSub["id_societe_expediteur"] == $id_societe_expediteur) ? "selected" : "";
                                        echo "<option value='" . $rowSub['id_societe_expediteur'] ."'$selected>". $rowSub['nom_societe_expediteur'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="importateur" name="importateur" class="col-form-label">Société
                                importateur:</label>
                            <select id="importateur" name="importateur" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT * FROM societe_importateur";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        $selected = ($rowSub["id_societe_importateur"] == $id_societe_importateur) ? "selected" : "";
                                        echo "<option value='" . $rowSub['id_societe_importateur'] ."'$selected>". $rowSub['nom_societe_importateur'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <!-- <div class="col">
                            <label for="facture" name="facture" class="col-form-label">Numéro de la facture:</label>
                            <select id="facture" name="facture" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%" disabled>
                                <option value="">Choisir ...</option>
                                <?php    
                                    // $query = "SELECT id_data_cc, num_facture FROM data_cc";
                                    // $stmt = $conn->prepare($query);
                                    // $stmt->execute();
                                    // $resu = $stmt->get_result();
                                    
                                    // while ($rowSub = $resu->fetch_assoc()) {
                                    //     $selected = ($rowSub["id_data_cc"] == $id_data_cc) ? "selected" : "";
                                    //     echo "<option value='" . $rowSub['id_data_cc'] ."'$selected>". $rowSub['num_facture'] . "</option>";
                                    // }
                                    ?>
                            </select>
                        </div> -->
                        <!-- <div class="col">
                            <label for="numDom" name="numDom" class="col-form-label">Numéro de
                                domiciliation:</label>
                            <input type="text" class="form-control" name="numDom" id="numDom"
                                placeholder="Numéro de domiciliation" required style="font-size:90%">
                            <div id="error_message" style="color: red; display: none;">Le numéro de domiciliation ne
                                doit pas
                                commencer par "N°" ou "n°".</div>
                        </div>
                        <div class="col">
                            <label for="date_dom" name="date_dom" class="col-form-label">Date de création DOM</label>
                            <input type="date" class="form-control" name="date_dom" id="date_dom"
                                placeholder="Date de départ de l'exportation" required style="font-size:90%">
                            <div id="date_error1" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                        </div> -->
                    </div>
                    <div class="mb-3">
                        <label for="mode_emballage" name="mode_emballage" class="col-form-label">Nombre et mode
                            d'emballage:</label>
                        <input type="text" class="form-control" name="mode_emballage" id="mode_emballage"
                            placeholder="Nombre et mode d'emballage" required style="font-size:90%">

                        <!-- <div class="col">
                            <label for="pj_dom" name="pj_dom" class="col-form-label">Pièce joint de DOM:</label>
                            <input type="file" class="form-control" name="pj_dom" id="pj_dom" accept=".pdf"
                                placeholder="Nombre de colis" style="font-size:90%" required>
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_controle" name="lieu_controle" class="col-form-label">Lieu de
                                controle:</label>
                            <input type="text" class="form-control" name="lieu_controle" id="lieu_controle"
                                placeholder="Lieu de controle" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="lieu_emb" name="lieu_emb" class="col-form-label">Lieu
                                d'embarquement:</label>
                            <input type="text" class="form-control" name="lieu_emb" id="lieu_emb"
                                placeholder="Lieu d'embarquement" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="declaration" name="declaration" class="col-form-label">Numéro de fiche de
                                déclaration:</label>
                            <input type="text" class="form-control" name="declaration" id="declaration"
                                placeholder="Numéro de fiche de déclaration" required style="font-size:90%">
                            <div id="error_message2" style="color: red; display: none;">Le numéro de fiche de
                                déclaration ne doit pas commencer par "N°" ou "n°".</div>
                        </div>
                        <div class="col">
                            <label for="date_declaration" name="date_declaration" class="col-form-label">Date de
                                fiche de déclaration:</label>
                            <input type="date" class="form-control" name="date_declaration" id="date_declaration"
                                required style="font-size:90%">
                            <div id="date_error2" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                            <input type="hidden" id="id" value="<?php echo $id_data_cc; ?>" name="id">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_declaration" name="pj_declaration" class="col-form-label">Pièce joint de
                                fiche de
                                déclaration:</label>
                            <input type="file" class="form-control" name="pj_declaration" id="pj_declaration"
                                accept=".pdf" style="font-size:90%" required>
                        </div>
                        <div class="col">
                            <label for="num_lp3" name="num_lp3" class="col-form-label">Numéro de LP III E:</label>
                            <input type="text" placeholder="Numéro de LP III E" class="form-control" name="num_lp3"
                                id="num_lp3" required style="font-size:90%">
                            <div id="error_message3" style="color: red; display: none;">Le numéro LP3 E ne doit pas
                                commencer par "N°" ou "n°".</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="date_lp3" name="date_lp3" class="col-form-label">Date de création de LP III
                                E:</label>
                            <input type="date" class="form-control" name="date_lp3" id="date_lp3" required
                                style="font-size:90%">
                            <div id="date_error3" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                        </div>
                        <div class="col">
                            <label for="pj_lp3e" name="pj_lp3e" class="col-form-label">Pièce joint LP III E:</label>
                            <input type="file" class="form-control" name="pj_lp3e" id="pj_lp3e" accept=".pdf"
                                style="font-size:90%" required>
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col">
                            <label for="chef" name="chef" class="col-form-label">Chef de section scellage:</label>
                            <select id="chef" name="chef" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                        // $query = "SELECT * FROM agent WHERE fonction_agent='Chef de section scellage'";
                                        // $stmt = $conn->prepare($query);
                                        // $stmt->execute();
                                        // $resu = $stmt->get_result();
                                        
                                        // while ($rowSub = $resu->fetch_assoc()) {
                                        //     echo "<option value='" . $rowSub['id_agent'] ."'>" . $rowSub['nom_agent'] .' '.$rowSub['prenom_agent']. "</option>";
                                        // }
                                        ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="qualite" name="qualite" class="col-form-label">Responsable de la qualité du
                                Laboratoire des Mines:</label>
                            <select id="qualite" name="qualite" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                        // $query = "SELECT * FROM agent WHERE fonction_agent='Responsable de la qualité du Laboratoire des Mines'";
                                        // $stmt = $conn->prepare($query);
                                        // $stmt->execute();
                                        // $resu = $stmt->get_result();
                                        
                                        // while ($rowSub = $resu->fetch_assoc()) {
                                        //     echo "<option value='" . $rowSub['id_agent'] ."'>" . $rowSub['nom_agent'] .' '.$rowSub['prenom_agent']. "</option>";
                                        // }
                                        ?>
                            </select>
                        </div>
                    </div> -->
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

    //selectTom();
    console.log("Correct");

});

// function selectTom() {
//     // Initialisez TomSelect pour chaque élément select
//     var selectOptions = {
//         create: false,
//         sortField: {
//             field: "text",
//             direction: "asc"
//         }
//     };
//     new TomSelect("#expediteur", selectOptions);
//     new TomSelect("#importateur", selectOptions);
//     new TomSelect("#chef", selectOptions);
//     new TomSelect("#qualite", selectOptions);
//     new TomSelect("#police", selectOptions);
//     new TomSelect("#douane", selectOptions);
//     new TomSelect("#agent_scellage", selectOptions);

// };
// document.getElementById('date_depart').addEventListener('input', function() {
//     const dateInput = this.value;
//     const dateError = document.getElementById('date_error1');
//     if (isValidDate(dateInput)) {
//         dateError.style.display = 'none';
//     } else {
//         dateError.style.display = 'block';
//     }
// });
document.getElementById('date_declaration').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error2');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
    }
});
// document.getElementById("numDom").addEventListener("input", function() {
//     console.log('consulter');
//     const factureInput = document.getElementById("numDom");
//     const errorMessage = document.getElementById("error_message");
//     const value = factureInput.value.trim();

//     if (/^(N°|n°)/.test(value)) {
//         errorMessage.style.display = "block";
//         factureInput.setCustomValidity("Le numéro de domiciliation ne doit pas commencer par 'N°' ou 'n°'.");
//     } else {
//         errorMessage.style.display = "none";
//         factureInput.setCustomValidity("");
//     }
// });
document.getElementById("declaration").addEventListener("input", function() {
    const factureInput = document.getElementById("declaration");
    const errorMessage = document.getElementById("error_message2");
    const value = factureInput.value.trim();

    if (/^(N°|n°)/.test(value)) {
        errorMessage.style.display = "block";
        factureInput.setCustomValidity("Le numéro de déclaration ne doit pas commencer par 'N°' ou 'n°'.");
    } else {
        errorMessage.style.display = "none";
        factureInput.setCustomValidity("");
    }
});
document.getElementById("num_lp3").addEventListener("input", function() {
    const factureInput = document.getElementById("num_lp3");
    const errorMessage = document.getElementById("error_message3");
    const value = factureInput.value.trim();

    if (/^(N°|n°)/.test(value)) {
        errorMessage.style.display = "block";
        factureInput.setCustomValidity("Le numéro LP3 E ne doit pas commencer par 'N°' ou 'n°'.");
    } else {
        errorMessage.style.display = "none";
        factureInput.setCustomValidity("");
    }
});
document.getElementById('date_lp3').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error3');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
    }
});
document.getElementById('date_depart').addEventListener('input', function() {
    var inputDate = new Date(this.value);
    var today = new Date();

    // Réinitialiser l'heure à 00:00:00 pour comparer uniquement les dates
    today.setHours(0, 0, 0, 0);

    if (inputDate < today) {
        document.getElementById('date_error1').style.display = 'block';
    } else {
        document.getElementById('date_error1').style.display = 'none';
    }
});

function isValidDate(dateString) {
    const date = new Date(dateString);
    const timestamp = date.getTime();

    if (typeof timestamp !== 'number' || Number.isNaN(timestamp)) {
        return false;
    }

    return dateString === date.toISOString().split('T')[0];
}

function validatePDFInput(event) {
    var fileInput = event.target;
    var filePath = fileInput.value;
    var allowedExtension = /\.pdf$/i;

    if (!allowedExtension.test(filePath)) {
        alert('Veuillez choisir un fichier PDF.');
        fileInput.value = ''; // Clear the file input
        return false;
    }
}

// document.getElementById('pj_dom').addEventListener('change', validatePDFInput);
document.getElementById('pj_declaration').addEventListener('change', validatePDFInput);
document.getElementById('pj_lp3e').addEventListener('change', validatePDFInput);
</script>