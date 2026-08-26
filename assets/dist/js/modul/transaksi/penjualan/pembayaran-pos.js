$(document).keydown(function(e){
  if(e.keyCode==123) { 
    e.preventDefault();
    $('#bsimpan').click(); 
  }        
});  

$("#calcash").click(function(){
  $("#cash").val($("#ttrans").val());
  _hitungtotal();  
});

$("#caldebit").click(function(){
  $("#debit").val($("#ttrans").val());
  _hitungtotal();  
});

$("#calcredit").click(function(){
  $("#credit").val($("#ttrans").val());
  _hitungtotal();  
});

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

$('#bankdebit,#bankcredit').select2({
    "allowClear": true,
    "theme":"bootstrap4",
    "dropdownParent": $('#modal'),
    "ajax": {
        "url": base_url+"Select_Master/view_bank",
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

$("#cash,#debit,#credit").on("keyup", function(e){ 
  _hitungtotal();
});    

var _hitungtotal = (function(v){
  try{
    let tcash = Number($('#cash').val().split('.').join('').toString().replace(',','.'));
    let tdebit = Number($('#debit').val().split('.').join('').toString().replace(',','.')); 
    let tcredit = Number($('#credit').val().split('.').join('').toString().replace(',','.'));     
    let ttrans = Number($('#ttrans').val().split('.').join('').toString().replace(',','.'));
    let tbayar = tcash+tdebit+tcredit;
    let tsisa = ttrans-tbayar;

    $("#tbayar").val(tbayar);
    $("#tsisa").val(tsisa);

    if(tbayar == 0) $("#tbayar").attr('placeholder', '0,00');
    if(tsisa == 0) $("#tsisa").attr('placeholder', '0,00');

  } catch(err) {
    toastr.error(err);
  }
});       

var _IsValid = (function(){
  let totalbayar = Number($('#tbayar').val().split('.').join('').toString().replace(',','.'));      
  let sisa = Number($('#tsisa').val().split('.').join('').toString().replace(',','.'));

  if(!totalbayar>0){
    $('#tbayar').attr('data-title','Total bayar masih 0 !');
    $('#tbayar').tooltip('show');
    $('#cash').focus();
    return 0;        
  }
  if(sisa>0){
    $('#tsisa').attr('data-title','Pembayaran masih kurang dari nilai transaksi !');
    $('#tsisa').tooltip('show');
    $('#cash').focus();
    return 0;        
  }

  return 1;
});

$("#bsimpan").click(function(){
  if(_IsValid()==0) return;

  let   trigger = $('#modaltrigger').val(),
        tcash = Number($('#cash').val().split('.').join('').toString().replace(',','.')),
        tdebit = Number($('#debit').val().split('.').join('').toString().replace(',','.')),                               
        tdebitno = $('#debitno').val(),
        tcreditno = $('#creditno').val(),
        tdebitbank = $('#bankdebit').val(),
        tdebitbankn = $('#bankdebit option:selected').text(),
        tcreditbank = $('#bankcredit').val(),                
        tcreditbankn = $('#bankcredit option:selected').text(),                        
        tcredit = Number($('#credit').val().split('.').join('').toString().replace(',','.')),
        tbayar = Number($('#tbayar').val().split('.').join('').toString().replace(',','.')),
        tsisa = Number($('#tsisa').val().split('.').join('').toString().replace(',','.'));         

  $("#"+trigger).contents().find("#bayarcash").val(tcash);  
  $("#"+trigger).contents().find("#bayardebit").val(tdebit);
  $("#"+trigger).contents().find("#bayardebitno").val(tdebitno); 
  $("#"+trigger).contents().find("#bayardebitbank").val(tdebitbank);  
  $("#"+trigger).contents().find("#bayardebitbankn").val(tdebitbankn);                        
  $("#"+trigger).contents().find("#bayarcreditbank").val(tcreditbank);                        
  $("#"+trigger).contents().find("#bayarcreditbankn").val(tcreditbankn);                          
  $("#"+trigger).contents().find("#bayarcredit").val(tcredit);
  $("#"+trigger).contents().find("#bayarcreditno").val(tcreditno);                      
  $("#"+trigger).contents().find("#tbayartrigger").val(tbayar);   
  $('#modal').modal('hide');                      
});

$('#modal').on('hidden.bs.modal', function() {
  $("#iframe-page-pos").focus();     
});  
