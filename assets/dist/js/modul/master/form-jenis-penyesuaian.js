
function _clearForm(){
    $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
    $(":checkbox").prop("checked", false);                
}     

setTimeout(function (){
    $('#kode').focus();
}, 500);                

$('#coaselisih').select2({
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

$(this).on('shown.bs.tooltip', function (e) {
    setTimeout(function () {
      $(e.target).tooltip('hide');
    }, 2000);
});

$("#submit").click(function(){
    if (_IsValid()===0) return;
    _saveData();
});

var _IsValid = (function(){
    if ($('#kode').val()==''){
        $('#kode').attr('data-title','Kode jenis harus diisi !');      
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
    if ($('#coaselisih').val()=='' || $('#coaselisih').val()==null){
        $('#coaselisih').attr('data-title','Akun selisih penyesuaian harus diisi !');      
        $('#coaselisih').tooltip('show');
        $('#coaselisih').focus();
        return 0;
    }    
    return 1;
});

var _saveData = (function(){
    const id = $("#id").val(),
          kode = $("#kode").val(),
          nama = $("#nama").val(),
          coa = $("#coaselisih").val();

    var rey = new FormData();  
    rey.set('id',id);
    rey.set('kode',kode);
    rey.set('nama',nama);
    rey.set('coa',coa);  

    $.ajax({ 
        "url"    : base_url+"Master_Jenis_Penyesuaian/savedata", 
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
              toastr.success("Data pajak berhasil disimpan");                  
              return;
            } else {        
              toastr.error(result);                          
              return;
            }
        } 
    })
});

function _getData(id){
    if(id=='' || id==null) return;    

    $.ajax({ 
        "url"    : base_url+"Master_Jenis_Penyesuaian/getdata",       
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
              const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);

              $('#id').val(result.data[0]['id']);            
              $('#kode').val(result.data[0]['kode']);
              $('#nama').val(result.data[0]['nama']);
              if(result.data[0]['coa']!==null) $('#coaselisih').append(_coa).trigger('change');

              $('.loader-wrap').addClass('d-none');                                       
              return;
            }
        } 
    })
}