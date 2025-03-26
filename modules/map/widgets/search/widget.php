<div id="widget" class="relative flex w-full h-full">
    <input type="text" id="search-bar" class="absolute top-2 left-2 z-5 p-2 border border-gray-300 rounded bg-surface text-OnSurface focus:outline-none" placeholder="Search...">
    <canvas id="map-canvas" class="w-full h-full "></canvas>
</div>

<script type="module">
    import * as PDFJS from "https://esm.sh/pdfjs-dist";
    import * as PDFWorker from "https://esm.sh/pdfjs-dist/build/pdf.worker.min";
    try {
        PDFJS.GlobalWorkerOptions.workerSrc = PDFWorker;
    } catch (e) {
        window.pdfjsWorker = PDFWorker;
    }

    const floor3Url = '/assets/floors/3.pdf';
    const floor4Url = '/assets/floors/4.pdf';

    const canvas = document.getElementById('map-canvas');
    const context = canvas.getContext('2d');
    const searchBar = $('#search-bar');

    function highlightText(ctx, textObj, viewport) {
        const [a, b, c, d, e, f] = textObj.transform;
        const width = textObj.width;
        const height = textObj.height;
        const scale = viewport.scale;
        const canvasHeight = ctx.canvas.height;
        const adjustedX = e * scale;
        const adjustedY = canvasHeight - (f * scale);
        const angle = Math.atan2(b, d);
        const centerX = adjustedX + (width * scale) / 2;
        const centerY = adjustedY - (height * scale) / 2;
        const radius = (Math.sqrt(width * width + height * height) * scale) / 2;

        ctx.save();
        ctx.translate(centerX, centerY);
        ctx.rotate(angle);
        ctx.beginPath();
        ctx.arc(0, 0, radius, 0, 2 * Math.PI);
        ctx.strokeStyle = "red";
        ctx.lineWidth = 2;
        ctx.stroke();
        ctx.restore();
    }

    function renderPDF(url, searchText) {
        console.log(url);
        pdfjsLib.getDocument(url).promise.then(pdf => {
            pdf.getPage(1).then(page => {
                const viewport = page.getViewport({ scale: 1.5 });
                canvas.width = viewport.width;
                canvas.height = viewport.height;

                const renderContext = {
                    canvasContext: context,
                    viewport: viewport
                };
                page.render(renderContext).promise.then(() => {
                    if (searchText) {
                        page.getTextContent().then(textContent => {
                            textContent.items.forEach(textObj => {
                                if (textObj.str.trim().toLowerCase().includes(searchText)) {
                                    highlightText(context, textObj, viewport);
                                }
                            });
                        });
                    }
                });
            });
        });
    }

    let currentRenderTask = null;

    searchBar.on('keypress', function(e) {
        if (e.which === 13) {
            const searchText = $(this).val().toLowerCase();
            const floorUrls = [floor3Url, floor4Url];
            let found = false;

            if (currentRenderTask) {
                currentRenderTask.cancel();
            }

            floorUrls.forEach(url => {
                pdfjsLib.getDocument(url).promise.then(pdf => {
                    pdf.getPage(1).then(page => {
                        page.getTextContent().then(textContent => {
                            textContent.items.forEach(textObj => {
                                if (textObj.str.trim().toLowerCase().includes(searchText)) {
                                    if (!found) {
                                        currentRenderTask = renderPDF(url, searchText);
                                        found = true;
                                    }
                                    highlightText(context, textObj, page.getViewport({ scale: 1.5 }));
                                }
                            });
                        });
                    });
                });
            });

            if (!found) {
                console.log('No matching text found in any floor');
            }
        }
    });

    renderPDF(floor3Url, "");
</script>

