/* ========================================================================================== */
/* File Name : keluar-barang.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Select2_Item } from '../../component.js';

$(function() {

  const qparam = new URLSearchParams(this.location.search);  
  
  setupHiddenInputChangeListener($('#id')[0]);
  setupHiddenInputChangeListener($('#idreferensi')[0]);  

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#gudang',`${base_url}Select_Master/view_gudang`,'form_gudang','Gudang');

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

/* ========================================================================================== */

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $('#kontak').keydown(function(e){
    if(e.keyCode==13) { $('#carikontak').click(); }
  });

  $('#person').keydown(function(e){
    if(e.keyCode==13) { $('#cariperson').click(); }
  });  

  $('#bagbeli').keydown(function(e){
    if(e.keyCode==13) { $('#caribagbeli').click(); }
  });

  $(this).on('select2:open', () => {
    this.querySelector('.select2-search__field').focus();
  });  

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/bkrData";      
  });

  $("#bViewJurnal").click(function() {
      if($("#id").val()=="") return;

      $.ajax({ 
        "url"    : base_url+"Modal/lihat_jurnal", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");            
          parent.window.$(".modal-title").html("Jurnal "+$("#nomor").val());
          parent.window.$("#modaltrigger").val("iframe-page-bkr");
          parent.window.$('#coltrigger').val('');                
        },        
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');      
          console.log('error menampilkan modal jurnal...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window._transaksidatatable($("#nomor").val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
  });  

  $("#carikontak").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-bkr");
          parent.window.$('#coltrigger').val('vendor');                
        },         
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');
          console.log('error menampilkan modal cari kontak...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak('6'); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
    }    
  });

  $("#cariperson").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak_attention", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak Person");
          parent.window.$("#modaltrigger").val("iframe-page-bkr");
          parent.window.$('#coltrigger').val('vendor');
        },         
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');
          console.log('error menampilkan modal cari kontak attention...');
          return;
        },
        "success": function(result) {                
          parent.window.$(".main-modal-body").html(result);      
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window._persondatatable($('#idkontak').val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
    }    
  });  

  $("#caribagbeli").click(function() {
    if($(this).attr('role')) {    
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-bkr");
          parent.window.$('#coltrigger').val('bagbeli');                
        },
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');          
          console.log('error menampilkan modal cari kontak...');
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

  $("#caripenerimaan").click(function() {    
  if($(this).attr('role')) {        
    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Vendor harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    } 

    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi_multiple", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Penerimaan Barang");
        parent.window.$("#modaltrigger").val("iframe-page-bkr");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');          
        console.log('error menampilkan modal cari penerimaan barang...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);    
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
        parent.window._transaksidatatable('view_cari_terima_barang_r',$('#idkontak').val());
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);
        return;
      } 
    });   
  }
  });

  $("#badd").click(function() {
    _clearForm();
    _addRow();
    _inputFormat();          
    _formState1();
  });

  $("#bedit").click(function() {
    if($('#id').val()=='') return;    
    _formState1();
  });

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-bkr/${$("#id").val()}`)    
  });

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-bkr");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');          
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);  
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');            
        parent.window._transaksidatatable('view_keluar_barang_pembelian');
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
    $("select[name^='item']").last().focus();            
  });

  $("#bcancel").click(function() {
    _clearForm();
    _formState2();
  });

  $("#bdelete").click(function() {
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

  $("#bsave").click(function() {
    if (_IsValid()===0) return;
    _saveData();
  });

  $('#id').on('change',function(){
    var idtrans = $(this).val();
    _getDataTransaksi(idtrans);
  });  

  $('#idreferensi').on('change',function(){
    var id = $(this).val();
    _getDataPenerimaan(id);
  });    

  $(this).on("keyup", "input[name^='qty']", function(){
      let _idx = $(this).index('.qty');
      _hitungsubtotal();
  });

  $(this).on("select2:select", "select[name^='item']", function(){

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.item');

      $.ajax({ 
        "url"    : base_url+"PB_Keluar_Barang/get_item", 
        "type"   : "POST", 
        "data"   : "id="+$(this).val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(){
          console.log('error ambil satuan item...');
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : function(result) {
          let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']);          
          
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change');  
          //Hitung Total Quantity Dan Sub Total
          _hitungsubtotal();
          $("#loader-detil").addClass('d-none');    
          return;                    
      } 
      });
  });

  $(this).on('shown.bs.tooltip', function (e) {
    setTimeout(function () {
      $(e.target).tooltip('hide');
    }, 2000);
  });    

/**/

/* ========================================================================================== */

var _inputFormat = () => {
  Component_Inputmask_Numeric('.numeric');
  Component_Select2('.satuan',`${base_url}Select_Master/view_satuan`,'form_satuan','Satuan');  
  Component_Select2_Item('.item',`${base_url}Select_Master/view_item`,'form_item','Item');  
  Component_Select2('.proyekdetil',`${base_url}Select_Master/view_proyek`,'form_proyek','Proyek');    
  Component_Select2('.gudangdetil',`${base_url}Select_Master/view_gudang`,'form_gudang','Gudang');      
}

var _clearForm = () => {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false); 
  $('.select2').val('').change();    
  $('#namakontak').html("");
  $('#namaperson').html("");    
  $('.datepicker').datepicker('setDate','dd-mm-yy'); 
  $('.total').val('0');  
  $('#tdetil tbody').html('');               
}

var _formState1 = () => {
  $('.input-group-append').attr('data-dismiss','modal');
  $('.input-group-append').attr('data-toggle','modal');
  $('.input-group-append').attr('role','button');    
  $('.btn-step2').addClass('disabled');
  $('.btn-step1').removeClass('disabled');
  $('#baddrow').removeAttr('disabled');           
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").removeAttr('disabled');
  setTimeout(function () {
    $('#kontak').focus();        
  },300);
}

var _formState2 = () => {
  _addRow();
  _inputFormat();  
  $('.btn-step2').removeClass('disabled');
  $('.btn-step1').addClass('disabled'); 
  $('#baddrow').attr('disabled','disabled');     
  $(':input').not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
  $(':input').not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff"); 
  $('.input-group-append').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
}

var _addRow = () => {
  let newrow = " <tr>";
      newrow += "<td><select name=\"item[]\" class=\"item form-control select2 form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
      newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
      newrow += "<td><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
      newrow += "<td><input type=\"hidden\" name=\"idrefd[]\" class=\"idrefd\"><input type=\"hidden\" name=\"idrefdet[]\" class=\"idrefdet\"><input type=\"text\" name=\"refnodetil[]\" class=\"refnodetil form-control form-control-sm\" autocomplete=\"off\" readonly></td>";      
      newrow += "<td><select name=\"proyekdetil[]\" class=\"proyekdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";
      newrow += "<td><select name=\"gudangdetil[]\" class=\"gudangdetil form-control select2 form-control-sm\" style=\"width:100%\"></select></td>";
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tdetil tbody').append(newrow);
}

_clearForm();    
_addRow();
_inputFormat();  
_formState1();

/**/

/* CRUD
/* ========================================================================================== */

var _IsValid = () => {

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Vendor harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return 0;
    }
    if ($('#idbagbeli').val()==''){
      $('#bagbeli').attr('data-title','Karyawan harus diisi !');      
      $('#bagbeli').tooltip('show');
      $('#bagbeli').focus();
      return 0;
    }
    if ($('#gudang').val()=='' || $('#gudang').val()==null){
      $('#gudang').attr('data-title','Gudang harus diisi !');      
      $('#gudang').tooltip('show');
      $('#gudang').focus();
      return 0;
    }
    if ($('#uraian').val()==''){
      $('#uraian').attr('data-title','Uraian harus diisi !');      
      $('#uraian').tooltip('show');
      $('#uraian').focus();
      return 0;
    }    

    let totalbaris = $(".item").length;
    for(let i=0;i<totalbaris;i++){
      if($("select[name^='item']").eq(i).val()=='' || $("select[name^='item']").eq(i).val()==null){
        $("select[name^='item']").eq(i).attr('data-title','Item harus diisi !');      
        $("select[name^='item']").eq(i).tooltip('show');      
        $("select[name^='item']").eq(i).focus();
        return 0;
      }
    }
    return 1;
}

var _deleteData = () => {
  const id = $("#id").val(),
        nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"PB_Keluar_Barang/deletedata", 
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
}

var _saveData = (function(){

  const id = $("#id").val(),
        tgl = $("#tgl").val(),
        nomor = $("#nomor").val(),
        kontak = $("#idkontak").val(),
        person = $("#idperson").val(),
        karyawan = $("#idbagbeli").val(),
        alamat = $("#alamat").val(), 
        status = $("#status").val(),            
        gudang = $("#gudang").val(),
        uraian = $("#uraian").val(),
        catatan = $("#memo").val();

  let detil = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value,
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val(),               
               catatan:$("textarea[name^='catatan']").eq(index).val(),
               nopb:$("input[name^='idrefd']").eq(index).val(),
               noorder:$("input[name^='idrefdet']").eq(index).val(),
               proyek:$("select[name^='proyekdetil']").eq(index).val(),                              
               gudang:$("select[name^='gudangdetil']").eq(index).val()               
             });

  }); 

  detil = JSON.stringify(detil);  

  var totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.'));  

  var rey = new FormData();  

  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('person',person);
  rey.set('karyawan',karyawan);    
  rey.set('alamat',alamat); 
  rey.set('gudang',gudang); 
  rey.set('status',status);
  rey.set('uraian',uraian);
  rey.set('catatan',catatan);          
  rey.set('totalqty',totalqty);      
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"PB_Keluar_Barang/savedata", 
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
//      parent.window.toastr.error(xhr.responseText);
      parent.window.toastr.error("Error : "+xhr.status+", "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      result = JSON.parse(result);
      parent.window.$(".loader-wrap").addClass("d-none");                                            
      if(result.pesan=='sukses'){
          parent.window.Swal.fire({
              title: `Anda ingin mencetak transaksi ini ?`,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Iya`,
          }).then((printing) => {
              if (printing.isConfirmed) {
                window.open(`${base_url}Laporan/preview/page-bkr/${result.nomor}`)
              }
              parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
              _clearForm();
              _addRow();
              _inputFormat();
              _formState1();
              return;
          })
      }                  
    } 
  });

});

