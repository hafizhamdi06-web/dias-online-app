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
        if($("#idkontak").val()!='') 
        {
             parent.window.Swal.fire({
              title: `Jika anda ingin pasien, maka transaksi akan dikosongkan!`,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Iya`,
              }).then((printing) => {
                  if (printing.isConfirmed) {  
                        $('#tdetil tbody').html('');  
                        _hitungsubtotal();
                        _hitungTotal();
                         _bersihkandp(); 
                         _CariKontak();
        
        
                  } 
                  else  return;
              })
        } 
        else
        { 
         _bersihkandp(); 
         _CariKontak();
        }
            
         
    }    
  });
  
var _CariKontak = () => { 
    
    
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
               
            $('#divtglexpiredmember').append("<button type=\"button\" class=\"btn btn-danger btn-step1 text-sm btn-sm bexpiredmember\" role=\"button\"  > Member Tidak Aktif</button>");
            $('#statusmember').val(0);  
           }
           else if ( tglexpired < tglsekarang )  
           {
            $('#divtglexpiredmember').append("<button type=\"button\" class=\"btn btn-danger btn-step1 text-sm btn-sm  bexpiredmember\" role=\"button\"  > Member Tidak Aktif, Terakhir Tanggal <span class=\"badge badge-light\" >"+result.data[0]['tglexpired']+"</span></button>");
            $('#statusmember').val(0);  
           }
           else 
           { 
            $('#divtglexpiredmember').append("<button type=\"button\" class=\"btn btn-light btn-step1 text-sm btn-sm  bexpiredmember\" role=\"button\"  > Member Aktif, Expired Tanggal <span class=\"badge badge-primary\" >"+result.data[0]['tglexpired']+"</span> </button>"); 
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
    
    

     window.medlibbeluminvoice_cara2 = function() { 
         
        if($(this).hasClass("disabled")) return;
          
        if($('#idkontak').val()==''){
          $('#namakontak').attr('data-title','Pasien harus diisi !');
          $('#namakontak').tooltip('show');
          $('#namakontak').focus();
          return 0;
        }
        
        
        $.ajax({ 
          "url"    : base_url+"Modal/cari_faktur_web", 
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
            parent.window._transaksidatatable('view_web_blm_iv_2',$('#idkontak').val());
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
            "data"   :   {webid: $("#webidbelumiv").val() }, //, statusmember:$("#statusmember").val()
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
                  _dokter = $("<option selected='selected'></option>").val(result.data[datake]['iddokter']).text(result.data[datake]['namadokter']) ;      
            
            
    
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
              
              if(result.data[datake]['wajibdokter']==1)
              {
              $("select[name^='dokter']").eq(rows).append(_dokter).trigger('change');   
              }
              
              //$("input[name^='noref']").eq(rows).val(result.data[datake]['noref']);  
              norefresep=(result.data[datake]['noref']);  
              
              $("input[name^='medidd']").eq(rows).val(result.data[datake]['medidd']);    
              $("input[name^='medidu']").eq(rows).val(result.data[datake]['medidu']); 
              
              $("input[name^='kedatanganke']").eq(rows).val(0); 
              $('#keberapa').val(rows); 
              
              $("input[name^='cetak']").eq(rows).val(1);  
               
               
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
              
          var    _dokter_resep = $("<option selected='selected'></option>").val(iddokter).text(namadokter),
                _op_resep = $("<option selected='selected'></option>").val(iddokter).text(namadokter) ;  
                   
              
              $("select[name^='dokter']").eq(webrows).append(_dokter_resep).trigger('change');  
              $("select[name^='operator']").eq(webrows).append(_op_resep).trigger('change'); 
              
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
          
              if(result.data[datake]['wajibdokter']==1)
              {
              $("select[name^='dokter']").eq(rows).append(_dokter).trigger('change');   
              }
               
          //$("input[name^='noref']").eq(rows).val(result.data[datake]['noref']);  
          norefresep=(result.data[datake]['noref']);  
          
          $("input[name^='medidd_sudahbayar']").eq(rows).val(result.data[datake]['medidd']);    
          $("input[name^='medidu_sudahbayar']").eq(rows).val(result.data[datake]['medidu']); 
          
          $("input[name^='kedatanganke']").eq(rows).val(0); 
          $('#keberapa').val(rows); 
           
           $("input[name^='cetak']").eq(rows).val(1);   
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
                var _item = $("<option selected='selected'></option>").val(1486).text('RESEP SKIN CARE');
                $("select[name^='item']").eq(webrows).append(_item).trigger('change');   
            _SetDataBarang(webrows,1486); //bid = RESEP SKIN CARE =  1486
          }
          
          $("input[name^='qty']").eq(webrows).val(qtyresep);  
          $("input[name^='noref']").eq(webrows).val(norefresep);  
          $("input[name^='noic']").eq(webrows).val("000");  
          
           var    _dokter_resep = $("<option selected='selected'></option>").val(iddokter).text(namadokter),
                _op_resep = $("<option selected='selected'></option>").val(iddokter).text(namadokter) ;  
          
          //$("select[name^='dokter']").eq(webrows).append( $("<option selected='selected'></option>.val(iddokter)").text(namadokter) ).trigger('change');  
          $("select[name^='dokter']").eq(webrows).append(_dokter_resep).trigger('change');  
          $("select[name^='operator']").eq(webrows).append(_op_resep).trigger('change');  
          
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
  
  

 $(".bexpiredmember").click(function() { 
    
    alert(1111);
        $.ajax({ 
      "url"    : base_url+"Modal/cari_histori", 
      "type"   : "POST",  
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Data Transaksi Pasien");
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
        parent.window._transaksidatatable('view_pos_histori',$('#idkontak').val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    }); 
    
    
    
  }); 

 $("#bpasienhistori").click(function() { 
    
    
        $.ajax({ 
      "url"    : base_url+"Modal/cari_histori", 
      "type"   : "POST",  
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Data Transaksi Pasien");
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
        parent.window._transaksidatatable('view_pos_histori',$('#idkontak').val());
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    }); 
    
    
    
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
              qty=result.data[datake]['qtydetil'].replace(".", ",");   
              harga=result.data[datake]['hargadetil'].replace(".", ",");        
              dis1=result.data[datake]['dis1detil'].replace(".", ",");        
              dis2=result.data[datake]['dis2detil'].replace(".", ",");
              diskon=result.data[datake]['diskondetil'].replace(".", ",");           
              
              subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
             // qtyresep=qtyresep+Number(result.data[datake]['qtydetil'].replace(".", ",")); 
              
              
               
          
          
          $("input[name^='qty']").eq(rows).val(qty);
          qtyresep=qtyresep+Number($("input[name^='qty']").eq(rows).val().split('.').join('').toString().replace(',','.'));
          
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
               // $("input[name^='noref']").eq(rows).val(result.data[datake]['medCode']); 
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
           
            $("input[name^='cetak']").eq(rows).val(1);  
            
           
           
          //pilihan=result.data[datake]['pilihan'].replace("|", "','");  
          // $('#pilihanpaketnya').val(pilihan);
          //pilihan=result.data[datake]['pilihan']; 
          
          $('#keberapa').val(rows);     
          itemproduk+=result.data[datake]['produk']; 
          
           
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
              
              $("input[name^='cetak']").eq(rows).val(1);  
              
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
                              
                               $("input[name^='cetak']").eq(rows).val(1);  
                              
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
                
                          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem3']).text(result.data[datake]['namaitem3']),
                              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan3']).text(result.data[datake]['satuan3']),
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
                              
                               $("input[name^='cetak']").eq(rows).val(1);  
                              
                              
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
                
                          var _item = $("<option selected='selected'></option>").val(result.data[datake]['iditem4']).text(result.data[datake]['namaitem4']),
                              _satuan = $("<option selected='selected'></option>").val(result.data[datake]['idsatuan4']).text(result.data[datake]['satuan4']),
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
                              
                               $("input[name^='cetak']").eq(rows).val(1);  
                              
                              
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
       
      
        
        parent.window.toastr.success("Sukses menarik data Promo ");   
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
          
           $("input[name^='cetak']").eq(rows).val(1);  
          
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
           
          _CekKelengkapanTindakan();
           
          $('#pilihanpaket').val(''); 
          return;                    
      } 
      });
  });
  
  
  
  
  $("#bkirimulangpointteman").click(function() {
    if($(this).hasClass("disabled")) return;
    
    if($('#teman').val()==''){
      $('#teman').attr('data-title','Pilih Nama Teman !');
      $('#teman').tooltip('show');
      $('#teman').focus();
      return 0;
    }
   
    if($(this).attr('role')) {   
        
        _kirimulangpointteman();
        
    }      
  });
   

  $("#carisalesman").click(function() {
    if($(this).attr('role')) {    
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-pos_2");
          parent.window.$('#coltrigger').val('salesman');                
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
          parent.window._pilihkategorikontak('4');
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });    
    }      
  });
  
  
  
   $("#carivoucher").click(function() {   
       
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
       
       $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/getdata_voucher",      
    "type"   : "POST", 
    "dataType" : "json",  
    "data" : {xid: $("#idkontak").val()},
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data voucher !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) { 
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else if (result.data.length !== '0') {
        parent.window.toastr.error('Tidak ada data voucher ');
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      }  else {  
         
        $('#voucherjumlah').val(result.data[0]['nilai'].replace(".", ","));  
        $('#voucherid').val(result.data[0]['id']); 
        $('#voucherno').val(result.data[0]['nomor']);   
        
        parent.window.toastr.success("Sukses menarik data voucher ");   
        parent.window.$('.loader-wrap').addClass('d-none');   
        return;

      }
    } 
  })
   
    
  });   
  
  
   $("#caridp").click(function() {   
       
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    
    
       
       $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/getdata_dp",      
    "type"   : "POST", 
    "dataType" : "json",  
    "data" : {xid: $("#idkontak").val()},
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil data DP !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) { 
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      }  
      else if (result.data.length  == '0') {
     //   parent.window.toastr.error('Tidak ada saldo DP ');
           parent.window.$('.loader-wrap').addClass('d-none');                  
          return;
      } 
      else {    
          
        $('#dpjumlah').val(result.data[0]['nilai'].replace(".", ","));  
        $('#dpid').val(result.data[0]['id']); 
        $('#dpno').val(result.data[0]['nomor']);  
        
        _hitungTotal();
        //parent.window.toastr.success("Sukses menarik data DP ");  
        
        parent.window.$('.loader-wrap').addClass('d-none');   
        return;

      }
    } 
  }) 
  });  
  
   $("#carisurgerydp").click(function() {   
       
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
       
       
       
       
       $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/getdata_surgerydp",      
    "type"   : "POST", 
    "dataType" : "json",  
    "data" : {xid: $("#idkontak").val()},
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error('Error : Gagal mengambil Surgery DP !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) { 
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else if (result.data.length !== '0') {
        parent.window.toastr.error('Tidak ada data Surgery DP ');
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      }  else {  
         
        $('#surgerydpidu').val(result.data[0]['id']); 
        $('#surgerydpno').val(result.data[0]['nomor']);  
        $('#surgerydptotal').val(result.data[0]['surgerydptotal'].replace(".", ","));  
        $('#surgerydppembayaran').val(result.data[0]['surgerydppembayaran'].replace(".", ","));  
        $('#surgerydppiutang').val(result.data[0]['nilaipiutang'].replace(".", ","));  
        
        
         $('#tdetil tbody').html('');
        for (let i = 0; i < result.data.length; i++) {
          _addRow();
        }
        _inputFormat();  

        var rows = 0, _tsubtotal = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']);                       

          $("select[name^='item']").eq(rows).append(_item).trigger('change');       
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');            
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
          $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));        
          $("input[name^='dis1']").eq(rows).val(result.data[rows]['dis1detil'].replace(".", ","));        
          $("input[name^='dis2']").eq(rows).val(result.data[rows]['dis2detil'].replace(".", ","));
          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskondetil'].replace(".", ","));                       
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));   
            
          $("input[name^='item_tipe2020']").eq(rows).val(result.data[rows]['item_tipe2020']); 
          $("input[name^='wajibdokter']").eq(rows).val(result.data[rows]['wajibdokter']);   
          
          _tsubtotal += Number(result.data[rows]['subtotaldetil']); 

          rows++;
        });

         
        _hitungsubtotal();
        _hitungTotal();
        
        //parent.window.toastr.success("Sukses menarik data Surgery DP ");   
        parent.window.$('.loader-wrap').addClass('d-none');   
        return;

      }
    } 
  }) 
  });  

  
   $("#boknopaket").click(function() {
       $('#modalPaket').modal('hide');       
      _AmbilDetailPaket(); 
  });

  
   $("#bbayar").click(function() {
       if($(this).attr('role')) { 
            $('#modalbayar').on('shown.bs.modal', function(){  
                $("#caridp").click();  
            });   
            $('#modalbayar').modal('show');
       }
  });

  
   $("#bdatalainnya").click(function() {
       if($(this).attr('role')) {
       $('#modaldatalainnya').modal('show');
       }
  });
   $("#bdatasurgery").click(function() {
       if($(this).attr('role')) {
       $('#modaldatasurgery').modal('show');
       }
  });
  
  
  $("#badd").click(function() {
    _clearForm();
    //_addRow();
    _inputFormat();          
    _formState1();
  });

  $("#bedit").click(function() {
    if($('#id').val()=='') return;        
    _formState1();
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

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
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
        parent.window.$('#namaview').val('view_pos');
        parent.window._setcabang();
        parent.window._transaksidatatable('view_pos');
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    });   
  });  

  $("#bcanceltransaksi").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$(".loader-wrap").removeClass("d-none");        
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi Cancel");
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
        parent.window._transaksidatatable('view_pos_cancel' ); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
        return;
      } 
    });   
  });  
  
  

  $("#bapprovecanceltransaksi").click(function() {
    if($('#id').val()=='') return;    
    
    //appcanceltransaksi
     $('#modalpassword').on('shown.bs.modal', function(){  
                $('#jenispassword').val('appcanceltransaksi');  
                $('#username').val('');  $('#username').removeAttr('disabled');
                $('#password').val('');  $('#password').removeAttr('disabled');
                
   
            });      
              $('#modalpassword').modal('show'); 
    
    //_formState1();
  });

  $("#baddrow").click(function() {
    _addRow();
    _inputFormat();
    $("select[name^='item']").last().focus();        
  });

  $("#bcancel").click(function() {
    _clearForm();
   // _addRow();
    _inputFormat();
    _formState2();
  });

  $("#bsave").click(function() {  
     if ($('#dkkwalkin').val()=='')  
    {
     $('#modalDKK').modal('show');  
     return ;
    }
    if ($('#rekammedis').val()==''){ 
         $('#modalcatatanplanning').on('shown.bs.modal', function(){  
             $('#untuksave').val(1);
            $('#rekammedis').focus();   
        });  
        $('#modalcatatanplanning').modal('show');  
        return ;
     }
    
    if (_IsValid()===0) return;
    
    if($('#id').val()!='')
    {
       if ($('#alasanedit').val()=='') {
            $('#modalalasanedit').on('shown.bs.modal', function(){  
                $('#alasanedit').focus(); 
            });    
            $('#modalalasanedit').modal('show'); 
            return ;
       }
       
       
    } 
    
    _saveData(); 
    
  });
  
   $("#bokmodalalasanedit").click(function() {  
    
     if ($('#alasanedit').val()==''){  
           $('#alasanedit').attr('data-title','Masukkan alasan edit');
          $('#alasanedit').tooltip('show');
          $('#alasanedit').focus();
            return ;
     }
     else { 
         $('#modalalasanedit').modal('hide'); 
      _saveData(); 
     } 
     
  });
  
  

  $("#bokpilihdkk").click(function() { 
    if (_IsValid()===0) return;
    if ($('#dkkwalkin').val()==''){ 
      $('#dkkwalkin').attr('data-title','Pilih DKK !');
      $('#dkkwalkin').tooltip('show');
      $('#dkkwalkin').focus();
      return 0;
    }
    
    
    
     if ($('#rekammedis').val()==''){ 
         $('#modalcatatanplanning').on('shown.bs.modal', function(){  
             $('#untuksave').val(1);
            $('#rekammedis').focus();   
        });  
        $('#modalcatatanplanning').modal('show');  
     }
     else { 
      _saveData(); 
     }   
  });
  
  $("#bokmodalcatatanplanning").click(function() { 
      if ( $('#untuksave').val() ==1 )
      {  
          if (_IsValid()===0) return;
            _saveData(); 
          
      }
  
  });
  
 

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-pos_hp/${$("#id").val()}`)    
  });

  $('#pajak').on('change',function(e){
    _hitungTotal();
  });

  $('#chkpph22').on('click',function(e){
    _hitungTotal();
  });

  $('#id').on('change',function(){
    const idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });  

  $('#idkontak').on('change',function(){
    
       _AmbilDetailPasien();
      
  });  

  $('#kasjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#debitjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#kreditjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#transferjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#dpjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#merchantjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#voucherjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#piutangjumlah').on('change',function(){
       _hitungTotal(); 
  });  

  $('#surgerydppembayaran').on('change',function(){
       _hitungTotal(); 
  });   

  $('#idpaket').on('change',function(){  
     if($('#idpaket').val()=='')  return;  
     
     if($('#jumlahpaket').val()>1)  
     {
       $('#modalPaket').on('shown.bs.modal', function(){ 
       $('#nopaketnya').focus();   
         });   

        $('#modalPaket').modal('show');     
     }
     
     else
     {
       _AmbilDetailPaket();  
     }
     
     
      
  });  
  

  $('#idpromo').on('change',function(){
      if($('#idpromo').val()=='')  return;  
       _AmbilDetailPromo();
       
  });  
     
    $('#tgl').on('change',function(){
       if($('#id').val()!=='') return; 
      _set_nomor_ip($('#tgl').val()); 
  });   
  

  $('#medid').on('change',function(){
      if($('#medid').val()=='')  return;
       _AmbilDetailPRO(); 
  });  

  $('#webid').on('change',function(){ 
      if($('#webid').val()=='')  return;
       _AmbilDetailWEB(); 
  }); 
  
  $('#webidbelumiv').on('change',function(){ 
      if($('#webidbelumiv').val()=='')  return;
       _AmbilDetailWEB_blmiv(); 
  }); 
  
  
  $('#txtidbarang').on('change',function(){  
      if($('#txtidbarang').val()=='')  return;
       _AmbilDataItem(); 
  }); 
  
  $('#iddiskonvocer').on('change',function(){  
      if($('#iddiskonvocer').val()=='')  return;
       _ambildetailvocer(); 
  }); 
  
  
  
  
   
   var _CariBarang = () => { 
    
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }   
          
           
      $.ajax({ 
        "url"    : base_url+"Modal/cari_barang", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Produk");
          parent.window.$("#modaltrigger").val("iframe-page-pos_2");
          parent.window.$('#coltrigger').val('pos');                
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal cari Produk...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');  
          
           
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak(''); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
             
          }, 500);
          return;
        } 
      });
      
      
       
   }
   
   
 
   

  
  $("#btambahitem2").click(function() {   
      
    if($(this).attr('role')) {     
        _CariBarang(); 
    }    
  });   
  
  $("#btambahitem").click(function() {   
    if($(this).attr('role')) {       
        _CariBarang(); 
    }   
  }); 
  
  
    var _CariKupon = () => { 
    
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }   
          
           
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kupon", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kupon");
          parent.window.$("#modaltrigger").val("iframe-page-pos_2");
          parent.window.$('#coltrigger').val('pos');                
        },         
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal cari Produk...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');  
          
           
          parent.window._lstkategorikontak();
          parent.window._pilihkategorikontak(''); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
             
          }, 500);
          return;
        } 
      });
      
      
       
   }
   
   
  $("#bkupon").click(function() {   
      
      //seleksi dulu
     
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_cek_kupon_otc", 
        "type"   : "POST", 
        "data"   :  {notransaksi: $("#nomor").val(),tanggal:$("#tgl").val(),idkontak:$("#idkontak").val()},
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){ 
          parent.window.toastr.error("error cek kupon otc..");   
          $("#loader-detil").addClass('d-none');          
          return 0;
        },
        "success"  : async function(result) {  
             
          var jumlah=result.data[0]['jumlah'] ; 
          
          if(jumlah==0)
          { 
                 parent.window.toastr.success("Pasien tidak mendapatkan kupon OTC.");  
            
            
          }
          else
          {
             parent.window.toastr.success("Pasien mendapatkan kupon OTC.");   
                  
                    _CariKupon();  
             
          }
          
          
          
          $("#loader-detil").addClass('d-none');                 
        return;    
        } 
      });
      
      
      
      
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_cek_kupon_tindakan", 
        "type"   : "POST", 
        "data"   :  {notransaksi: $("#nomor").val(),tanggal:$("#tgl").val(),idkontak:$("#idkontak").val()},
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){ 
          parent.window.toastr.error("error cek kupon otc..");   
          $("#loader-detil").addClass('d-none');          
          return 0;
        },
        "success"  : async function(result) {  
             
          var jumlah=result.data[0]['jumlah'] ; 
          
          if(jumlah==0)
          { 
                 parent.window.toastr.success("Pasien tidak mendapatkan kupon TINDAKAN.");  
            
            
          }
          else
          {
             parent.window.toastr.success("Pasien mendapatkan kupon TINDAKAN.");   
                  
                    _CariKupon();  
             
          }
          
          
          
          $("#loader-detil").addClass('d-none');                 
        return;    
        } 
      });
      
      
      
      
      
      
      
   
    
    
  });  
   
  
  
  
  
  
  
 
  $("#bokpilihitem").click(function() {   
      
    
  });
  
  var _AmbilDataItem = () => { 
     
    if($('#txtidbarang').val()=='' || $('#txtidbarang').val()==null ){
      $('#btambahitem2').attr('data-title','Tidak ada yang dipilih !'); 
      return 0;
    }  
         
      
     let _iditem = $('#txtidbarang').val();  
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_item", 
        "type"   : "POST", 
        "data"   : "id="+_iditem,
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
           
        _addRow(); 
        _inputFormat(); 
        var _idx = $("select[name^='item']").length , wajibdokter=0, hasilcek=0; 
        _idx=_idx-1; 
         
     
         let satuan = $("<option selected='selected'></option>")
                        .val(result.data[0]['idsatuan'])
                        .text(result.data[0]['namasatuan']);   
          let kode = $("<option selected='selected'></option>")
                        .val(result.data[0]['iditem'])
                        .text(result.data[0]['namaitem']);         
          
          $("select[name^='item']").eq(_idx).append(kode).trigger('change');  
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change');  
          $("select[name^='satuan']").eq(_idx).prop('disabled',true); 
          $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual'].replace(".", ","));
          $("input[name^='qty']").eq(_idx).val(1);    
          $("input[name^='item_tipe2020']").eq(_idx).val(result.data[0]['kelompok2020']);  
          $("input[name^='jenisitem']").eq(_idx).val(result.data[0]['jenisitem']);  
          $("input[name^='wajibdokter']").eq(_idx).val(result.data[0]['wajibdokter']);   
          $("input[name^='bisaeditharga']").eq(_idx).val(result.data[0]['bisaeditharga']);  
          $("input[name^='cetak']").eq(_idx).val(result.data[0]['cetak']);  
           
          
          if ( $('#statusmember').val()==1 )
          {
              $("input[name^='dis1']").eq(_idx).val(result.data[0]['diskon'].replace(".", ","));
              if(result.data[0]['diskon']==0) $("input[name^='dis1']").eq(_idx).attr('placeholder','0,00');   
              
          }
          else if (result.data[0]['diskonweb']!=0)
              {
                   $("input[name^='dis1']").eq(_idx).val(result.data[0]['diskonweb'].replace(".", ","));
                  
              }
          
           
          if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00'); 
          
          
          //cek dskonkaryawan jka jenisnya = 0 = produk
           if ( $('#kontaktipe').val()==4 && result.data[0]['jenisitem']==0 )  
           {
               hasilcek = await _CekDiskonKaryawan(_idx) ;  
                //if (hasilcek==9)   
                //  { let xdata = await CekDiskonKaryawan_35 (_idx);  }
           }
          //end cek dskonkaryawan 
          
         
          
           //$('#keberapa').val(_idx);
            //cek kelengkapan dokter dll
          let data = await _cekbisaeditharga(); 
          let jumlah = await _hitungJumlahDetil(_idx);  
          let subtotal = await _hitungsubtotal();  
          _hitungTotal();   
         
          _TampilkanNamaItem(_idx);
          
          $('#pilihanitem').val('').change();   
          
          _CekKelengkapanTindakan(); 
          
          $('#txtidbarang').val(''); 
          return;                    
      } 
      });
  
  }
  
   var _CekDiskonKaryawan = (_Baris) => {
       
      
    if($('#idkontak').val()=='' || $('#idkontak').val()==null ){
      $('#kontak').attr('data-title','Data Pasien kosong !'); 
      return 0;
    }  
         
      
     let _idkontak = $('#idkontak').val();  
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_cek_diskon_karyawan", 
        "type"   : "POST", 
        "data"   : "id="+_idkontak,
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){ 
          parent.window.toastr.error("error cek diskon karyawan..");   
          $("#loader-detil").addClass('d-none');          
          return 0;
        },
        "success"  : async function(result) {  
             
          var jumlah=result.data[0]['jumlah'] ; 
          
          if(jumlah==0)
          { 
                 const totalbaris = $(".item").length;
                    for(let i=0;i<totalbaris;i++){  
                        if( $("input[name^='jenisitem']").eq(i).val()==0 &&    $("input[name^='dis1']").eq(i).val()==100 && _Baris != i )  jumlah++;  
                    }
               
               
            if(jumlah==0)   
            {
             $("input[name^='dis1']").eq(_Baris).val(100); 
              _hitungJumlahDetil(_Baris);    
                $("input[name^='qty']").eq(_Baris).attr('disabled','disabled');   
              parent.window.toastr.success("Karyawan mendapat diskon 100 %  produk.");  
            }
            else
            {_CekDiskonKaryawan_35(_Baris) ;}
            
            
          }
          else
          {
             //alert('Karyawan sudah pernah dapat free product, diskon akan diberikan 35 % dengan resep skin care.')
             //parent.window.Swal.fire({  title: `Karyawan sudah pernah dapat free product, diskon akan diberikan 35 % dengan resep skin care.`   }) 
             _CekDiskonKaryawan_35(_Baris) ;
             
          }
          
          let data = await _cekbisaeditharga(); 
          let jumlah2 = await _hitungJumlahDetil(_Baris);  
          let subtotal = await _hitungsubtotal();  
          _hitungTotal();   
          
          $("#loader-detil").addClass('d-none');                 
        return;    
        } 
      });
  
  }
  
  var _CekDiskonKaryawan_35 = (_Baris) => {
        
    if($('#idkontak').val()=='' || $('#idkontak').val()==null ){
      $('#kontak').attr('data-title','Data Pasien kosong !'); 
      return 0;
    }  
         
      
     let _idkontak = $('#idkontak').val();  
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_cek_diskon_karyawan_35", 
        "type"   : "POST", 
        "data"   : "id="+_idkontak,
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){ 
          parent.window.toastr.error("error cek diskon karyawan..");   
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) {  
             
          var jumlah=result.data[0]['jumlah'] ; 
          
          if(jumlah<2)
          { 
                 const totalbaris = $(".item").length;
                    for(let i=0;i<totalbaris;i++){  
                        if( $("input[name^='jenisitem']").eq(i).val()==0 &&    $("input[name^='dis1']").eq(i).val()==35 && _Baris != i )  jumlah++;  
                    }
               
               
            if(jumlah<2)   
            {
             $("input[name^='dis1']").eq(_Baris).val(35); 
              _hitungJumlahDetil(_Baris);    
                $("input[name^='qty']").eq(_Baris).attr('disabled','disabled');   
              parent.window.toastr.success("Karyawan mendapat diskon 35 %  produk.");  
            }
            else
              {
                 //alert('Karyawan sudah pernah dapat free product, diskon akan diberikan 35 % dengan resep skin care.')
                 parent.window.Swal.fire({  title: `Karyawan sudah beli product = ` + jumlah + ` buah, tidak dapat discount lagi.`   })  
              }
            
          }
            else
              {
                 //alert('Karyawan sudah pernah dapat free product, diskon akan diberikan 35 % dengan resep skin care.')
                 parent.window.Swal.fire({  title: `Karyawan sudah beli product = ` + jumlah + ` buah, tidak dapat discount lagi.`   })  
              }
          
            let data = await _cekbisaeditharga(); 
          let jumlah2 = await _hitungJumlahDetil(_Baris);  
          let subtotal = await _hitungsubtotal();  
          _hitungTotal();   
        
          $("#loader-detil").addClass('d-none');                 
        return;    
        } 
      });
  
  }
       
       
       
       
    var _CekKelengkapanTindakan = () => { 
        
        const totalbaris = $(".item").length;
        for(let i=0;i<totalbaris;i++){  
            //alert('baris ke'+i);
            if($("input[name^='wajibdokter']").eq(i).val()=="1") { 
                if($("select[name^='operator']").eq(i).val()=='' || $("select[name^='operator']").eq(i).val()==null){   
                   
                        $('#keberapa').val(i);
                        $('#modaloperator').on('shown.bs.modal', function(){ 
                           $('#labelmodaloperator').html('Pilih Operator ' + $("select[name^='item']").eq(i).text());   
                           $('#pilihanoperator').focus();   
                           
                        }); 
                        $('#modaloperator').modal('show');   
                        return 0 ;
                        break ;
                } 
                if($("select[name^='dokter']").eq(i).val()=='' || $("select[name^='dokter']").eq(i).val()==null){   
                    //alert($("select[name^='dokter']").eq(i).val());
                        $('#keberapa').val(i);
                        $('#modaldokter').on('shown.bs.modal', function(){ 
                           $('#labelmodaldokter').html('Pilih Dokter ' + $("select[name^='item']").eq(i).text());   
                          $('#pilihandokter').focus();   
                           
                        }); 
                         
                        $('#modaldokter').modal('show');    
                        return 0 ;
                        break ;
                } 
                if($("input[name^='noref']").eq(i).val()=='' || $("input[name^='noref']").eq(i).val()==null){    
                    
                        $('#keberapa').val(i);
                        $('#modalnoref').on('shown.bs.modal', function(){ 
                            $('#labelmodalnoref').html('Masukkan No Ref ' + $("select[name^='item']").eq(i).text()); 
                           $('#norefnya').focus();   
                        }); 
                       $('#modalnoref').modal('show');    
                        return 0 ;  
                        break ;
                } 
                if($("input[name^='noic']").eq(i).val()=='' || $("input[name^='noic']").eq(i).val()==null){   
                    
                        $('#keberapa').val(i);
                        $('#modalnoic').on('shown.bs.modal', function(){ 
                            $('#labelmodalnoic').html('Masukkan No IC ' + $("select[name^='item']").eq(i).text()); 
                            $('#noicnya').focus();   
                        }); 
                       $('#modalnoic').modal('show');   
                        return 0 ;   
                        break ;
                }     
            } 
        }    
        return 1 ;
    }

  
 $("#bokpilihdokter").click(function() {    
    if($('#pilihandokter').val()==''){
     alert ('Tidak ada yang dipilih !'); 
      return 0;
    }  
     var _iddokter = $('#pilihandokter').val();  
     var _namadokter = ($("select[name^='pilihandokter'] option:selected").text());
        var _idx = $('#keberapa').val();    
        
        var   _dokter = $("<option selected='selected'></option>").val(_iddokter).text(_namadokter) ;
        $("select[name^='dokter']").eq(_idx).append(_dokter).trigger('change');  
        
        $('#pilihandokter').val(''); $('#pilihandokter').text('');
        _CekKelengkapanTindakan();
        
          return;  
  });
  
  
 $("#bokpilihoperator").click(function() {    
    if($('#pilihanoperator').val()==''){
     alert ('Tidak ada yang dipilih !'); 
      return 0;
    }  
     
      var _namadokter = ($("select[name^='pilihanoperator'] option:selected").text());  
      
        var _idx = $('#keberapa').val();    
        
        var   _dokter = $("<option selected='selected'></option>").val($('#pilihanoperator').val()).text(_namadokter) ;
        $("select[name^='operator']").eq(_idx).append(_dokter).trigger('change');   
              
        $('#pilihanoperator').val(''); $('#pilihanoperator').text('');
        _CekKelengkapanTindakan();
          return;  
  });
  
  
 $("#boknoref").click(function() {    
        if($('#norefnya').val()==''){
        alert ('Masukkan No Ref !'); 
        return 0; 
        }
        var _idx = $('#keberapa').val();   
         
        $("input[name^='noref']").eq(_idx).val($('#norefnya').val());  
        $('#norefnya').val('');
        _CekKelengkapanTindakan();
        return;  
  });
  
  
 $("#boknoic").click(function() {    
        if($('#noicnya').val()==''){
        alert ('Masukkan No IC !'); 
        return 0; 
        }
        var _idx = $('#keberapa').val();  
         
        $("input[name^='noic']").eq(_idx).val($('#noicnya').val());  
        $('#noicnya').val('');
        
        _CekKelengkapanTindakan() ;
        return;  
  });
      
    

   
  $(this).on("click", "input[name^='bcekharga']", async function(){ 
      alert('tesinput');
     
     // let _idx = $(this).index('.bcekharga');  
     //  _cekbisaeditharga(_idx) ; 
  });
  
  $(this).on("click", "button[name^='bcekharga']", async function(){ 
      alert('tesbutton');
     
      l 
  });
  

  
  
  $(this).on("keyup", "input[name^='qty']", async function(){ 
      let _idx = $(this).index('.qty');  
      let data = await _TampilkanNamaItem(_idx); 
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

  $(this).on("keyup", "input[name^='dis1']", async function(){
      let _idx = $(this).index('.dis1');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });  

  $(this).on("keyup", "input[name^='dis2']", async function(){
      let _idx = $(this).index('.dis2');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });  
  
    $(this).on("keyup", "input[name^='dis2']", async function(){
      let _idx = $(this).index('.dis2');
      let jumlah = await _hitungJumlahDetil(_idx);
      let subtotal = await _hitungsubtotal();
      _hitungTotal();
  });  

 

  $(this).on("select2:select", "select[name^='item']", function(){
      if($(this).val()=="" || $(this).val()==null) return; 
      let _idx = $(this).index('.item'); 
      
      _SetDataBarang(_idx,$(this).val());

     
  });
  
   
  
  
  var _SetDataBarang = (_idx,_idbarang) => { 
      
        $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_item", 
        "type"   : "POST", 
        "data"   : "id="+_idbarang+"&kontak="+$("#idkontak").val(),
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
          
          $("select[name^='satuan']").eq(_idx).append(satuan).trigger('change');  
          $("select[name^='satuan']").eq(_idx).prop('disabled',true); 
          $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);   
          //$("input[name^='qty']").eq(_idx).val(1);    
          $("input[name^='item_tipe2020']").eq(_idx).val(result.data[0]['kelompok2020']);  
          $("input[name^='wajibdokter']").eq(_idx).val(result.data[0]['wajibdokter']);
          $("input[name^='bisaeditharga']").eq(_idx).val(result.data[0]['bisaeditharga']);
          
          
          
          if ( $('#kontaktipe').val()==12 )
          {
              $("input[name^='dis1']").eq(_idx).val(result.data[0]['diskon']); 
          }      
          if(result.data[0]['diskon']==0) $("input[name^='dis1']").eq(_idx).attr('placeholder','0,00');    
          if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');  
          
          
          
          
           $("#loader-detil").addClass('d-none');  
          let data = await _cekbisaeditharga();

          let jumlah = await _hitungJumlahDetil(_idx);

          let subtotal = await _hitungsubtotal();
          _hitungTotal();
          
          return;                    
      } 
      });
      
      
  }
  
 


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
  Component_Select2_Item('.item',`${base_url}Select_Master/view_item`);  
  Component_Select2_Item('.item2',`${base_url}Select_Master/view_item`);  
  Component_Select2('.dokter',`${base_url}Select_Master/view_dokter`);  
  Component_Select2('.dokter2',`${base_url}Select_Master/view_dokter`);  
  Component_Select2('.operator',`${base_url}Select_Master/view_karyawan`);  
  Component_Select2('.operator2',`${base_url}Select_Master/view_karyawan`);  
  Component_Select2('.aos',`${base_url}Select_Master/view_karyawan`);  
  Component_Select2('.recom',`${base_url}Select_Master/view_karyawan`);  
  Component_Select2('.referal',`${base_url}Select_Master/view_karyawan`);  
  Component_Select2('.paket',`${base_url}Select_Master/view_paket`); 
  Component_Select2('.promo',`${base_url}Select_Master/view_promo`); 
  Component_Select2('.bank',`${base_url}Select_Master/view_bank2`); 
  Component_Select2('.merchant',`${base_url}Select_Master/view_merchant`); 
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
  
  $('#pasientipe').html("");  
  $('#pasienid').html("");  
  $('#pasienalamat').html("");  
  $('#pasiennohp').html("");    
  
   $('#idsalesman').val(parent.window.$('#idkaryawan').val());
  $('#salesman').val(parent.window.$('#namakaryawan').val());
  
   $('#bstatuskartumember').html('') ; 
 // $('#namakontak').html(parent.window.$('#namakaryawan').val());
 
    document.getElementById("optbawakartu").checked = false;
    document.getElementById("opttidakbawakartu").checked = false; 
    
    
  $('#cabang').val($('#cabanguser').val());
  
  
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
    $('#namakontak').focus();        
  },300);
   
  $('.kuncitext').attr('disabled','disabled'); 
  $('.kuncicombo').prop('disabled',true);   
  
  $('.buttontambahan').attr('data-dismiss','modal');
  $('.buttontambahan').attr('data-toggle','modal');
  $('.buttontambahan').attr('role','button');
  
 
 if ($('#bisatambah').val()==0)  $('#bsave').addClass('disabled'); 
 
 if ($('#bisaedit').val()==0)  $('#bkirimulangpointteman').addClass('disabled');   
 if ($('#bisaedit').val()==0)  $('#bkirimulangpointteman').addClass('sm-none'); 
     
 // $('#totalsisa').background-color('white');    
 document.getElementById("totalsisa").style.backgroundColor = "white"; 
 document.getElementById("totalbayar").style.backgroundColor = "white"; 
 document.getElementById("tsubtotal").style.backgroundColor = "white";  
 document.getElementById("nomor").style.backgroundColor = "white";   
 document.getElementById("tgl").style.backgroundColor = "white";   
 document.getElementById("namakontak").style.backgroundColor = "white";   
}

