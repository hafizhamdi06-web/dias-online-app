window._getPersetujuanData = function(id){
  if(id=='' || id==null) return;

  $('#idpersetujuan').val(id);

  $.ajax({
    "url"    : base_url+"Persetujuan/getverifikasidata",
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
      $('#jenispersetujuan').val(row['jenis']);
      $('#keteranganpersetujuan').val(row['keterangan']);
      $('#pemohonpersetujuan').val(row['pemohon']);
      $('#tanggalpersetujuan').val(row['tanggal']);
      $('#catatanpersetujuan').val('');

      $('.loader-wrap').addClass('d-none');
      return;
    }
  })
}

var _kirimRespon = (url, catatan) => {
  const id = $('#idpersetujuan').val();

  $.ajax({
    "url"    : base_url+url,
    "type"   : "POST",
    "data"   : "id="+id+"&catatan="+encodeURIComponent(catatan),
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
        toastr.success("Respon berhasil disimpan");
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
}

$("#bsetujupersetujuan").click(function(){
  _kirimRespon("Persetujuan/setuju", $('#catatanpersetujuan').val());
});

$("#btolakpersetujuan").click(function(){
  const catatan = $('#catatanpersetujuan').val();

  if(catatan==''){
    $('#catatanpersetujuan').attr('data-title','Catatan harus diisi jika menolak !');
    $('#catatanpersetujuan').tooltip('show');
    $('#catatanpersetujuan').focus();
    return;
  }

  _kirimRespon("Persetujuan/tolak", catatan);
});

$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});
