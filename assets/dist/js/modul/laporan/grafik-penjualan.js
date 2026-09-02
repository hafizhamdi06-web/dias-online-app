/* ========================================================================================== */
/* File Name : grafik-penjualan.js
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../component.js';
import { Component_Select2 } from '../component.js';
import { Component_Scrollbars } from '../component.js';

toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

var _chartInstances = {};

$(function() {

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

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

  $("#dtgldari").click(function() {
    $("#tgldari").datepicker('show');
  });

  $("#dtglsampai").click(function() {
    $("#tglsampai").datepicker('show');
  });

  Component_Select2('#cabang', base_url+"Select_Master/view_gudang_pilihan");

  var _cabangDefault = $("<option selected='selected'></option>")
    .val($('#cabangdefault').val())
    .text($('#namacabangdefault').val());
  $('#cabang').append(_cabangDefault).trigger('change');

  _defaultRentangTanggal();

  $('#btampilkan').on('click', function(){
    _muatGrafik();
    _muatRingkasan();
    _muatTopPasien();
    _muatTopProduk();
    _muatTopProdukQty();
  });

  $('#bexportpdf').on('click', function(){
    var cabangText = $('#cabang').find(':selected').text();
    var ringkasan = 'Grafik Penjualan — Periode: '+$('#tgldari').val()+' s/d '+$('#tglsampai').val()+' — Cabang: '+cabangText;
    $('#print-summary').text(ringkasan);
    window.print();
  });

  _muatGrafik();
  _muatRingkasan();
  _muatTopPasien();
  _muatTopProduk();
  _muatTopProdukQty();

});

var _defaultRentangTanggal = () => {
  var hariIni = new Date();
  var awalTahun = new Date(hariIni.getFullYear(), 0, 1);
  $('#tgldari').datepicker('setDate', awalTahun);
  $('#tglsampai').datepicker('setDate', hariIni);
};

var _namaBulan = (bulan) => {
  var parts = String(bulan).split('-');
  var namaBulanArr = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  var idx = Number(parts[1]) - 1;
  return (namaBulanArr[idx] || parts[1]) + ' ' + parts[0];
};

var _formatRibuan = (n) => {
  return Math.round(Number(n) || 0).toLocaleString('id-ID');
};

var _renderChart = (canvasId, label, labels, data, warna) => {
  if (_chartInstances[canvasId]) {
    _chartInstances[canvasId].destroy();
  }

  var ctx = document.getElementById(canvasId).getContext('2d');
  _chartInstances[canvasId] = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: label,
        data: data,
        borderColor: warna,
        backgroundColor: warna,
        pointBackgroundColor: warna,
        fill: false,
        lineTension: 0.2
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: { display: false },
      scales: {
        yAxes: [{
          ticks: {
            beginAtZero: true,
            callback: function(value) { return _formatRibuan(value); }
          }
        }]
      },
      tooltips: {
        callbacks: {
          label: function(item) { return label+': '+_formatRibuan(item.yLabel); }
        }
      }
    }
  });
};

var _muatGrafik = () => {
  $.ajax({
    "url"    : base_url+"Grafik_Penjualan/getdata",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      tgldari: $('#tgldari').val(),
      tglsampai: $('#tglsampai').val(),
      cabang: $('#cabang').val()
    },
    "cache"  : false,
    "beforeSend" : function(){
      $('#btampilkan').prop('disabled', true);
    },
    "error"  : function(xhr,status,error){
      $('#btampilkan').prop('disabled', false);
      toastr.error("Gagal mengambil data grafik : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      $('#btampilkan').prop('disabled', false);

      var rows = result.data || [];
      var labels = rows.map(function(r){ return _namaBulan(r.bulan); });
      var omzet = rows.map(function(r){ return Number(r.omzet) || 0; });
      var jumlahtransaksi = rows.map(function(r){ return Number(r.jumlahtransaksi) || 0; });
      var jumlahpasien = rows.map(function(r){ return Number(r.jumlahpasien) || 0; });

      _renderChart('chart-omzet', 'Omzet', labels, omzet, '#28a745');
      _renderChart('chart-transaksi', 'Jumlah Transaksi', labels, jumlahtransaksi, '#17a2b8');
      _renderChart('chart-pasien', 'Jumlah Pasien', labels, jumlahpasien, '#ffc107');
    }
  });
};

var _muatTopPasien = () => {
  $.ajax({
    "url"    : base_url+"Grafik_Penjualan/toppasien",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      tgldari: $('#tgldari').val(),
      tglsampai: $('#tglsampai').val(),
      cabang: $('#cabang').val()
    },
    "cache"  : false,
    "error"  : function(xhr,status,error){
      toastr.error("Gagal mengambil data top pasien : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      var rows = result.data || [];
      var $body = $('#tabel-top-pasien tbody').empty();

      if (!rows.length) {
        $body.append('<tr><td colspan="5" class="text-center text-muted text-sm">Tidak ada data</td></tr>');
        return;
      }

      rows.forEach(function(r, i){
        var tr = '<tr>'
          + '<td class="text-sm text-center">'+(i+1)+'</td>'
          + '<td class="text-sm">'+(r.nama || '-')+'</td>'
          + '<td class="text-sm">'+(r.idpasien || '-')+'</td>'
          + '<td class="text-sm">'+(r.nohp || '-')+'</td>'
          + '<td class="text-sm text-right">'+_formatRibuan(r.total)+'</td>'
          + '</tr>';
        $body.append(tr);
      });
    }
  });
};

var _muatTopProduk = () => {
  $.ajax({
    "url"    : base_url+"Grafik_Penjualan/topproduk",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      tgldari: $('#tgldari').val(),
      tglsampai: $('#tglsampai').val(),
      cabang: $('#cabang').val()
    },
    "cache"  : false,
    "error"  : function(xhr,status,error){
      toastr.error("Gagal mengambil data top produk : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      var rows = result.data || [];
      var $body = $('#tabel-top-produk tbody').empty();

      if (!rows.length) {
        $body.append('<tr><td colspan="5" class="text-center text-muted text-sm">Tidak ada data</td></tr>');
        return;
      }

      rows.forEach(function(r, i){
        var tr = '<tr>'
          + '<td class="text-sm text-center">'+(i+1)+'</td>'
          + '<td class="text-sm">'+(r.kode || '-')+'</td>'
          + '<td class="text-sm">'+(r.nama || '-')+'</td>'
          + '<td class="text-sm text-right">'+_formatRibuan(r.qty)+'</td>'
          + '<td class="text-sm text-right">'+_formatRibuan(r.total)+'</td>'
          + '</tr>';
        $body.append(tr);
      });
    }
  });
};

var _muatTopProdukQty = () => {
  $.ajax({
    "url"    : base_url+"Grafik_Penjualan/topprodukqty",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      tgldari: $('#tgldari').val(),
      tglsampai: $('#tglsampai').val(),
      cabang: $('#cabang').val()
    },
    "cache"  : false,
    "error"  : function(xhr,status,error){
      toastr.error("Gagal mengambil data top produk qty : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      var rows = result.data || [];
      var $body = $('#tabel-top-produk-qty tbody').empty();

      if (!rows.length) {
        $body.append('<tr><td colspan="5" class="text-center text-muted text-sm">Tidak ada data</td></tr>');
        return;
      }

      rows.forEach(function(r, i){
        var tr = '<tr>'
          + '<td class="text-sm text-center">'+(i+1)+'</td>'
          + '<td class="text-sm">'+(r.kode || '-')+'</td>'
          + '<td class="text-sm">'+(r.nama || '-')+'</td>'
          + '<td class="text-sm text-right">'+_formatRibuan(r.qty)+'</td>'
          + '<td class="text-sm text-right">'+_formatRibuan(r.total)+'</td>'
          + '</tr>';
        $body.append(tr);
      });
    }
  });
};

var _muatRingkasan = () => {
  $.ajax({
    "url"    : base_url+"Grafik_Penjualan/ringkasan",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : {
      cabang: $('#cabang').val()
    },
    "cache"  : false,
    "error"  : function(xhr,status,error){
      toastr.error("Gagal mengambil ringkasan : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      var rows = result.data || [];
      var row = rows[0] || {};

      $('#ib-omzet-hari-ini').text(_formatRibuan(row.omzethariini));
      $('#ib-omzet-bulan-ini').text(_formatRibuan(row.omzetbulanini));
      $('#ib-pasien-hari-ini').text(_formatRibuan(row.pasienhariini));
      $('#ib-pasien-bulan-ini').text(_formatRibuan(row.pasienbulanini));
    }
  });
};