var _formState2 = () => {
  $('.btn-step2').removeClass('disabled');
  $('.btn-step1').addClass('disabled'); 
  $('#baddrow').attr('disabled','disabled');     
  $(':input').not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
  $(':input').not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff"); 
  $('.input-group-append').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
  $('.buttontambahan').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
  
   
 if ($('#bisahapus').val()==0)  $('#bdelete').addClass('disabled');
 if ($('#bisaedit').val()==0)  $('#bedit').addClass('disabled');
 if ($('#bisaprint').val()==0)  $('#bprint').addClass('disabled');
 if ($('#bisaedit').val()==0)  $('#bkirimulangpointteman').addClass('disabled');   
 if ($('#bisaedit').val()==0)  $('#bkirimulangpointteman').addClass('sm-none');   
   
} 
  
   
     
      $(this).on("click", "button[name^='bdetailitem']", async function(){
      let _idbaris = $(this).index('.bdetailitem');
      //let jumlah = await _tampilkancollapse(_idbaris);  
       $("div[name^='collapsrow']").eq(_idbaris).collapse('toggle');  
        });  


      $(this).on("click", "button[name^='baksi']", async function(){
      let _idbaris = $(this).index('.baksi');
             alert('aksi untuk baris ke ' + _idbaris ) ;
        }); 
        
        $(this).on("click", "button[name^='binputdesimal']", async function(){
            let _idbaris = $(this).index('.binputdesimal') ;  
             //$("input[name^='qty']").eq(_idbaris).removeClass('numeric'); 
             //$("input[name^='qty']").eq(_idbaris).addClass('qty form-control form-control-sm'); 
             //$("input[name^='qty']").eq(_idbaris).val('');
             
             
             Component_Inputmask_Numeric_Flexible('.qty', 12);  
             
             alert('Sukses' + _idbaris);
        });
        
        
        
        
      $(this).on("click", "button[name^='bdiskonvocher']", async function(){
            let _idbaris = $(this).index('.bdiskonvocher'), novocer=''; 
             $('#iddiskonvocer').val('') ;
             $('#keberapa').val(_idbaris); 
             //novocer = $("input[name^='novoucherwebdetil']").eq(_idbaris).val();
             
           //$('#modaldiskonvocer').on('shown.bs.modal', function(){ 
           //     $('#keberapa').val(_idbaris); 
           //     $('#novoucherweb').val(novocer);  
           //     $('#novoucherweb').focus(); 
                //$("input[name^='novoucherwebdetil']").eq(_idbaris).val(novocer);  
           // });      
           //   $('#modaldiskonvocer').modal('show'); 
              
           //alert('Pastikan jumlah produk sudah diinput sesuai jenisnya.') 
           
            parent.window.Swal.fire({  title: `Pastikan jumlah produk sudah diinput sesuai jenisnya.`   })
 
          
                    
                    $.ajax({ 
                      "url"    : base_url+"Modal/cari_diskonvocer", 
                      "type"   : "POST", 
                      "dataType" : "html",
                      "beforeSend": function(){
                        parent.window.$(".loader-wrap").removeClass("d-none");        
                        parent.window.$(".modal").modal("show");                  
                        parent.window.$(".modal-title").html("Cari Diskon Voucher");
                        parent.window.$("#modaltrigger").val("iframe-page-pos_2");                              
                      },       
                      "error": function(){
                        parent.window.$(".loader-wrap").addClass("d-none");
                        console.log('error menampilkan modal cari diskon voucher...');
                        return;
                      },
                      "success": function(result) {
                        parent.window.$(".main-modal-body").html(result);  
                        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');     
                        parent.window.$('#idkontak').val($('#idkontak').val());         
                        parent.window._transaksidatatable('view_pos');   
                          setTimeout(function (){
                               parent.window.$('#modal input').focus();
                          }, 500);
                        return;
                      } 
                    });   
              
        });  
        
        
        
        
