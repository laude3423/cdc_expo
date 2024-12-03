<?php 
require_once('../../scripts/db_connect.php');
require('../../scripts/session.php');
require_once('../../scripts/session_actif.php');
$countries = [
    ['name' => 'Afghanistan', 'code' => '+93', 'length' => 9],
    ['name' => 'Albania', 'code' => '+355', 'length' => 9],
    ['name' => 'Algeria', 'code' => '+213', 'length' => 9],
    ['name' => 'Andorra', 'code' => '+376', 'length' => 6],
    ['name' => 'Angola', 'code' => '+244', 'length' => 9],
    ['name' => 'Argentina', 'code' => '+54', 'length' => 10],
    ['name' => 'Armenia', 'code' => '+374', 'length' => 8],
    ['name' => 'Australia', 'code' => '+61', 'length' => 9],
    ['name' => 'Austria', 'code' => '+43', 'length' => 10],
    ['name' => 'Azerbaijan', 'code' => '+994', 'length' => 9],
    ['name' => 'Bahrain', 'code' => '+973', 'length' => 8],
    ['name' => 'Bangladesh', 'code' => '+880', 'length' => 10],
    ['name' => 'Belarus', 'code' => '+375', 'length' => 9],
    ['name' => 'Belgium', 'code' => '+32', 'length' => 9],
    ['name' => 'Belize', 'code' => '+501', 'length' => 7],
    ['name' => 'Benin', 'code' => '+229', 'length' => 9],
    ['name' => 'Bhutan', 'code' => '+975', 'length' => 8],
    ['name' => 'Bolivia', 'code' => '+591', 'length' => 8],
    ['name' => 'Bosnia and Herzegovina', 'code' => '+387', 'length' => 8],
    ['name' => 'Botswana', 'code' => '+267', 'length' => 8],
    ['name' => 'Brazil', 'code' => '+55', 'length' => 11],
    ['name' => 'Brunei', 'code' => '+673', 'length' => 7],
    ['name' => 'Bulgaria', 'code' => '+359', 'length' => 9],
    ['name' => 'Burkina Faso', 'code' => '+226', 'length' => 8],
    ['name' => 'Burundi', 'code' => '+257', 'length' => 8],
    ['name' => 'Cambodia', 'code' => '+855', 'length' => 9],
    ['name' => 'Cameroon', 'code' => '+237', 'length' => 9],
    ['name' => 'Canada', 'code' => '+1', 'length' => 10],
    ['name' => 'Cape Verde', 'code' => '+238', 'length' => 7],
    ['name' => 'Central African Republic', 'code' => '+236', 'length' => 8],
    ['name' => 'Chad', 'code' => '+235', 'length' => 9],
    ['name' => 'Chile', 'code' => '+56', 'length' => 9],
    ['name' => 'China', 'code' => '+86', 'length' => 11],
    ['name' => 'Colombia', 'code' => '+57', 'length' => 10],
    ['name' => 'Comoros', 'code' => '+269', 'length' => 7],
    ['name' => 'Congo (Brazzaville)', 'code' => '+242', 'length' => 9],
    ['name' => 'Congo (Kinshasa)', 'code' => '+243', 'length' => 9],
    ['name' => 'Costa Rica', 'code' => '+506', 'length' => 8],
    ['name' => 'Croatia', 'code' => '+385', 'length' => 9],
    ['name' => 'Cuba', 'code' => '+53', 'length' => 8],
    ['name' => 'Cyprus', 'code' => '+357', 'length' => 8],
    ['name' => 'Czech Republic', 'code' => '+420', 'length' => 9],
    ['name' => 'Denmark', 'code' => '+45', 'length' => 8],
    ['name' => 'Djibouti', 'code' => '+253', 'length' => 6],
    ['name' => 'Dominica', 'code' => '+1-767', 'length' => 10],
    ['name' => 'Dominican Republic', 'code' => '+1-809', 'length' => 10],
    ['name' => 'East Timor', 'code' => '+670', 'length' => 8],
    ['name' => 'Ecuador', 'code' => '+593', 'length' => 9],
    ['name' => 'Egypt', 'code' => '+20', 'length' => 10],
    ['name' => 'El Salvador', 'code' => '+503', 'length' => 8],
    ['name' => 'Equatorial Guinea', 'code' => '+240', 'length' => 9],
    ['name' => 'Eritrea', 'code' => '+291', 'length' => 7],
    ['name' => 'Estonia', 'code' => '+372', 'length' => 8],
    ['name' => 'Eswatini', 'code' => '+268', 'length' => 7],
    ['name' => 'Ethiopia', 'code' => '+251', 'length' => 9],
    ['name' => 'Fiji', 'code' => '+679', 'length' => 7],
    ['name' => 'Finland', 'code' => '+358', 'length' => 9],
    ['name' => 'France', 'code' => '+33', 'length' => 9],
    ['name' => 'Gabon', 'code' => '+241', 'length' => 9],
    ['name' => 'Gambia', 'code' => '+220', 'length' => 7],
    ['name' => 'Georgia', 'code' => '+995', 'length' => 9],
    ['name' => 'Germany', 'code' => '+49', 'length' => 10],
    ['name' => 'Ghana', 'code' => '+233', 'length' => 9],
    ['name' => 'Greece', 'code' => '+30', 'length' => 10],
    ['name' => 'Grenada', 'code' => '+1-473', 'length' => 10],
    ['name' => 'Guatemala', 'code' => '+502', 'length' => 8],
    ['name' => 'Guinea', 'code' => '+224', 'length' => 9],
    ['name' => 'Guinea-Bissau', 'code' => '+245', 'length' => 7],
    ['name' => 'Guyana', 'code' => '+592', 'length' => 7],
    ['name' => 'Haiti', 'code' => '+509', 'length' => 8],
    ['name' => 'Honduras', 'code' => '+504', 'length' => 8],
    ['name' => 'Hungary', 'code' => '+36', 'length' => 9],
    ['name' => 'Iceland', 'code' => '+354', 'length' => 7],
    ['name' => 'India', 'code' => '+91', 'length' => 10],
    ['name' => 'Indonesia', 'code' => '+62', 'length' => 10],
    ['name' => 'Iran', 'code' => '+98', 'length' => 10],
    ['name' => 'Iraq', 'code' => '+964', 'length' => 10],
    ['name' => 'Ireland', 'code' => '+353', 'length' => 9],
    ['name' => 'Israel', 'code' => '+972', 'length' => 9],
    ['name' => 'Italy', 'code' => '+39', 'length' => 10],
    ['name' => 'Ivory Coast', 'code' => '+225', 'length' => 8],
    ['name' => 'Jamaica', 'code' => '+1-876', 'length' => 10],
    ['name' => 'Japan', 'code' => '+81', 'length' => 10],
    ['name' => 'Jordan', 'code' => '+962', 'length' => 9],
    ['name' => 'Kazakhstan', 'code' => '+7', 'length' => 10],
    ['name' => 'Kenya', 'code' => '+254', 'length' => 9],
    ['name' => 'Kiribati', 'code' => '+686', 'length' => 5],
    ['name' => 'Kuwait', 'code' => '+965', 'length' => 8],
    ['name' => 'Kyrgyzstan', 'code' => '+996', 'length' => 9],
    ['name' => 'Laos', 'code' => '+856', 'length' => 9],
    ['name' => 'Latvia', 'code' => '+371', 'length' => 8],
    ['name' => 'Lebanon', 'code' => '+961', 'length' => 8],
    ['name' => 'Lesotho', 'code' => '+266', 'length' => 9],
    ['name' => 'Liberia', 'code' => '+231', 'length' => 7],
    ['name' => 'Libya', 'code' => '+218', 'length' => 9],
    ['name' => 'Liechtenstein', 'code' => '+423', 'length' => 7],
    ['name' => 'Lithuania', 'code' => '+370', 'length' => 8],
    ['name' => 'Luxembourg', 'code' => '+352', 'length' => 9],
    ['name' => 'Madagascar', 'code' => '+261', 'length' => 9],
    ['name' => 'Malawi', 'code' => '+265', 'length' => 9],
    ['name' => 'Malaysia', 'code' => '+60', 'length' => 9],
    ['name' => 'Maldives', 'code' => '+960', 'length' => 7],
    ['name' => 'Mali', 'code' => '+223', 'length' => 8],
    ['name' => 'Malta', 'code' => '+356', 'length' => 8],
    ['name' => 'Marshall Islands', 'code' => '+692', 'length' => 7],
    ['name' => 'Mauritania', 'code' => '+222', 'length' => 8],
    ['name' => 'Mauritius', 'code' => '+230', 'length' => 8],
    ['name' => 'Mexico', 'code' => '+52', 'length' => 10],
    ['name' => 'Micronesia', 'code' => '+691', 'length' => 7],
    ['name' => 'Moldova', 'code' => '+373', 'length' => 8],
    ['name' => 'Monaco', 'code' => '+377', 'length' => 8],
    ['name' => 'Mongolia', 'code' => '+976', 'length' => 8],
    ['name' => 'Montenegro', 'code' => '+382', 'length' => 8],
    ['name' => 'Morocco', 'code' => '+212', 'length' => 9],
    ['name' => 'Mozambique', 'code' => '+258', 'length' => 9],
    ['name' => 'Myanmar', 'code' => '+95', 'length' => 9],
    ['name' => 'Namibia', 'code' => '+264', 'length' => 9],
    ['name' => 'Nauru', 'code' => '+674', 'length' => 7],
    ['name' => 'Nepal', 'code' => '+977', 'length' => 10],
    ['name' => 'Netherlands', 'code' => '+31', 'length' => 9],
    ['name' => 'New Zealand', 'code' => '+64', 'length' => 9],
    ['name' => 'Nicaragua', 'code' => '+505', 'length' => 8],
    ['name' => 'Niger', 'code' => '+227', 'length' => 8],
    ['name' => 'Nigeria', 'code' => '+234', 'length' => 10],
    ['name' => 'North Korea', 'code' => '+850', 'length' => 9],
    ['name' => 'North Macedonia', 'code' => '+389', 'length' => 8],
    ['name' => 'Norway', 'code' => '+47', 'length' => 8],
    ['name' => 'Oman', 'code' => '+968', 'length' => 8],
    ['name' => 'Pakistan', 'code' => '+92', 'length' => 10],
    ['name' => 'Palau', 'code' => '+680', 'length' => 7],
    ['name' => 'Palestine', 'code' => '+970', 'length' => 9],
    ['name' => 'Panama', 'code' => '+507', 'length' => 8],
    ['name' => 'Papua New Guinea', 'code' => '+675', 'length' => 7],
    ['name' => 'Paraguay', 'code' => '+595', 'length' => 9],
    ['name' => 'Peru', 'code' => '+51', 'length' => 9],
    ['name' => 'Philippines', 'code' => '+63', 'length' => 10],
    ['name' => 'Poland', 'code' => '+48', 'length' => 9],
    ['name' => 'Portugal', 'code' => '+351', 'length' => 9],
    ['name' => 'Qatar', 'code' => '+974', 'length' => 8],
    ['name' => 'Romania', 'code' => '+40', 'length' => 10],
    ['name' => 'Russia', 'code' => '+7', 'length' => 10],
    ['name' => 'Rwanda', 'code' => '+250', 'length' => 9],
    ['name' => 'Saint Kitts and Nevis', 'code' => '+1-869', 'length' => 10],
    ['name' => 'Saint Lucia', 'code' => '+1-758', 'length' => 10],
    ['name' => 'Saint Vincent and the Grenadines', 'code' => '+1-784', 'length' => 10],
    ['name' => 'Samoa', 'code' => '+685', 'length' => 5],
    ['name' => 'San Marino', 'code' => '+378', 'length' => 10],
    ['name' => 'Sao Tome and Principe', 'code' => '+239', 'length' => 7],
    ['name' => 'Saudi Arabia', 'code' => '+966', 'length' => 9],
    ['name' => 'Senegal', 'code' => '+221', 'length' => 9],
    ['name' => 'Serbia', 'code' => '+381', 'length' => 9],
    ['name' => 'Seychelles', 'code' => '+248', 'length' => 7],
    ['name' => 'Sierra Leone', 'code' => '+232', 'length' => 8],
    ['name' => 'Singapore', 'code' => '+65', 'length' => 8],
    ['name' => 'Slovakia', 'code' => '+421', 'length' => 9],
    ['name' => 'Slovenia', 'code' => '+386', 'length' => 9],
    ['name' => 'Solomon Islands', 'code' => '+677', 'length' => 7],
    ['name' => 'Somalia', 'code' => '+252', 'length' => 7],
    ['name' => 'South Africa', 'code' => '+27', 'length' => 9],
    ['name' => 'South Korea', 'code' => '+82', 'length' => 10],
    ['name' => 'South Sudan', 'code' => '+211', 'length' => 9],
    ['name' => 'Spain', 'code' => '+34', 'length' => 9],
    ['name' => 'Sri Lanka', 'code' => '+94', 'length' => 9],
    ['name' => 'Sudan', 'code' => '+249', 'length' => 9],
    ['name' => 'Suriname', 'code' => '+597', 'length' => 7],
    ['name' => 'Sweden', 'code' => '+46', 'length' => 9],
    ['name' => 'Switzerland', 'code' => '+41', 'length' => 9],
    ['name' => 'Syria', 'code' => '+963', 'length' => 9],
    ['name' => 'Taiwan', 'code' => '+886', 'length' => 9],
    ['name' => 'Tajikistan', 'code' => '+992', 'length' => 9],
    ['name' => 'Tanzania', 'code' => '+255', 'length' => 9],
    ['name' => 'Thailand', 'code' => '+66', 'length' => 9],
    ['name' => 'Togo', 'code' => '+228', 'length' => 8],
    ['name' => 'Tonga', 'code' => '+676', 'length' => 7],
    ['name' => 'Trinidad and Tobago', 'code' => '+1-868', 'length' => 10],
    ['name' => 'Tunisia', 'code' => '+216', 'length' => 8],
    ['name' => 'Turkey', 'code' => '+90', 'length' => 10],
    ['name' => 'Turkmenistan', 'code' => '+993', 'length' => 8],
    ['name' => 'Tuvalu', 'code' => '+688', 'length' => 5],
    ['name' => 'Uganda', 'code' => '+256', 'length' => 9],
    ['name' => 'Ukraine', 'code' => '+380', 'length' => 9],
    ['name' => 'United Arab Emirates', 'code' => '+971', 'length' => 9],
    ['name' => 'United Kingdom', 'code' => '+44', 'length' => 10],
    ['name' => 'United States', 'code' => '+1', 'length' => 10],
    ['name' => 'Uruguay', 'code' => '+598', 'length' => 8],
    ['name' => 'Uzbekistan', 'code' => '+998', 'length' => 9],
    ['name' => 'Vanuatu', 'code' => '+678', 'length' => 7],
    ['name' => 'Vatican City', 'code' => '+379', 'length' => 10],
    ['name' => 'Venezuela', 'code' => '+58', 'length' => 11],
    ['name' => 'Vietnam', 'code' => '+84', 'length' => 10],
    ['name' => 'Yemen', 'code' => '+967', 'length' => 9],
    ['name' => 'Zambia', 'code' => '+260', 'length' => 9],
    ['name' => 'Zimbabwe', 'code' => '+263', 'length' => 9],
];
?>
<?php

