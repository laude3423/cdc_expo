<?php
require_once('../../scripts/db_connect.php');

// if (isset($_GET['id'])) {
    $id = 1;

    $sql = "SELECT datacc.*, societe_imp.*, societe_exp.*
        FROM data_cc datacc
        LEFT JOIN societe_importateur societe_imp ON datacc.id_societe_importateur= societe_imp.id_societe_importateur
        LEFT JOIN societe_expediteur societe_exp ON datacc.id_societe_expediteur= societe_exp.id_societe_expediteur
        WHERE id_data_cc = $id";
        $sql1 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Chef de Division Exportation Minière' AND assiste_agent.id_data_cc=$id";
        $sql2 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Responsable qualité Laboratoire des Mines' AND assiste_agent.id_data_cc=$id";
        $sql3 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Agent de Scellage' AND assiste_agent.id_data_cc=$id";
        $sql4 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Douanier' AND assiste_agent.id_data_cc=$id";
        $sql5 = "SELECT ag.*, assiste_agent.* FROM pv_agent_assister assiste_agent
        LEFT JOIN agent ag ON assiste_agent.id_agent=ag.id_agent WHERE ag.fonction_agent='Officier de Police' AND assiste_agent.id_data_cc=$id";

        $result = mysqli_query($conn, $sql);
        $result1 = mysqli_query($conn, $sql1);
        $result2 = mysqli_query($conn, $sql2);
        $result3 = mysqli_query($conn, $sql3);
        $result4 = mysqli_query($conn, $sql4);
        $result5 = mysqli_query($conn, $sql5);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        $data1 = mysqli_fetch_assoc($result1);
        $data2 = mysqli_fetch_assoc($result2);
        $data3 = mysqli_fetch_assoc($result3);
        $data4 = mysqli_fetch_assoc($result4);
        $data5 = mysqli_fetch_assoc($result5);
        
        $combined_data = array(
            'datacc' => $data,
            'agent' => $data1,
            'agent1' => $data2,
            'agent2' => $data3,
            'agent3' => $data4,
            'agent4' => $data5
        );

        echo json_encode($combined_data);
    } else {
        echo json_encode(array('error' => 'Erreur lors de la récupération des : ' . mysqli_error($conn)));
    }
// } else {
//     echo json_encode(array('error' => 'ID non spécifié.'));
// }
?>