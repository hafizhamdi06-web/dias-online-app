$('#tipe').select2({
    "allowClear": true,
    "theme":"bootstrap4",
    "tags": true,
    "dropdownParent": parent.window.$('#modal'),
    "ajax": {
      "url": base_url+"Select_Master/view_jenis_lain",
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

$('#gudang').select2({
    "allowClear": true,
    "theme":"bootstrap4",
    "dropdownParent": parent.window.$('#modal'),
    "ajax": {
      "url": base_url+"Select_Master/view_gudang",
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

function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);
  $('.select2').val('').change();
}
setTimeout(function (){
        $('#kode').focus();
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
    if ($('#tipe').val()=='' || $('#tipe').val()==null){
      $('#tipe').attr('data-title','Tipe harus diisi !');
      $('#tipe').tooltip('show');
      $('#tipe').focus();
      return 0;
    }
    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val(),
        tipe = $("#tipe").val(),
        gudang = $("#gudang").val(),
        keterangan = $("#keterangan").val();

  var rey = new FormData();
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);
  rey.set('tipe',tipe);
  rey.set('gudang',gudang);
  rey.set('keterangan',keterangan);

  $.ajax({
    "url"    : base_url+"Master_Lain/savedata",
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
      "url"    : base_url+"Master_Lain/getdata",
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
          const _tipe = $("<option selected='selected'></option>").val(result.data[0]['tipe']).text(result.data[0]['tipe']),
                _gudang = $("<option selected='selected'></option>").val(result.data[0]['idgudang']).text(result.data[0]['gudang']);

          $('#id').val(result.data[0]['id']);
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#keterangan').val(result.data[0]['keterangan']);
          if(result.data[0]['tipe']!==null) $('#tipe').append(_tipe).trigger("change");
          if(result.data[0]['idgudang']!==null) $('#gudang').append(_gudang).trigger("change");

          $('.loader-wrap').addClass('d-none');
          return;
        }
    }
  })
}
