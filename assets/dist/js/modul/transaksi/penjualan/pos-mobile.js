/* ========================================================================================== */
/* File Name : pos-mobile.js
/* ========================================================================================== */

toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

var items = [];
var itemSeq = 0;
var pasienDiscount = 0;
var editId = '';

$(function() {

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $('#pasien').select2({
    "theme": "bootstrap4",
    "placeholder": "Cari nama pasien...",
    "allowClear": true,
    "minimumInputLength": 3,
    "ajax": {
      "url": base_url+"Select_Master/view_pasien",
      "type": "post",
      "dataType": "json",
      "delay": 500,
      "data": (params) => {
        return { search: params.term };
      },
      "processResults": (data) => {
        return { results: data };
      }
    }
  });

  $('#carinama').select2({
    "theme": "bootstrap4",
    "placeholder": "Cari nama/kode item...",
    "allowClear": true,
    "minimumInputLength": 3,
    "ajax": {
      "url": base_url+"Select_Master/view_item",
      "type": "post",
      "dataType": "json",
      "delay": 500,
      "data": (params) => {
        return { search: params.term };
      },
      "processResults": (data) => {
        return { results: data };
      }
    }
  });

  $('#pasien').on('select2:select', function(e){
    var data = e.params.data;
    $('#idkontak').val(data.id);
    _ambilDetailPasien(data.id);
  });

  $('#pasien').on('select2:clear', function(){
    $('#idkontak').val('');
    $('#kontaktipe').val('');
    $('#pasien-info').addClass('d-none');
    $('#pasien-kategori').addClass('d-none');
    $('#pasien-member').addClass('d-none');
    pasienDiscount = 0;
  });

  $('#carinama').on('select2:select', function(e){
    var data = e.params.data;
    _ambilDetailItem(data.id, data.text);
    $('#carinama').val(null).trigger('change');
  });

  $('#pm-debit-bank, #pm-kredit-bank, #pm-transfer-bank').select2({
    "theme": "bootstrap4",
    "placeholder": "Pilih bank...",
    "allowClear": true,
    "ajax": {
      "url": base_url+"Select_Master/view_bank2",
      "type": "post",
      "dataType": "json",
      "delay": 500,
      "data": (params) => {
        return { search: params.term };
      },
      "processResults": (data) => {
        return { results: data };
      }
    }
  });

  $('#pm-merchant-jenis').select2({
    "theme": "bootstrap4",
    "placeholder": "Pilih jenis merchant...",
    "allowClear": true,
    "ajax": {
      "url": base_url+"Select_Master/view_merchant",
      "type": "post",
      "dataType": "json",
      "delay": 500,
      "data": (params) => {
        return { search: params.term };
      },
      "processResults": (data) => {
        return { results: data };
      }
    }
  });

  $(".pm-metode-btn").on('click', function(){
    var metode = $(this).data('metode');
    var $group = $(".pm-metode-fields[data-metode-fields='"+metode+"']");

    if ($(this).hasClass('pm-metode-active')) {
      $(this).removeClass('pm-metode-active');
      $group.addClass('d-none');
      $("#pm-"+metode+"-nilai").val(0);
      $("#pm-"+metode+"-no").val('');
      if ($("#pm-"+metode+"-bank").length) $("#pm-"+metode+"-bank").val(null).trigger('change');
      if ($("#pm-"+metode+"-jenis").length) $("#pm-"+metode+"-jenis").val(null).trigger('change');
    } else {
      $(this).addClass('pm-metode-active');
      $group.removeClass('d-none');
    }
    _hitungTotal();
  });

  $(".pm-metode-nilai").on('input', function(){
    var raw = $(this).val().replace(/[^0-9]/g, '');
    $(this).val(raw === '' ? '' : _formatRibuan(raw));
    _hitungTotal();
  });

  $(".pm-btn-sisa").on('click', function(){
    var metode = $(this).data('metode');
    var total = items.reduce((sum, i) => sum + _subtotalItem(i), 0);
    var totalMetodeLain = _metodeList.reduce(function(sum, m){
      if (m === metode) return sum;
      return sum + (_isMetodeActive(m) ? _getMetodeNilai(m) : 0);
    }, 0);
    var sisa = total - totalMetodeLain;
    if (sisa < 0) sisa = 0;
    $("#pm-"+metode+"-nilai").val(_formatRibuan(sisa));
    _hitungTotal();
  });

  $('#bsimpan').on('click', _simpanTransaksi);

  $('#briwayat').on('click', function(){
    $('.pm-content').addClass('d-none');
    $('.pm-footer').addClass('d-none');
    $('#pm-riwayat-panel').removeClass('d-none');
    _muatRiwayatHariIni();
  });

  $('#briwayat-tutup').on('click', function(){
    $('#pm-riwayat-panel').addClass('d-none');
    $('.pm-content').removeClass('d-none');
    $('.pm-footer').removeClass('d-none');
  });

  $(".pm-lebar-btn").on('click', function(){
    var lebar = $(this).data('lebar');
    try { localStorage.setItem(_LEBAR_KEY, lebar); } catch(e) {}
    _terapkanLebarStruk(lebar);
  });

  $('#pm-edit-batal').on('click', function(){
    _resetForm();
  });

  _terapkanLebarStruk(_ambilLebarStruk());

  _hitungTotal();

});

