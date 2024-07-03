<?php 
$pdfUrl = $pdfFilePath;
$pdfUrl2 = $pdfFilePath2;
?>

<!-- Affichage du premier PDF -->
<?php if ($pdfUrl): ?>
<div id="pdf-container1">
    <canvas id="pdf-canvas1" class="container"></canvas>
</div>
<?php endif; ?>

<!-- Affichage du deuxième PDF -->
<?php if ($pdfUrl2): ?>
<div id="pdf-container2">
    <canvas id="pdf-canvas2" class="container"></canvas>
</div>
<?php endif; ?>

<!-- Inclusion de la bibliothèque pdf.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script>
function renderPDF(pdfUrl, canvasId) {
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
            const canvas = document.getElementById(canvasId);
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
}

// Render the first PDF if URL is available
<?php if ($pdfUrl): ?>
renderPDF('<?php echo $pdfUrl; ?>', 'pdf-canvas1');
<?php endif; ?>

// Render the second PDF if URL is available
<?php if ($pdfUrl2): ?>
renderPDF('<?php echo $pdfUrl2; ?>', 'pdf-canvas2');
<?php endif; ?>
</script>
