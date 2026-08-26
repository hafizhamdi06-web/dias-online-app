/* ========================================================================================== */
/* File Name : uangmuka-pembelian.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Select2_Account } from '../../component.js';

$(function() {
  
  const qparam = new URLSearchParams(this.location.search);  

  setupHiddenInputChangeListener($('#id')[0]);
  setupHiddenInputChangeListener($('#notrans')[0]);    

  Component_Inputmask_Date('.datepicker');
  Component_Inputmask_Numeric('.numeric');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#term',`${base_url}Select_Master/view_termin`,'form_termin','Termin');
  Component_Select2_Account(
    `#coadebit,#coahutang`,
    `${base_url}Select_Master/view_coa`,
    `form_coa`,
    `Akun`
  );  
  Component_Select2_Account(
    `#coakredit`,
    `${base_url}Select_Master/view_coa_kasbank`,
    `form_coa`,
    `Akun`
  );
  $('.datepicker').datepicker('setDate','dd-mm-yy');

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
    location.href=base_url+"page/umbData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-umb");
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
          parent.window.$("#modaltrigger").val("iframe-page-umb");
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

  $("#badd").click(function() {
    _clearForm();
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
        parent.window.$("#modaltrigger").val("iframe-page-umb");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');                  
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);   
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');           
        parent.window._transaksidatatable('view_uang_muka_pembelian');
        setTimeout(function (){
             parent.window.$('#modal input').focus();
        }, 500);
        return;
      } 
    });   
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

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-umb/${$("#id").val()}`)    
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

  $("#chklunas").click(function(){
    if ($(this).is(":checked"))
    {
      $("#kasbank").removeClass("d-none");
      $("#termin").addClass("d-none");
      $("#hutang").addClass("d-none");      
    } else {
      $("#kasbank").addClass("d-none");
      $("#termin").removeClass("d-none");      
//      $("#hutang").removeClass("d-none");            
    }
      $("#coakredit").val(null).trigger('change');
      $("#coahutang").val(null).trigger('change');      
      $("#term").val(null).trigger('change');          
  })

  $(this).on('shown.bs.tooltip', function (e) {
    setTimeout(function () {
      $(e.target).tooltip('hide');
    }, 2000);
  });  

/**/

/* ========================================================================================== */

var _clearForm = () => {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .nilaipajak").val('');
  $(":checkbox").prop("checked", true); 
  $('.select2').val('').change();
  $('#pajak').val('0').change();          
  $('#namakontak').html("");
  $('#namaperson').html(""); 
  $('.datepicker').datepicker('setDate','dd-mm-yy'); 
  $('.total').val('0');  
  $('#uang').val('Rp');
  $('#kurs').val('1');  
  $("#kasbank").removeClass("d-none");
  $("#termin").addClass("d-none");      
  $("#hutang").addClass("d-none");  
}

var _formState1 = () => {
  $('.input-group-append').attr('data-dismiss','modal');
  $('.input-group-append').attr('data-toggle','modal');
  $('.input-group-append').attr('role','button');    
  $('.btn-step2').addClass('disabled');
  $('.btn-step1').removeClass('disabled');
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").removeAttr('disabled');

  if($("#id").val()==''){
    $("#stslunas").removeClass("d-none");
    $(":checkbox").prop("disabled", false);     
  } else {
    $("#stslunas").addClass("d-none");
    $(":checkbox").prop("disabled", true);         
  }

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
  $(":checkbox").prop("disabled", true); 
  $("#stslunas").addClass("d-none");        
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
    if($('#coadebit').val()=='' || $('#coadebit').val()==null){
      $('#coadebit').attr('data-title','Akun uang muka harus diisi !');
      $('#coadebit').tooltip('show');
      $('#coadebit').focus();
      return 0;
    }
    if($("#chklunas").prop('checked')==true){
      if($('#coakredit').val()=='' || $('#coakredit').val()==null){
        $('#coakredit').attr('data-title','Akun kas/bank harus diisi !');
        $('#coakredit').tooltip('show');
        $('#coakredit').focus();
        return 0;
      }
    } else {
      if($('#term').val()=='' || $('#term').val()==null){
        $('#term').attr('data-title','Termin harus diisi !');
        $('#term').tooltip('show');
        $('#term').focus();
        return 0;
      }      
    }
    if($('#jumlah').val()=='0' || $('#jumlah').val()=='0,00'){
      $('#jumlah').attr('data-title','Jumlah uang muka harus diisi !');
      $('#jumlah').tooltip('show');
      $('#jumlah').focus();
      return 0;
    }

    return 1;
}

