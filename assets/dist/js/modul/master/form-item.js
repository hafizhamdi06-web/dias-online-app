var _inputFormat = () => {
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
      return value;
    },
    removeMaskOnSubmit:false
  });

  $('.qty').inputmask({
    alias:'numeric',
    digits:$("#decqty").val(),
    digitsOptional:false,
    isNumeric: true,      
    prefix:'',
    groupSeparator:".",
    placeholder: '0',
    radixPoint:",",
    autoGroup:true,
    autoUnmask:true,
    onBeforeMask: function (value, opts) {
      return value;
    },
    removeMaskOnSubmit:false
  });

  $('.datepicker').datepicker();

  $('.datepicker').inputmask({
    alias:'dd/mm/yyyy',
    mask: "1-2-y", 
    placeholder: "_", 
    leapday: "-02-29", 
    separator: "-"
  })

  $('.gudangsa').select2({
       "allowClear": true,
       "theme":"bootstrap4",
       "dropdownParent": $('#tabItem'),          
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
      }
  });

  $('.kontaksa').select2({
      "allowClear": true,
      "theme":"bootstrap4",
      "dropdownParent": $('#tabItem'),
      "ajax": {
          "url": base_url+"Select_Master/view_kontak",
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
       "templateResult": kontakSelect,    
  });

}    

var _addRow = () => {
  let _harga = Number($("#hargabeli").val().split('.').join('').toString().replace(',','.'));
  let newrow = " <tr>";
      newrow += "<td><input type=\"text\" name=\"nomorsa[]\" class=\"nomorsa form-control form-control-sm\" placeholder=\"[Auto]\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\"></td>";  
      newrow += "<td><input type=\"text\" name=\"tanggalsa[]\" class=\"tanggalsa form-control form-control-sm datepicker\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\"></td>";      
      newrow += "<td><select name=\"gudangsa[]\" class=\"gudangsa form-control select2 form-control-sm\" style=\"width:100%;\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";  
      newrow += `<td><input type=\"tel\" name=\"hargasa[]\" class=\"hargasa form-control form-control-sm numeric\" autocomplete=\"off\" value="${_harga}"></td>`;
      newrow += "<td><input type=\"tel\" name=\"qtysa[]\" class=\"qtysa form-control form-control-sm qty\" autocomplete=\"off\" value=\"0\"></td>";      
      newrow += "<td><select name=\"kontaksa[]\" class=\"kontaksa form-control select2 form-control-sm\" style=\"width:100%;\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>"; 
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tsaldo tbody').append(newrow);
}

var _hapusbaris = (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  //_hitungsubtotal();
}

$("#dTglSaldo").click(function() {
  if($(this).attr('role')) {
    $("#tglsa").focus();
  }
});

$('#jenis').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItem'),
     "minimumResultsForSearch": "Infinity"                                         
}); 

$('#tipe').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItem'),
     "minimumResultsForSearch": "Infinity"                                     
}); 

$('#status').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItem'),
     "minimumResultsForSearch": "Infinity"                    
}); 

$('#satuanD').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItem'),          
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
    }
});

$('#satuanDef').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItem'),          
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
    }
});

$('#kategori').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "ajax": {
        "url": base_url+"Select_Master/view_kategori_item",
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
    }
});

$('#subkategori').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "ajax": {
        "url": base_url+"Select_Master/view_subkategori_item",
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
    }
});

$('#coapersediaan,#coapendapatan,#coahpp').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItem'),          
     "ajax": {
        "url": base_url+"Select_Master/view_coa",
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
     "templateResult": textSelect,              
});  

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

$("#baddrow").click(function() {
  _addRow();
  _inputFormat();
  $("input[name^='nomor']").last().focus();            
});

$("#jenis").on("select2:select", function(){
  if(this.value==1){
    $("#coapersediaan").val('').trigger('change');
    $("#coapendapatan").val('').trigger('change');    
    $("#coahpp").val('').trigger('change');        
    $("#labelcoa1").html("Akun Biaya *");    
    $("#divcoahpp").addClass("d-none");
  }else{
    $("#labelcoa1").html("Akun Persediaan *");    
    $("#divcoahpp").removeClass("d-none");
    get_default_coa('ITP');
    get_default_coa('ITT');
    get_default_coa('ITH');         
  }
});

