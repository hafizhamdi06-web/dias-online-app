/* ========================================================================================== */
/* File Name : pembayaran-piutang.js
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

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#tipebayar');
  Component_Select2_Account('#coadebet',`${base_url}Select_Master/view_coa_kasbank`,'form_coa','Akun');   

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

  $(this).on('select2:open', function(){
    this.querySelector('.select2-search__field').focus();
  });

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/ppjData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-ppj");
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
          parent.window.$("#modaltrigger").val("iframe-page-ppj");
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

  $("#bcarifaktur").click(function() {   
  	if($(this).hasClass("disabled")) return;

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Pelanggan harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    } 
    if($('#coadebet').val()=='' || $('#coadebet').val()==null){
      $('#coadebet').attr('data-title','Akun Kas/Bank harus diisi !');
      $('#coadebet').tooltip('show');
      $('#coadebet').focus();
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
    		parent.window.$("#modaltrigger").val("iframe-page-ppj");   
    		parent.window.$('#coltrigger').val('idfaktur');
  	  },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');
        console.log('error menampilkan modal cari faktur penjualan...');
        return;
      },
      "success": function(result) {                             
        parent.window.$(".main-modal-body").html(result);      
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');        
        parent.window.$("#param1").val(stringinv);                             
        parent.window._transaksidatatable('view_cari_faktur_penjualan_dp',$('#idkontak').val());
//        parent.window._transaksidatatable('view_cari_faktur_penjualan',$('#idkontak').val());
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
      $('#kontak').attr('data-title','Pelanggan harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return;
    } 
    if($('#coadebet').val()=='' || $('#coadebet').val()==null){
      $('#coadebet').attr('data-title','Akun Kas/Bank harus diisi !');
      $('#coadebet').tooltip('show');
      $('#coadebet').focus();
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
        parent.window.$("#modaltrigger").val("iframe-page-ppj");   
        parent.window.$('#coltrigger').val('idretur');
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');
        console.log('error menampilkan modal cari retur penjualan...');
        return;
      },
      "success": function(result) {                             
        parent.window.$(".main-modal-body").html(result);     
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');         
        parent.window.$("#param1").val(stringrtr);                                        
        parent.window._transaksidatatable('view_cari_retur_penjualan',$('#idkontak').val());
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
    		parent.window.$("#modaltrigger").val("iframe-page-ppj");            
      },
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);     
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');         
        parent.window._transaksidatatable('view_pembayaran_piutang');
    		setTimeout(function (){
    		   parent.window.$('#modal input').focus();
    		}, 500);
        return;
      } 
    });   
  });

  $('#id').on('change',function(){
    const idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
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

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-ppj/${$("#id").val()}`)    
  });

  $(this).on("keyup", "input[name^='jmlbayarinv']", function(e){
      _hitungTotal();
      return;
  });

  $(this).on("keyup", "input[name^='jmlpotong']", function(e){
      _hitungTotal();
      return;
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
    $('#tipebayar').val(0).change();     
    $('#namakontak').html('');       
    $('#tfaktur tbody').html('');
    $('#tretur tbody').html('');      
    $('#tgl').datepicker('setDate','dd-mm-yy'); 
    $('#uangdebet').val('Rp');
    $('#kursdebet').val('1');
    $('#totalrp').val('0');
    $('.nav-tabs a[href="#tab-faktur"]').tab('show');                                                                              
  }

  var _formState1 = () => {
    $('#carikontak').attr('data-dismiss','modal');
    $('#carikontak').attr('data-toggle','modal');
    $('#carikontak').attr('role','button');  
    $('.btn-step2').addClass('disabled');
    $('.btn-step1').removeClass('disabled');
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").removeAttr('disabled'); 
    setTimeout(function () {
      $('#kontak').focus();        
    },300);
  }

  var _formState2 = () => {
    $('.btn-step2').removeClass('disabled');
    $('.btn-step1').addClass('disabled');
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
    $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");   
    $('#carikontak').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
  }

  var _addRow = (t1) => {
  	if(t1=='faktur'){
	    let newrow = " <tr>";
	        newrow += "<td><input type=\"hidden\" name=\"tipeinv\" class=\"tipeinv\"><input type=\"hidden\" name=\"idinv\" class=\"idinv\"><input type=\"text\" name=\"nomorinv[]\" class=\"nomorinv form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
	        newrow += "<td><input type=\"text\" name=\"tglinv[]\" class=\"tglinv form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
      		newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"totalinv[]\" class=\"totalinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
      		newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"terbayarinv[]\" class=\"terbayarinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
      		newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlbayarinv[]\" class=\"jmlbayarinv form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
	        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbarisfaktur($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
	        newrow += "</tr>";
	    $('#tfaktur tbody').append(newrow);
  	}
  	if(t1=='retur'){
      let newrow = " <tr>";
          newrow += "<td><input type=\"hidden\" name=\"idrtr\" class=\"idrtr\"><input type=\"text\" name=\"noretur[]\" class=\"noretur form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
          newrow += "<td><input type=\"text\" name=\"tglretur[]\" class=\"tglretur form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"totalretur[]\" class=\"totalretur form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"terbayarr[]\" class=\"terbayarr form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
          newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"jmlpotong[]\" class=\"jmlpotong form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
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
    if ($('#coadebet').val()=='' || $('#coadebet').val()==null){
      $('#coadebet').attr('data-title','Akun Kas/Bank harus diisi !');      
      $('#coadebet').tooltip('show');
      $('#coadebet').focus();
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
      if($("input[name^='nomorinv']").eq(i).val()==''){
    		$('#bcarifaktur').attr('data-title','Data tagihan harus diisi !');      
    		$('#bcarifaktur').tooltip('show');
    		$('#bcarifaktur').focus();      	
        return 0;
      }
    }

    const totalbaris2 = $(".idrtr").length;
    for(let j=0;j<totalbaris2;j++){
      if($("input[name^='noretur']").eq(j).val()=='' && $("input[name^='jmlpotong']").eq(j).val() !== '0,00'){
        $('.nav-tabs a[href="#tab-retur"]').tab('show'); 
        setTimeout(function(){
          $('#bcariretur').attr('data-title','Data retur harus diisi !');      
          $('#bcariretur').tooltip('show');
          $('#bcariretur').focus();       
        },300);               
        return 0;
      }
    }

    if ($('#totalrp').val()=='0,00' || $('#totalrp').val()==''){
      $('#totalrp').attr('data-title','Total pembayaran tidak boleh 0 !');      
      $('#totalrp').tooltip('show');
      return 0;
    }

    return 1;
}

var _deleteData = () => {

  const id = $("#id").val();
  const nomor = $("#nomor").val();  

  $.ajax({ 
    "url"    : base_url+"PJ_Pembayaran_Piutang/deletedata", 
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

  let id = $("#id").val(),
      tgl = $("#tgl").val(),
      nomor = $("#nomor").val(),
      kontak = $("#idkontak").val(),
      uraian = $("#uraian").val(),
      coadb = $("#coadebet").val(),
      uangdb = $("#iduangdebet").val(),
      kursdb = Number($("#kursdebet").val().split('.').join('').toString().replace(',','.')),
      tipebayar = $("#tipebayar").val(),
      status = $("#status").val(),
      detil = [],
      detilretur = [];      

	$("input[name^='idinv']").each(function(index,element){  
	  detil.push({
	           idinvoice:this.value,
             tipeinv:$("input[name^='tipeinv']").eq(index).val(),
	           jmlbayar:Number($("input[name^='jmlbayarinv']").eq(index).val().split('.').join('').toString().replace(',','.'))
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

	const totalRp = Number($("#totalrp").val().split('.').join('').toString().replace(',','.'));
	var rey = new FormData();  

	rey.set('id',id);
	rey.set('tgl',tgl);
	rey.set('nomor',nomor);
	rey.set('kontak',kontak);
	rey.set('uraian',uraian);
	rey.set('coadb',coadb);
	rey.set('uangdb',uangdb);
	rey.set('kursdb',kursdb);
	rey.set('tipebayar',tipebayar); 
	rey.set('status',status); 	   
	rey.set('totalRp',totalRp);      
	rey.set('detil',detil);
  rey.set('detilretur',detilretur);  

	$.ajax({ 
  	"url"    : base_url+"PJ_Pembayaran_Piutang/savedata", 
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
                window.open(`${base_url}Laporan/preview/page-ppj/${result.nomor}`)
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
	})
}


var _tampilData = (result) => {
        if (typeof result.pesan !== 'undefined') {
          alert(result.pesan);
          parent.window.$('.loader-wrap').addClass('d-none');                  
          return;
        } else {
          
          const coaDb = $("<option selected='selected'></option>").val(result.data[0]['coadbid']).text(result.data[0]['coadb']);            

          var rows = 0,
              rows2 = 0;          

          $('#tfaktur tbody').html('');
          for (let i = 0; i < result.data.length; i++) {
            _addRow('faktur');
          }

          $('#tretur tbody').html('');
          for (let j = 0; j < result.data2.length; j++) {
            _addRow('retur');
          }

          _inputFormat();            

          $('#id').val(result.data[0]['id']);            
          $('#nomor').val(result.data[0]['nomor']);
          $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
          $('#uraian').val(result.data[0]['uraian']);            
          $('#idkontak').val(result.data[0]['kontakid']);
          $('#kontak').val(result.data[0]['kontakkode']);
          $('#namakontak').html(result.data[0]['kontak']);                        
          $('#iduangdebet').val(result.data[0]['iduangdebet']);                        
          $('#uangdebet').val(result.data[0]['uangdebet']);                        
          $('#kursdebet').val(result.data[0]['kursdebet'].replace(".", ","));                        
          $('#coadebet').append(coaDb).trigger('change');
          $('#totalrp').val(result.data[0]['total'].replace(".", ","));                        
          $('#tipebayar').val(result.data[0]['tipebayar']).change();
          $('#status').val(result.data[0]['status']);                        

          /* Isi Detil Transaksi Faktur */
          $.each(result.data, function() {
            $("input[name^='tipeinv']").eq(rows).val(result.data[rows]['tipeinv']);                                                                                  
            $("input[name^='idinv']").eq(rows).val(result.data[rows]['idinv']);                                                                      
            $("input[name^='nomorinv']").eq(rows).val(result.data[rows]['nomorinv']);
            $("input[name^='tglinv']").eq(rows).val(result.data[rows]['tglinv']);
            $("input[name^='totalinv']").eq(rows).val(result.data[rows]['totaltrans'].replace(".", ","));
            $("input[name^='terbayarinv']").eq(rows).val(result.data[rows]['terbayar'].replace(".", ","));
            $("input[name^='jmlbayarinv']").eq(rows).val(result.data[rows]['totalbayar'].replace(".", ","));          

            if(result.data[rows]['totaltrans']==0) $("input[name^='totalinv']").eq(rows).attr('placeholder','0,00');            
            if(result.data[rows]['terbayar']==0) $("input[name^='terbayarinv']").eq(rows).attr('placeholder','0,00');            
            if(result.data[rows]['totalbayar']==0) $("input[name^='jmlbayarinv']").eq(rows).attr('placeholder','0,00');            

            rows++;
          });
          /**/

          /* Isi Detil Transaksi Retur */
          $.each(result.data2, function() {
            $("input[name^='idrtr']").eq(rows2).val(result.data2[rows2]['idretur']);                                                                      
            $("input[name^='noretur']").eq(rows2).val(result.data2[rows2]['noretur']);
            $("input[name^='tglretur']").eq(rows2).val(result.data2[rows2]['tglretur']);
            $("input[name^='totalretur']").eq(rows2).val(result.data2[rows2]['totaltransr'].replace(".", ","));
            $("input[name^='terbayarr']").eq(rows2).val(result.data2[rows2]['terbayarr'].replace(".", ","));
            $("input[name^='jmlpotong']").eq(rows2).val(result.data2[rows2]['totalpotong'].replace(".", ","));          

            if(result.data2[rows2]['totaltransr']==0) $("input[name^='totalretur']").eq(rows2).attr('placeholder','0,00');            
            if(result.data2[rows2]['terbayarr']==0) $("input[name^='terbayarr']").eq(rows2).attr('placeholder','0,00');            
            if(result.data2[rows2]['totalpotong']==0) $("input[name^='jmlpotong']").eq(rows2).attr('placeholder','0,00');            

            rows2++;
          });

          $('.nav-tabs a[href="#tab-faktur"]').tab('show'); 
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
      "url"    : base_url+"PJ_Pembayaran_Piutang/getdata",       
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
      "url"    : base_url+"PJ_Pembayaran_Piutang/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "nomor="+nomor,
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

