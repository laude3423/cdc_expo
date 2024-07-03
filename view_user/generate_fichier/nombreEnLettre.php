<?php
function nombreEnLettres($nombre) {
    if($nombre== 0){
        return "Zéro";
    }
    $lettres = array('', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix-sept', 'dix-huit', 'dix-neuf');
    $dizaines = array('', '', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingt', 'quatre-vingt-dix');
    
    if ($nombre < 20) {
        return $lettres[$nombre];
    } elseif ($nombre < 100) {
        return $dizaines[floor($nombre / 10)] . ' ' . $lettres[$nombre % 10];
    } elseif ($nombre < 1000) {
        $centaines = floor($nombre / 100);
        $reste = $nombre % 100;
        $resultat = $lettres[$centaines] . ' cent ';
        if ($reste > 0) {
            $resultat .= nombreEnLettres($reste);
        }
        return $resultat;
    } elseif ($nombre < 10000) {
        $milliers = floor($nombre / 1000);
        $reste = $nombre % 1000;
        $resultat = $lettres[$milliers] . ' mille ';
        if ($reste > 0) {
            $resultat .= nombreEnLettres($reste);
        }
        return $resultat;
    } else {
        return 'invalide';
    }
}

function moisEnLettres($mois) {
    $moisFr = array('', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre');
    return $moisFr[$mois];
}
function comparer($valeur) {
    switch ($valeur) {
        case '00':
            return 0;
        case '01':
            return 1;
        case '02':
            return 2;
        case '03':
            return 3;
        case '04':
            return 4;
        case '05':
            return 5;
        case '06':
            return 6;
        case '07':
            return 7;
        case '08':
            return 8;
        case '09':
            return 9;
        default:
            return $valeur; // Valeurs autres que '00' à '09' restent inchangées
    }
}
$timezone = new DateTimeZone('Indian/Antananarivo');

// Créer un objet DateTime avec le fuseau horaire spécifié
$date4 = new DateTime('now', $timezone);

// Formatter la date selon le format désiré
$dateTextFormat = "Y-m-d H:i:s";
$dateText = $date4->format($dateTextFormat);

// Extraire les composantes de la date
$anneeText = $date4->format('Y');
$moisText = $date4->format('n');
$jourText = $date4->format('j');
$heureText = $date4->format('G');
$minuteText = $date4->format('i');
$secondeText = $date4->format('s');

$heureCompare = comparer($heureText);
$minuteCompare = comparer($minuteText);
if(($heureCompare<2)&&($minuteCompare< 2)){
    $dateEnTexte = "L'an " . nombreEnLettres($anneeText) . " le " . nombreEnLettres($jourText) . " " . moisEnLettres($moisText) . " à " . nombreEnLettres($heureCompare) . " heure " . nombreEnLettres($minuteCompare) . " minute.";
}elseif($heureCompare<2){
    $dateEnTexte = "L'an " . nombreEnLettres($anneeText) . " le " . nombreEnLettres($jourText) . " " . moisEnLettres($moisText) . " à " . nombreEnLettres($heureCompare) . " heure " . nombreEnLettres($minuteCompare) . " minutes.";
}elseif($minuteCompare< 2){
    $dateEnTexte = "L'an " . nombreEnLettres($anneeText) . " le " . nombreEnLettres($jourText) . " " . moisEnLettres($moisText) . " à " . nombreEnLettres($heureCompare) . " heures " . nombreEnLettres($minuteCompare) . " minute.";
}else{
    $dateEnTexte = "L'an " . nombreEnLettres($anneeText) . " le " . nombreEnLettres($jourText) . " " . moisEnLettres($moisText) . " à " . nombreEnLettres($heureCompare) . " heures " . nombreEnLettres($minuteCompare) . " minutes.";
}

?>