var _ambildetailvocer = () => {
     
    
    let _idx = $('#keberapa').val(),
        _idvoucher = $('#iddiskonvocer').val() ;  
         
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/getdata_diskonvocer", 
        "type"   : "POST",  
        "data"   : "idvoucher="+_idvoucher,
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
          console.error('error ambil diskon voucher...');
          //console.error(xhr.responseText);
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) { 
              
              if (typeof result.pesan !== 'undefined') {
                parent.window.toastr.error(result.pesan);
                parent.window.$('.loader-wrap').addClass('d-none');                  
                return;
              } else if (result.data.length == 0) {
                parent.window.toastr.error('Data voucher tidak ditemukan');
                parent.window.$('.loader-wrap').addClass('d-none');                  
                return;
              }  else  
              {
                  
                let rows=0;  
                    _addRow_vocer();
                    rows = $("input[name^='vno']").length ;  
                    rows=rows-1; 
                    
                let vno='',vnilai=0, vitem = '',vbaris=0,vuntuk1bon=0,vitem2='',vpersentase=0,vnilai2=0,vfreeitem='',vid='', namaitem='';
                let xharga=0,xjenisitem=0, xdiskonpersen=0, xkel2020='';
            
                    vno=(result.data[0]['nomor']);  
                    vnilai=result.data[0]['nilai'].replace(".", ","); 
                    vitem=(result.data[0]['item']);  
                    vbaris=(_idx+1);  
                    vuntuk1bon=(result.data[0]['v1transaksi']);  
                    vitem2=(result.data[0]['vitem2']);   
                    vpersentase=(result.data[0]['rupiah']);   
                    vnilai2=result.data[0]['vnilai2'].replace(".", ",");  
                    vfreeitem=(result.data[0]['ikode']);   
                    vid=(result.data[0]['id']);
                    
                    
            
                    const totalbaris = $(".item").length;
              
                    if (vuntuk1bon==1)  // jik jenis voucher untuk 1 bon jenis produk
                    { 
                       
                            for(let i=0;i<totalbaris;i++){ 
                                
                                xjenisitem=$("input[name^='wajibdokter']").eq(i).val(); 
                                 xkel2020=$("input[name^='item_tipe2020']").eq(i).val();  
                                       
                                if (  xkel2020==5   )  //item jeninya skin care & drug
                                 { 
                                        xharga=Number($("input[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.')); 
                                        
                                        if (vpersentase==0)    //jjika diskon persen
                                        {
                                            if (vnilai>0) $("input[name^='dis1']").eq(i).val(vnilai);   
                                            if (vnilai2>0) $("input[name^='dis2']").eq(i).val(vnilai2); 
                                            let jumlah = await _hitungJumlahDetil(i);
                                            let subtotal = await _hitungsubtotal();
                                            _hitungTotal();
                               
                                        }
                                        else  //jjika diskon rupiah
                                        {
                                             
                                            if (vnilai>0) 
                                            {
                                                xdiskonpersen=vnilai/xharga*100;
                                                $("input[name^='dis1']").eq(i).val(xdiskonpersen.toString().replace('.',',')   );  
                                            let jumlah = await _hitungJumlahDetil(i);
                                            let subtotal = await _hitungsubtotal();
                                            _hitungTotal();
                                            }
                                        }
                                 
                                 }
                                
                                
                            }
                        
                     parent.window.toastr.success("Voucher untuk jenis produk" );    
                    }
                    
                    else if (vuntuk1bon==2)  // jik jenis voucher untuk 1 bon terbatas untuk 
                    {
                        
                        //               If IG.TextMatrix(j, 11) = "0" Or (InStr(1, IG.TextMatrix(j, 0), "FACIAL") > 0) Or (InStr(1, IG.TextMatrix(j, 0), "TOTOK") > 0) Or (InStr(1, IG.TextMatrix(j, 0), "MICRODERMABRASI") > 0) Or (InStr(1, IG.TextMatrix(j, 0), "VITALION") > 0) Or (InStr(1, IG.TextMatrix(j, 0), "BIO LITE") > 0) Or (InStr(1, IG.TextMatrix(j, 0), IGV2.TextMatrix(i, 2)) > 0) Or (InStr(1, IG.TextMatrix(j, 0), IGV2.TextMatrix(i, 5)) > 0) Then                                    'kolom 11 = jenis persediaan = stok = 0
                        
                        // 1 bon terbatas adalah untuk produk dan semua tidanakn
 
                                for(let i=0;i<totalbaris;i++){ 
                                    
                                    namaitem=$("select[name^='item']").eq(i).text(); 
                                    namaitem=namaitem.toUpperCase();
                                    xjenisitem=$("input[name^='wajibdokter']").eq(i).val();  
                                     
                                            xharga=Number($("input[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.')); 
                                            
                                            if (vpersentase==0)    //jjika diskon persen
                                            {
                                                if (vnilai>0) $("input[name^='dis1']").eq(i).val(vnilai);   
                                                if (vnilai2>0) $("input[name^='dis2']").eq(i).val(vnilai2); 
                                                let jumlah = await _hitungJumlahDetil(i);
                                                let subtotal = await _hitungsubtotal();
                                                _hitungTotal();
                                   
                                            }
                                            else  //jjika diskon rupiah
                                            {
                                                 
                                                if (vnilai>0) 
                                                { 
                                                xdiskonpersen=vnilai/xharga*100;
                                                $("input[name^='dis1']").eq(i).val(xdiskonpersen.toString().replace('.',',')   );  
                                                let jumlah = await _hitungJumlahDetil(i);
                                                let subtotal = await _hitungsubtotal();
                                                _hitungTotal();
                                                }
                                            } 
                                } 
                    
                    parent.window.toastr.success("Voucher untuk semua item" );      
                        
                    }
                    
                     else    // jik jenis voucher untuk item saja
                    {
                        
                               if ( vitem!='' || vitem2!=''   )  //jika item 1 dan item 2 ada isinya
                               {
                                    for(let i=0;i<totalbaris;i++){ 
                                        
                                        namaitem=$("select[name^='item']").eq(i).text(); 
                                        namaitem=namaitem.toUpperCase(); 
                                        if ( namaitem.search(vitem)>=0 || namaitem.search(vitem2)>=0   )  //jik 0= persediaan kalau 1 = tindakan
                                         { 
                                                xharga=Number($("input[name^='harga']").eq(i).val().split('.').join('').toString().replace(',','.')); 
                                                
                                                if (vpersentase==0)    //jjika diskon persen
                                                {
                                                    if (vnilai>0) $("input[name^='dis1']").eq(i).val(vnilai);   
                                                    if (vnilai2>0) $("input[name^='dis2']").eq(i).val(vnilai2); 
                                                    let jumlah = await _hitungJumlahDetil(i);
                                                    let subtotal = await _hitungsubtotal();
                                                    _hitungTotal(); 
                                                }
                                                else  //jjika diskon rupiah
                                                { 
                                                    if (vnilai>0) 
                                                    {
                                                        xdiskonpersen=vnilai/xharga*100;
                                                        $("input[name^='dis1']").eq(i).val(xdiskonpersen.toString().replace('.',',')   );  
                                                        let jumlah = await _hitungJumlahDetil(i);
                                                        let subtotal = await _hitungsubtotal();
                                                        _hitungTotal();
                                                    }
                                                } 
                                         } 
                                    } 
                               }
                               else
                               { 
                                       
                                    // jika item 1 dan 2 nya kosong, maka vouhcer untuk produk baris tersebut
                                    
                                    xjenisitem=$("input[name^='wajibdokter']").eq(_idx).val();  
                                    xharga=Number($("input[name^='harga']").eq(_idx).val().split('.').join('').toString().replace(',','.'));  
                                    
                                                if (vpersentase==0)    //jjika diskon persen
                                                {
                                                    if (vnilai>0) $("input[name^='dis1']").eq(_idx).val(vnilai);   
                                                    if (vnilai2>0) $("input[name^='dis2']").eq(_idx).val(vnilai2); 
                                                    let jumlah = await _hitungJumlahDetil(_idx);
                                                    let subtotal = await _hitungsubtotal();
                                                    _hitungTotal(); 
                                                }
                                                else  //jjika diskon rupiah
                                                { 
                                                   if (vnilai>0) 
                                                    {
                                                         xdiskonpersen=vnilai/xharga*100;
                                                        $("input[name^='dis1']").eq(_idx).val(xdiskonpersen.toString().replace('.',',')   );  
                                                        let jumlah = await _hitungJumlahDetil(_idx);
                                                        let subtotal = await _hitungsubtotal();
                                                        _hitungTotal();
                                                    }
                                                }  
                                     
                               } 
                    } 
                  
                    $("input[name^='vno']").eq(rows).val(result.data[0]['nomor']);  
                    $("input[name^='vnilai']").eq(rows).val(result.data[0]['nilai']);  
                    $("input[name^='vitem']").eq(rows).val(result.data[0]['item']);  
                    $("input[name^='vbaris']").eq(rows).val(_idx+1);  
                    $("input[name^='vuntuk1bon']").eq(rows).val(result.data[0]['v1transaksi']);  
                    $("input[name^='vitem2']").eq(rows).val(result.data[0]['vitem2']);   
                    $("input[name^='vpersentase']").eq(rows).val(result.data[0]['rupiah']);   
                    $("input[name^='vnilai2']").eq(rows).val(result.data[0]['vnilai2']);   
                    $("input[name^='vfreeitem']").eq(rows).val(result.data[0]['ikode']);   
                    $("input[name^='vid']").eq(rows).val(result.data[0]['id']);    
                    
                    
                   $('#iddiskonvocer').val(''); 
                    
                     parent.window.toastr.success("Sukses menarik data voucher" );   
                return; 
             }  
        }    
      
      });  
     
  }  
        
        
        

      $(this).on("click", "button[name^='bvoucherweb']", async function(){
             let _idbaris = $(this).index('.bvoucherweb'), novocer=''; 
             novocer = $("input[name^='novoucherwebdetil']").eq(_idbaris).val();
             
            $('#modalvoucherweb').on('shown.bs.modal', function(){ 
                $('#keberapa').val(_idbaris); 
                $('#novoucherweb').val(novocer);  
                $('#novoucherweb').focus(); 
                //$("input[name^='novoucherwebdetil']").eq(_idbaris).val(novocer);  
            });      
              $('#modalvoucherweb').modal('show'); 
        });  
         
         
 $("#bokmodalvoucherweb").click(function() { 
     
    let _idx = $('#keberapa').val(); 
     let _novocer = $('#novoucherweb').val(); 
     let _kode = '', _urutan=0, _kontak='';
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/getdata_voucherweb", 
        "type"   : "POST", 
        "data"   : "novocer="+$('#novoucherweb').val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
          console.error('error ambil voucher web...');
          //console.error(xhr.responseText);
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) { 
              
              if (typeof result.pesan !== 'undefined') {
                parent.window.toastr.error(result.pesan);
                parent.window.$('.loader-wrap').addClass('d-none');                  
                return;
              } else if (result.data.length == 0) {
                parent.window.toastr.error('Kode voucher tidak ada');
                parent.window.$('.loader-wrap').addClass('d-none');                  
                return;
              }  else  
              {
                  
                  
                   
                  _kontak = result.data[0]['kontak'] ;
               
              
                      if ( _kontak !== $('#idkontak').val()  ) { 
                                parent.window.toastr.error('Kode voucher milik pasien ' + result.data[0]['namapasien']);
                                return; 
                    } else if ( result.data[0]['tglexpired'] < result.data[0]['tglhariini'] ) { 
                        parent.window.toastr.error('Kode voucher untuk telah expired pada tanggal ' + result.data[0]['tglexpired']  );
                        return;
                        
                    //} else if ( result.data[0]['status'] == 1) { 
                   //     parent.window.toastr.error('Kode voucher telah digunakan');
                   //     return;
                        
                    }  else if ( result.data[0]['jenis'] == 1 && result.data[0]['iditem'] !==  $("input[name^='item']").eq(_idx).val() ) { 
                        parent.window.toastr.error('Kode voucher untuk ' + result.data[0]['kodeitem']  );
                        return;
                        
                    }  
                      
                        $("input[name^='novoucherwebdetil']").eq(_idx).val($('#novoucherweb').val()); 
                        $("input[name^='idvoucherwebdetil']").eq(_idx).val(result.data[0]['idvoucher']);   
                        $("input[name^='pointvoucherwebdetil']").eq(_idx).val(result.data[0]['jumlahpoint']); 
                        $("input[name^='dis1']").eq(_idx).val(result.data[0]['diskonpersen']); 
                        
                            let jumlah = await _hitungJumlahDetil(_idx);
                            let subtotal = await _hitungsubtotal();
                            _hitungTotal();
                                            
                         
                         $('#modalvoucherweb').modal('hide'); 
                        return; 
             }  
        }    
      
      });  
     
  });
  
  
  
      $(this).on("click", "button[name^='beditharga']", async function(){
             let _idbaris = $(this).index('.beditharga'), novocer=''; 
             
            // novocer = $("input[name^='novoucherwebdetil']").eq(_idbaris).val();
             
            $('#modalpassword').on('shown.bs.modal', function(){ 
                $('#keberapa').val(_idbaris); 
                $('#jenispassword').val('editharga');  
                $('#username').val(''); 
                $('#password').val(''); 
                //$('#novoucherweb').focus(); 
                //$("input[name^='novoucherwebdetil']").eq(_idbaris).val(novocer);  
            });      
              $('#modalpassword').modal('show'); 
        });  
 
 
        
 $("#bokmodalpassword").click(function() {  
     
    if($('#username').val()==''){
     alert ('Masukkan User Name !'); 
      return ;
    } else  if($('#password').val()==''){
     alert ('Masukkan User Name !'); 
      return ;
    }   else  if($('#jenispassword').val()==''){
     alert ('Jenis kosong, silahkan dibuka ulang form POS !'); 
      return ;
    }     
     
     
     
     
    let _idx = $('#keberapa').val(); 
     let _jenis = $('#jenispassword').val(); 
     let _kode = '', _urutan=0, _kontak='';
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/getdata_bukapassword", 
        "type"   : "POST",  
        "data"   : "jenis="+_jenis+"&username="+$("#username").val()+"&password="+$("#password").val(),
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend" : function(){
          $("#loader-detil").removeClass('d-none');
        },        
        "error"  : function(xhr,status,error){
          console.error('error ambil voucher web...');
          //console.error(xhr.responseText);
          $("#loader-detil").addClass('d-none');          
          return;
        },
        "success"  : async function(result) { 
              
              if (typeof result.pesan !== 'undefined') {
                parent.window.toastr.error(result.pesan);
                parent.window.$('.loader-wrap').addClass('d-none');                  
                return;
              } else if (result.data.length == 0) {
                parent.window.toastr.error('Password Salah');
                parent.window.$('.loader-wrap').addClass('d-none');                  
                return;
              }  else  
              {
                  //_kontak = result.data[0]['kontak'] ; 
                         
                         //$('#modalpassword').modal('hide'); 
                         
                        if ( _jenis=='editharga')
                        {
                           $("input[name^='harga']").eq(_idx).removeAttr('disabled');   
                           $('#modalpassword').modal('hide'); 
                           parent.window.toastr.success("Sukses membuka Kunci harga " + $("select[name^='spannama']").eq(_idx).text() );   
                        
                        }
                        else if ( _jenis=='appcanceltransaksi')
                        {
                           $("#appcanceltransaksi").val(1);  
                           _formState1();
                           $('#modalpassword').modal('hide'); 
                           parent.window.toastr.success("Sukses Approve Transaksi Cancel, Silahkan revisi Transaksi" );   
                           
                        }
                        return; 
             }  
        }    
      
      });  
     
  });  




