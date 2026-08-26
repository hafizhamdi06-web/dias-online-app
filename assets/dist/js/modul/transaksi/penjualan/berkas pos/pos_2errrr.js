/* ========================================================================================== */
/* File Name : order-penjualan.js
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
  setupHiddenInputChangeListener($('#idkontak')[0]);
  setupHiddenInputChangeListener($('#idpaket')[0]); 
  setupHiddenInputChangeListener($('#idpromo')[0]);  
  setupHiddenInputChangeListener($('#medid')[0]);
  setupHiddenInputChangeListener($('#webid')[0]); 
  setupHiddenInputChangeListener($('#webidbelumiv')[0]); 
  setupHiddenInputChangeListener($('#txtidbarang')[0]);  
  setupHiddenInputChangeListener($('#statuspaket')[0]);   
  setupHiddenInputChangeListener($('#iddiskonvocer')[0]);  
  
  
  
  


  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#pajak'); 
  Component_Select2('#termin',`${base_url}Select_Master/view_termin`,'form_termin','Termin'); 
  Component_Select2('.karyawan',`${base_url}Select_Master/view_karyawan`);
  Component_Select2('.klinikluar',`${base_url}Select_Master/view_klinikluar`); 
  Component_Select2('.teman',`${base_url}Select_Master/view_pasien`); 

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

  if($('#nilaipph22').val() != '') {
    $('#col-pph22').removeClass('d-none');
    $('#col-clear').removeClass('col-sm-8');    
    $('#col-clear').addClass('col-sm-6');        
  }

/**/

/* ========================================================================================== */

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });
  
  
    document.addEventListener('contextmenu', event => event.preventDefault());

    document.onkeydown = function (e) {
        
       // alert(e.keyCode);

        //   F12 key
        if(e.keyCode == 123) {
             _CariBarang();
            return false;
        }
        
         //   F1 key
        if(e.keyCode == 122) {
            $('#carikontak').click();
            return false;
        }
    }
    
    
  $('#noref').keydown(function(e){
    if(e.keyCode==13) { $('#boknoref').focus(); }
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

  $(this).on('select2:open', function(){
    this.querySelector('.select2-search__field').focus();
  });  

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });
  
    $("#chkdetailpasien").click(function(){
    if ($(this).is(":checked"))
    {
         $(".detailpasien").addClass('d-none');
    } else { 
         $(".detailpasien").removeClass('d-none');         
    }         
  });
  
  
// jike enter, makaok di masing2 form modal
  $('#norefnya').keydown(function(e){
    if(e.keyCode==13) { $('#boknoref').click(); }
  });
  

  $("#bTable").click(function() {
    parent.window.$('.loader-wrap').removeClass('d-none');
    location.href=base_url+"page/opjData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-pos_hp");
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
        "url"    : base_url+"Modal/cari_kontak_pos", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-pos_2");
          parent.window.$('#coltrigger').val('customer');                
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal cari kontak...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');  
          
          
          parent.window._setjenis();
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak(''); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
             
          }, 500);
          return;
        } 
      });
    }    
  });
  
  
  
  