var _getDataTransaksi = (id) => {

  if(id=='' || id==null) return;    

  $.ajax({ 
    "url"    : base_url+"PB_Keluar_Barang/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi pengeluaran barang retur !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {

      if (typeof result.pesan !== 'undefined') { 
        alert(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else { 
        
        $('#tdetil tbody').html('');
        for (let i = 0; i < result.data.length; i++) {
          _addRow();
        }
        _inputFormat();

        var gudang = $("<option selected='selected'></option>").val(result.data[0]['idgudang']).text(result.data[0]['gudang']);                        

        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);
        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
        $('#idreferensi').val(result.data[0]['orderid']);                        
        $('#refnomor').val(result.data[0]['nomororder']);            
        $('#uraian').val(result.data[0]['uraian']);            
        $('#memo').val(result.data[0]['catatan']);                        
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontakkode']);
        $('#namakontak').html(result.data[0]['kontak']);
        $('#idperson').val(result.data[0]['idperson']);
        $('#person').val(result.data[0]['person']);
        $('#idbagbeli').val(result.data[0]['idkaryawan']);
        $('#bagbeli').val(result.data[0]['namakaryawan']);                                    
        $('#gudang').append(gudang).trigger('change');            
        $('#status').val(result.data[0]['status']);        
        $('#alamat').val(result.data[0]['alamat']);                                                                                                                     

        var rows = 0, 
            _tqty = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']), 
              _proyek = $("<option selected='selected'></option>").val(result.data[rows]['idproyek']).text(result.data[rows]['proyek']),                                     
              _gudang = $("<option selected='selected'></option>").val(result.data[rows]['idgudangd']).text(result.data[rows]['gudangd']);

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));                        
          $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catdetil']);                                               
          $("input[name^='idrefd']").eq(rows).val(result.data[rows]['pbdid']);                                                                                
          $("input[name^='idrefdet']").eq(rows).val(result.data[rows]['orderdid']);                                                                      
          $("input[name^='refnodetil']").eq(rows).val(result.data[rows]['nomororderd']);                                                          
          $("select[name^='proyekdetil']").eq(rows).append(_proyek).trigger('change');
          $("select[name^='gudangdetil']").eq(rows).append(_gudang).trigger('change');

          _tqty += Number(result.data[rows]['qtydetil']);

          if(result.data[rows]['qtydetil']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');

          rows++;
        });

          $('#tqty').val(_tqty.toString().replace('.',','));             


        if($('.btn-step1').hasClass('disabled')){
          $('.btn-delrow').addClass('disabled');
          $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
          $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");
        }
        parent.window.$('.loader-wrap').addClass('d-none');                                       
        return;

      }

  } 
})
}

