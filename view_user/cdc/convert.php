<?php 
$pdfUrl=$pdfFilePath;
 $lien_corrige = str_replace("../", "", $pdfUrl);
if ($pdfUrl): 
?>
<style>
#pdf-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.pdf-canvas {
    width: 100%;
    height: auto;
    max-width: 100%;
    margin-bottom: 20px;
}
</style>
<div id="pdf-container">
    <!-- Les canvas seront générés ici pour chaque page -->
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script>
const pdfUrl = '<?php echo $pdfUrl; ?>';
const pdfjsLib = window['pdfjs-dist/build/pdf'];
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

const loadingTask = pdfjsLib.getDocument(pdfUrl);
loadingTask.promise.then(pdf => {
    console.log('PDF loaded');

    // Parcours de toutes les pages
    for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
        pdf.getPage(pageNum).then(page => {
            console.log('Page ' + pageNum + ' loaded');

            // Define the viewport
            const isMobile = window.innerWidth <= 768;
            const initialScale = isMobile ? 1.0 : 1.5;
            const initialViewport = page.getViewport({
                scale: initialScale
            });

            // Adjust the scale based on window width
            const scale = Math.min(1.5, window.innerWidth / initialViewport.width);
            const viewport = page.getViewport({
                scale: scale
            });

            // Créez un nouveau canvas pour chaque page
            const canvas = document.createElement('canvas');
            canvas.className = 'pdf-canvas';
            document.getElementById('pdf-container').appendChild(canvas);

            // Préparez le contexte du canvas en fonction des dimensions de la page
            const context = canvas.getContext('2d');
            canvas.height = viewport.height;
            canvas.width = viewport.width;

            // Rendu de la page PDF dans le contexte du canvas
            const renderContext = {
                canvasContext: context,
                viewport: viewport
            };
            const renderTask = page.render(renderContext);
            renderTask.promise.then(() => {
                console.log('Page ' + pageNum + ' rendered');
            });
        });
    }
}, reason => {
    console.error(reason);
});
</script>
<?php endif; ?>