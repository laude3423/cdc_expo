<?php
require_once('../../scripts/db_connect.php');
session_start();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT vi.*, vo.*, agent.* FROM `visa` AS vi 
            LEFT JOIN vol AS vo ON vo.id_vol = vi.id_vol 
            LEFT JOIN agent_controle AS agent ON agent.id_agent_controle = vi.id_agent_controle
            WHERE id_visa = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resu = $stmt->get_result();
    $row = $resu->fetch_assoc();

    $stmt->close();
    $id_vol = $row['id_vol'];
} else {
    echo "vide";
}
?>

<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Modifier visa</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="./update.php" method="post" enctype="multipart/form-data"
                    onsubmit="return validateFile()">
                    <?php if ($row['accompagne'] == 'OUI') { ?>
                    <!-- Section Accompagné -->
                    <div class="mb-3">
                        <label for="civilite_edit" class="col-form-label">Civilité:</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="civilite_edit" id="civilite_v" value="M"
                                <?php echo ($row['civilite'] == 'M') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="civilite_v">Mademoiselle</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="civilite_edit" id="civilite_a" value="MM"
                                <?php echo ($row['civilite'] == 'MM') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="civilite_a">Madame</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="civilite_edit" id="civilite_m" value="MS"
                                <?php echo ($row['civilite'] == 'MS') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="civilite_m">Monsieur</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="nom_edit" class="col-form-label">Nom de l'expéditeur:</label>
                            <input type="text" value="<?php echo $row['nom_porteur']; ?>" class="form-control"
                                name="nom_edit" id="nom_edit" placeholder="Nom de l'agent" style="font-size:90%"
                                required>
                        </div>
                        <div class="col">
                            <label for="prenom_edit" class="col-form-label">Prénom(s) de l'expéditeur:</label>
                            <input type="text" value="<?php echo $row['prenom_porteur']; ?>" class="form-control"
                                name="prenom_edit" id="prenom_edit" placeholder="Prénom de l'agent"
                                style="font-size:90%" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="passeport_edit" class="col-form-label">Passeport:</label>
                            <input type="text" value="<?php echo $row['numero_passeport']; ?>" class="form-control"
                                name="passeport_edit" id="passeport_edit" placeholder="Numéro du passeport"
                                style="font-size:90%" required>
                        </div>
                        <div class="col">
                            <label for="scan_passeport_edit" class="col-form-label">Scan du passeport
                                (facultatif):</label>
                            <input type="file" class="form-control" name="scan_passeport_edit" id="scan_passeport_edit"
                                placeholder="Scan du passeport" style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="num_vol_edit" class="col-form-label">Numéro du vol:</label>
                            <select id="num_vol_edit" class="form-select" name="num_vol_edit" placeholder="Choisir ..."
                                autocomplete="off" style="font-size:90%" onchange="updateFlightDetails1()" required>
                                <option value="">Choisir ...</option>
                                <?php
                                $query = "SELECT * FROM vol";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                while ($rowSub = $resu->fetch_assoc()) {
                                    $selected = ($rowSub["id_vol"] == $id_vol) ? "selected" : "";
                                    echo "<option value='" . $rowSub['id_vol'] . "' data-compagnie='" . $rowSub['nom_compagnie'] . "' data-escale='" . $rowSub['destination_vol'] . "' $selected>" . $rowSub['numero_vol'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col">
                            <label for="compagnie_edit" class="col-form-label">Nom de la compagnie:</label>
                            <input type="text" class="form-control" name="compagnie_edit" id="compagnie_edit"
                                value="<?php echo $row['nom_compagnie']; ?>" placeholder="Nom de la compagnie"
                                style="font-size:90%" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="escale_edit" class="col-form-label">Escale:</label>
                            <input type="text" class="form-control" name="escale_edit" id="escale_edit"
                                placeholder="Escale" value="<?php echo $row['destination_vol']; ?>"
                                style="font-size:90%" readonly>
                        </div>
                        <div class="col">
                            <label for="date_depart_edit" class="col-form-label">Date de départ:</label>
                            <input type="date" class="form-control" name="date_depart_edit" id="date_depart_edit"
                                value="<?php echo $row['date_depart']; ?>" style="font-size:90%" required>
                        </div>
                    </div>
                    <?php } else { ?>
                    <!-- Section Fret -->
                    <div class="row">
                        <div class="col">
                            <label for="nom_fret_edit" class="col-form-label">Numéro du fret:</label>
                            <select id="nom_fret_edit" name="nom_fret_edit" class="form-select"
                                placeholder="Choisir ..." autocomplete="off" style="font-size:90%"
                                onchange="updateFlightDetails_fret()" required>
                                <option value="">Choisir ...</option>
                                <?php
                                $query = "SELECT * FROM fret";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                while ($rowSub = $resu->fetch_assoc()) {
                                    $selected = ($rowSub["id_fret"] == $row['id_fret']) ? "selected" : "";
                                    echo "<option value='" . $rowSub['id_fret'] . "' data-fret='" . $rowSub['lieu_depart'] . "' $selected>" . $rowSub['nom_fret'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- <div class="col">
                            <label for="lieu_depart_edit" class="col-form-label">Lieu de départ:</label>
                            <input type="text" class="form-control" name="lieu_depart_edit" id="lieu_depart_edit"
                                placeholder="Lieu de départ" style="font-size:90%" readonly>
                        </div> -->
                        <div class="col">
                            <label for="date_depart_edit_fret" class="col-form-label">Date de départ:</label>
                            <input type="date" class="form-control" name="date_depart_edit_fret"
                                id="date_depart_edit_fret" value="<?php echo $row['date_depart']; ?>"
                                style="font-size:90%" required>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col">
                            <label for="matricule_edit" class="col-form-label">Matricule du responsable:</label>
                            <select id="matricule_edit" class="form-select" name="matricule_edit"
                                placeholder="Choisir ..." autocomplete="off" style="font-size:90%"
                                onchange="updateFlightDetails_agent1()" required>
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
                        <div class="col">
                            <div class="col">
                                <label for="nom_agent_edit" class="col-form-label">Nom du responsable:</label>
                                <input type="text" class="form-control" name="nom_agent_edit" id="nom_agent_edit"
                                    value="<?php echo $row['nom_agent']; ?>" placeholder="Nom du responsable"
                                    style="font-size:90%" readonly>
                            </div>
                        </div>
                    </div>
                    <?php if($row['accompagne']=='NON'){ ?>
                    <div class="row">
                        <div class="col">
                            <label for="prenom_agent_edit" class="col-form-label">Prénom(s) de l'agent:</label>
                            <input type="text" class="form-control" name="prenom_agent_edit" id="prenom_agent_edit"
                                value="<?php echo $row['prenom_agent']; ?>" placeholder="Nom du responsable"
                                style="font-size:90%" readonly>
                        </div>
                        <div class="col">
                            <label for="numero_cc_edit" class="col-form-label">Numéro du C.C:</label>
                            <input type="text" class="form-control" name="numero_cc_edit" id="numero_cc_edit"
                                value="<?php echo $row['numero_cc']; ?>" style="font-size:90%" required>
                            <?php    
                                    // $query = "SELECT * FROM data_cc";
                                    // $stmt = $conn->prepare($query);
                                    // $stmt->execute();
                                    // $resu = $stmt->get_result();
                                    // while ($rowSub = $resu->fetch_assoc()) {
                                    //     $selected = ($rowSub["id_data_cc"] == $id_data_cc) ? "selected" : "";
                                    //     echo "<option value='" . $rowSub['id_data_cc'] ."' $selected>". $rowSub['num_cc'] . "</option>";
                                    // }
                                    ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="date_cc_edit" class="col-form-label">Date du C.C:</label>
                            <input type="date" class="form-control" name="date_cc_edit" id="date_cc_edit"
                                value="<?php echo $row['date_cc']; ?>" style="font-size:90%" required>
                        </div>
                        <div class="col">
                            <label for="scan_cc_edit_a" class="col-form-label">Date du C.C (facultatif):</label>
                            <input type="file" class="form-control" name="scan_cc_edit_a" id="scan_cc_edit_a" ?
                                style="font-size:90%" required>
                        </div>
                    </div>
                    <?php }else { ?>
                    <div class="row">
                        <div class="col">
                            <label for="prenom_agent_edit" class="col-form-label">Prénom(s) de l'agent:</label>
                            <input type="text" class="form-control" name="prenom_agent_edit" id="prenom_agent_edit"
                                value="<?php echo $row['prenom_agent']; ?>" placeholder="Nom du responsable"
                                style="font-size:90%" readonly>
                        </div>
                        <div class="col">
                            <label for="numero_cc_edit" class="col-form-label">Numéro du C.C:</label>
                            <input type="text" class="form-control" name="numero_cc_edit" id="numero_cc_edit"
                                value="<?php echo $row['numero_cc']; ?>" style="font-size:90%" required>
                            <?php    
                                // $query = "SELECT * FROM data_cc";
                                // $stmt = $conn->prepare($query);
                                // $stmt->execute();
                                // $resu = $stmt->get_result();
                                // while ($rowSub = $resu->fetch_assoc()) {
                                //     $selected = ($rowSub["id_data_cc"] == $id_data_cc) ? "selected" : "";
                                //     echo "<option value='" . $rowSub['id_data_cc'] ."' $selected>". $rowSub['num_cc'] . "</option>";
                                // }
                                ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="date_cc_edit" class="col-form-label">Date du C.C:</label>
                            <input type="date" class="form-control" name="date_cc_edit" id="date_cc_edit"
                                value="<?php echo $row['date_cc']; ?>" style="font-size:90%" required>
                        </div>
                        <div class="col">
                            <label for="scan_cc_edit" class="col-form-label">Scan du C.C(facultatif):</label>
                            <input type="file" class="form-control" name="scan_cc_edit" id="scan_cc_edit"
                                style="font-size:90%">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="facture_edit" class="col-form-label">Numero de la facture:</label>
                            <input type="text" class="form-control" value="<?php echo $row['numero_facture']; ?>"
                                placeholder="Numéro de la facture" name="facture_edit" id="facture_edit"
                                style="font-size:90%">
                        </div>
                        <div class="col">
                            <label for="scan_facture_A" class="col-form-label">Scan de la facture(facultatif):</label>
                            <input type="file" class="form-control" name="scan_facture_A" id="scan_facture_A"
                                style="font-size:90%" accept=".pdf">
                        </div>
                    </div>
                    <?php } ?>
                    <input type="hidden" value="<?php echo $row['accompagne']; ?>" id="type_trasport"
                        name="type_trasport">
                    <input type="hidden" value="<?php echo $row['id_visa']; ?>" id="id_edit" name="id_edit">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                        <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                    </div>
                </form>
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
        try {
            var selectElement = $('#id_data_edit');
            if (selectElement.length) {
                new TomSelect(selectElement[0], {
                    // Options de Tom Select
                });
            } else {
                console.error('Element #my-select not found');
            }
        } catch (error) {
            console.error('An error occurred:', error);
        }
    });

    function validateFile() {
        const fileInput = document.getElementById('scan_visa');
        const filePath = fileInput.value;
        const allowedExtensions = /(\.pdf)$/i;
        const fileSize = fileInput.files[0].size;
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes

        if (!allowedExtensions.exec(filePath)) {
            alert('Veuillez télécharger un fichier ayant les extensions .pdf uniquement');
            fileInput.value = '';
            return false;
        } else if (fileSize > maxSize) {
            alert('La taille du fichier doit être inférieure à 2 Mo');
            fileInput.value = '';
            return false;
        }
        return true;
    }

    function updateFlightDetails_fret() {
        var selectVol = document.getElementById('nom_fret_edit');
        var fretInput = document.getElementById('lieu_depart_edit');

        var selectedOption = selectVol.options[selectVol.selectedIndex];
        var fret = selectedOption.getAttribute('data-fret');

        // Mettre à jour les champs fret et escale
        fretInput.value = fret;
    }
    </script>