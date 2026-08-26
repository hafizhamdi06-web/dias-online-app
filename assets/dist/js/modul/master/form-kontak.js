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

  $('.terminsa').select2({
       "allowClear": true,
       "theme":"bootstrap4",
       "dropdownParent": $('#tabKontak'),          
       "ajax": {
          "url": base_url+"Select_Master/view_termin",
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
}    

var _addRow = () => {
  let newrow = " <tr>";
      newrow += "<td><input type=\"text\" name=\"nomorsa[]\" class=\"nomorsa form-control form-control-sm\" placeholder=\"[Auto]\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\"></td>";  
      newrow += "<td><input type=\"text\" name=\"tanggalsa[]\" class=\"tanggalsa form-control form-control-sm datepicker\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\"></td>";      
      newrow += "<td><select name=\"terminsa[]\" class=\"terminsa form-control select2 form-control-sm\" style=\"width:100%;\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";  
      newrow += "<td><input type=\"tel\" name=\"jumlahsa[]\" class=\"jumlahsa form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
      newrow += "<td><input name=\"catatansa[]\" class=\"catatansa form-control form-control-sm\" autocomplete=\"off\" data-trigger=\"manual\" data-placement=\"auto\" value=\"SALDO AWAL\"></select></td>"; 
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

$('.select2').select2({
    "theme":"bootstrap4"
})

$("#bTglLahir").click(function() {
  if($(this).attr('role')) {
    $("#tgllahir").focus();
  }
});

$('#kelamin,#lvlharga').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabKontak'),
     "minimumResultsForSearch": "Infinity"     
});

$('#kategori').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#modal'),     
     "ajax": {
        "url": base_url+"Select_Master/view_kategori_kontak",
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

$('#matauang').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabKontak'),  
     "minimumResultsForSearch": "Infinity",             
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

$('#bank').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabKontak'),     
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

$('#terminbeli,#terminjual').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabKontak'),  
     "minimumResultsForSearch": "Infinity",                     
     "ajax": {
        "url": base_url+"Select_Master/view_termin",
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

$('#bagbeli,#bagjual').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabKontak'),  
     "ajax": {
        "url": base_url+"Select_Master/view_karyawan",
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

function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);                
}         

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

$("#baddrow").click(function() {
  _addRow();
  _inputFormat();
  $("input[name^='nomor']").last().focus();            
});

var _IsValid = (function(){
    //Cek Header Input
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode kontak harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }

    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama kontak harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }

    if ($('#kategori').val()=='' || $('#kategori').val()==null){
      $('#kategori').attr('data-title','Kategori kontak harus diisi !');      
      $('#kategori').tooltip('show');
      $('#kategori').focus();
      return 0;
    }     

    return 1;
});