var _getDataFaktur = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PJ_Pembayaran_Piutang/getdatafaktur",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      alert('Error : Gagal mengambil data faktur penjualan !');
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

        parent.window.$('.loader-wrap').addClass('d-none');   
        return;                       
      }
    }
  })
}

var _getDataRetur = (id) => {
  if(id=='' || id==null) return;  

  $.ajax({ 
    "url"    : base_url+"PJ_Pembayaran_Piutang/getdataretur",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id.toString(),
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(xhr){
      console.log(xhr.responseText);
      alert('Error : Gagal mengambil data retur penjualan !');
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

        if(rows==1 && $("input[name^='idrtr']").eq(0).val()==''){
          $('#tretur tbody').html('');
          rows = 0;
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

var _hitungTotal = () => {
  let ttotal = 0;
  
  $('.idinv').each(function(index,element) {
    ttotal += Number($("input[name^='jmlbayarinv']").eq(index).val().split('.').join('').toString().replace(',','.'));     
  });  

  $('.idrtr').each(function(index,element) {
    ttotal -= Number($("input[name^='jmlpotong']").eq(index).val().split('.').join('').toString().replace(',','.'));     
  });

  ttotal = ttotal.toString().replace('.',',');            

  if(ttotal==0) ttotal='0,00';

  $('#totalrp').val(ttotal).attr('placeholder',ttotal);      
  return;

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