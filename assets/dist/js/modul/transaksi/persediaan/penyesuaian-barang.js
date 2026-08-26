/* ========================================================================================== */
/* File Name : penyesuaian-barang.js
/* Info Lain : 
/* ========================================================================================== */
var _harga=0;

$(function() {

  const qparam = new URLSearchParams(this.location.search);    
  
  setupHiddenInputChangeListener($('#id')[0]);

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
    parent.window.$(".loader-wrap").addClass("d-none");
  }

  $('.tab-wrap').overlayScrollbars({
  className: "os-theme-dark",
  overflowBehavior : {
    x :'hidden',
    y :'scroll' 
  },
  scrollbars : {
    autoHide : 'scroll',
    autoHideDelay : 800,
    snapHandle:true             
  }
  });
  
/* Format Select2 Header Transaksi
/* ========================================================================================== */

  $('#gudang').select2({
      "allowClear": false,
      "theme":"bootstrap4",
      "allowAddLink": true,
      "addLink":"form_gudang",
      "linkTitle":"Gudang",
      "ajax": {
          "url": base_url+"Select_Master/view_gudang",
          "type": "post",
          "dataType": "json",                                       
          "delay": 800,
          "data": function(params) {
            return {
              search: params.term
            }
          },
          "processResults": function (data, page) {
          return {
            results: data
          };
        },
      },
  });

  $('#jenis').select2({
      "allowClear": false,
      "theme":"bootstrap4",
      "ajax": {
          "url": base_url+"Select_Master/view_jenis_penyesuaian_barang",
          "type": "post",
          "dataType": "json",                                       
          "delay": 800,
          "data": function(params) {
            return {
              search: params.term
            }
          },
          "processResults": function (data, page) {
          return {
            results: data
          };
        },
      },
  });

/**/

/* Format Input dan Select2 Pada Tabel Detil Transaksi
/* Select2 dan inputmask number
/* ========================================================================================== */
  $('.datepicker').inputmask({
    alias:'dd/mm/yyyy',
    mask: "1-2-y", 
    placeholder: "_", 
    leapday: "-02-29", 
    separator: "-"
  });    

  function _inputFormat(){

    $('.numeric').inputmask({
      alias:'numeric',
      digits:'2',
      digitsOptional:false,
      isNumeric: true,      
      prefix:'',
      groupSeparator:".",
      placeholder: '0',
      radixPoint:",",
      autoGroup:true,
      autoUnmask:true,
      onBeforeMask: function (value, opts) {
        //console.dir(opts);
        return value;
      },
      removeMaskOnSubmit:false
    });

    $('.qtyin, .qtyout, #tqtyin, #tqtyout').inputmask({
      alias:'numeric',
      digits:$("#decimalqty").val(),
      digitsOptional:false,
      isNumeric: true,      
      prefix:'',
      groupSeparator:".",
      placeholder: '0',
      radixPoint:",",
      autoGroup:true,
      autoUnmask:true,
      onBeforeMask: function (value, opts) {
        //console.dir(opts);
        return value;
      },
      removeMaskOnSubmit:false
    });    

    $('.satuan').select2({
        "allowClear": false,
        "theme":"bootstrap4",
        "allowAddLink": true,
        "addLink":"form_satuan",
        "linkTitle":"Satuan",
        "ajax": {
            "url": base_url+"Select_Master/view_satuan",
            "type": "post",
            "dataType": "json",                                       
            "delay": 800,
            "data": function(params) {
              return {
                search: params.term
              }
            },
            "processResults": function (data, page) {
            return {
              results: data
            };
          },
        },
    });

    $('.item').select2({
        "allowClear": false,
        "theme":"bootstrap4",
        "allowAddLink": true,
        "addLink":"form_item",
        "linkTitle":"Item",
        "linkSize":"modal-lg",                                               
        "ajax": {
            "url": base_url+"Select_Master/view_item",
            "type": "post",
            "dataType": "json",                                       
            "delay": 800,
            "data": function(params) {
              return {
                search: params.term
              }
            },
            "processResults": function (data, page) {
            return {
              results: data
            };
          },
        },
         "templateResult": itemSelect,    
    });

  }

  function itemSelect(par){
    if(!par.id){
      return par.text;
    }
    var $par = $('<div class=\'pb-1\' style=\'border-bottom:1px dotted #86cfda;\'><span class=\'font-weight-bold\' style=\'opacity:.8;\'>'+par.kode+'</span><br/><span>'+par.text+'</span></div>');
    return $par;
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
    location.href=base_url+"page/stokadjData";      
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
          parent.window.$("#modaltrigger").val("iframe-page-stokadj");
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
          parent.window.$("#modaltrigger").val("iframe-page-stokadj");
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
      window.open(`${base_url}Laporan/preview/page-stokadj/${$("#id").val()}`)    
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
        parent.window.$("#modaltrigger").val("iframe-page-stokadj");        
      },       
      "error": function(){
        parent.window.$('.loader-wrap').addClass('d-none');          
        console.log('error menampilkan modal cari transaksi...');
        return;
      },
      "success": function(result) {
        parent.window.$(".main-modal-body").html(result);      
        parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');        
        parent.window._transaksidatatable('view_penyesuaian_barang');
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
      /* Read more about isConfirmed, isDenied below */
      if (result.isConfirmed) {
        _deleteData();      
      }
    })
  });

  $("#bsave").click(function() {
    if (_IsValid()===0) return;
    _saveData();
  });

  $('#id').on('change',function(e){
    var idtrans = $(this).val();
    _formState2();
    _getDataTransaksi(idtrans);
  });  

  $(this).on("keyup", "input[name^='qtyin']", async function(e){
      let _idx = $(this).index('.qtyin');
      if(Number($("input[name^='qtyin']").eq(_idx).val().split('.').join('').toString().replace(',','.'))>0){
        $("input[name^='qtyout']").eq(_idx).val('0').attr('placeholder','0,00');
        if(Number($("input[name^='harga']").eq(_idx).val().split('.').join('').toString().replace(',','.'))==0){             
          $("input[name^='harga']").eq(_idx).val(_harga).prop('readonly',false);                
        }
      }

      let jumlah = await _hitungJumlahDetil(_idx);
      _hitungsubtotal();
      return;
  });

  $(this).on("keyup", "input[name^='qtyout']", async function(e){
      let _idx = $(this).index('.qtyout');

      if(Number($("input[name^='qtyout']").eq(_idx).val().split('.').join('').toString().replace(',','.'))>0){
        $("input[name^='qtyin']").eq(_idx).val('0').attr('placeholder','0,00');        
        $("input[name^='harga']").eq(_idx).val('0').attr('placeholder','0,00').prop('readonly',true);        
      }

      let jumlah = await _hitungJumlahDetil(_idx);
      _hitungsubtotal();
      return;
  });  

  $(this).on("select2:select", "select[name^='item']", function(e){
      //alert($(this).val());return;

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.item');

      $.ajax({ 
        "url"    : base_url+"STK_Penyesuaian_Barang/get_item", 
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
          _harga = result.data[0]['harga'];

          if(result.data[0]['harga']==0) $("input[name^='harga']").eq(_idx).attr('placeholder','0,00');

          //Hitung Total Quantity Dan Sub Total
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

/**/


/* Other Function 
/* Etc
/* ========================================================================================== */

function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio, .noclear").val('');
  $(":checkbox").prop("checked", false); 
  $('.select2').val('').change();    
  $('#namakontak').html("");
  $('.datepicker').datepicker('setDate','dd-mm-yy'); 
  $('.total').val('0');  
  $('#tdetil tbody').html('');               
}

function _formState1(){
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

function _formState2(){
  $('.btn-step2').removeClass('disabled');
  $('.btn-step1').addClass('disabled'); 
  $('#baddrow').attr('disabled','disabled');     
  $(':input').not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
  $(':input').not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff"); 
  $('.input-group-append').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
}

function _addRow(){
  var newrow = " <tr>";
      newrow += "<td><select name=\"item[]\" class=\"item form-control select2 form-control-sm\" style=\"width:100%\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
      newrow += "<td><input type=\"tel\" name=\"qtyin[]\" class=\"qtyin form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><input type=\"tel\" name=\"qtyout[]\" class=\"qtyout form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";      
      newrow += "<td><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
      newrow += "<td class=\"d-none\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></div></td>";      
      newrow += "<td class=\"d-none\"><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></td>";      
      newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
      newrow += "<td><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
      newrow += "<td class=\"d-none\"><input type=\"hidden\" name=\"idrefdet[]\" class=\"idrefdet\"><input type=\"text\" name=\"refnodetil[]\" class=\"refnodetil form-control form-control-sm\" autocomplete=\"off\" readonly></td>";      
      newrow += "<td class=\"d-none\"><select name=\"proyekdetil[]\" class=\"proyekdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";
      newrow += "<td class=\"d-none\"><select name=\"gudangdetil[]\" class=\"gudangdetil form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";      
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tdetil tbody').append(newrow);
}
/**/

/* Fungsi CRUD
/* ***********
/* ========================================================================================== */
var _IsValid = (function(){
    //Cek Header Input
    if($('#idkontak').val()==''){
      $('#kontak').attr('data-title','Kontak harus diisi !');
      $('#kontak').tooltip('show');
      $('#kontak').focus();
      return 0;
    }
    if ($('#gudang').val()=='' || $('#gudang').val()==null){
      $('#gudang').attr('data-title','Gudang harus diisi !');      
      $('#gudang').tooltip('show');
      $('#gudang').focus();
      return 0;
    }            
    if ($('#jenis').val()=='' || $('#jenis').val()==null){
      $('#jenis').attr('data-title','Jenis penyesuaian harus diisi !');      
      $('#jenis').tooltip('show');
      $('#jenis').focus();
      return 0;
    }                
    if ($('#uraian').val()==''){
      $('#uraian').attr('data-title','Uraian harus diisi !');      
      $('#uraian').tooltip('show');
      $('#uraian').focus();
      return 0;
    }
    //Cek Detil Input
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
});

var _deleteData = (function(){
  const id = $("#id").val();
  const nomor = $("#nomor").val();  

  $.ajax({ 
    "url"    : base_url+"STK_Penyesuaian_Barang/deletedata", 
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
});

var _saveData = (function(){

const id = $("#id").val(),
      tgl = $("#tgl").val(),
      nomor = $("#nomor").val(),
      kontak = $("#idkontak").val(),
      status = $("#status").val(),            
      gudang = $("#gudang").val(),
      uraian = $("#uraian").val(),
      noref = $("#refnomor").val(),
      jenis = $("#jenis").val();

  var detil = [];

  $("select[name^='item']").each(function(index,element){  
      detil.push({
               item:this.value,
               qtyin:Number($("input[name^='qtyin']").eq(index).val().split('.').join('').toString().replace(',','.')),
               qtyout:Number($("input[name^='qtyout']").eq(index).val().split('.').join('').toString().replace(',','.')),               
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

  //Total Transaksi
  var total = Number($("#tsubtotal").val().split('.').join('').toString().replace(',','.'));

  var rey = new FormData();

  rey.set('id',id);
  rey.set('tgl',tgl);
  rey.set('nomor',nomor);
  rey.set('kontak',kontak); 
  rey.set('gudang',gudang);   
  rey.set('uraian',uraian);   
  rey.set('noref',noref);    
  rey.set('jenis',jenis);       
  rey.set('status',status);      
  rey.set('total',total);        
  rey.set('detil',detil);

  $.ajax({ 
    "url"    : base_url+"STK_Penyesuaian_Barang/savedata", 
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
                  window.open(`${base_url}Laporan/preview/page-stokadj/${result.nomor}`)
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

});

function _getDataTransaksi(id){
  //alert(id);
  if(id=='' || id==null) return;    

  $.ajax({ 
    "url"    : base_url+"STK_Penyesuaian_Barang/getdata",       
    "type"   : "POST", 
    "dataType" : "json", 
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : function(){
      parent.window.toastr.error("Error : Gagal mengambil data transaksi penyesuaian barang !");      
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : function(result) {

      if (typeof result.pesan !== 'undefined') { // Jika ada pesan maka tampilkan pesan
        alert(result.pesan);
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      } else { // Jika tidak ada pesan tampilkan data
        
        /*Atur Baris Detil Transaksi*/
        $('#tdetil tbody').html('');
        for (let i = 0; i < result.data.length; i++) {
          _addRow();
        }
        _inputFormat();
        /**/

        /* Isi Header Transaksi */
          const gudang = $("<option selected='selected'></option>").val(result.data[0]['idgudang']).text(result.data[0]['gudang']),
                jenis = $("<option selected='selected'></option>").val(result.data[0]['idjenis']).text(result.data[0]['jenis']);                                  

          $('#id').val(result.data[0]['id']);            
          $('#nomor').val(result.data[0]['nomor']);
          $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
          $('#refnomor').val(result.data[0]['noref']);            
          $('#uraian').val(result.data[0]['uraian']);            
          $('#idkontak').val(result.data[0]['kontakid']);
          $('#kontak').val(result.data[0]['kontakkode']);
          $('#namakontak').html(result.data[0]['kontak']);
          $('#gudang').append(gudang).trigger('change');            
          $('#jenis').append(jenis).trigger('change');                      
          $('#status').val(result.data[0]['status']);        
        /**/

        /* Isi Detil dan Footer Transaksi */
        var rows = 0, 
            _tqtyin = 0,
            _tqtyout = 0,            
            _tsubtotal = 0;
        
        $.each(result.data, function() {

          var _item = $("<option selected='selected'></option>").val(result.data[rows]['iditem']).text(result.data[rows]['namaitem']),
              _satuan = $("<option selected='selected'></option>").val(result.data[rows]['idsatuan']).text(result.data[rows]['satuan']);

          $("select[name^='item']").eq(rows).append(_item).trigger('change');   
          $("select[name^='satuan']").eq(rows).append(_satuan).trigger('change');               
          $("input[name^='qtyin']").eq(rows).val(result.data[rows]['qtydetilin'].replace(".", ","));            
          $("input[name^='qtyout']").eq(rows).val(result.data[rows]['qtydetilout'].replace(".", ","));                      
          $("input[name^='harga']").eq(rows).val(result.data[rows]['hargadetil'].replace(".", ","));
          $("input[name^='diskon']").eq(rows).val(result.data[rows]['diskon'].replace(".", ","));
          $("input[name^='persen']").eq(rows).val(result.data[rows]['persendiskon'].replace(".", ","));
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotaldetil'].replace(".", ","));            
          $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catdetil']); 

          _tqtyin += Number(result.data[rows]['qtydetilin']);
          _tqtyout += Number(result.data[rows]['qtydetilout']);          
          _tsubtotal += Number(result.data[rows]['subtotaldetil']);            

          //atur placeholder numeric jika 0
          if(result.data[rows]['qtydetilin']==0) $("input[name^='qtyin']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['qtydetilout']==0) $("input[name^='qtyout']").eq(rows).attr('placeholder','0,00');                      
          if(result.data[rows]['hargadetil']==0) $("input[name^='harga']").eq(rows).attr('placeholder','0,00');                        
          if(result.data[rows]['diskon']==0) $("input[name^='diskon']").eq(rows).attr('placeholder','0,00');
          if(result.data[rows]['persendiskon']==0) $("input[name^='persen']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['subtotaldetil']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');

          rows++;
        });

          $('#tqtyin').val(_tqtyin.toString().replace('.',','));             
          $('#tqtyout').val(_tqtyout.toString().replace('.',','));                       
          $('#tsubtotal').val(_tsubtotal.toString().replace('.',','));

          if(_tqtyin==0) $("#tqtyin").attr('placeholder','0,00');            
          if(_tqtyout==0) $("#tqtyout").attr('placeholder','0,00');            
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

function _hitungJumlahDetil(idx){
  let vqtyin = Number($("input[name^='qtyin']").eq(idx).val().split('.').join('').toString().replace(',','.')),
      vqtyout = Number($("input[name^='qtyout']").eq(idx).val().split('.').join('').toString().replace(',','.')),  
      vharga = Number($("input[name^='harga']").eq(idx).val().split('.').join('').toString().replace(',','.')),      
      vsubtotal = 0;

  vsubtotal = (vharga)*(vqtyin+vqtyout);
  vsubtotal = vsubtotal.toString().replace('.',',');  

  if(vsubtotal==0) vsubtotal='0,00';
  $("input[name^='subtotal']").eq(idx).val(vsubtotal).attr('placeholder',vsubtotal);
  
  return vsubtotal;
}

function _hitungsubtotal(){
  let tqtyin = 0, tqtyout = 0, tsubtotal = 0;
  
  $('.item').each(function(index,element) {
    tqtyin += Number($("input[name^='qtyin']").eq(index).val().split('.').join('').toString().replace(',','.')); 
    tqtyout += Number($("input[name^='qtyout']").eq(index).val().split('.').join('').toString().replace(',','.'));     
    tsubtotal += Number($("input[name^='subtotal']").eq(index).val().split('.').join('').toString().replace(',','.'));     
  });  

  tqtyin = tqtyin.toString().replace('.',',');
  tqtyout = tqtyout.toString().replace('.',',');  
  tsubtotal = tsubtotal.toString().replace('.',',');            

  if(tqtyin==0) tqtyin='0,00';
  if(tqtyout==0) tqtyout='0,00';  
  if(tsubtotal==0) tsubtotal='0,00';

  $('#tqtyin').val(tqtyin).attr('placeholder',tqtyin);
  $('#tqtyout').val(tqtyout).attr('placeholder',tqtyout);  
  $('#tsubtotal').val(tsubtotal).attr('placeholder',tsubtotal);      
  return;
}

window._hapusbaris = (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  _hitungsubtotal();
}