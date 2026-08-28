/* ========================================================================================== */
/* File Name : permintaan-barang.js
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Inputmask_Numeric_Flexible } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';

$(function () {

  Component_Scrollbars('.tab-wrap','scroll','scroll');
  Component_Inputmask_Date('.datepicker');

  Component_Select2('#jenis');

  _cekApprovePo();

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $(this).on('select2:open', function() {
    this.querySelector('.select2-search__field').focus();
  });

  _isiDepoFarmasi();

  $("#karyawan").keydown(function(e){
    if(e.keyCode==13) { $('#carikaryawan').click(); }
  });

  $("#carikaryawan").click(function() {
    if($(this).attr('role')) {
      $.ajax({
        "url"    : base_url+"Modal/cari_kontak",
        "type"   : "POST",
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");
          parent.window.$(".modal-title").html("Cari Karyawan");
          parent.window.$("#modaltrigger").val("iframe-page-pmb");
          parent.window.$('#coltrigger').val('karyawan');
        },
        "error": function(){
          console.log('error menampilkan modal cari kontak...');
          parent.window.$(".loader-wrap").addClass("d-none");
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak('4');

          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);

          return;
        }
      });
    }
  });

  $("#tujuannama").keydown(function(e){
    if(e.keyCode==13) { $('#caritujuan').click(); }
  });

  $("#caritujuan").click(function() {
    if($(this).attr('role')) {
      $.ajax({
        "url"    : base_url+"Modal/cari_tujuan",
        "type"   : "POST",
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");
          parent.window.$(".modal-title").html("Cari Tujuan");
          parent.window.$("#modaltrigger").val("iframe-page-pmb");
        },
        "error": function(){
          console.log('error menampilkan modal cari tujuan...');
          parent.window.$(".loader-wrap").addClass("d-none");
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        }
      });
    }
  });

  $('#tujuankode').on('change', function(){
    _applyTujuanLock($(this).val());
  });

  $("#gudangnama").keydown(function(e){
    if(e.keyCode==13) { $('#carigudang').click(); }
  });

  $("#carigudang").click(function() {
    if($(this).attr('role')) {
      $.ajax({
        "url"    : base_url+"Modal/cari_gudang",
        "type"   : "POST",
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");
          parent.window.$(".modal-title").html("Cari Gudang");
          parent.window.$("#modaltrigger").val("iframe-page-pmb");
        },
        "error": function(){
          console.log('error menampilkan modal cari gudang...');
          parent.window.$(".loader-wrap").addClass("d-none");
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        }
      });
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/pmbData";
  });

  $("#badd").click(function() {
    _clearForm();
    _addRow();
    _inputFormat();
    _formState1();
    _isiDepoFarmasi();
    _isiKaryawanDefault();
    _isiKeteranganDefault();
    _setStatus(0);
    _getNomor();
  });

  $("#bedit").click(function() {
    if($(this).hasClass('disabled')) return;
    if($('#id').val()=='') return;
    _formState1();
  });

  $("#bdelete").click(function() {
    if($(this).hasClass('disabled')) return;
    if($('#id').val()=='') return;
    const nomor = $("#nomor").val();
    parent.window.Swal.fire({
      title: 'Anda yakin akan menghapus '+nomor+'?',
      showDenyButton: false,
      showCancelButton: true,
      confirmButtonText: `Iya`,
    }).then((result) => {
      if (result.isConfirmed) {
        _deleteData();
      }
    })
  });

  $("#bsearch").click(function() {
    if($(this).hasClass('disabled')) return;
    $.ajax({
      "url"    : base_url+"Modal/cari_transaksi",
      "type"   : "POST",
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");
        parent.window.$(".modal").modal("show");
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-pmb");
      },
      "error": function(){
        console.log('error menampilkan modal cari transaksi...');
        parent.window.$(".loader-wrap").addClass("d-none");
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
        parent.window._setcabang();
        parent.window._transaksidatatable('view_permintaan_barang');
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);
        return;
      }
    });
  });

  $("#bprint").click(function() {
    if($(this).hasClass('disabled')) return;
    if($('#id').val()=='') return;
    window.open(`${base_url}Laporan/preview/page-pmb/${$("#id").val()}`)
  });

  $("#bverifikasi, #bverifikasibawah").click(function() {
    if($(this).hasClass('disabled')) return;
    var id = $('#id').val();
    if(id=='') return;

    $.ajax({
      "url"    : base_url+"Modal/verifikasi_pmb",
      "type"   : "POST",
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");
        parent.window.$(".modal").modal("show");
        parent.window.$(".modal-title").html("Verifikasi");
        parent.window.$("#modaltrigger").val("iframe-page-pmb");
      },
      "error": function(){
        console.log('error menampilkan modal verifikasi...');
        parent.window.$(".loader-wrap").addClass("d-none");
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);
        parent.window._getVerifikasiData(id);
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);
        return;
      }
    });
  });

  $("#baddrow").click(function() {
    _addRow();
    _inputFormat();
    $("input[name^='kodeitem']").last().focus();
  });

  $("#bcancel").click(function() {
    _clearForm();
    _addRow();
    _inputFormat();
    _formState2();
  });

  $("#bsave").click(function() {
    if (_IsValid()===0) return;
    _saveData();
  });

  $('#id').on('change',function(e){
    const idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });

  $(this).on('keydown', "input[name^='kodeitem']", function(e){
    if(e.keyCode==13) { $(this).closest('tr').find('.caritem').click(); }
  });

  $(this).on('click', '.caritem', function() {
    if($(this).attr('role')) {
      let idx = $('.caritem').index(this);
      $.ajax({
        "url"    : base_url+"Modal/cari_item",
        "type"   : "POST",
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");
          parent.window.$(".modal-title").html("Cari Item");
          parent.window.$("#modaltrigger").val("iframe-page-pmb");
          parent.window.$('#coltrigger').val(idx);
        },
        "error": function(){
          console.log('error menampilkan modal cari item...');
          parent.window.$(".loader-wrap").addClass("d-none");
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        }
      });
    }
  });

  $(this).on("keyup", "input[name^='qty']", async function(){
    await _hitungTotalQty();
  });

  $(this).on('click', '.btn-refresh-stok', async function(){
    let _idx = $('.btn-refresh-stok').index(this);
    await _refreshStokBaris(_idx);
  });

  $(this).on('shown.bs.tooltip', function (e) {
    setTimeout(function () {
      $(e.target).tooltip('hide');
    }, 2000);
  });

  const qparam = new URLSearchParams(this.location.search);

  if(qparam.get('id')==null){
    _clearForm();
    _addRow();
    _inputFormat();
    _formState1();
    _isiDepoFarmasi();
    _isiKaryawanDefault();
    _isiKeteranganDefault();
    _setStatus(0);
    _getNomor();
  }else{
    _addRow();
    _inputFormat();
    _formState2();
    $('#id').val(qparam.get('id')).trigger('change');
  }

});

/* ========================================================================================== */

