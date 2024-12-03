<?php
$labels = [];
$data = [];

// 1 user
$sql = "SELECT count(*) AS count FROM users WHERE id_groupe = 4";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Utilisateur';  // Utilisez [] pour ajouter au tableau
    $data[] = $row['count'];    // Utilisez [] pour ajouter au tableau
}

// 2 Autorisation
$sql = "SELECT count(numero_autorisation) AS count FROM autorisation";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Autorisation';
    $data[] = $row['count'];
}

// 3 Visa
$sql = "SELECT count(numero_visa) AS count FROM visa";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'visa';
    $data[] = $row['count'];
}

// 4 scellage
$sql = "SELECT count(id_pays) AS count FROM pays";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'pays';
    $data[] = $row['count'];
}

// 5 Continent
$sql = "SELECT count(id_continent) AS count FROM continent";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Continent';
    $data[] = $row['count'];
}

// 6 Agent de controle
$sql = "SELECT count(id_agent_controle) AS count FROM agent_controle";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Agent';
    $data[] = $row['count'];
}

// 7 Vol
$sql = "SELECT count(id_vol) AS count FROM vol";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Vol';
    $data[] = $row['count'];
}
// 8 Fret
$sql = "SELECT count(id_fret) AS count FROM fret";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $labels[] = 'Fret';
    $data[] = $row['count'];
}
// Encodez les tableaux en JSON
$labels_json = json_encode($labels);
$data_json = json_encode($data);
?>