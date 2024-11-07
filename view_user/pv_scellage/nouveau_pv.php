<?php
include_once('../../scripts/db_connect.php');
    if (isset($_GET['id'])) {
        $id_data_cc = $_GET['id'];
    }
?>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouveau PV de scellage</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../pv_scellage/lister.php" method="post">
                    <div class="row">
                        <div class="col">
                            <label for="nombre" name="nombre" class="col-form-label">Nombre de colis:</label>
                            <input type="number" class="form-control" name="nombre" id="nombre"
                                placeholder="Nombre de colis" required style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="type_colis" name="type_colis" class="col-form-label">Type de colis:</label>
                            <input type="text" class="form-control" name="type_colis" id="type_colis"
                                placeholder="Type de colis" required style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="lieu_scellage" name="lieu_scellage" class="col-form-label">Lieu de
                                Scellage:</label>
                            <input type="text" class="form-control" name="lieu_scellage" id="lieu_scellage"
                                placeholder="Lieu de scellage" required style="font-size:90%">
                            <input type="hidden" id="id" name="id" value="<?php echo $id_data_cc; ?>">
                        </div>
                        <div class="col">
                            <label for="police" name="police" class="col-form-label">Officier de Police:</label>
                            <select id="police" name="police" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                            $query = "SELECT * FROM agent WHERE fonction_agent='Officier de police judiciaire'";
                                            $stmt = $conn->prepare($query);
                                            $stmt->execute();
                                            $resu = $stmt->get_result();
                                            
                                            while ($rowSub = $resu->fetch_assoc()) {
                                                echo "<option value='" . $rowSub['id_agent'] ."'>" . $rowSub['nom_agent'] .' '. $rowSub['prenom_agent']."</option>";
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
                                            echo "<option value='" . $rowSub['id_agent'] ."'>" . $rowSub['nom_agent'] .' '. $rowSub['prenom_agent']. "</option>";
                                        }
                                        ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="agent_scellage" name="agent_scellage" class="col-form-label">Agent de
                                scellage:</label>
                            <select id="agent_scellage" name="agent_scellage[]" placeholder="Choisir ..."
                                autocomplete="off" style="font-size:90%" multiple>
                                <option value="">Choisir ...</option>
                                <?php    
                                        $query = "SELECT * FROM agent WHERE fonction_agent='Agent de scellage'";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo "<option value='" . $rowSub['id_agent'] ."'>" . $rowSub['nom_agent'] .' '. $rowSub['prenom_agent']. "</option>";
                                        }
                                        ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-3">
                            <label for="fraude" name="fraude" class="col-form-label">Agent de l'Agence Nationale
                                Anti-Fraude:</label>
                            <select id="fraude" name="fraude" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                        $query = "SELECT * FROM agent WHERE fonction_agent=\"Agent de l'Agence Nationale Anti-Fraude\"";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();

                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo "<option value='" . $rowSub['id_agent'] ."'>" . $rowSub['nom_agent'] . ' ' . $rowSub['prenom_agent'] . "</option>";
                                        }

                                        ?>
                            </select>
                        </div>
                        <div class="col mb-3">
                            <label for="qualite" name="qualite" class="col-form-label">Responsable de la qualité du
                                Laboratoire:</label>
                            <select id="qualite" name="qualite" placeholder="Choisir ..." autocomplete="off" required
                                style="font-size:90%">
                                <option value="">Choisir ...</option>
                                <?php    
                                        $query = "SELECT * FROM agent WHERE fonction_agent='Responsable de la qualité du Laboratoire des Mines'";
                                        $stmt = $conn->prepare($query);
                                        $stmt->execute();
                                        $resu = $stmt->get_result();
                                        
                                        while ($rowSub = $resu->fetch_assoc()) {
                                            echo "<option value='" . $rowSub['id_agent'] ."'>" . $rowSub['nom_agent'] .' '.$rowSub['prenom_agent']. "</option>";
                                        }
                                        ?>
                            </select>
                        </div>
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

    new TomSelect("#agent_scellage", selectOptions);
    new TomSelect("#douane", selectOptions);
    new TomSelect("#police", selectOptions);
    new TomSelect("#fraude", selectOptions);
    new TomSelect("#qualite", selectOptions);

};
</script>