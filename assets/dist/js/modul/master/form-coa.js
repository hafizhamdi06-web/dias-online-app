setTimeout(function (){
        $('#tipe').focus();
    }, 500);           

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

  $('.datepicker').datepicker();

  $('.datepicker').inputmask({
    alias:'dd/mm/yyyy',
    mask: "1-2-y", 
    placeholder: "_", 
    leapday: "-02-29", 
    separator: "-"
  })

  $('.kontak').select2({
      "allowClear": true,
      "theme":"bootstrap4",
      "dropdownParent": $('#tabCOA'),
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
  let newrow = " <tr>";
      newrow += "<td><select name=\"kontak[]\" class=\"kontak form-control select2 form-control-sm\" style=\"width:100%;\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
      newrow += "<td><input type=\"text\" name=\"tanggal[]\" class=\"tanggal form-control form-control-sm datepicker\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\"></td>";      
      newrow += "<td><input type=\"tel\" name=\"jumlah[]\" class=\"jumlah form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";      
      newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
      newrow += "</tr>";
  $('#tsaldo tbody').append(newrow);
}

var _hapusbaris = (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
}

$("#dTglSaldo").click(function() {
  if($(this).attr('role')) {
    $("#tglsa").focus();
  }
});

$('#tipe').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabCOA'),     
     "ajax": {
        "url": base_url+"Select_Master/view_coa_tipe",
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

$('#uang').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabCOA'),     
     "ajax": {
        "url": base_url+"Select_Master/view_mata_uang",
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

$('#divisi').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabCOA'),     
     "ajax": {
        "url": base_url+"Select_Master/view_divisi",
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

$('#bank').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabCOA'),     
     "ajax": {
        "url": base_url+"Select_Master/view_bank",
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

$('#indukakun').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabCOA'),     
     "ajax": {
        "url": base_url+"Select_Master/view_coa_induk",
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

$('#gd').select2({
     "minimumResultsForSearch": "Infinity",                 
     "theme":"bootstrap4"
});

$("#tipe,#gd").on("select2:select", function(){
  esaldoawal();  
})

$('#chksub').click(function(){
  if ($('#chksub').is(":checked"))
  {
    $('#indukakun').removeAttr('disabled');
    $('#indukakun').focus();
  } else {
    $('#indukakun').attr('disabled','disabled');
    $('#indukakun').val(null).trigger('change');    
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

$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});

$("#baddrow").click(function() {
  _addRow();
  _inputFormat();
  $("select[name^='kontak']").last().focus();            
});

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#tipe').val()=='' || $('#tipe').val()==null){
      $('#tipe').attr('data-title','Tipe Akun harus diisi !');      
      $('#tipe').tooltip('show');
      $('#tipe').focus();
      return 0;
    }    
    if ($('#nomor').val()==''){
      $('#nomor').attr('data-title','Nomor akun harus diisi !');      
      $('#nomor').tooltip('show');
      $('#nomor').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nomor akun harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }
    return 1;
});

var _saveData = function(){
  const id = $("#id").val(),
        tipe = $("#tipe").val(),
        nomor = $("#nomor").val(),
        nama = $("#nama").val(),
        gd = $("#gd").val(),
        subcoa = $("#indukakun").val(),
        uang = $("#uang").val(),
        bank = $("#bank").val()!==null ? $("#bank").val() : undefined,
        nomorbank = $("#nomorbank").val(),
        divisi = $("#divisi").val()!==null ? $("#divisi").val() : undefined;

  var status = 1,
      kasmasuk = 0,
      kaskeluar = 0,
      saldoawal = [];

  if($("#aktif").prop('checked')==false) status=0;
  if($("#kasmasuk").prop('checked')==true) kasmasuk=1;
  if($("#kaskeluar").prop('checked')==true) kaskeluar=1;

  $("select[name^='kontak']").each(function(index,element){  
      saldoawal.push({
               kontak:this.value,
               tanggal:$("input[name^='tanggal']").eq(index).val(),
               jumlah:Number($("input[name^='jumlah']").eq(index).val().split('.').join('').toString().replace(',','.'))
             });

  }); 

  saldoawal = JSON.stringify(saldoawal); 

//  alert(saldoawal);return;

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('tipe',tipe);
  rey.set('nomor',nomor);
  rey.set('nama',nama);
  rey.set('gd',gd);
  rey.set('subcoa',subcoa);
  rey.set('uang',uang);
  rey.set('bank',bank);
  rey.set('divisi',divisi);  
  rey.set('nomorbank',nomorbank);  
  rey.set('status',status);
  rey.set('kasmasuk',kasmasuk);
  rey.set('kaskeluar',kaskeluar);
  rey.set('saldoawal',saldoawal);

  $.ajax({ 
    "url"    : base_url+"Master_Akun/savedata", 
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
        toastr.success("Data akun berhasil disimpan");                  
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  });
};

function _getData(id){
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"Master_Akun/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        $('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : function(xhr,status,error){
        $(".main-modal-body").html('');        
        toastr.error("Perbaiki kesalahan ini : "+xhr.status+" "+error);
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
          const _tipe = $("<option selected='selected'></option>").val(result.data[0]['idtipe']).text(result.data[0]['tipe']),
                _indukcoa = $("<option selected='selected'></option>").val(result.data[0]['idparent']).text(result.data[0]['parent']),
                _gd = $("<option selected='selected'></option>").val(result.data[0]['gd']).text(result.data[0]['gd']),
                _uang = $("<option selected='selected'></option>").val(result.data[0]['iduang']).text(result.data[0]['uang']),
                _divisi = $("<option selected='selected'></option>").val(result.data[0]['iddivisi']).text(result.data[0]['divisi']),
                _bank = $("<option selected='selected'></option>").val(result.data[0]['idbank']).text(result.data[0]['bank']);

          $('#id').val(result.data[0]['id']);            
          $('#nomor').val(result.data[0]['nomor']);
          $('#tipe').append(_tipe);
          if(result.data[0]['parent']!==null) $('#indukakun').append(_indukcoa);          
          $('#nama').val(result.data[0]['nama']);
          $('#gd').val(result.data[0]['gd']).trigger('change');     
          $('#uang').append(_uang);
          if(result.data[0]['divisi']!==null) $('#divisi').append(_divisi);               
          if(result.data[0]['bank']!==null) $('#bank').append(_bank);                         
          $('#nomorbank').val(result.data[0]['nobank']);          

          if(result.data[0]['sub']=='1'){
            $("#chksub").prop('checked',true);
            $('#indukakun').removeAttr('disabled');
          }

          if(result.data[0]['status']=='1'){
            $("#aktif").prop('checked',true);
          }

          if(result.data[0]['kasmasuk']=='1'){
            $("#kasmasuk").prop('checked',true);
          }

          if(result.data[0]['kaskeluar']=='1'){
            $("#kaskeluar").prop('checked',true);
          }

          esaldoawal();

          var rows = 0;
          $.each(result.data, function() {
            var _kontak = $("<option selected='selected'></option>").val(result.data[rows]['saidkontak']).text(result.data[rows]['sakontak']);                        

            if(result.data[rows]['said'] != null) {
              _addRow();
              _inputFormat();

              $("select[name^='kontak']").eq(rows).append(_kontak).trigger('change');               
              $("input[name^='jumlah']").eq(rows).val(result.data[rows]['sajumlah'].replace(".", ","));            
              $("input[name^='tanggal']").eq(rows).val(result.data[rows]['satanggal']); 
            }
            rows++;
          });

          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}

function esaldoawal(){
  let tipecoa = Number($("#tipe").val());

  if($("#gd").val()=='D' && tipecoa < 11 && tipecoa != 3 && tipecoa != 5 && tipecoa != 6){
    $("#tsaldo tbody").html("");
    $("#baddrow").removeClass("d-none");
  } else {
    $("#tsaldo tbody").html("<tr><td align='center' class='py-2' colspan='4'>Tidak tersedia.</td></tr>");    
    $("#baddrow").addClass("d-none");
  }
}