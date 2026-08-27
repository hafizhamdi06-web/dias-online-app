function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
}
setTimeout(function (){
        $('#idmenu').focus();
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
    if ($('#idmenu').val()==''){
      $('#idmenu').attr('data-title','ID Menu harus diisi !');
      $('#idmenu').tooltip('show');
      $('#idmenu').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama Role harus diisi !');
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }
    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        idmenu = $("#idmenu").val(),
        nama = $("#nama").val();

  var rey = new FormData();
  rey.set('id',id);
  rey.set('idmenu',idmenu);
  rey.set('nama',nama);

  $.ajax({
    "url"    : base_url+"Master_Role/savedata",
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
        toastr.success("Data berhasil disimpan");
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
      "url"    : base_url+"Master_Role/getdata",
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
          $('#id').val(result.data[0]['id']);
          $('#idmenu').val(result.data[0]['idmenu']);
          $('#nama').val(result.data[0]['nama']);

          $('.loader-wrap').addClass('d-none');
          return;
        }
    }
  })
}