var _deleteData = () => {
  const id = $("#id").val(),
        nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"PB_Uang_Muka_Pembelian/deletedata", 
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
        uraian = $("#uraian").val(),
        coadp = $("#coadebit").val(),
        coakas = $("#coakredit").val(),        
        termin = $("#term").val(),                
        uang = 0,
        kurs = Number($("#kurs").val().split('.').join('').toString().replace(',','.')),
        jumlah = Number($("#jumlah").val().split('.').join('').toString().replace(',','.')),
        tipe = 1;

    if($("#chklunas").prop('checked')==false){
      tipe = 0;
    }

    var rey = new FormData();  
    rey.set('id',id);
    rey.set('tgl',tgl);
    rey.set('nomor',nomor);
    rey.set('kontak',kontak);
    rey.set('uraian',uraian);
    rey.set('coadp',coadp);
    rey.set('coakas',coakas);    
    rey.set('uang',uang);
    rey.set('kurs',kurs);
    rey.set('termin',termin);    
    rey.set('jumlah',jumlah);          
    rey.set('tipe',tipe);              

    $.ajax({ 
        "url"    : base_url+"PB_Uang_Muka_Pembelian/savedata", 
        "type"   : "POST", 
        "data"   : rey,
        "processData": false,
        "contentType": false,
        "cache"    : false,
        "beforeSend" : () => {
            parent.window.$(".loader-wrap").removeClass("d-none");
        },
        "error": (xhr, status, error) => {
            parent.window.$(".loader-wrap").addClass("d-none");
            parent.window.toastr.error(`Error : ${xhr.status} ${error}`);      
            console.error(xhr.responseText);      
            return;
        },
        "success": (result) => {
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
                      window.open(`${base_url}Laporan/preview/page-umb/${result.nomor}`)
                    }
                    parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
                    _clearForm();
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

        const coadp = $("<option selected='selected'></option>").val(result.data[0]['idcoadp']).text(result.data[0]['coadp']);
        const coakas = $("<option selected='selected'></option>").val(result.data[0]['idcoakas']).text(result.data[0]['coakas']);
        const termin = $("<option selected='selected'></option>").val(result.data[0]['idtermin']).text(result.data[0]['termin']);                                

        if(result.data[0]['tipe']==1) {
          $("#chklunas").prop('checked',true);          
          $("#kasbank").removeClass("d-none");
          $("#termin").addClass("d-none");      
          $("#hutang").addClass("d-none");            
        } else {
          $("#chklunas").prop('checked',false);
          $("#kasbank").addClass("d-none");
          $("#termin").removeClass("d-none"); 
 //        $("#hutang").removeClass("d-none");                      
        }

        $("#stslunas").addClass("d-none");      
        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);
        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
//        $('#idreferensi').val(result.data[0]['orderid']);                        
//        $('#refnomor').val(result.data[0]['nomororder']);            
        $('#uraian').val(result.data[0]['uraian']);            
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontakkode']);
        $('#namakontak').html(result.data[0]['kontak']);
        $('#term').append(termin).trigger('change');
        $('#coadebit').append(coadp).trigger('change');        
        $('#coakredit').append(coakas).trigger('change');                
        $("#jumlah").val(result.data[0]['jumlah']);

        if($('.btn-step1').hasClass('disabled')){
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
    "url"    : base_url+"PB_Uang_Muka_Pembelian/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi uang muka pembelian !');
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
    "url"    : base_url+"PB_Uang_Muka_Pembelian/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "nomor="+nomor,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');                  
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data transaksi uang muka pembelian !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {
      _tampildata(result);
    } 
})

}

/**/

if(qparam.get('id')!=null){
    _formState2();  
    $("#id").val(qparam.get('id')).trigger('change');          
}else if(qparam.get('nomor')!=null){
    _formState2();  
    $("#notrans").val(qparam.get('nomor')).trigger('change');          
}else {
    _formState1();  
}

});