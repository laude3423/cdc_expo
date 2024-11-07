<?php 
$pdfUrl1 = $pdfFilePath1; // Remplacez $pdfFilePath1 par la variable appropriée pour le premier PDF
$lien_corrige1 = str_replace("../", "", $pdfUrl1);

if ($pdfUrl1): 
?>
<style>
#pdf-canvas1 {
    width: 100%;
    height: auto;
    max-width: 100%;
}
</style>
<div id="pdf-container1">
    <a href="https://cdc.minesmada.org/view_user/<?php echo $lien_corrige1; ?>">
        <canvas id="pdf-canvas1" class="container"></canvas>
    </a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script>
const pdfUrl1 = '<?php echo $pdfUrl1; ?>';
const pdfjsLib = window['pdfjs-dist/build/pdf'];
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.worker.min.js';

const loadingTask1 = pdfjsLib.getDocument(pdfUrl1);
loadingTask1.promise.then(pdf => {
    console.log('PDF 1 loaded');

    // Fetch the first page
    pdf.getPage(1).then(page => {
        console.log('Page 1 loaded');

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
        const canvas = document.getElementById('pdf-canvas1');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        // Render PDF page into canvas context
        const renderContext = {
            canvasContext: context,
            viewport: viewport
        };
        const renderTask1 = page.render(renderContext);
        renderTask1.promise.then(() => {
            console.log('Page 1 rendered');
        });
    });
}, reason => {
    console.error(reason);
});
</script>
<?php endif; ?>

<?php 
$pdfUrl2 = $pdfFilePath2; // Remplacez $pdfFilePath2 par la variable appropriée pour le deuxième PDF
$lien_corrige2 = str_replace("../", "", $pdfUrl2);

if ($pdfUrl2): 
?>
<style>
#pdf-canvas2 {
    width: 100%;
    height: auto;
    max-width: 100%;
}
</style>
<div id="pdf-container2">
    <a href="https://cdc.minesmada.org/view_user/<?php echo $lien_corrige2; ?>">
        <canvas id="pdf-canvas2" class="container"></canvas>
    </a>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

<script>
const pdfUrl2 = '<?php echo $pdfUrl2; ?>';
const loadingTask2 = pdfjsLib.getDocument(pdfUrl2);
loadingTask2.promise.then(pdf => {
    console.log('PDF 2 loaded');

    // Fetch the first page
    pdf.getPage(1).then(page => {
        console.log('Page 2 loaded');

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
        const canvas = document.getElementById('pdf-canvas2');
        const context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        // Render PDF page into canvas context
        const renderContext = {
            canvasContext: context,
            viewport: viewport
        };
        const renderTask2 = page.render(renderContext);
        renderTask2.promise.then(() => {
            console.log('Page 2 rendered');
        });
    });
}, reason => {
    console.error(reason);
});
</script>
<?php endif; ?>