var STATUS_LABELS = ['Belum Verifikasi','Pending','Verifikasi Finance','Perintah Kirim','Sedang DiKirim','Progress Diterima Cabang','Selesai Diterima Cabang','Konfirmasi Bag Pembelian'];

var _setStatus = (status) => {
  status = (status==null || status=='') ? 0 : Number(status);
  $('#status').val(status);
  $('#statusnama').val(STATUS_LABELS[status]);
}

var _isiKaryawanDefault = () => {
  var idkaryawandefault = $('#idkaryawandefault').val();
  if(idkaryawandefault==null || idkaryawandefault==''){
    return;
  }
  $('#idkaryawan').val(idkaryawandefault);
  $('#karyawan').val($('#karyawandefault').val());
}

var _isiKeteranganDefault = () => {
  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/getketerangan",
    "type"   : "POST",
    "dataType" : "json",
    "success": function(result) {
      $('#uraian').val(result.data[0]['keterangan']);
    }
  });
}

var _cekApprovePo = () => {
  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/cekapprovepo",
    "type"   : "POST",
    "dataType" : "json",
    "success": function(result) {
      if(result.approve==1){
        $('#tdetil').removeClass('pb-hide-stokakhir');
        $('#bverifikasi, #bverifikasibawah').removeClass('d-none');
      }else{
        $('#tdetil').addClass('pb-hide-stokakhir');
        $('#bverifikasi, #bverifikasibawah').addClass('d-none');
      }
    }
  });
}

var _isiDepoFarmasi = () => {
  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/getgudangnama",
    "type"   : "POST",
    "dataType" : "json",
    "data" : "id="+$('#cabang').val(),
    "success": function(result) {
      $('#gudangsumbernama').val(result.data[0]['gnama']);
    }
  });
}