var _AmbilDetailPasien = () => { 
    
            $('#divtglexpiredmember').html(""); 
            $('#statusmember').val(0);  
            $('#statuspasienbaru').html(""); 
            $('#pasienbaru').val(0);
    
    
      $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_detail_pasien", 
        "type"   : "POST", 
        "data"   : "id="+$("#idkontak").val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
          console.error('error ambil detail pasien...');
          //console.error(xhr.responseText);
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) {       
          

        $('#kontaktipe').val(result.data[0]['tipeid']);  
        $('#pasientipe').html(result.data[0]['namatipe']);
        //$('#lbljenispasien').html(result.data[0]['namatipe']);
        
        $('#pasienid').html(result.data[0]['pasienid']);  
        $('#pasienalamat').html(result.data[0]['alamat']);   
        $('#pasiennohp').html(result.data[0]['nohp']);     
        $('#namakontak').val(result.data[0]['nama']);       
        $('#tgltransaksiakhir').html(result.data[0]['tglakhir']);
        $('#tglbuat').html(result.data[0]['tglbuat']);
        const tglsekarang = new Date() ;
        const tglexpired = new Date(result.data[0]['tglexpired']) ;
        const tglbuat = result.data[0]['tglbuat'] ;
        const tglnow = result.data[0]['tglsekarang'] ;
        
       
        if( result.data[0]['tipeid']==12   ) 
        { 
          
           //jika pasien member makan cek tgl expirednya, jika tgl expired kosong, brarti sudah tidak aktif, jika sudah expired maka tidak dapat diskon member
           if (result.data[0]['tglexpired']=='' || result.data[0]['tglexpired']== 'null' || result.data[0]['tglexpired']== null ) 
           
           {
               
            $('#divtglexpiredmember').append("<button type=\"button\" class=\"btn btn-danger btn-step1 text-sm btn-sm  buttontambahan\" role=\"button\"> Member Tidak Aktif</button>");
            $('#statusmember').val(0);  
           }
           else if ( tglexpired < tglsekarang )  
           {
            $('#divtglexpiredmember').append("<button type=\"button\" class=\"btn btn-danger btn-step1 text-sm btn-sm   buttontambahan\" role=\"button\"> Member Sudah Expired Tanggal <span class=\"badge badge-light\" >"+result.data[0]['tglexpired']+"</span></button>");
            $('#statusmember').val(0);  
           }
           else 
           { 
            $('#divtglexpiredmember').append("<button type=\"button\" class=\"btn btn-light btn-step1 text-sm btn-sm   buttontambahan\" role=\"button\"> Member Aktif, Expired Tanggal <span class=\"badge badge-primary\" >"+result.data[0]['tglexpired']+"</span> </button>"); 
            $('#statusmember').val(1); 
            
            
             //   $('#namakontak').attr('data-title','Member Aktif, Expired Tanggal' +result.data[0]['tglexpired'] );
             //   $('#namakontak').tooltip('show');
      
           } 
            
           // //$('#tglexpiredmember').val(result.data[0]['tglexpired']);  
            //$('#tglexpiredmember').append("<span class=\"badge badge-pill badge-primary\" id=\"spantglexpiredmember\" >"+result.data[0]['tglexpired']+"</span> ");  
            //if (result.data[0]['tglexpired']!='') $('#labeltglexpiredmember').html('Tanggal Expired ' ); 
            //SetStatusMember();
        
        }
        
        
        //cek apakah pasien baru atau bukan
           if (result.data[0]['tglbuat']=='' || result.data[0]['tglbuat']== 'null' || result.data[0]['tglbuat']== null ) 
           
           {
            $('#statuspasienbaru').append("<span class=\"badge badge-danger\" >Tanggal Buat Kosong</span>");
            $('#pasienbaru').val(0);  
           }
           else if ( tglnow  > tglbuat )  
           {
            $('#statuspasienbaru').append("<span class=\"badge badge-danger\" >Pasien Lama</span>");
            $('#pasienbaru').val(0);  
           }
           else 
           { 
            $('#statuspasienbaru').append("<span class=\"badge badge-primary\" >Pasien Baru</span>");
            $('#pasienbaru').val(1);  
             //   $('#namakontak').attr('data-title','Member Aktif, Expired Tanggal' +result.data[0]['tglexpired'] );
             //   $('#namakontak').tooltip('show');
      
           } 
        
        
          
        //reset ulang diskon jika bukna membeer
         if ($("#id").val()==''){ 
            const totalbaris = $(".item").length;
            for(let i=0;i<totalbaris;i++){
              if( result.data[0]['tipeid']!=12   ){  
                        $("input[name^='dis1']").eq(i).val(0).attr('placeholder',0);
                        $("input[name^='dis2']").eq(i).val(0).attr('placeholder',0); 
                        _hitungJumlahDetil(i); 
                        _TampilkanNamaItem(i); 
                        
              }
            } 
            _hitungsubtotal();
            _hitungTotal();
        
            // konfirmasi kartu 
            $('#bstatuskartumember').html('') ; 
            if (result.data[0]['tipeid']==12) { 
                
                $('#modalkartumember').on('shown.bs.modal', function(){   
                    document.getElementById("optbawakartu").checked = false;
                    document.getElementById("opttidakbawakartu").checked = false; 
                    
                    $('#optbawakartu').focus();   
                }); 
                
                $('#modalkartumember').modal('show');   
            }
              
         }    
          return;                    
      } 
      }); 
}
 
 

 var SetKartuPasien = () => {  
     var a='',b='';
    $('#bstatuskartumember').html('') ; 
    if ($('#optbawakartu').is(":checked") ) {  
       if ( $('#statusmember').val()==1 )   $('#statusmember').val(1);  
        //$('#bstatuskartumember').append(''); 
        a = $('#divtglexpiredmember').html() ;
        b = "<span class=\"badge badge-pill badge-primary\" >Bawa Kartu</span> "
        
    }
    else  if ($('#opttidakbawakartu').is(":checked") ) {
         $('#statusmember').val(0);  
        $('#bstatuskartumember').append(''); 
        a = $('#divtglexpiredmember').html() ;
        b = "<span class=\"badge badge-pill badge-danger\" >Tidak Bawa Kartu</span> "
        //$('#bstatuskartumember').append(""); 
    } 
    
    
        $('#divtglexpiredmember').html('');
        $('#divtglexpiredmember').append(a+b); 
        
 }

 
 $("#bokpilihkartu").click(function() { 
    SetKartuPasien(); 
 });


    window.datateman = function() {  
        $('#modaldatateman').modal('show'); 
    }
    window.datalainnya = function() {  
        $('#modaldatalainnya').modal('show'); 
    }
    window.catatanplanning = function() {  
        
        
         $('#modalcatatanplanning').on('shown.bs.modal', function(){  
             $('#untuksave').val(0);   
            $('#rekammedis').focus();   
        });  
        $('#modalcatatanplanning').modal('show'); 
    }

     window.medlibbeluminvoice = function() { 
         
        if($(this).hasClass("disabled")) return;
          
        if($('#idkontak').val()==''){
          $('#namakontak').attr('data-title','Pasien harus diisi !');
          $('#namakontak').tooltip('show');
          $('#namakontak').focus();
          return 0;
        }
        
        
        $.ajax({ 
          "url"    : base_url+"Modal/cari_faktur", 
          "type"   : "POST",  
          "dataType" : "html",
          "beforeSend": function(){
            parent.window.$(".loader-wrap").removeClass("d-none");        
            parent.window.$(".modal").modal("show");                  
            parent.window.$(".modal-title").html("Cari Transaksi Web Belum Dibuat Invoice");
            parent.window.$("#modaltrigger").val("iframe-page-pos_2");   
            parent.window.$('#coltrigger').val('webidbelumiv');     
          },       
          "error": function(){
            parent.window.$(".loader-wrap").addClass("d-none");
            console.log('error menampilkan modal cari transaksi...');
            return;
          },
          "success": function(result) {
            parent.window.$(".main-modal-body").html(result);  
            parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');            
            parent.window._transaksidatatable('view_web_blm_iv',$('#idkontak').val());
              setTimeout(function (){
                   parent.window.$('#modal input').focus();
              }, 500);
            return;
          } 
        });   
    }
 
      var _AmbilDetailWEB_blmiv = () => {   
          
          
       
          $.ajax({ 
            "url"    : base_url+"PJ_POS_HP/get_detail_web_blmiv", 
            "type"   : "POST",  
            "data"   :   {webid: $("#webidbelumiv").val()},
            "dataType" : "json", 
            "cache"  : false,
            "beforeSend" : function(){
              $("#loader-detil").removeClass('d-none');
            },        
            "error"  : function(xhr,status,error){
                  parent.window.toastr.error('Error : Gagal mengambil data Invoice Web Belum Invoice !');
                  parent.window.$('.loader-wrap').addClass('d-none');                  
                return;
            },
            "success" : function(result) {
            
          if (typeof result.pesan !== 'undefined') {
            parent.window.toastr.error(result.pesan);
            parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
          } else {  
            
            var rows = 0, nopaket='', kedatangan=0, harga=0, dis1=0, dis2=0, diskon=0, subtotal=0, qty=0, pilihan='' , kepilihan=0, datake=0 ;
            var statusbayar = 0, norefmerchant='', totaltransaksidanbayar=0, hargaongkir=0, webrows=0, tiperesep=0, qtyresep=0, norefresep='', iddokter=0, namadokter='' ;
            let fltnya='';
            
            
              
             tiperesep=(result.data[0]['tiperesep']);  
             iddokter=(result.data[0]['iddokter']); 
             namadokter=(result.data[0]['namadokter']);  
             $('#idmedlib').val(result.data[0]['idmedlib']) ; 
             $('#kodemedlib').val(result.data[0]['kodemedlib']) ; 
                
                
            $.each(result.data, function() {
            _addRow();
            _inputFormat();
            rows = $("select[name^='item']").length ;  
            rows=rows-1;  
    
              var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem']).text(result.data[datake]['namaitem']),
                  _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan']).text(result.data[datake]['satuan']),
                  _dokter = $("<option selected='selected'></option>").val(result.data[datake]['iddokter']).text(result.data[datake]['namadokter']);      
            
            
    
              $("select[name^='item']").eq(rows).append(_item).trigger('change');       
              $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');            
              //$("input[name^='qty']").eq(rows).val(result.data[datake]['qtydetil'].replace(".", ",")); 
              
              
                  kedatangan=0; 
                  qty=Number(result.data[datake]['qtydetil'].replace(".", ","));   
                  harga=result.data[datake]['hargadetil'].replace(".", ",");        
                  dis1=result.data[datake]['dis1detil'].replace(".", ",");        
                  dis2=result.data[datake]['dis2detil'].replace(".", ",");
                  diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
                  subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");    
                  qtyresep+=qty;
                 
              
              
              $("input[name^='qty']").eq(rows).val(qty);         
              $("input[name^='harga']").eq(rows).val(harga);        
              $("input[name^='dis1']").eq(rows).val(dis1);        
              $("input[name^='dis2']").eq(rows).val(dis2);
              $("input[name^='diskon']").eq(rows).val(diskon);                       
              $("input[name^='subtotal']").eq(rows).val(subtotal);   
                
              $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe2020']); 
              $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter']); 
              $("select[name^='dokter']").eq(rows).append(_dokter).trigger('change');  
              
              $("input[name^='noref']").eq(rows).val(result.data[datake]['noref']);  
              norefresep=(result.data[datake]['noref']);  
              
              $("input[name^='medidd']").eq(rows).val(result.data[datake]['medidd']);    
              $("input[name^='medidu']").eq(rows).val(result.data[datake]['medidu']); 
              
              $("input[name^='kedatanganke']").eq(rows).val(0); 
              $('#keberapa').val(rows); 
               
               
              _TampilkanNamaItem(rows); 
              rows++;
              datake++;
              kedatangan=0;
            });  
            
            
            _addRow();
            _inputFormat();
            webrows = $("select[name^='item']").length ;  
            webrows=webrows-1;   
            
            
            const tipedkk = ["1", "2", "3", "5", "6"]; 
            let index = tipedkk.indexOf(tiperesep); 
              if ( index > 0 )
              {  
                    var _item = $("<option selected='selected'></option>").val(3446).text('RESEP SKIN CARE DKK');
                    $("select[name^='item']").eq(webrows).append(_item).trigger('change');   
                    _SetDataBarang(webrows,3446); //bid RESEP SKIN CARE DKK = 3446
              }
              else
              {
                    var _item = $("<option selected='selected'></option>").val(1486).text('RESEP SKIN CARE DKK');
                    $("select[name^='item']").eq(webrows).append(_item).trigger('change');   
                _SetDataBarang(webrows,1486); //bid = RESEP SKIN CARE =  1486
              }
              
              $("input[name^='qty']").eq(webrows).val(qtyresep);  
              $("input[name^='noref']").eq(webrows).val(norefresep);  
              $("input[name^='noic']").eq(webrows).val("000");  
              
              $("select[name^='dokter']").eq(webrows).append( $("<option selected='selected'></option>.val(iddokter)").text(namadokter) ).trigger('change');  
              
             _TampilkanNamaItem(webrows);   
               
           
           _hitungsubtotal();
           _hitungTotal(); 
            
            parent.window.toastr.success("Sukses menarik data Invoice WEB ");   
            parent.window.$('.loader-wrap').addClass('d-none');   
            
            return;   
                          
          }
          } 
          }); 
    }
     
 
 
 window.medlibbyjokul = function() {   
    if($(this).hasClass("disabled")) return;
      
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    
    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_faktur", 
      "type"   : "POST",  
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi Web Pembayaran Via Jokul");
        parent.window.$("#modaltrigger").val("iframe-page-pos_2");   
        parent.window.$('#coltrigger').val('webid');     
      },       
      "error": function(){
        parent.window.$(".loader-wrap").addClass("d-none");
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);  
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');            
        parent.window._transaksidatatable('view_web',$('#idkontak').val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    });   
    
    
}
 
  
$("#bweb").click(function() {  
});

 
  
  var _AmbilDetailWEB = () => {   
   
      $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_detail_web", 
        "type"   : "POST",  
        "data"   :   {webid: $("#webid").val()},
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
              parent.window.toastr.error('Error : Gagal mengambil data Invoice WEB !');
              parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : function(result) {
        
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
          
             
        
        var rows = 0, nopaket='', kedatangan=0, harga=0, dis1=0, dis2=0, diskon=0, subtotal=0, qty=0, pilihan='' , kepilihan=0, datake=0 ;
        var statusbayar = 0, norefmerchant='', totaltransaksidanbayar=0, hargaongkir=0, webrows=0, tiperesep=0, qtyresep=0, norefresep='', iddokter=0, namadokter='' ;
        let fltnya='';
        
        
         statusbayar=(result.data[0]['statusbayar']); 
         norefmerchant=(result.data[0]['norefmerchant']); 
         totaltransaksidanbayar=Number(result.data[0]['totaltransaksidanbayar'].toString().replace(',','.')); 
         hargaongkir=Number(result.data[0]['hargaongkir'].toString().replace(',','.'));  
         tiperesep=(result.data[0]['tiperesep']);  
         iddokter=(result.data[0]['iddokter']); 
         namadokter=(result.data[0]['namadokter']);  
         $('#idmedlib').val(result.data[0]['idmedlib']) ; 
         $('#kodemedlib').val(result.data[0]['kodemedlib']) ; 
            
            
        $.each(result.data, function() {
        _addRow();
        _inputFormat();
        rows = $("select[name^='item']").length ;  
        rows=rows-1;  

          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem']).text(result.data[datake]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan']).text(result.data[datake]['satuan']),
              _dokter = $("<option selected='selected'></option>").val(result.data[datake]['iddokter']).text(result.data[datake]['namadokter']);      
        
        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');       
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');            
          //$("input[name^='qty']").eq(rows).val(result.data[datake]['qtydetil'].replace(".", ",")); 
          
          
              kedatangan=0; 
              qty=result.data[datake]['qtydetil'].replace(".", ",");   
              harga=result.data[datake]['hargadetil'].replace(".", ",");        
              dis1=result.data[datake]['dis1detil'].replace(".", ",");        
              dis2=result.data[datake]['dis2detil'].replace(".", ",");
              diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
              subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");    
              qtyresep+=qty;
             
          
          
          $("input[name^='qty']").eq(rows).val(qty);         
          $("input[name^='harga']").eq(rows).val(harga);        
          $("input[name^='dis1']").eq(rows).val(dis1);        
          $("input[name^='dis2']").eq(rows).val(dis2);
          $("input[name^='diskon']").eq(rows).val(diskon);                       
          $("input[name^='subtotal']").eq(rows).val(subtotal);   
            
          $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe2020']); 
          $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter']); 
          $("select[name^='dokter']").eq(rows).append(_dokter).trigger('change');  
          
          $("input[name^='noref']").eq(rows).val(result.data[datake]['noref']);  
          norefresep=(result.data[datake]['noref']);  
          
          $("input[name^='medidd_sudahbayar']").eq(rows).val(result.data[datake]['medidd']);    
          $("input[name^='medidu_sudahbayar']").eq(rows).val(result.data[datake]['medidu']); 
          
          $("input[name^='kedatanganke']").eq(rows).val(0); 
          $('#keberapa').val(rows); 
           
           
          _TampilkanNamaItem(rows); 
          rows++;
          datake++;
          kedatangan=0;
        }); 
        
        
        _addRow();
        _inputFormat();
        webrows = $("select[name^='item']").length ;  
        webrows=webrows-1;   
        
        
        const tipedkk = ["1", "2", "3", "5", "6"]; 
        let index = tipedkk.indexOf(tiperesep); 
          if ( index > 0 )
          {  
                var _item = $("<option selected='selected'></option>").val(3446).text('RESEP SKIN CARE DKK');
                $("select[name^='item']").eq(webrows).append(_item).trigger('change');   
                _SetDataBarang(webrows,3446); //bid RESEP SKIN CARE DKK = 3446
          }
          else
          {
                var _item = $("<option selected='selected'></option>").val(1486).text('RESEP SKIN CARE DKK');
                $("select[name^='item']").eq(webrows).append(_item).trigger('change');   
            _SetDataBarang(webrows,1486); //bid = RESEP SKIN CARE =  1486
          }
          
          $("input[name^='qty']").eq(webrows).val(qtyresep);  
          $("input[name^='noref']").eq(webrows).val(norefresep);  
          $("input[name^='noic']").eq(webrows).val("000");  
          
          $("select[name^='dokter']").eq(webrows).append( $("<option selected='selected'></option>.val(iddokter)").text(namadokter) ).trigger('change');  
          
         _TampilkanNamaItem(webrows);  
          
        _addRow();
        _inputFormat();
        webrows = $("select[name^='item']").length ;  
        webrows=webrows-1;    
                var _item = $("<option selected='selected'></option>").val(5135).text('BIAYA LAYANAN');
                $("select[name^='item']").eq(webrows).append(_item).trigger('change');  
                 _SetDataBarang(webrows,5135); //bid biaya layanan =5135
          
         
         setTimeout(function (){
               _TampilkanNamaItem(webrows); 
          }, 500);
          
        
      
       
        if (hargaongkir>0) {
            _addRow();
            _inputFormat();
            webrows = $("select[name^='item']").length ;  
            webrows=webrows-1;  
            _SetDataBarang(webrows,2445);         
          $("input[name^='harga']").eq(webrows).val(hargaongkir);    
          $("input[name^='subtotal']").eq(webrows).val(hargaongkir);  
         
         setTimeout(function (){
               _TampilkanNamaItem(webrows); 
          }, 500);
             
        }   
      
      if (statusbayar==1) {
         $('#merchantno').val(norefmerchant);   
         $('#merchantjumlah').val(totaltransaksidanbayar);  
         
          var _merchantjenis = $("<option selected='selected'></option>").val(6).text('DOKU');
          $('#merchantjenis').append(_merchantjenis).trigger('change');    
      }
           
       
       _hitungsubtotal();
       _hitungTotal();
       
      // $("#medid").val('')=
        
        parent.window.toastr.success("Sukses menarik data Invoice WEB ");   
        parent.window.$('.loader-wrap').addClass('d-none');   
        
        //_CekKelengkapanJenis();
        
        return;   
                      
      }
      } 
      }); 
}


 $("#bpasien").click(function() { 
    if($(this).hasClass("disabled")) return;
    
     $('#modaldatapasien').modal('show');
    
  }); 
 

  $("#bpro").click(function() { 
    if($(this).hasClass("disabled")) return;
      
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    
    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_faktur", 
      "type"   : "POST",  
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi PRO");
        parent.window.$("#modaltrigger").val("iframe-page-pos_2");   
        parent.window.$('#coltrigger').val('medid');     
      },       
      "error": function(){
        parent.window.$(".loader-wrap").addClass("d-none");
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);  
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');            
        parent.window._transaksidatatable('view_pro',$('#idkontak').val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    });   
  });  
  
   var _AmbilDetailPRO = () => {   
   
      $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_detail_pro", 
        "type"   : "POST",  
        "data"   :   {medid: $("#medid").val()},
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
              parent.window.toastr.error('Error : Gagal mengambil data PRO !');
              parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : async function(result) {
        
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
          
           
            
        
        var rows = 0, nopaket='', kedatangan=0, harga=0, dis1=0, dis2=0, diskon=0, subtotal=0, qty=0, pilihan='' , kepilihan=0, datake=0 ;
        let fltnya='';
        var itemproduk = 0, qtyresep = 0, noref = '' ;
        nopaket = $('#nopaketnya').val() ;
        
        noref=result.data[0]['medCode']  ;
        
        $('#lmcidpro').val(result.data[0]['proidu']) ; //iu lmc untuk link pembayaran
        var 
              _dokteru = $("<option selected='selected'></option>").val(result.data[0]['iddokter']).text(result.data[0]['namadokter']), 
              _operatoru = $("<option selected='selected'></option>").val(result.data[0]['idoperator']).text(result.data[0]['namaoperator']);
            
        $.each(result.data, function() {
        _addRow();
        _inputFormat();
        rows = $("select[name^='item']").length ;  
        rows=rows-1;  

          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem']).text(result.data[datake]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan']).text(result.data[datake]['satuan']),
              _paket = $("<option selected='selected'></option>").val(result.data[datake]['idpaket']).text(result.data[datake]['namapaket']),
              _promo = $("<option selected='selected'></option>").val(result.data[datake]['idpromo']).text(result.data[datake]['namapromo']),
              _dokter = $("<option selected='selected'></option>").val(result.data[datake]['iddokter']).text(result.data[datake]['namadokter']), 
              _operator = $("<option selected='selected'></option>").val(result.data[datake]['idoperator']).text(result.data[datake]['namaoperator']), 
              _pro = $("<option selected='selected'></option>").val(result.data[datake]['idpro']).text(result.data[datake]['namapro']);      
        
        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');       
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');            
          //$("input[name^='qty']").eq(rows).val(result.data[datake]['qtydetil'].replace(".", ",")); 
          
          
              kedatangan=0; 
              qty=Number(result.data[datake]['qtydetil'].replace(".", ","));   
              harga=result.data[datake]['hargadetil'].replace(".", ",");        
              dis1=result.data[datake]['dis1detil'].replace(".", ",");        
              dis2=result.data[datake]['dis2detil'].replace(".", ",");
              diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
              subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
          
          
          $("input[name^='qty']").eq(rows).val(qty);         
          $("input[name^='harga']").eq(rows).val(harga);        
          $("input[name^='dis1']").eq(rows).val(dis1);        
          $("input[name^='dis2']").eq(rows).val(dis2);
          $("input[name^='diskon']").eq(rows).val(diskon);                       
          $("input[name^='subtotal']").eq(rows).val(subtotal);   
            
          $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe2020']); 
          $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter']); 
          $("select[name^='paket']").eq(rows).append(_paket).trigger('change');  
          $("select[name^='promo']").eq(rows).append(_promo).trigger('change'); 
          if (result.data[datake]['wajibdokter']=='1')
          {
                $("select[name^='dokter']").eq(rows).append(_dokter).trigger('change');      
                $("input[name^='noref']").eq(rows).val(result.data[datake]['medCode']); 
          }  
          //$("select[name^='operator']").eq(rows).append(_operator).trigger('change');  
          //$("input[name^='nopaketdetil']").eq(rows).val(nopaket);             
          $("input[name^='idpaketdetil']").eq(rows).val(result.data[datake]['idpaketdetil']);  
          $("input[name^='daripaket']").eq(rows).val(result.data[datake]['daripaket']);    
          $("input[name^='proidd']").eq(rows).val(result.data[datake]['proidd']);    
          $("input[name^='proidu']").eq(rows).val(result.data[datake]['proidu']);  
          $("input[name^='kedatanganke']").eq(rows).val(0); 
          
           if (result.data[datake]['lmdType']=='add_on') 
           {
             $("select[name^='aos']").eq(rows).append(_pro).trigger('change');    
           }
           else
           {
             $("select[name^='recom']").eq(rows).append(_pro).trigger('change');    
           }
            
           
           
          //pilihan=result.data[datake]['pilihan'].replace("|", "','");  
          // $('#pilihanpaketnya').val(pilihan);
          //pilihan=result.data[datake]['pilihan']; 
          
          $('#keberapa').val(rows);     
          itemproduk+=result.data[datake]['produk']; 
          qtyresep+=qty;  
           
          _TampilkanNamaItem(rows); 
          
          rows++;
          datake++;
          kedatangan=0;
        });
        
        
        
          
          
          // tampilkan RESEP
        
        
          if ( itemproduk > 0 )
          {
              
              _addRow();
                _inputFormat();
                rows = $("select[name^='item']").length ;  
                rows=rows-1;   
        
        
                var _item = $("<option selected='selected'></option>").val(1486).text('RESEP SKIN CARE');
                $("select[name^='item']").eq(rows).append(_item).trigger('change');   
                _SetDataBarang(rows,1486); //bid = RESEP SKIN CARE =  1486
         
          
                   
                  $("input[name^='noref']").eq(rows).val(noref);  
                  $("input[name^='noic']").eq(rows).val("000");  
                  
                  $("select[name^='dokter']").eq(rows).append(_dokteru).trigger('change');  
                  $("select[name^='operator']").eq(rows).append(_operatoru).trigger('change');  
                  
                  qtyresep = qtyresep.toString().replace('.',',');  
                  if(qtyresep==0) qtyresep='0,00'; 
                  $("input[name^='qty']").eq(rows).val(qtyresep).attr('placeholder',qtyresep); 
                  
                let data = await _TampilkanNamaItem(rows);     
        
          }
        
         
       _hitungsubtotal();
       _hitungTotal();
       
       // $("#medid").val('')=
        
        parent.window.toastr.success("Sukses menarik data PRO ");   
        parent.window.$('.loader-wrap').addClass('d-none');   
        
       _CekKelengkapanTindakan() ;
        
        return;   
                      
      }
      } 
      }); 
}

  
    var _CekKelengkapanJenis = () => {   
   } 

   
  
  
  $("#bpromo").click(function() { 
      
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    
    $('#idpromo').val('') ;
    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_promo", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-pos_2");                              
      },       
      "error": function(){
        parent.window.$(".loader-wrap").addClass("d-none");
        console.log('error menampilkan modal cari promo...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);  
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');     
        parent.window.$('#jeniskontak').val($('#kontaktipe').val());         
        parent.window._transaksidatatable('view_pos');   
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    }); 
  });  
  
   
  var _AmbilDetailPromo = () => {  
   
      $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_detail_promo", 
        "type"   : "POST",  
        "data"   :   {idpromo:$("#idpromo").val()},
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
              parent.window.toastr.error('Error : Gagal mengambil data promo !');
              parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : async function(result) { 
        
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
          
          var tsubtotal = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')) ,
                minimaltransaksi = result.data[0]['minimaltotaltransaksi'].replace(".", ","),
                maxpasien = result.data[0]['maxpasien'],
                jumlahambil = Number(result.data[0]['jumlahambil'])+1,
                pakaijam = result.data[0]['pakaijam'],
                jam1 = result.data[0]['jam1'],
                jam2 = result.data[0]['jam2'],
                jamsekarang = result.data[0]['jamsekarang'];
        
        if ( minimaltransaksi>0 && tsubtotal < minimaltransaksi )
        {
            parent.window.toastr.error('Minimal transaksi ' + minimaltransaksi.toString().replace('.',',')   );
            parent.window.$('.loader-wrap').addClass('d-none');      
            return ;
        }
        else if (maxpasien >0  && jumlahambil > maxpasien )
        {
            //jika ada batasan jumlah pasien maka hitung promo yg sudah diambil hari ini pada cabang ini 
            parent.window.toastr.error('Jumlah quota sudah habis. Max Quota ' + maxpasien   );
            return; 
        }
        else if ( jamsekarang <  jam1 && pakaijam == 1 )
        {
            //mulai jam start promo 
            parent.window.toastr.error('Promo berlaku mulai jam ' + jam1   );
            return; 
        }
        else if ( jamsekarang >  jam2  && pakaijam == 1 )
        {
            //mulai jam start promo 
            parent.window.toastr.error('Promo berlaku sampai jam ' + jam2   );
            return; 
        }

            
        
        var rows = 0, datake=0, harga=0, dis1=0, dis2=0, diskon=0, subtotal=0, qty=0, pilihan='' , kepilihan=0, jenispromo, item2='', item3='', item4='' ;
        var pilihan1 = '', pilihan2 = '', pilihan3 = '', pilihan4 = '' ;
        let fltnya='';
        var idx = 0;
          
         
        $.each(result.data, async function() {
            
            _addRow();
            _inputFormat(); 
            rows = $("select[name^='item']").length ;  
            rows=rows-1; 
            
             jenispromo=result.data[datake]['jenispromo']; 
             if (jenispromo=0)
             { qty=result.data[datake]['qtydetil'].replace(".", ",");    }
             else
             { qty=result.data[datake]['qtydetil1'].replace(".", ",");    }
             

          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem']).text(result.data[datake]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan']).text(result.data[datake]['satuan']),
              _promo = $("<option selected='selected'></option>").val(result.data[datake]['idpromo']).text(result.data[datake]['namapromo']);                       

              $("select[name^='item']").eq(rows).append(_item).trigger('change');       
              $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');   
             
              harga=result.data[datake]['hargadetil'].replace(".", ",");        
              dis1=result.data[datake]['dis1detil'].replace(".", ",");        
              dis2=result.data[datake]['dis2detil'].replace(".", ",");
              //diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
              //subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
          
              $("input[name^='qty']").eq(rows).val(qty);         
              $("input[name^='harga']").eq(rows).val(harga);        
              $("input[name^='dis1']").eq(rows).val(dis1);        
              $("input[name^='dis2']").eq(rows).val(dis2);
             // $("input[name^='diskon']").eq(rows).val(diskon);                       
             // $("input[name^='subtotal']").eq(rows).val(subtotal);   
                
              $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe2020']); 
              $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter']); 
              $("select[name^='promo']").eq(rows).append(_promo).trigger('change');   
              
              pilihan1=result.data[datake]['pilihan1'];   
               
              
                  if (pilihan1!='')
              {   
                       
                        let resultx = pilihan1.split("|");
                       $.each(resultx, function() {
                           if (resultx[kepilihan] !== 'undefined')
                           {
                               fltnya+=" ikode = '"+resultx[kepilihan]+"' or "; 
                           }
                           kepilihan++; 
                       });
                       
                      fltnya= fltnya.substr(1,  fltnya.length-4); 
                       $('#pilihanpaketnya').val(fltnya);
                       $('#keberapa').val(rows);
                       
                              $('#pilihanpaket').select2({ 
                                 "allowClear": true,
                                 "theme":"bootstrap4",    
                                 "ajax": {
                                    "url": base_url+"Select_Master/view_item_paket",
                                    "type": "post",
                                    "dataType": "json",                                       
                                    "delay": 800,
                                    "data": (params) => {
                                      return {  
                                         tipe:  $('#pilihanpaketnya').val() ,
                                         search: params.term
                                      }
                                    },
                                    "processResults": function (data, page) {
                                    return {
                                      results: data
                                    };
                                  },
                                }
                            });
                           
                          //const data  = await  _TampilkanModalPilihan();
                          
                          $('#modalPilihan').modal('show');   
                      
                      fltnya='';
              } 
          
          
              
              _hitungJumlahDetil(rows);
              _TampilkanNamaItem(rows); 
               
              
              item2=result.data[datake]['kditem2']; 
              if (item2!=='')
              {
                             
                             rows++;
                             //datake++;
                            _addRow();
                            _inputFormat();   
                            
                                qty=result.data[datake]['qtydetil2'].replace(".", ",");   
                
                          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem2']).text(result.data[datake]['namaitem2']),
                              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan2']).text(result.data[datake]['satuan2']),
                              _promo = $("<option selected='selected'></option>").val(result.data[datake]['idpromo']).text(result.data[datake]['namapromo']);                       
                
                              $("select[name^='item']").eq(rows).append(_item).trigger('change');       
                              $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');   
                             
                              harga=result.data[datake]['hargadetil2'].replace(".", ",");        
                              dis1=result.data[datake]['dis1detil2'].replace(".", ",");        
                              dis2=result.data[datake]['dis2detil2'].replace(".", ",");
                              //diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
                              //subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
                          
                              $("input[name^='qty']").eq(rows).val(qty);         
                              $("input[name^='harga']").eq(rows).val(harga);        
                              $("input[name^='dis1']").eq(rows).val(dis1);        
                              $("input[name^='dis2']").eq(rows).val(dis2);
                             // $("input[name^='diskon']").eq(rows).val(diskon);                       
                             // $("input[name^='subtotal']").eq(rows).val(subtotal);   
                                
                              $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe20202']); 
                                $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter2']); 
                              $("select[name^='promo']").eq(rows).append(_promo).trigger('change');   
                              
                               pilihan2=result.data[datake]['pilihan2'];   
                                      if (pilihan2!=='')
                                  {   
                                            let resultx = pilihan2.split("|");
                                           $.each(resultx, function() {
                                               if (resultx[kepilihan] !== 'undefined')
                                               {
                                                   fltnya+=" ikode = '"+resultx[kepilihan]+"' or "; 
                                               }
                                               kepilihan++; 
                                           });
                                           
                                          fltnya= fltnya.substr(1,  fltnya.length-4); 
                                           $('#pilihanpaketnya').val(fltnya);
                                           $('#keberapa').val(rows);
                                           
                                                  $('#pilihanpaket').select2({ 
                                                     "allowClear": true,
                                                     "theme":"bootstrap4",    
                                                     "ajax": {
                                                        "url": base_url+"Select_Master/view_item_paket",
                                                        "type": "post",
                                                        "dataType": "json",                                       
                                                        "delay": 800,
                                                        "data": (params) => {
                                                          return {  
                                                             tipe:  $('#pilihanpaketnya').val() ,
                                                             search: params.term
                                                          }
                                                        },
                                                        "processResults": function (data, page) {
                                                        return {
                                                          results: data
                                                        };
                                                      },
                                                    }
                                                });
                                               
                                              $('#modalPilihan').modal('show');  
                                              
                                                //const data  = await  _TampilkanModalPilihan();
                                                
                                                
                                          fltnya='';
                                  }  
                              _TampilkanNamaItem(rows); 
                              _hitungJumlahDetil(rows);  
                            
              }
              
              item3=result.data[datake]['kditem3'];
              if (item3!=='')
              {
                             rows++;
                             //datake++;
                            _addRow();
                            _inputFormat();   
                            
                                qty=result.data[datake]['qtydetil3'].replace(".", ",");   
                
                          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem2']).text(result.data[datake]['namaitem3']),
                              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan2']).text(result.data[datake]['satuan3']),
                              _promo = $("<option selected='selected'></option>").val(result.data[datake]['idpromo']).text(result.data[datake]['namapromo']);                       
                
                              $("select[name^='item']").eq(rows).append(_item).trigger('change');       
                              $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');   
                             
                              harga=result.data[datake]['hargadetil3'].replace(".", ",");        
                              dis1=result.data[datake]['dis1detil3'].replace(".", ",");        
                              dis2=result.data[datake]['dis2detil3'].replace(".", ",");
                              //diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
                              //subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
                          
                              $("input[name^='qty']").eq(rows).val(qty);         
                              $("input[name^='harga']").eq(rows).val(harga);        
                              $("input[name^='dis1']").eq(rows).val(dis1);        
                              $("input[name^='dis2']").eq(rows).val(dis2);
                             // $("input[name^='diskon']").eq(rows).val(diskon);                       
                             // $("input[name^='subtotal']").eq(rows).val(subtotal);   
                                
                              $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe20203']); 
                                 $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter3']); 
                              $("select[name^='promo']").eq(rows).append(_promo).trigger('change');   
                              
                              
                              pilihan3=result.data[datake]['pilihan3'];   
                               
                                  if (pilihan3!=='')
                                  {   
                                            let resultx = pilihan3.split("|");
                                           $.each(resultx, function() {
                                               if (resultx[kepilihan] !== 'undefined')
                                               {
                                                   fltnya+=" ikode = '"+resultx[kepilihan]+"' or "; 
                                               }
                                               kepilihan++; 
                                           });
                                           
                                          fltnya= fltnya.substr(1,  fltnya.length-4); 
                                           $('#pilihanpaketnya').val(fltnya);
                                           $('#keberapa').val(rows);
                                           
                                                  $('#pilihanpaket').select2({ 
                                                     "allowClear": true,
                                                     "theme":"bootstrap4",    
                                                     "ajax": {
                                                        "url": base_url+"Select_Master/view_item_paket",
                                                        "type": "post",
                                                        "dataType": "json",                                       
                                                        "delay": 800,
                                                        "data": (params) => {
                                                          return {  
                                                             tipe:  $('#pilihanpaketnya').val() ,
                                                             search: params.term
                                                          }
                                                        },
                                                        "processResults": function (data, page) {
                                                        return {
                                                          results: data
                                                        };
                                                      },
                                                    }
                                                });
                                               
                                               $('#modalPilihan').modal('show');  
                                          fltnya='';
                                  }  
                              
                              _TampilkanNamaItem(rows); 
                              _hitungJumlahDetil(rows);   
                          
                  
              }
              
              item4=result.data[datake]['kditem4'];
              if (item4!=='')
              {
                             rows++;
                             //datake++;
                            _addRow();
                            _inputFormat();   
                            
                                qty=result.data[datake]['qtydetil4'].replace(".", ",");   
                
                          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem2']).text(result.data[datake]['namaitem4']),
                              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan2']).text(result.data[datake]['satuan4']),
                              _promo = $("<option selected='selected'></option>").val(result.data[datake]['idpromo']).text(result.data[datake]['namapromo']);                       
                
                              $("select[name^='item']").eq(rows).append(_item).trigger('change');       
                              $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');   
                             
                              harga=result.data[datake]['hargadetil4'].replace(".", ",");        
                              dis1=result.data[datake]['dis1detil4'].replace(".", ",");        
                              dis2=result.data[datake]['dis2detil4'].replace(".", ",");
                              //diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
                              //subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
                          
                              $("input[name^='qty']").eq(rows).val(qty);         
                              $("input[name^='harga']").eq(rows).val(harga);        
                              $("input[name^='dis1']").eq(rows).val(dis1);        
                              $("input[name^='dis2']").eq(rows).val(dis2);
                             // $("input[name^='diskon']").eq(rows).val(diskon);                       
                             // $("input[name^='subtotal']").eq(rows).val(subtotal);   
                                
                              $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe20204']); 
                                $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter4']); 
                              $("select[name^='promo']").eq(rows).append(_promo).trigger('change');   
                              
                              
                              pilihan4=result.data[datake]['pilihan4'];   
                                  if (pilihan4!=='')
                                  {   
                                            let resultx = pilihan4.split("|");
                                           $.each(resultx, function() {
                                               if (resultx[kepilihan] !== 'undefined')
                                               {
                                                   fltnya+=" ikode = '"+resultx[kepilihan]+"' or "; 
                                               }
                                               kepilihan++; 
                                           });
                                           
                                          fltnya= fltnya.substr(1,  fltnya.length-4); 
                                           $('#pilihanpaketnya').val(fltnya);
                                           $('#keberapa').val(rows);
                                           
                                                  $('#pilihanpaket').select2({ 
                                                     "allowClear": true,
                                                     "theme":"bootstrap4",    
                                                     "ajax": {
                                                        "url": base_url+"Select_Master/view_item_paket",
                                                        "type": "post",
                                                        "dataType": "json",                                       
                                                        "delay": 800,
                                                        "data": (params) => {
                                                          return {  
                                                             tipe:  $('#pilihanpaketnya').val() ,
                                                             search: params.term
                                                          }
                                                        },
                                                        "processResults": function (data, page) {
                                                        return {
                                                          results: data
                                                        };
                                                      },
                                                    }
                                                });
                                               
                                               $('#modalPilihan').modal('show');  
                                          fltnya='';
                                  }  
                              
                             _TampilkanNamaItem(rows);  
                              _hitungJumlahDetil(rows); 
                           
                  
              }
              
          datake++;
          rows++; 
        });
        
        
        $('#idpromo').val('');
       _hitungsubtotal();
       _hitungTotal();
       
      
        
       // parent.window.toastr.success("Sukses menarik data Promo ");   
        parent.window.$('.loader-wrap').addClass('d-none');   
        return;   
                      
      }
      } 
      }); 
}
 
 
 
