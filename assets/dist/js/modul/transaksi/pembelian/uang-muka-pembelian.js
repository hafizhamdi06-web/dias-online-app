$('#nofakturpjk').inputmask({
  mask: "999.999-99.99999999"
});

$("#dTgl").click(function() {
    $("#tgl").focus();
});    

$("#dTglPajak").click(function() {
    $("#tglpajak").focus();
});    

$(document).on('select2:open', () => {
  document.querySelector('.select2-search__field').focus();
});   

$("#jumlah").on("keyup", function(e){
  const pjk = $("#pajak").val();
  _hitungtotal(pjk);
});    

$('#pajak').on('change',function(e){
  const pjk = this.value;
  if(pjk==0){
    if(!$("#colpjk1").hasClass("d-none")) $("#colpjk1").addClass("d-none");
    if(!$("#colpjk2").hasClass("d-none")) $("#colpjk2").addClass("d-none");
    if(!$("#colpjk3").hasClass("d-none")) $("#colpjk3").addClass("d-none");
    if(!$("#colpjk4").hasClass("d-none")) $("#colpjk4").addClass("d-none"); 
    //$('#nofakturpjk').val('');           
  }else{
    $("#colpjk1").removeClass("d-none");
    $("#colpjk2").removeClass("d-none");      
    $("#colpjk3").removeClass("d-none");      
    $("#colpjk4").removeClass("d-none");                  
  }
  _hitungtotal(pjk);
});

var _hitungtotal = (function(v){
  try{
    const nilaiP = Number(10/100);      
    const tjumlah = Number($('#jumlah').val().split('.').join('').toString().replace(',','.'));    
    let tpjk = 0, tdp = 0;

    if(v==2){
      tpjk = (100/110)*tjumlah;
      tpjk = tpjk*nilaiP;
      tdp = tjumlah;
    }else if(v==1){
      tpjk = tjumlah*nilaiP;
      tdp = tjumlah+tpjk;
    }else{
      tpjk = 0;
      tdp = tjumlah;
    }

    tpjk = tpjk.toString().replace('.',',');
    tdp =  tdp.toString().replace('.',',');

    if(tpjk==0) tpjk='0,00';
    if(tdp==0) tdp='0,00';

    $('#jumlahpjk').val(tpjk).attr('placeholder',tpjk);
    $('#total').val(tdp).attr('placeholder',tdp);                  
  } catch(err) {
    console.error(err);
    alert("Pesan : Mohon maaf ada kesalahan pada sistem..");
  }
});       

var _IsValid = (function(){
  const total = Number($('#total').val().split('.').join('').toString().replace(',','.'));      

  if($("#coadp").val()=="" || $("#coadp").val()==null){
    $('#coadp').attr('data-title','COA uang muka harus dipilih !');
    $('#coadp').tooltip('show');
    $('#coadp').focus();
    return 0;        
  }
  if(!total>0){
    $('#jumlah').attr('data-title','Uang muka harus diisi !');
    $('#jumlah').tooltip('show');
    $('#jumlah').focus();
    return 0;        
  }
  return 1;
});

$("#bsimpan").click(function(){

  if(_IsValid()==0) return;

  const trigger = $('#modaltrigger').val(),
        iddp = $('#id').val(),
        tgldp = $('#tgl').val(),
        nomor = $('#nomor').val(),  
        uraian = $('#uraian').val(), 
        coadp = $('#coadp').val(),             
        coadpname = $('#coadp').select2('data')[0]['text'],  
        pajak = $('#pajak').val(), 
        nopajak = $('#nofakturpjk').val(), 
        tglpajak = $('#tglpajak').val(),                                        
        jumlah = Number($('#jumlah').val().split('.').join('').toString().replace(',','.')),
        jumlahpjk = Number($('#jumlahpjk').val().split('.').join('').toString().replace(',','.')),                               
        total = Number($('#total').val().split('.').join('').toString().replace(',','.'));

  $("#"+trigger).contents().find("#iddp").val(iddp);  
  $("#"+trigger).contents().find("#tgldp").val(tgldp);                  
  $("#"+trigger).contents().find("#nomordp").val(nomor); 
  $("#"+trigger).contents().find("#coadp").val(coadp);
  $("#"+trigger).contents().find("#coadpname").val(coadpname);             
  $("#"+trigger).contents().find("#uraiandp").val(uraian);                        
  $("#"+trigger).contents().find("#pajakdp").val(pajak);                        
  $("#"+trigger).contents().find("#tglpajakdp").val(tglpajak);                        
  $("#"+trigger).contents().find("#fakturpajakdp").val(nopajak);                                          
  $("#"+trigger).contents().find("#tdp").val(total);  
  $("#"+trigger).contents().find("#tdpjumlah").val(jumlah);
  $("#"+trigger).contents().find("#tdppajak").val(jumlahpjk);            
  $("#"+trigger).contents().find("#triggertdp").val(total);        
  $('#modal').modal('hide');                      

});
