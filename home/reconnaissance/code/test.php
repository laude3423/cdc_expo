<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner le visage</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
    body {
        background-color: #f8f9fa;
    }

    .container {
        margin-top: 50px;
    }

    h1 {
        font-size: 24px;
        margin-bottom: 20px;
        text-align: center;
    }

    #video {
        border: 2px solid #007bff;
        border-radius: 5px;
        margin: 0 auto;
        height: 80%;
        width: 100%;
        display: block;
    }

    #canvas {
        display: none;
    }

    .button-container {
        margin-top: 20px;
        text-align: center;
    }

    button {
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
    }

    button:hover {
        background-color: #0056b3;
    }

    .result-container {
        margin-top: 20px;
        padding: 10px;
        background-color: #ffffff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .loading {
        display: none;
        font-weight: bold;
        color: #007bff;
    }
    </style>
    <script>
    navigator.mediaDevices.getUserMedia({
            video: {
                width: 640,
                height: 480
            }
        })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Erreur d'accès à la caméra : ", err);
            alert("Erreur d'accès à la caméra. Veuillez vérifier vos paramètres.");
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="row g-0 border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static custom-text-justify">
                <h1>Scanner le visage pour l'authentification</h1>
                <video id="video" width="640" height="480" autoplay></video>
                <canvas id="canvas" width="640" height="480"></canvas>
                <div class="loading" id="loading">Traitement de l'image, veuillez patienter...</div>
                <div class="result-container" id="result">
                    <!-- Les résultats seront affichés ici -->
                </div>
            </div>
        </div>
    </div>

    <script>
    // Accéder à la caméra
    const video = document.getElementById('video');
    const loadingText = document.getElementById('loading');
    const resultContainer = document.getElementById('result');

    // Demander l'accès à la caméra
    navigator.mediaDevices.getUserMedia({
            video: true
        })
        .then(stream => {
            video.srcObject = stream;
        })
        .catch(err => {
            console.error("Erreur d'accès à la caméra : ", err);
            alert("Erreur d'accès à la caméra. Veuillez vérifier vos paramètres.");
        });
    let captureCount = 0; // Initialisation du compteur
    // Fonction de capture d'image
    function captureImage() {
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const imageData = canvas.toDataURL('image/jpeg');
        // Afficher le texte de chargement
        loadingText.style.display = 'block';
        resultContainer.innerHTML = ''; // Réinitialiser les résultats

        // Envoyer imageData à votre serveur pour traitement
        fetch('comparaison.php', {
                method: 'POST',
                body: JSON.stringify({
                    image: imageData
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.text().then(text => {
                console.log('Réponse brute du serveur:', text); // Vérifiez la réponse du serveur
                loadingText.style.display = 'none'; // Masquer le texte de chargement

                try {
                    const data = JSON.parse(text); // Convertir la réponse en objet JSON
                    console.log('Données traitées avec succès:',
                        data); // Afficher l'objet JSON dans la console pour vérifier son contenu

                    if (data.error) {
                        resultContainer.innerHTML = `<p class="text-danger">Erreur : ${data.error}</p>`;
                    } else {
                        // Affichage des informations retournées par PHP
                        const faceInfo = `
                                <strong>Résultat de la comparaison :</strong><br>
                                Similitude : ${data.similarity}%<br>
                                Message : ${data.message}<br>
                                <strong>Détails de la comparaison :</strong><br>
                                Confiance : ${data.comparisonResult.confidence ?? 'N/A'}<br>
                            `;
                        resultContainer.innerHTML = faceInfo;
                        if (data.similarity >= 80) {
                            // Rediriger vers une autre page, par exemple "dashboard.php"
                            window.location.href = '../../home.php';
                        }
                    }
                } catch (e) {
                    console.error('Erreur lors du parsing JSON:', e);
                    resultContainer.innerHTML =
                        `<p class="text-danger">Erreur lors du traitement de la réponse.</p>`;
                }
            }));
        // Incrémenter le compteur de captures
        captureCount++;

        // Si le nombre de captures dépasse 5, rediriger vers une autre page
        if (captureCount > 5) {
            window.location.href = '../../../scripts/logout.php';
        }
    }

    // Capture automatique toutes les 5 secondes
    setInterval(captureImage, 5000); // 5000 ms = 5 secondes
    </script>
</body>

</html>