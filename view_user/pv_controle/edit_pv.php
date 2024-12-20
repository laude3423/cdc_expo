<?php
    require_once('../../scripts/db_connect.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
        $sql = "SELECT datacc.*, societe_imp.*, societe_exp.*
        FROM data_cc datacc
        LEFT JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
        LEFT JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
        WHERE id_data_cc = $id_data_cc";
        $sql1 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Chef de section scellage' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql2 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Responsable de la qualité du Laboratoire des Mines' AND assiste_agent.id_data_cc=$id_data_cc";
        
        $sql4 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Douanier' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql5 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Officier de Police' AND assiste_agent.id_data_cc=$id_data_cc";

        
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $resu = $stmt->get_result();
        $row = $resu->fetch_assoc();
        //
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $resu1 = $stmt1->get_result();
        $row1 = $resu1->fetch_assoc();
        //
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $resu2 = $stmt2->get_result();
        $row2 = $resu2->fetch_assoc();
        //
        $sql3 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de Scellage' AND assiste_agent.id_data_cc=$id_data_cc";
        $result3= mysqli_query($conn, $sql3);
        //
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute();
        $resu4 = $stmt4->get_result();
        $row4 = $resu4->fetch_assoc();
        //
        $stmt5 = $conn->prepare($sql5);
        $stmt5->execute();
        $resu5 = $stmt5->get_result();
        $row5 = $resu5->fetch_assoc();
        
        $num_domiciliation = $row["num_domiciliation"] ?? "";
        $pj_domiciliation = $row["pj_domiciliation"] ?? "";
        $nombre_colis = $row["nombre_colis"] ?? "";   
        $lieu_controle = $row["lieu_controle_pv"] ?? "";   
        $lieu_embarquement = $row["lieu_embarquement_pv"] ?? "";   
        $mode_emballage = $row["mode_emballage"] ?? "";   
        $num_fiche_declaration = $row["num_fiche_declaration_pv"] ?? "";   
        $date_fiche_declaration = $row["date_fiche_declaration_pv"] ?? "";   
        $num_lp3e = $row["num_lp3e_pv"] ?? "";  
        $date_lp3e = $row["date_lp3e"] ?? "";   
        $pj_lp3e = $row["pj_lp3e_pv"] ?? "";
        $pj_fiche_declaration = $row["pj_fiche_declaration_pv"] ?? "";
        $id_societe_expediteur = $row["id_societe_expediteur"] ?? "";
        $id_societe_importateur = $row["id_societe_importateur"] ?? "";
        $date_dom = $row['date_dom'] ?? "";

        //
        $id_agent_chef = $row1["id_agent"] ?? "";
        $id_agent_qualite = $row2["id_agent"] ?? "";
        
        $id_agent_scellage = array();

        if($result3){
            while($row3 = mysqli_fetch_assoc($result3)){
                $id_agent_scellage[] = $row3['id_agent'];
            }
        }
        $stmt->close();
        $stmt1->close();
        $stmt2->close();
        $stmt4->close();
        $stmt5->close();
    }

?>
<style>
.required {
    color: red;
}
</style>

