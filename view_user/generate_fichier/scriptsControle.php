<?php
require_once('../../scripts/db_connect.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Viewer</title>
    <style>
    /* Basic styling for the modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        border-radius: 1rem;
        max-width: 600px;
        text-align: center;
    }

    @media only screen and (max-width: 768px) {
        iframe {
            height: 400px;
            /* Ajustez la hauteur pour les petits écrans */
        }
    }

    .close-btn {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        margin-top: 20px;
        border: none;
        cursor: pointer;
        float: right;
    }
    </style>
</head>

<body>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <div class="text-center">
                <img src="/logo/logo.png" alt="img" width="20%" height="20%">
            </div>
            <p>Bonjour!</p>
            <p>Ce Procès-Verbal de Contrôle est délivré par le Ministère des Mines.</p>
        </div>
    </div>
    <div id="fileLink" style="display:none;">
        <!-- PHP will inject the file link here -->
        <?php
    if(isset($_GET['id_data_cc'])) {
         $id = $_GET['id_data_cc'];
        $sql = "SELECT lien_pv_controle FROM data_cc WHERE id_data_cc=$id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if(!empty($row['lien_pv_controle'])) {
            $lien_pv_controle = $row["lien_pv_controle"];
            $pdfFilePath = $lien_pv_controle;
                include "../cdc/convert.php";
        }else{
            "Aucun scan correspondant";
        }
        
    }
    ?>
    </div>

    <script>
    window.onload = function() {
        document.getElementById('myModal').style.display = 'block';
        setTimeout(closeModal, 3500);
    };

    function closeModal() {
        document.getElementById('myModal').style.display = 'none';
        document.getElementById('fileLink').style.display = 'block';
    }
    </script>

</body>

</html>