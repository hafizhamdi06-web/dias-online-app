/* ========================================================================================== */
/* File Name : edit-data-pos.js
/* Info Lain : Edit cepat diskon (sddiskonpersen / sddiskon) baris POS pembayaran merchant
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';

toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

$(function() {

  this.addEventListener('contextmenu', function(e){ e.preventDefault(); });

  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Inputmask_Date('.datepicker');

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
  });

  $("#dtgldari").click(function(){ $("#tgldari").datepicker('show'); });
  $("#dtglsampai").click(function(){ $("#tglsampai").datepicker('show'); });

  Component_Select2('#cabang', base_url+"Select_Master/view_gudang_pilihan");
  var _cabangDefault = $("<option selected='selected'></option>")
    .val($('#cabangdefault').val())
    .text($('#namacabangdefault').val());
  $('#cabang').append(_cabangDefault).trigger('change');

  var hariIni = new Date();
  $('#tgldari').datepicker('setDate', hariIni);
  $('#tglsampai').datepicker('setDate', hariIni);

  $('#btampilkan').on('click', _muatData);
  $('#bsimpansemua').on('click', _simpanSemua);

  $('#fnotransaksi').on('keydown', function(e){
    if (e.key === 'Enter' || e.keyCode === 13) { e.preventDefault(); _muatData(); }
  });

  _initSortKolom();
  _initResizeKolom();

  // harga berubah -> kalau ada Disk % 1, sesuaikan Diskon Nilai (= persen * harga); hitung ulang sub total
  $('#tabeledit tbody').on('input', 'input.inp-harga', function(){
    var $tr = $(this).closest('tr');
    var harga = _harga($tr);
    var persen = _num($tr.find('input.inp-persen').val());
    if (harga > 0 && persen > 0) {
      $tr.find('input.inp-nilai').val(Math.round(harga * persen / 100));
    }
    _tandaiBerubah($tr);
  });

  // Disk % 1 berubah -> Diskon Nilai = persen * harga
  $('#tabeledit tbody').on('input', 'input.inp-persen', function(){
    var $tr = $(this).closest('tr');
    var harga = _harga($tr);
    // hanya turunkan Diskon Nilai dari % kalau harga > 0.
    // baris harga 0 (paket/promo, sddiskon negatif) tidak boleh dinolkan.
    if (harga > 0) {
      var persen = _num($(this).val());
      $tr.find('input.inp-nilai').val(Math.round(harga * persen / 100));
    }
    _tandaiBerubah($tr);
  });

  // Diskon Nilai berubah -> Disk % 1 = nilai / harga * 100
  $('#tabeledit tbody').on('input', 'input.inp-nilai', function(){
    var $tr = $(this).closest('tr');
    var harga = _harga($tr);
    var nilai = _num($(this).val());
    var persen = harga > 0 ? (nilai / harga * 100) : 0;
    $tr.find('input.inp-persen').val(_bulat(persen, 2));
    _tandaiBerubah($tr);
  });

  $('#tabeledit tbody').on('click', 'button.btn-simpan-baris', function(){
    _simpanBaris($(this).closest('tr'));
  });

  _muatData();

});

var _num = (v) => {
  if (v === null || v === undefined) return 0;
  v = String(v).replace(/\s/g,'');
  if ((v.match(/,/g) || []).length === 1 && v.indexOf('.') === -1) v = v.replace(',', '.');
  else v = v.replace(/,/g,'');
  var n = parseFloat(v);
  return isNaN(n) ? 0 : n;
};

var _fmt = (n) => (Math.round((Number(n) || 0) * 100) / 100).toLocaleString('id-ID');
var _bulat = (n, d) => { var p = Math.pow(10, d); return Math.round((Number(n) || 0) * p) / p; };

var _qty   = ($tr) => _num($tr.data('qty'));
var _harga = ($tr) => _num($tr.find('input.inp-harga').val());

// Rumus:
//   Diskon Nilai = Disk % 1 / 100 * Harga        (per unit)
//   Sub Total    = Qty * (Harga - Diskon Nilai)
var _subtotalRow = ($tr) => _qty($tr) * (_harga($tr) - _num($tr.find('input.inp-nilai').val()));

var _hitungSubTotal = ($tr) => {
  var sub = _subtotalRow($tr);
  $tr.find('td.col-subtotal').text(_fmt(sub));
  return sub;
};

var _tandaiBerubah = ($tr) => {
  _hitungSubTotal($tr);
  $tr.removeClass('row-tersimpan').addClass('row-berubah');
  _refreshTotal();
};

var _muatData = () => {
  $.ajax({
    "url"    : base_url+"PJ_Edit_Data_POS/getdata",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      tgldari: $('#tgldari').val(),
      tglsampai: $('#tglsampai').val(),
      cabang: $('#cabang').val(),
      notransaksi: $('#fnotransaksi').val()
    },
    "cache"  : false,
    "beforeSend" : function(){ $('#btampilkan').prop('disabled', true); },
    "error"  : function(xhr,status,error){
      $('#btampilkan').prop('disabled', false);
      toastr.error("Gagal mengambil data : "+xhr.status+" "+error);
    },
    "success" : function(result){
      $('#btampilkan').prop('disabled', false);
      _resetIndikatorSort();
      _grupAktif = true; // data baru datang terurut per No Transaksi
      var rows = result.data || [];
      var $body = $('#tabeledit tbody').empty();

      if (!rows.length) {
        $body.append('<tr><td colspan="13" class="text-center text-muted text-sm py-3">Tidak ada data</td></tr>');
        _refreshTotal();
        return;
      }

      rows.forEach(function(r, i){
        // Sub Total awal = Qty * (Harga - Diskon Nilai)
        var subAwal = (Number(r.qty) || 0) * ((Number(r.harga) || 0) - (Number(r.diskon) || 0));
        var $tr = $('<tr>')
          .attr('data-sdid', r.sdid)
          .attr('data-suid', r.suid)
          .attr('data-qty', r.qty)
          .attr('data-harga', r.harga)
          .attr('data-totalawal', r.totaltransaksi)
          .attr('data-merchantjumlah', r.merchantjumlah);

        $tr.append('<td class="d-none">'+r.sdid+'</td>');
        $tr.append('<td class="text-center"><i class="fas fa-caret-right text-sm"></i></td>');
        $tr.append('<td class="text-sm">'+(r.notransaksi || '-')+'</td>');
        $tr.append('<td class="text-sm">'+(r.tanggal || '-')+'</td>');
        $tr.append('<td class="text-sm">'+(r.pasien || '-')+'</td>');
        $tr.append('<td class="text-sm">'+(r.kodeitem || '-')+'</td>');
        $tr.append('<td class="text-sm">'+(r.namaitem || '-')+'</td>');
        $tr.append('<td class="text-sm text-right">'+_fmt(r.qty)+'</td>');
        $tr.append('<td class="text-right"><input type="text" class="form-control form-control-sm inp-edit inp-harga" value="'+(Math.round(Number(r.harga) || 0))+'"></td>');
        $tr.append('<td class="text-right"><input type="text" class="form-control form-control-sm inp-edit inp-persen" value="'+_bulat(r.diskonpersen, 2)+'"></td>');
        $tr.append('<td class="text-right"><input type="text" class="form-control form-control-sm inp-edit inp-nilai" value="'+(Math.round(Number(r.diskon) || 0))+'"></td>');
        $tr.append('<td class="text-sm text-right col-subtotal">'+_fmt(subAwal)+'</td>');
        $tr.append('<td class="text-center"><button type="button" class="btn btn-primary btn-sm btn-simpan-baris py-0"><i class="fas fa-save"></i></button></td>');

        $body.append($tr);
      });

      _refreshTotal();
    }
  });
};

// baris "Total Transaksi" ditampilkan di tiap pergantian No Transaksi
// (hanya bermakna saat data terurut per No Transaksi -> default & sort kolom No Transaksi)
var _grupAktif = true;

var _refreshTotal = () => { _sisipTotalGrup(); _bangunRingkasan(); };

var _sisipTotalGrup = () => {
  var $body = $('#tabeledit tbody');
  $body.children('tr.tr-total-grup').remove();
  if (!_grupAktif) return;

  var $rows = $body.children('tr[data-suid]');
  if (!$rows.length) return;

  var grup = [], cur = null;
  $rows.each(function(){
    var $tr = $(this);
    var suid = String($tr.data('suid'));
    if (!cur || cur.suid !== suid) {
      cur = {
        suid: suid,
        notransaksi: $tr.children().eq(2).text().trim(),
        awal: _num($tr.attr('data-totalawal')),
        merchant: _num($tr.attr('data-merchantjumlah')),
        subtotal: 0,
        $last: $tr
      };
      grup.push(cur);
    }
    cur.subtotal += _subtotalRow($tr);
    cur.merchant = _num($tr.attr('data-merchantjumlah'));
    cur.$last = $tr;
  });

  grup.forEach(function(g){
    var selisih = Math.round(g.subtotal) !== Math.round(g.merchant);
    g.$last.after(
      '<tr class="tr-total-grup'+(selisih ? ' tr-total-selisih' : '')+'">' +
        '<td class="d-none"></td>' +
        '<td colspan="10" class="text-right text-sm font-weight-bold">' +
          'Total ' + g.notransaksi +
          ' &nbsp;&middot;&nbsp; Transaksi Awal: ' + _fmt(g.awal) +
          ' &nbsp;&middot;&nbsp; Merchant: ' + _fmt(g.merchant) +
        '</td>' +
        '<td class="text-right text-sm font-weight-bold">' + _fmt(g.subtotal) + '</td>' +
        '<td></td>' +
      '</tr>'
    );
  });
};

// Ringkasan per No Transaksi: total sub total (live), total transaksi awal, total pembayaran merchant
var _bangunRingkasan = () => {
  var order = [], map = {};

  $('#tabeledit tbody tr[data-suid]').each(function(){
    var $tr = $(this);
    var suid = String($tr.data('suid'));
    if (!map[suid]) {
      map[suid] = {
        notransaksi: $tr.children().eq(2).text().trim(),
        subtotal: 0,
        awal: _num($tr.attr('data-totalawal')),
        merchant: _num($tr.attr('data-merchantjumlah'))
      };
      order.push(suid);
    }
    map[suid].subtotal += _subtotalRow($tr);
    map[suid].merchant  = _num($tr.attr('data-merchantjumlah'));
  });

  var $body = $('#tabelringkasan tbody').empty();
  var tSub = 0, tAwal = 0, tMerc = 0;

  if (!order.length) {
    $body.append('<tr><td colspan="4" class="text-center text-muted text-sm py-2">-</td></tr>');
  } else {
    order.forEach(function(suid){
      var g = map[suid];
      tSub += g.subtotal; tAwal += g.awal; tMerc += g.merchant;
      var selisih = Math.round(g.subtotal) !== Math.round(g.merchant);
      $body.append(
        '<tr class="'+(selisih ? 'rk-selisih' : '')+'">' +
          '<td class="text-sm">'+g.notransaksi+'</td>' +
          '<td class="text-sm text-right">'+_fmt(g.subtotal)+'</td>' +
          '<td class="text-sm text-right">'+_fmt(g.awal)+'</td>' +
          '<td class="text-sm text-right">'+_fmt(g.merchant)+'</td>' +
        '</tr>'
      );
    });
  }

  $('#rk-subtotal').text(_fmt(tSub));
  $('#rk-awal').text(_fmt(tAwal));
  $('#rk-merchant').text(_fmt(tMerc));
};

var _simpanBaris = ($tr, senyap) => {
  var sdid = $tr.data('sdid');
  if (!sdid) return;

  return $.ajax({
    "url"    : base_url+"PJ_Edit_Data_POS/savedata",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      sdid: sdid,
      harga: _num($tr.find('input.inp-harga').val()),
      diskonpersen: _num($tr.find('input.inp-persen').val()),
      diskon: _num($tr.find('input.inp-nilai').val())
    },
    "cache"  : false,
    "beforeSend" : function(){ $tr.find('button.btn-simpan-baris').prop('disabled', true); },
    "error"  : function(xhr,status,error){
      $tr.find('button.btn-simpan-baris').prop('disabled', false);
      toastr.error("Gagal menyimpan : "+xhr.status+" "+error);
    },
    "success" : function(res){
      $tr.find('button.btn-simpan-baris').prop('disabled', false);
      if (res.pesan !== 'sukses') {
        toastr.error(res.pesan || 'Gagal menyimpan.');
        return;
      }
      $tr.find('td.col-subtotal').text(_fmt(res.subtotal));
      $tr.removeClass('row-berubah').addClass('row-tersimpan');
      // segarkan tampilan sumerchantjumlah utk semua baris transaksi yg sama
      // ("Total Transaksi Awal" sengaja dibiarkan = nilai saat data dimuat)
      $('#tabeledit tbody tr[data-suid="'+$tr.data('suid')+'"]').attr('data-merchantjumlah', res.merchantjumlah);
      _refreshTotal();
      if (!senyap) toastr.success('Tersimpan — '+res.notransaksi
        +' | Total: '+_fmt(res.totaltransaksi)
        +' | Tanpa DP: '+_fmt(res.totaltada)
        +' | Merchant: '+_fmt(res.merchantjumlah));
    }
  });
};

/* ---------- Sort per kolom ---------- */