var _applyTujuanLock = (kode) => {
  kode = (kode||'').toUpperCase();

  if(kode=='CABANG' || kode==''){
    $('#jenis').val(1).trigger('change');
    $('#carigudang').attr('data-dismiss','modal').attr('data-toggle','modal').attr('role','button');
    $('#gudangnama').removeAttr('disabled');
  }else{
    $('#jenis').val(2).trigger('change');
    $('#carigudang').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');
    $('#gudangnama').attr('disabled','disabled');
  }
  $('#jenis').attr('disabled','disabled');
}
window._applyTujuanLock = _applyTujuanLock;

window._pilihTransaksi = (id) => {
  $('#id').val(id);
  _formState2();
  _getDataTransaksi(id);
}

window._reloaddatatable = () => {
  _getDataTransaksi($('#id').val());
}

var _isiItemBaris = async (idx, iditem) => {
  if(iditem==null || iditem==''){
    $("input[name='item[]']").eq(idx).val('');
    $("input[name^='itemnama']").eq(idx).val('');
    $("input[name^='kodeitem']").eq(idx).val('');
    $("select[name^='satuan']").eq(idx).empty();
    return;
  }

  $("#loader-detil").removeClass('d-none');

  await $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/get_item",
    "type"   : "POST",
    "data"   : "id="+iditem,
    "dataType" : "json",
    "cache"  : false,
    "success"  : async function(result) {
      $("input[name='item[]']").eq(idx).val(iditem);
      $("input[name^='itemnama']").eq(idx).val(result.data[0]['nama']);
      $("input[name^='kodeitem']").eq(idx).val(result.data[0]['kode']);

      $("select[name^='satuan']").eq(idx).empty();
      var satuan = $("<option selected='selected'></option>")
                      .val(result.data[0]['idsatuan'])
                      .text(result.data[0]['namasatuan']);

      $("select[name^='satuan']").eq(idx).append(satuan).trigger('change');
      $("select[name^='satuan']").eq(idx).attr('disabled','disabled');

      await _refreshStokBaris(idx);
      await _hitungTotalQty();
      $("#loader-detil").addClass('d-none');
      return;
    }
  });
}
window._pilihItemBaris = (idx, iditem) => {
  _isiItemBaris(Number(idx), iditem);
}

var _inputFormat = () => {
  Component_Inputmask_Numeric('.numeric');
  Component_Inputmask_Numeric_Flexible('.qty,#tqty', $("#decimalqty").val());
}

var _clearForm = () => {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .noclear").val('');
  $('.select2').val('').change();
  $('.datepicker').datepicker('setDate','dd-mm-yy');
  $('.total').val('0');
  $('#tdetil tbody').html('');
  $('#gudangsumbernama').val('');
}

var _formState1 = () => {
  $('.input-group-append').attr('data-dismiss','modal');
  $('.input-group-append').attr('data-toggle','modal');
  $('.input-group-append').attr('role','button');
  $('.btn-step2').addClass('disabled');
  $('.btn-step1').removeClass('disabled');
  $('#baddrow').removeAttr('disabled');
  $(":input").not(":button, :submit, :reset, :radio, .total, #gudangsumbernama, #statusnama, #jenis, #catatanverifikasi").removeAttr('disabled');
  $(".satuan").prop('disabled',true);
  _applyTujuanLock($('#tujuankode').val());
  setTimeout(function () {
    $('#karyawan').focus();
  },300);
}

var _formState2 = () => {
  $('.btn-step2').removeClass('disabled');
  $('.btn-step1').addClass('disabled');
  $('#baddrow').attr('disabled','disabled');
  $(':input').not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');
  $(':input').not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff");
  $('.input-group-append').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');

  if($('#bisahapus').val()==0) $('#bdelete').addClass('disabled');
  if($('#bisaedit').val()==0) $('#bedit').addClass('disabled');
  if($('#bisaprint').val()==0) $('#bprint').addClass('disabled');
}