var _addRow_vocer = () => {
  let  
        
         newrow  = "<tr><td > ";
        newrow += "<div class=\"form-group row my-0\">";   
        newrow += "<div class=\" col-1 px-3 \"><input name=\"vno[]\" type=\"tel\" class=\"vno form-control form-control-sm\"  autocomplete=\"off\"  ></div>"; 
        newrow += "<input name=\"vnilai[]\" type=\"tel\" class=\"vnilai col-1 form-control form-control-sm numeric kuncitext \" autocomplete=\"off\"  value=\"0\">";
        newrow += "<input name=\"vitem[]\" type=\"tel\" class=\"vitem col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "<input name=\"vbaris[]\" type=\"tel\" class=\"vbaris col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "<input name=\"vuntuk1bon[]\" type=\"tel\" class=\"vbaris col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "<input name=\"vitem2[]\" type=\"tel\" class=\"vitem2 col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "<input name=\"vpersentase[]\" type=\"tel\" class=\"vpersentase col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "<input name=\"vnilai2[]\" type=\"tel\" class=\"vnilai2 col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "<input name=\"vfreeitem[]\" type=\"tel\" class=\"vfreeitem col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "<input name=\"vid[]\" type=\"tel\" class=\"vid col-1 form-control form-control-sm\"  autocomplete=\"off\"   >"; 
        newrow += "</div>";
        
         newrow += "</td> <td>" ;  
         newrow += " <a href=\"javascript:void(0)\" class=\"btn btn-step1 buttontambahan \" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"> <i class=\"fas fa-trash text-md text-black\"></i></a>  ";   
        newrow += "</td> </tr>" ;  
      
  $('#tvoucher tbody').append(newrow); 
   
  
   
} 

