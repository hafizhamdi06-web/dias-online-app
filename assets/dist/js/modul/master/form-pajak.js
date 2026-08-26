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

$('#tipe').select2({
  "theme":'bootstrap4',
  "dropdownParent": parent.window.$('#modal'),            
  "minimumResultsForSearch": "Infinity"           
}); 

$('#coain,#coaout').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": parent.window.$('#modal'),          
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

function textSelect(par){
  if(!par.id){
    return par.text;
  }
  var $par = $('<span>('+par.kode+') '+par.text+'</span>');
  return $par;
}

function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);                
}     

$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});

setTimeout(function (){
        $('#kode').focus();
}, 500);  

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode pajak harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama pajak harus diisi !');      
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
        nilai = Number($("#nilai").val().split('.').join('').toString().replace(',','.')),
        tipe = $("#tipe").val(),        
        coain = $("#coain").val(),
        coaout = $("#coaout").val();

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);
  rey.set('nilai',nilai);  
  rey.set('tipe',tipe);
  rey.set('coain',coain);        
  rey.set('coaout',coaout);        

  $.ajax({ 
    "url"    : base_url+"Master_Pajak/savedata", 
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
      console.error(xhr.responseText);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        $('#modal').modal('hide');                
        toastr.success("Data pajak berhasil disimpan");                  
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
      "url"    : base_url+"Master_Pajak/getdata",       
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
          let _coain = $("<option selected='selected'></option>").val(result.data[0]['idcoain']).text(result.data[0]['coain']),
              _coaout = $("<option selected='selected'></option>").val(result.data[0]['idcoaout']).text(result.data[0]['coaout']);

          $('#id').val(result.data[0]['id']);            
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#nilai').val(result.data[0]['nilai'].replace(".", ","));                                                    
          if(result.data[0]['nilai']==0) $("#nilai").attr('placeholder','0,00');            
          $('#tipe').val(result.data[0]['tipe']).trigger('change');          
          if(result.data[0]['coain'] != null) $('#coain').append(_coain).trigger('change');
          if(result.data[0]['coaout'] != null) $('#coaout').append(_coaout).trigger('change');          
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}