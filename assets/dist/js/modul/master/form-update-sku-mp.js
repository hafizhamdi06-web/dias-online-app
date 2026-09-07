function _getDataSkuMp(id){
  if(id=='' || id==null) return;

  $.ajax({
    "url"      : base_url+"Master_Item_POS/getskump",
    "type"     : "POST",
    "dataType" : "json",
    "data"     : "id="+id,
    "cache"    : false,
    "beforeSend": function(){
      $('.loader-wrap').removeClass('d-none');
    },
    "error": function(xhr,status,error){
      $(".main-modal-body").html('');
      toastr.error("Error : "+xhr.status+" "+error);
      console.error(xhr.responseText);
      $('.loader-wrap').addClass('d-none');
      return;
    },
    "success": function(result){
      if (typeof result.pesan !== 'undefined') {
        toastr.error(result.pesan);
        $('.loader-wrap').addClass('d-none');
        return;
      }

      $('#id').val(result.data[0]['id']);
      $('#kode').val(result.data[0]['kode']);
      $('#nama').val(result.data[0]['nama']);
      $('#skushopee').val(result.data[0]['skushopee']);
      $('#skutokopedia').val(result.data[0]['skutokopedia']);

      $('.loader-wrap').addClass('d-none');
      setTimeout(function(){ $('#skushopee').focus().select(); }, 300);
      return;
    }
  });
}

// refresh datatable di iframe halaman "Update SKU Marketplace"
function _reloadTabelSkuMp(){
  try {
    var trigger = $("#modaltrigger").val();
    var el = trigger ? document.getElementById(trigger) : null;
    if(el && el.contentWindow && typeof el.contentWindow._reloaddatatable === 'function'){
      el.contentWindow._reloaddatatable();
      return;
    }
    $('iframe').each(function(){
      try {
        var w = this.contentWindow;
        if(w && typeof w._reloaddatatable === 'function'
           && w.document.getElementById('update-sku-mp-table')){
          w._reloaddatatable();
        }
      } catch(e){}
    });
  } catch(e){}
}

$("#submit").off('click').on('click', function(){
  var id = $("#id").val();
  if(id=='' || id==null){
    toastr.error('Item tidak valid');
    return;
  }

  var rey = new FormData();
  rey.set('id', id);
  rey.set('skushopee', ($("#skushopee").val() || '').trim());
  rey.set('skutokopedia', ($("#skutokopedia").val() || '').trim());

  $.ajax({
    "url"        : base_url+"Master_Item_POS/updskump",
    "type"       : "POST",
    "data"       : rey,
    "processData": false,
    "contentType": false,
    "cache"      : false,
    "beforeSend": function(){
      $(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      $(".loader-wrap").addClass("d-none");
      toastr.error("Error : "+xhr.status+" "+error);
      console.log(xhr.responseText);
      return;
    },
    "success": function(result){
      $(".loader-wrap").addClass("d-none");

      if(result == 'sukses'){
        $('#modal').modal('hide');
        toastr.success("SKU Marketplace berhasil disimpan");
        _reloadTabelSkuMp();
        return;
      } else {
        toastr.error(result);
        return;
      }
    }
  });
});
