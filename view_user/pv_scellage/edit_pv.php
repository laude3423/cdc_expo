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
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de Scellage' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql2 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Responsable de la qualité du Laboratoire des Mines' AND assiste_agent.id_data_cc=$id_data_cc";
        
        $sql4 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de douane' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql5 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Officier de police judiciaire' AND assiste_agent.id_data_cc=$id_data_cc";

        
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
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE assiste_agent.id_data_cc=$id_data_cc";
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
        $lieu_scellage = $row["lieu_scellage_pv"] ?? "";   
        $lieu_embarquement = $row["lieu_embarquement_pv"] ?? "";   
        $type_colis = $row["type_colis"] ?? "";   
        $num_fiche_declaration = $row["num_fiche_declaration_pv"] ?? "";   
        $date_fiche_declaration = $row["date_fiche_declaration_pv"] ?? "";   
        $num_lp3e = $row["num_lp3e_pv"] ?? "";  
        $date_lp3e = $row["date_lp3e"] ?? "";   
        $pj_lp3e = $row["pj_lp3e_pv"] ?? ""; 
        $pj_fiche_declaration = $row["pj_fiche_declaration_pv"] ?? "";
        $id_societe_expediteur = $row["id_societe_expediteur"] ?? "";
        $id_societe_importateur = $row["id_societe_importateur"] ?? "";
        $date_depart =$row['date_depart'] ?? "";

        //
        $id_agent_chef = $row1["id_agent"] ?? "";
        $id_agent_qualite = $row2["id_agent"] ?? "";
        $id_agent_scellage = $row3["id_agent"] ?? "";
        $id_agent_douane = $row4["id_agent"] ?? "";
        $id_agent_police = $row5["id_agent"] ?? "";

        $sql6 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de l\'Agence Nationale Anti-Fraude' AND assiste_agent.id_data_cc=$id_data_cc";
        $stmt6 = $conn->prepare($sql6);
        $stmt6->execute();
        $resu6 = $stmt6->get_result();
        $row6 = $resu6->fetch_assoc();
        $id_fraude = $row6['id_agent'] ?? "";
        
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

<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier le PV de Scellage</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="./update_pv.php" method="post">
                    <div class="row">
                        <div class="col">
                            <label for="nombre" name="nombre" class="col-form-label">Nombre de colis:</label>
                            <input type="numbere" class="form-control" name="nombre" id="nombre"
                                value="<?php echo $nombre_colis; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="type_colis" name="type_colis" class="col-form-label">Type de
                                colis:</label>
                            <input type="text" class="form-control" name="type_colis" id="type_colis"
                                value="<?php echo $type_colis; ?>" required style="font-size:90%">
                            <input type="hidden" id="id" name="id" value="<?php echo $id_data_cc; ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_scellage" name="lieu_scellage" class="col-form-label">Lieu de
                                scellage:</label>
                            <input type="text" class="form-control" name="lieu_scellage" id="lieu_scellage"
                                value="<?php echo $lieu_scellage; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="police" name="police" class="col-form-label">Officier de
                                Police:</label>
                            <select id="police" name="police" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php 
                                    $query = "SELECT * FROM agent WHERE fonction_agent='Officier de police judiciaire'";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            $selected = ($rowSub["id_agent"] == $id_agent_police) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] .' '. $rowSub['prenom_agent']. "</option>";
                                        }
                                    
                                        ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="douane" name="douane" class="col-form-label">Agent Douanier:</label>
                            <select id="douane" name="douane" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT * FROM agent WHERE fonction_agent='Agent de douane'";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            $selected = ($rowSub["id_agent"] == $id_agent_douane) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] .' '. $rowSub['prenom_agent']. "</option>";
                                        }
                                    
                                        ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="agent_scellage" name="agent_scellage" class="col-form-label">Agent
                                de
                                scellage:</label>
                            <select id="agent_scellage" name="agent_scellage[]" placeholder="Choisir ..."
                                autocomplete="off" required style="font-size:90%" multiple>
                                <option value="">Choisir ...</option>
                                <?php  
                                        $query = "SELECT * FROM agent WHERE fonction_agent='Agent de Scellage'";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo$rowSub["id_agent"];
                                            $selected = (in_array($rowSub["id_agent"], $id_agent_scellage)) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] .' '. $rowSub['prenom_agent']. "</option>";
                                        }
                                       
                                        ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="faude" name="faude" class="col-form-label">Agent de l'Agence Nationale
                                Anti-Fraude:</label>
                            <select id="faude" name="faude" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                        $query = "SELECT * FROM agent WHERE fonction_agent=\"Agent de l'Agence Nationale Anti-Fraude\"";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $resu = $stmt->get_result();
                                            
                                            while ($rowSub = $resu->fetch_assoc()) {
                                                $selected = ($rowSub["id_agent"] == $id_fraude) ? "selected" : "";
                                                echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] .' '. $rowSub['prenom_agent']. "</option>";
                                            }
                                        
                                            ?>
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
                                if(!empty($id_agent_qualite)){
                                    $query = "SELECT * FROM agent WHERE fonction_agent='Responsable de la qualité du Laboratoire des Mines'";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            $selected = ($rowSub["id_agent"] == $id_agent_qualite) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] .' '.$rowSub['prenom_agent']. "</option>";
                                        }
                                }   
                                    
                                        ?>
                            </select>
                        </div>
                    </div>
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

    selectTom();
    console.log("Correct");

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
    new TomSelect("#police", selectOptions);
    new TomSelect("#douane", selectOptions);
    new TomSelect("#agent_scellage", selectOptions);
    new TomSelect("#faude", selectOptions);
    new TomSelect("#qualite", selectOptions);

};
</script>