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

$('#pajak').select2({
    "allowClear": true,
    "theme":"bootstrap4",
    "dropdownParent": $('#modal'),
    "ajax": {
        "url": base_url+"Select_Master/view_pajak_pph",
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

$('#pajak').on("select2:select", function(){
    $.ajax({ 
      "url"    : base_url+"Master_Pajak/getdata", 
      "type"   : "POST", 
      "data"   : "id="+$("#pajak").val(),
      "dataType" : "json", 
      "beforeSend": function(){
        $("#spinner").removeClass("d-none");
      },        
      "error": function(){
        $("#spinner").addClass("d-none");
        toastr.error("Gagal mengambil rate pajak !");
        return;
      },
      "success": function(result) {
        let _rate = Number(result.data[0]['nilai']/100);
        let _jmlbayar = Number($("#jmlbayar").val());
        let _jmlpph = _rate * _jmlbayar;
        _jmlpph = Math.floor((_jmlpph*100)/100);        

        if(_jmlpph == 0){
          $('#jmlpph').val(0).attr('placeholder','0,00');
        }else{
          $("#jmlpph").val(_jmlpph.toString().replace('.',','));
        }
        $("#spinner").addClass("d-none");        
        return;
      } 
    });  
})

var _IsValid = (function(){
  let jumlah = Number($('#jmlpph').val().split('.').join('').toString().replace(',','.'));      

  if($("#pajak").val() == '' || $("#pajak").val() == null){
    $('#pajak').attr('data-title','Pajak harus dipilih !');
    $('#pajak').tooltip('show');
    $('#pajak').focus();
    return 0;        
  }
  if(jumlah == 0){
    $('#jmlpph').attr('data-title','Jumlah pajak harus diisi !');
    $('#jmlpph').tooltip('show');
    $('#jmlpph').focus();
    return 0;        
  }

  return 1;
});

$("#bsimpan").click(function(){
  if(_IsValid()==0) return;

  let   trigger = $('#modaltrigger').val(),
        jmlpph = Number($('#jmlpph').val().split('.').join('').toString().replace(',','.')),
        nobukti = $('#nomorbupot').val(),
        idpajak = $('#pajak').val(),
        pajak = $('#pajak option:selected').text(),
        stssetor = 0;

  if($("#chksetorsendiri").prop('checked') == true) stssetor = 1;

  $("#"+trigger).contents().find("#totalpajak").val(jmlpph.toString().replace('.',','));   
  $("#"+trigger).contents().find("#namapph").val(pajak);         
  $("#"+trigger).contents().find("#nobupot").val(nobukti);     
  $("#"+trigger).contents().find("#idpph").val(idpajak);
  $("#"+trigger).contents().find("#statussetor").val(stssetor).trigger('change');        
  $("#"+trigger).contents().find("#triggerpph").val(jmlpph).trigger('change');   
  $('#modal').modal('hide');                      
});

$("#bhapus").click(function(){
  let   trigger = $('#modaltrigger').val();

  $("#"+trigger).contents().find("#totalpajak").val(0).attr('placeholder','0,00');   
  $("#"+trigger).contents().find("#namapph").val("");         
  $("#"+trigger).contents().find("#nobupot").val("");     
  $("#"+trigger).contents().find("#idpph").val("");      
  $("#"+trigger).contents().find("#statussetor").val(0);          
  $("#"+trigger).contents().find("#triggerpph").val(0).trigger('change');   
  $('#modal').modal('hide');                      
});

$('#modal').on('hidden.bs.modal', function() {
  $("#iframe-page-phb").focus();     
});  
