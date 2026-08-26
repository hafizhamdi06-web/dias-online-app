/* ========================================================================================== */
/* File Name : mutasi-gudang.js
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
  setupHiddenInputChangeListener($('#idpermintaan')[0]);

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2('#gudangasal,#gudangtujuan',`${base_url}Select_Master/view_gudang`,'form_gudang','Gudang');

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

/* ========================================================================================== */
/* ========================================================================================== */

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $('#kontak').keydown(function(e){
    if(e.keyCode==13) { $('#carikontak').click(); }
  });

  $('#kontak2').keydown(function(e){
    if(e.keyCode==13) { $('#carikontak2').click(); }
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
    location.href=base_url+"page/mutasiData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-mutasi");
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
          parent.window.$("#modaltrigger").val("iframe-page-mutasi");
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
          parent.window._pilihkategorikontak('4'); 
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
    }    
  });

  $("#carikontak2").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$('.loader-wrap').removeClass('d-none');
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-mutasi");
          parent.window.$('#coltrigger').val('kontak2');                
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

  $("#bsearch").click(function() {    
    $.ajax({ 
      "url"    : base_url+"Modal/cari_transaksi", 
      "type"   : "POST", 
      "dataType" : "html",
      "beforeSend": function(){
        parent.window.$('.loader-wrap').removeClass('d-none');          
        parent.window.$(".modal").modal("show");                  
        parent.window.$(".modal-title").html("Cari Transaksi");
        parent.window.$("#modaltrigger").val("iframe-page-mutasi");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');          
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);  
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');            
        parent.window._transaksidatatable('view_mutasi_barang');
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
      window.open(`${base_url}Laporan/preview/page-mutasi/${$("#id").val()}`)    
  });

  $('#id').on('change',function(){
    var idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });  

  $(this).on("keyup", "input[name^='qty']", async function(){
      let _idx = $(this).index('.qty');
      let jumlah = await _hitungJumlahDetil(_idx);
      _hitungsubtotal();
      return;
  });

  $(this).on("select2:select", "select[name^='item']", function(){

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.item');

      $.ajax({ 
        "url"    : base_url+"STK_Mutasi_Barang/get_item", 
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
          $("select[name^='satuan']").eq(_idx).attr('disabled','disabled');                       
          $("input[name^='harga']").eq(_idx).val(result.data[0]['harga']);    
          
          if(result.data[0]['harga']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');

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

/* ========================================================================================== */

var _inputFormat = () => {
  Component_Inputmask_Numeric('.numeric');
  Component_Inputmask_Numeric_Flexible('.qty,#tqty', $("#decimalqty").val());    
  Component_Select2('.satuan',`${base_url}Select_Master/view_satuan`,'form_satuan','Satuan');  
  Component_Select2_Item('.item',`${base_url}Select_Master/view_item`,'form_item','Item');  
}

var _clearForm = () => {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .noclear").val('');
  $(":checkbox").prop("checked", false); 
  $('.select2').val('').change();    
  $('#namakontak,#namakontak2').html("");
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
      newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";
      newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";      
      newrow += "<td class=\"d-none\"><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></td>";      
      newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
      newrow += "<td><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
      newrow += "<td class=\"d-none\"><input type=\"hidden\" name=\"idrefdet[]\" class=\"idrefdet\"><input type=\"text\" name=\"refnodetil[]\" class=\"refnodetil form-control form-control-sm\" autocomplete=\"off\" readonly></td>";      
      newrow += "<td class=\"d-none\"><select name=\"proyekdetil[]\" class=\"proyekdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";
      newrow += "<td class=\"d-none\"><select name=\"gudangdetil[]\" class=\"gudangdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";      
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tdetil tbody').append(newrow);
}

/**/

/* CRUD
/* ========================================================================================== */
var _IsValid = () => {

    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Penerima harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return 0;
    }
    if ($('#gudangtujuan').val()=='' || $('#gudangtujuan').val()==null){
      $('#gudangtujuan').attr('data-title','Gudang tujuan harus diisi !');      
      $('#gudangtujuan').tooltip('show');
      $('#gudangtujuan').focus();
      return 0;
    }            
    if($('#idkontak2').val()==''){
      $('#kontak2').attr('data-title','Pengirim harus diisi !');
      $('#kontak2').tooltip('show');
      $('#kontak2').focus();
      return 0;
    }    
    if ($('#gudangasal').val()=='' || $('#gudangasal').val()==null){
      $('#gudangasal').attr('data-title','Gudang asal harus diisi !');      
      $('#gudangasal').tooltip('show');
      $('#gudangasal').focus();
      return 0;
    }    
    if ($('#uraian').val()==''){
      $('#uraian').attr('data-title','Uraian harus diisi !');      
      $('#uraian').tooltip('show');
      $('#uraian').focus();
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
    "url"    : base_url+"STK_Mutasi_Barang/deletedata", 
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
  });  
}

var _saveData = () => {

  const id = $("#id").val(),
        tgl = $("#tgl").val(),
        nomor = $("#nomor").val(),
        kontak = $("#idkontak").val(),
        karyawan = $("#idkontak2").val(),
        status = $("#status").val(),            
        gudangtujuan = $("#gudangtujuan").val(),
        gudangasal = $("#gudangasal").val(),      
        uraian = $("#uraian").val(),
        noref = $("#refnomor").val();

  var detil = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value,
               qty:Number($("input[name^='qty']").eq(index).val().split('.').join('').toString().replace(',','.')),
               satuan:$("select[name^='satuan']").eq(index).val(),               
               harga:Number($("input[name^='harga']").eq(index).val().split('.').join('').toString().replace(',','.')),
               diskon:Number($("input[name^='diskon']").eq(index).val().split('.').join('').toString().replace(',','.')),
               persen:Number($("input[name^='persen']").eq(index).val().split('.').join('').toString().replace(',','.')),               
               catatan:$("textarea[name^='catatan']").eq(index).val(),
               noref:$("input[name^='idrefdet']").eq(index).val(),
               proyek:$("select[name^='proyekdetil']").eq(index).val(),                              
               gudang:$("select[name^='gudangdetil']").eq(index).val()               
             });
  });

  detil = JSON.stringify(detil);

  const totalqty = Number($("#tqty").val().split('.').join('').toString().replace(',','.')),
        totalsub = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.'));

  var rey = new FormData();

  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak);
  rey.set('karyawan',karyawan);    
  rey.set('gudangtujuan',gudangtujuan); 
  rey.set('gudangasal',gudangasal);   
  rey.set('uraian',uraian);   
  rey.set('noref',noref);     
  rey.set('status',status);      
  rey.set('totalqty',totalqty);      
  rey.set('totalsub',totalsub);
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"STK_Mutasi_Barang/savedata", 
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
      console.error(xhr.responseText);      
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
                  window.open(`${base_url}Laporan/preview/page-mutasi/${result.nomor}`)
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

var _getDataTransaksi = (id) => {

  if(id=='' || id==null) return;    

  $.ajax({ 
      "url"    : base_url+"STK_Mutasi_Barang/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(){
        parent.window.toastr.error("Error : Gagal mengambil data transaksi mutasi barang !");      
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

          const gudangtujuan = $("<option selected='selected'></option>").val(result.data[0]['idgudangtujuan']).text(result.data[0]['gudangtujuan']),
                gudangasal = $("<option selected='selected'></option>").val(result.data[0]['idgudangasal']).text(result.data[0]['gudangasal']);                                  

          $('#id').val(result.data[0]['id']);            
          $('#nomor').val(result.data[0]['nomor']);
          $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
//          $('#idreferensi').val(result.data[0]['orderid']);                        
          $('#refnomor').val(result.data[0]['noref']);            
          $('#uraian').val(result.data[0]['uraian']);            
          $('#idkontak').val(result.data[0]['kontakid']);
          $('#kontak').val(result.data[0]['kontakkode']);
          $('#namakontak').html(result.data[0]['kontak']);
          $('#idkontak2').val(result.data[0]['idkaryawan']);
          $('#kontak2').val(result.data[0]['kodekaryawan']);
          $('#namakontak2').html(result.data[0]['namakaryawan']);                                              
          $('#gudangtujuan').append(gudangtujuan).trigger('change');            
          $('#gudangasal').append(gudangasal).trigger('change');                      
          $('#status').val(result.data[0]['status']);        

          var rows = 0, 
              _tqty = 0,
              _tsubtotal = 0;
          
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

            $('#tqty').val(_tqty.toString().replace('.',','));             
            $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));
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
    })
}

/**/

  if(qparam.get('id')==null){
      _clearForm();
      _addRow();
      _inputFormat();
      _formState1();  
  }else{
      _clearForm();
      _formState2();  
      $("#id").val(qparam.get('id')).trigger('change');          
  }

});

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

window._hapusbaris = (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  _hitungsubtotal();
}