var _resetIndikatorSort = () => {
  $('#tabeledit thead th').removeClass('sort-asc sort-desc')
    .find('i.sort-ind').attr('class', 'fas fa-sort sort-ind');
};

var _nilaiSel = (tr, colIdx, type) => {
  var td = tr.children[colIdx];
  if (!td) return type === 'text' ? '' : 0;
  var inp = td.querySelector('input');
  var raw = ((inp ? inp.value : td.textContent) || '').trim();
  if (type === 'num') return _num(raw);
  if (type === 'date') {
    var m = raw.match(/(\d{2})-(\d{2})-(\d{4})/);
    return m ? Number(m[3] + m[2] + m[1]) : 0;
  }
  return raw.toLowerCase();
};

var _initSortKolom = () => {
  var _abaikanKlik = false;
  $(document).on('edp:resized', function(){ _abaikanKlik = true; setTimeout(function(){ _abaikanKlik = false; }, 0); });

  $('#tabeledit thead').on('click', 'th.th-sort', function(e){
    if (_abaikanKlik || $(e.target).hasClass('col-resizer')) return;

    var $th = $(this);
    var colIdx = $th.index();
    var type = $th.data('sort') || 'text';
    var dir = $th.hasClass('sort-asc') ? -1 : 1;

    _resetIndikatorSort();
    $th.addClass(dir === 1 ? 'sort-asc' : 'sort-desc')
       .find('i.sort-ind').attr('class', 'fas fa-sort-' + (dir === 1 ? 'up' : 'down') + ' sort-ind');

    var $body = $('#tabeledit tbody');
    $body.children('tr.tr-total-grup').remove();
    var rows = $body.children('tr[data-suid]').get();
    if (rows.length < 2) { _refreshTotal(); return; }

    rows.sort(function(a, b){
      var va = _nilaiSel(a, colIdx, type), vb = _nilaiSel(b, colIdx, type);
      if (va < vb) return -1 * dir;
      if (va > vb) return  1 * dir;
      return 0;
    });
    rows.forEach(function(tr){ $body.append(tr); });

    // baris Total per No Transaksi hanya bermakna saat diurutkan per No Transaksi
    _grupAktif = (colIdx === 2);
    _refreshTotal();
  });
};