function textSelect(par){
  if(!par.id){
    return par.text;
  }
  var $par = $('<span>('+par.kode+') '+par.text+'</span>');
  return $par;
}

function kontakSelect(par){
  if(!par.id){
    return par.text;
  }
  var $par = $('<div class=\'pb-1\' style=\'border-bottom:1px dotted #86cfda;\'><span class=\'font-weight-normal\'>'+par.kode+'</span><br/><span class=\'font-weight-bold text-sm\'>'+par.text+'</span></div>');
  return $par;
}  

function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);       
  $('.select2').val('').change();                  
}       

function get_default_coa(id){
    $.ajax({ 
      "url"    : base_url+"Master_Item/getdefaultcoa",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "error"  : function(xhr,status,error){
        $(".main-modal-body").html('');        
        toastr.error("Perbaiki kesalahan ini : "+xhr.status+" "+error);
        console.error(xhr.responseText);
        return;
      },
      "success" : function(result) {
        if (typeof result.pesan !== 'undefined') { // Jika ada pesan maka tampilkan pesan
          toastr.error(result.pesan);
          return;
        } else { // Jika tidak ada pesan tampilkan json ke form
          const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);

          if(result.data[0]['coa']!==null){
            if(id=='ITP') {
              $('#coapersediaan').append(_coa);                      
            }            
            if(id=='ITT') {
              $('#coapendapatan').append(_coa);                      
            }                        
            if(id=='ITH') {
              $('#coahpp').append(_coa);                      
            }                                    
          }

          /**/
          return;
        }
    } 
  })  
}

var _IsValid = function(){
    if ($('#nomor').val()==''){
      $('#nomor').attr('data-title','Kode barang/jasa harus diisi !');      
      $('#nomor').tooltip('show');
      $('#nomor').focus();
      return 0;
    }

    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama barang/jasa harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }

    if ($('#satuanD').val()=='' || $('#satuanD').val()==null){
        toastr.error('Pesan : Satuan dasar harus diisi !');
        return 0;
    }    

    if($("#jenis").val()==1){
      if ($('#coapersediaan').val()=='' || $('#coapersediaan').val()==null){
        toastr.error('Pesan : Akun biaya harus diisi !');
        return 0;
      }    
      if ($('#coapendapatan').val()=='' || $('#coapendapatan').val()==null){
        toastr.error('Pesan : Akun pendapatan harus diisi !');
        return 0;
      }          
    } else {
      if ($('#coapersediaan').val()=='' || $('#coapersediaan').val()==null){
        toastr.error('Pesan : Akun persediaan harus diisi !');
        return 0;
      }    
      if ($('#coapendapatan').val()=='' || $('#coapendapatan').val()==null){
        toastr.error('Pesan : Akun pendapatan harus diisi !');
        return 0;
      }          
      if ($('#coahpp').val()=='' || $('#coahpp').val()==null){
        toastr.error('Pesan : Akun hpp harus diisi !');
        return 0;
      }          
    }

    return 1;
};

