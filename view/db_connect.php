<?php 
    $servername = "localhost";
    $username = "cdc_user";
    $password = "5D9eyAEPCx0M8rgJ";
    $dbname = "cdc_db";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if(!$conn){
        die(("Connection refusé".mysqli_connect_error()));
    }
    //echo "Base de données connecté avec succès"

?>