var _hitunghari = (_Tgl1, _Tgl2) => {  
    
    let zdate = _Tgl1 ;
    const myArray = zdate.split("-");
    
    let tanggal = myArray[0];
    let bulan = myArray[1];
    let tahun = myArray[2];
    
    let datez = tahun + '-'+ bulan + '-' + tanggal ; 
    
    let zdate2 = _Tgl2 ;
    const myArray2 = zdate2.split("-");
    
    let tanggalx = myArray2[0];
    let bulan2 = myArray2[1];
    let tahun2 = myArray2[2];
    
    let datez2 = tahun2 + '-'+ bulan2 + '-' + tanggalx ;  
     
    
    var tanggal1 = new Date(datez); // new Date() saja akan menghasilkan tanggal sekarang
    var tanggal2 = new Date(datez2); // format tanggal YYYY-MM-DD, tahun-bulan-hari
     
    // set jam menjadi jam 12 malam, atau 00
    tanggal1.setHours(0, 0, 0, 0);
    tanggal2.setHours(0, 0, 0, 0);
     
    var selisih = Math.abs(tanggal1 - tanggal2);
    // Selisih akan dalam millisecond atau mili detik
     
    var hariDalamMillisecond = 1000 * 60 * 60 * 24; // 1000 * 1 menit * 1 jam * 1 hari
     
    var selisihTanggal = Math.round(selisih / hariDalamMillisecond);
    // Hasilnya adalah 8 hari
    
    selisihTanggal=selisihTanggal+1; 
 
  return selisihTanggal;
}

