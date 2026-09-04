var _inputFormatHargaMp = () => {
  $('.numeric').inputmask({
    alias:'numeric',
    digits:'2',
    digitsOptional:false,
    isNumeric: true,
    prefix:'',
    groupSeparator:".",
    placeholder: '0',
    radixPoint:",",
    autoGroup:true,
    autoUnmask:true,
    onBeforeMask: function (value, opts) {
      return value;
    },
    removeMaskOnSubmit:false
  });
};

function _getDataHargaMp(id){
  if(id=='' || id==null) return;

  $.ajax({
    "url"      : base_url+"Master_Item_POS/gethargamp",
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
      $('#hargajual1').val(String(result.data[0]['hargajual1']).replace(".", ","));
      $('#hargamp').val(String(result.data[0]['hargamp']).replace(".", ","));

      $('.loader-wrap').addClass('d-none');
      setTimeout(function(){ $('#hargamp').focus().select(); }, 300);
      return;
    }
  });
}

// refresh datatable di iframe halaman "Update Harga Marketplace"
function _reloadTabelHargaMp(){
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
           && w.document.getElementById('update-harga-mp-table')){
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

  var hargamp = Number($("#hargamp").val().split('.').join('').toString().replace(',','.'));
  if(isNaN(hargamp) || hargamp < 0){
    toastr.error('Harga Jual MP tidak valid');
    $('#hargamp').focus();
    return;
  }

  var rey = new FormData();
  rey.set('id', id);
  rey.set('hargamp', hargamp);

  $.ajax({
    "url"        : base_url+"Master_Item_POS/updhargamp",
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
        toastr.success("Harga Marketplace berhasil disimpan");
        _reloadTabelHargaMp();
        return;
      } else {
        toastr.error(result);
        return;
      }
    }
  });
});

_inputFormatHargaMp();
