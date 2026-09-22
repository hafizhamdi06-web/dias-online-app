toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

var _riwayat = [];

$(function () {

  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
  });

  $('.content-wrapper').overlayScrollbars({
    className: "os-theme-dark",
    overflowBehavior : { x :'hidden', y :'scroll' },
    scrollbars : { autoHide : 'scroll', autoHideDelay : 300, snapHandle:true }
  });

  _tampilkanBalasanAi('Halo! Saya DIAS AI. Ceritakan data laporan apa yang Anda butuhkan (mis. "data penjualan bulan ini dalam bentuk excel"), nanti saya carikan laporan yang sesuai.');

  $('#chat-kirim').click(function(){
    _kirimPesan();
  });

  $('#chat-input').on('keydown', function(e){
    if (e.key === 'Enter') {
      e.preventDefault();
      _kirimPesan();
    }
  });

});

function _kirimPesan(){
  var pesan = $('#chat-input').val().trim();
  if (pesan === '') return;

  _tampilkanPesanUser(pesan);
  $('#chat-input').val('').prop('disabled', true);
  $('#chat-kirim').prop('disabled', true);

  var idLoading = _tampilkanIndikatorMengetik();

  $.ajax({
    "url": base_url+"Dias_Ai/chat",
    "type": "POST",
    "dataType": "json",
    "data": { pesan: pesan, riwayat: _riwayat.slice(-6).join('\n') },
    "error": function(xhr, status, error){
      $('#'+idLoading).remove();
      $('#chat-input').prop('disabled', false);
      $('#chat-kirim').prop('disabled', false);
      toastr.error("Gagal menghubungi DIAS AI: "+xhr.status+" "+error);
    },
    "success": function(result){
      $('#'+idLoading).remove();
      $('#chat-input').prop('disabled', false).focus();
      $('#chat-kirim').prop('disabled', false);

      if (result.pesan === 'error') {
        _tampilkanBalasanAi('Maaf, terjadi kendala: '+(result.error || 'tidak diketahui')+'.');
        return;
      }

      _riwayat.push('User: '+pesan);
      _riwayat.push('DIAS AI: '+result.balasan);

      if (result.ditemukan && result.laporan) {
        _tampilkanBalasanAi(result.balasan, result.laporan, result.filter);
      } else {
        _tampilkanBalasanAi(result.balasan);
      }
    }
  });
}

function _tampilkanPesanUser(pesan){
  var $bubble = $(
    '<div class="d-flex justify-content-end mb-3">'+
      '<div class="p-2 rounded bg-primary text-white" style="max-width:75%">'+_escapeHtml(pesan)+'</div>'+
    '</div>'
  );
  $('#chat-isi').append($bubble);
  _scrollKeBawah();
}

function _tampilkanIndikatorMengetik(){
  var id = 'chat-loading-'+Date.now();
  var $bubble = $(
    '<div class="d-flex justify-content-start mb-3" id="'+id+'">'+
      '<div class="p-2 rounded bg-light border" style="max-width:75%"><i class="fas fa-circle-notch fa-spin"></i> DIAS AI sedang mengetik...</div>'+
    '</div>'
  );
  $('#chat-isi').append($bubble);
  _scrollKeBawah();
  return id;
}

function _tampilkanBalasanAi(balasan, laporan, filter){
  var $bubble = $('<div class="d-flex justify-content-start mb-3"></div>');
  var $isi = $('<div class="p-2 rounded bg-light border" style="max-width:75%"></div>');
  $isi.append('<div>'+_escapeHtml(balasan)+'</div>');

  if (laporan) {
    var $tombol = $(
      '<button type="button" class="btn btn-success btn-sm mt-2">'+
        '<i class="fas fa-file-excel"></i> Download "'+_escapeHtml(laporan.nama)+'" (Excel)'+
      '</button>'
    );
    $tombol.on('click', function(){
      _unduhLaporan(laporan, filter);
    });
    $isi.append($tombol);
  }

  $bubble.append($isi);
  $('#chat-isi').append($bubble);
  _scrollKeBawah();
}

function _unduhLaporan(laporan, filter){
  filter = filter || {};
  $('#fd-id').val(laporan.arid);
  $('#fd-tgldari').val(filter.tgldari || '');
  $('#fd-tglsampai').val(filter.tglsampai || '');
  $('#fd-tgl').val(filter.tgl || '');
  $('#fd-namapt').val(filter.namapt || '');
  $('#fd-gudang').val(filter.gudang || '');
  $('#fd-saldo').val(filter.saldo || 0);
  $('#form-download-laporan').trigger('submit');
}

function _scrollKeBawah(){
  var $box = $('#chat-box');
  $box.scrollTop($box[0].scrollHeight);
}

function _escapeHtml(teks){
  return $('<div>').text(teks || '').html();
}
