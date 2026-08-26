/* ========================================================================================== */
/* File Name : pembayaran-hutang.js
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
  setupHiddenInputChangeListener($('#idfaktur')[0]);  
  setupHiddenInputChangeListener($('#idretur')[0]);
  setupHiddenInputChangeListener($('#triggerpph')[0]);        
  setupHiddenInputChangeListener($('#statussetor')[0]);        

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#tipebayar');
  Component_Select2_Account('#coakredit',`${base_url}Select_Master/view_coa_kasbank`,'form_coa','Akun');
  Component_Select2_Account('#coaselisih',`${base_url}Select_Master/view_coa`,'form_coa','Akun');  
  Component_Select2('#bank',`${base_url}Select_Master/view_bank`,'form_bank','Bank');      

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

  $("#dTglCek").click(function() {
    if($(this).attr('role')) {
      $("#tglcekgiro").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/phbData";      
  });

  $("#tipebayar").on("select2:select",function() {
    if($(this).val() == 1) {
      $(".giro").removeClass('d-none');
      $(".transfer").addClass('d-none');      
      return;
    }
    if($(this).val() == 2) {
      $(".giro").addClass('d-none');
      $(".transfer").removeClass('d-none');      
      return;
    }
    if($(this).val() == 0) {
      $(".giro").addClass('d-none');
      $(".transfer").addClass('d-none');      
      return;
    }
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
          parent.window.$("#modaltrigger").val("iframe-page-phb");
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

  $("#calcrp").click(async function() {
    if($(this).attr('role')) {
      await _hitungkasbank();
      if(Number($("#totalselisih").val().split('.').join('').toString().replace(',','.')) != 0) {
        $("#lebihbayar").removeClass("d-none");
      } else {
        $("#lebihbayar").addClass("d-none");      
      }      
    }
  })

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
          parent.window.$("#modaltrigger").val("iframe-page-phb");
          parent.window.$('#coltrigger').val('kontak');                       
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

  $("#bcarifaktur").click(function() {   
    if($(this).hasClass("disabled")) return;

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Vendor harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    } 
    if($('#coakredit').val()=='' || $('#coakredit').val()==null){
      $('#coakredit').attr('data-title','Akun Kas/Bank harus diisi !');
      $('#coakredit').tooltip('show');
      $('#coakredit').focus();
      return;
    } 

    let totalbaris = $(".idinv").length;
    let stringinv = "";
    for(let i=0;i<totalbaris;i++){
        if(i > 0) {
          stringinv = stringinv + ',' + `'${$("input[name^='nomorinv']").eq(i).val()}'`;
        } else {
          stringinv = `'${$("input[name^='nomorinv']").eq(i).val()}'`;
        }
    }    

    $.ajax({ 
      "url"    : base_url+"Modal/cari_faktur", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');
        parent.window.$(".modal").modal("show");  
        parent.window.$(".modal-title").html("Cari Data Tagihan");
        parent.window.$("#modaltrigger").val("iframe-page-phb");   
        parent.window.$('#coltrigger').val('idfaktur');
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');
        console.log('error menampilkan modal cari faktur pembelian...');
        return;
      },
      "success": function(result) {                             
        parent.window.$(".main-modal-body").html(result);     
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');      
        parent.window.$("#param1").val(stringinv);                             
        parent.window._transaksidatatable('view_cari_faktur_pembelian_dp',$('#idkontak').val());
        setTimeout(function (){
           parent.window.$("#modal input[type='search']").focus();
        }, 500);
        return;
      } 
    });   
  });  

  $("#bcariretur").click(function() {   
    if($(this).hasClass("disabled")) return;

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Vendor harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    } 
    if($('#coakredit').val()=='' || $('#coakredit').val()==null){
      $('#coakredit').attr('data-title','Akun Kas/Bank harus diisi !');
      $('#coakredit').tooltip('show');
      $('#coakredit').focus();
      return;
    } 

    let totalbaris = $(".idrtr").length;
    let stringrtr = "";
    for(let i=0;i<totalbaris;i++){
        if(i > 0) {
          stringrtr = stringrtr + ',' + `'${$("input[name^='noretur']").eq(i).val()}'`;
        } else {
          stringrtr = `'${$("input[name^='noretur']").eq(i).val()}'`;
        }
    }    

    $.ajax({ 
      "url"    : base_url+"Modal/cari_faktur", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');
        parent.window.$(".modal").modal("show");  
        parent.window.$(".modal-title").html("Cari Data Retur");
        parent.window.$("#modaltrigger").val("iframe-page-phb");   
        parent.window.$('#coltrigger').val('idretur');
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');
        console.log('error menampilkan modal cari retur pembelian...');
        return;
      },
      "success": function(result) {                             
        parent.window.$(".main-modal-body").html(result);   
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');        
        parent.window.$("#param1").val(stringrtr);                                        
        parent.window._transaksidatatable('view_cari_retur_pembelian',$('#idkontak').val());
        setTimeout(function (){
           parent.window.$("#modal input[type='search']").focus();
        }, 500);
        return;
      } 
    });   
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
        parent.window.$("#modaltrigger").val("iframe-page-phb");            
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result); 
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');             
        parent.window._transaksidatatable('view_pembayaran_hutang');
        setTimeout(function (){
           parent.window.$('#modal input').focus();
        }, 500);
        return;
      } 
    });   
  });

  $("#bpajak").click(function() {  
    if($(this).attr('role')) {    
      $.ajax({ 
        "url"    : base_url+"Modal/pph_pembelian", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Potongan Pajak");
          parent.window.$("#modaltrigger").val("iframe-page-phb");            
        },
        "error": function(){
          parent.window.$('.loader-wrap').addClass('d-none');
          console.log('error menampilkan modal potongan pph...');
          return;
        },
        "success": function(result) {
          let _pph = $("<option selected='selected'></option>").val($('#idpph').val()).text($('#namapph').val()),
              _stssetor = $("#statussetor").val();

          parent.window.$(".main-modal-body").html(result); 
          parent.window.$('#jmlbayar').val(Number($("#totalpembayaran").val().split('.').join('').toString().replace(',','.')));          
          parent.window.$('#jmlpph').val($("#totalpajak").val());                  
          parent.window.$('#nomorbupot').val($("#nobupot").val());                  
          if($('#namapph').val() != '') parent.window.$('#pajak').append(_pph).trigger('change');                          

          if(_stssetor == 1) parent.window.$('#chksetorsendiri').prop('checked', true);                  

          setTimeout(function (){
             parent.window.$('#pajak').focus();
          }, 500);
          parent.window.$('.loader-wrap').addClass('d-none');        
          return;
        } 
      });   
    }
  });

  $('#id').on('change',function(){
    const idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });  

  $('#triggerpph,#statussetor').on('change',function(){
    _hitungTotal();
  });  

  $('#notrans').on('change',function(){
    const idtrans = $(this).val();
    _formState2();
    _getDataTransaksiNomor(idtrans);
  });  

  $('#idfaktur').on('change',function(){
    const id = $(this).val();
    _getDataFaktur(id);
  });    

  $('#idretur').on('change',function(){
    const id = $(this).val();
    _getDataRetur(id);
  });    

  $("#badd").click(function() {
    _clearForm();
    _addRow('faktur');
    _addRow('retur');    
    _inputFormat();
    _formState1();
  });

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-phb/${$("#id").val()}`)    
  });

  $("#bedit").click(function() {
    if($('#id').val()=='') return;    
    _formState1();
  });

  $("#bcancel").click(function() {
    _clearForm();
    _addRow('faktur');
    _addRow('retur');
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

  $(this).on("keyup", "#totalrp,input[name^='jmlbayarinv'],input[name^='jmlbayarkasbank'],input[name^='jmlbayardiskon'],input[name^='jmlbayarretur'],input[name^='jmlbayarpph'],input[name^='jmlpotong']", async function(e){
      e.preventDefault();

      await _hitungTotal();

      if(Number($("#totalselisih").val().split('.').join('').toString().replace(',','.')) != 0) {
        $("#lebihbayar").removeClass("d-none");
      } else {
        $("#lebihbayar").addClass("d-none");      
      }
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
  }

  var _clearForm = () => {
    $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
    $(":checkbox").prop("checked", false); 
    $('.select2').val('').change();
    $('#tipebayar').val(0).trigger('change');     
    $('.total').val('0');
    $('#totalrp').val('0');    
    $('#namakontak').html('');       
    $('#tfaktur tbody').html('');
    $('#tretur tbody').html('');      
    $('#tgl').datepicker('setDate','dd-mm-yy'); 
    $('#tglcekgiro').datepicker();
    $('#uangkredit').val('Rp');
    $('#kurskredit').val('1');
    $('.nav-tabs a[href="#tab-faktur"]').tab('show');                                                                                  
    $("#lebihbayar").addClass("d-none");

    if($("#tipebayar").val() == 1) {
      $(".giro").removeClass('d-none');
      $(".transfer").addClass('d-none');      
    }
    if($("#tipebayar").val() == 2) {
      $(".giro").addClass('d-none');
      $(".transfer").removeClass('d-none');      
    }
    if($("#tipebayar").val() == 0) {
      $(".giro").addClass('d-none');
      $(".transfer").addClass('d-none');      
    }
  }

  var _formState1 = () => {
    $('#carikontak,#bpajak,#calcrp').attr('data-dismiss','modal');
    $('#carikontak,#bpajak,#calcrp').attr('data-toggle','modal');
    $('#carikontak,#bpajak,#calcrp').attr('role','button');      
    $('.btn-step2').addClass('disabled');
    $('.btn-step1').removeClass('disabled');
    $(":input").not(":button, :submit, :reset, :radio, .total").removeAttr('disabled'); 

    setTimeout(function () {
      $('#kontak').focus();        
    },300);

  }

  var _formState2 = () => {
    $('.btn-step2').removeClass('disabled');
    $('.btn-step1').addClass('disabled');
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");   
    $('#carikontak,#bpajak,#calcrp').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
  }

  var _addRow = (t1) => {
    if(t1=='faktur'){
      var newrow = " <tr>";
          newrow += "<td><input type=\"hidden\" name=\"tipeinv\" class=\"tipeinv\"><input type=\"hidden\" name=\"idinv\" class=\"idinv\"><input type=\"text\" name=\"nomorinv[]\" class=\"nomorinv form-control form-control-sm\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\" readonly></td>";
          newrow += "<td><input type=\"text\" name=\"tglinv[]\" class=\"tglinv form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-light border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"totalinv[]\" class=\"totalinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-light border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"terbayarinv[]\" class=\"terbayarinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
          newrow += "<td><input type=\"hidden\" name=\"tempbayarinv[]\" class=\"tempbayarinv\" value=\"0\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlbayarinv[]\" class=\"jmlbayarinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" data-trigger=\"manual\" data-placement=\"left\"></div></td>";       
          newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlbayarkasbank[]\" class=\"jmlbayarkasbank form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";                    
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlbayardiskon[]\" class=\"jmlbayardiskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlbayarretur[]\" class=\"jmlbayarretur form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";                   
          newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlbayarpph[]\" class=\"jmlbayarpph form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";                    
          newrow += "<td class=\"d-none\"><input type=\"text\" name=\"nobuktipotong[]\" class=\"nobuktipotong form-control form-control-sm\" autocomplete=\"off\"></td>";          
          newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbarisfaktur($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
          newrow += "</tr>";
      $('#tfaktur tbody').append(newrow);
    }
    if(t1=='retur'){
      var newrow = " <tr>";
          newrow += "<td><input type=\"hidden\" name=\"idrtr\" class=\"idrtr\"><input type=\"text\" name=\"noretur[]\" class=\"noretur form-control form-control-sm\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\" readonly></td>";
          newrow += "<td><input type=\"text\" name=\"tglretur[]\" class=\"tglretur form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-light border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"totalretur[]\" class=\"totalretur form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-light border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"terbayarr[]\" class=\"terbayarr form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
          newrow += "<td><input type=\"hidden\" name=\"bayartempretur[]\" class=\"bayartempretur\" value=\"0\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlpotong[]\" class=\"jmlpotong form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" data-trigger=\"manual\" data-placement=\"left\"></div></td>";
          newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbarisretur($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
          newrow += "</tr>";
      $('#tretur tbody').append(newrow);
    }
  }

/**/