var _addRow = () => {
  var newrow = " <tr>";
      newrow += "<td><div class=\"input-group\" data-target-input=\"nearest\"><input type=\"hidden\" name=\"item[]\" class=\"item\"><input type=\"text\" name=\"kodeitem[]\" class=\"kodeitem form-control form-control-sm\" autocomplete=\"off\" readonly data-trigger=\"manual\" data-placement=\"auto\"><div class=\"input-group-append caritem\" role=\"button\"><div class=\"input-group-text\"><i class=\"fa fa-ellipsis-h\"></i></div></div></div></td>";
      newrow += "<td><input type=\"text\" name=\"itemnama[]\" class=\"itemnama form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
      newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
      newrow += "<td><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
      newrow += "<td><input type=\"tel\" name=\"stokreal[]\" class=\"numeric form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td class=\"col-stokakhir\"><input type=\"text\" name=\"stok[]\" class=\"numeric form-control form-control-sm\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></td>";
      newrow += "<td class=\"col-refreshstok text-center\"><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-refresh-stok\" tabindex=\"-1\"><i class=\"fas fa-sync text-primary\"></i></a></td>";
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tdetil tbody').append(newrow);
}

var _refreshStokBaris = async (idx) => {
  let iditem = $("input[name='item[]']").eq(idx).val();
  let idgudang = $('#cabang').val();

  if(!iditem || !idgudang) return;

  await $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/refreshstok",
    "type"   : "POST",
    "data"   : "item="+iditem+"&gudang="+idgudang,
    "dataType" : "json",
    "cache"  : false,
    "success"  : function(result) {
      $("input[name='stok[]']").eq(idx).val(result.data[0]['stok']);
    }
  });
}

var _hitungTotalQty = () => {
  let tqty = 0;
  const totalbaris = $(".item").length;
  for(let i=0;i<totalbaris;i++){
    tqty += Number($("input[name^='qty']").eq(i).val().split('.').join('').toString().replace(',','.'));
  }
  tqty = tqty.toString().replace('.',',');
  if(tqty==0) tqty='0,00';
  $('#tqty').val(tqty);
}

var _getNomor = () => {
  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/getnomor",
    "type"   : "POST",
    "data"   : "tgl="+$('#tgl').val(),
    "cache"  : false,
    "success"  : function(result) {
      $('#nomor').val(result);
    }
  });
}

/* Fungsi CRUD
/* ***********
/* ========================================================================================== */

var _IsValid = () => {

    if($('#idkaryawan').val()==''){
      $('#karyawan').attr('data-title','Karyawan harus diisi !');
      $('#karyawan').tooltip('show');
      $('#karyawan').focus();
      return 0;
    }
    if ($('#tujuan').val()=='' || $('#tujuan').val()==null){
      $('#tujuannama').attr('data-title','Tujuan harus diisi !');
      $('#tujuannama').tooltip('show');
      $('#tujuannama').focus();
      return 0;
    }

    const totalbaris = $(".item").length;
    for(let i=0;i<totalbaris;i++){
      if($("input[name='item[]']").eq(i).val()=='' || $("input[name='item[]']").eq(i).val()==null){
        $("input[name^='kodeitem']").eq(i).attr('data-title','Item harus diisi !');
        $("input[name^='kodeitem']").eq(i).tooltip('show');
        $("input[name^='kodeitem']").eq(i).focus();
        return 0;
      }
    }
    return 1;
}

var _deleteData = () => {
  const id = $("#id").val(),
        nomor = $("#nomor").val();

  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/deletedata",
    "type"   : "POST",
    "data"   : "id="+id+"&nomor="+nomor,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Err: "+xhr.status+", "+error);
      console.log(xhr.responseText);
      return;
    },
    "success": function(result) {
      parent.window.$(".loader-wrap").addClass("d-none");

      if(result=='sukses'){
        _clearForm();
        _addRow();
        _inputFormat();
        _formState1();
        parent.window.toastr.success("Transaksi berhasil dihapus");
        return;
      } else {
        parent.window.toastr.error(result);
        return;
      }
    }
  })
};

