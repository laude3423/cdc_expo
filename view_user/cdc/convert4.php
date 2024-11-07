<?php 
$pdfUrl=$pdfFilePath;
 $lien_corrige = str_replace("../", "", $pdfUrl);
if ($pdfUrl): 
?>
<style>
#pdf-canvas {
    width: 100%;
    height: auto;
    max-width: 100%;
}
</style>
<div id="pdf-container">
    <a href="https://cdc.minesmada.org/view_user/<?php echo $lien_corrige; ?>">
        <canvas id="pdf-canvas" class="container"></canvas>
    </a>
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