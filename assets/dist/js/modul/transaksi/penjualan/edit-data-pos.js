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

  // harga berubah -> kalau ada Disk % 1, sesuaikan Diskon Nilai; hitung ulang sub total
  $('#tabeledit tbody').on('input', 'input.inp-harga', function(){
    var $tr = $(this).closest('tr');
    var dasar = _dasar($tr);
    var persen = _num($tr.find('input.inp-persen').val());
    if (dasar > 0 && persen > 0) {
      $tr.find('input.inp-nilai').val(Math.round(dasar * persen / 100));
    }
    _tandaiBerubah($tr);
  });

  // input diskon berubah -> hitung ulang pasangan & sub total
  $('#tabeledit tbody').on('input', 'input.inp-persen', function(){
    var $tr = $(this).closest('tr');
    var dasar = _dasar($tr);
    // hanya turunkan Diskon Nilai dari % kalau ada dasar (qty*harga) > 0.
    // baris harga 0 (paket/promo, sddiskon negatif) tidak boleh dinolkan.
    if (dasar > 0) {
      var persen = _num($(this).val());
      $tr.find('input.inp-nilai').val(Math.round(dasar * persen / 100));
    }
    _tandaiBerubah($tr);
  });

  $('#tabeledit tbody').on('input', 'input.inp-nilai', function(){
    var $tr = $(this).closest('tr');
    var dasar = _dasar($tr);
    var nilai = _num($(this).val());
    var persen = dasar > 0 ? (nilai / dasar * 100) : 0;
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

var _dasar = ($tr) => _num($tr.data('qty')) * _num($tr.find('input.inp-harga').val());

var _hitungSubTotal = ($tr) => {
  var sub = _dasar($tr) - _num($tr.find('input.inp-nilai').val());
  $tr.find('td.col-subtotal').text(_fmt(sub));
  return sub;
};

var _tandaiBerubah = ($tr) => {
  _hitungSubTotal($tr);
  $tr.removeClass('row-tersimpan').addClass('row-berubah');
};

var _muatData = () => {
  $.ajax({
    "url"    : base_url+"PJ_Edit_Data_POS/getdata",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      tgldari: $('#tgldari').val(),
      tglsampai: $('#tglsampai').val(),
      cabang: $('#cabang').val()
    },
    "cache"  : false,
    "beforeSend" : function(){ $('#btampilkan').prop('disabled', true); },
    "error"  : function(xhr,status,error){
      $('#btampilkan').prop('disabled', false);
      toastr.error("Gagal mengambil data : "+xhr.status+" "+error);
    },
    "success" : function(result){
      $('#btampilkan').prop('disabled', false);
      var rows = result.data || [];
      var $body = $('#tabeledit tbody').empty();

      if (!rows.length) {
        $body.append('<tr><td colspan="13" class="text-center text-muted text-sm py-3">Tidak ada data</td></tr>');
        return;
      }

      rows.forEach(function(r, i){
        var dasar = (Number(r.qty) || 0) * (Number(r.harga) || 0);
        var $tr = $('<tr>')
          .attr('data-sdid', r.sdid)
          .attr('data-suid', r.suid)
          .attr('data-qty', r.qty)
          .attr('data-harga', r.harga);

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
        $tr.append('<td class="text-sm text-right col-subtotal">'+_fmt(dasar - (Number(r.diskon) || 0))+'</td>');
        $tr.append('<td class="text-center"><button type="button" class="btn btn-primary btn-sm btn-simpan-baris py-0"><i class="fas fa-save"></i></button></td>');

        $body.append($tr);
      });
    }
  });
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
      $('#tabeledit tbody tr[data-suid="'+$tr.data('suid')+'"]').attr('data-merchantjumlah', res.merchantjumlah);
      if (!senyap) toastr.success('Tersimpan — '+res.notransaksi
        +' | Total: '+_fmt(res.totaltransaksi)
        +' | Tanpa DP: '+_fmt(res.totaltada)
        +' | Merchant: '+_fmt(res.merchantjumlah));
    }
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
