/* ========================================================================================== */
/* File Name : retur-penjualan.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Inputmask_Numeric_Flexible } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Select2_Item } from '../../component.js';

$(function() {

  const qparam = new URLSearchParams(this.location.search);  

  setupHiddenInputChangeListener($('#id')[0]);
  setupHiddenInputChangeListener($('#notrans')[0]);

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#gudang',`${base_url}Select_Master/view_gudang`,'form_gudang','Gudang');
  Component_Select2('#termin',`${base_url}Select_Master/view_termin`,'form_termin','Termin');
  Component_Select2('#pajak');

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

  if($('#nilaipph22').val() != '') {
    $('#col-pph22').removeClass('d-none');
    $('#col-clear').removeClass('col-sm-8');    
    $('#col-clear').addClass('col-sm-6');        
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

  $('#salesman').keydown(function(e){
    if(e.keyCode==13) { $('#carisalesman').click(); }
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
    location.href=base_url+"page/rpjData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-rpj");
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
          parent.window.$("#modaltrigger").val("iframe-page-rpj");
          parent.window.$('#coltrigger').val('customer');           
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
          parent.window._pilihkategorikontak('2'); 
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
          parent.window.$("#modaltrigger").val("iframe-page-rpj");
          parent.window.$('#coltrigger').val('customer');                
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

  $("#carisalesman").click(function() {
    if($(this).attr('role')) {    
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');          
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-rpj");
          parent.window.$('#coltrigger').val('salesman');                
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
      window.open(`${base_url}Laporan/preview/page-rpj/${$("#id").val()}`)    
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
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        _deleteData();      
      }
    })
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
        parent.window.$("#modaltrigger").val("iframe-page-rpj");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');      
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);    
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');        
        parent.window._transaksidatatable('view_retur_penjualan');
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
    _addRow();
    _inputFormat();
    _formState2();
  });

  $("#bsave").click(function() {
    if (_IsValid()===0) return;
    _saveData();
  });

  $('#pajak').on('change',function(){
    _hitungTotal();
  });

  $('#chkpph22').on('click',function(){
    _hitungTotal();
  });

  $('#id').on('change',function(){
    var idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });    

  $('#notrans').on('change',function(){
    var idtrans = $(this).val();
    _formState2();
    _getDataTransaksiNomor(idtrans);
  });      

  $(this).on("keyup", "input[name^='qty']", async function(e){
      let _idx = $(this).index('.qty');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='harga']", async function(e){
      let _idx = $(this).index('.harga');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='diskon']", async function(e){
      let _idx = $(this).index('.diskon');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });  

  $(this).on("select2:select", "select[name^='item']", function(e){
      //alert($(this).val());return;

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.item');

      $.ajax({ 
        "url"    : base_url+"PJ_Retur_Penjualan/get_item", 
        "type"   : "POST", 
        "data"   : "id="+$(this).val()+"&kontak="+$("#idkontak").val(),
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
        "success"  : async function(result) {
          let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']),
              gudang = $("<option selected='selected'></option>")
                        .val(result.data[0]['idgudang'])
                        .text(result.data[0]['gudang']);          
          
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change');  
          $("select[name^='satuan']").eq(_idx).prop("disabled", true);            

          switch(result.data[0]['level']){
            case '1':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);
              if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;
            case '2':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual2']);
              if(result.data[0]['hargajual2']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                                      
            case '3':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual3']);
              if(result.data[0]['hargajual3']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                       
            case '4':
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual4']);
              if(result.data[0]['hargajual4']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
              break;                                                                                 
            default:
              $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);    
              if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');
          }    

          if(result.data[0]['gudang'] != null) $("select[name^='gudangdetil']").eq(_idx).append(gudang).trigger('change');

          let jumlah = await _hitungJumlahDetil(_idx);
          let subtotal = await _hitungsubtotal();
          _hitungTotal();
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
    Component_Inputmask_Numeric_Flexible('.qty,#tqty', $("#decimalqty").val());      
    Component_Select2('.satuan',`${base_url}Select_Master/view_satuan`,'form_satuan','Satuan');  
    Component_Select2_Item('.item',`${base_url}Select_Master/view_item`,'form_item','Item');  
    Component_Select2('.gudangdetil',`${base_url}Select_Master/view_gudang`,'form_gudang','Gudang');      
    Component_Select2('.proyekdetil',`${base_url}Select_Master/view_proyek`,'form_proyek','Proyek');              
  }

  var _clearForm = () => {
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .nilaipajak, .noclear").val('');
    $(":checkbox").prop("checked", false); 
    $('.select2').val('').change();
    $('#pajak').val($('#defpajak').val()).change();    
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
    $(":input").not(":button, :submit, :reset, :radio, .total").removeAttr('disabled');
    $(".satuan").prop('disabled',true);                 
    setTimeout(function () {
      $('#kontak').focus();        
    },300);
  }

  var _formState2 = () => {
    $('.btn-step2').removeClass('disabled');
    $('.btn-step1').addClass('disabled'); 
    $('#baddrow').attr('disabled','disabled');     
    $(':input').not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
    $(':input').not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff"); 
    $('.input-group-append').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
  }

  var _addRow = () => {
    let newrow = " <tr>";
        newrow += "<td><select name=\"item[]\" class=\"item form-control select2 form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";      
        newrow += "<td><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
        newrow += "<td><input type=\"hidden\" name=\"noref[]\" class=\"noref\"><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
        newrow += "<td><select name=\"gudangdetil[]\" class=\"gudangdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";      
        if($("#multiproyek").val()==1){        
          newrow += "<td><select name=\"proyekdetil[]\" class=\"proyekdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";              
        } else {
          newrow += "<td class=\"d-none\"><select name=\"proyekdetil[]\" class=\"proyekdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";          
        }
        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
        newrow += "</tr>";
    $('#tdetil tbody').append(newrow);
  }

/**/

