<?php
include_once('../../../scripts/db_connect.php');
// require_once('../scripts/session_admin.php');
// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_data_cc = isset($_POST["id_data_cc"]) ? intval($_POST["id_data_cc"]) : null;
    $id_substance = isset($_POST["id_substance"]) ? intval($_POST["id_substance"]) : null;
    $id_couleur_substance = isset($_POST["id_couleur_substance"]) ? intval($_POST["id_couleur_substance"]) : null;
    $unite_poids_facture = isset($_POST["unite_poids_facture"]) ? ($_POST["unite_poids_facture"] === "ct" ? "g" : $_POST["unite_poids_facture"]) : null;

    if($_POST["unite_poids_facture"] === 'ct'){
        $poids_facture = floatval($_POST["poids_facture"])*0.2; //poids en gramme
    }else {
        $poids_facture = isset($_POST["poids_facture"]) ? htmlspecialchars($_POST["poids_facture"]) : null;
    }
    $prix_unitaire_facture = isset($_POST["prix_unitaire_facture"]) ? htmlspecialchars($_POST["prix_unitaire_facture"]) : null;
    $granulo_facture = isset($_POST["granulo_facture"]) ? intval($_POST["granulo_facture"]) : null;
    $id_degre_couleur = isset($_POST["id_degre_couleur"]) ? intval($_POST["id_degre_couleur"]) : null;
    $id_transparence = isset($_POST["id_transparence"]) ? intval($_POST["id_transparence"]) : null;
    $id_durete = isset($_POST["id_durete"]) ? intval($_POST["id_durete"]) : null;
    $id_categorie = isset($_POST["id_categorie"]) ? intval($_POST["id_categorie"]) : null;
    $id_forme_substance = isset($_POST["id_forme_substance"]) ? intval($_POST["id_forme_substance"]) : null;
    $id_dimension_diametre = isset($_POST["id_dimension_diametre"]) ? intval($_POST["id_dimension_diametre"]) : null;
    $id_lp1_info = isset($_POST["id_lp1_info"]) ? intval($_POST["id_lp1_info"]) : null;




    if (isset($_POST["num_ae_pe"]) && !empty($_POST["num_ae_pe"])) {
        $num_ae_pe = $_POST["num_ae_pe"];
        $sql_num_ae_pe = "UPDATE pre SET num_ae_ou_pe = ? WHERE id_pre = ?";
        $stmt_num_ae_pe = $conn->prepare($sql_num_ae_pe);
        $stmt_num_ae_pe->bind_param("si", $num_ae_pe, $id_pre);
        $stmt_num_ae_pe->execute();
    }


}