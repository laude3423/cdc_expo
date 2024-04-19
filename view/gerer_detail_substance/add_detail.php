<?php 
include "../db_connect.php";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--Bootstrap CSS-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="../shared/stylees.css">

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <!-- TomSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <title>Ministere des mines</title>
    <?php 
    include "../shared/navBar.php";
    ?>


</head>

<body>
    <div class="container">
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
            aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle substance</h1>
                        <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="substance" name="substance" id="substance" class="col-form-label">Nom de la
                                    substance:</label></br>
                                <select id="select-substance" placeholder="Choisir ..." autocomplete="off"
                                    style="width: 100%;">
                                    <?php    
                                $query = "SELECT id_substance, nom_substance FROM substance";
                                $stmt = $conn->prepare($query);
                                $stmt->execute();
                                $resu = $stmt->get_result();
                                
                                while ($rowSub = $resu->fetch_assoc()) {
                                    echo "<option value='" . $rowSub['id_substance'] . "'>" . $rowSub['nom_substance'] . "</option>";
                                }
                                ?>
                                </select>
                            </div>
                            <div class="mb-3 tom-select-form">
                                <label for="granulo" name="granulo" class="col-form-label">Granulomètrie:</label>
                                <select id="granulo" placeholder="Choisir ..." autocomplete="off">
                                    <option value="">Choisir ...</option>
                                    <?php    
                                    $query = "SELECT id_granulo, nom_granulo FROM granulo";
                                    $stmt = $conn->prepare($query);
                                    $stmt->execute();
                                    $resu = $stmt->get_result();
                                    
                                    while ($rowSub = $resu->fetch_assoc()) {
                                        echo "<option value='" . $rowSub['id_granulo'] . "'>" . $rowSub['nom_granulo'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <select id="select-beast" placeholder="Select a person..." autocomplete="off">
                                    <option value="">Select a person...</option>
                                    <option value="4">Thomas Edison</option>
                                    <option value="1">Nikola</option>
                                    <option value="3">Nikola Tesla</option>
                                    <option value="5">Arnold Schwarzenegger</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="prix" name="prix" class="col-form-label">Prix unitaire:</label>
                                <input type="text" class="form-control" name="prix" id="prix"
                                    placeholder="prix unitaire" required style="font-size:90%">
                                <input type="hidden" id="id" name="id">
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-check">
                                    <input class="form-check-input" type="radio" name="type" id="cin" value="cin"
                                        required>
                                    <label class="form-check-label" id="label_cin" for="cin">
                                        Carat
                                    </label>
                                </div>
                                <div class="col-md-6 form-check">
                                    <input class="form-check-input" type="radio" name="type" id="csr" value="csr">
                                    <label class="form-check-label" id="label_csr" for="csr">
                                        Gramme
                                    </label>
                                </div>
                                <div class="col-md-6 form-check">
                                    <input class="form-check-input" type="radio" name="type" id="csr" value="csr">
                                    <label class="form-check-label" id="label_csr" for="csr">
                                        Kilogramme
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary"
                                    onclick="closeModal()">Close</button>
                                <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- TomSelect JS -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <!-- Votre script personnalisé -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Sélectionnez le modal par son ID
        var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));

        // Affichez le modal
        myModal.show();

        new TomSelect(".tom-select-form #granulo", {
            create: true,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    });
    </script>

</body>

</html>