/* Fungsi CRUD
/* ***********
/* ========================================================================================== */

var _IsValid = () => {

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Pelanggan harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
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
        parent.window.Swal.fire({
            title: `Item pada baris ke-${i+1} harus diisi !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        }).then((result) => {
            setTimeout(function(){
              $("select[name^='item']").eq(i).focus();        
            },400);
        });
        return 0;                                  
      }
      if(Number($("input[name^='qty']").eq(i).val().split('.').join('').toString().replace(',','.'))==0){
        parent.window.Swal.fire({
            title: `Qty pada baris ke-${i+1} harus diisi !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        }).then((result) => {
            setTimeout(function(){
              $("select[name^='item']").eq(i).focus();        
            },400);
        });
        return 0;                                  
      }      
      if($("select[name^='gudangdetil']").eq(i).val()=='' || $("select[name^='gudangdetil']").eq(i).val()==null){
        parent.window.Swal.fire({
            title: `Gudang pada baris ke-${i+1} harus diisi !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
        }).then((result) => {
            setTimeout(function(){
              $("select[name^='item']").eq(i).focus();        
            },400);
        });
        return 0;                                  
      }      
    }
    return 1;
}

var _deleteData = () => {
  const id = $("#id").val();
  const nomor = $("#nomor").val();  

  $.ajax({ 
    "url"    : base_url+"PJ_Retur_Penjualan/deletedata", 
    "type"   : "POST", 
    "data"   : "id="+id+"&nomor="+nomor,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Error : "+xhr.status+", "+error);      
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

var _saveData = () => {

  let id = $("#id").val(),
      tgl = $("#tgl").val(),
      nomor = $("#nomor").val(),
      kontak = $("#idkontak").val(),
      person = $("#idperson").val(),
      alamat = $("#alamat").val(),            
      pajak = $("#pajak").val(),
      gudang = $("#gudang").val(), 
      karyawan = $("#idsalesman").val(),  
      termin = $("#termin").val(),    
      status = $("#status").val(), 
      uraian = $("#uraian").val(),
      detil = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value,
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val(),               
               harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
               diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
               persen:Number($("input[name^='persen']").eq(index).val().split('.').join('').toString().replace(',','.')),               
               catatan:$("textarea[name^='catatan']").eq(index).val(),
               noref:$("input[name^='noref']").eq(index).val(),               
               proyek:$("select[name^='proyekdetil']").eq(index).val(),                              
               gudang:$("select[name^='gudangdetil']").eq(index).val()                                             
             });
  }); 

  detil = JSON.stringify(detil);  

  let totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.')),
      totalsub = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')),
      totalpajak = Number($("#tpajak").val().split('.').join('').toString().replace(',','.')),
      totalpph22 = Number($("#tpajakpph22").val().split('.').join('').toString().replace(',','.')),      
      totaltrans = Number($("#ttrans").val().split('.').join('').toString().replace(',','.'));

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('person',person);
  rey.set('alamat',alamat);
  rey.set('karyawan',karyawan);
  rey.set('termin',termin);      
  rey.set('gudang',gudang);  
  rey.set('pajak',pajak);
  rey.set('uraian',uraian); 
  rey.set('status',status);      
  rey.set('totalqty',totalqty);      
  rey.set('totalsub',totalsub);
  rey.set('totalpajak',totalpajak);            
  rey.set('totalpph22',totalpph22);              
  rey.set('totaltrans',totaltrans);   
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"PJ_Retur_Penjualan/savedata", 
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
                window.open(`${base_url}Laporan/preview/page-rpj/${result.nomor}`)
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

}

var _ambilData = (result) => {
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

          $('#id').val(result.data[0]['id']);            
          $('#nomor').val(result.data[0]['nomor']);
          $('#tgl').datepicker('setDate',result.data[0]['tanggal']); 
          $('#alamat').val(result.data[0]['alamat']);          
          $('#uraian').val(result.data[0]['uraian']);            
          $('#idkontak').val(result.data[0]['kontakid']);
          $('#kontak').val(result.data[0]['kontakkode']);
          $('#namakontak').html(result.data[0]['kontak']);
          $('#idperson').val(result.data[0]['idperson']);
          $('#person').val(result.data[0]['person']);
          $('#idsalesman').val(result.data[0]['idkaryawan']);
          $('#salesman').val(result.data[0]['kodekaryawan']);          
          $('#pajak').val(result.data[0]['pajak']).change();
          $('#status').val(result.data[0]['status']);        

          var rows = 0, _tqty = 0, _tsubtotal = 0, _tpajak = 0, _tpph22 = 0, _ttrans = 0;
          
          $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']),
              _gudang = $("<option selected='selected'></option>").val(result.data[rows]['idgudang']).text(result.data[rows]['gudang']),
              _proyek = $("<option selected='selected'></option>").val(result.data[rows]['idproyek']).text(result.data[rows]['proyek']);

            $("select[name^='item']").eq(rows).append(_item).trigger('change');   
            $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
            $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
            $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));
            $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskon'].replace(".", ","));            
            $("input[name^='persen']").eq(rows).val(result.data[rows]['persendiskon'].replace(".", ","));                        
            $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));            
            $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catdetil']);                                               
            $("select[name^='gudangdetil']").eq(rows).append(_gudang).trigger('change');                         
            if(result.data[rows]['proyek'] != null) $("select[name^='proyekdetil']").eq(rows).append(_proyek).trigger('change');                                   

            _tqty += Number(result.data[rows]['qtydetil']);
            _tsubtotal += Number(result.data[rows]['subtotaldetil']);            

            //atur placeholder numeric jika 0
            if(result.data[rows]['qtydetil']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
            if(result.data[rows]['hargadetil']==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');                        
            if(result.data[rows]['diskon']==0) $("input[name^='diskon']").eq(rows).attr('placeholder','0,00');
            if(result.data[rows]['persendiskon']==0) $("input[name^='persen']").eq(rows).attr('placeholder','0,00');            
            if(result.data[rows]['subtotaldetil']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');                        

            rows++;
          });

            _tpajak = Number(result.data[0]['tpajak']);
            _tpph22 = Number(result.data[0]['tpph22']);

            if(result.data[0]['pajak']=='2'){
              _ttrans = _tsubtotal;
            }else{
              _ttrans = _tsubtotal + _tpajak + _tpph22;
            }            

            if(_tpph22 > 0) $("#chkpph22").prop('checked', true);

            $('#tqty').val(_tqty.toString().replace('.',','));   
            $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
            $('#tpajak').val(_tpajak.toString().replace('.',','));               
            $('#tpajakpph22').val(_tpph22.toString().replace('.',','));                           
            $('#ttrans').val(_ttrans.toString().replace('.',',')); 
          /**/

          if($('.btn-step1').hasClass('disabled')){
            $('.btn-delrow').addClass('disabled');
            $(":input").not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
            $(":input").not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff");
          }
          parent.window.$('.loader-wrap').addClass('d-none');                                       
          return;
        }  
}

var _getDataTransaksi = (id) => {

    if(id=='' || id==null) return;    

    if($("#notrans").val()!='') return;

    $.ajax({ 
      "url"    : base_url+"PJ_Retur_Penjualan/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        $('#modaltransaksi').modal('hide');           
        parent.window.$('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(){
        parent.window.toastr.error('Error : Gagal mengambil data transaksi retur penjualan !');
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        _ambilData(result);
      } 
    })

}

var _getDataTransaksiNomor = (nomor) => {

    if(nomor=='' || nomor==null) return;    

    $.ajax({ 
      "url"    : base_url+"PJ_Retur_Penjualan/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "nomor="+nomor,
      "cache"  : false,
      "beforeSend" : function(){
        $('#modaltransaksi').modal('hide');           
        parent.window.$('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(){
        parent.window.toastr.error('Error : Gagal mengambil data transaksi retur penjualan !');
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        _ambilData(result);
      } 
    })

}
/**/
  if(qparam.get('id')!=null){
      _clearForm();
      _formState2();  
      $("#id").val(qparam.get('id')).trigger('change');          
  }else if(qparam.get('nomor')!=null){
      _clearForm();
      _formState2();  
      $("#notrans").val(qparam.get('nomor')).trigger('change');          
  }else {
      _clearForm();
      _addRow();
      _inputFormat();
      _formState1();  
  }

})

var _hitungTotal = () => {
    let p = $('#pajak').val();
    let nilaiP = Number($('#nilaipajak').val()/100);  
    let nilaiPph22 = Number($('#nilaipph22').val()/100);                  
    let tqty = Number($('#tqty').val().split('.').join('').toString().replace(',','.'));    
    let tsubtotal = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    let tpjk = 0, tpph22 = 0, ttrans = 0, tdp = 0, tsisa = 0;

    if(p==2){
      tpjk = (100/110)*tsubtotal;
      tpjk = tpjk*nilaiP;
      tpjk = Math.floor((tpjk*100)/100);      
      if($('#chkpph22').prop('checked') == true) {
        tpph22 = (100/110)*tsubtotal;
        tpph22 = tpph22*nilaiPph22;  
        tpph22 = Math.floor((tpph22*100)/100);        
      }   
      ttrans = tsubtotal;
    }else if(p==1){
      tpjk = tsubtotal*nilaiP;
      tpjk = Math.floor((tpjk*100)/100);      
      if($('#chkpph22').prop('checked') == true) {      
        tpph22 = tsubtotal*nilaiPph22;  
        tpph22 = Math.floor((tpph22*100)/100);            
      }
      ttrans = tsubtotal+tpjk+tpph22;
    }else{
      tpjk = 0;
      tpph22 = 0;
      ttrans = tsubtotal+tpjk+tpph22;
    }

    tqty = tqty.toString().replace('.',',');
    tsubtotal = tsubtotal.toString().replace('.',',');            
    tpjk = tpjk.toString().replace('.',',');
    tpph22 = tpph22.toString().replace('.',',');            
    ttrans = ttrans.toString().replace('.',',');

    if(tqty==0) {
      if($("#decimalqty").val()==0) tqty='0';
      if($("#decimalqty").val()==1) tqty='0,0';      
      if($("#decimalqty").val()==2) tqty='0,00';      
      if($("#decimalqty").val()==3) tqty='0,000';      
      if($("#decimalqty").val()==4) tqty='0,0000';
      if($("#decimalqty").val()==5) tqty='0,00000';                  
    }
    if(tsubtotal==0) tsubtotal='0,00';
    if(tpjk==0) tpjk='0,00';
    if(tpph22==0) tpph22='0,00';            
    if(ttrans==0) ttrans='0,00';

    $('#tqty').val(tqty).attr('placeholder',tqty);
    $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
    $('#tpajak').val(tpjk).attr('placeholder',tpjk);
    $('#tpajakpph22').val(tpph22).attr('placeholder',tpph22);        
    $('#ttrans').val(ttrans).attr('placeholder',ttrans);      
}

var _hitungJumlahDetil = (idx) => {
  let vqty = Number($("input[name^='qty']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vharga = Number($("input[name^='harga']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vdiskon = Number($("input[name^='diskon']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vsubtotal = 0;
 
  vsubtotal = (vharga-vdiskon)*vqty;
  vsubtotal = vsubtotal.toString().replace('.',',');  

  if(vsubtotal==0) vsubtotal='0,00';
  $("input[name^='subtotal']").eq(idx).val(vsubtotal).attr('placeholder',vsubtotal);
  
  return vsubtotal;
}

var _hitungsubtotal = () => {
  let tqty = 0, tsubtotal = 0;
  
  $('.item').each(function(index,element) {
    tqty += Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')); 
    tsubtotal += Number($("input[name^='subtotal']").eq(index).val().split('.').join('').toString().replace(',','.'));     
  });  

  tqty = tqty.toString().replace('.',',');
  tsubtotal = tsubtotal.toString().replace('.',',');            

  if(tqty==0) tqty='0,00';
  if(tsubtotal==0) tsubtotal='0,00';

  $('#tqty').val(tqty).attr('placeholder',tqty);
  $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
  return;
}

window._hapusbaris = async (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  await _hitungsubtotal();
  _hitungTotal();
}