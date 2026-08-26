function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);                
}  
$('.numeric').inputmask({
  alias:'numeric',
  digits:'0',
  digitsOptional:false,
  isNumeric: true,      
  prefix:'',
  rightAlign: !1,
  groupSeparator:"",
  placeholder: '',
  radixPoint:"",
  autoGroup:true,
  autoUnmask:true,
  onBeforeMask: function (value, opts) {
    return value;
  },
  removeMaskOnSubmit:false
});   
setTimeout(function (){
        $('#tahun').focus();
    }, 500);                
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
    if ($('#tahun').val()==''){
      $('#tahun').attr('data-title','tahun periode harus diisi !');      
      $('#tahun').tooltip('show');
      $('#tahun').focus();
      return 0;
    }
    return 1;
});

var _saveData = (function(){

  const rey = new FormData();  
  rey.set('tahun', $("#tahun").val());

  $.ajax({ 
    "url"    : base_url+"Settings_Info/saveperiode", 
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
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        $('#modal').modal('hide');                
        toastr.success("Tahun periode berhasil disimpan");                  
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  });
});