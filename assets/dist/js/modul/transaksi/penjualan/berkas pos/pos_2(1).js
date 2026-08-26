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

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#pajak'); 
  Component_Select2('#termin',`${base_url}Select_Master/view_termin`,'form_termin','Termin'); 
  Component_Select2('.karyawan',`${base_url}Select_Master/view_karyawan`,'','');
  Component_Select2('.klinikluar',`${base_url}Select_Master/view_klinikluar`,'','');

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
          
        //reset ulang diskon jika bukna membeer
            const totalbaris = $(".item").length;
            for(let i=0;i<totalbaris;i++){
              if( result.data[0]['tipeid']!=12   ){  
                        $("input[name^='dis1']").eq(i).val(0).attr('placeholder',0);
                        $("input[name^='dis2']").eq(i).val(0).attr('placeholder',0); 
                        _hitungJumlahDetil(i); 
              }
            }  
            // konfirmasi kartu 
            if (result.data[0]['tipeid']==12) {
                $('#modalkartumember').modal('show');   
            }
             
            
          return;                    
      } 
      }); 
}
 

 $("#bokpilihkartu").click(function() { 
    $('#bstatuskartumember').html('') ; 
   if ($('#optbawakartu').is(":checked") ) {  
        $('#bstatuskartumember').append("<span class=\"badge badge-pill badge-primary\" >Bawa Kartu</span> "); 
        
    }
    else  if ($('#opttidakbawakartu').is(":checked") ) {
        $('#bstatuskartumember').append("<span class=\"badge badge-pill badge-danger\" >Tidak Bawa Kartu</span> "); 
    }
     
 });

  $("#bpromo").click(function() {  
      
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }
    
    
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
        "success" : function(result) {
        
      if (typeof result.pesan !== 'undefined') {
        parent.window.toastr.error(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else {
          
      
        
       // $('#tdetil tbody').html('');
        //for (let i = 0; i < result.data.length; i++) {
        //  _addRow();
        //}
        _inputFormat();    
            
        
        var rows = 0, datake=0, harga=0, dis1=0, dis2=0, diskon=0, subtotal=0, qty=0, pilihan='' , kepilihan=0, jenispromo, item2='', item3='', item4='' ;
        var pilihan1 = '', pilihan2 = '', pilihan3 = '', pilihan4 = '' ;
        let fltnya='';
        var idx = 0;
          
         
        $.each(result.data, function() {
            
            _addRow();
            _inputFormat(); 
            rows = $("select[name^='item']").length ;  
            rows=rows-1; 
            
             jenispromo=result.data[datake]['jenispromo']; 
             if (jenispromo=0)
             {
                qty=result.data[datake]['qtydetil'].replace(".", ",");    
             }
             else
             {
                qty=result.data[datake]['qtydetil1'].replace(".", ",");  
             }
             

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
        
       _hitungsubtotal();
       _hitungTotal();
        
        parent.window.toastr.success("Sukses menarik data Promo ");   
        parent.window.$('.loader-wrap').addClass('d-none');   
        return;   
                      
      }
      } 
      }); 
}
 
  


  $("#bpaket").click(function() {   
      
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
  });  

  
  
  
var _AmbilDetailPaket= () => {  
    
   
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
          
      
        
        //$('#tdetil tbody').html('');
        //for (let i = 0; i < result.data.length; i++) {
        //  _addRow();
        //}
        //_inputFormat();    
            
        
        var rows = 0, nopaket='', kedatangan=0, harga=0, dis1=0, dis2=0, diskon=0, subtotal=0, qty=0, pilihan='' , kepilihan=0, datake=0 ;
        let fltnya='';
        nopaket = $('#nopaketnya').val() ;
            
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
          
          if (result.data[datake]['kedatangan']==99)
          {  
              kedatangan=0; 
              qty=result.data[datake]['qtydetil'].replace(".", ",");   
              harga=result.data[datake]['hargadetil'].replace(".", ",");        
              dis1=result.data[datake]['dis1detil'].replace(".", ",");        
              dis2=result.data[datake]['dis2detil'].replace(".", ",");
              diskon=result.data[datake]['diskondetil'].replace(".", ",");                       
              subtotal=result.data[datake]['subtotaldetil'].replace(".", ",");   
          }
          else
          {  
              kedatangan++; 
              qty=result.data[datake]['qtydetiltindakan'].replace(".", ","); 
          }
          
          $("input[name^='qty']").eq(rows).val(qty);         
          $("input[name^='harga']").eq(rows).val(harga);        
          $("input[name^='dis1']").eq(rows).val(dis1);        
          $("input[name^='dis2']").eq(rows).val(dis2);
          $("input[name^='diskon']").eq(rows).val(diskon);                       
          $("input[name^='subtotal']").eq(rows).val(subtotal);   
            
          $("input[name^='item_tipe2020']").eq(rows).val(result.data[datake]['item_tipe2020']); 
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
          kedatangan=0;
        });
        
       _hitungsubtotal();
       _hitungTotal();
       
       $("#nopaketnya").val('')=
        
        parent.window.toastr.success("Sukses menarik data Paket ");   
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
          
           
          _kode=result.data[0]['namaitem'];
          _urutan=_idx;
          _TampilkanNamaItem(_idx);  
           $("span[name^='spannama']").eq(_urutan).html(_kode); 
           
 
          $('#pilihanpaket').val(''); 
          return;                    
      } 
      });
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
      }  else {  
         
        $('#dpjumlah').val(result.data[0]['nilai'].replace(".", ","));  
        $('#dpid').val(result.data[0]['id']); 
        $('#dpno').val(result.data[0]['nomor']);  
        
       
        
        parent.window.toastr.success("Sukses menarik data DP ");   
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
          
          _tsubtotal += Number(result.data[rows]['subtotaldetil']); 

          rows++;
        });

         
        _hitungsubtotal();
        _hitungTotal();
        
        parent.window.toastr.success("Sukses menarik data Surgery DP ");   
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
       $('#modalbayar').modal('show');   
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
        parent.window._transaksidatatable('view_pos');
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
   // _addRow();
    _inputFormat();
    _formState2();
  });

  $("#bsave").click(function() { 
    if (_IsValid()===0) return;
    _saveData(); 
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
       $('#modalPaket').modal('show');
       
  });  
  

  $('#idpromo').on('change',function(){
       _AmbilDetailPromo();
       
  });  
     
    $('#tgl').on('change',function(){
       if($('#id').val()!=='') return; 
      _set_nomor_ip($('#tgl').val());
        
  });  
 

  $('#idpaket').on('change',function(){ 
       $('#modalPaket').modal('show');
       
  });  
  
  
  
  $("#btambahitem").click(function() {   
           
    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pasien harus diisi !');
      $('#namakontak').tooltip('show');
      $('#namakontak').focus();
      return 0;
    }  
       
       $('#modaltambahitem').modal('show');  
       
  });  
  
  var _TampilkanNamaItem = (idx) => {   
    let xspannama ='', xspanhargabersih=0, xspanqty=0, xnourut=0;
    xspannama =$("select[name^='item']").eq(idx).text() ;
    xspanqty= $("input[name^='qty']").eq(idx).val();
    xspanhargabersih= $("input[name^='subtotal']").eq(idx).val();
    xnourut=Number(idx)+1 ;  
    
   $("span[name^='spannama']").eq(idx).html(xspannama);
   $("span[name^='spanqty']").eq(idx).html(xspanqty); 
   $("span[name^='spanhargabersih']").eq(idx).html(xspanhargabersih);
   $("span[name^='spanurutan']").eq(idx).html(xnourut); 
      
  }  
 
  $("#bokpilihitem").click(function() {   
          
    if($('#pilihanitem').val()==''){
      $('#btambahitem').attr('data-title','Tidak ada yang dipilih !'); 
      return 0;
    }  
         
      
     let _iditem = $('#pilihanitem').val();  
     
       $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_item", 
        "type"   : "POST", 
        "data"   : "id="+$('#pilihanitem').val(),
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
        var _idx = $("select[name^='item']").length ; 
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
          $("input[name^='harga']").eq(_idx).val(result.data[0]['hargajual']);   
          $("input[name^='qty']").eq(_idx).val(1);    
          $("input[name^='item_tipe2020']").eq(_idx).val(result.data[0]['kelompok2020']);  
          
          if ( $('#kontaktipe').val()==12 )
          {
              $("input[name^='dis1']").eq(_idx).val(result.data[0]['diskon']); 
          }      
          if(result.data[0]['diskon']==0) $("input[name^='dis1']").eq(_idx).attr('placeholder','0,00');    
          if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00'); 
          
           $('#keberapa').val(_idx);
      

          let jumlah = await _hitungJumlahDetil(_idx);

          let subtotal = await _hitungsubtotal();
          _hitungTotal();
           
            
          if ( $("input[name^='item_tipe2020']").eq(_idx).val()==1 || $("input[name^='item_tipe2020']").eq(_idx).val()==2 || $("input[name^='item_tipe2020']").eq(_idx).val()==3 || $("input[name^='item_tipe2020']").eq(_idx).val()==4 || $("input[name^='item_tipe2020']").eq(_idx).val()==9 || $("input[name^='item_tipe2020']").eq(_idx).val()==10 || $("input[name^='item_tipe2020']").eq(_idx).val()==11 || $("input[name^='item_tipe2020']").eq(_idx).val()==12 )
          {
               
               $('#modalnoic').modal('show'); 
               $('#modalnoref').modal('show'); 
               $('#modaloperator').modal('show'); 
               $('#modaldokter').modal('show'); 
               $('#keberapa').val('');
          } 
          
            _TampilkanNamaItem(_idx);
          
          $('#pilihanitem').val('').change();  
          return;                    
      } 
      });
  });
  
  
 
  
  
 $("#bokpilihdokter").click(function() {    
    if($('#pilihandokter').val()==''){
     alert ('Tidak ada yang dipilih !'); 
      return 0;
    }  
     var _iddokter = $('#pilihandokter').val();  
     var _namadokter = ($("select[name^='pilihandokter'] option:selected").text());
        var _idx = $('#keberapa').val();   
        _idx=_idx-1; 
         
        
        var   _dokter = $("<option selected='selected'></option>").val(_iddokter).text(_namadokter) ;
        $("select[name^='dokter']").eq(_idx).append(_dokter).trigger('change'); 
        
        $('#pilihandokter').val('');
        
          return;  
  });
  
  
 $("#bokpilihoperator").click(function() {    
    if($('#pilihanoperator').val()==''){
     alert ('Tidak ada yang dipilih !'); 
      return 0;
    }  
     
      var _namadokter = ($("select[name^='pilihanoperator'] option:selected").text());  
      
        var _idx = $('#keberapa').val();   
        _idx=_idx-1;  
        
        var   _dokter = $("<option selected='selected'></option>").val($('#pilihanoperator').val()).text(_namadokter) ;
        $("select[name^='operator']").eq(_idx).append(_dokter).trigger('change');   
        
        $('#pilihanoperator').val('');
          return;  
  });
  
  
 $("#boknoref").click(function() {    
        if($('#norefnya').val()==''){
        alert ('Masukkan No Ref !'); 
        return 0; 
        }
        var _idx = $('#keberapa').val();  
        _idx=_idx-1;   
         
        $("input[name^='noref']").eq(_idx).val($('#norefnya').val());   
        $('#norefnya').val('');
        return;  
  });
  
  
 $("#boknoic").click(function() {    
        if($('#noicnya').val()==''){
        alert ('Masukkan No IC !'); 
        return 0; 
        }
        var _idx = $('#keberapa').val();  
        _idx=_idx-1;   
         
        $("input[name^='noic']").eq(_idx).val($('#noicnya').val());  
        $('#noicnya').val('');
        return;  
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

      $.ajax({ 
        "url"    : base_url+"PJ_POS_HP/get_item", 
        "type"   : "POST", 
        "data"   : "id="+$(this).val()+"&kontak="+$("#idkontak").val(),
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
          $("input[name^='qty']").eq(_idx).val(1);    
          $("input[name^='item_tipe2020']").eq(_idx).val(result.data[0]['kelompok2020']);  
          
          if ( $('#kontaktipe').val()==12 )
          {
              $("input[name^='dis1']").eq(_idx).val(result.data[0]['diskon']); 
          }      
          if(result.data[0]['diskon']==0) $("input[name^='dis1']").eq(_idx).attr('placeholder','0,00');    
          if(result.data[0]['hargajual']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');    
           $("#loader-detil").addClass('d-none');   

          let jumlah = await _hitungJumlahDetil(_idx);

          let subtotal = await _hitungsubtotal();
          _hitungTotal();
          
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
  Component_Select2_Item('.item2',`${base_url}Select_Master/view_item`,'form_item','Item');  
  Component_Select2('.dokter',`${base_url}Select_Master/view_dokter`,'','');  
  Component_Select2('.dokter2',`${base_url}Select_Master/view_dokter`,'','');  
  Component_Select2('.operator',`${base_url}Select_Master/view_karyawan`,'','');  
  Component_Select2('.operator2',`${base_url}Select_Master/view_karyawan`,'','');  
  Component_Select2('.aos',`${base_url}Select_Master/view_karyawan`,'','');  
  Component_Select2('.recom',`${base_url}Select_Master/view_karyawan`,'','');  
  Component_Select2('.referal',`${base_url}Select_Master/view_karyawan`,'','');  
  Component_Select2('.paket',`${base_url}Select_Master/view_paket`,'',''); 
  Component_Select2('.promo',`${base_url}Select_Master/view_promo`,'',''); 
  Component_Select2('.bank',`${base_url}Select_Master/view_bank2`,'',''); 
  Component_Select2('.merchant',`${base_url}Select_Master/view_merchant`,'',''); 
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
  let  
        
         newrow  = "<tr><td width=\"90%\"><div class=\"card  collapsed-card card-light\"  >";
        newrow += "<div class=\"card-header\" data-card-widget=\"collapse\">";  
        newrow += "<div class=\"form-group row my-0\">";  
        newrow += "<span class=\"col-1 col-form-label text-sm px-1 font-weight-normal\" id=\"spanurutan[]\" name=\"spanurutan[]\">No</span> "; 
        newrow += "<span class=\"col-3 col-form-label text-sm px-1 font-weight-normal\" id=\"spannama[]\" name=\"spannama[]\">Keterangan Produk</span>";  
        newrow += "<span class=\"col-2 col-form-label text-sm px-1 font-weight-normal\" id=\"spanqty[]\" name=\"spanqty[]\">Qty</span>";  
        newrow += "<span class=\"col-2 col-form-label text-sm px-1 font-weight-normal\" id=\"spanhargabersih[]\" name=\"spanhargabersih[]\">Harga Nett</span>";  
        newrow += "</div>";  
        newrow += "</div>"; 
         newrow += "<div class=\"card-body\">";   
                                    
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Produk</label>"; 
        newrow += "<div class=\"col-9\"><div class=\"input-group\" data-target-input=\"nearest\">";
        newrow += "<select name=\"item[]\" class=\"item form-control select2 form-control-sm kuncitext\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "<input name=\"item_tipe2020[]\" type=\"hidden\" class=\"form-control form-control-sm\">";
        newrow += "</div></div></div>";  
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Qty</label>";
        newrow += "<div class=\"col-3\">";
        newrow += "<input name=\"qty[]\" type=\"text\" class=\"total form-control form-control-sm numeric  \" value=\"0\">";
        newrow += "</div>  ";
        newrow += "<label class=\"col-2 col-form-label text-sm px-1 font-weight-normal\">Satuan</label>";
        newrow += "<div class=\"col-4\">";
        newrow += "<select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select>";
        newrow += "</div>   ";
        newrow += "</div> ";
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Disc 1</label>";
        newrow += "<div class=\"col-3\">";
        newrow += "<input name=\"dis1[]\" type=\"text\" class=\"total form-control form-control-sm kuncitext\" value=\"0\">";
        newrow += "</div> ";
        newrow += "<label class=\"col-2 col-form-label text-sm px-1 font-weight-normal\">Harga</label>";
        newrow += "<div class=\"col-4\">";
        newrow += "<input name=\"harga[]\" type=\"text\" class=\"total form-control form-control-sm numeric kuncitext\" value=\"0\">";
        newrow += "</div>   ";
        newrow += "</div>";
                   
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Disc 2</label>";
        newrow += "<div class=\"col-3\">";
        newrow += "<input name=\"dis2[]\" type=\"text\" class=\"total form-control form-control-sm kuncitext\" value=\"0\">";
        newrow += "</div>   ";
        newrow += "<label class=\"col-2 col-form-label text-sm px-1 font-weight-normal\">Disc</label>";
        newrow += "<div class=\"col-4\">";
        newrow += "<input name=\"diskon[]\" type=\"text\" class=\"total form-control form-control-sm numeric kuncitext\" value=\"0\">";
        newrow += "</div> ";            
        newrow += "</div>"; 
        
        newrow += " <div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Sub Total</label>";
        newrow += "<div class=\"col-9\">";
        newrow += "<input name=\"subtotal[]\" type=\"text\" class=\"total form-control form-control-sm numeric kuncitext\" value=\"0\">";
        newrow += " </div> ";         
        newrow += "</div>"; 
        
        


        
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
        newrow += "</div>"; 
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No IC</label>"; 
        newrow += "<div class=\"col-3\">"; 
        newrow += "<input name=\"noic[]\" type=\"text\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
        newrow += "</div>"; 
        newrow += "</div>"; 
        
         newrow += "<div class=\"form-group row my-0\">"; 
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No Paket</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"nopaketdetil[]\" type=\"text\" class=\"form-control form-control-sm kuncitext\"  autocomplete=\"off\">";  
         newrow += "</div>"; 
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Ke-</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"kedatanganke[]\" type=\"text\" class=\"form-control form-control-sm kuncitext\"  autocomplete=\"off\">"; 
         newrow += "</div> "; 
         newrow += "</div>"; 
          
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Nama Paket</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"paket[]\" class=\"paket form-control select2 form-control-sm kuncicombo\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
         newrow += "<input name=\"idpaketdetil[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"daripaket[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
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
        
        newrow += "</div> ";
        newrow += " </div>" ;  
         
       
        newrow += "</div> "; 
        newrow += "</div> ";  
        newrow += " </div>" ; 
        newrow += "</td> " ; 
        newrow += "<td>" ; 
         
        newrow += " <a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><label class=\"col-form-label text-sm px-3 font-weight-normal\">Hapus</label><i class=\"fas fa-trash text-md text-red\"></i></a> ";   
        
        newrow += "</td> </tr>" ; 
      
 
      
      
  $('#tdetil tbody').append(newrow);
  
  $('.kuncitext').attr('disabled','disabled');  
  $('.kuncicombo').prop('disabled',true); 
  
  
}  


var _addRowxx = () => {
  let newrow = " <div class=\"card border-primary mb-3 collapsed-card style=\"max-width: 18rem; \">"; 
       newrow += "<div class=\"card-header\" data-card-widget=\"collapse\"   >   ";  
      
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<span class=\"col-1 col-form-label text-sm px-1 font-weight-normal\" id=\"spanurutan[]\" name=\"spanurutan[]\">No</span>"; 
        newrow += "<span class=\"col-3 col-form-label text-sm px-1 font-weight-normal\" id=\"spannama[]\" name=\"spannama[]\">Keterangan Produk</span>";  
        newrow += "<span class=\"col-2 col-form-label text-sm px-1 font-weight-normal\" id=\"spanqty[]\" name=\"spanqty[]\">Qty</span>";  
        newrow += "<span class=\"col-2 col-form-label text-sm px-1 font-weight-normal\" id=\"spanhargabersih[]\" name=\"spanhargabersih[]\">Harga Nett</span>"; 
         
     
        newrow += "</div>"; 
        newrow += "</div>"; 
        newrow += "</div>"; 
             
 
      
      
  $('#tdetil tbody').append(newrow);
  
  $('.kuncitext').attr('disabled','disabled');  
  $('.kuncicombo').prop('disabled',true); 
  
  
}                 
     
 
  
  
 
var _addRowx = () => {
  let newrow = "<tr><td width=\"90%\"><div class=\"card border-primary mb-3 collapsed-card \">"; 
       newrow += "<div class=\"card-header\" data-card-widget=\"collapse\"   >   ";  
      
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<span class=\"col-1 col-form-label text-sm px-1 font-weight-normal\" id=\"spanurutan[]\" name=\"spanurutan[]\">No</span>"; 
        newrow += "<span class=\"col-3 col-form-label text-sm px-1 font-weight-normal\" id=\"spannama[]\" name=\"spannama[]\">Keterangan Produk</span>";  
        newrow += "<span class=\"col-2 col-form-label text-sm px-1 font-weight-normal\" id=\"spanqty[]\" name=\"spanqty[]\">Qty</span>";  
        newrow += "<span class=\"col-2 col-form-label text-sm px-1 font-weight-normal\" id=\"spanhargabersih[]\" name=\"spanhargabersih[]\">Harga Nett</span>"; 
         
     
        newrow += "</div>"; 
             
        newrow += "</div>";    
  
         newrow += "<div class=\"card-body\">";   
                                    
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Produk</label>"; 
        newrow += "<div class=\"col-9\"><div class=\"input-group\" data-target-input=\"nearest\">";
        newrow += "<select name=\"item[]\" class=\"item form-control select2 form-control-sm kuncitext\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
        newrow += "<input name=\"item_tipe2020[]\" type=\"hidden\" class=\"form-control form-control-sm\">";
        newrow += "</div></div></div>";  
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Qty</label>";
        newrow += "<div class=\"col-3\">";
        newrow += "<input name=\"qty[]\" type=\"text\" class=\"total form-control form-control-sm numeric  \" value=\"0\">";
        newrow += "</div>  ";
        newrow += "<label class=\"col-2 col-form-label text-sm px-1 font-weight-normal\">Satuan</label>";
        newrow += "<div class=\"col-4\">";
        newrow += "<select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select>";
        newrow += "</div>   ";
        newrow += "</div> ";
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Disc 1</label>";
        newrow += "<div class=\"col-3\">";
        newrow += "<input name=\"dis1[]\" type=\"text\" class=\"total form-control form-control-sm kuncitext\" value=\"0\">";
        newrow += "</div> ";
        newrow += "<label class=\"col-2 col-form-label text-sm px-1 font-weight-normal\">Harga</label>";
        newrow += "<div class=\"col-4\">";
        newrow += "<input name=\"harga[]\" type=\"text\" class=\"total form-control form-control-sm numeric kuncitext\" value=\"0\">";
        newrow += "</div>   ";
        newrow += "</div>";
                   
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Disc 2</label>";
        newrow += "<div class=\"col-3\">";
        newrow += "<input name=\"dis2[]\" type=\"text\" class=\"total form-control form-control-sm kuncitext\" value=\"0\">";
        newrow += "</div>   ";
        newrow += "<label class=\"col-2 col-form-label text-sm px-1 font-weight-normal\">Disc</label>";
        newrow += "<div class=\"col-4\">";
        newrow += "<input name=\"diskon[]\" type=\"text\" class=\"total form-control form-control-sm numeric kuncitext\" value=\"0\">";
        newrow += "</div> ";            
        newrow += "</div>"; 
        
        newrow += " <div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Sub Total</label>";
        newrow += "<div class=\"col-9\">";
        newrow += "<input name=\"subtotal[]\" type=\"text\" class=\"total form-control form-control-sm numeric kuncitext\" value=\"0\">";
        newrow += " </div> ";         
        newrow += "</div>"; 
        
        


        
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
        newrow += "</div>"; 
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No IC</label>"; 
        newrow += "<div class=\"col-3\">"; 
        newrow += "<input name=\"noic[]\" type=\"text\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
        newrow += "</div>"; 
        newrow += "</div>"; 
        
         newrow += "<div class=\"form-group row my-0\">"; 
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">No Paket</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"nopaketdetil[]\" type=\"text\" class=\"form-control form-control-sm kuncitext\"  autocomplete=\"off\">";  
         newrow += "</div>"; 
         newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Ke-</label>"; 
         newrow += "<div class=\"col-3\">"; 
         newrow += "<input name=\"kedatanganke[]\" type=\"text\" class=\"form-control form-control-sm kuncitext\"  autocomplete=\"off\">"; 
         newrow += "</div> "; 
         newrow += "</div>"; 
          
        
        newrow += "<div class=\"form-group row my-0\">";
        newrow += "<label class=\"col-3 col-form-label text-sm px-1 font-weight-normal\">Nama Paket</label>"; 
        newrow += "<div class=\"col-9\">";
        newrow += "<select name=\"paket[]\" class=\"paket form-control select2 form-control-sm kuncicombo\"  data-trigger=\"manual\" data-placement=\"auto\"></select>";
         newrow += "<input name=\"idpaketdetil[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
         newrow += "<input name=\"daripaket[]\" type=\"hidden\" class=\"form-control form-control-sm\"  autocomplete=\"off\">"; 
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
        
        newrow += "</div> ";
        newrow += " </div>" ;  
         
       
        newrow += "</div> "; 
        newrow += "</div> ";  
        newrow += " </div>" ; 
        newrow += "</td> " ; 
        newrow += "<td>" ; 
         
        newrow += " <a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><label class=\"col-form-label text-sm px-3 font-weight-normal\">Hapus</label><i class=\"fas fa-trash text-md text-red\"></i></a> ";   
        
        newrow += "</td> </tr>" ; 
      
      
  $('#tdetil tbody').append(newrow);
  
  $('.kuncitext').attr('disabled','disabled');  
  $('.kuncicombo').prop('disabled',true); 
  
  
}                 
                 
                
                  



/**/

/* CRUD
/* ========================================================================================== */
var _IsValid = () => {

    if($('#idkontak').val()==''){
      $('#namakontak').attr('data-title','Pelanggan harus diisi !');
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
    if ($('#dkkwalkin').val()==''){
        
        $('#modalDKK').modal('show');   
        
      //$('#dkkwalkin').attr('data-title','DKK Walk In harus diisi !');      
      //$('#dkkwalkin').tooltip('show');
      //$('#dkkwalkin').focus();
      return 0;
    } 

    const totalbaris = $(".item").length;
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
        tgl = $("#tgl").val(),
        kontak = $("#idkontak").val(),
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
        kodetele= $("#kodetele").val(),reviewcatatan= $("#reviewcatatan").val()  
        
        ;

  let detil = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value, 
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               dis1:Number($("input[name^='dis1']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               dis2:Number($("input[name^='dis2']").eq(index).val().split('.').join('').toString().replace(',','.')), 
               diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
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
               daripaket:($("input[name^='daripaket']").eq(index).val())                 
             });

  }); 

  detil = JSON.stringify(detil);  

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
  rey.set('tgl',tgl);
  rey.set('kontak',kontak);
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
  
   
  
  
            
  rey.set('detil',detil);

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
        _kliniklain = $("<option selected='selected'></option>").val(result.data[0]['idkliniklain']).text(result.data[0]['namakliniklain'])
        
        ;
        
        
        $('#id').val(result.data[0]['id']);            
        $('#nomor').val(result.data[0]['nomor']);
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
        return;
      }
  } 
})

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
  vsubtotal = vsubtotal.toString().replace('.',',');  
  vdiskon = vdiskon.toString().replace('.',','); 

  if(vsubtotal==0) vsubtotal='0,00';
  if(vdiskon==0) vdiskon='0,00';
   
  $("input[name^='subtotal']").eq(idx).val(vsubtotal).attr('placeholder',vsubtotal);
  $("input[name^='diskon']").eq(idx).val(vdiskon).attr('placeholder',vdiskon);
  

 
  
  return vsubtotal;
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
      $('#kasjumlah').val(Math.abs(tsisa)).attr('placeholder',tsisa);
      _hitungTotal();
      return;
  });  
  
