var _inputFormatPos = () => {
  $('.qty').inputmask({
    alias:'numeric',
    digits:$("#decqty").val(),
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
}

$('#kategori').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "minimumResultsForSearch": "Infinity"
});

$('#status').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "minimumResultsForSearch": "Infinity"
});

$('#satuandasar').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "ajax": {
        "url": base_url+"Select_Master/view_satuan",
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
    }
});

$('#satuandefault').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "ajax": {
        "url": base_url+"Select_Master/view_satuan",
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
    }
});

$("#submit").click(function(){
  if (_IsValidPos()===0) return;
  _saveDataPos();
});

var _IsValidPos = function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode item harus diisi !');
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }

    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama item harus diisi !');
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }

    if ($('#satuandasar').val()=='' || $('#satuandasar').val()==null){
        toastr.error('Pesan : Satuan dasar harus diisi !');
        return 0;
    }

    if ($('#satuandefault').val()=='' || $('#satuandefault').val()==null){
        toastr.error('Pesan : Satuan default harus diisi !');
        return 0;
    }

    return 1;
};

var _saveDataPos = function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val(),
        namaweb = $("#namaweb").val(),
        kategori = $("#kategori").val(),
        status = $("#status").val(),
        serial = $("#serial").prop('checked') ? 1 : 0,
        satuand = $("#satuandasar").val(),
        satuan = $("#satuandefault").val(),
        qtyperbox = Number($("#qtyperbox").val().split('.').join('').toString().replace(',','.')),
        stokmaks = Number($("#stokmaks").val().split('.').join('').toString().replace(',','.')),
        stokmin = Number($("#stokmin").val().split('.').join('').toString().replace(',','.')),
        stokreorder = Number($("#stokreorder").val().split('.').join('').toString().replace(',','.')),
        maxorder = Number($("#maxorder").val().split('.').join('').toString().replace(',','.'));

  var rey = new FormData();
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);
  rey.set('namaweb',namaweb);
  rey.set('kategori',kategori);
  rey.set('status',status);
  rey.set('serial',serial);
  rey.set('satuand',satuand);
  rey.set('satuan',satuan);
  rey.set('qtyperbox',qtyperbox);
  rey.set('stokmaks',stokmaks);
  rey.set('stokmin',stokmin);
  rey.set('stokreorder',stokreorder);
  rey.set('maxorder',maxorder);

  $.ajax({
    "url"    : base_url+"Master_Item_POS/savedata",
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
      console.log(xhr.responseText);
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");

      if(result=='sukses'){
        $('#modal').modal('hide');
        toastr.success("Data item POS berhasil disimpan");
        return;
      } else {
        toastr.error(result);
        return;
      }
    }
  })
};

function _getData(id){
    if(id=='' || id==null) return;

    $.ajax({
      "url"    : base_url+"Master_Item_POS/getdata",
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
          const _satuand = $("<option selected='selected'></option>").val(result.data[0]['idsatuand']).text(result.data[0]['satuand']),
                _satuan = $("<option selected='selected'></option>").val(result.data[0]['idsatuan']).text(result.data[0]['satuan']);

          $('#id').val(result.data[0]['id']);
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#namaweb').val(result.data[0]['namaweb']);
          if(result.data[0]['satuand']!==null) $('#satuandasar').append(_satuand);
          if(result.data[0]['satuan']!==null) $('#satuandefault').append(_satuan);
          $('#kategori').val(result.data[0]['kategori']).trigger('change');
          $('#status').val(result.data[0]['status']).trigger('change');
          $('#serial').prop('checked', result.data[0]['serial']==1);
          $('#qtyperbox').val(String(result.data[0]['qtyperbox']).replace(".", ","));
          $('#stokmaks').val(String(result.data[0]['stokmaks']).replace(".", ","));
          $('#stokmin').val(String(result.data[0]['stokmin']).replace(".", ","));
          $('#stokreorder').val(String(result.data[0]['stokreorder']).replace(".", ","));
          $('#maxorder').val(String(result.data[0]['maxorder']).replace(".", ","));

          $('.loader-wrap').addClass('d-none');
          return;
        }
    }
  })
}

_inputFormatPos();

setTimeout(function (){
        $('#kode').focus();
}, 500);
