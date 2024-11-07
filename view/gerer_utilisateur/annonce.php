<?php
require '../../vendor/autoload.php';
require_once('../../scripts/db_connect.php');
require_once('../../scripts/session.php');
use PHPMailer\PHPMailer\PHPMailer; // Assurez-vous que PHPMailer est correctement inclus
$mail = new PHPMailer(true);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $id=5;

    $query = "SELECT mail_user FROM users WHERE id_groupe IN (1, 3)";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {

        $mail->isSMTP();
        $mail->Host = 'mail.minesmada.org';
        $mail->SMTPAuth = true;
        $mail->Username = 'no-reply@minesmada.org';
        $mail->Password = 'test@123';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('no-reply@minesmada.org', 'Minesmada');
        $mail->isHTML(true);
        $mail->Subject = "=?UTF-8?B?" . base64_encode($subject) . "?=";
        $mail->Body = nl2br($message);

        while ($row = $result->fetch_assoc()) {
            $mail->addAddress($row['mail_user']);

            if (!$mail->send()) {
                echo 'Erreur lors de l\'envoi du message à ' . $row['mail_user'] . ': ' . $mail->ErrorInfo . '<br>';
            } else {
                echo 'Message envoyé avec succès à ' . $row['mail_user'] . '<br>';
            }

            // Clear all recipients and attachments for the next loop iteration
            $mail->clearAddresses();
        }
         header("Location: ".$_SERVER['PHP_SELF']);
    } else {
        echo "<p>Aucun utilisateur trouvé.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../../logo/favicon.ico">
    <title>Ministère des Mines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!--Font awesome-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-rbs5jQhjAAcWNfo49T8YpCB9WAlUjRRJZ1a1JqoD9gZ/peS9z3z9tpz9Cg3i6/6S" crossorigin="anonymous">
    </script>
</head>

<body>
    <?php include_once('../shared/navBar.php'); ?>
    <div class="container">
        <form action="" method="POST">
            <div class="mb-3">
                <label for="subject">Sujet :</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            <div class="mb-3">
                <label for="message">Message :</label>
                <textarea id="message" class="form-control" name="message" rows="4" cols="50" required></textarea>
            </div>
            <button type="submit" class="btn btn-dark">Envoyer l'annonce</button>
        </form>
    </div>
</body>

</html>