var _inputFormat = () => {
    
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
          
       



} 


_inputFormat();


        $('.select2').select2({
            "theme":"bootstrap4"
        })
    
        $("#dTglSaldo").click(function() {
          if($(this).attr('role')) {
            $("#tglsa").focus();
          }
        });
        
        $("#bTglLahir").click(function() {
          if($(this).attr('role')) {
            $("#tgllahir").focus();
          }
        });
        

 
  
function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false); 
  
}     
setTimeout(function (){
        $('#kode').focus();
    }, 500);                
$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});
/* End Form Init */

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode voucher harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama voucher harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }
    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        nomor = $("#kode").val(),
        tglterbit = $("#tglterbit").val(),
        tglpakai = $("#tglpakai").val(),
        pasien = $("#pasien").val(),
        diskon1 = $("#diskon1").val(),
        diskon2 = $("#diskon2").val(),
        jenis = $("#jenis").val(),
        item1 = $("#item1").val(),
        item2 = $("#item2").val(),
        masaberlaku = $("#masaberlaku").val(),  
        penggunaan = $("#penggunaan").val(),
        itemfree = $("#itemfree").val(),
        teman = $("#teman").val();
        
         var produksaja = 0, rupiah = 0, pakaibytanggal = 0 ;
  if ($('#chkproduksaja').is(":checked"))  produksaja = 1;
  if ($('#chkrupiah').is(":checked"))  rupiah = 1;
  if ($('#chkpakaibytanggal').is(":checked"))  pakaibytanggal = 1;

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('nomor',nomor); 
  rey.set('tglterbit',tglterbit); 
  rey.set('tglpakai',tglpakai); 
  rey.set('pasien',pasien); 
  rey.set('diskon1',diskon1); 
  rey.set('diskon2',diskon2); 
  rey.set('jenis',jenis); 
  rey.set('item1',item1); 
  rey.set('item2',item2); 
  rey.set('masaberlaku',masaberlaku);  
  rey.set('produksaja',produksaja);  
  rey.set('rupiah',rupiah);  
  rey.set('pakaibytanggal',pakaibytanggal);  
  rey.set('penggunaan',penggunaan);  
  rey.set('itemfree',itemfree);  
  rey.set('teman',teman);   

  $.ajax({ 
    "url"    : base_url+"Master_Voucher/savedata", 
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
        toastr.success("Data voucher berhasil disimpan");                  
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
    
    _inputFormat();

    $.ajax({ 
      "url"    : base_url+"Master_Voucher/getdata",       
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
        if (typeof result.pesan !== 'undefined') { // Jika ada pesan maka tampilkan pesan
          toastr.error(result.pesan);
          $('.loader-wrap').addClass('d-none'); 
          return;
        } else { // Jika tidak ada pesan tampilkan json ke form
        
           $('#id').val(result.data[0]['id']);        
          $('#kode').val(result.data[0]['kode']);
          $('#tglterbit').val(result.data[0]['tglterbit']);
          $('#tglpakai').val(result.data[0]['tglpakai']);
          
          $('#diskon1').val(result.data[0]['diskon1'].replace(".", ","));   
          $('#diskon2').val(result.data[0]['diskon2'].replace(".", ","));
          
          if(result.data[0]['diskon1']==0) $("#diskon1").attr('placeholder','0,00');      
          if(result.data[0]['diskon2']==0) $("#diskon2").attr('placeholder','0,00');
           $('#masaberlaku').val(result.data[0]['masaberlaku'])); 
          $('#penggunaan').val(result.data[0]['penggunaan']).trigger('change');
          
          if(result.data[0]['produksaja']!=='0') $('#chkproduksaja').prop('checked',1);
          if(result.data[0]['rupiah']!=='0') $('#chkrupiah').prop('checked',1);
          if(result.data[0]['pakaibytanggal']!=='0') $('#chkpakaibytanggal').prop('checked',1);
      
          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}