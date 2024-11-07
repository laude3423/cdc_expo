<?php
require_once('../../scripts/db_connect.php');
require '../../vendor/autoload.php';
use PhpOffice\PhpWord\TemplateProcessor;
include '../../mylibs/phpqrcode/qrlib.php';

function nombreEnLettres($chiffre){
    if(str_contains($chiffre, '.') || str_contains($chiffre, ',')) {
        $parties = explode('.', $chiffre); // Divise le nombre en parties en utilisant le point comme délimiteur
        $partie_entiere = $parties[0]; // La partie entière
        $partie_decimale = $parties[1]; // La partie décimale

        $fmt_partie_entiere = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
        $chiffreEnLettres_partie_entiere = $fmt_partie_entiere->format($partie_entiere);

        $fmt_partie_decimale = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
        $chiffreEnLettres_partie_decimale = $fmt_partie_decimale->format($partie_decimale);

        $chiffreEnLettres = $chiffreEnLettres_partie_entiere. " virgule " .$chiffreEnLettres_partie_decimale;

    } else {
        $fmt = new NumberFormatter('fr', NumberFormatter::SPELLOUT);
        $chiffreEnLettres = $fmt->format($chiffre);      
    }

    return $chiffreEnLettres;
}
    $sql = "SELECT * FROM vol WHERE id_vol = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_vol);
    $stmt->execute();
    $resu = $stmt->get_result();
    $row = $resu->fetch_assoc();
    $nom_compagnie=$row['nom_compagnie'];
    $num_vol=$row['numero_vol'];
    $destination_vol=$row['destination_vol'];
    $stmt->close();

    //select numero
    $sql2 = "SELECT numero_autorisation FROM autorisation WHERE id_autorisation = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $id);
    $stmt2->execute();
    $resu2 = $stmt2->get_result();
    $row2 = $resu2->fetch_assoc();
    $num_as=$row2['numero_autorisation'];
    $stmt2->close();

    $sql3 = "SELECT * FROM pays WHERE id_pays = ?";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bind_param("i", $id_pays);
    $stmt3->execute();
    $resu3 = $stmt3->get_result();
    $row3 = $resu3->fetch_assoc();
    $destination=$row3['nom_pays'];
    $stmt3->close();

    $sql4 = "SELECT * FROM agent_controle WHERE id_agent_controle = ?";
    $stmt4 = $conn->prepare($sql4);
    $stmt4->bind_param("i", $matricule);
    $stmt4->execute();
    $resu4 = $stmt4->get_result();
    $row4 = $resu4->fetch_assoc();
    $matricule_responsable=$row4['matricule'];
    $stmt4->close();

    $locale = 'fr_FR';

    // Créer une instance de DateTime avec la date d'aujourd'hui
    $date = new DateTime();

    // Créer un formateur de date
    $formatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Paris', IntlDateFormatter::GREGORIAN, 'd MMMM yyyy');

    // Formater la date
    $dateFormatted2 = ucfirst($formatter->format($date));

    $templatePathScan = '../template/model_scan_auto.docx';
    $templatePath =  '../template/mode_auto.docx';
    
    $templateScan = new TemplateProcessor($templatePathScan);
    $template = new TemplateProcessor($templatePath);
    
    $entete="
            MINISTERE DES MINES                
            -----------------------                
                SECRETARIAT GENERAL DES MINES                 
                                ----------------------                
                                        DIRECTION GENERALE DES MINES
                                                ---------------------
                                                    DIRECTION DES EXPORTATIONS ET VALEURS
                                                        --------------------- 
                                                            GUICHET UNIQUE D'EXPORTATION
                                                                ---------------------