/* CRUD
/* ========================================================================================== */

var _IsValid = () => {

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','vendor harus diisi !');
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
    if ($('#coakredit').val()=='' || $('#coakredit').val()==null){
      $('#coakredit').attr('data-title','Akun Kas/Bank harus diisi !');      
      $('#coakredit').tooltip('show');
      $('#coakredit').focus();
      return 0;
    }
    if ($('#tipebayar').val()=='' || $('#tipebayar').val()==null){
      $('#tipebayar').attr('data-title','Tipe pembayaran harus diisi !');      
      $('#tipebayar').tooltip('show');
      $('#tipebayar').focus();
      return 0;
    }

    const totalbaris = $(".idinv").length;
    for(let i=0;i<totalbaris;i++){
      let ttotalinv = Number($("input[name^='totalinv']").eq(i).val().split('.').join('').toString().replace(',','.'));      
      let ttotalterbayar = Number($("input[name^='terbayarinv']").eq(i).val().split('.').join('').toString().replace(',','.'));  
      let ttotalbayartemp = Number($("input[name^='tempbayarinv']").eq(i).val());                   
      let ttotalbayar = Number($("input[name^='jmlbayarinv']").eq(i).val().split('.').join('').toString().replace(',','.'));            
      let tsisabayar = ttotalinv - ttotalterbayar + ttotalbayartemp;

      if($("input[name^='nomorinv']").eq(i).val()==''){
        $('.nav-tabs a[href="#tab-faktur"]').tab('show');        
        setTimeout(function(){
          $("input[name^='nomorinv']").eq(i).attr('data-title','Data tagihan masih kosong !');      
          $("input[name^='nomorinv']").eq(i).tooltip('show');                         
          $("input[name^='nomorinv']").eq(i).focus();       
        },300);
        return 0;
      }
      if(ttotalbayar > tsisabayar){
        $('.nav-tabs a[href="#tab-faktur"]').tab('show');                
        setTimeout(function(){
          $("input[name^='jmlbayarinv']").eq(i).attr('data-title','Hutang dibayar melebihi total tagihan !');      
          $("input[name^='jmlbayarinv']").eq(i).tooltip('show');
          $("input[name^='jmlbayarinv']").eq(i).focus();        
        },300);
        return 0;
      }
    }

    const totalbaris2 = $(".idrtr").length;
    for(let j=0;j<totalbaris2;j++){
      let tretur = Number($("input[name^='totalretur']").eq(j).val().split('.').join('').toString().replace(',','.'));      
      let treturterbayar = Number($("input[name^='terbayarr']").eq(j).val().split('.').join('').toString().replace(',','.'));  
      let treturbayartemp = Number($("input[name^='bayartempretur']").eq(j).val());                   
      let treturbayar = Number($("input[name^='jmlpotong']").eq(j).val().split('.').join('').toString().replace(',','.'));            
      let tsisaretur = tretur - treturterbayar + treturbayartemp;

      if($("input[name^='noretur']").eq(j).val()=='' && $("input[name^='jmlpotong']").eq(j).val() !== '0,00'){
        $('.nav-tabs a[href="#tab-retur"]').tab('show'); 
        setTimeout(function(){
          $("input[name^='noretur']").eq(j).attr('data-title','Data retur harus dipilih !');      
          $("input[name^='noretur']").eq(j).tooltip('show');
          $("input[name^='noretur']").eq(j).focus();       
        },300);               
        return 0;
      }
      if(treturbayar > tsisaretur){
        $('.nav-tabs a[href="#tab-retur"]').tab('show');                
        setTimeout(function(){
          $("input[name^='jmlpotong']").eq(j).attr('data-title','Nilai potong melebihi total retur !');      
          $("input[name^='jmlpotong']").eq(j).tooltip('show');
          $("input[name^='jmlpotong']").eq(j).focus();        
        },300);
        return 0;
      }      
    }    

    if ($('#totalrp').val()=='0,00' || $('#totalrp').val()==''){
      $('#totalrp').attr('data-title','Jumlah Kas Bank tidak boleh 0 !');      
      $('#totalrp').tooltip('show');
      $('#totalrp').focus();      
      return 0;
    }

    let tselisih = Number($("#totalselisih").val().split('.').join('').toString().replace(',','.'));
    if(tselisih != 0) {
      if($("#coaselisih").val() == null || $("#coaselisih").val() == '') {
          parent.window.Swal.fire({
            title: 'Pesan : Ada selisih pembayaran, akun selisih harus diisi !',
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Oke`,
          }).then((result) => {
            if (result.isConfirmed) {
              $("#lebihbayar").removeClass("d-none");
              setTimeout(function(){
                $("#coaselisih").focus();
              },300);
            }
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
    "url"    : base_url+"PB_Pembayaran_Hutang/deletedata", 
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
        _addRow('faktur');
        _addRow('retur');        
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

  const id = $("#id").val(),
        tgl = $("#tgl").val(),
        nomor = $("#nomor").val(),
        kontak = $("#idkontak").val(),
        uraian = $("#uraian").val(),
        coacr = $("#coakredit").val(),
        uangcr = $("#iduangkredit").val(),
        kurscr = Number($("#kurskredit").val().split('.').join('').toString().replace(',','.')),
        tipebayar = $("#tipebayar").val(),
        nomorgiro = $("#nocekgiro").val(),
        tglgiro = $("#tglcekgiro").val(), 
        bank = $("#bank").val(),
        nomorbupot = $("#nobupot").val(),
        statussetor = $("#statussetor").val(),
        pph = $("#idpph").val(),
        coaselisih = $("#coaselisih").val(),  
        status = $("#status").val();      

  var detil = [],
      detilretur = [];

  $("input[name^='idinv']").each(function(index,element){  
    let jmlkasbank = Number($("input[name^='jmlbayarkasbank']").eq(index).val().split('.').join('').toString().replace(',','.'));    
    let jmldiskon = Number($("input[name^='jmlbayardiskon']").eq(index).val().split('.').join('').toString().replace(',','.'));
    let jmlretur = Number($("input[name^='jmlbayarretur']").eq(index).val().split('.').join('').toString().replace(',','.'));    
    let jmlbayar = Number($("input[name^='jmlbayarinv']").eq(index).val().split('.').join('').toString().replace(',','.'));

    detil.push({
             idinvoice:this.value,
             tipeinv:$("input[name^='tipeinv']").eq(index).val(),
             jmlbayar:jmlbayar,
             jmlkasbank:jmlkasbank,             
             jmldiskon:jmldiskon,
             jmlretur:jmlretur
           });
  });

  $("input[name^='idrtr']").each(function(index,element){  
    detilretur.push({
             idretur:this.value,
             jmlbayar:Number($("input[name^='jmlpotong']").eq(index).val().split('.').join('').toString().replace(',','.'))
           });

  });   

  detil = JSON.stringify(detil);
  detilretur = JSON.stringify(detilretur);    

  //Total Transaksi
  const totalkas = Number($("#totalrp").val().split('.').join('').toString().replace(',','.'));
  const totaldiskon = Number($("#totaldiskon").val().split('.').join('').toString().replace(',','.'));  
  const totalretur = Number($("#totalretur").val().split('.').join('').toString().replace(',','.'));
  const totalpajak = Number($("#totalpajak").val().split('.').join('').toString().replace(',','.'));
  const totalbayar = Number($("#totalpembayaran").val().split('.').join('').toString().replace(',','.'));
  const totalselisih = Number($("#totalselisih").val().split('.').join('').toString().replace(',','.'));  

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('uraian',uraian);
  rey.set('coacr',coacr);
  rey.set('uangcr',uangcr);
  rey.set('kurscr',kurscr);
  rey.set('tipebayar',tipebayar); 
  rey.set('nomorgiro',nomorgiro);
  rey.set('tglgiro',tglgiro);
  rey.set('bank',bank);         
  rey.set('nomorbupot',nomorbupot);       
  rey.set('pph',pph);             
  rey.set('statussetor',statussetor);        
  rey.set('status',status);      
  rey.set('totalkas',totalkas);
  rey.set('totaldiskon',totaldiskon);        
  rey.set('totalretur',totalretur);
  rey.set('totalpajak',totalpajak);
  rey.set('totalbayar',totalbayar);              
  rey.set('totalselisih',totalselisih);                
  rey.set('coaselisih',coaselisih);                
  rey.set('detil',detil);
  rey.set('detilretur',detilretur);  

  $.ajax({ 
  "url"    : base_url+"PB_Pembayaran_Hutang/savedata", 
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
                window.open(`${base_url}Laporan/preview/page-phb/${result.nomor}`)
              }
              parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
              _clearForm();
              _addRow('faktur');
              _addRow('retur');        
              _inputFormat();
              _formState1();
              return;
          })
      }                  
  } 
  });
}

var _tampilData = (result) => {
    if (typeof result.pesan !== 'undefined') {
      parent.window.toastr.error(result.pesan);
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    } else {
      
      const coacr = $("<option selected='selected'></option>").val(result.data[0]['coacrid']).text(result.data[0]['coacr']);            
      const coaselisih = $("<option selected='selected'></option>").val(result.data[0]['coaselisihid']).text(result.data[0]['coaselisih']);                  
      const _bank = $("<option selected='selected'></option>").val(result.data[0]['idbank']).text(result.data[0]['bank']);            

      var rows = 0,
          rows2 = 0;          

      /*Atur Baris Detil Transaksi Faktur*/
      $('#tfaktur tbody').html('');
      for (let i = 0; i < result.data.length; i++) {
        _addRow('faktur');
      }
      /**/
      /*Atur Baris Detil Transaksi Retur*/
      $('#tretur tbody').html('');
      for (let j = 0; j < result.data2.length; j++) {
        _addRow('retur');
      }
      /**/          

      _inputFormat();            

      $('#id').val(result.data[0]['id']);            
      $('#nomor').val(result.data[0]['nomor']);
      $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
      $('#uraian').val(result.data[0]['uraian']);            
      $('#idkontak').val(result.data[0]['kontakid']);
      $('#kontak').val(result.data[0]['kontakkode']);
      $('#namakontak').html(result.data[0]['kontak']);                        
      $('#coakredit').append(coacr).trigger('change');
      if(result.data[0]['bank'] != null) $('#bank').append(_bank).trigger('change');
      if(result.data[0]['coaselisih'] != null) $('#coaselisih').append(coaselisih).trigger('change');            
      $('#tipebayar').val(result.data[0]['tipebayar']).change();
      $('#totalrp').val(result.data[0]['total'].replace(".", ",")); 
      $('#totalpajak').val(result.data[0]['totalpajak'].replace(".", ",")); 
      $('#totalretur').val(result.data[0]['totalretur'].replace(".", ","));  
      $('#totalpembayaran').val(result.data[0]['totalpembayaran'].replace(".", ","));  
      $('#totaldiskon').val(result.data[0]['totaldiskon'].replace(".", ","));                   
      $('#idpph').val(result.data[0]['idpph']);
      $('#namapph').val(result.data[0]['namapajak']);      
      $('#nobupot').val(result.data[0]['nomorbupot']); 
      $('#statussetor').val(result.data[0]['statussetor']);                         
      $('#status').val(result.data[0]['status']);                        

      $.each(result.data, function() {
        $("input[name^='tipeinv']").eq(rows).val(result.data[rows]['tipeinv']);                                                                                  
        $("input[name^='idinv']").eq(rows).val(result.data[rows]['idinv']);                                                                      
        $("input[name^='nomorinv']").eq(rows).val(result.data[rows]['nomorinv']);
        $("input[name^='tglinv']").eq(rows).val(result.data[rows]['tglinv']);
        $("input[name^='totalinv']").eq(rows).val(result.data[rows]['totaltrans'].replace(".", ","));
        $("input[name^='terbayarinv']").eq(rows).val(result.data[rows]['terbayar'].replace(".", ","));
        $("input[name^='jmlbayarinv']").eq(rows).val(result.data[rows]['totalbayar'].replace(".", ","));  
        $("input[name^='tempbayarinv']").eq(rows).val(result.data[rows]['totalbayar'].replace(".", ","));            
        $("input[name^='jmlbayardiskon']").eq(rows).val(result.data[rows]['jmldiskon'].replace(".", ","));                  
        $("input[name^='jmlbayarkasbank']").eq(rows).val(result.data[rows]['jmlkasbank'].replace(".", ","));                          
        $("input[name^='jmlbayarretur']").eq(rows).val(result.data[rows]['jmlretur'].replace(".", ","));                                  

        if(result.data[rows]['totaltrans']==0) $("input[name^='totalinv']").eq(rows).attr('placeholder','0,00');            
        if(result.data[rows]['terbayar']==0) $("input[name^='terbayarinv']").eq(rows).attr('placeholder','0,00');            
        if(result.data[rows]['totalbayar']==0) $("input[name^='jmlbayarinv']").eq(rows).attr('placeholder','0,00');          
        if(result.data[rows]['jmldiskon']==0) $("input[name^='jmlbayardiskon']").eq(rows).attr('placeholder','0,00');                    
        if(result.data[rows]['jmlkasbank']==0) $("input[name^='jmlbayarkasbank']").eq(rows).attr('placeholder','0,00');                            
        if(result.data[rows]['jmlretur']==0) $("input[name^='jmlbayarretur']").eq(rows).attr('placeholder','0,00');                    

        rows++;
      });

      $.each(result.data2, function() {
        $("input[name^='idrtr']").eq(rows2).val(result.data2[rows2]['idretur']);                                                                      
        $("input[name^='noretur']").eq(rows2).val(result.data2[rows2]['noretur']);
        $("input[name^='tglretur']").eq(rows2).val(result.data2[rows2]['tglretur']);
        $("input[name^='totalretur']").eq(rows2).val(result.data2[rows2]['totaltransr'].replace(".", ","));
        $("input[name^='terbayarr']").eq(rows2).val(result.data2[rows2]['terbayarr'].replace(".", ","));
        $("input[name^='jmlpotong']").eq(rows2).val(result.data2[rows2]['totalpotong'].replace(".", ","));          
        $("input[name^='bayartempretur']").eq(rows2).val(result.data2[rows2]['totalpotong'].replace(".", ","));                  

        if(result.data2[rows2]['totaltransr']==0) $("input[name^='totalretur']").eq(rows2).attr('placeholder','0,00');            
        if(result.data2[rows2]['terbayarr']==0) $("input[name^='terbayarr']").eq(rows2).attr('placeholder','0,00');            
        if(result.data2[rows2]['totalpotong']==0) $("input[name^='jmlpotong']").eq(rows2).attr('placeholder','0,00');            

        rows2++;
      });

      if($("#tipebayar").val() == 1) {
        $(".giro").removeClass('d-none');
        $(".transfer").addClass('d-none');      
      }
      if($("#tipebayar").val() == 2) {
        $(".giro").addClass('d-none');
        $(".transfer").removeClass('d-none');      
      }
      if($("#tipebayar").val() == 0) {
        $(".giro").addClass('d-none');
        $(".transfer").addClass('d-none');      
      }

      $('.nav-tabs a[href="#tab-faktur"]').tab('show');
      if($('.btn-step1').hasClass('disabled')){
        $('.btn-delrow').addClass('disabled');
        $(":input").not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
        $(":input").not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff");
      }   

      _hitungTotal();                                                                                 

      if(Number($("#totalselisih").val().split('.').join('').toString().replace(',','.')) != 0){
        $("#lebihbayar").removeClass("d-none");
      } else {
        $("#lebihbayar").addClass("d-none");        
      }

      parent.window.$('.loader-wrap').addClass('d-none');                                       
      return;
    }
}

var _getDataTransaksi = (id) => {
    if(id=='' || id==null) return;    

    if($("#notrans").val()!='') return;

    $.ajax({ 
      "url"    : base_url+"PB_Pembayaran_Hutang/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(){
        parent.window.toastr.error('Error : Gagal mengambil data transaksi pembayaran piutang !');
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
      "url"    : base_url+"PB_Pembayaran_Hutang/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "nomor="+nomor,
      "cache"  : false,
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(xhr,status,error){
        parent.window.toastr.error('Error : Gagal mengambil data transaksi pembayaran piutang !');
        console.log(xhr.responseText);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        _tampilData(result);
      } 
  })
}

var _getDataFaktur = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PB_Pembayaran_Hutang/getdatafaktur",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      alert('Error : Gagal mengambil data faktur pembelian !');
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
        var rows = $(".idinv").length;         

        if(rows==1 && $("input[name^='idinv']").eq(0).val()==''){
          $('#tfaktur tbody').html('');
          rows = 0;
        }

        $.each(result.data, function() {
          _addRow('faktur');
          _inputFormat();

          $("input[name^='tipeinv']").eq(rows).val(result.data[datarows]['tipe']);                                                                      
          $("input[name^='idinv']").eq(rows).val(result.data[datarows]['id']);                                                                      
          $("input[name^='nomorinv']").eq(rows).val(result.data[datarows]['nomor']);
          $("input[name^='tglinv']").eq(rows).val(result.data[datarows]['tgl']);
          $("input[name^='totalinv']").eq(rows).val(result.data[datarows]['totaltrans'].replace(".", ","));
          $("input[name^='terbayarinv']").eq(rows).val(result.data[datarows]['terbayar'].replace(".", ","));
          $("input[name^='jmlbayarinv']").eq(rows).val(result.data[datarows]['totalbayar'].replace(".", ","));          

          if(result.data[datarows]['totaltrans']==0) $("input[name^='totalinv']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['terbayar']==0) $("input[name^='terbayarinv']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['totalbayar']==0) $("input[name^='jmlbayarinv']").eq(rows).attr('placeholder','0,00');            

          datarows++;
          rows++;
        });

        _hitungTotal();
        if(Number($("#totalselisih").val().split('.').join('').toString().replace(',','.')) != 0){
          $("#lebihbayar").removeClass("d-none");
        } else {
          $("#lebihbayar").addClass("d-none");        
        }

        parent.window.$('.loader-wrap').addClass('d-none');   
        return;                       
      }
    }
  });
}

var _getDataRetur = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PB_Pembayaran_Hutang/getdataretur",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      alert('Error : Gagal mengambil data retur pembelian !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : async function(result){

      if (typeof result.pesan !== 'undefined') { 
        alert(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else { 
        let datarows = 0;
        let rows = $(".idrtr").length;         

        if(rows==1){
          if($("input[name^='idrtr']").eq(0).val()=='' || $("input[name^='idrtr']").eq(0).val()==0){
            $('#tretur tbody').html('');
            rows = 0;
          }
        }

        $.each(result.data, function() {
          _addRow('retur');
          _inputFormat();

          $("input[name^='idrtr']").eq(rows).val(result.data[datarows]['id']);                                                                      
          $("input[name^='noretur']").eq(rows).val(result.data[datarows]['nomor']);
          $("input[name^='tglretur']").eq(rows).val(result.data[datarows]['tgl']);
          $("input[name^='totalretur']").eq(rows).val(result.data[datarows]['totaltrans'].replace(".", ","));
          $("input[name^='terbayarr']").eq(rows).val(result.data[datarows]['terbayar'].replace(".", ","));
          $("input[name^='jmlpotong']").eq(rows).val(result.data[datarows]['totalpotong'].replace(".", ","));          

          if(result.data[datarows]['totaltrans']==0) $("input[name^='totalretur']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['terbayar']==0) $("input[name^='terbayarr']").eq(rows).attr('placeholder','0,00');            
          if(result.data[datarows]['totalpotong']==0) $("input[name^='jmlpotong']").eq(rows).attr('placeholder','0,00');            

          datarows++;
          rows++;
        });

        _hitungTotal();
        if(Number($("#totalselisih").val().split('.').join('').toString().replace(',','.')) != 0){
          $("#lebihbayar").removeClass("d-none");
        } else {
          $("#lebihbayar").addClass("d-none");        
        }
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
      _addRow('faktur');
      _addRow('retur');
      _inputFormat();
      _formState1();  
  }

})

var _hitungkasbank = function() {
  let tpph = Number($("#totalpajak").val().split('.').join('').toString().replace(',','.'));
  let tdiskon = Number($("#totaldiskon").val().split('.').join('').toString().replace(',','.'));  
  let tretur = Number($("#totalretur").val().split('.').join('').toString().replace(',','.'));    
  let tbayar = Number($("#totalpembayaran").val().split('.').join('').toString().replace(',','.'));      

  if(tbayar - tpph - tdiskon - tretur == 0) {
    $("#totalrp").val(0).attr('placeholder','0,00');
  } else {
    $("#totalrp").val((tbayar-tpph-tdiskon-tretur).toString().replace('.',','));    
  }

  _hitungTotal();
}

var _hitungTotal = function () {
  let tterbayar = 0;
  let ttagihan = 0;  
  let ttotal = 0;
  let tretur = 0;
  let tdiskon = 0;    
  let tpph = 0;      
  let tkasbank = 0;
  let tselisih = 0;
  let stspph = $("#statussetor").val();    

  $('.idinv').each(function(index,element) {
    ttagihan += Number($("input[name^='totalinv']").eq(index).val().split('.').join('').toString().replace(',','.'));   
    tterbayar += Number($("input[name^='terbayarinv']").eq(index).val().split('.').join('').toString().replace(',','.'));       
    ttotal += Number($("input[name^='jmlbayarinv']").eq(index).val().split('.').join('').toString().replace(',','.'));   
    tdiskon += Number($("input[name^='jmlbayardiskon']").eq(index).val().split('.').join('').toString().replace(',','.'));         
    tretur += Number($("input[name^='jmlbayarretur']").eq(index).val().split('.').join('').toString().replace(',','.'));
  });  

  $('.idrtr').each(function(index,element) {
    tretur += Number($("input[name^='jmlpotong']").eq(index).val().split('.').join('').toString().replace(',','.'));
  });

  tpph = Number($("#totalpajak").val().split('.').join('').toString().replace(',','.'));
  tkasbank = Number($("#totalrp").val().split('.').join('').toString().replace(',','.'));
  tselisih = Number(tkasbank+tdiskon+tretur+tpph-ttotal);

  ttotal = ttotal.toString().replace('.',',');            
  tretur = tretur.toString().replace('.',',');            
  tdiskon = tdiskon.toString().replace('.',',');            
  tkasbank = tkasbank.toString().replace('.',',');
  tselisih = tselisih.toString().replace('.',',');                  

  if(ttotal==0) ttotal='0,00';
  if(tretur==0) tretur='0,00';
  if(tdiskon==0) tdiskon='0,00';
  if(tkasbank==0) tkasbank='0,00';  
  if(tselisih==0) tselisih='0,00';    

  $('#ttagihan').val(ttagihan);
  $('#tterbayar').val(tterbayar);  
  $('#totalretur').val(tretur).attr('placeholder',tretur);
  $('#totaldiskon').val(tdiskon).attr('placeholder',tdiskon);
  $('#totalselisih').val(tselisih).attr('placeholder',tselisih);      
  $('#totalpembayaran').val(ttotal).attr('placeholder',ttotal);
}

window._hapusbarisfaktur = (obj) => {
  if($(obj).hasClass('disabled')) return;

  $(obj).parent().parent().remove();
  _hitungTotal();
}

window._hapusbarisretur = (obj) => {
  if($(obj).hasClass('disabled')) return;

  $(obj).parent().parent().remove();
  _hitungTotal();
}