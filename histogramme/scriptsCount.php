<?php
$labels = [];
$data = [];

// 1 user
$sql = "SELECT count(*) AS count FROM users";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Utilisateur';  // Utilisez [] pour ajouter au tableau
    $data[] = $row['count'];    // Utilisez [] pour ajouter au tableau
}

// 2 cc
$sql = "SELECT count(num_cc) AS count FROM data_cc WHERE num_cc IS NOT NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'CDC';
    $data[] = $row['count'];
}

// 3 controle
$sql = "SELECT count(num_pv_controle) AS count FROM data_cc WHERE num_cc IS NOT NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Contrôle';
    $data[] = $row['count'];
}

// 4 scellage
$sql = "SELECT count(num_pv_scellage) AS count FROM data_cc WHERE num_cc IS NOT NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Scellage';
    $data[] = $row['count'];
}

// 5 ancien LP
$sql = "SELECT count(id_ancien_lp) AS count FROM ancien_lp WHERE numero_lp IS NOT NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Ancien_lp';
    $data[] = $row['count'];
}

// 6 Facture
$sql = "SELECT count(num_facture) AS count FROM data_cc WHERE num_cc IS NOT NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Facture';
    $data[] = $row['count'];
}

// 7 Attestation
$sql = "SELECT count(num_attestation) AS count FROM data_cc WHERE num_cc IS NOT NULL";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Attestation';
    $data[] = $row['count'];
}

// 8 Societe exp
$sql = "SELECT count(*) AS count FROM societe_expediteur";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Expéditeur';
    $data[] = $row['count'];
}

// 9 Societe importateur
$sql = "SELECT count(*) AS count FROM societe_importateur";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Importateur';
    $data[] = $row['count'];
}

// 10 Agent de scellage
$sql = "SELECT count(*) AS count FROM agent";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Agent';
    $data[] = $row['count'];
}

// 11 Substance
$sql = "SELECT count(*) AS count FROM substance";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Substance';
    $data[] = $row['count'];
}

// 12 Direction
$sql = "SELECT count(*) AS count FROM direction";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Direction';
    $data[] = $row['count'];
}

// Encodez les tableaux en JSON
$labels_json = json_encode($labels);
$data_json = json_encode($data);
?>