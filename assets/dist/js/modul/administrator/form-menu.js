/* Form Init */

$('.numeric-single').inputmask({
    alias:'numeric',
    digits:'0',
    digitsOptional:false,
    isNumeric: true,      
    prefix:'',
    groupSeparator:".",
    placeholder: '0',
    radixPoint:",",
    autoGroup:true,
    autoUnmask:true,
    rightAlign:false,
    onBeforeMask: (value, opts) => {
        return value;
    },
    removeMaskOnSubmit:false
});

$('#tipe').select2({
    "theme":'bootstrap4',
    "minimumResultsForSearch": "Infinity"           
}); 

$('#induk').select2({
    "allowClear": true,
    "theme":"bootstrap4",
    "dropdownParent": parent.window.$('#modal'),          
    "ajax": {
      "url": base_url+"Select_Master/view_parent_menu",
      "type": "post",
      "dataType": "json",                                       
      "delay": 800,
      "data": (params) => {
          return {
            tipe: $("#tipe").val(),
            search: params.term
          }
      },
      "processResults": (data, page) => {
          return {
            results: data
          }
      },
    }
});

$('#laporan').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": parent.window.$('#modal'),          
     "ajax": {
        "url": base_url+"Select_Master/view_daftar_laporan",
        "type": "post",
        "dataType": "json",                                       
        "delay": 800,
        "data": (params) => {
            return {
              search: params.term
            }
        },
        "processResults": (data, page) => {
            return {
              results: data
            }
        },
     }
});

$("#tipe").on("select2:select", () => {
    _fieldActive();
    $('#induk').val('').trigger('change');
});

$("#induk").on("select2:select select2:clear", () => {
    if($('#id').val()=='') _setNextUrutan();
});

var _setNextUrutan = () => {
    $.ajax({
      "url"    : base_url+"Admin_Menu/getnexturutan",
      "type"   : "POST",
      "dataType" : "json",
      "data" : "induk="+$('#induk').val(),
      "cache"  : false,
      "success" : (result) => {
          $('#urutan').val(result.data[0]['urutan']);
      }
    })
};

$("#submit").click( () => {
    if (_IsValid()===0) return;
    _saveData();
});

var _fieldActive = () => {
    if($("#tipe").val()==1){
        $("#laporanL").removeClass("d-none");  
        $("#keteranganL").removeClass("d-none");      
        $("#iconL").addClass("d-none");
        $("#linkL").addClass("d-none");              
    }else{
        $("#keteranganL").addClass("d-none");  
        $("#iconL").removeClass("d-none");      
        $("#linkL").removeClass("d-none");                  
    }
}

var _IsValid = () => {
    if ($('#tipe').val()=='' || $('#tipe').val()==null){
        $('#tipe').attr('data-title','Tipe harus diisi !');      
        $('#tipe').tooltip('show');
        $('#tipe').focus();
        return 0;
    }
    if ($('#nama').val()==''){
        $('#nama').attr('data-title','Nama menu harus diisi !');      
        $('#nama').tooltip('show');
        $('#nama').focus();
        return 0;
    }
    if ($('#caption1').val()==''){
        $('#caption1').attr('data-title','Caption menu harus diisi !');      
        $('#caption1').tooltip('show');
        $('#caption1').focus();
        return 0;
    }    
    return 1;
};

var _saveData = () => {

    var aktif = 0;

    if($('#aktif').prop('checked')) aktif = 1;

    const rey = new FormData();  
    rey.set('id',$("#id").val());
    rey.set('nama',$("#nama").val());
    rey.set('tipe',$("#tipe").val());
    rey.set('induk',$("#induk").val());
    rey.set('keterangan',$("#keterangan").val());  
    rey.set('link',$("#link").val());    
    rey.set('icon',$("#icon").val());
    rey.set('caption1',$("#caption1").val()); 
    rey.set('aktif',aktif);              
    rey.set('urutan',Number($("#urutan").val().split('.').join('').toString().replace(',','.')));
    rey.set('report',$("#laporan").val()); 
    rey.set('catatan',$("#catatan").val()); 

    $.ajax({ 
      "url"    : base_url+"Admin_Menu/savedata", 
      "type"   : "POST", 
      "data"   : rey,
      "processData": false,
      "contentType": false,
      "cache"    : false,
      "beforeSend" : () => {
          $(".loader-wrap").removeClass("d-none");
      },
      "error": (xhr, status, error) => {
          $(".loader-wrap").addClass("d-none");
          toastr.error("Error : "+xhr.status+" "+error);      
          console.log(xhr.responseText);      
          return;
      },
      "success": async (result) => {
          await sidebarmenu_content();        
          $(".loader-wrap").addClass("d-none");        

          if(result=='sukses'){
              $('#modal').modal('hide');                
              toastr.success("Data menu berhasil disimpan");                  
              return;
          } else {        
              toastr.error(result);                          
              return;
          }
      } 
    })
};

var sidebarmenu_content = () => {
  $.ajax({ 
    "url"    : base_url+"Dasbor/refreshsidebarmenu", 
    "type"   : "POST", 
    "dataType" : "html",
    "cache"    : false,
    "error" : (xhr) => {
      console.error(xhr.responseText);
      return;
    },
    "success": (result) => {
      $('aside').fadeOut(250, function() {
        $(this).append(result).fadeIn(250);
      });
      return;                   
    } 
  })        
};

var _getData = (id) => {

    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"Admin_Menu/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : () => {
          $('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : (xhr,status,error) => {
          $(".main-modal-body").html('');        
          toastr.error("Perbaiki kesalahan ini : "+xhr.status+" "+error);
          console.error(xhr.responseText);
          $('.loader-wrap').addClass('d-none');                  
          return;
      },
      "success" : (result) => {
          if (typeof result.pesan !== 'undefined') {
              toastr.error(result.pesan);
              $('.loader-wrap').addClass('d-none'); 
              return;
          } else {
              const _parent = $("<option selected='selected'></option>").val(result.data[0]['MPARENT']).text(result.data[0]['parent']),
                    _report = $("<option selected='selected'></option>").val(result.data[0]['MREPORT']).text(result.data[0]['report']);

              $('#id').val(result.data[0]['MID']);            
              $('#nama').val(result.data[0]['MNAMA']);
              $('#keterangan').val(result.data[0]['MDESCRIPTION']);          
              if(result.data[0]['parent']!==null) $('#induk').append(_parent).trigger("change");                      
              if(result.data[0]['report']!==null) $('#laporan').append(_report).trigger("change");                                
              $('#tipe').val(result.data[0]['MTYPE']).trigger('change');
              $('#urutan').val(result.data[0]['MURUTAN'].replace(".", ","));
              $('#link').val(result.data[0]['MLINK']);
              $('#caption1').val(result.data[0]['MCAPTION1']);
              $('#icon').val(result.data[0]['MICON']);
              $('#catatan').val(result.data[0]['MCATATAN']);                        

              if(result.data[0]['MURUTAN']==0) $("#urutan").attr('placeholder','0');            

              if(result.data[0]['MACTIVE']=='1'){
                $("#aktif").prop('checked',true);
              }

              _fieldActive();
              $('.loader-wrap').addClass('d-none');                                       
              return;
          }
      } 
  })
};

_fieldActive();