";
    //societe
    $poids=floatval($poids);
    $poids_lettre=nombreEnLettres($poids);
    $uniteed="";
    $civilite_affiche="";
    if($civilite=='M'){
        $civilite_affiche="Mademoiselle ";
    }else if($civilite=='MM'){
        $civilite_affiche='Madame ';
    }else{
        $civilite_affiche='Monsieur ';
    }
    if($unite=="Kg"){
        $uniteed="Kilogramme";
    }else if($unite=="g"){
        $uniteed="Gramme";
    }
    $designation_phase="-".$designation .' pesant '.$poids_lettre.'('.$poids.$unite.')'.' '.$uniteed;
    $nom_expediteur=$nom." ".$prenom;
    $nom_responsable=$nom_agent." ".$prenom_agent;
    $remplace[] = array('description'=>$designation_phase);
    $templateScan->cloneBlock('block_name', 0, true, false, $remplace);
    $templateScan->setValue('entete', $entete);
    $templateScan->setValue('num_as', $num_as);
    $templateScan->setValue('civilite', $civilite_affiche);
    $templateScan->setValue('nom_expediteur', $nom_expediteur);
    $templateScan->setValue('numero_passeport', $passeport);
    $templateScan->setValue('destination', $destination);
    $templateScan->setValue('num_vol', $num_vol);
    $templateScan->setValue('nom_compagnie', $nom_compagnie);
    $templateScan->setValue('date_depart', $date_depart);
    $templateScan->setValue('destination_vol', $destination_vol);
    $templateScan->setValue('nom_responsable', $nom_responsable);
    $templateScan->setValue('matricule', $matricule_responsable);
    $templateScan->setValue('date_maintenant', $dateFormatted2);
    $destinationFolder =  '../upload/';
    $numPVClear=preg_replace('/[^a-zA-Z0-9]/', '-', $num_as);
    $nouveau_nom_fichier2 = $numPVClear . '.docx';

    $outputFilePath = $destinationFolder . $nouveau_nom_fichier2;
    

        $directory = "../upload";
        $pathToSave = $directory . '/' . $numPVClear . '.pdf';
        $templateScan->saveAs($outputFilePath);
    // Utiliser soffice pour convertir le DOCX en PDF
        $commande = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $outputFilePath . '"';
        shell_exec($commande);

        // Générer un lien de tléchargement vers le fichier PDF
        echo 'Le publipostage a été généré avec succès : <a href="' . $pathToSave . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $outputFilePath . '" download>Télécharger ici DOCX 1 </a>';
        echo $outputFilePath;
        if (unlink($outputFilePath)) {
            echo 'Le fichier a été supprimé avec succès.';
        } else {
            echo 'Une erreur s\'est produite lors de la suppression du fichier.';
        }

        //------------------------------------------------------------------------------------------
        
         //societe
        $template->cloneBlock('block_name', 0, true, false, $remplace);
        $template->setValue('entete', $entete);
        $template->setValue('num_as', $num_as);
        $template->setValue('civilite', $civilite_affiche);
        $template->setValue('nom_expediteur', $nom_expediteur);
        $template->setValue('numero_passeport', $passeport);
        $template->setValue('destination', $destination);
        $template->setValue('num_vol', $num_vol);
        $template->setValue('nom_compagnie', $nom_compagnie);
        $template->setValue('date_depart', $date_depart);
        $template->setValue('destination_vol', $destination_vol);
        $template->setValue('nom_responsable', $nom_responsable);
        $template->setValue('matricule', $matricule_responsable);
        $template->setValue('date_maintenant', $dateFormatted2);

        // Enregistrer le nouveau document DOCX
        $nouveau_nom_fichierQR = $numPVClear  . '_QR.docx';
        $outputFilePathQR = $destinationFolder . $nouveau_nom_fichierQR;
        $template->saveAs($outputFilePathQR);

        // //------------------------------------------------------------------------------------------------------------------------------

        // //Generer le QR COde
        $tempDir = '../upload/';
        $lien = 'https://cdc.minesmada.org/aut_visa/autorisation/scriptsPdf.php?id_autorisation='.$id;
        $qrcode_name = 'qrcode_test';
        QRcode::png($lien, $tempDir.''.$qrcode_name.'.png', QR_ECLEVEL_L, 5);
         // Chemin vers le fichier QR code et le logo
        $qrCodePath = $tempDir . $qrcode_name . '.png';
        $logoPath = '../../logo/logoMine.png';

        // Dimensions souhaitées pour le logo
        $logoWidth = 40; // Largeur souhaitée du logo
        $logoHeight = 40; // Hauteur souhaitée du logo

        // Créer une image à partir du QR code (qui est maintenant en PNG)
        $qrCode = imagecreatefrompng($qrCodePath);

        if ($qrCode === false) {
            die('Erreur : Impossible de créer une image à partir du QR code.');
        }

        // Créer une image à partir du logo (qui est en PNG)
        $logo = imagecreatefrompng($logoPath);

        if ($logo === false) {
            die('Erreur : Impossible de créer une image à partir du logo.');
        }

        // Dimensions actuelles du logo
        $logoActualWidth = imagesx($logo);
        $logoActualHeight = imagesy($logo);

        // Redimensionner le logo aux dimensions souhaitées
        $logoResized = imagecreatetruecolor($logoWidth, $logoHeight);
        imagecopyresampled($logoResized, $logo, 0, 0, 0, 0, $logoWidth, $logoHeight, $logoActualWidth, $logoActualHeight);

        // Dimensions du QR code
        $qrWidth = imagesx($qrCode);
        $qrHeight = imagesy($qrCode);

        // Positionnement du logo au centre du QR code
        $logoX = ($qrWidth / 2) - ($logoWidth / 2);
        $logoY = ($qrHeight / 2) - ($logoHeight / 2);

        // Fusionner le logo redimensionné sur le QR code
        imagecopy($qrCode, $logoResized, $logoX, $logoY, 0, 0, $logoWidth, $logoHeight);

        // Chemin pour l'image fusionnée
        $mergedImagePath = $tempDir . $qrcode_name . '_with_logo.png';

        // Sauvegarder l'image fusionnée
        imagepng($qrCode, $mergedImagePath);

        // Libérer la mémoire
        imagedestroy($qrCode);
        imagedestroy($logo);
        imagedestroy($logoResized);

        // // Mettre le QR Code dans le fichier Word
        $templateProcessor = new TemplateProcessor($outputFilePathQR);

        $directoryQR = '../upload/';

        $templateProcessor->setImageValue(
            'qrcode',
            [
                'path' => $mergedImagePath,
                'width' => 150, //=4cm
                'height' => 150,
                
            ]
        );

        $newNameQR = $numPVClear . '_QR.docx';
        $pathToSaveNew = $destinationFolder . $newNameQR;
        $templateProcessor->saveAs($pathToSaveNew);
        // //---------------------------------------------------------------------------------------------------------------------------


        // // Nom du fichier PDF résultant
        $pdfFileName = $numPVClear . '_QR.pdf';
        $pathToSavePDF = $directory . '/' . $pdfFileName;

        // Convertir le fichier Word en PDF en utilisant la commande "soffice"
        $command = 'soffice --headless --convert-to pdf --outdir "' . $directory . '" "' . $pathToSaveNew . '"';
        shell_exec($command);

        echo 'Le publipostage a été généré avec succès : <a href="' . $pathToSavePDF . '" download>Télécharger ici PDF</a>';
        echo 'Le publipostage a ét généré avec succès : <a href="' . $pathToSaveNew . '" download>Télécharger ici DOCX 1 </a>';
        unlink($pathToSaveNew);
    
?>