var _cekPaket = (_NoPaket, _IdPaket) => {
    
    let _Kedatanganke=99;
    const totalbaris = $(".item").length; 
     for(let i=0;i<totalbaris;i++){   
       if ( _NoPaket == $("input[name^='nopaketdetil']").eq(i).val() &&    $("select[name^='paket']").eq(i).val() ==_IdPaket )  
       {
           _Kedatanganke=  $("input[name^='kedatanganke']").eq(i).val()   ; 
       } 
    } 
    
   return _Kedatanganke ; 
    
}

  $("#bpaket").click(function() {   
       
    if($(this).attr('role')) {
      
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    
    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_paket", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-pos_2");                  
      },       
      "error": function(){
        parent.window.$(".loader-wrap").addClass("d-none");
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);  
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');            
        parent.window._transaksidatatable('view_pos');
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    });  
    }
  });  
 
  var _AmbilDetailPaket= () => { 
      
      
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    
    
   
      $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_detail_paket", 
        "type"   : "POST",  
        "data"   :   {nopaket: $("#nopaketnya").val(),kontak:$("#idkontak").val(),idpaket:$("#idpaket").val()},
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
              parent.window.toastr.error('Error : Gagal mengambil data paket !');
              parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : function(result) {
        
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
          
         var  
                jenispasien = result.data[0]['jenispasien'],
                kontaktipe = $('#kontaktipe').val(),
                chkkonsulsaja = $('#chkkonsulsaja').val(),
                konsulsaja = result.data[0]['konsulsaja'],
                teman = $('#teman').val(),
                berdua = result.data[0]['berdua'],
                pasienbaru = $('#pasienbaru').val(),
                pasienbarusaja = result.data[0]['pasienbarusaja'],
                tanggalsekarang = result.data[0]['tanggalsekarang'],
                tanggalakhir = result.data[0]['tanggalakhir'] ,
                umurmax = result.data[0]['umurmax'] 
                
                ;
            
        
        if ( jenispasien==1 && kontaktipe!=12 )
        { 
            parent.window.toastr.error('Paket hanya untuk member'  ); 
            return ;
        } else if ( konsulsaja==1 && chkkonsulsaja==0 )
        { 
            parent.window.toastr.error('Paket hanya untuk konsul saja'  ); 
            return ;
        } else if ( pasienbarusaja==1 && pasienbaru!='1' )
        { 
            parent.window.toastr.error('Paket hanya untuk pasien baru '  ); 
            return ;
        } else if ( tanggalakhir!= '' )
        { 
                var selisih=_hitunghari(tanggalakhir,tanggalsekarang)
            
                if ( umurmax < selisih )
                { 
                    parent.window.toastr.error('Paket sudah lewat batas umur max yaitu ' + umurmax + ' hari' ); 
                    return ;
                }  
        }  
   
        
        var rows = 0, nopaket='', kedatangan=0, harga=0, dis1=0, dis2=0, diskon=0, subtotal=0, qty=0, pilihan='' , kepilihan=0, datake=0  ;
        let fltnya='';
        nopaket = $('#nopaketnya').val() ;
        
         let datangaktif =_cekPaket(nopaket, result.data[0]['idpaket'] ) ;
         
         
          
          if (result.data[0]['kedatangan']==99 && datangaktif ==99 )
          {  
            kedatangan=0;   
          }
          else if ( datangaktif !=99 )
          { 
           kedatangan=datangaktif;  
              kedatangan++;
          } 
          else
          {  
              kedatangan=result.data[0]['kedatangan']; 
              kedatangan++;
          }
              
            
        $.each(result.data, function() {
        _addRow();
        _inputFormat();
        rows = $("select[name^='item']").length ;  
        rows=rows-1;  

          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem']).text(result.data[datake]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan']).text(result.data[datake]['satuan']),
              _paket = $("<option selected='selected'></option>").val(result.data[datake]['idpaket']).text(result.data[datake]['namapaket']);      
        
        

          $("select[name^='item']").eq(rows).append(_item).trigger('change');       
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');            
          $("input[name^='qty']").eq(rows).val(result.data[datake]['qtydetil'].replace(".", ",")); 
          
          let datangaktif =_cekPaket(nopaket) ;
          
          if (kedatangan == 0 )
          {  
              //kedatangan=0; 
              qty=result.data[datake]['qtydetil'].replace(".", ",");   
              harga=result.data[datake]['hargadetil'].replace(".", ",");        
              dis1=result.data[datake]['dis1detil'].replace(".", ",");        
              dis2=result.data[datake]['dis2detil'].replace(".", ",");
              diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
              subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
          } 
          else
          {  
              //kedatangan=result.data[datake]['kedatangan']; 
              qty=result.data[datake]['qtydetiltindakan'].replace(".", ","); 
          }
          
          $("input[name^='qty']").eq(rows).val(qty);         
          $("input[name^='harga']").eq(rows).val(harga);        
          $("input[name^='dis1']").eq(rows).val(dis1);        
          $("input[name^='dis2']").eq(rows).val(dis2);
          $("input[name^='diskon']").eq(rows).val(diskon);                       
          $("input[name^='subtotal']").eq(rows).val(subtotal);   
            
          $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe2020']);
          $("input[name^='wajibdokter']").eq(rows).val(result.data[datake]['wajibdokter']);  
          $("select[name^='paket']").eq(rows).append(_paket).trigger('change');  
          $("input[name^='nopaketdetil']").eq(rows).val(nopaket);             
          $("input[name^='idpaketdetil']").eq(rows).val(result.data[datake]['idpaketdetil']);  
          $("input[name^='daripaket']").eq(rows).val(result.data[datake]['daripaket']);  
          
          $("input[name^='kedatanganke']").eq(rows).val(kedatangan);  
          //pilihan=result.data[datake]['pilihan'].replace("|", "','");  
          // $('#pilihanpaketnya').val(pilihan);
          pilihan=result.data[datake]['pilihan']; 
          
          if (pilihan!='')
          {   
                    let resultx = pilihan.split("|");
                   $.each(resultx, function() {
                       if (resultx[kepilihan] !== 'undefined')
                       {
                           fltnya+=" ikode = '"+resultx[kepilihan]+"' or ";
                          
                           
                       }
                       kepilihan++; 
                   });
                   
                  fltnya= fltnya.substr(1,  fltnya.length-4); 
                   $('#pilihanpaketnya').val(fltnya);
                   $('#keberapa').val(rows);
                   
                          $('#pilihanpaket').select2({ 
                             "allowClear": true,
                             "theme":"bootstrap4",    
                             "ajax": {
                                "url": base_url+"Select_Master/view_item_paket",
                                "type": "post",
                                "dataType": "json",                                       
                                "delay": 800,
                                "data": (params) => {
                                  return {  
                                     tipe:  $('#pilihanpaketnya').val() ,
                                     search: params.term
                                  }
                                },
                                "processResults": function (data, page) {
                                return {
                                  results: data
                                };
                              },
                            }
                        });
                      
                       $('#modalPilihan').modal('show');   
                  
                  fltnya='';
          }
          _TampilkanNamaItem(rows); 
          rows++;
          datake++;
          //kedatangan=0;
        });
        
       _hitungsubtotal();
       _hitungTotal();
       
       $("#nopaketnya").val('');
       $("#idpaket").val('');
       
       
       //_CekKelengkapanTindakan();
        
        //parent.window.toastr.success("Sukses menarik data Paket ");   
        parent.window.$('.loader-wrap').addClass('d-none');   
        return;   
                      
      }
      } 
      }); 
}


  $("#bokpilihanpaket").click(function() { 
      
     let _idx = $('#keberapa').val(); 
     let _iditem = $('#pilihanpaket').val(); 
     let _kode = '', _urutan=0;
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_item", 
        "type"   : "POST", 
        "data"   : "id="+$('#pilihanpaket').val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
          console.error('error ambil satuan item...');
          //console.error(xhr.responseText);
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) { 
            
           
  
          let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']);   
                        
          let kode = $("<option selected='selected'></option>")
                        .val(result.data[0]['iditem'])
                        .text(result.data[0]['namaitem']);             
          
          $("select[name^='item']").eq(_idx).append(kode).trigger('change');  
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change');  
          $("select[name^='satuan']").eq(_idx).prop('disabled',true);  
          $("input[name^='item_tipe2020']").eq(_idx).val(result.data[0]['kelompok2020']);  
          $("input[name^='wajibdokter']").eq(_idx).val(result.data[0]['wajibdokter']); 
          
           
          _kode=result.data[0]['namaitem'];
          _urutan=_idx; 
          _TampilkanNamaItem(_idx); 
           $("span[name^='spannama']").eq(_urutan).html(_kode); 
           
          _CekKelengkapanTindak