<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier le PV de Controle</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="../pv_controle/update_pv.php" method="post" enctype="multipart/form-data">
                    <div class="row" style="display: none;">
                        <div class="col">
                            <label for="expediteur" name="expediteur" class="col-form-label">Société
                                expéditeur:</label>
                            <select id="expediteur" name="expediteur" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT societe.*, datacc.id_data_cc FROM data_cc datacc INNER JOIN societe_expediteur societe ON datacc.id_societe_expediteur=societe.id_societe_expediteur WHERE datacc.id_data_cc=$id_data_cc";
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
                                    $query = "SELECT societe.*, datacc.id_data_cc FROM data_cc datacc INNER JOIN societe_importateur societe ON datacc.id_societe_importateur=societe.id_societe_importateur WHERE datacc.id_data_cc=$id_data_cc";
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
                            <label for="facture" name="facture" class="col-form-label">Numéro de la
                                facture:</label>
                            <select id="facture" name="facture" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%" disabled>
                                <option value="">Choisir ...</option>
                                <?php    
                                    // $query = "SELECT id_data_cc, num_facture FROM data_cc WHERE id_data_cc=$id_data_cc";
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
                                domiciliation<span class="required">*</span></label>
                            <input type="text" class="form-control" name="numDom" id="numDom"
                                value="<?php echo $num_domiciliation; ?>" required style="font-size:90%">
                            <div id="error_message" style="color: red; display: none;">Le numéro de domiciliation ne
                                doit pas
                                commencer par "N°" ou "n°".</div>
                        </div>
                        <div class="col">
                            <label for="date_dom" name="date_dom" class="col-form-label">Date de création DOM<span
                                    class="required">*</span></label>
                            <input type="date" class="form-control" name="date_dom" id="date_dom"
                                value="<?php echo $date_dom; ?>" required style="font-size:90%">
                            <div id="date_error1" style="color: red; display: none;">Veuillez entrer une date valide.
                            </div>
                        </div> -->
                    </div>
                    <div class="mb-3">
                        <label for="mode_emballage" name="mode_emballage" class="col-form-label">Nombre et mode
                            d'emballage<span class="required">*</span></label>
                        <input type="text" class="form-control" name="mode_emballage" id="mode_emballage"
                            value="<?php echo $mode_emballage; ?>" required style="font-size:90%">
                        <!-- <div class="col">
                            <label for="pj_dom" name="pj_dom" class="col-form-label">Pièce joint de
                                DOM</label>
                            <input type="file" class="form-control" name="pj_dom" id="pj_dom" accept=".pdf"
                                placeholder="Nombre de colis" style="font-size:90%">
                        </div> -->
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_controle" name="lieu_controle" class="col-form-label">Lieu de
                                controle<span class="required">*</span></label>
                            <input type="text" class="form-control" name="lieu_controle" id="lieu_controle"
                                value="<?php echo $lieu_controle; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="lieu_emb" name="lieu_emb" class="col-form-label">Lieu
                                d'embarquement<span class="required">*</span></label>
                            <input type="text" class="form-control" name="lieu_emb" id="lieu_emb"
                                value="<?php echo $lieu_embarquement; ?>" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="declaration" name="declaration" class="col-form-label">Numéro de fiche
                                de
                                déclaration<span class="required">*</span></label>
                            <input type="text" class="form-control" name="declaration" id="declaration"
                                value="<?php echo $num_fiche_declaration; ?>" required style="font-size:90%">
                            <div id="error_message2" style="color: red; display: none;">Le numéro de fiche de
                                déclaration ne doit pas commencer par "N°" ou "n°".</div>
                        </div>
                        <div class="col">
                            <label for="date_declaration" name="date_declaration" class="col-form-label">Date
                                de
                                fiche de déclaration<span class="required">*</span></label>
                            <input type="date" class="form-control" name="date_declaration"
                                value="<?php echo $date_fiche_declaration; ?>" id="date_declaration" required
                                style="font-size:90%">
                            <div id="date_error2" style="color: red; display: none;">Veuillez entrer une date
                                valide.
                                <input type="hidden" value="<?php echo $id_data_cc; ?>" id="id" name="id">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_declaration" name="pj_declaration" class="col-form-label">Pièce
                                joint de
                                fiche de
                                déclaration:</label>
                            <input type="file" class="form-control" name="pj_declaration" id="pj_declaration"
                                accept=".pdf" placeholder="Numéro de fiche de déclaration" style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="num_lp3" name="num_lp3" class="col-form-label">Numéro de LP III
                                E<span class="required">*</span></label>
                            <input type="text" value="<?php echo $num_lp3e; ?>" class="form-control" name="num_lp3"
                                id="num_lp3" required style="font-size:90%">
                            <div id="error_message3" style="color: red; display: none;">Le numéro LP3 E ne doit pas
                                commencer par "N°" ou "n°".</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="date_lp3" name="date_lp3" class="col-form-label">Date de création de
                                LP III
                                E<span class="required">*</span></label>
                            <input type="date" class="form-control" name="date_lp3" id="date_lp3"
                                value="<?php echo $date_lp3e; ?>" required style="font-size:90%">
                            <div id="date_error3" style="color: red; display: none;">Veuillez entrer une date
                                valide.
                            </div>
                        </div>
                        <div class="col">
                            <label for="pj_lp3" name="pj_lp3" class="col-form-label">Pièce joint LP III
                                E:</label>
                            <input type="file" accept=".pdf" class="form-control" name="pj_lp3" id="pj_lp3"
                                style="font-size:90%">
                        </div>
                    </div>
                    <!-- <div class="row">
                        <div class="col">
                            <label for="chef" name="chef" class="col-form-label">Chef de section scellage:<span
                                    class="required">*</span></label>
                            <select id="chef" name="chef" placeholder="Choisir ..." autocomplete="off"
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    
                                    // if(!empty($id_agent_chef)) {
                                    //     $query = "SELECT * FROM agent WHERE fonction_agent='Chef de section scellage'";
                                    //     $stmt = $conn->prepare($query);
                                    //     $stmt->execute();
                                    //     $resu = $stmt->get_result();
                                    //     while ($rowSub = $resu->fetch_assoc()) {
                                    //         $selected = ($rowSub["id_agent"] == $id_agent_chef) ? "selected" : "";
                                    //         echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] .' '.$rowSub['prenom_agent']. "</option>";
                                    //     }
                                        
                                    // }?>

                            </select>
                        </div>
                        <div class="col">
                            <label for="qualite" name="qualite" class="col-form-label">Responsable de
                                qualité,
                                laboratoire<span class="required">*</span></label>
                            <select id="qualite" name="qualite" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php 
                                // if(!empty($id_agent_qualite)){
                                //     $query = "SELECT * FROM agent WHERE fonction_agent='Responsable de la qualité du Laboratoire des Mines'";
                                //     $stmt = $conn->prepare($query);
                                //     $stmt->execute();
                                //     $resu = $stmt->get_result();
                                        
                                //         while ($rowSub = $resu->fetch_assoc()) {
                                //             $selected = ($rowSub["id_agent"] == $id_agent_qualite) ? "selected" : "";
                                //             echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] .' '.$rowSub['prenom_agent']. "</option>";
                                //         }
                                // }   
                                    
                                        ?>
                            </select>
                        </div>
                    </div><br> --><br>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                        <button class="btn btn-sm btn-primary" type="submit2" name="submit2">Enregistrer</button>
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
//         create: true,
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
document.getElementById('date_lp3').addEventListener('input', function() {
    const dateInput = this.value;
    const dateError = document.getElementById('date_error3');
    if (isValidDate(dateInput)) {
        dateError.style.display = 'none';
    } else {
        dateError.style.display = 'block';
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
    var allowedExtension = /(\.pdf)$/i;

    if (!allowedExtension.exec(filePath)) {
        alert('Veuillez choisir un fichier PDF.');
        fileInput.value = '';
        return false;
    }
}

document.getElementById('pj_dom').addEventListener('change', validatePDFInput);
document.getElementById('pj_declaration').addEventListener('change', validatePDFInput);
document.getElementById('pj_lp3').addEventListener('change', validatePDFInput);

document.getElementById("numDom").addEventListener("input", function() {
    const factureInput = document.getElementById("numDom");
    const errorMessage = document.getElementById("error_message");
    const value = factureInput.value.trim();

    if (/^(N°|n°)/.test(value)) {
        errorMessage.style.display = "block";
        factureInput.setCustomValidity("Le numéro de domiciliation ne doit pas commencer par 'N°' ou 'n°'.");
    } else {
        errorMessage.style.display = "none";
        factureInput.setCustomValidity("");
    }
});
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
</script>