$edit_societe_id = isset($_GET['edit_id']) ? $_GET['edit_id'] : null;

    if (isset($_POST['submit'])) {
        $nom = htmlspecialchars($_POST['nom']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $contact = htmlspecialchars($_POST['contact']);
        $email = htmlspecialchars($_POST['email']);
        $destination= htmlspecialchars($_POST['destination']);
        $id_societe_importateur = $_POST['id'];

        // $country_code = $_POST['country_code'];
        // $valid = false;

        // foreach ($countries as $country) {
        //     if ($country['code'] === $country_code) {
        //         $number_length = $country['length'];
        //         $clean_number = preg_replace('/\D/', '', $contact);
        //         if (strlen($clean_number) == $number_length) {
        //             $valid = true;
        //             break;
        //         }
        //     }
        // }

        
            if (empty($id_societe_importateur)) {
                // Insertion d'une nouvelle société
                $sql = "INSERT INTO `societe_importateur`(`nom_societe_importateur`, `adresse_societe_importateur`, `contact_societe_importateur`, `email_societe_importateur`,`pays_destination`, `validation`) VALUES ('$nom','$adresse','$contact','$email', '$destination',  'En attente')";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $_SESSION['toast_message'] = "Insertion réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
            } else {
                // Mise à jour d'une société existante
                $sql = "UPDATE `societe_importateur` SET `nom_societe_importateur`='$nom', `adresse_societe_importateur`='$adresse', `contact_societe_importateur`='$contact', `email_societe_importateur`='$email',`pays_destination`='$destination', `validation`='En attente' WHERE `id_societe_importateur`='$id_societe_importateur'";
                $result = mysqli_query($conn, $sql);

                if ($result) {
                    $_SESSION['toast_message'] = "Modification réussie.";
                    header("Location: ".$_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Erreur d'enregistrement" . mysqli_error($conn);
                }
            }
        // } else {
        //     $_SESSION['toast_message2']="Le numéro de téléphone est invalide.";
        //     header("Location: ".$_SERVER['PHP_SELF']);
        //     exit();
        // }
    }
if(isset($_SESSION['toast_message'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <img src="../images/succes.png" class="rounded me-2" alt="" style="width:20px;height:20px">
                <strong class="me-auto">Notifications</strong>
                <small class="text-muted">Maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['toast_message'] . '
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message']);
}
if(isset($_SESSION['toast_message2'])) {
    echo '
    <div class="toast-container-centered">
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                 <img src="../../view/images/warning.jpeg" class="rounded me-2" alt="" style="width:20px;height:20px">
                    <strong class="me-auto">Notifications</strong>
                <small class="text-muted">Maintenant</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                ' . $_SESSION['toast_message'] . '
            </div>
        </div>
    </div>';

    // Effacer le message du Toast de la variable de session
    unset($_SESSION['toast_message2']);
}
$edit_societe_details = array();

if (!empty($edit_societe_id)) {
    $sql_edit = "SELECT * FROM `societe_importateur` WHERE `id_societe_importateur`='$edit_societe_id'";
    $result_edit = mysqli_query($conn, $sql_edit);

    if ($result_edit) {
        $edit_societe_details = mysqli_fetch_assoc($result_edit);
    } else {
        echo "Erreur lors de la récupération des détails de la société" . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const spinner = document.getElementById('loadingSpinner');
        const table = document.getElementById('agentTable');

        // Afficher le spinner
        spinner.style.display = 'block';
        table.style.display = 'none';

        // Simulation de chargement des données
        setTimeout(() => {
            spinner.style.display = 'none';
            table.style.display = 'table';
        }, 2000); // Changer le délai selon vos besoins
    });
    </script>
    <style>
    #agentTable {
        display: none;
    }

    .required {
        color: red;
    }

    main {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        /* background-color: #f0f0f0; Couleur de fond */
        background-color: #ffffff;
    }

    .centered-container {
        text-align: center;
    }

    .rounded-border {
        background-color: #fff;
        /* Couleur de fond du cadre */
        border-radius: 10px;
        /* Bordure arrondie */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        /* Ombre légère */
        padding: 20px;
        width: 100%;
        max-width: 1000px;
        /* Largeur maximale du cadre */
    }
    </style>
    <style>
    .custom-text-justify {
        text-align: justify;
        text-justify: inter-word;
        /* Ajoutez cette propriété pour une justification plus précise */
    }

    td {
        font-size: small;
    }
    </style>
    <style>
    .container {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .btn {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .dropdown-item {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .form-control {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .form-select {
        font-size: small;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }

    .h4 {
        font-size: 20px;
        /* Vous pouvez remplacer "small" par une taille spécifique, par exemple "12px" ou "0.8em" */
    }
    </style>

    <title>Ministere des mines</title>
    <?php 
    include "../shared/navBar.php";
    ?>


</head>

<body>
    <div class="container">
        <hr>
        <div class="row">
            <div class="col">
                <h5>Liste des sociétés importateur</h5>
            </div>
            <div class="col">
                <input type="text" id="search" class="form-control" placeholder="Recherche par nom...">
            </div>
            <div class="col text-end">
                <a class="btn btn-success btn-sm rounded-pill px-3 " href="./exporter.php?"><i
                        class="fas fa-file-excel"></i> Exporter en excel</a>
                <a class="btn btn-dark btn-sm rounded-pill px-3 " href="#" onclick="openModal()"><i
                        class="fa-solid fa-add me-1"></i>Ajouter nouveau</a>
            </div>
        </div>
        <hr>
        <div id="loadingSpinner" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <table id="agentTable" class="table table-hover text-center">
            <thead class="table-dark">
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Nom</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Pays de destination</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $sql="SELECT * FROM `societe_importateur` WHERE `validation` iS NOT NULL ORDER BY id_societe_importateur DESC";
                $result= mysqli_query($conn, $sql);
                while($row = mysqli_fetch_assoc($result)){
                  ?>
                <tr>
                    <?php  if( $row['validation']=='Validé'){
                    ?>
                    <td>✅</td>
                    <?php  }else {?>
                    <td>⚠️</td>
                    <?php  }?>
                    <td><?php echo $row['nom_societe_importateur'] ?></td>
                    <td><?php echo $row['adresse_societe_importateur'] ?></td>
                    <td><?php echo $row['contact_societe_importateur'] ?></td>
                    <td><?php echo $row['pays_destination'] ?></td>
                    <td><?php echo $row['validation'] ?></td>
                    <td>
                        <a class="link-dark"
                            href="./detail.php?id=<?php echo $row['id_societe_importateur']; ?>">détails</a>
                        <?php if ($row['validation'] != 'Validé') {
                                ?>
                        <a href="#" class="link-dark"
                            onclick="openModal(<?php echo $row['id_societe_importateur']?>)"><i
                                class="fa-solid fa-pen-to-square me-3"></i></a>
                        <a href="#" class="link-dark"
                            onclick="confirmerSuppression(<?php echo $row['id_societe_importateur']?>)"><i
                                class="fa-solid fa-trash "></i></a>
                        <?php
                            } else {
                                    ?>
                        <a href="#" class="link-dark" data-toggle="tooltip"
                            title="Modification non autorisée : Société déjà validé">
                            <i class="fa-solid fa-pen-to-square me-3"></i>
                        </a>
                        <a href="#" class="link-dark" data-toggle="tooltip"
                            title="Suppression non autorisée : Société déjà validé">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <?php   
                }

                ?>


                <tr>
            </tbody>
        </table>
        <div>
            <?php
                include('../../shared/pied_page.php');
            ?>
        </div>
    </div>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        aria-labelledby="staticBackdropLabel" style="font-size:90%; font-weight:bold">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nouvelle société</h1>
                    <button type="button" class="btn-close" onclick="closeModal()" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="societeForm" action="" method="post">
                        <div class="mb-3">
                            <label for="nom" name="nom" class="col-form-label">Nom de la société<span
                                    class="required">*</span></label>
                            <input type="text" class="form-control" name="nom" id="nom" placeholder="Nom complète"
                                required style="font-size:90%">
                        </div>
                        <div class="mb-3">
                            <label for="adresse" name="adresse" class="col-form-label">Adresse de la société<span
                                    class="required">*</span></label>
                            <input type="text" class="form-control" id="adresse" name="adresse"
                                placeholder="Adresse complète" required style="font-size:90%">
                        </div>
                        <div class="mb-3">
                            <label for="contact" class="col-form-label">Contact de la société<span
                                    class="required">*</span></label>
                            <input type="text" class="form-control" id="contact" name="contact"
                                placeholder="Numéro de téléphone" required style="font-size: 90%">
                        </div>
                        <div class="mb-3">
                            <label for="email" name="email" class="col-form-label">Email de la société:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Adresse email"
                                style="font-size:90%">
                        </div>
                        <div class="mb-3">
                            <label for="destination" name="destination" class="col-form-label">Destination<span
                                    class="required">*</span></label>
                            <input type="text" class="form-control" id="destination" name="destination"
                                placeholder="Ville de destination" required style="font-size:90%">
                        </div>
                        <input type="hidden" id="id" name="id">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="closeModal()">Close</button>
                            <button class="btn btn-sm btn-primary" type="submit" name="submit">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--Bootstrap-->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Inclure jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    var myModal;
    var closeModalAfterSubmit = false; // Variable pour vérifier si la modal doit être fermée

    // Fonction pour fermer la modal et actualiser la page si nécessaire
    function closeModal() {
        console.log("Fermeture de la modal");
        if (myModal) {
            myModal.hide();
            if (closeModalAfterSubmit) {
                location.reload(); // Actualiser la page après la fermeture de la modal
            }
        }
    }
    // Fonction pour confirmer la suppression
    function confirmDeletion() {
        // Ici, vous pouvez ajouter le code PHP pour effectuer la suppression
        // Par exemple, vous pouvez utiliser une requête AJAX pour appeler un script PHP de suppression
        console.log("Suppression confirmée");
        closeModal(); // Fermer la modale après la confirmation
    }
    $(document).ready(function() {
        $('.toast').toast('show');
        $('[data-toggle="tooltip"]').tooltip();
    });

    function confirmerSuppression(id) {
        // Utilisation de la fonction confirm pour afficher une boîte de dialogue
        var confirmation = confirm("Êtes-vous sûr de vouloir supprimer cet élément ?");

        // Si l'utilisateur clique sur "OK", la suppression est effectuée
        if (confirmation) {
            $.ajax({
                url: 'delete.php',
                method: 'POST', // Utilisez la méthode POST pour la suppression
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    // Traitez la réponse du serveur ici
                    if (response.success) {
                        // La suppression a réussi
                        alert('Suppression réussie.');
                        // Vous pouvez également effectuer d'autres actions nécessaires après la suppression
                        location.reload();
                    } else {
                        // La suppression a échoué
                        alert('Erreur lors de la suppression : ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erreur lors de la suppression : ' + error);
                }
            });
        } else {
            // Sinon, rien ne se passe
        }
    }

    function openModal(edit_id = null) {
        myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'), {
            backdrop: 'static',
            keyboard: false
        });

        if (edit_id) {
            // Si edit_id est défini, c'est une édition, ajustez le titre et pré-remplissez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Modifier la société';
            var id = edit_id;

            function getDataById(id) {
                $.ajax({
                    url: 'get_data.php',
                    method: 'GET',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#id').val(data.id_societe_importateur);
                        $('#nom').val(data.nom_societe_importateur);
                        $('#adresse').val(data.adresse_societe_importateur);
                        $('#contact').val(data.contact_societe_importateur);
                        $('#email').val(data.email_societe_importateur);
                        // var countryCode = data.code_pays;
                        // $('#country_code').val(countryCode);
                        // updateCountryName(countryCode);
                        $('#destination').val(data.pays_destination);
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur lors de la récupération des données : ' + error);
                    }
                });
            }
            getDataById(id);
        } else {
            // Sinon, c'est une nouvelle société, ajustez le titre et réinitialisez les champs
            document.getElementById('staticBackdropLabel').innerText = 'Nouvelle société';
            document.getElementById('nom').value = '';
            document.getElementById('adresse').value = '';
            document.getElementById('contact').value = '';
            document.getElementById('email').value = '';
            //document.getElementById('country_code').value = '';
            document.getElementById('id').value = '';
        }

        myModal.show();
    }

    // function updateCountryName(countryCode) {
    //     $('#country_code').each(function() {
    //         if ($(this).val() === countryCode) {
    //             var countryName = $(this).text().split('(')[1].replace(')', '');
    //             $('#country_name').val(countryName);
    //         }
    //     });
    // }
    </script>
</body>

</html>