var setkas = () => {  
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
      $('#kasjumlah').val(Math.abs(tsisa)).attr('placeholder',tsisa);
      _hitungTotal();
      return;
  }  
  
 $("#bsetdebit").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
      $('#debitjumlah').val(Math.abs(tsisa)).attr('placeholder',tsisa);
      _hitungTotal();
  });   
  
 $("#bsetkredit").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
      $('#kreditjumlah').val(Math.abs(tsisa)).attr('placeholder',tsisa);
      _hitungTotal();
  });  
 $("#bsettransfer").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
      $('#transferjumlah').val(Math.abs(tsisa)).attr('placeholder',tsisa);
      _hitungTotal();
  });  
 $("#bsetmerchant").click(function() {   
     _bersihkanpembayaran();
      let tsubtotal  = Number($('#tsubtotal').val().split('.').join('').toString().replace(',','.'));
      let tsisa = Number($('#totalsisa').val().split('.').join('').toString().replace(',','.'));
      $('#merchantjumlah').val(Math.abs(tsisa)).attr('placeholder',tsisa);
      _hitungTotal();
  });  
  
  
  
  
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
            parent.window.toastr.error('Error : Gagal mengambil data transaksi pos !');
            parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : (result) => {
             $('#nomor').val(result.data[0]['no']);
        } 
  })
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