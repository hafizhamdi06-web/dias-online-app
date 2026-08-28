$('#statusverif').select2({
    "theme":"bootstrap4",
    "minimumResultsForSearch": -1,
    "dropdownParent": $('#modal')
});

window._getVerifikasiData = function(id){
  if(id=='' || id==null) return;

  $('#idverif').val(id);

  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/getverifikasidata",
    "type"   : "POST",
    "dataType" : "json",
    "data" : "id="+id,
    "cache"  : false,
    "beforeSend" : function(){
      $('.loader-wrap').removeClass('d-none');
    },
    "error"  : function(xhr,status,error){
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
      }

      var row = result.data[0];
      $('#tanggalverif').val(row['tanggal']);
      $('#nomorverif').val(row['nomor']);
      $('#karyawanverif').val(row['karyawan']);
      $('#catatanverif').val(row['catatanverifikasi']);

      if(row['status']==1 || row['status']==2){
        $('#statusverif').val(row['status']).trigger('change');
      }else{
        $('#statusverif').val(2).trigger('change');
      }

      $('.loader-wrap').addClass('d-none');
      return;
    }
  })
}

$("#bokverif").click(function(){
  const id = $('#idverif').val(),
        catatan = $('#catatanverif').val(),
        status = $('#statusverif').val();

  if(status==1 && catatan==''){
    $('#catatanverif').attr('data-title','Catatan Verifikasi harus diisi jika status Pending !');
    $('#catatanverif').tooltip('show');
    $('#catatanverif').focus();
    return;
  }

  $.ajax({
    "url"    : base_url+"PB_Permintaan_Barang/simpanverifikasi",
    "type"   : "POST",
    "data"   : "id="+id+"&status="+status+"&catatan="+encodeURIComponent(catatan),
    "cache"    : false,
    "beforeSend" : function(){
      $(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      $(".loader-wrap").addClass("d-none");
      toastr.error("Err: "+xhr.status+", "+error);
      console.log(xhr.responseText);
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");
      if(result=='sukses'){
        toastr.success("Verifikasi berhasil disimpan");
        $('#modal').modal('hide');
        var trigger = $('#modaltrigger').val();
        if(trigger && document.getElementById(trigger) && document.getElementById(trigger).contentWindow._reloaddatatable){
          document.getElementById(trigger).contentWindow._reloaddatatable();
        }
      } else {
        toastr.error(result);
      }
    }
  })
});

$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});