var _saveData = function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val(),
        tipe = $("#kategori").val(),
        alamat1 = $("#a1alamat").val(),
        kodepos1 = $("#a1kodepos").val(),
        telp1 = $("#a1telp").val(),
        faks1 = $("#a1faks").val(),
        email1 = $("#a1email").val(), 
        kontak1 = $("#a1kontak").val(),                       
        terminbeli = $("#terminbeli").val(),                               
        terminjual = $("#terminjual").val(),
        bagbeli = $("#bagbeli").val(),
        bagjual = $("#bagjual").val(),
        bagtagih = $("#bagtagih").val(),      
        lvlharga = $("#lvlharga").val(),
        npwp = $("#npwp").val(),
        kelamin = $("#kelamin").val(),
        matauang = $("#matauang").val(), 
        bank = $("#bank").val(),
        norekbank = $("#noakunbank").val(),    
        namarek = $("#namarek").val(),                                                                                                                             
        batashutang = Number($("#batashutang").val().split('.').join('').toString().replace(',','.')), 
        bataspiutang = Number($("#bataspiutang").val().split('.').join('').toString().replace(',','.')),
        diskon = Number($("#diskonjual").val().split('.').join('').toString().replace(',','.'));

  var pkp = 0;
  if ($('#chkpkp').is(":checked"))  pkp = 1;

  var saldoawal = [],
      isValid = null,
      _elFocus = null;

  $("input[name^='nomorsa']").each(function(index,element){
    let _tgl = $("input[name^='tanggalsa']").eq(index).val();
    let _termin = $("select[name^='terminsa']").eq(index).val();
    let _jumlah = Number($("input[name^='jumlahsa']").eq(index).val().split('.').join('').toString().replace(',','.'));
    let _catatan = $("input[name^='catatansa']").eq(index).val();

    if (_tgl == '')  {
      isValid = "Tanggal pada baris ke-" + Number(index+1) + " harus diisi"; 
      _elFocus = $("input[name^='tanggalsa']").eq(index);
      return;
    } else if (_termin == null) {
      isValid = "Termin pada baris ke-" + Number(index+1) + " harus diisi"; 
      _elFocus = $("select[name^='terminsa']").eq(index);        
      return;
    } else if (_jumlah == 0) {
      isValid = "Jumlah pada baris ke-" + Number(index+1) + " masih 0";
      _elFocus = $("input[name^='jumlahsa']").eq(index);
      return;                              
    }        

    saldoawal.push({
             nomor:this.value,
             tanggal:_tgl,
             termin:_termin,        
             jumlah:_jumlah,
             catatan:_catatan               
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
  rey.set('nama',nama);
  rey.set('tipe',tipe);  
  rey.set('alamat1',alamat1); 
  rey.set('kodepos1',kodepos1);
  rey.set('telp1',telp1);
  rey.set('faks1',faks1);
  rey.set('email1',email1);
  rey.set('kontak1',kontak1);
  rey.set('terminbeli',terminbeli);
  rey.set('terminjual',terminjual);
  rey.set('bagbeli',bagbeli);
  rey.set('bagjual',bagjual);
  rey.set('bagtagih',bagtagih);
  rey.set('lvlharga',lvlharga);
  rey.set('npwp',npwp);  
  rey.set('kelamin',kelamin);  
  rey.set('matauang',matauang); 
  rey.set('bank',bank); 
  rey.set('norekbank',norekbank); 
  rey.set('namarek',namarek);            
  rey.set('batashutang',batashutang);
  rey.set('bataspiutang',bataspiutang);
  rey.set('diskon',diskon);   
  rey.set('pkp',pkp);  
  rey.set('saldoawal',saldoawal);                                              

  $.ajax({ 
    "url"    : base_url+"Master_Kontak/savedata", 
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
        toastr.success("Data kontak berhasil disimpan");                  
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
      "url"    : base_url+"Master_Kontak/getdata",       
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
                _terminbeli = $("<option selected='selected'></option>").val(result.data[0]['idterminbeli']).text(result.data[0]['terminbeli']),
                _terminjual = $("<option selected='selected'></option>").val(result.data[0]['idterminjual']).text(result.data[0]['terminjual']),
                _bagbeli = $("<option selected='selected'></option>").val(result.data[0]['idbagbeli']).text(result.data[0]['bagbeli']), 
                _bagjual = $("<option selected='selected'></option>").val(result.data[0]['idbagjual']).text(result.data[0]['bagjual']), 
                _bagtagih = $("<option selected='selected'></option>").val(result.data[0]['idbagtagih']).text(result.data[0]['bagtagih']),
                _uang = $("<option selected='selected'></option>").val(result.data[0]['iduang']).text(result.data[0]['uang']),
                _bank = $("<option selected='selected'></option>").val(result.data[0]['idbank']).text(result.data[0]['bank']);

          $('#id').val(result.data[0]['id']);            
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);          
          if(result.data[0]['tipe']!==null) $('#kategori').append(_tipe);          
          $('#a1alamat').val(result.data[0]['alamat1']);          
          $('#a1telp').val(result.data[0]['telp1']); 
          $('#a1faks').val(result.data[0]['fax1']);
          $('#a1email').val(result.data[0]['email1']);   
          $('#a1kontak').val(result.data[0]['kontak1']);
          $('#a1kodepos').val(result.data[0]['kodepos1']);          
          $('#noakunbank').val(result.data[0]['norekbank']);
          $('#namarek').val(result.data[0]['namarek']);                    

          if(result.data[0]['terminbeli']!==null) $('#terminbeli').append(_terminbeli);          
          if(result.data[0]['terminjual']!==null) $('#terminjual').append(_terminjual);
          if(result.data[0]['bagbeli']!==null) $('#bagbeli').append(_bagbeli);                    
          if(result.data[0]['bagjual']!==null) $('#bagjual').append(_bagjual);                    
          if(result.data[0]['bagtagih']!==null) $('#bagtagih').append(_bagtagih);
          if(result.data[0]['uang']!==null) $('#matauang').append(_uang);
          if(result.data[0]['bank']!==null) $('#bank').append(_bank);                                                            

          $('#batashutang').val(result.data[0]['batashutang'].replace(".", ","));                                                     
          if(result.data[0]['batashutang']==0) $("#batashutang").attr('placeholder','0,00');            
          $('#bataspiutang').val(result.data[0]['bataspiutang'].replace(".", ","));                                                     
          if(result.data[0]['bataspiutang']==0) $("#bataspiutang").attr('placeholder','0,00');            
          $('#diskonjual').val(result.data[0]['diskon'].replace(".", ","));                                                     
          if(result.data[0]['diskon']==0) $("#diskonjual").attr('placeholder','0,00');            

          if(result.data[0]['npwp']!=='0') $('#npwp').val(result.data[0]['npwp']);
          if(result.data[0]['pkp']!=='0') $('#chkpkp').prop('checked',1);

          $('#lvlharga').val(result.data[0]['levelhargajual']).trigger('change');
          $('#kelamin').val(result.data[0]['kelamin']).trigger('change');

          var rows = 0;
          $.each(result.data, function() {
            var _terminsa = $("<option selected='selected'></option>").val(result.data[rows]['idterminsa']).text(result.data[rows]['terminsa']);

            if(result.data[rows]['nomorsa'] != null) {
              _addRow();
              _inputFormat();

              $("input[name^='nomorsa']").eq(rows).val(result.data[rows]['nomorsa']);
              $("select[name^='terminsa']").eq(rows).append(_terminsa).trigger('change');
              $("input[name^='jumlahsa']").eq(rows).val(result.data[rows]['jumlahsa'].replace(".", ","));                             
              $("input[name^='catatansa']").eq(rows).val(result.data[rows]['catatansa'].replace(".", ","));            
              $("input[name^='tanggalsa']").eq(rows).val(result.data[rows]['tanggalsa']); 
            }
            rows++;
          });                    

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}

setTimeout(function (){
        $('#kode').focus();
}, 500);                   
