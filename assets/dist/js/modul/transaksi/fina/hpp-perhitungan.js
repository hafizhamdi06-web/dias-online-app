var tabel = null;
var _dataTerkini = [];
var _detailTerkini = [];

toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

var _formatNilai = (n) => {
  return (Number(n) || 0).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

$(function () {

  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
  });

  $('.tab-wrap').overlayScrollbars({
    className: "os-theme-dark",
    overflowBehavior : { x :'scroll', y :'scroll' },
    scrollbars : { autoHide : 'scroll', autoHideDelay : 300, snapHandle:true }
  });

  var sekarang = new Date();
  $('#bulan').val(sekarang.getMonth() + 1);
  $('#tahun').val(sekarang.getFullYear());

  $('#pt').select2({
    "theme":"bootstrap4",
    "placeholder":"Pilih PT",
    "allowClear": true,
    "ajax": {
      "url": base_url+"Select_Master/view_namapt",
      "type": "post",
      "dataType": "json",
      "delay": 500,
      "data": function(params){ return { search: params.term }; },
      "processResults": function(data){ return { results: data }; }
    }
  });

  $('#cabang').select2({
    "theme":"bootstrap4",
    "placeholder":"Pilih PT dulu",
    "allowClear": true,
    "ajax": {
      "url": base_url+"Select_Master/view_gudang_per_pt",
      "type": "post",
      "dataType": "json",
      "delay": 500,
      "data": function(params){ return { search: params.term, pt: $('#pt').val() }; },
      "processResults": function(data){ return { results: data }; }
    }
  });

  $('#pt').on('change', function(){
    $('#cabang').val(null).trigger('change');
    if ($(this).val()) {
      $('#cabang').prop('disabled', false).attr('placeholder', 'Pilih cabang');
    } else {
      $('#cabang').prop('disabled', true).attr('placeholder', 'Pilih PT dulu');
    }
  });

  $('#bhitung').click(function(){
    _hitungHpp();
  });

  $(document).on('click', '#b-tutup-detail-hpp, #b-tutup-x-detail-hpp', function(){
    $('#modal-detail-hpp').modal('hide');
  });

  // Klik caret di baris detail -> buka/tutup rincian lapisan FIFO (asal HPP) baris itu.
  $('#detail-hpp-tbody').on('click', '.b-toggle-asal', function(){
    var $icon = $(this);
    var $tr = $icon.closest('tr');
    var i = $tr.data('i');
    var $next = $tr.next('tr.baris-asal-hpp');

    $('#detail-hpp-tbody tr.baris-asal-hpp').remove();
    $('#detail-hpp-tbody .b-toggle-asal').removeClass('fa-caret-down').addClass('fa-caret-right');

    if ($next.length) { return; } // sudah terbuka sblm diklik -> cukup ditutup (sudah dihapus di atas)

    $icon.removeClass('fa-caret-right').addClass('fa-caret-down');
    $tr.after(_buatBarisAsal(_detailTerkini[i]));
  });

  // Delegasi dari <table>, bukan <tbody>, krn tbody dibuat ulang tiap kali "Hitung" diklik.
  $('#hpp-table').on('click', '.b-detail-hpp', function(){
    var idx = $(this).data('idx');
    _tampilkanDetail(_dataTerkini[idx]);
  });

});

function _hitungHpp(){
  var bulan = $('#bulan').val();
  var tahun = $('#tahun').val();
  var pt = $('#pt').val();
  var cabang = $('#cabang').val();

  if (!pt) { toastr.error('Pilih PT dulu.'); return; }
  if (!cabang) { toastr.error('Pilih Cabang dulu.'); return; }
  if (!tahun || tahun.length !== 4) { toastr.error('Isi Tahun dengan benar (4 digit).'); return; }

  $.ajax({
    "url": base_url+"Fina_Hpp/hitung",
    "type": "POST",
    "dataType": "json",
    "data": { bulan: bulan, tahun: tahun, cabang: cabang, pt: pt },
    "beforeSend": function(){
      $("#bhitung").prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Menghitung...');
    },
    "error": function(xhr, status, error){
      $("#bhitung").prop('disabled', false).html('<i class="fas fa-calculator"></i> Hitung');
      toastr.error("Gagal menghitung : "+xhr.status+" "+error);
    },
    "success": function(result){
      $("#bhitung").prop('disabled', false).html('<i class="fas fa-calculator"></i> Hitung');

      if (result.pesan !== 'sukses') {
        toastr.error(result.error || 'Gagal menghitung HPP.', "", {timeOut: 20000});
        return;
      }

      _tampilkanHasil(result.data, result.total_qty, result.total_hpp);

      if (result.data.length === 0) {
        toastr.warning('Tidak ada penjualan pada periode/cabang ini.');
      }
    }
  });
}

