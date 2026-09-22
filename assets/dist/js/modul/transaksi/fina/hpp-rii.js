var tabel = null;
var _dataTerkini = [];

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

  $('.datepicker').inputmask({
    alias:'dd/mm/yyyy',
    mask: "1-2-y",
    placeholder: "_",
    leapday: "-02-29",
    separator: "-"
  });

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    orientation: 'bottom',
    container: 'body'
  }).on('show', function(){
    var $input = $(this);
    var offset = $input.offset();
    $('.datepicker.datepicker-dropdown:visible').css({
      'z-index': 99999,
      'top': (offset.top + $input.outerHeight()) + 'px',
      'left': offset.left + 'px'
    });
  }).on('changeDate', function(){
    _cekStatus();
  });

  $('#dtglawal').click(function(){ $('#tglawal').datepicker('show'); });
  $('#dtglakhir').click(function(){ $('#tglakhir').datepicker('show'); });

  $('#tglakhir').datepicker('setDate', sekarang);

  _muatInfoProses();
  _cekStatus();

  $('#bproses').click(function(){
    _prosesUlang();
  });

  $('#blihat').click(function(){
    _lihatLaporan();
  });

  $('#tglawal, #tglakhir').on('blur', function(){
    _cekStatus();
  });

  $(document).on('click', '#b-tutup-detail-hpp-rii, #b-tutup-x-detail-hpp-rii', function(){
    $('#modal-detail-hpp-rii').modal('hide');
  });

  $('#hpp-rii-table').on('click', '.b-detail-hpp-rii', function(){
    var idx = $(this).data('idx');
    _tampilkanDetail(_dataTerkini[idx]);
  });

  $('#detail-hpp-rii-tbody').on('click', '.b-toggle-asal', function(){
    var $icon = $(this);
    var $tr = $icon.closest('tr');
    var sdid = $tr.data('sdid');
    var $next = $tr.next('tr.baris-asal-hpp-rii');

    $('#detail-hpp-rii-tbody tr.baris-asal-hpp-rii').remove();
    $('#detail-hpp-rii-tbody .b-toggle-asal').removeClass('fa-caret-down').addClass('fa-caret-right');

    if ($next.length) { return; } // sudah terbuka sblm diklik -> cukup ditutup (sudah dihapus di atas)

    $icon.removeClass('fa-caret-right').addClass('fa-caret-down');
    var $baris = $('<tr class="baris-asal-hpp-rii"><td></td><td colspan="6" class="p-0 pb-2"><i class="fas fa-circle-notch fa-spin text-sm"></i></td></tr>');
    $tr.after($baris);
    _muatAsal(sdid, $baris);
  });

});

function _muatAsal(sdid, $baris){
  $.ajax({
    "url": base_url+"Fina_HppRii/asal",
    "type": "POST",
    "dataType": "json",
    "data": { sdid: sdid },
    "error": function(){
      $baris.find('td:last').html('<span class="text-sm text-danger">Gagal memuat sumber HPP.</span>');
    },
    "success": function(result){
      if (result.pesan !== 'sukses') {
        $baris.find('td:last').html('<span class="text-sm text-danger">'+(result.error || 'Gagal memuat sumber HPP.')+'</span>');
        return;
      }
      $baris.find('td:last').html(_buatTabelAsal(result.data || []));
    }
  });
}

function _buatTabelAsal(asal){
  var html = '<table class="table table-sm table-borderless mb-0" style="width:calc(100% - 1rem); margin-left:1rem">';
  html += '<thead><tr class="text-muted">'+
            '<th class="text-sm" style="width:22%">Sumber (Harga Beli)</th>'+
            '<th class="text-sm">Tanggal</th>'+
            '<th class="text-sm text-right">Qty</th>'+
            '<th class="text-sm text-right">Harga Satuan</th>'+
            '<th class="text-sm text-right">Subtotal</th>'+
          '</tr></thead><tbody>';

  if (asal.length === 0) {
    html += '<tr><td class="text-sm" colspan="5">Tidak ada rincian (stok kosong saat transaksi ini, HPP dianggap Rp 0).</td></tr>';
  }

  asal.forEach(function(a){
    html += '<tr>'+
      '<td class="text-sm">'+a.susumber+' - '+a.notransaksi+'</td>'+
      '<td class="text-sm">'+a.tanggal+'</td>'+
      '<td class="text-sm text-right">'+_formatNilai(a.qty)+'</td>'+
      '<td class="text-sm text-right">'+_formatNilai(a.harga)+'</td>'+
      '<td class="text-sm text-right">'+_formatNilai(a.qty*a.harga)+'</td>'+
    '</tr>';
  });

  html += '</tbody></table>';
  return html;
}

function _muatInfoProses(){
  $.ajax({
    "url": base_url+"Fina_HppRii/info",
    "type": "POST",
    "dataType": "json",
    "success": function(result){
      if (result.pesan !== 'sukses' || !result.terakhir) {
        $('#info-proses').text('Belum pernah diproses.');
        return;
      }
      $('#info-proses').text(
        'Terakhir diproses: '+result.terakhir+' -- data s/d '+result.sampai+' ('+result.jumlah+' baris).'
      );
    }
  });
}