var _getDataPenerimaan = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PB_Keluar_Barang/getdatapenerimaan",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      parent.window.toastr.error('Error : Gagal mengambil data penerimaan barang !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : async function(result){
      if (typeof result.pesan !== 'undefined') {
        alert(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
        var datarows = 0;
        var rows = $(".item").length;         

        if(rows==1 && Number($('#tqty').val().split('.').join('').toString().replace(',','.'))=='0'){
          $('#tdetil tbody').html('');
          rows = 0;
        }

        $.each(result.data, function() {
          _addRow();
          _inputFormat();

          var _item = $("<option selected='selected'></option>").val(result.data[datarows]['item']).text(result.data[datarows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[datarows]['satuan']).text(result.data[datarows]['namasatuan']),
              _proyek = $("<option selected='selected'></option>").val(result.data[datarows]['idproyek']).text(result.data[datarows]['proyek']),                                     
              _gudang = $("<option selected='selected'></option>").val(result.data[datarows]['idgudang']).text(result.data[datarows]['gudang']);              

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qty']").eq(rows).val(result.data[datarows]['qty'].replace(".", ","));            
          $("textarea[name^='catatan']").eq(rows).val(result.data[datarows]['catatan']); 
          $("input[name^='idrefd']").eq(rows).val(result.data[datarows]['idpbd']);         
          $("input[name^='idrefdet']").eq(rows).val(result.data[datarows]['idsod']);                                                                      
          $("input[name^='refnodetil']").eq(rows).val(result.data[datarows]['nomorso']);
          $("select[name^='proyekdetil']").eq(rows).append(_proyek).trigger('change');
          $("select[name^='gudangdetil']").eq(rows).append(_gudang).trigger('change');                                                                    

          if(result.data[datarows]['qty']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            

          datarows++;
          rows++;
        });

        _hitungsubtotal();

        parent.window.$('.loader-wrap').addClass('d-none');   
        return;                       
      }
    }
  });
}

/**/

  $("#id").val(qparam.get('id')).trigger('change');

});

var _hitungsubtotal = () => {
  let tqty = 0;
  
  $('.item').each(function(index,element) {
    tqty += Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')); 
  });  

  tqty = tqty.toString().replace('.',',');

  if(tqty==0) tqty='0,00';

  $('#tqty').val(tqty).attr('placeholder',tqty);
  return;
}

window._hapusbaris = (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  _hitungsubtotal();
}