function _tampilkanHasil(data, totalQty, totalHpp){
  _dataTerkini = data;

  if (tabel) {
    tabel.destroy();
    $('#hpp-table tbody').remove();
  }

  $('#hpp-table').removeClass('d-none').append('<tbody></tbody>');

  var $tbody = $('#hpp-table tbody');
  data.forEach(function(row, idx){
    var $tr = $('<tr>');
    $tr.append('<td class="text-sm">'+row.ikode+'</td>');
    $tr.append('<td class="text-sm">'+row.inama+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(row.qtyterjual)+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(row.totalhpp)+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(row.hpprata2)+'</td>');
    $tr.append('<td class="text-sm text-center"><button type="button" class="btn btn-outline-primary btn-xs b-detail-hpp" data-idx="'+idx+'"><i class="fas fa-search"></i> Sumber</button></td>');
    $tbody.append($tr);
  });

  $('#foot-qty').text(_formatNilai(totalQty));
  $('#foot-hpp').text(_formatNilai(totalHpp));

  tabel = $('#hpp-table').DataTable({
    "processing": false,
    "serverSide": false,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "paging": data.length > 25,
    "info": true,
    "order": [[ 0, 'asc' ]],
    "columnDefs": [{ "orderable": false, "targets": 5 }],
    "language": {
      "emptyTable": "Tidak ada data"
    }
  });
}

function _tampilkanDetail(row){
  if (!row) return;

  _detailTerkini = row.detail || [];

  $('#detail-hpp-judul').text('Rincian Sumber HPP — ['+row.ikode+'] '+row.inama);

  var $tbody = $('#detail-hpp-tbody').empty();
  _detailTerkini.forEach(function(d, i){
    var $tr = $('<tr data-i="'+i+'">');
    $tr.append('<td class="text-center"><i class="fas fa-caret-right text-sm b-toggle-asal" style="cursor:pointer" title="Lihat rincian sumber HPP baris ini"></i></td>');
    $tr.append('<td class="text-sm">'+d.notransaksi+'</td>');
    $tr.append('<td class="text-sm">'+d.tanggal+'</td>');
    $tr.append('<td class="text-sm">'+d.susumber+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(d.qty)+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(d.hppsatuan)+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(d.hpp)+'</td>');
    $tbody.append($tr);
  });

  $tbody.append(
    '<tr class="bg-light font-weight-bold">'+
      '<td class="text-sm" colspan="4">Total</td>'+
      '<td class="text-sm text-right">'+_formatNilai(row.qtyterjual)+'</td>'+
      '<td class="text-sm text-right"></td>'+
      '<td class="text-sm text-right">'+_formatNilai(row.totalhpp)+'</td>'+
    '</tr>'
  );

  $('#modal-detail-hpp').modal('show');
}

// Baris nested berisi rincian lapisan FIFO yg membentuk HPP satu baris penjualan --
// termasuk transaksi PRO asal (kalau HPP-nya berasal dari hasil produksi via TMB).
function _buatBarisAsal(detailBaris){
  var asal = (detailBaris && detailBaris.asal) || [];

  var html = '<tr class="baris-asal-hpp"><td></td><td colspan="6" class="p-0 pb-2">';
  html += '<table class="table table-sm table-borderless mb-0" style="width:calc(100% - 1rem); margin-left:1rem">';
  html += '<thead><tr class="text-muted">'+
            '<th class="text-sm" style="width:22%">Sumber Lapisan Stok</th>'+
            '<th class="text-sm">No PRO Asal</th>'+
            '<th class="text-sm text-right">Qty</th>'+
            '<th class="text-sm text-right">Harga Satuan</th>'+
            '<th class="text-sm text-right">Subtotal</th>'+
          '</tr></thead><tbody>';

  if (asal.length === 0) {
    html += '<tr><td class="text-sm" colspan="5">Tidak ada rincian.</td></tr>';
  }

  asal.forEach(function(a){
    var sumberTeks = a.notransaksi
      ? (a.susumber+' - '+a.notransaksi+(a.tanggal ? ' ('+a.tanggal+')' : ''))
      : (a.susumber === 'KOSONG' ? 'Stok kosong (HPP dianggap Rp 0)' : a.susumber);
    html += '<tr>'+
      '<td class="text-sm">'+sumberTeks+'</td>'+
      '<td class="text-sm">'+(a.pronotransaksi || '-')+'</td>'+
      '<td class="text-sm text-right">'+_formatNilai(a.qty)+'</td>'+
      '<td class="text-sm text-right">'+_formatNilai(a.harga)+'</td>'+
      '<td class="text-sm text-right">'+_formatNilai(a.qty*a.harga)+'</td>'+
    '</tr>';
  });

  html += '</tbody></table></td></tr>';
  return html;
}
