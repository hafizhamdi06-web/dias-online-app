/* ========================================================================================== */
/* File Name : jurnal-umum.js
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
  Component_Scrollbars('.tab-wrap','hidden','scroll');

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
      parent.window.$(".loader-wrap").addClass("d-none");
  }

/**/

/* Kontrol (Button, Anchor, Etc..)
/* ========================================================================================== */

  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
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
    location.href=base_url+"page/juData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-ju");
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
          parent.window.$(".loader-wrap").removeClass("d-none");                                              
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-ju");
          parent.window.$('#coltrigger').val('vendor');                    
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");                                      
          console.log('error menampilkan modal cari kontak...');
          return;
        },
        "success": function(result) {      
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window._lstkategorikontak();
          parent.window._kontakdatatable();
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
    _addRow();
    _inputFormat();      
    _formState1();
  });

  $("#bedit").click(function() {
    if($('#id').val()=='') return;

    if($('#status').val()=='1'){       
      parent.window.toastr.info("Transaksi sudah di posting, tidak bisa diedit !");      
    } else {
      _formState1();
    }
  });  

  $("#bdelete").click(function() {
    if($('#id').val()=='') return;

    if($('#status').val()=='1'){       
      parent.window.toastr.info("Transaksi sudah di posting, tidak bisa dihapus !");      
    } else {
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
    }
  });

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");                                          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-ju");        
      },       
      "error": function(){
        parent.window.$(".loader-wrap").addClass("d-none");                                          
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);      
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');        
        parent.window._transaksidatatable('view_jurnal_umum');
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
      $("select[name^='coa']").last().focus();    
  });

  $("#bcancel").click(function() {
      _clearForm();
      _addRow();_addRow();
      _inputFormat();
      _formState2();
  });

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-ju/${$("#id").val()}`)    
  });

  $("#bsave").click(function() {
      if (_IsValid()===0) return;
      _saveData();
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

  $(this).on("keyup", "input[name^='amtdebet']", async function(){
      let _idx = $(this).index('.amtdebet');
      if(Number($("input[name^='amtdebet']").eq(_idx).val().split('.').join('').toString().replace(',','.'))>0){
        $("input[name^='amtkredit']").eq(_idx).val('0').attr('placeholder','0,00');
      }

      let jumlah = await _hitungJumlahDetil(_idx);
      //Hitung Total
      _hitungsubtotal();
      return;
  });

  $(this).on("keyup", "input[name^='amtkredit']", async function(){
      let _idx = $(this).index('.amtkredit');
      if(Number($("input[name^='amtkredit']").eq(_idx).val().split('.').join('').toString().replace(',','.'))>0){
        $("input[name^='amtdebet']").eq(_idx).val('0').attr('placeholder','0,00');        
      }

      let jumlah = await _hitungJumlahDetil(_idx);
      //Hitung Total
      _hitungsubtotal();
      return;
  });  

  $(this).on('shown.bs.tooltip', function (e) {
    setTimeout(function () {
      $(e.target).tooltip('hide');
    }, 2000);
  });  

/**/


/* Other Function 
/* Etc
/* ========================================================================================== */

  var _inputFormat = () => {
      Component_Inputmask_Numeric('.numeric');
      Component_Select2_Account('.coa',`${base_url}Select_Master/view_coa`,'form_coa','Akun');
      Component_Select2('.divisi',`${base_url}Select_Master/view_divisi_kode`,'form_divisi','Divisi');
      Component_Select2('.proyek',`${base_url}Select_Master/view_proyek_kode`,'form_proyek','Proyek');
  }

  var _clearForm = () => {
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .noclear").val('');
    $(":checkbox").prop("checked", false); 
    $('.select2').val('').change(); 
    $('#namakontak').html('');       
    $('#tdetil tbody').html('');                                    
    $('#tgl').datepicker('setDate','dd-mm-yy'); 
    $('#totaldebet').val('0');
    $('#totalkredit').val('0');    
  }

  var _formState1 = () => {
    $('#carikontak').attr('data-dismiss','modal');
    $('#carikontak').attr('data-toggle','modal');
    $('#carikontak').attr('role','button');  
    $('.btn-step2').addClass('disabled');
    $('.btn-step1').removeClass('disabled');
    $('#baddrow').removeAttr('disabled');     
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").removeAttr('disabled');
    setTimeout(function () {
      $('#kontak').focus();        
    },300);
  }

  var _formState2 = () => {
    $('.btn-step2').removeClass('disabled');
    $('.btn-step1').addClass('disabled');
    $('#baddrow').attr('disabled','disabled'); 
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");   
    $('#carikontak').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
  }

  var _addRow = () => {
    let newrow = " <tr>";
        newrow += "<td><select name=\"coa[]\" class=\"coa form-control select2 form-control-sm\" style=\"width:100%;\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"amtdebet[]\" class=\"amtdebet form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";        
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"amtkredit[]\" class=\"amtkredit form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
        newrow += "<td><input type=\"text\" name=\"kurs[]\" class=\"kurs form-control form-control-sm numeric\" autocomplete=\"off\" value=\"1\" readonly></td>";
        if($("#multidivisi").val()==1){
          newrow += "<td><select name=\"divisi[]\" class=\"divisi form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";            
        } else {
          newrow += "<td class=\"d-none\"><select name=\"divisi[]\" class=\"divisi form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";                        
        }
        if($("#multiproyek").val()==1){
          newrow += "<td><select name=\"proyek[]\" class=\"proyek form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>"; 
        } else {
          newrow += "<td class=\"d-none\"><select name=\"proyek[]\" class=\"proyek form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>"; 
        }          
        newrow += "<td><textarea name=\"catatan[]\" class=\"catatan form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
        newrow += "</tr>";
    $('#tdetil tbody').append(newrow);
  }

/**/

/* CRUD
/* ========================================================================================== */

var _IsValid = (function(){
    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Kontak harus diisi !');
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

    let totalbaris = $(".coa").length;
    for(let i=0;i<totalbaris;i++){
      if($("select[name^='coa']").eq(i).val()=='' || $("select[name^='coa']").eq(i).val()==null){
        $("select[name^='coa']").eq(i).attr('data-title','Akun harus diisi !');      
        $("select[name^='coa']").eq(i).tooltip('show');      
        $("select[name^='coa']").eq(i).focus();
        return 0;
      }
    }

    if ($('#totaldebet').val()!==$('#totalkredit').val()){
      parent.window.Swal.fire({
        title: `Total Debit Dan Total Kredit Harus Sama`,
        showDenyButton: false,
        showCancelButton: false,
        confirmButtonText: `Tutup`,
      });
      return 0;
    }

    return 1;
});

var _deleteData = (function(){
  let id = $("#id").val(),
      nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"Fina_Jurnal_Umum/deletedata", 
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
        alert(result);
        parent.window.toastr.error(result);                          
        return;
      }
    } 
  });  
});

var _saveData = (function(){

    let id = $("#id").val(),
        tgl = $("#tgl").val(),
        nomor = $("#nomor").val(),
        kontak = $("#idkontak").val(),
        uraian = $("#uraian").val(),
        status = $("#status").val(),
        detil = [];

  $("select[name^='coa']").each(function(index,element){  
      detil.push({
               coa:this.value,
               jmldb:Number($("input[name^='amtdebet']").eq(index).val().split('.').join('').toString().replace(',','.')),
               jmlcr:Number($("input[name^='amtkredit']").eq(index).val().split('.').join('').toString().replace(',','.')),
               divisi:$("select[name^='divisi']").eq(index).val(),
               proyek:$("select[name^='proyek']").eq(index).val(),
               catatan:$("textarea[name^='catatan']").eq(index).val()
             });

  }); 

  detil = JSON.stringify(detil);  

  //Total Transaksi
  var totaldb = Number($("#totaldebet").val().split('.').join('').toString().replace(',','.')),
      totalcr = Number($("#totalkredit").val().split('.').join('').toString().replace(',','.'));  

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('uraian',uraian);
  rey.set('status',status);  
  rey.set('totaldb',totaldb);      
  rey.set('totalcr',totalcr);          
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"Fina_Jurnal_Umum/savedata", 
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
                window.open(`${base_url}Laporan/preview/page-ju/${result.nomor}`)
              }
              parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
              _clearForm();
              _addRow();
              _addRow();              
              _inputFormat();
              _formState1();
              return;
          })
      }                  
    } 
  });

});

var _tampilData = (result) => {
    if (typeof result.pesan !== 'undefined') { // Jika ada pesan maka tampilkan pesan
      alert(result.pesan);
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    } else { // Jika tidak ada pesan tampilkan json ke form
      
      /*Atur Baris Detil Transaksi*/
      $('#tdetil tbody').html('');
      for (let i = 0; i < result.data.length; i++) {
        _addRow();
      }
      _inputFormat();
      /**/

      /* Isi Header Transaksi */
      $('#id').val(result.data[0]['id']);            
      $('#nomor').val(result.data[0]['nomor']);
      $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
      $('#uraian').val(result.data[0]['uraian']);            
      $('#idkontak').val(result.data[0]['kontakid']);
      $('#kontak').val(result.data[0]['kontakkode']);
      $('#namakontak').html(result.data[0]['kontak']);                        
      $('#totaldebet').val(result.data[0]['total'].replace(".", ","));                        
      $('#totalkredit').val(result.data[0]['total'].replace(".", ","));                                    
      $('#status').val(result.data[0]['status']);                        
      /**/
      /* Isi Detil Transaksi */
      var rows = 0;
      $.each(result.data, function() {
        var coa = $("<option selected='selected'></option>").val(result.data[rows]['coadetilid']).text(result.data[rows]['coadetil']);            
        var divisi = $("<option selected='selected'></option>").val(result.data[rows]['divisidetilid']).text(result.data[rows]['divisidetil']);            

        $("select[name^='coa']").eq(rows).append(coa).trigger('change');            
        $("input[name^='amtdebet']").eq(rows).val(result.data[rows]['debit'].replace(".", ","));
        $("input[name^='amtkredit']").eq(rows).val(result.data[rows]['kredit'].replace(".", ","));              
        $("select[name^='divisi']").eq(rows).append(divisi).trigger('change');                                                                           
        $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catatandetil']);              

        //atur placeholder numeric jika 0
        if(result.data[rows]['debit']==0) $("input[name^='amtdebet']").eq(rows).attr('placeholder','0,00');            
        if(result.data[rows]['kredit']==0) $("input[name^='amtkredit']").eq(rows).attr('placeholder','0,00');                          

        rows++;
      });
      /**/

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
    if($("#notrans").val()!='') return;    

    $.ajax({ 
      "url"    : base_url+"Fina_Jurnal_Umum/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(){
        alert('Error : Gagal mengambil data transaksi jurnal umum !');
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        _tampilData(result);
      } 
  })

}

var _getDataTransaksiNomor = (nomor) => {

    if(nomor=='' || nomor==null) return;    

    $.ajax({ 
      "url"    : base_url+"Fina_Jurnal_Umum/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "nomor="+nomor,
      "cache"  : false,
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(){
        alert('Error : Gagal mengambil data transaksi jurnal umum !');
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        _tampilData(result);
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
      _addRow();_addRow();
      _inputFormat();
      _formState1();  
  }

}); 

var _hitungJumlahDetil = (idx) => {  
  return;
}

var _hitungsubtotal = () => {
  let tdebet = 0, tkredit = 0;
  
  $('.coa').each(function(index,element) {
    tdebet += Number($("input[name^='amtdebet']").eq(index).val().split('.').join('').toString().replace(',','.')); 
    tkredit += Number($("input[name^='amtkredit']").eq(index).val().split('.').join('').toString().replace(',','.'));     
  });  

  tdebet = tdebet.toString().replace('.',',');
  tkredit = tkredit.toString().replace('.',',');            

  if(tdebet==0) tdebet='0,00';
  if(tkredit==0) tkredit='0,00';

  $('#totaldebet').val(tdebet).attr('placeholder',tdebet);
  $('#totalkredit').val(tkredit).attr('placeholder',tkredit);      
  return;
}

window._hapusbaris = (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  _hitungsubtotal();
}