var _LEBAR_KEY = 'pm_struk_lebar';

var _ambilLebarStruk = () => {
  try {
    var v = localStorage.getItem(_LEBAR_KEY);
    return (v === '58' || v === '80') ? v : '80';
  } catch(e) {
    return '80';
  }
};

var _terapkanLebarStruk = (lebar) => {
  $('body').removeClass('pm-lebar-58 pm-lebar-80').addClass('pm-lebar-'+lebar);
  $(".pm-lebar-btn").removeClass('pm-lebar-active');
  $(".pm-lebar-btn[data-lebar='"+lebar+"']").addClass('pm-lebar-active');
  $('#pm-page-size-style').text('@page { size: '+lebar+'mm auto; margin: 0; }');
};

var _metodeList = ['tunai', 'debit', 'kredit', 'transfer', 'merchant'];

var _isMetodeActive = (metode) => {
  return $(".pm-metode-btn[data-metode='"+metode+"']").hasClass('pm-metode-active');
};

var _getMetodeNilai = (metode) => {
  return _parseAngka($("#pm-"+metode+"-nilai").val());
};

var _formatRibuan = (n) => {
  return Math.round(Number(n) || 0).toLocaleString('id-ID');
};

var _parseAngka = (str) => {
  return Number(String(str || '').replace(/[^0-9]/g, '')) || 0;
};