var _saveData = () => {

const id = $("#id").val(),
      tgl = $("#tgl").val(),
      nomor = $("#nomor").val(),
      karyawan = $("#idkaryawan").val(),
      gudang = $("#cabang").val(),
      gudangsumber = $("#gudang").val(),
      tujuan = $("#tujuan").val(),
      jenis = $("#jenis").val(),
      uraian = $("#uraian").val(),
      status = $("#status").val(),
      catatanverifikasi = $("#catatanverifikasi").val();

  var detil = [];

  $("input[name='item[]']").each(function(index,element){
      detil.push({
               item:this.value,
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val(),
               stokreal:Number($("input[name^='stokreal']").eq(index).val().split('.').join('').toString().replace(',','.')),
               stok:Number($("input[name='stok[]']").eq(index).val().split('.').join('').toString().replace(',','.')),
               catatan:$("textarea[name^='catatan']").eq(index).val()
             });

  });

  detil = JSON.stringify(detil);

  var rey = new FormData();
  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('karyawan',karyawan);
  rey.set('gudang',gudang);
  rey.set('gudangsumber',gudangsumber);
  rey.set('tujuan',tujuan);
  rey.set('jenis',jenis);
  rey.set('uraian',uraian);
  rey.set('status',status);
  rey.set('catatanverifikasi',catatanverifikasi);
  rey.set('detil',detil);

  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/savedata",
    "type"   : "POST",
    "data"   : rey,
    "processData": false,
    "contentType": false,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Err: "+xhr.status+", "+error);
      console.log(xhr.responseText);
      return;
    },
    "success": function(result) {
      result = JSON.parse(result);
      parent.window.$(".loader-wrap").addClass("d-none");
      if(result.pesan=='sukses'){
          parent.window.toastr.success("Transaksi berhasil disimpan");
          const idtersimpan = result.nomor;
          _clearForm();
          _addRow();
          _inputFormat();
          _formState1();
          parent.window.Swal.fire({
            title: 'Apakah akan mencetak?',
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: `Iya`,
            cancelButtonText: `Tidak`,
          }).then((res) => {
            if (res.isConfirmed) {
              window.open(`${base_url}Laporan/preview/page-pmb/${idtersimpan}`)
            }
          })
          return;
      } else {
          parent.window.toastr.error(result.pesan);
      }
    }
  })
}

var _getDataTransaksi = (id) => {
  if(id=='' || id==null) return;

  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/getdata",
    "type"   : "POST",
    "dataType" : "json",
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');
    },
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi permintaan barang !');
      parent.window.$('.loader-wrap').addClass('d-none');
      return;
    },
    "success" : function(result) {

      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');
        return;
      } else {
        $('#tdetil tbody').html('');
        for (let i = 0; i < result.data.length; i++) {
          _addRow();
        }
        _inputFormat();

        $('#id').val(result.data[0]['id']);
        $('#nomor').val(result.data[0]['nomor']);
        $('#tgl').val(result.data[0]['tanggal']);
        $('#idkaryawan').val(result.data[0]['idkaryawan']);
        $('#karyawan').val(result.data[0]['namakaryawan']);
        $('#gudangsumbernama').val(result.data[0]['gudang']);
        $('#tujuan').val(result.data[0]['idtujuan']);
        $('#tujuannama').val(result.data[0]['tujuan']);
        $('#tujuankode').val(result.data[0]['tujuankode']);
        $('#gudang').val(result.data[0]['idgudangsumber']);
        $('#gudangnama').val(result.data[0]['gudangsumber']);
        _applyTujuanLock(result.data[0]['tujuankode']);
        $('#uraian').val(result.data[0]['uraian']);
        _setStatus(result.data[0]['status']);
        $('#catatanverifikasi').val(result.data[0]['catatanverifikasi']);

        for (let i = 0; i < result.data.length; i++) {
          let satuan = $("<option selected='selected'></option>").val(result.data[i]['idsatuan']).text(result.data[i]['satuan']);

          $("input[name='item[]']").eq(i).val(result.data[i]['iditem']);
          $("input[name^='itemnama']").eq(i).val(result.data[i]['namaitem']);
          $("input[name^='kodeitem']").eq(i).val(result.data[i]['kditem']);
          $("select[name^='satuan']").eq(i).append(satuan).trigger('change');
          $("select[name^='satuan']").eq(i).attr('disabled','disabled');
          $("input[name^='qty']").eq(i).val(result.data[i]['qtydetil']);
          $("input[name^='stokreal']").eq(i).val(result.data[i]['stokrealdetil']);
          $("input[name='stok[]']").eq(i).val(result.data[i]['stokdetil']);
          $("textarea[name^='catatan']").eq(i).val(result.data[i]['catdetil']);
        }

        _hitungTotalQty();

        parent.window.$('.loader-wrap').addClass('d-none');
        return;
      }
    }
  })
};

window._hapusbaris = async (obj) => {
  if($(obj).hasClass('disabled')) return;
  $(obj).closest('tr').remove();
  await _hitungTotalQty();
}