//   newrow += "<button class=\"beditharga dropdown-item\" type=\"button\" id=\"beditharga\" name=\"beditharga\" >Edit Harga</button>";  

var _addRow = () => {
  let  
        
         newrow  = "<tr><td > ";
        newrow += "<div class=\"form-group row my-0\">";  
        newrow += "<span class=\"spannama col-3 col-form-label text-sm px-3 font-weight-normal\" id=\"spannama[]\" name=\"spannama[]\">Keterangan Produk</span>"; 
        newrow += "<input name=\"qty[]\" type=\"tel\" class=\"qty col-1 form-control form-control-sm\"  autocomplete=\"off\"  value=\"0\">"; 
        newrow += "<input name=\"harga[]\" type=\"tel\" class=\"harga col-2 form-control form-control-sm numeric kuncitext \" autocomplete=\"off\"  value=\"0\">";
        newrow += "<input name=\"bisaeditharga[]\" type=\"hidden\" value=\"0\">";  
        newrow += "<input name=\"dis1[]\" type=\"text\" class=\"dis1 col-1 form-control form-control-sm kuncitext\" value=\"0\">";
        newrow += "<input name=\"dis2[]\" type=\"text\" class=\"dis2 col-1  form-control form-control-sm kuncitext\" value=\"0\">";
        newrow += "<input name=\"subtotal[]\" type=\"text\" class=\"subtotal col-2 form-control form-control-sm numeric kuncitext\" autocomplete=\"off\" tabindex=\"-1\" value=\"0\">"; 
        
         
        newrow += "<div class=\"col-1\">";
        newrow += "<div class=\"btn-group\" role=\group\" aria-label=\"Basic example\">";
        newrow += "<button type=\"button\" id=\"bdetailitem\" name=\"bdetailitem\" class=\"bdetailitem btn btn-info btn-step1 text-sm btn-sm  buttontambahan \" role=\"button\" aria-expanded=\"false\">Detail</button> "; 
        newrow += " ";  
            newrow += "<div class=\"dropdown  \">";  
                newrow += "<button class=\"btn btn-warning text-sm btn-sm dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\">";  
                newrow += "<i class=\"fa fa-ellipsis-h\"></i></button>";  
                newrow += "<div class=\"dropdown-menu\">";   
                newrow += "<button class=\"bvoucherweb dropdown-item\" type=\"button\" id=\"bvoucherweb\" name=\"bvoucherweb\" >Voucher Web</button>"; 
                newrow += "<button class=\"bdiskonvocher dropdown-item\" type=\"button\" id=\"bdiskonvocher\" name=\"bdiskonvocher\" >Diskon Voucher</button>";  
                newrow += "<button class=\"binputdesimal dropdown-item\" type=\"button\" id=\"binputdesimal\" name=\"binputdesimal\" >Input Desimal</button>";  
                
                
                newrow += "</div>";  
            newrow += "</div>"; 
        newrow += "</div>";  
        newrow += "</div>"; 
 
        newrow += "</div>"; 
        
        
        newrow += "<div class=\"collapsrow collapse\" id=\"collapsrow[]\" name=\"collapsrow[]\" type=\"tel\" autocomplete=\"off\" >"; 
         newrow += "<div class=\"card card-body\">";   
                                    
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Produk</label>"; 
        newrow += "<div class=\"col-9\"><div class=\"input-group\" data-target-input=\"nearest\">";
        newrow += "<select name=\"item[]\" class=\"item form-control select2 form-control-sm kuncitext\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "<input name=\"item_tipe2020[]\" type=\"hidden\" class=\"form-control form-control-sm\">";
        newrow += "<input name=\"wajibdokter[]\" type=\"hidden\" class=\"form-control form-control-sm\">";
        newrow += "<input name=\"cetak[]\" type=\"hidden\" class=\"cetak form-control form-control-sm\" value=\"1\">";
        newrow += "<input name=\"jenisitem[]\" type=\"hidden\" class=\"form-control form-control-sm\">";
        newrow += "</div></div></div>";  
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal  \">Diskon</label>";
        newrow += "<div class=\"col-3\">";
        newrow += "<input name=\"diskon[]\" type=\"text\" class=\"diskon form-control form-control-sm kuncitext \" value=\"0\">";
        newrow += "<input name=\"tdiskondua[]\" type=\"hidden\" class=\"tdiskondua form-control form-control-sm kuncitext\" value=\"0\">";
        newrow += "</div>  ";
        newrow += "<label class=\"col-2 col-form-label text-sm px-1 font-weight-normal\">Satuan</label>";
        newrow += "<div class=\"col-4\">";
        newrow += "<select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select>";
        newrow += "</div>   ";
        newrow += "</div> ";
 
        
        
         newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Dokter</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"dokter[]\" class=\"dokter form-control select2 form-control-sm\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "</div></div>";  
        
         newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Operator</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"operator[]\" class=\"operator form-control select2 form-control-sm\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "</div></div>"; 
        
        newrow += "<div class=\"form-group row my-0\">"; 
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No Ref</label>"; 
        newrow += "<div class=\"col-3\">"; 
        newrow += "<input name=\"noref[]\" type=\"text\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"medidu[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"medidd[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">";
         newrow += "<input name=\"medidu_sudahbayar[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"medidd_sudahbayar[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"proidu[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"proidd[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
        newrow += "</div>"; 
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No IC</label>"; 
        newrow += "<div class=\"col-3\">"; 
        newrow += "<input name=\"noic[]\" type=\"text\" class=\"noic form-control form-control-sm\"  autocomplete=\"off\">"; 
        newrow += "</div>"; 
        newrow += "</div>"; 
        
         newrow += "<div class=\"form-group row my-0\">"; 
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No Paket</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"nopaketdetil[]\" type=\"text\" class=\"nopaketdetil form-control form-control-sm\"  autocomplete=\"off\">";  
         newrow += "</div>"; 
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Ke-</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"kedatanganke[]\" type=\"text\" class=\"kedatanganke form-control form-control-sm kuncitext\"  autocomplete=\"off\">"; 
         newrow += "</div> "; 
         newrow += "</div>"; 
          
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Nama Paket</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"paket[]\" class=\"paket form-control select2 form-control-sm kuncicombo\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
         newrow += "<input name=\"idpaketdetil[]\" type=\"hidden\" class=\"idpaketdetil form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"daripaket[]\" type=\"hidden\" class=\"daripaket form-control form-control-sm\"  autocomplete=\"off\">"; 
        newrow += "</div></div>"; 
        
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Nama Promo</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"promo[]\" class=\"promo form-control select2 form-control-sm kuncicombo\"  data-trigger=\"manual\" data-placement=\"auto\"></select>"; 
        newrow += "</div></div>"; 
        
         newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Referal</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"referal[]\" class=\"referal form-control select2 form-control-sm\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "</div></div>"; 
        
         newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">AOS</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"aos[]\" class=\"aos form-control select2 form-control-sm\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "</div></div>"; 
        
         newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Recom</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"recom[]\" class=\"recom form-control select2 form-control-sm\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "</div></div>";
        
        
         newrow += "<div class=\"form-group row my-0\">"; 
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No Voucher Web</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"novoucherwebdetil[]\" type=\"text\" class=\"novoucherwebdetil form-control form-control-sm kuncitext\"  autocomplete=\"off\">";  
         newrow += "<input name=\"idvoucherwebdetil[]\" type=\"hidden\" class=\"idvoucherwebdetil form-control form-control-sm kuncitext\"  autocomplete=\"off\">";  
         newrow += "</div>";  
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Jumlah Point</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"pointvoucherwebdetil[]\" type=\"text\" class=\"pointvoucherwebdetil form-control form-control-sm kuncitext\"  autocomplete=\"off\">"; 
         newrow += "</div> "; 
         newrow += "</div>"; 
         newrow += "</div>"; 
         
         
        
        newrow += "</div> ";
        newrow += "</div>" ;   
       
        
         newrow += "</td> <td>" ;  
         newrow += " <a href=\"javascript:void(0)\" class=\"btn btn-step1 buttontambahan \" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"> <i class=\"fas fa-trash text-md text-black\"></i></a>  ";   
        newrow += "</td> </tr>" ; 
      
 
      
      
  $('#tdetil tbody').append(newrow);
  
  $('.kuncitext').attr('disabled','disabled');  
  $('.kuncicombo').prop('disabled',true); 
  
  setwarnasubtotal();
  
   
}  
        
 function setwarnasubtotal() { 
	
	var spansubtotallist = document.getElementsByName('spansubtotal');  
	for (var i = 0, j = spansubtotallist.length; i < j; i++) {  
			spansubtotallist[i].style.backgroundColor = "transparent"; 
	} 
	 
	
}                 



/**/

/* CRUD
/* ========================================================================================== */
var _IsValid = () => {

    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    if ($('#idsalesman').val()==''){
      $('#salesman').attr('data-title','Karyawan harus diisi !');      
      $('#salesman').tooltip('show');
      $('#salesman').focus();
      return 0;
    } 
    //if ($('#dkkwalkin').val()==''){ 
    //    $('#modalDKK').modal('show');   
    //  return 0;
    //}
 

   
    const totalbaris = $(".item").length;
    
     for(let i=0;i<totalbaris;i++){  
        _hitungJumlahDetil(i);  
    } 
    
    for(let i=0;i<totalbaris;i++){ 
      if($("select[name^='item']").eq(i).val()=='' || $("select[name^='item']").eq(i).val()==null){
        $("select[name^='item']").eq(i).attr('data-title','Item harus diisi !');      
        $("select[name^='item']").eq(i).tooltip('show');      
        $("select[name^='item']").eq(i).focus();
        return 0;
      }
      if($("select[name^='paket']").eq(i).text()!='' && $("input[name^='daripaket']").eq(i).val()=='1'  && $("input[name^='nopaketdetil']").eq(i).val()==''){  
        //$("input[name^='nopaketdetil']").eq(i).attr('data-title','No Paket harus diisi !');      
        //$("input[name^='nopaketdetil']").eq(i).tooltip('show');      
       // $("input[name^='nopaketdetil']").eq(i).focus();
       
       const namapaket=$("select[name^='paket']").eq(i).text();
       
       parent.window.Swal.fire({
                      title: 'No Paket '+namapaket+' belum diisi !',
                      showDenyButton: false,
                      showCancelButton: false,
                      confirmButtonText: `Iya`,
                  })
                  
                  
        
        
        return 0;
      }
    } 
    
    let a = _CekKelengkapanTindakan() ;
    if (a==0) return 0;
    
    let totalsisa =  Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
    let totaltransaksi =  Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    if (totaltransaksi>0 ) {
        if (totalsisa<0 ) { 
            $('#modalbayar').on('shown.bs.modal', function(){  
                $("#caridp").click();  
            }); 
          $('#modalbayar').modal('show'); 
          
          return 0;
        } 
    }
    
   



    return 1;
}

var _kirimulangpointteman = () => {
  const id = $("#id").val();
  const idteman = $("#teman").val(); 
  var   tsubtotal = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.'));
 
         
    if($('#idkontak').val()==''){
      $('#teman').attr('data-title','Pasien Kosong !');
      $('#teman').tooltip('show'); 
      return 0;
    }  else if ( tsubtotal <500000)
      {
          $('#teman').attr('data-title','Transaksi harus diatas 500.000,- ');
          $('#teman').tooltip('show'); 
          return ;
      }
         

  $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/kirimulangpoint", 
    "type"   : "POST", 
    "data"   : "idteman="+idteman+"&id="+id,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Error : "+xhr.status+" "+error);      
      console.error(xhr.responseText);      
      return;
    },
    "success": function(result) {
      parent.window.$(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        parent.window.toastr.success("Kirim ulang point teman sukses");                  
        return;
      } else {        
        parent.window.toastr.error(result);      
        return;
      }
    } 
  })  
}