var _ambilDetailPasien = (idkontak) => {
  $.ajax({
    "url"    : base_url+"PJ_POS_HP/get_detail_pasien",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : "id="+idkontak,
    "cache"  : false,
    "beforeSend" : function(){
      $("#loader").removeClass('d-none');
    },
    "error"  : function(xhr,status,error){
      $("#loader").addClass('d-none');
      toastr.error("Gagal mengambil data pasien : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      $("#loader").addClass('d-none');

      if (typeof result.pesan !== 'undefined') {
        toastr.error(result.pesan);
        return;
      }

      var row = result.data[0];
      $('#kontaktipe').val(row.tipeid);
      $('#pasien-idpasien').text(row.pasienid || '-');
      $('#pasien-info').removeClass('d-none');

      if (row.namatipe) {
        $('#pasien-kategori').text(row.namatipe).removeClass('d-none');
      } else {
        $('#pasien-kategori').addClass('d-none');
      }

      // Khusus kategori MEMBER (ktipe=12): cek status aktif/expired dari
      // f_tanggal_akhir + 1 tahun, sama seperti POS desktop. Kategori
      // diskon lain (mis. GOGOBLI/KELUARGA) tidak dikenai pengecekan ini.
      var memberAktif = true;
      $('#pasien-member').addClass('d-none');

      if (row.tipeid == 12) {
        var tglexpired = _parseTglYYYYMMDD(row.tglexpired);
        var tglexpiredText = _formatTglDDMMYYYY(tglexpired);
        var hariIni = new Date();
        hariIni.setHours(0,0,0,0);

        if (!tglexpired) {
          memberAktif = false;
        } else {
          tglexpired.setHours(0,0,0,0);
          memberAktif = (tglexpired >= hariIni);
        }

        if (memberAktif) {
          $('#pasien-member').text('Member Aktif, Expired '+tglexpiredText).removeClass('d-none pm-badge-member-off').addClass('pm-badge-member-on');
        } else {
          var teksTidakAktif = tglexpiredText ? ('Member Tidak Aktif, Terakhir '+tglexpiredText) : 'Member Tidak Aktif';
          $('#pasien-member').text(teksTidakAktif).removeClass('d-none pm-badge-member-on').addClass('pm-badge-member-off');
        }
      }

      pasienDiscount = memberAktif ? (Number(row.ktdiscount) || 0) : 0;
      if (pasienDiscount > 0) {
        $('#pasien-diskon').text('Diskon Member '+pasienDiscount+'%').removeClass('d-none');
      } else {
        $('#pasien-diskon').addClass('d-none');
      }
    }
  });
};

var _parseTglYYYYMMDD = (str) => {
  if (!str) return null;
  var parts = str.split(' ')[0].split('-');
  if (parts.length !== 3) return null;
  return new Date(parts[0], parts[1]-1, parts[2]);
};

var _formatTglDDMMYYYY = (dateObj) => {
  if (!dateObj) return '';
  var dd = String(dateObj.getDate()).padStart(2, '0');
  var mm = String(dateObj.getMonth()+1).padStart(2, '0');
  var yyyy = dateObj.getFullYear();
  return dd+'-'+mm+'-'+yyyy;
};

var _ambilDetailItem = (iid, namaitem) => {
  $.ajax({
    "url"    : base_url+"PJ_POS_HP/get_item",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : "id="+iid+"&kontak="+$('#idkontak').val(),
    "cache"  : false,
    "beforeSend" : function(){
      $("#loader").removeClass('d-none');
    },
    "error"  : function(xhr,status,error){
      $("#loader").addClass('d-none');
      toastr.error("Gagal mengambil data item : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      $("#loader").addClass('d-none');

      var row = result.data[0];
      itemSeq++;

      var idiskon = Number(row.diskon) || 0;
      var dis1 = (pasienDiscount > 0 && $('#cabang').val() != 18) ? idiskon : 0;

      items.push({
        seq: itemSeq,
        iid: iid,
        namaitem: namaitem,
        qty: 1,
        harga: Number(row.hargajual) || 0,
        dis1: dis1,
        idsatuan: row.idsatuan,
        namasatuan: row.namasatuan
      });

      _renderItemList();
      _hitungTotal();
    }
  });
};

var _diskonNominal = (item) => {
  if (item.dis1 > 0 && item.harga > 0 && item.qty > 0) {
    return (item.dis1/100) * item.harga;
  }
  return 0;
};

var _subtotalItem = (item) => {
  var diskon = _diskonNominal(item);
  return (item.harga - diskon) * item.qty;
};

var _renderItemList = () => {
  $("#pm-itemlist").html('');

  if (items.length === 0) {
    $("#pm-item-empty").removeClass('d-none');
    return;
  }
  $("#pm-item-empty").addClass('d-none');

  items.forEach(function(row){
    var card =
      '<div class="pm-item-card" data-seq="'+row.seq+'">' +
        '<div class="pm-item-nama">'+row.namaitem+'</div>' +
        '<div class="pm-item-row">' +
          '<div class="pm-item-field pm-item-field-qty">' +
            '<label>Qty</label>' +
            '<input type="text" class="pm-item-qty" data-seq="'+row.seq+'" value="'+row.qty+'" inputmode="numeric">' +
          '</div>' +
          '<div class="pm-item-field">' +
            '<label>Harga</label>' +
            '<input type="text" class="pm-item-harga" data-seq="'+row.seq+'" value="'+_formatRibuan(row.harga)+'" inputmode="numeric" readonly>' +
          '</div>' +
          '<div class="pm-item-field pm-item-field-dis1">' +
            '<label>Dis 1 (%)</label>' +
            '<input type="text" class="pm-item-dis1" data-seq="'+row.seq+'" value="'+row.dis1+'" inputmode="numeric" readonly>' +
          '</div>' +
          '<div class="pm-item-field pm-item-field-subtotal">' +
            '<label>Subtotal</label>' +
            '<input type="text" class="pm-item-subtotal-val" readonly value="'+_formatRupiah(_subtotalItem(row))+'" data-subtotal-seq="'+row.seq+'">' +
          '</div>' +
          '<button type="button" class="pm-item-hapus" data-seq="'+row.seq+'"><i class="fas fa-trash"></i></button>' +
        '</div>' +
      '</div>';
    $("#pm-itemlist").append(card);
  });

  $(".pm-item-qty").off('input').on('input', function(){
    var cleaned = $(this).val().replace(/[^0-9]/g, '');
    if (cleaned !== $(this).val()) $(this).val(cleaned);

    var seq = Number($(this).data('seq'));
    var item = items.find(i => i.seq===seq);
    if (item) {
      item.qty = Number(cleaned) || 0;
      $("[data-subtotal-seq='"+seq+"']").val(_formatRupiah(_subtotalItem(item)));
      _hitungTotal();
    }
  });

  $(".pm-item-hapus").off('click').on('click', function(){
    var seq = Number($(this).data('seq'));
    items = items.filter(i => i.seq!==seq);
    _renderItemList();
    _hitungTotal();
  });
};

var _hitungTotal = () => {
  var total = items.reduce((sum, i) => sum + _subtotalItem(i), 0);
  $("#pm-total").text(_formatRupiah(total));

  var totalDibayar = _metodeList.reduce(function(sum, m){
    return sum + (_isMetodeActive(m) ? _getMetodeNilai(m) : 0);
  }, 0);
  $("#pm-totaldibayar").text(_formatRupiah(totalDibayar));

  var selisih = totalDibayar - total;
  if (selisih > 0) {
    $("#pm-kembali").text(_formatRupiah(selisih));
    $("#pm-row-kembali").removeClass('d-none');
    $("#pm-row-kurang").addClass('d-none');
  } else if (selisih < 0) {
    $("#pm-kurang").text(_formatRupiah(Math.abs(selisih)));
    $("#pm-row-kurang").removeClass('d-none');
    $("#pm-row-kembali").addClass('d-none');
  } else {
    $("#pm-row-kembali").addClass('d-none');
    $("#pm-row-kurang").addClass('d-none');
  }
};

var _formatRupiah = (angka) => {
  return Number(angka || 0).toLocaleString('id-ID', {minimumFractionDigits: 2, maximumFractionDigits: 2});
};

var _simpanTransaksi = () => {
  if (!$('#idkontak').val()) {
    toastr.error("Pilih pasien terlebih dahulu !");
    return;
  }
  if (items.length === 0) {
    toastr.error("Tambahkan minimal 1 item !");
    return;
  }

  var total = items.reduce((sum, i) => sum + _subtotalItem(i), 0);

  var totalDibayar = 0;
  for (var mi=0; mi<_metodeList.length; mi++) {
    var metode = _metodeList[mi];
    if (!_isMetodeActive(metode)) continue;
    var nilai = _getMetodeNilai(metode);
    if (nilai <= 0) continue;
    totalDibayar += nilai;

    if ((metode==='debit' || metode==='kredit' || metode==='transfer') && !$("#pm-"+metode+"-bank").val()) {
      toastr.error("Pilih Bank untuk metode "+metode.toUpperCase()+" !");
      return;
    }
    if (metode==='merchant' && !$("#pm-merchant-jenis").val()) {
      toastr.error("Pilih Jenis Merchant !");
      return;
    }
  }

  if (totalDibayar <= 0) {
    toastr.error("Pilih metode pembayaran dan isi nilainya !");
    return;
  }
  if (totalDibayar < total) {
    toastr.error("Total pembayaran kurang dari total transaksi !");
    return;
  }

  var detil = items.map(function(row){
    return {
      item: row.iid,
      qty: row.qty,
      harga: row.harga,
      dis1: row.dis1,
      dis2: 0,
      diskon: _diskonNominal(row),
      diskon2: 0,
      satuan: row.idsatuan,
      dokter: null,
      operator: null,
      paket: '',
      promo: '',
      referal: '',
      aos: '',
      recom: '',
      noref: '',
      noic: '',
      nopaketdetil: '',
      kedatanganke: 0,
      idpaketdetil: '',
      daripaket: '',
      medidu: '',
      medidd: '',
      proidu: '',
      proidd: '',
      cetak: 1,
      idvoucherwebdetil: '',
      pointvoucherwebdetil: 0,
      medidu_sudahbayar: '',
      medidd_sudahbayar: ''
    };
  });

  var rey = new FormData();
  rey.set('id', editId || '');
  rey.set('nomor', '');
  rey.set('nomorlama', '');
  rey.set('tgl', $('#tgl').val());
  rey.set('kontak', $('#idkontak').val());
  rey.set('kontaktipe', $('#kontaktipe').val());
  rey.set('karyawan', $('#karyawan').val());
  rey.set('catatan', '');

  rey.set('tsubtotal', total);
  rey.set('totalbayar', totalDibayar);
  rey.set('totalsisa', totalDibayar - total);

  rey.set('kasjumlah', _getMetodeNilai('tunai'));

  rey.set('debitjumlah', _getMetodeNilai('debit')); rey.set('debitno', $('#pm-debit-no').val() || ''); rey.set('debitnama',''); rey.set('debitbank', $('#pm-debit-bank').val() || ''); rey.set('debitjenis',''); rey.set('debitbanklain','');
  rey.set('kreditjumlah', _getMetodeNilai('kredit')); rey.set('kreditno', $('#pm-kredit-no').val() || ''); rey.set('kreditnama',''); rey.set('kreditbank', $('#pm-kredit-bank').val() || ''); rey.set('kreditjenis',''); rey.set('kreditbanklain','');
  rey.set('transferjumlah', _getMetodeNilai('transfer')); rey.set('transferno', $('#pm-transfer-no').val() || ''); rey.set('transfernama',''); rey.set('transferbank', $('#pm-transfer-bank').val() || '');

  rey.set('voucherid', ''); rey.set('voucherno', ''); rey.set('voucherjumlah', 0);

  rey.set('dpid', ''); rey.set('dpjenis', ''); rey.set('dpjumlah', 0);

  rey.set('totaltanpadp', total);
  rey.set('cabang', $('#cabang').val());
  rey.set('rekammedis', '');

  rey.set('merchantjenis', $('#pm-merchant-jenis').val() || ''); rey.set('merchantno', $('#pm-merchant-no').val() || ''); rey.set('merchantjumlah', _getMetodeNilai('merchant'));

  rey.set('training', ''); rey.set('farmasi', ''); rey.set('farmasiasisten', ''); rey.set('salesmarketing', ''); rey.set('kliniklain', '');
  rey.set('dkkwalkin', '');

  rey.set('piutangjumlah', 0);
  rey.set('surgerydpidu', 0); rey.set('surgerydptotal', 0); rey.set('surgerydppembayaran', 0); rey.set('surgerydppiutang', 0);

  rey.set('reviewnilai', ''); rey.set('reviewcatatan', ''); rey.set('kodetele', '');
  rey.set('medid', ''); rey.set('idmedlib', ''); rey.set('teman', ''); rey.set('appcanceltransaksi', '');
  rey.set('alasanedit', ''); rey.set('lmcidpro', '');

  rey.set('detil', JSON.stringify(detil));
  rey.set('detil_v', JSON.stringify([]));

  $.ajax({
    "url"    : base_url+"PJ_POS_HP/savedata",
    "type"   : "POST",
    "data"   : rey,
    "processData": false,
    "contentType": false,
    "cache"    : false,
    "beforeSend" : function(){
      $("#loader").removeClass('d-none');
      $("#bsimpan").prop('disabled', true);
    },
    "error": function(xhr, status, error){
      $("#loader").addClass('d-none');
      $("#bsimpan").prop('disabled', false);
      toastr.error("Error : "+xhr.status+", "+error);
      console.log(xhr.responseText);
    },
    "success": function(result) {
      $("#loader").addClass('d-none');
      $("#bsimpan").prop('disabled', false);

      var parsed;
      try {
        parsed = JSON.parse(result);
      } catch(e) {
        toastr.error("Gagal membaca response server.");
        console.log(result);
        return;
      }

      if (parsed.pesan === 'sukses') {
        var idTransaksiBaru = parsed.nomor;
        Swal.fire({
          title: 'Transaksi berhasil disimpan',
          icon: 'success',
          showCancelButton: true,
          confirmButtonText: 'Cetak Struk',
          cancelButtonText: 'Tutup'
        }).then(function(r){
          if (r.isConfirmed) {
            _cetakStruk(idTransaksiBaru);
          }
          _resetForm();
        });
      } else {
        toastr.error("Gagal menyimpan transaksi, silakan coba lagi.");
      }
    }
  });
};

var _resetForm = () => {
  items = [];
  itemSeq = 0;
  pasienDiscount = 0;
  editId = '';
  $('#pm-edit-banner').addClass('d-none');
  $('#bsimpan').text('Simpan Transaksi');
  $('#idkontak').val('');
  $('#kontaktipe').val('');
  $('#pasien').val(null).trigger('change');
  $('#pasien-info').addClass('d-none');
  $('#pasien-kategori').addClass('d-none');
  $('#pasien-member').addClass('d-none');
  $('#pasien-diskon').addClass('d-none');

  $(".pm-metode-btn.pm-metode-active").removeClass('pm-metode-active');
  $(".pm-metode-fields").addClass('d-none');
  $(".pm-metode-nilai").val(0);
  $(".pm-metode-text").val('');
  $("#pm-debit-bank, #pm-kredit-bank, #pm-transfer-bank, #pm-merchant-jenis").val(null).trigger('change');

  _renderItemList();
  _hitungTotal();
};

var _muatRiwayatHariIni = () => {
  $.ajax({
    "url"    : base_url+"PJ_POS_HP/riwayathariini",
    "type"   : "POST",
    "dataType" : "json",
    "cache"  : false,
    "beforeSend" : function(){
      $("#loader").removeClass('d-none');
    },
    "error"  : function(xhr,status,error){
      $("#loader").addClass('d-none');
      toastr.error("Gagal mengambil riwayat transaksi : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      $("#loader").addClass('d-none');
      _renderRiwayatList(result.data || []);
    }
  });
};

var _renderRiwayatList = (rows) => {
  $("#pm-riwayat-list").html('');

  if (rows.length === 0) {
    $("#pm-riwayat-empty").removeClass('d-none');
    return;
  }
  $("#pm-riwayat-empty").addClass('d-none');

  rows.forEach(function(row){
    var card =
      '<div class="pm-riwayat-card" data-id="'+row.id+'">' +
        '<div class="pm-riwayat-card-top">' +
          '<span class="pm-riwayat-nomor">'+row.nomor+'</span>' +
          '<span class="pm-riwayat-jam">'+row.jam+'</span>' +
        '</div>' +
        '<div class="pm-riwayat-pasien">'+row.pasien+'</div>' +
        '<span class="pm-riwayat-total">'+_formatRupiah(row.total)+'</span>' +
        '<div class="pm-riwayat-card-bottom">' +
          '<button type="button" class="pm-riwayat-cetak" data-id="'+row.id+'"><i class="fas fa-print"></i> Cetak</button>' +
          '<button type="button" class="pm-riwayat-edit" data-id="'+row.id+'"><i class="fas fa-pen"></i> Edit</button>' +
        '</div>' +
      '</div>';
    $("#pm-riwayat-list").append(card);
  });

  $(".pm-riwayat-card").off('click').on('click', function(){
    _lihatDetailRiwayat($(this).data('id'));
  });

  $(".pm-riwayat-cetak").off('click').on('click', function(e){
    e.stopPropagation();
    _cetakStruk($(this).data('id'));
  });

  $(".pm-riwayat-edit").off('click').on('click', function(e){
    e.stopPropagation();
    _muatUntukEdit($(this).data('id'));
  });
};

var _lihatDetailRiwayat = (id) => {
  $.ajax({
    "url"    : base_url+"PJ_POS_HP/getdata",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      $("#loader").removeClass('d-none');
    },
    "error"  : function(xhr,status,error){
      $("#loader").addClass('d-none');
      toastr.error("Gagal mengambil detail transaksi : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      $("#loader").addClass('d-none');

      var rows = result.data || [];
      if (rows.length === 0) {
        toastr.error("Detail transaksi tidak ditemukan.");
        return;
      }
      var header = rows[0];

      var itemsHtml = rows.map(function(r){
        return '<div class="pm-riwayat-detail-item">' +
                 '<span>'+r.namaitem+' ('+r.qtydetil+' x '+_formatRupiah(r.hargadetil)+')</span>' +
                 '<span>'+_formatRupiah(r.subtotaldetil)+'</span>' +
               '</div>';
      }).join('');

      var bayarHtml = '';
      if (Number(header.kasjumlah) > 0) {
        bayarHtml += '<div class="pm-riwayat-detail-bayar"><span>Tunai</span><span>'+_formatRupiah(header.kasjumlah)+'</span></div>';
      }
      if (Number(header.debitjumlah) > 0) {
        bayarHtml += '<div class="pm-riwayat-detail-bayar"><span>Debit ('+(header.debitbank||'-')+')</span><span>'+_formatRupiah(header.debitjumlah)+'</span></div>';
      }
      if (Number(header.kreditjumlah) > 0) {
        bayarHtml += '<div class="pm-riwayat-detail-bayar"><span>Kredit ('+(header.kreditbank||'-')+')</span><span>'+_formatRupiah(header.kreditjumlah)+'</span></div>';
      }
      if (Number(header.transferjumlah) > 0) {
        bayarHtml += '<div class="pm-riwayat-detail-bayar"><span>Transfer ('+(header.transferbank||'-')+')</span><span>'+_formatRupiah(header.transferjumlah)+'</span></div>';
      }
      if (Number(header.merchantjumlah) > 0) {
        bayarHtml += '<div class="pm-riwayat-detail-bayar"><span>Merchant ('+(header.merchantjenis||'-')+')</span><span>'+_formatRupiah(header.merchantjumlah)+'</span></div>';
      }

      var kembali = Number(header.totalsisa) || 0;

      var html =
        '<div style="text-align:left">' +
          '<div style="font-size:13px;color:#777;margin-bottom:10px">'+header.nomor+' &middot; '+header.tanggal+'<br>'+header.kontak+'</div>' +
          '<div style="max-height:220px;overflow-y:auto;border-top:1px solid #f0f2f5;border-bottom:1px solid #f0f2f5">'+itemsHtml+'</div>' +
          '<div style="margin-top:10px">'+bayarHtml+'</div>' +
          '<div class="pm-riwayat-detail-bayar" style="font-weight:700;margin-top:6px;border-top:1px solid #f0f2f5;padding-top:6px"><span>Total</span><span>'+_formatRupiah(header.tsubtotal)+'</span></div>' +
          (kembali > 0 ? '<div class="pm-riwayat-detail-bayar" style="color:#198754;font-weight:700"><span>Kembali</span><span>'+_formatRupiah(kembali)+'</span></div>' : '') +
        '</div>';

      Swal.fire({
        title: 'Detail Transaksi',
        html: html,
        confirmButtonText: 'Tutup',
        width: 420
      });
    }
  });
};

var _muatUntukEdit = (id) => {
  $.ajax({
    "url"    : base_url+"PJ_POS_HP/getdata",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      $("#loader").removeClass('d-none');
    },
    "error"  : function(xhr,status,error){
      $("#loader").addClass('d-none');
      toastr.error("Gagal mengambil data transaksi : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      $("#loader").addClass('d-none');

      var rows = result.data || [];
      if (rows.length === 0) {
        toastr.error("Data transaksi tidak ditemukan.");
        return;
      }
      var header = rows[0];

      editId = id;

      // Tutup panel riwayat, tampilkan form input
      $('#pm-riwayat-panel').addClass('d-none');
      $('.pm-content').removeClass('d-none');
      $('.pm-footer').removeClass('d-none');

      $('#pm-edit-nomor').text(header.nomor);
      $('#pm-edit-banner').removeClass('d-none');
      $('#bsimpan').text('Simpan Perubahan');

      // Pasien
      $('#idkontak').val(header.kontakid);
      $('#pasien').empty().append(new Option(header.kontak, header.kontakid, true, true)).trigger('change');
      _ambilDetailPasien(header.kontakid);

      // Item
      items = [];
      itemSeq = 0;
      rows.forEach(function(r){
        if (!r.iditem) return;
        itemSeq++;
        items.push({
          seq: itemSeq,
          iid: r.iditem,
          namaitem: r.namaitem,
          qty: Number(r.qtydetil) || 0,
          harga: Number(r.hargadetil) || 0,
          dis1: Number(r.dis1detil) || 0,
          idsatuan: r.idsatuan,
          namasatuan: r.satuan
        });
      });
      _renderItemList();

      // Metode pembayaran
      $(".pm-metode-btn.pm-metode-active").removeClass('pm-metode-active');
      $(".pm-metode-fields").addClass('d-none');
      $(".pm-metode-nilai").val(0);
      $(".pm-metode-text").val('');
      $("#pm-debit-bank, #pm-kredit-bank, #pm-transfer-bank, #pm-merchant-jenis").empty().trigger('change');

      _aktifkanMetodeEdit('tunai', header.kasjumlah);
      _aktifkanMetodeEdit('debit', header.debitjumlah, header.debitno, header.debitbank);
      _aktifkanMetodeEdit('kredit', header.kreditjumlah, header.kreditno, header.kreditbank);
      _aktifkanMetodeEdit('transfer', header.transferjumlah, header.transferno, header.transferbank);
      _aktifkanMetodeEdit('merchant', header.merchantjumlah, header.merchantno, header.merchantjenis);

      _hitungTotal();

      $('html, body').animate({ scrollTop: 0 }, 200);
    }
  });
};

var _aktifkanMetodeEdit = (metode, nilai, noref, bankjenis) => {
  nilai = Number(nilai) || 0;
  if (nilai <= 0) return;

  $(".pm-metode-btn[data-metode='"+metode+"']").addClass('pm-metode-active');
  $(".pm-metode-fields[data-metode-fields='"+metode+"']").removeClass('d-none');
  $("#pm-"+metode+"-nilai").val(_formatRibuan(nilai));

  if (typeof noref !== 'undefined' && noref !== null) {
    $("#pm-"+metode+"-no").val(noref);
  }
  if (bankjenis) {
    var $select = $("#pm-"+metode+"-bank");
    if ($select.length === 0) $select = $("#pm-"+metode+"-jenis");
    if ($select.length) {
      $select.append(new Option(bankjenis, bankjenis, true, true)).trigger('change');
    }
  }
};

var _strukAngka = _formatRibuan;

var _escHtml = (str) => {
  return String(str == null ? '' : str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;');
};

var _renderStruk = (rows) => {
  var header = rows[0];
  var alamat = [header.strukalamat1, header.strukalamat2].filter(Boolean).join(', ');

  var html = '';
  html += '<div class="pm-struk-center pm-struk-bold">'+_escHtml(header.strukpt)+'</div>';
  html += '<div class="pm-struk-center">'+_escHtml(header.strukcabang)+'</div>';
  if (alamat) {
    html += '<div class="pm-struk-center">'+_escHtml(alamat)+'</div>';
  }
  html += '<div class="pm-struk-divider"></div>';

  html += '<div class="pm-struk-row"><span>No Faktur</span><span>'+_escHtml(header.nomor)+'</span></div>';
  html += '<div class="pm-struk-row"><span>Tanggal</span><span>'+_escHtml(header.tanggal)+'</span></div>';
  html += '<div class="pm-struk-row"><span>Kasir</span><span>'+_escHtml(header.namakaryawan||'-')+'</span></div>';
  html += '<div class="pm-struk-row"><span>Pelanggan</span><span>'+_escHtml(header.kontak||'-')+'</span></div>';
  html += '<div class="pm-struk-divider"></div>';

  rows.forEach(function(r){
    if (!r.iditem) return;

    var qty = Math.round(Number(r.qtydetil)||0);
    var harga = Number(r.hargadetil)||0;
    var diskon = Number(r.diskondetil)||0;
    var subtotal = Number(r.subtotaldetil)||0;

    html += '<div class="pm-struk-item-nama">'+_escHtml(r.namaitem)+'</div>';
    html += '<div class="pm-struk-item-sub"><span>'+qty+' x '+_strukAngka(harga)+'</span><span>'+_strukAngka(subtotal)+'</span></div>';

    if (r.dokter) {
      html += '<div class="pm-struk-diskon">Dokter: '+_escHtml(r.dokter)+'</div>';
    }
    if (diskon > 0) {
      html += '<div class="pm-struk-diskon">Diskon '+qty+' x '+_strukAngka(diskon)+'</div>';
    }
  });

  html += '<div class="pm-struk-divider"></div>';
  html += '<div class="pm-struk-row pm-struk-bold"><span>GRAND TOTAL</span><span>'+_strukAngka(header.tsubtotal)+'</span></div>';
  html += '<div class="pm-struk-row"><span>BAYAR</span><span>'+_strukAngka(header.totalbayar)+'</span></div>';
  if (Number(header.totalsisa) > 0) {
    html += '<div class="pm-struk-row pm-struk-bold"><span>KEMBALI</span><span>'+_strukAngka(header.totalsisa)+'</span></div>';
  }
  html += '<div class="pm-struk-divider"></div>';

  html += '<div class="pm-struk-footer-note">Terima kasih atas kunjungan Anda</div>';
  html += '<div class="pm-struk-footer-note pm-struk-bold">CARE LOVE & SMILE</div>';
  if (header.struknohp) {
    html += '<div class="pm-struk-footer-note">'+_escHtml(header.struknohp)+'</div>';
  }

  $('#pm-struk-print').html(html);
};

var _cetakStruk = (id) => {
  $.ajax({
    "url"    : base_url+"PJ_POS_HP/getdata",
    "type"   : "POST",
    "dataType" : "json",
    "data"   : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      $("#loader").removeClass('d-none');
    },
    "error"  : function(xhr,status,error){
      $("#loader").addClass('d-none');
      toastr.error("Gagal mengambil data struk : "+xhr.status+" "+error);
    },
    "success" : function(result) {
      $("#loader").addClass('d-none');

      var rows = result.data || [];
      if (rows.length === 0) {
        toastr.error("Data struk tidak ditemukan.");
        return;
      }

      _renderStruk(rows);
      setTimeout(function(){ window.print(); }, 300);
    }
  });
};
