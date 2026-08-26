var url = base_url+'laporan/neraca';
var PDFJS = window['pdfjs-dist/build/pdf'];
PDFJS.GlobalWorkerOptions.workerSrc = base_url+'assets/plugins/pdf/pdf.worker.js';

var thePdf,viewer,page,canvas,viewport,scale = 1.3;
    
PDFJS.getDocument(url).promise.then(function(pdf) {
    thePdf = pdf;
    viewer = document.getElementById('pdf-viewer');
    
    for(page = 1; page <= pdf.numPages; page++) {
      canvas = document.createElement("canvas");    
      canvas.className = 'pdf-page-canvas';         
      viewer.appendChild(canvas);            
      renderPage(page, canvas);
    }
});

function renderPage(pageNumber, canvas) {
    thePdf.getPage(pageNumber).then(function(page) {
      viewport = page.getViewport({ scale: scale });
      canvas.height = viewport.height;
      canvas.width = viewport.width;          
      page.render({canvasContext: canvas.getContext('2d'), viewport: viewport});
});

}

$("#printOut").click(function(){
 var canvas = document.getElementById('pdf-viewer');
  var win = window.open('', '', '');
  var html = "<img src='" + canvas.toDataURL() + "'>";
  win.document.write(html);
  win.document.close();
  win.focus();
  win.print();
  win.close();
})