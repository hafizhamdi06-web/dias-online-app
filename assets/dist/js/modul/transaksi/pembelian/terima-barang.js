/* ========================================================================================== */
/* File Name : terima-barang.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Inputmask_Numeric_Flexible } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Select2_Item } from '../../component.js';
import { Component_Select2_Account } from '../../component.js';

$(function() {
  
  const qparam = new URLSearchParams(this.location.search);  

  setupHiddenInputChangeListener($('#id')[0]);
  setupHiddenInputChangeListener($('#notrans')[0]);    
  setupHiddenInputChangeListener($('#idreferensi')[0]);
  setupHiddenInputChangeListener($('#triggertdp')[0]);

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#pajak');
  Component_Select2('#termin',`${base_url}Select_Master/view_termin`,'form_termin','Termin');
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

  $('#refnomor').keydown(function(e){
    if(e.keyCode==13) { $('#cariorder').click(); }
  });  

  $(this).on('select2:open', () => {
    this.querySelector('.select2-search__field').focus();
  });  

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });

  $("#dTglFaktur").click(function() {
    if($(this).attr('role')) {
      $("#tglpajak").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/pbbData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-pbb");
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

  $("#bViewJurnalDP").click(function() {
      if($("#id").val()=="") return;

      $.ajax({ 
        "url"    : base_url+"Modal/lihat_jurnal", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");            
          parent.window.$(".modal-title").html("Jurnal "+$("#nomordp").val());
          parent.window.$("#modaltrigger").val("iframe-page-pbb");
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
          parent.window._transaksidatatable($("#nomordp").val());
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
          parent.window.$("#modaltrigger").val("iframe-page-pbb");
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
          parent.window.$("#modaltrigger").val("iframe-page-pbb");
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
          parent.window.$("#modaltrigger").val("iframe-page-pbb");
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

  var _InitDp = () => {
    Component_Inputmask_Date('.datepicker', true);
    Component_Inputmask_Numeric('.numeric', true);    
    Component_Select2('#pajak',null,null,null,true);        
    Component_Select2_Account('#coadp',`${base_url}Select_Master/view_coa`,null,null,true);    
    window.parent.$('.datepicker').datepicker('setDate','dd-mm-yy');              
  }

  $("#buangmuka").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/uang_muka_pembelian", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');          
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Uang Muka Pembelian");
          parent.window.$("#modaltrigger").val("iframe-page-pbb");
          parent.window.$('#coltrigger').val('');          
        },         
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');          
          console.log('error menampilkan form pembayaran uang muka...');
          return;
        },
        "success": function(result) {                
          parent.window.$(".main-modal-body").html(result);      
          _InitDp();
          if(Number($("#triggertdp").val())>0){
            const coadp = $("<option selected='selected'></option>").val($("#coadp").val()).text($("#coadpname").val()),
                  coadpkas = $("<option selected='selected'></option>").val($("#coadpkas").val()).text($("#coadpkasname").val());
            parent.window.$('#id').val($("#iddp").val()); 
            parent.window.$('#tgl').datepicker('setDate',$("#tgldp").val()); 
            parent.window.$('#nomor').val($("#nomordp").val());             
            parent.window.$('#uraian').val($("#uraiandp").val());
            parent.window.$('#pajak').val($("#pajakdp").val()).trigger('change');   
            parent.window.$('#nofakturpjk').val($("#fakturpajakdp").val());  
            parent.window.$('#tglpajak').val($("#tglpajakdp").val());                                    
            parent.window.$('#coadp').append(coadp).trigger("change");
            parent.window.$('#coadpkas').append(coadpkas).trigger("change");                        
            parent.window.$('#jumlah').val($("#tdpjumlah").val());
            parent.window.$('#jumlahpjk').val($("#tdppajak").val());            
            parent.window.$('#total').val($("#triggertdp").val());                                    
          }
          setTimeout(function (){
               parent.window.$('#uraian').focus();
          }, 500);
          parent.window.$('.loader-wrap').addClass('d-none');                            
          return;
        } 
      });
    }    
  });

  $("#bresetdp").click(function(){
    if($(this).attr("role")) {
        parent.window.Swal.fire({
          title: 'Anda yakin akan menghapus uang muka ?',
          showDenyButton: false,
          showCancelButton: true,
          confirmButtonText: `Iya`,
        }).then((result) => {
          if (result.isConfirmed) {
            if($("#iddp").val() == '') {
                $("#iddp").val("");
                $("#tdp").val("0,00");
                $("#triggertdp").val(0);
                _hitungTotal();
                $("#bresetdp").addClass("d-none");
            } else {
                $.ajax({ 
                  "url"    : base_url+"PB_Terima_Barang/deletedp", 
                  "type"   : "POST", 
                  "data"   : "id="+$("#id").val(),
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
                      $("#iddp").val("");
                      $("#tdp").val("0,00");
                      $("#triggertdp").val(0);
                      _hitungTotal();
                      $("#bresetdp").addClass("d-none");
                      return;
                    } else {        
                      parent.window.toastr.error(result);      
                      return;
                    }
                  } 
                });
            }
          }
        })    
    }
  })

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

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-pbb");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');                  
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);   
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');           
        parent.window._transaksidatatable('view_penerimaan_barang');
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);
        return;
      } 
    });   
  });

  $("#cariorder").click(function() {    
  if($(this).attr('role')) {        
    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Vendor harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    } 

    let totalbaris = $(".item").length;
    let stringpo = "";
    for(let i=0;i<totalbaris;i++){
        if(i > 0) {
          stringpo = stringpo + ',' + $("input[name^='noref']").eq(i).val();
        } else {
          stringpo = $("input[name^='noref']").eq(i).val();
        }
    }    

    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi_multiple", 
      "type"   : "POST", 
      "dataType" : "html", 
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');                  
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Order Pembelian");
        parent.window.$("#modaltrigger").val("iframe-page-pbb");        
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');                          
        console.log('error menampilkan modal cari order pembelian...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);      
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');        
        parent.window.$("#param1").val(stringpo); 
        parent.window._transaksidatatable('view_cari_order_pembelian_new',$('#idkontak').val());
        setTimeout(function (){
             parent.window.$("#modal input[type='search']").focus();
        }, 500);        
        return;
      } 
    });   
  }
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

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-pbb/${$("#id").val()}`)    
  });

  $('#pajak').on('change',function(){
    _hitungTotal();
  });

  $('#id').on('change',function(){
    var idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });  

  $('#notrans').on('change',function(){
    var notrans = $(this).val();
    _formState2();
    _getDataTransaksiNomor(notrans);
  });    

  $('#idreferensi').on('change',function(){
    var id = $(this).val();
    _getDataOrder(id);
  });    

  $('#triggertdp').on('change',function(){
    _hitungTotal();
    if($("#tdp").val() == '0,00' || $("#tdp").val() == '0' || $("#tdp").val() == '') {
        $("#bresetdp").addClass("d-none");         
    } else {
        $("#bresetdp").removeClass("d-none");                     
    }    
  });  

  $(this).on("keyup", "input[name^='qty']", async function(){
      let _idx = $(this).index('.qty');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='harga']", async function(){
      let _idx = $(this).index('.harga');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });

  $(this).on("keyup", "input[name^='diskon']", async function(){
      let _idx = $(this).index('.diskon');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });    

  $(this).on("select2:select", "select[name^='item']", function(){

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.item');

      $.ajax({ 
        "url"    : base_url+"PB_Terima_Barang/get_item", 
        "type"   : "POST", 
        "data"   : "id="+$(this).val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(){
          alert('Error : Data item tidak valid ...');
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) {
          let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']);          
          
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change'); 
          $("select[name^='satuan']").eq(_idx).prop('disabled',true);            
          $("input[name^='harga']").eq(_idx).val(result.data[0]['harga']);    
          
          if(result.data[0]['harga']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');     

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
  $('#pajak').val('0').change();          
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
  $(".satuan").prop('disabled',true);                 
  
  setTimeout(function () {
    $('#kontak').focus();        
  },300);
}

var _formState2 = () => {
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
      newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";      
      newrow += "<td><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";      
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
      newrow += "<td><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
      newrow += "<td><input type=\"hidden\" name=\"noref[]\" class=\"noref\"><input type=\"hidden\" name=\"idrefdet[]\" class=\"idrefdet\"><input type=\"text\" name=\"refnodetil[]\" class=\"refnodetil form-control form-control-sm\" autocomplete=\"off\" readonly></td>";      
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
  const id = $("#id").val(),
        nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"PB_Terima_Barang/deletedata", 
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

var _saveData = () => {

  let id = $("#id").val(),
      tgl = $("#tgl").val(),
      nomor = $("#nomor").val(),
      kontak = $("#idkontak").val(),
      person = $("#idperson").val(),
      karyawan = $("#idbagbeli").val(),
      termin = $("#termin").val(),
      pajak = $("#pajak").val(),
      alamat = $("#alamat").val(), 
      status = $("#status").val(),            
      nopajak = $("#nofakturpajak").val(),
      tglpajak = $("#tglpajak").val(),                        
      gudang = $("#gudang").val(),
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
               noorder:$("input[name^='idrefdet']").eq(index).val(),
               proyek:$("select[name^='proyekdetil']").eq(index).val(),                              
               gudang:$("select[name^='gudangdetil']").eq(index).val()               
             });
  });

  detil = JSON.stringify(detil);

  const totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.')),
        totalsub = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')),
        totalpajak = Number($("#tpajak").val().split('.').join('').toString().replace(',','.')),
        totaltrans = Number($("#ttrans").val().split('.').join('').toString().replace(',','.')),
        totaldp = Number($("#tdp").val().split('.').join('').toString().replace(',','.')),
        totalsisa = Number($("#tsisa").val().split('.').join('').toString().replace(',','.')),
        iddp = $("#iddp").val(),
        tgldp = $("#tgldp").val(),
        nomordp = $("#nomordp").val(),
        uraiandp = $("#uraiandp").val(),
        coadp = $("#coadp").val(),
        coadpkas = $("#coadpkas").val(),
        pajakdp = $("#pajakdp").val(),
        tglpajakdp = $("#tglpajakdp").val(),
        fakturpajakdp = $("#fakturpajakdp").val(),
        tdppajak = $("#tdppajak").val();

  var rey = new FormData();

  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('person',person);
  rey.set('karyawan',karyawan);    
  rey.set('termin',termin);
  rey.set('pajak',pajak);
  rey.set('nopajak',nopajak);    
  rey.set('tglpajak',tglpajak);      
  rey.set('alamat',alamat); 
  rey.set('gudang',gudang); 
  rey.set('status',status);      
  rey.set('totalqty',totalqty);      
  rey.set('totalsub',totalsub);
  rey.set('totalpajak',totalpajak);            
  rey.set('totaltrans',totaltrans);  
  rey.set('totaldp',totaldp);            
  rey.set('totalsisa',totalsisa);                
  rey.set('iddp',iddp);
  rey.set('tgldp',tgldp);
  rey.set('nomordp',nomordp);
  rey.set('uraiandp',uraiandp);
  rey.set('coadp',coadp);      
  rey.set('coadpkas',coadpkas);                            
  rey.set('pajakdp',pajakdp);
  rey.set('tglpajakdp',tglpajakdp);
  rey.set('fakturpajakdp',fakturpajakdp);
  rey.set('tdppajak',tdppajak);                                                                                                                        
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"PB_Terima_Barang/savedata", 
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
          parent.window.Swal.fire({
              title: `Anda ingin mencetak transaksi ini ?`,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Iya`,
          }).then((printing) => {
              if (printing.isConfirmed) {
                window.open(`${base_url}Laporan/preview/page-pbb/${result.nomor}`)
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
  })
}

var _tampildata = (result) => {
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

        const term = $("<option selected='selected'></option>").val(result.data[0]['idtermin']).text(result.data[0]['termin']);
        const gudang = $("<option selected='selected'></option>").val(result.data[0]['idgudang']).text(result.data[0]['gudang']);                        

        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);
        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
//        $('#idreferensi').val(result.data[0]['orderid']);                        
//        $('#refnomor').val(result.data[0]['nomororder']);            
        $('#uraian').val(result.data[0]['uraian']);            
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontakkode']);
        $('#namakontak').html(result.data[0]['kontak']);
        $('#idperson').val(result.data[0]['idperson']);
        $('#person').val(result.data[0]['person']);
        $('#idbagbeli').val(result.data[0]['idkaryawan']);
        $('#bagbeli').val(result.data[0]['namakaryawan']);                                    
        $('#termin').append(term).trigger('change');
        $('#gudang').append(gudang).trigger('change');            
        $('#pajak').val(result.data[0]['pajak']).change();
        $('#status').val(result.data[0]['status']);        
        $('#alamat').val(result.data[0]['alamat']);                                                                                                                     

        var rows = 0, _tqty = 0, _tsubtotal = 0, _tpajak = 0, _ttrans = 0, _tdp = 0, _tsisa = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']),
              _proyek = $("<option selected='selected'></option>").val(result.data[rows]['idproyek']).text(result.data[rows]['proyek']),
              _gudangd = $("<option selected='selected'></option>").val(result.data[rows]['idgudang']).text(result.data[rows]['gudang']);                        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
          $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));
          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskon'].replace(".", ","));
          $("input[name^='persen']").eq(rows).val(result.data[rows]['persendiskon'].replace(".", ","));
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));            
          $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catdetil']);
          $("input[name^='noref']").eq(rows).val(result.data[rows]['morderid']);           
          $("input[name^='idrefdet']").eq(rows).val(result.data[rows]['orderdid']);                                                                      
          $("input[name^='refnodetil']").eq(rows).val(result.data[rows]['nomororderd']);                                                          
          $("select[name^='proyekdetil']").eq(rows).append(_proyek).trigger('change');
          $("select[name^='gudangdetil']").eq(rows).append(_gudangd).trigger('change');                        

          _tqty += Number(result.data[rows]['qtydetil']);
          _tsubtotal += Number(result.data[rows]['subtotaldetil']);            

          if(result.data[rows]['qtydetil']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['hargadetil']==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');                        
          if(result.data[rows]['diskon']==0) $("input[name^='diskon']").eq(rows).attr('placeholder','0,00');
          if(result.data[rows]['persendiskon']==0) $("input[name^='persen']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['subtotaldetil']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');

          rows++;
        });

          _tpajak = Number(result.data[0]['tpajak']);
          if(result.data[0]['pajak']=='2'){
            _ttrans = _tsubtotal;
          }else{
            _ttrans = _tsubtotal + _tpajak;
          }
          _tdp = Number(result.data[0]['tdp']);            
          _tsisa = _ttrans - _tdp;

          $('#tqty').val(_tqty.toString().replace('.',','));   
          $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
          $('#tpajak').val(_tpajak.toString().replace('.',','));               
          $('#ttrans').val(_ttrans.toString().replace('.',',')); 
          $('#tdp').val(_tdp.toString().replace('.',','));
          $('#tsisa').val(_tsisa.toString().replace('.',','));

          $('#nofakturpajak').val(result.data[0]['nofakturpjk']);        
          $('#tglpajak').datepicker('setDate',result.data[0]['tglpajak']);                    
          $('#iddp').val(result.data[0]['iddp']);
          $('#tgldp').val(result.data[0]['tgldp']);          
          $('#nomordp').val(result.data[0]['nomordp']);      
          $('#uraiandp').val(result.data[0]['uraiandp']);
          $('#coadp').val(result.data[0]['coadp']);
          $('#coadpname').val(result.data[0]['coadpname']);                                            
          $('#pajakdp').val(result.data[0]['pajakdp']);
          $('#tglpajakdp').val(result.data[0]['tgldp']);          
          $('#fakturpajakdp').val(result.data[0]['fakturpajakdp']);                    
          $('#tdpjumlah').val(result.data[0]['tdpjumlah']);                    
          $('#tdppajak').val(result.data[0]['tdppajak']);                 
          $('#triggertdp').val(_tdp);

        if($('.btn-step1').hasClass('disabled')){
          $('.btn-delrow').addClass('disabled');
          $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
          $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");
        }
        parent.window.$('.loader-wrap').addClass('d-none');                                       
        return;

      }
}

var _getDataTransaksi = (id) => {

  if(id=='' || id==null) return;    

  $.ajax({ 
    "url"    : base_url+"PB_Terima_Barang/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi penerimaan barang !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result){
      _tampildata(result);
    } 
  })
}

var _getDataTransaksiNomor = (nomor) => {

  if(nomor=='' || nomor==null) return;    

  $.ajax({ 
    "url"    : base_url+"PB_Terima_Barang/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "nomor="+nomor,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');                  
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi penerimaan barang !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {
      _tampildata(result);
    } 
})

}

var _getDataOrder = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PB_Terima_Barang/getdataorder",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      parent.window.toastr.error('Error : Gagal mengambil data order pembelian !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : async function(result){
      if (typeof result.pesan !== 'undefined') { 
        parent.window.toastr.error(result.pesan);
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
              _satuan = $("<option selected='selected'></option>").val(result.data[datarows]['satuan']).text(result.data[datarows]['namasatuan']);

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qty']").eq(rows).val(result.data[datarows]['qty'].replace(".", ","));            
          $("input[name^='harga']").eq(rows).val(result.data[datarows]['harga'].replace(".", ","));
          $("input[name^='diskon']").eq(rows).val(result.data[datarows]['diskon'].replace(".", ","));
          $("input[name^='persen']").eq(rows).val(result.data[datarows]['persen'].replace(".", ","));
          $("input[name^='subtotal']").eq(rows).val(result.data[datarows]['jumlah'].replace(".", ","));          
          $("textarea[name^='catatan']").eq(rows).val(result.data[datarows]['catatan']); 
          $("input[name^='idrefdet']").eq(rows).val(result.data[datarows]['id']);                                                                      
          $("input[name^='refnodetil']").eq(rows).val(result.data[datarows]['nomor']);   
          $("input[name^='noref']").eq(rows).val(result.data[datarows]['orderid']);                                                                  

          if(result.data[datarows]['qty']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['harga']==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');                        
          if(result.data[datarows]['diskon']==0) $("input[name^='diskon']").eq(rows).attr('placeholder','0,00');
          if(result.data[datarows]['persen']==0) $("input[name^='persen']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['jumlah']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');            

          datarows++;
          rows++;
        });

        let subtotal = await _hitungsubtotal();
        _hitungTotal();

        parent.window.$('.loader-wrap').addClass('d-none');   
        return;                       
      }
    }
  });
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

});

var _hitungTotal = () => {
    let p = $('#pajak').val();
    let nilaiP = Number($('#nilaipajak').val()/100);      
    let tqty = Number($('#tqty').val().split('.').join('').toString().replace(',','.'));    
    let tsubtotal = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    let tdp = Number($('#tdp').val().split('.').join('').toString().replace(',','.'));    
    let tpjk = 0, ttrans = 0, tsisa = 0;


    if(p==2){
      tpjk = (100/110)*tsubtotal;
      tpjk = tpjk*nilaiP;
      ttrans = tsubtotal;
      tsisa = ttrans-tdp;
    }else if(p==1){
      tpjk = tsubtotal*nilaiP;
      ttrans = tsubtotal+tpjk;
      tsisa = ttrans-tdp;
    }else{
      tpjk = 0;
      ttrans = tsubtotal+tpjk;
      tsisa = ttrans-tdp;
    }

      tqty = tqty.toString().replace('.',',');
      tsubtotal = tsubtotal.toString().replace('.',',');            
      tpjk = tpjk.toString().replace('.',',');
      ttrans = ttrans.toString().replace('.',',');
      tdp =  tdp.toString().replace('.',',');
      tsisa = tsisa.toString().replace('.',',');

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
      if(ttrans==0) ttrans='0,00';
      if(tdp==0) tdp='0,00';
      if(tsisa==0) tsisa='0,00';

      $('#tqty').val(tqty).attr('placeholder',tqty);
      $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
      $('#tpajak').val(tpjk).attr('placeholder',tpjk);
      $('#ttrans').val(ttrans).attr('placeholder',ttrans);      
      $('#tdp').val(tdp).attr('placeholder',tdp);                  
      $('#tsisa').val(tsisa).attr('placeholder',tsisa);            

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