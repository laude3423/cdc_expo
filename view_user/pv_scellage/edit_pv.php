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
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Chef de Division Exportation Minière' AND assiste_agent.id_data_cc=$id_data_cc";
        $sql2 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Responsable qualité Laboratoire des Mines' AND assiste_agent.id_data_cc=$id_data_cc";
        
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

        //
        $id_agent_chef = $row1["id_agent"] ?? "";
        $id_agent_qualite = $row2["id_agent"] ?? "";
        $id_agent_scellage = $row3["id_agent"] ?? "";
        $id_agent_douane = $row4["id_agent"] ?? "";
        $id_agent_police = $row5["id_agent"] ?? "";
        
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
                <form action="./update_pv.php" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col">
                            <label for="expediteur_edit" name="expediteur_edit" class="col-form-label">Société
                                expéditeur:</label>
                            <select id="expediteur_edit" name="expediteur_edit" placeholder="Choisir ..."
                                autocomplete="off" required style="font-size:90%">
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
                            <label for="destination_edit" name="destination_edit" class="col-form-label">Destination
                                finale:</label>
                            <select id="destination_edit" name="destination_edit" placeholder="Choisir ..."
                                autocomplete="off" required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT societe.*, datacc.id_data_cc FROM data_cc datacc INNER JOIN societe_importateur societe ON datacc.id_societe_importateur=societe.id_societe_importateur WHERE datacc.id_data_cc=$id_data_cc";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        $selected = ($rowSub["id_societe_importateur"] == $id_societe_importateur) ? "selected" : "";
                                        echo "<option value='" . $rowSub['id_societe_importateur'] ."'$selected>". $rowSub['pays_destination'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="facture_edit" name="facture_edit" class="col-form-label">Numéro de la
                                facture:</label>
                            <select id="facture_edit" name="facture_edit" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    $query = "SELECT id_data_cc, num_facture FROM data_cc WHERE id_data_cc=$id_data_cc";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        $selected = ($rowSub["id_data_cc"] == $id_data_cc) ? "selected" : "";
                                        echo "<option value='" . $rowSub['id_data_cc'] ."'$selected>". $rowSub['num_facture'] . "</option>";
                                    }
                                    ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="numDom_edit" name="numDom_edit" class="col-form-label">Numéro de
                                domiciliation:</label>
                            <input type="text" class="form-control" name="numDom_edit" id="numDom_edit"
                                value="<?php echo $num_domiciliation; ?>" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nombre_edit" name="nombre_edit" class="col-form-label">Nombre de colis:</label>
                            <input type="numbere_edit" class="form-control" name="nombre_edit" id="nombre_edit"
                                value="<?php echo $nombre_colis; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="pj_dom_edit" name="pj_dom_edit" class="col-form-label">Pièce joint de
                                DOM:</label>
                            <input type="file" class="form-control" name="pj_dom_edit" id="pj_dom_edit"
                                placeholder="Nombre de colis" style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_sce_edit" name="lieu_sce_edit" class="col-form-label">Lieu de
                                scellage:</label>
                            <input type="text" class="form-control" name="lieu_sce_edit" id="lieu_sce_edit"
                                value="<?php echo $lieu_scellage; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="type_colis_edit" name="type_colis_edit" class="col-form-label">Type de
                                colis:</label>
                            <input type="text" class="form-control" name="type_colis_edit" id="type_colis_edit"
                                value="<?php echo $type_colis; ?>" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_emb_edit" name="lieu_emb_edit" class="col-form-label">Lieu
                                d'embarquement:</label>
                            <input type="text" class="form-control" name="lieu_emb_edit" id="lieu_emb_edit"
                                value="<?php echo $lieu_embarquement; ?>" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="declaration_edit" name="declaration_edit" class="col-form-label">Numéro de fiche
                                de
                                déclaration:</label>
                            <input type="text" class="form-control" name="declaration_edit" id="declaration_edit"
                                value="<?php echo $num_fiche_declaration; ?>" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="date_declaration_edit" name="date_declaration_edit" class="col-form-label">Date
                                de
                                fiche de déclaration:</label>
                            <input type="date" class="form-control" name="date_declaration_edit"
                                value="<?php echo $date_fiche_declaration; ?>" id="date_declaration_edit" required
                                style="font-size:90%">
                            <input type="hidden" value="<?php echo $id_data_cc; ?>" id="id_edit" name="id_edit">
                        </div>
                        <div class="col">
                            <label for="pj_declaration_edit" name="pj_declaration_edit" class="col-form-label">Pièce
                                joint de
                                fiche de
                                déclaration:</label>
                            <input type="file" class="form-control" name="pj_declaration_edit" id="pj_declaration_edit"
                                placeholder="Numéro de fiche de déclaration" style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="num_lp3_edit" name="num_lp3_edit" class="col-form-label">Numéro de LP III
                                E:</label>
                            <input type="text" value="<?php echo $num_lp3e; ?>" class="form-control" name="num_lp3_edit"
                                id="num_lp3_edit" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="date_lp3_edit" name="date_lp3_edit" class="col-form-label">Date de création de
                                LP III
                                E:</label>
                            <input type="date" class="form-control" name="date_lp3_edit" id="date_lp3_edit"
                                value="<?php echo $date_lp3e; ?>" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="pj_lp3_edit" name="pj_lp3_edit" class="col-form-label">Pièce joint LP III
                                E:</label>
                            <input type="file" class="form-control" name="pj_lp3_edit" id="pj_lp3_edit"
                                style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="chef_edit" name="chef_edit" class="col-form-label">Chef de Division:</label>
                            <select id="chef_edit" name="chef_edit" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                    
                                    if(!empty($id_agent_chef)) {
                                        $query = "SELECT * FROM agent WHERE id_agent=$id_agent_chef";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            $selected = ($rowSub["id_agent"] == $id_agent_chef) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] . "</option>";
                                        }
                                        
                                    }?>

                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="qualite_edit" name="qualite_edit" class="col-form-label">Responsable de
                                qualité
                                laboratoire:</label>
                            <select id="qualite_edit" name="qualite_edit" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php 
                                if(!empty($id_agent_qualite)){
                                    $query = "SELECT * FROM agent WHERE id_agent=$id_agent_qualite";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            $selected = ($rowSub["id_agent"] == $id_agent_qualite) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] . "</option>";
                                        }
                                }   
                                    
                                        ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="police_edit" name="police_edit" class="col-form-label">Officier de
                                Police:</label>
                            <select id="police_edit" name="police_edit" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php 
                                if(!empty($id_agent_police)) {
                                    $query = "SELECT * FROM agent WHERE id_agent=$id_agent_police";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            $selected = ($rowSub["id_agent"] == $id_agent_police) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] . "</option>";
                                        }
                                }
                                    
                                        ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="douane_edit" name="douane_edit" class="col-form-label">Agent Douanier:</label>
                            <select id="douane_edit" name="douane_edit" placeholder="Choisir ..." autocomplete="off"
                                required style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                if(!empty($id_agent_douane)) {
                                    $query = "SELECT * FROM agent WHERE id_agent=$id_agent_douane";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            $selected = ($rowSub["id_agent"] == $id_agent_douane) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] . "</option>";
                                        }
                                }
                                    
                                        ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="agent_scellage_edit" name="agent_scellage_edit" class="col-form-label">Agent
                                de
                                scellage:</label>
                            <select id="agent_scellage_edit" name="agent_scellage_edit[]" placeholder="Choisir ..."
                                autocomplete="off" required style="font-size:90%" multiple>
                                <option value="">Choisir ...</option>
                                <?php  
                                    if(count($id_agent_scellage)>0) {
                                        $query = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
                                        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de Scellage' AND assiste_agent.id_data_cc=$id_data_cc";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo$rowSub["id_agent"];
                                            $selected = (in_array($rowSub["id_agent"], $id_agent_scellage)) ? "selected" : "";
                                            echo "<option value='" . $rowSub['id_agent'] ."'$selected>". $rowSub['nom_agent'] . "</option>";
                                        }
                                    }
                                       
                                        ?>
                            </select>
                        </div>
                    </div></br>
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

    new TomSelect("#expediteur_edit", selectOptions);
    new TomSelect("#destination_edit", selectOptions);
    new TomSelect("#facture_edit", selectOptions);
    new TomSelect("#chef_edit", selectOptions);
    new TomSelect("#qualite_edit", selectOptions);
    new TomSelect("#police_edit", selectOptions);
    new TomSelect("#douane_edit", selectOptions);
    new TomSelect("#agent_scellage_edit", selectOptions);

};
</script>