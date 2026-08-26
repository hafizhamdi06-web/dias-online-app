$('.numeric').inputmask({
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
  onBeforeMask: function (value, opts) {
    //console.dir(opts);
    return value;
  },
  removeMaskOnSubmit:false
});

function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);                
}

function textSelect(par){
  if(!par.id){
    return par.text;
  }
  var $par = $('<span>('+par.kode+') '+par.text+'</span>');
  return $par;
}

$('#coaaktiva,#coapenyusutan,#coabypenyusutan,#coawo').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#modal'),          
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

setTimeout(function (){
        $('#kode').focus();
    }, 500);                

$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});
/* End Form Init */

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }

    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val(),
        umur = $("#umur").val(),
        coaaktiva = $("#coaaktiva").val(),
        coadepresiasi = $("#coabypenyusutan").val(),
        coadepresiasiakum = $("#coapenyusutan").val(),
        coawriteoff = $("#coawo").val();

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);
  rey.set('umur',umur);  
  rey.set('coaaktiva',coaaktiva);  
  rey.set('coadepresiasi',coadepresiasi);  
  rey.set('coadepresiasiakum',coadepresiasiakum);  
  rey.set('coawriteoff',coawriteoff);          

  $.ajax({ 
    "url"    : base_url+"Master_Kelompok_Aktiva/savedata", 
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
      toastr.error("Perbaiki masalah ini : "+xhr.status+" "+error);      
      console.error(xhr.responseText);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        $('#modal').modal('hide');                
        toastr.success("Data kelompok aktiva berhasil disimpan");                  
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  });
});

function _getData(id){
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"Master_Kelompok_Aktiva/getdata",       
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
        if (typeof result.pesan !== 'undefined') { // Jika ada pesan maka tampilkan pesan
          toastr.error(result.pesan);
          $('.loader-wrap').addClass('d-none'); 
          return;
        } else { // Jika tidak ada pesan tampilkan json ke form
          const _coaaktiva = $("<option selected='selected'></option>").val(result.data[0]['idcoaaktiva']).text(result.data[0]['coaaktiva']),
                _coadepresiasi = $("<option selected='selected'></option>").val(result.data[0]['idcoadepresiasi']).text(result.data[0]['coadepresiasi']),
                _coadepresiasiakum = $("<option selected='selected'></option>").val(result.data[0]['idcoadepresiasiakum']).text(result.data[0]['coadepresiasiakum']),
                _coawriteoff = $("<option selected='selected'></option>").val(result.data[0]['idcoawriteoff']).text(result.data[0]['coawriteoff']);          

          $('#id').val(result.data[0]['id']);            
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#umur').val(result.data[0]['umur'].replace(".", ","));                                                     
          if(result.data[0]['umur']==0) $("#umur").attr('placeholder','0,00');            

          if(result.data[0]['coaaktiva']!==null) $('#coaaktiva').append(_coaaktiva);          
          if(result.data[0]['coadepresiasi']!==null) $('#coabypenyusutan').append(_coadepresiasi);
          if(result.data[0]['coadepresiasiakum']!==null) $('#coapenyusutan').append(_coadepresiasiakum);                              
          if(result.data[0]['coawriteoff']!==null) $('#coawo').append(_coawriteoff);
          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}