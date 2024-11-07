<?php require_once('../../scripts/db_connect.php');
    require('../../scripts/session.php');
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql2 = "SELECT * FROM `visa` WHERE id_visa=?";
    
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $resu2 = $stmt2->get_result();
        $row2 = $resu2->fetch_assoc();

        $stmt2->close();
    }

 ?>
<div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false"
    aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Insérer scan des PV</h1>
                <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="./insert_scan.php" method="post" enctype="multipart/form-data"
                    onsubmit="return validateFile()">
                    <div class="mb-3">
                        <label for="passeport" class="col-form-label">Scan du certificat de conformité avec numéro de
                            visa:</label>
                        <input type="hidden" value="<?php echo $row2['id_visa']; ?>" name="idval" id="idval">
                        <input type="hidden" value="<?php echo $row2['numero_visa']; ?>" name="num_visa" id="num_visa">
                        <input type="file" class="form-control" name="scan_visa" id="scan_visa" required
                            style="font-size:90%">
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
</script>