var _deleteData = () => {
  const id = $("#id").val();
  const nomor = $("#nomor").val();

  $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/deletedata", 
    "type"   : "POST", 
    "data"   : "id="+id+"&nomor="+nomor,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Error : "+xhr.status+" "+error);      
      console.error(xhr.responseText);      
      return;
    },
    "success": function(result) {
      parent.window.$(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        _clearForm();
       // _addRow();
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
        nomor = $("#nomor").val(),
        nomorlama = $("#nomorlama").val(),
        tgl = $("#tgl").val(),
        kontak = $("#idkontak").val(), kontaktipe = $("#kontaktipe").val(),
        karyawan = $("#idsalesman").val(),
        catatan = $("#catatan").val() ,
        debitno = $("#debitno").val() , debitnama = $("#debitnama").val() ,  debitbank = $("#debitbank").val() , debitjenis = $("#debitjenis").val(), debitbanklain = $("#debitbanklain").val(),
        kreditno = $("#kreditno").val() , kreditnama = $("#kreditnama").val() ,  kreditbank = $("#kreditbank").val() , kreditjenis = $("#kreditjenis").val(), kreditbanklain = $("#kreditbanklain").val(),
        transferno = $("#transferno").val() , transfernama = $("#transfernama").val() ,  transferbank = $("#transferbank").val() ,
        voucherid = $("#voucherid").val() , voucherno = $("#voucherno").val(),
        dpid = $("#dpid").val(), dpjenis = $("#dpjenis").val(),
        cabang = $("#cabang").val(), rekammedis= $("#rekammedis").val(),
        merchantno = $("#merchantno").val(), merchantjenis = $("#merchantjenis").val(),
        training = $("#training").val(),farmasi = $("#farmasi").val(),farmasiasisten = $("#farmasiasisten").val(),salesmarketing = $("#salesmarketing").val(),kliniklain = $("#kliniklain").val(),
        dkkwalkin = $("#dkkwalkin").val(),
        surgerydpidu= $("#surgerydpidu").val() ,
        kodetele= $("#kodetele").val(),reviewcatatan= $("#reviewcatatan").val()  ,
        medid= $("#medid").val(),idmedlib=$("#idmedlib").val() ,teman= $("#teman").val(), appcanceltransaksi= $("#appcanceltransaksi").val(),
        alasanedit= $("#alasanedit").val(), lmcidpro= $("#lmcidpro").val()
        
        ;

  let detil = [], detil_v = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value, 
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               dis1:Number($("input[name^='dis1']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               dis2:Number($("input[name^='dis2']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
               diskon2:Number($("input[name^='tdiskondua']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val() ,
               dokter:$("select[name^='dokter']").eq(index).val() ,
               operator:$("select[name^='operator']").eq(index).val() ,
               paket:$("select[name^='paket']").eq(index).val()       ,
               promo:$("select[name^='promo']").eq(index).val()       ,
               referal:$("select[name^='referal']").eq(index).val()       ,
               aos:$("select[name^='aos']").eq(index).val()               ,
               recom:$("select[name^='recom']").eq(index).val() , 
               noref:($("input[name^='noref']").eq(index).val()) , 
               noic:($("input[name^='noic']").eq(index).val())   , 
               nopaketdetil:($("input[name^='nopaketdetil']").eq(index).val())     , 
               kedatanganke:Number($("input[name^='kedatanganke']").eq(index).val())      , 
               idpaketdetil:($("input[name^='idpaketdetil']").eq(index).val())            , 
               daripaket:($("input[name^='daripaket']").eq(index).val())                   , 
               medidu:($("input[name^='medidu']").eq(index).val())                   , 
               medidd:($("input[name^='medidd']").eq(index).val())                   , 
               proidu:($("input[name^='proidu']").eq(index).val())                   , 
               proidd:($("input[name^='proidd']").eq(index).val())                    , 
               cetak:($("input[name^='cetak']").eq(index).val())                      , 
               idvoucherwebdetil:($("input[name^='idvoucherwebdetil']").eq(index).val())                      , 
               pointvoucherwebdetil:Number($("input[name^='pointvoucherwebdetil']").eq(index).val()) , 
               medidu_sudahbayar:($("input[name^='medidu_sudahbayar']").eq(index).val())                   , 
               medidd_sudahbayar:($("input[name^='medidd_sudahbayar']").eq(index).val())   
               
             });

  }); 

  detil = JSON.stringify(detil);  
  
  
  $("input[name^='vno']").each(function(index,element){  
      detil_v.push({
               vno:this.value, 
               vnilai:Number($("input[name^='vnilai']").eq(index).val().split('.').join('').toString().replace(',','.')),  
               vitem:($("input[name^='vitem']").eq(index).val()) , 
               vbaris:($("input[name^='vbaris']").eq(index).val()) , 
               vuntuk1bon:($("input[name^='vuntuk1bon']").eq(index).val()) , 
               vitem2:($("input[name^='vitem2']").eq(index).val()) , 
               vpersentase:($("input[name^='vpersentase']").eq(index).val()) ,  
               vnilai2:Number($("input[name^='vnilai2']").eq(index).val().split('.').join('').toString().replace(',','.')),  
               vfreeitem:($("input[name^='vfreeitem']").eq(index).val()) , 
               vid:($("input[name^='vid']").eq(index).val())  
             });

  }); 
  
   detil_v = JSON.stringify(detil_v);  

  var   tsubtotal = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.')),
        totalbayar = Number($("#totalbayar").val().split('.').join('').toString().replace(',','.')),
        totalsisa = Number($("#totalsisa").val().split('.').join('').toString().replace(',','.')),
        kasjumlah = Number($("#kasjumlah").val().split('.').join('').toString().replace(',','.')),
        debitjumlah = Number($("#debitjumlah").val().split('.').join('').toString().replace(',','.')),
        kreditjumlah = Number($("#kreditjumlah").val().split('.').join('').toString().replace(',','.')),
        transferjumlah = Number($("#transferjumlah").val().split('.').join('').toString().replace(',','.')),
        voucherjumlah = Number($("#voucherjumlah").val().split('.').join('').toString().replace(',','.'))  ,
        dpjumlah = Number($("#dpjumlah").val().split('.').join('').toString().replace(',','.'))   ,
        totaltanpadp = Number($("#totaltanpadp").val().split('.').join('').toString().replace(',','.'))  ,
        merchantjumlah = Number($("#merchantjumlah").val().split('.').join('').toString().replace(',','.'))  ,
        piutangjumlah = Number($("#piutangjumlah").val().split('.').join('').toString().replace(',','.'))  ,
        surgerydptotal = Number($("#surgerydptotal").val().split('.').join('').toString().replace(',','.'))  ,
        surgerydppembayaran = Number($("#surgerydppembayaran").val().split('.').join('').toString().replace(',','.'))  ,
        surgerydppiutang = Number($("#surgerydppiutang").val().split('.').join('').toString().replace(',','.'))   ,
        reviewnilai = Number($("#reviewnilai").val().split('.').join('').toString().replace(',','.'))  
        
        ;
       
        

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('nomor',nomor);
  rey.set('nomorlama',nomorlama);
  rey.set('tgl',tgl);
  rey.set('kontak',kontak);
  rey.set('kontaktipe',kontaktipe);
  rey.set('karyawan',karyawan); 
  rey.set('catatan',catatan);   
  
  rey.set('tsubtotal',tsubtotal);   
  rey.set('totalbayar',totalbayar);   
  rey.set('totalsisa',totalsisa);   
     
  rey.set('kasjumlah',kasjumlah);  
  
  rey.set('debitjumlah',debitjumlah);  
  rey.set('debitno',debitno);  
  rey.set('debitnama',debitnama);  
  rey.set('debitbank',debitbank);   
  rey.set('debitjenis',debitjenis); 
  rey.set('debitbanklain',debitbanklain); 
  
  rey.set('kreditjumlah',kreditjumlah);  
  rey.set('kreditno',kreditno);  
  rey.set('kreditnama',kreditnama);  
  rey.set('kreditbank',kreditbank);   
  rey.set('kreditjenis',kreditjenis); 
  rey.set('kreditbanklain',kreditbanklain); 
  
  rey.set('transferjumlah',transferjumlah);  
  rey.set('transferno',transferno);  
  rey.set('transfernama',transfernama);  
  rey.set('transferbank',transferbank);  
  
  rey.set('voucherid',voucherid);  
  rey.set('voucherno',voucherno);  
  rey.set('voucherjumlah',voucherjumlah); 
  
  rey.set('dpid',dpid);  
  rey.set('dpjenis',dpjenis);  
  rey.set('dpjumlah',dpjumlah);   
  
  rey.set('totaltanpadp',totaltanpadp);  
  rey.set('cabang',cabang);
  rey.set('rekammedis',rekammedis);  
  
  rey.set('merchantjenis',merchantjenis);  
  rey.set('merchantno',merchantno);  
  rey.set('merchantjumlah',merchantjumlah); 
  
  rey.set('training',training); 
  rey.set('farmasi',farmasi); 
  rey.set('farmasiasisten',farmasiasisten); 
  rey.set('salesmarketing',salesmarketing); 
  rey.set('kliniklain',kliniklain); 
  rey.set('dkkwalkin',dkkwalkin); 
  
  rey.set('piutangjumlah',piutangjumlah); 
  rey.set('surgerydpidu',surgerydpidu); 
  rey.set('surgerydptotal',surgerydptotal); 
  rey.set('surgerydppembayaran',surgerydppembayaran); 
  rey.set('surgerydppiutang',surgerydppiutang); 
  
  rey.set('reviewnilai',reviewnilai); 
  rey.set('reviewcatatan',reviewcatatan); 
  rey.set('kodetele',kodetele); 
  rey.set('medid',medid);  //pro
  rey.set('idmedlib',idmedlib); 
  rey.set('teman',teman); 
  rey.set('appcanceltransaksi',appcanceltransaksi); 
  rey.set('alasanedit',alasanedit); 
  rey.set('lmcidpro',lmcidpro);  //pro yg benar yg ini 
            
  rey.set('detil',detil);
            
  rey.set('detil_v',detil_v);



  $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/savedata", 
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
           //if(result.statusemail=='0') 
          // {parent.window.toastr.error("Gagal Email Karena "+ result.eroremail );  }
          // else
           //{parent.window.toastr.success("Email sukses dikirim"); } 
          
          if(id=='') _kirimemail(nomor);
          //{   _kirimemail(nomor);
                      
          //}
         // else
          //{ 
             // parent.window.Swal.fire({
             //         title: `Transaksi ada perubahan, apakah anda akan kirim ulang ke email pasien ?`,
              //        showDenyButton: false,
              //        showCancelButton: true,
              //        confirmButtonText: `Iya`,
              //    }).then((printing) => {
              //        if (printing.isConfirmed) {
              //          _kirimemail(nomor);
               //       } 
               //   })   
          //}
          
          parent.window.Swal.fire({
              title: `Anda ingin mencetak transaksi ini ?`,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Iya`,
          }).then((printing) => {
              if (printing.isConfirmed) {
                window.open(`${base_url}Laporan/preview/page-pos_hp/${result.nomor}`)
              }
              parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
              _clearForm();
              //_addRow();
              _inputFormat();
              _formState1();
              return;
          })
      }                  
    } 
  })
}

 $("#bkirimulangemail").click(function() {  
     
     _kirimemail($("#nomor").val());
 
}) ;


 
 
var _kirimemail = (noip) => {
    let xnoip = noip ;
 
  $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/kirim_email",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "noip="+xnoip,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){ 
      parent.window.toastr.error('Error : Gagal kirim email !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) { 
                
           if(result.message=='success') 
           {parent.window.toastr.success("Email sukses dikirim"); } 
           else
           {parent.window.toastr.error("Email tidak terkirim karena "+ result.errors );  } 
         
        parent.window.$(".loader-wrap").addClass("d-none");                                 
        return;
      
  } 
})   
    
}

var _getDataTransaksi = (id) => {

  if(id=='' || id==null) return;    

  $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      alert('Error : Gagal mengambil data transaksi pos !');
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

        var _debitbank = $("<option selected='selected'></option>").val(result.data[0]['debitbank']).text(result.data[0]['debitbank']),
        _debitjenis = $("<option selected='selected'></option>").val(result.data[0]['debitjenis']).text(result.data[0]['debitjenis']),
        _kreditbank = $("<option selected='selected'></option>").val(result.data[0]['kreditbank']).text(result.data[0]['kreditbank']),
        _kreditjenis = $("<option selected='selected'></option>").val(result.data[0]['kreditjenis']).text(result.data[0]['kreditjenis']) ,
        _transferbank = $("<option selected='selected'></option>").val(result.data[0]['transferbank']).text(result.data[0]['transferbank']),
        _dpjenis = $("<option selected='selected'></option>").val(result.data[0]['dpjenis']).text(result.data[0]['dpjenis']),
        _merchantjenis = $("<option selected='selected'></option>").val(result.data[0]['merchantjenis']).text(result.data[0]['merchantjenis']),
        _training = $("<option selected='selected'></option>").val(result.data[0]['idtraining']).text(result.data[0]['namatraining']),
        _farmasi = $("<option selected='selected'></option>").val(result.data[0]['idfarmasi']).text(result.data[0]['namafarmasi']),
        _farmasiasisten = $("<option selected='selected'></option>").val(result.data[0]['idfarmasiasisten']).text(result.data[0]['namafarmasiasisten']),
        _salesmarketing = $("<option selected='selected'></option>").val(result.data[0]['idsalesmarketing']).text(result.data[0]['namasalesmarketing']),
        _dkkwalkin = $("<option selected='selected'></option>").val(result.data[0]['iddkkwalkin']).text(result.data[0]['namadkkwalkin']),
        _kliniklain = $("<option selected='selected'></option>").val(result.data[0]['idkliniklain']).text(result.data[0]['namakliniklain']),
        _teman = $("<option selected='selected'></option>").val(result.data[0]['idteman']).text(result.data[0]['namateman'])
        
        ;
        
        
        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);          
        $('#nomorlama').val(result.data[0]['nomor']);
        
        $('#tgl').datepicker('setDate',result.data[0]['tanggal']);          
        $('#idkontak').val(result.data[0]['kontakid']);
        $('#kontak').val(result.data[0]['kontak']); 
        $('#idsalesman').val(result.data[0]['idkaryawan']);
        $('#salesman').val(result.data[0]['namakaryawan']); 
        $('#catatan').val(result.data[0]['catatan']);  
        
        $('#tsubtotal').val(result.data[0]['tsubtotal'].replace(".", ","));   
        $('#totalbayar').val(result.data[0]['totalbayar'].replace(".", ","));                
        $('#totalsisa').val(result.data[0]['totalsisa'].replace(".", ","));   
        
        $('#kasjumlah').val(result.data[0]['kasjumlah'].replace(".", ","));   
        
        $('#debitjumlah').val(result.data[0]['debitjumlah'].replace(".", ","));   
        $('#debitno').val(result.data[0]['debitno']);  
        $('#debitnama').val(result.data[0]['debitnama']);  
        $('#debitbanklain').val(result.data[0]['debitbanklain']);  
                                            
        $('#debitbank').append(_debitbank).trigger('change');
        $('#debitjenis').append(_debitjenis).trigger('change'); 
        
        $('#kreditjumlah').val(result.data[0]['kreditjumlah'].replace(".", ","));   
        $('#kreditno').val(result.data[0]['kreditno']);  
        $('#kreditnama').val(result.data[0]['kreditnama']);  
        $('#kreditbanklain').val(result.data[0]['kreditbanklain']);     
                                            
        $('#kreditbank').append(_kreditbank).trigger('change');
        $('#kreditjenis').append(_kreditjenis).trigger('change'); 
        
        $('#transferjumlah').val(result.data[0]['transferjumlah'].replace(".", ","));   
        $('#transferno').val(result.data[0]['transferno']);  
        $('#transfernama').val(result.data[0]['transfernama']); 
        $('#transferbank').append(_transferbank).trigger('change');
        
        $('#voucherid').val(result.data[0]['voucherid']); 
        $('#voucherno').val(result.data[0]['voucherno']);  
        $('#voucherjumlah').val(result.data[0]['voucherjumlah'].replace(".", ",")); 
        
        $('#dpno').val(result.data[0]['dpno']);  
        $('#dpid').val(result.data[0]['dpid']);  
        $('#dpjenis').append(_dpjenis).trigger('change');
        $('#dpjumlah').val(result.data[0]['dpjumlah'].replace(".", ","));   
        
        $('#totaltanpadp').val(result.data[0]['totaltanpadp'].replace(".", ",")); 
        $('#cabang').val(result.data[0]['cabang']); 
        $('#rekammedis').val(result.data[0]['rekammedis']);   
        
        $('#merchantno').val(result.data[0]['merchantno']);   
        $('#merchantjenis').append(_merchantjenis).trigger('change');
        $('#merchantjumlah').val(result.data[0]['merchantjumlah'].replace(".", ","));  
        
        $('#training').append(_training).trigger('change'); 
        $('#farmasi').append(_farmasi).trigger('change'); 
        $('#farmasiasisten').append(_farmasiasisten).trigger('change'); 
        $('#salesmarketing').append(_salesmarketing).trigger('change'); 
        $('#kliniklain').append(_kliniklain).trigger('change'); 
        $('#dkkwalkin').append(_dkkwalkin).trigger('change'); 
        
        $('#piutangjumlah').val(result.data[0]['piutangjumlah'].replace(".", ",")); 
        
        $('#surgerydpno').val(result.data[0]['surgerydpno']);  
        $('#surgerydpidu').val(result.data[0]['surgerydpidu']);   
        $('#surgerydptotal').val(result.data[0]['surgerydptotal'].replace(".", ","));  
        $('#surgerydppembayaran').val(result.data[0]['surgerydppembayaran'].replace(".", ","));  
        $('#surgerydppiutang').val(result.data[0]['surgerydppiutang'].replace(".", ","));  
        
        $('#reviewnilai').val(result.data[0]['reviewnilai'].replace(".", ","));   
        $('#reviewcatatan').val(result.data[0]['reviewcatatan']);  
        $('#kodetele').val(result.data[0]['kodetele']); 
        $('#kodemedlib').val(result.data[0]['kodemedlib']);  
        $('#idmedlib').val(result.data[0]['idmedlib']); 
        
         
        $('#lmcidpro').val(result.data[0]['lmcid']); 
        
        
        
        $('#teman').append(_teman).trigger('change');
        
        $('#appcanceltransaksi').val('0');   
        

        var rows = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']),
              
              _dokter = $("<option selected='selected'></option>").val(result.data[rows]['iddokter']).text(result.data[rows]['dokter']),
              _operator = $("<option selected='selected'></option>").val(result.data[rows]['idoperator']).text(result.data[rows]['operator']),
              _paket = $("<option selected='selected'></option>").val(result.data[rows]['idpaket']).text(result.data[rows]['paket']),
              _promo = $("<option selected='selected'></option>").val(result.data[rows]['idpromo']).text(result.data[rows]['promo']),
              _referal = $("<option selected='selected'></option>").val(result.data[rows]['idreferal']).text(result.data[rows]['referal']),
              _aos = $("<option selected='selected'></option>").val(result.data[rows]['idaos']).text(result.data[rows]['aos']),
              _recom = $("<option selected='selected'></option>").val(result.data[rows]['idrecom']).text(result.data[rows]['recom']);   
              

          $("select[name^='item']").eq(rows).append(_item).trigger('change');       
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');            
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qtydetil'].replace(".", ","));            
          $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));        
          $("input[name^='dis1']").eq(rows).val(result.data[rows]['dis1detil'].replace(".", ","));        
          $("input[name^='dis2']").eq(rows).val(result.data[rows]['dis2detil'].replace(".", ","));  
          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskondetil'].replace(".", ",")); 
          $("input[name^='tdiskondua']").eq(rows).val(result.data[rows]['diskondetil2'].replace(".", ","));                       
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));   
            
          $("input[name^='item_tipe2020']").eq(rows).val(result.data[rows]['item_tipe2020']);  
          
          $("select[name^='dokter']").eq(rows).append(_dokter).trigger('change'); 
          $("select[name^='operator']").eq(rows).append(_operator).trigger('change'); 
          $("select[name^='paket']").eq(rows).append(_paket).trigger('change'); 
          $("select[name^='promo']").eq(rows).append(_promo).trigger('change'); 
          $("select[name^='referal']").eq(rows).append(_referal).trigger('change'); 
          $("select[name^='aos']").eq(rows).append(_aos).trigger('change'); 
          $("select[name^='recom']").eq(rows).append(_recom).trigger('change');   
          
          $("input[name^='noref']").eq(rows).val(result.data[rows]['noref']);    
          $("input[name^='noic']").eq(rows).val(result.data[rows]['noic']); 
          $("input[name^='nopaketdetil']").eq(rows).val(result.data[rows]['nopaket']); 
          $("input[name^='kedatanganke']").eq(rows).val(result.data[rows]['kedatanganke']);  
          $("input[name^='idpaketdetil']").eq(rows).val(result.data[rows]['idpaketdetil']);  
          $("input[name^='daripaket']").eq(rows).val(result.data[rows]['daripaket']); 
          
          $("input[name^='medidd']").eq(rows).val(result.data[rows]['medidd']);  
          $("input[name^='medidu']").eq(rows).val(result.data[rows]['medidu']); 
           
          $("input[name^='medidd_sudahbayar']").eq(rows).val(result.data[rows]['medidd_sudahbayar']);  
          $("input[name^='medidu_sudahbayar']").eq(rows).val(result.data[rows]['medidu_sudahbayar']); 
          
          $("input[name^='wajibdokter']").eq(rows).val(result.data[rows]['wajibdokter']);  
          $("input[name^='cetak']").eq(rows).val(result.data[rows]['cetak']);   
              
          $("input[name^='idvoucherwebdetil']").eq(rows).val(result.data[rows]['idvoucherwebdetil']);  
          $("input[name^='novoucherwebdetil']").eq(rows).val(result.data[rows]['novoucher']);   
          $("input[name^='pointvoucherwebdetil']").eq(rows).val(result.data[rows]['pointvoucherwebdetil']);   
          
          
             
          if(result.data[rows]['hargadetil']==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');              
          if(result.data[rows]['subtotaldetil']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');       
          
           

           _TampilkanNamaItem(rows);                       

          rows++;
        });

        
        //$('#tqty').val(_tqty.toString().replace('.',','));   
        
     

        if($('.btn-step1').hasClass('disabled')){
          $('.btn-delrow').addClass('disabled');
          $(":input").not(":button, :submit, :reset, :radio, .total").attr('disabled','disabled');   
          $(":input").not(":button, :submit, :reset, :radio, .total").css("background-color", "#ffffff");
        }
        parent.window.$('.loader-wrap').addClass('d-none');                                       
        //return;
      }
  } 
})



/* tarikdetail voucher */


  $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/getdata_detail_v",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      alert('Error : Gagal mengambil data transaksi voucher pos !');
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {

      if (typeof result.pesan !== 'undefined') {
        alert(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {  
        
        $('#tvoucher tbody').html('');
        for (let i = 0; i < result.data.length; i++) {
          _addRow_vocer();
        }
        _inputFormat();  
        var rows = 0;
        
        $.each(result.data, function() { 
 
          $("input[name^='vno']").eq(rows).val(result.data[rows]['VNOMOR']);    
          $("input[name^='vnilai']").eq(rows).val(result.data[rows]['SDVNILAI']); 
          $("input[name^='vitem']").eq(rows).val(result.data[rows]['SDVITEM1']); 
          $("input[name^='vbaris']").eq(rows).val(result.data[rows]['SDVURUTANITEM']);  
          $("input[name^='vuntuk1bon']").eq(rows).val(result.data[rows]['SPVD1BON']);  
          $("input[name^='vitem2']").eq(rows).val(result.data[rows]['SDVITEM2']); 
          
          $("input[name^='vpersentase']").eq(rows).val(result.data[rows]['SDVRUPIAH']);  
          $("input[name^='vnilai2']").eq(rows).val(result.data[rows]['SDVNILAI2']); 
           
          $("input[name^='vfreeitem']").eq(rows).val(result.data[rows]['SDVFREEITEM']);  
          $("input[name^='vid']").eq(rows).val(result.data[rows]['SDVIDVOUCHER']);  
           
            
 
          rows++;
        });
 
                                         
        return;
      }
  } 
})
/* tarikdetail voucher  end */



}

/**/

  if(qparam.get('id')==null){
      _clearForm();
      //_addRow();
      _inputFormat();
      _formState1();  
  }else{
      _clearForm();
      _formState2();  
      $("#id").val(qparam.get('id')).trigger('change');          
  }

});

var _hitungTotal = () => {
    let kas =  Number($('#kasjumlah').val().split('.').join('').toString().replace(',','.'));
    let debit =  Number($('#debitjumlah').val().split('.').join('').toString().replace(',','.'));
    let kredit =  Number($('#kreditjumlah').val().split('.').join('').toString().replace(',','.'));
    let transfer =  Number($('#transferjumlah').val().split('.').join('').toString().replace(',','.'));
    let dp =  Number($('#dpjumlah').val().split('.').join('').toString().replace(',','.'));
    let merchant =  Number($('#merchantjumlah').val().split('.').join('').toString().replace(',','.'));
    let voucher =  Number($('#voucherjumlah').val().split('.').join('').toString().replace(',','.'));
    let piutang =  Number($('#piutangjumlah').val().split('.').join('').toString().replace(',','.'));
    let surgerydppembayaran =  Number($('#surgerydppembayaran').val().split('.').join('').toString().replace(',','.'));
     
    
    
    let totalbayar = 0 ;
    
    totalbayar= kas+debit+kredit+transfer+dp+merchant+voucher+piutang+surgerydppembayaran ; 
    
    let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
    //let totalbayar =  Number($('#totalbayar').val().split('.').join('').toString().replace(',','.'));
    let totalsisa = 0 ;
    
    totalsisa = totalbayar - tsubtotal ; 
    
    totalsisa = totalsisa.toString().replace('.',',');  
    if(totalsisa==0) totalsisa='0,00';
    $('#totalsisa').val(totalsisa).attr('placeholder',totalsisa);  
    
     totalbayar = totalbayar.toString().replace('.',',');  
    if(totalbayar==0) totalbayar='0,00';
    $('#totalbayar').val(totalbayar).attr('placeholder',totalbayar);
    
     

}


 

  

var _hitungJumlahDetil = (idx) => {   
  let vqty = Number($("input[name^='qty']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vharga = Number($("input[name^='harga']").eq(idx).val().split('.').join('').toString().replace(',','.')),
     
      vdis1 = Number($("input[name^='dis1']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vdis2 = Number($("input[name^='dis2']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vsubtotal = 0, vdiskon1=0, vdiskon2=0, vharganet1=0, vdiskon=0 ; 
 
  vharganet1=vharga;
  if(vdis1>0 && vharga>0 && vqty >0)
  {
      vdiskon1=vdis1/100 * vharga;
      vharganet1=vharga-vdiskon1; 
  }
  if(vdis2>0 && vharga>0 && vqty >0)
  {
      vdiskon2=vdis2/100 * vharganet1; 
  }
  vdiskon=vdiskon1+vdiskon2;
 
  
  vsubtotal = (vharga-vdiskon)*vqty;
  vsubtotal = vsubtotal.toFixed(2);
  vsubtotal = vsubtotal.toString().replace('.',',');  
  vdiskon = vdiskon.toString().replace('.',','); 

  if(vsubtotal==0) vsubtotal='0,00';
  //if(vdiskon==0) vdiskon='0,00';
  
   
    $("input[name^='diskon']").eq(idx).val(vdiskon);
  $("input[name^='subtotal']").eq(idx).val(vsubtotal).attr('placeholder',vsubtotal); 
  $("input[name^='tdiskondua']").eq(idx).val(vdiskon2);
  
   
 
  return vsubtotal;
}

 
  
  var _TampilkanNamaItem = (xBaris) => {   
    let xspannama ='', xspanhargabersih=0, xspanqty=0, xnourut=0, xnamapaket='', xs=' ', xnamapromo='';
    xspannama =$("select[name^='item']").eq(xBaris).text() ; 
    xnamapaket =$("select[name^='paket']").eq(xBaris).text() ; 
    xnamapromo =$("select[name^='promo']").eq(xBaris).text() ; 
    
    if(xnamapaket!='') xnamapaket = "<span class=\"badge badge-primary\" >"+xnamapaket+"</span> " ;
    if(xnamapromo!='') xnamapromo = "<span class=\"badge badge-success\" >"+xnamapromo+"</span> " ;
    
    
    
    
   $("span[name^='spannama']").eq(xBaris).html(xspannama+xs+xnamapaket+xs+xnamapromo);
   
   if (xBaris>=0) {
     xnourut=Number(xBaris)+1 ;     
     $("span[name^='spanurutan']").eq(xBaris).html(xnourut);   
   } 
   
   return ;
      
  }  
  
 
  
    var _cekbisaeditharga = () => {      
       
        const totalbaris = $(".item").length;
            for(let i=0;i<totalbaris;i++){ 
                
                        if ($("input[name^='bisaeditharga']").eq(i).val()=='1')
                          { 
                               
                             // alert('bisa edit harga' + $("select[name^='item']").eq(i).text());  
                              $("input[name^='harga']").eq(i).removeAttr('disabled');  
                              $("input[name^='harga']").eq(i).addClass('harga form-control form-control-sm numeric'); 
                              //let tharga = 0 ;
                              
                             // alert('bisa edit harga' + $("input[name^='item']").eq(i).val()); 
                          }
                          //else
                          //{ 
                              //$("input[name^='harga']").eq(i).Attr('disabled'); 
                             //alert('Tidak bisa edit harga'  + $("select[name^='item']").eq(i).val() ); 
                          //} 
            }  
       
      
  }
  
  
  
   
  

var _hitungsubtotal = () => {
  let tqty = 0, tsubtotal = 0,vitem_tipe2020=0, vtotaltanpadp=0;
  
  $('.item').each(function(index,element) { 
    tqty += Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')); 
    tsubtotal += Number($("input[name^='subtotal']").eq(index).val().split('.').join('').toString().replace(',','.')); 
    
    vitem_tipe2020 = $("input[name^='item_tipe2020']").eq(index).val() ;    
    if (vitem_tipe2020!=8)    vtotaltanpadp += Number($("input[name^='subtotal']").eq(index).val().split('.').join('').toString().replace(',','.'));  
    
  });  

  tqty = tqty.toString().replace('.',',');
  tsubtotal = tsubtotal.toString().replace('.',',');   
  vtotaltanpadp = vtotaltanpadp.toString().replace('.',',');          

  if(tqty==0) tqty='0,00';
  if(tsubtotal==0) tsubtotal='0,00';
  if(vtotaltanpadp==0) vtotaltanpadp='0,00';

  $('#tqty').val(tqty).attr('placeholder',tqty);
  $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);    
  $('#totaltanpadp').val(vtotaltanpadp).attr('placeholder',vtotaltanpadp);   
  
   
    
    
  return;
}


 $("#bsetkas").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
       let dp =  Number($('#dpjumlah').val().split('.').join('').toString().replace(',','.')); 
       
        if(tsisa>0) return ;  
        tsisa=Math.abs(tsisa);
        tsisa = tsisa.toString().replace('.',',');
       
      $('#kasjumlah').val(tsisa).attr('placeholder',tsisa);
       
      _hitungTotal();
    return;
  });  
  
var setkas = () => {  
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
       
      if(tsisa>0) return ; 
        tsisa=Math.abs(tsisa);
        tsisa = tsisa.toString().replace('.',',');
      $('#kasjumlah').val(tsisa).attr('placeholder',tsisa);
      _hitungTotal();
      return;
  }  
  
 $("#bsetdebit").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
       
        if(tsisa>0) return ;  
        tsisa=Math.abs(tsisa);
        tsisa = tsisa.toString().replace('.',',');
      $('#debitjumlah').val(tsisa).attr('placeholder',tsisa);
      _hitungTotal();
  });   
  
 $("#bsetkredit").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
       
        if(tsisa>0) return ;  
        tsisa=Math.abs(tsisa);
        tsisa = tsisa.toString().replace('.',',');
      $('#kreditjumlah').val(tsisa).attr('placeholder',tsisa);
      _hitungTotal();
  });  
 $("#bsettransfer").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
       
        if(tsisa>0) return ;  
        tsisa=Math.abs(tsisa);
        tsisa = tsisa.toString().replace('.',',');
      $('#transferjumlah').val(tsisa).attr('placeholder',tsisa);
      _hitungTotal();
  });  
 $("#bsetmerchant").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
      
       
        if(tsisa>0) return ;  
        tsisa=Math.abs(tsisa);
        tsisa = tsisa.toString().replace('.',',');
      $('#merchantjumlah').val(tsisa).attr('placeholder',tsisa);
      _hitungTotal();
  });  
  
  
  
var _bersihkandp = () => {
    
    $('#dpjumlah').val(0);   
    $('#dpid').val(''); 
    $('#dpno').val('');  
    
    //_bersihkanpembayaran();
    //_hitungTotal();
    
}  
  
var _bersihkanpembayaran = () => {
    
    
   $('#kasjumlah').val('0,00').attr('placeholder','0,00');
   $('#debitjumlah').val('0,00').attr('placeholder','0,00');
   $('#kreditjumlah').val('0,00').attr('placeholder','0,00');
   $('#transferjumlah').val('0,00').attr('placeholder','0,00');
   $('#merchantjumlah').val('0,00').attr('placeholder','0,00'); 
    
    
    let dp =  Number($('#dpjumlah').val().split('.').join('').toString().replace(',','.')); 
    let voucher =  Number($('#voucherjumlah').val().split('.').join('').toString().replace(',','.'));
    let piutang =  Number($('#piutangjumlah').val().split('.').join('').toString().replace(',','.'));
    let surgerydppembayaran =  Number($('#surgerydppembayaran').val().split('.').join('').toString().replace(',','.'));
     
    
    
    let totalbayar = 0 ;
    
    totalbayar=  dp+voucher+piutang+surgerydppembayaran ; 
    
    let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.')); 
    let totalsisa = 0 ;
    
    totalsisa = totalbayar - tsubtotal ; 
    
    totalsisa = totalsisa.toString().replace('.',',');  
    if(totalsisa==0) totalsisa='0,00';
    $('#totalsisa').val(totalsisa).attr('placeholder',totalsisa);  
    
     totalbayar = totalbayar.toString().replace('.',',');  
    if(totalbayar==0) totalbayar='0,00';
    $('#totalbayar').val(totalbayar).attr('placeholder',totalbayar);

}

var _set_nomor_ip = (xtgl) => { 
    $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/getnomorip",       
        "type"   : "POST", 
        "dataType" : "json", 
        "data" : "tgl="+xtgl,
        "cache"  : false,
        "error"  : () => {
            parent.window.toastr.error('Error : Gagal mengambil no transaksi pos !');
            parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : (result) => {
             $('#nomor').val(result.data[0]['no']);
        } 
  })
}



window._cekharga = async (obj) => {
    
    await _cekbisaeditharga();
    
    
}

 
window._hapusbaris = async (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  await _hitungsubtotal();
  _hitungTotal();
}


window._tampilkandetailnya = async (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  await _hitungsubtotal();
  _hitungTotal();
}