function _cekStatus(){
  var tglakhir = $('#tglakhir').val();
  var tglawal = $('#tglawal').val();

  $.ajax({
    "url": base_url+"Fina_HppRii/cek",
    "type": "POST",
    "dataType": "json",
    "data": { tglakhir: tglakhir, tglawal: tglawal },
    "success": function(result){
      if (result.pesan !== 'sukses') { $('#status-proses').html(''); return; }

      if (result.baru === 0 && result.usang === 0) {
        $('#status-proses').html('<span class="text-success"><i class="fas fa-check-circle"></i> Sudah sesuai data terkini.</span>');
        return;
      }

      var pesan = [];
      if (result.baru > 0) { pesan.push(result.baru+' transaksi baru belum diproses'); }
      if (result.usang > 0) { pesan.push(result.usang+' transaksi lama sudah berubah/dibatalkan'); }
      $('#status-proses').html('<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> '+pesan.join(', ')+' -- klik "Proses Ulang".</span>');
    }
  });
}

function _prosesUlang(){
  var tglakhir = $('#tglakhir').val();
  var tglawal = $('#tglawal').val();

  $.ajax({
    "url": base_url+"Fina_HppRii/proses",
    "type": "POST",
    "dataType": "json",
    "data": { tglakhir: tglakhir, tglawal: tglawal },
    "beforeSend": function(){
      $("#bproses").prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Memproses...');
    },
    "error": function(xhr, status, error){
      $("#bproses").prop('disabled', false).html('<i class="fas fa-sync"></i> Proses Ulang');
      toastr.error("Gagal memproses : "+xhr.status+" "+error);
    },
    "success": function(result){
      $("#bproses").prop('disabled', false).html('<i class="fas fa-sync"></i> Proses Ulang');

      if (result.pesan !== 'sukses') {
        toastr.error(result.error || 'Gagal memproses HPP.', "", {timeOut: 20000});
        return;
      }

      toastr.success('Berhasil diproses: '+result.jumlah+' baris.');
      _muatInfoProses();
      _cekStatus();
    }
  });
}

function _lihatLaporan(){
  var bulan = $('#bulan').val();
  var tahun = $('#tahun').val();

  if (!tahun || tahun.length !== 4) { toastr.error('Isi Tahun dengan benar (4 digit).'); return; }

  $.ajax({
    "url": base_url+"Fina_HppRii/laporan",
    "type": "POST",
    "dataType": "json",
    "data": { bulan: bulan, tahun: tahun },
    "beforeSend": function(){
      $("#blihat").prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Memuat...');
    },
    "error": function(xhr, status, error){
      $("#blihat").prop('disabled', false).html('<i class="fas fa-search"></i> Lihat Laporan');
      toastr.error("Gagal memuat laporan : "+xhr.status+" "+error);
    },
    "success": function(result){
      $("#blihat").prop('disabled', false).html('<i class="fas fa-search"></i> Lihat Laporan');

      if (result.pesan !== 'sukses') {
        toastr.error(result.error || 'Gagal memuat laporan.', "", {timeOut: 20000});
        return;
      }

      _tampilkanHasil(result.data, result.total_qty, result.total_hpp);

      if (result.data.length === 0) {
        toastr.warning('Tidak ada data pada periode ini (pastikan sudah "Proses Ulang" sampai tanggal yg mencakup periode ini).');
      }
    }
  });
}

function _tampilkanHasil(data, totalQty, totalHpp){
  _dataTerkini = data;

  if (tabel) {
    tabel.destroy();
    $('#hpp-rii-table tbody').remove();
  }

  $('#hpp-rii-table').removeClass('d-none').append('<tbody></tbody>');

  var $tbody = $('#hpp-rii-table tbody');
  data.forEach(function(row, idx){
    var $tr = $('<tr>');
    $tr.append('<td class="text-sm">'+row.ikode+'</td>');
    $tr.append('<td class="text-sm">'+row.inama+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(row.qtyterjual)+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(row.totalhpp)+'</td>');
    $tr.append('<td class="text-sm text-right">'+_formatNilai(row.hpprata2)+'</td>');
    $tr.append('<td class="text-sm text-center"><button type="button" class="btn btn-outline-primary btn-xs b-detail-hpp-rii" data-idx="'+idx+'"><i class="fas fa-search"></i> Rincian</button></td>');
    $tbody.append($tr);
  });

  $('#foot-qty').text(_formatNilai(totalQty));
  $('#foot-hpp').text(_formatNilai(totalHpp));

  tabel = $('#hpp-rii-table').DataTable({
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

  var bulan = $('#bulan').val();
  var tahun = $('#tahun').val();

  $('#detail-hpp-rii-judul').text('Rincian Transaksi — ['+row.ikode+'] '+row.inama);
  var $tbody = $('#detail-hpp-rii-tbody').empty();
  $tbody.append('<tr><td colspan="7" class="text-center text-sm"><i class="fas fa-circle-notch fa-spin"></i> Memuat...</td></tr>');
  $('#modal-detail-hpp-rii').modal('show');

  $.ajax({
    "url": base_url+"Fina_HppRii/detail",
    "type": "POST",
    "dataType": "json",
    "data": { item: row.ikode, bulan: bulan, tahun: tahun },
    "error": function(){
      $tbody.empty().append('<tr><td colspan="7" class="text-center text-sm text-danger">Gagal memuat rincian.</td></tr>');
    },
    "success": function(result){
      if (result.pesan !== 'sukses') {
        $tbody.empty().append('<tr><td colspan="7" class="text-center text-sm text-danger">'+(result.error || 'Gagal memuat rincian.')+'</td></tr>');
        return;
      }

      var detail = result.data || [];
      $tbody.empty();

      detail.forEach(function(d){
        var $tr = $('<tr data-sdid="'+d.sdid+'">');
        $tr.append('<td class="text-center"><i class="fas fa-caret-right text-sm b-toggle-asal" style="cursor:pointer" title="Lihat sumber HPP baris ini"></i></td>');
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
    }
  });
}
