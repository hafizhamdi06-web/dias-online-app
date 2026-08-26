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
          
           $('.select2').select2({
            "theme":"bootstrap4"
        })
          
   
          $('.datepicker').datepicker();
        
          $('.datepicker').inputmask({
            alias:'dd/mm/yyyy',
            mask: "1-2-y", 
            placeholder: "_", 
            leapday: "-02-29", 
            separator: "-"
          })
        
          $('#pasien,#teman').select2({
             "allowClear": true,
             "theme":"bootstrap4",
             "dropdownParent": $('#modal'),     
             "ajax": {
                "url": base_url+"Select_Master/view_pasien",
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
        
        $('#item1,#item2,#itemfree').select2({
             "allowClear": true,
             "theme":"bootstrap4",
             "dropdownParent": $('#modal'),     
             "ajax": {
                "url": base_url+"Select_Master/view_item",
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
        
        $('#jenis').select2({
             
             "allowClear": true,
             "theme":"bootstrap4",
             "dropdownParent": $('#modal'),     
             "ajax": {
                "url": base_url+"Select_Master/view_jenisvoucher",
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
        
          
          



} 


_inputFormat();


       
    
        $("#btglpakai").click(function() {
          if($(this).attr('role')) {
            $("#tglpakai").focus();
          }
        });
        
        $("#btglterbit").click(function() {
          if($(this).attr('role')) {
            $("#tglterbit").focus();
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
    if ($('#pasien').val()==''){
      $('#pasien').attr('data-title','Nama pasien harus diisi !');      
      $('#pasien').tooltip('show');
      $('#pasien').focus();
      return 0;
    }
    if ($('#jenis').val()==''){
      $('#jenis').attr('data-title','Jenis harus diisi !');      
      $('#jenis').tooltip('show');
      $('#jenis').focus();
      return 0;
    }
    return 1;
});

var _saveData = (function(){
    
  const id = $("#id").val(),
        nomor = $("#kode").val(),
        tglterbit = $("#tglterbit").val(),
        tglpakai = $("#tglpakai").val(),
        tglexpired = $("#tglexpired").val(),
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
  rey.set('tglexpired',tglexpired); 
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
        
          
        const _jenis = $("<option selected='selected'></option>").val(result.data[0]['idjenis']).text(result.data[0]['namajenis']),
                _pasien = $("<option selected='selected'></option>").val(result.data[0]['idpasien']).text(result.data[0]['namapasien']),
                _item1 = $("<option selected='selected'></option>").val(result.data[0]['iditem']).text(result.data[0]['kodeitem']),
                _item2 = $("<option selected='selected'></option>").val(result.data[0]['iditem2']).text(result.data[0]['kodeitem2']), 
                _itemfree = $("<option selected='selected'></option>").val(result.data[0]['iditemfree']).text(result.data[0]['kodeitemfree']), 
                _teman = $("<option selected='selected'></option>").val(result.data[0]['idteman']).text(result.data[0]['kodeteman']) ;
                
                
        
           $('#id').val(result.data[0]['id']);        
          $('#kode').val(result.data[0]['kode']);
          $('#tglterbit').val(result.data[0]['tglterbit']);
          $('#tglpakai').val(result.data[0]['tglpakai']);
          $('#tglexpired').val(result.data[0]['tglexpired']);
          
          
          $('#diskon1').val(result.data[0]['diskon1'].replace(".", ","));   
          $('#diskon2').val(result.data[0]['diskon2'].replace(".", ","));
          
          if(result.data[0]['diskon1']==0) $("#diskon1").attr('placeholder','0,00');      
          if(result.data[0]['diskon2']==0) $("#diskon2").attr('placeholder','0,00');
          
          $('#masaberlaku').val(result.data[0]['masaberlaku']); 
          $('#penggunaan').val(result.data[0]['penggunaan']).trigger('change');
          
          if(result.data[0]['produksaja']!=='0') $('#chkproduksaja').prop('checked',1);
          if(result.data[0]['rupiah']!=='0') $('#chkrupiah').prop('checked',1);
          if(result.data[0]['pakaibytanggal']!=='0') $('#chkpakaibytanggal').prop('checked',1);
          
          
          if(result.data[0]['namapasien']!==null) $('#pasien').append(_pasien); 
          
          
          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
    
    
    
   
}