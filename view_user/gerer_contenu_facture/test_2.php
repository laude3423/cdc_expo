<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf_file'])) {
    $uploadDir = 'test/';
    $uploadFile = $uploadDir . basename($_FILES['pdf_file']['name']);

    if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadFile)) {
        $pdfUrl = $uploadFile;
    } else {
        echo "Erreur lors du téléchargement du fichier.";
        exit;
    }
} else {
    $pdfUrl = '';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Convertir PDF en JPG</title>
</head>
<body>
    <h1>Convertir PDF en JPG</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="pdf_file" accept="application/pdf">
        <button type="submit">Uploader et convertir</button>
    </form>

    <?php if ($pdfUrl): ?>
    <div id="pdf-container">
        <h2>Résultat de la conversion</h2>
        <canvas id="pdf-canvas"></canvas>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        const pdfUrl = '<?php echo $pdfUrl; ?>';
        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

        const loadingTask = pdfjsLib.getDocument(pdfUrl);
        loadingTask.promise.then(pdf => {
            console.log('PDF loaded');

            // Fetch the first page
            pdf.getPage(1).then(page => {
                console.log('Page loaded');

                const scale = 1.5;
                const viewport = page.getViewport({ scale: scale });

                // Prepare canvas using PDF page dimensions
                const canvas = document.getElementById('pdf-canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Render PDF page into canvas context
                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                const renderTask = page.render(renderContext);
                renderTask.promise.then(() => {
                    console.log('Page rendered');
                });
            });
        }, reason => {
            console.error(reason);
        });
    </script>
    <?php endif; ?>
</body>
</html>
