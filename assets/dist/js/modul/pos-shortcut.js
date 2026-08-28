document.addEventListener('keydown', function(e){
  if(e.keyCode !== 113) return; // F2

  var posFrame = document.getElementById('iframe-page-pos_2');
  if(!posFrame || !$(posFrame).is(':visible')) return;
  if(!posFrame.contentWindow || typeof posFrame.contentWindow._CariBarang !== 'function') return;

  e.preventDefault();
  posFrame.contentWindow._CariBarang();
});