/* ---------- Lebar kolom bisa digeser ---------- */

var _initResizeKolom = () => {
  $('#tabeledit thead th.th-sort').append('<span class="col-resizer"></span>');

  var drag = null;
  $('#tabeledit thead').on('mousedown', '.col-resizer', function(e){
    e.preventDefault();
    e.stopPropagation();
    var th = this.parentNode;
    drag = { th: th, x: e.pageX, w: th.offsetWidth, moved: false };
    $('body').addClass('col-resizing');
  });
  $(document).on('mousemove.edpresize', function(e){
    if (!drag) return;
    var w = Math.max(40, drag.w + (e.pageX - drag.x));
    drag.th.style.width = w + 'px';
    drag.moved = true;
  });
  $(document).on('mouseup.edpresize', function(){
    if (!drag) return;
    $('body').removeClass('col-resizing');
    if (drag.moved) $(document).trigger('edp:resized');
    drag = null;
  });
};

var _simpanSemua = () => {
  var $rows = $('#tabeledit tbody tr.row-berubah');
  if (!$rows.length) { toastr.info('Tidak ada baris yang berubah.'); return; }
  if (!confirm('Simpan '+$rows.length+' baris yang berubah?')) return;

  $('#bsimpansemua').prop('disabled', true);
  var chain = $.Deferred().resolve();
  $rows.each(function(){
    var $tr = $(this);
    chain = chain.then(function(){ return _simpanBaris($tr, true); });
  });
  chain.always(function(){
    $('#bsimpansemua').prop('disabled', false);
    toastr.success('Selesai menyimpan '+$rows.length+' baris.');
  });
};