var _saveData = function(){
  const id = $("#id").val(),
        kode = $("#nomor").val(),
        barcode = $("#barcode").val(),        
        nama = $("#nama").val(),
        satuan = $("#satuanD").val(),
        satuand = $("#satuanDef").val(),
        jenis = $("#jenis").val(),
        tipe = $("#tipe").val(),    
        stokmin = Number($("#stokmin").val().split('.').join('').toString().replace(',','.')),
        stokmaks = Number($("#stokmaks").val().split('.').join('').toString().replace(',','.')),                
        stokreorder = Number($("#stokreorder").val().split('.').join('').toString().replace(',','.')),
        hargabeli = Number($("#hargabeli").val().split('.').join('').toString().replace(',','.')),
        hargajual1 = Number($("#hargajual1").val().split('.').join('').toString().replace(',','.')),
        hargajual2 = Number($("#hargajual2").val().split('.').join('').toString().replace(',','.')),
        hargajual3 = Number($("#hargajual3").val().split('.').join('').toString().replace(',','.')),
        hargajual4 = Number($("#hargajual4").val().split('.').join('').toString().replace(',','.')), 
        coapersediaan = $("#coapersediaan").val(),
        coapendapatan = $("#coapendapatan").val(),                                                   
        coahpp = $("#coahpp").val(),
        status = $("#status").val();

  var saldoawal = [],
      isValid = null,
      _elFocus = null;

  $("input[name^='nomorsa']").each(function(index,element){
    let _qty = Number($("input[name^='qtysa']").eq(index).val().split('.').join('').toString().replace(',','.'));
    let _tgl = $("input[name^='tanggalsa']").eq(index).val();
    let _gudang = $("select[name^='gudangsa']").eq(index).val();
    let _harga = Number($("input[name^='hargasa']").eq(index).val().split('.').join('').toString().replace(',','.'));
    let _kontak = $("select[name^='kontaksa']").eq(index).val();

    if (_tgl == '')  {
      isValid = "Tanggal pada baris ke-" + Number(index+1) + " harus diisi"; 
      _elFocus = $("input[name^='tanggalsa']").eq(index);
      return;
    } else if (_gudang == null) {
      isValid = "Gudang pada baris ke-" + Number(index+1) + " harus diisi"; 
      _elFocus = $("select[name^='gudangsa']").eq(index);        
      return;
    } else if (_harga <= 0) {
      isValid = "Harga pokok pada baris ke-" + Number(index+1) + " masih 0";
      _elFocus = $("input[name^='hargasa']").eq(index);
      return;                              
    } else if (_qty <= 0) {
      isValid = "Qty pada baris ke-" + Number(index+1) + " masih 0"; 
      _elFocus = $("input[name^='qtysa']").eq(index);
      return;
    } else if(_kontak == null) {
      isValid = "Kontak pada baris ke-" + Number(index+1) + " harus diisi"; 
      _elFocus = $("select[name^='kontaksa']").eq(index);
      return;
    }        

    saldoawal.push({
             nomor:this.value,
             tanggal:_tgl,
             gudang:_gudang,        
             harga:_harga,
             qty: _qty,               
             kontak:_kontak               
    })     
  })  

  if(isValid != null) {
    Swal.fire({
      title: isValid,
      showDenyButton: false,
      showCancelButton: false,
      confirmButtonText: `Tutup`,
    }).then((result) => {
        setTimeout(function(){
          _elFocus.focus();
        },300)
    })
    return;                  
  }

  saldoawal = JSON.stringify(saldoawal);  

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('barcode',barcode);  
  rey.set('nama',nama);
  rey.set('satuan',satuan);
  rey.set('satuand',satuand);
  rey.set('jenis',jenis);
  rey.set('tipe',tipe);
  rey.set('stokmin',stokmin);
  rey.set('stokmaks',stokmaks);    
  rey.set('stokreorder',stokreorder);
  rey.set('hargabeli',hargabeli);
  rey.set('hargajual1',hargajual1);
  rey.set('hargajual2',hargajual2);
  rey.set('hargajual3',hargajual3);
  rey.set('hargajual4',hargajual4);      
  rey.set('coapersediaan',coapersediaan);        
  rey.set('coapendapatan',coapendapatan);          
  rey.set('coahpp',coahpp);            
  rey.set('status',status);  
  rey.set('saldoawal',saldoawal);    

  $.ajax({ 
    "url"    : base_url+"Master_Item/savedata", 
    "type"   : "POST", 
    "data"   : rey,
    "processData": false,
    "contentType": false,
    "cache"    : false,
    "beforeSend" : function(){
      $(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      $(".loader-wrap").addClass("d-none");
      toastr.error("Error : "+xhr.status+" "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        $('#modal').modal('hide');                
        toastr.success("Data item berhasil disimpan");                  
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  })
};

function _getData(id){
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"Master_Item/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        $('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(xhr,status,error){
        $(".main-modal-body").html('');        
        toastr.error("Error : "+xhr.status+" "+error);
        console.error(xhr.responseText);
        $('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        if (typeof result.pesan !== 'undefined') {
          toastr.error(result.pesan);
          $('.loader-wrap').addClass('d-none'); 
          return;
        } else {
          const _satuan = $("<option selected='selected'></option>").val(result.data[0]['idsatuan']).text(result.data[0]['satuan']),
                _satuanD = $("<option selected='selected'></option>").val(result.data[0]['idsatuand']).text(result.data[0]['satuand']),
                _coapersediaan = $("<option selected='selected'></option>").val(result.data[0]['idcoapersediaan']).text(result.data[0]['coapersediaan']),
                _coapendapatan = $("<option selected='selected'></option>").val(result.data[0]['idcoapendapatan']).text(result.data[0]['coapendapatan']),
                _coahpp = $("<option selected='selected'></option>").val(result.data[0]['idcoahpp']).text(result.data[0]['coahpp']);

          $('#id').val(result.data[0]['id']);            
          $('#nomor').val(result.data[0]['kode']);
          $('#barcode').val(result.data[0]['barcode']);          
          $('#nama').val(result.data[0]['nama']);          
          if(result.data[0]['satuan']!==null) $('#satuanD').append(_satuan);          
          if(result.data[0]['satuand']!==null) $('#satuanDef').append(_satuanD); 
          $('#stokmin').val(result.data[0]['stokmin'].replace(".", ","));                                                     
          if(result.data[0]['stokmin']==0) $("#stokmin").attr('placeholder','0,00');            
          $('#stokmaks').val(result.data[0]['stokmaks'].replace(".", ","));                                                     
          if(result.data[0]['stokmaks']==0) $("#stokmaks").attr('placeholder','0,00');                      
          $('#stoktotal').val(result.data[0]['stoktotal'].replace(".", ","));                                                     
          if(result.data[0]['stoktotal']==0) $("#stoktotal").attr('placeholder','0,00');                                
          $('#stokreorder').val(result.data[0]['reorder'].replace(".", ","));                                                     
          if(result.data[0]['reorder']==0) $("#stokreorder").attr('placeholder','0,00');                                
          $('#hargabeli').val(result.data[0]['hargabeli'].replace(".", ","));                                                     
          if(result.data[0]['hargabeli']==0) $("#hargabeli").attr('placeholder','0,00');
          $('#hargajual1').val(result.data[0]['hargajual1'].replace(".", ","));                                                     
          if(result.data[0]['hargajual1']==0) $("#hargajual1").attr('placeholder','0,00');
          $('#hargajual2').val(result.data[0]['hargajual2'].replace(".", ","));                                                     
          if(result.data[0]['hargajual2']==0) $("#hargajual2").attr('placeholder','0,00');
          $('#hargajual3').val(result.data[0]['hargajual3'].replace(".", ","));                                                     
          if(result.data[0]['hargajual3']==0) $("#hargajual3").attr('placeholder','0,00');
          $('#hargajual4').val(result.data[0]['hargajual4'].replace(".", ","));                                                     
          if(result.data[0]['hargajual4']==0) $("#hargajual4").attr('placeholder','0,00');                                        
          $('#jenis').val(result.data[0]['jenis']).trigger('change');
          $('#tipe').val(result.data[0]['tipe']).trigger('change');          
          $('#status').val(result.data[0]['status']).trigger('change');                    

          if(result.data[0]['coapersediaan'] != null) $('#coapersediaan').append(_coapersediaan);          
          if(result.data[0]['coapendapatan'] != null) $('#coapendapatan').append(_coapendapatan);                    
          if(result.data[0]['coahpp'] != null) $('#coahpp').append(_coahpp);                    

          var rows = 0;
          $.each(result.data2, function() {
            var _kontaksa = $("<option selected='selected'></option>").val(result.data2[rows]['idkontaksa']).text(result.data2[rows]['kontaksa']);                        
            var _gudangsa = $("<option selected='selected'></option>").val(result.data2[rows]['idgudangsa']).text(result.data2[rows]['gudangsa']);

            if(result.data2[rows]['nomorsa'] != null) {
              _addRow();
              _inputFormat();

              $("input[name^='nomorsa']").eq(rows).val(result.data2[rows]['nomorsa']);
              $("select[name^='gudangsa']").eq(rows).append(_gudangsa).trigger('change');
              $("select[name^='kontaksa']").eq(rows).append(_kontaksa).trigger('change');
              $("input[name^='hargasa']").eq(rows).val(result.data2[rows]['hargasa'].replace(".", ","));                             
              $("input[name^='qtysa']").eq(rows).val(result.data2[rows]['qtysa'].replace(".", ","));            
              $("input[name^='tanggalsa']").eq(rows).val(result.data2[rows]['tglsa']); 
            }
            rows++;
          });          

          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}

_inputFormat();

setTimeout(function (){
        $('#nomor').focus();
}, 500);                   