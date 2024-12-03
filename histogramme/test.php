<?php
require_once('../scripts/db_connect.php');
require_once('../scripts/connect_db_lp1.php');
require_once('../scripts/session.php');
 
?>
<?php
$radio_name = "Radio Nationale Malagasy (RNM)";
$radio_url = "http://rnm.stream.example.com/live.mp3"; // Remplacez par l'URL réelle
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $radio_name; ?></title>
</head>

<body>
    <h1><?php echo $radio_name; ?></h1>
    <audio controls autoplay>
        <source src="<?php echo $radio_url; ?>" type="audio/mpeg">
        Votre navigateur ne supporte pas la lecture audio.
    </audio>
</body>

</html>