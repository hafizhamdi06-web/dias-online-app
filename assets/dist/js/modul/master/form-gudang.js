$('#divisi').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "minimumResultsForSearch": "Infinity",          
     "dropdownParent": $('#modal'),          
     "ajax": {
        "url": base_url+"Select_Master/view_divisi",
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
    },
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

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode gudang harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama gudang harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }

    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val(),
        divisi = $("#divisi").val(),
        kontak = $("#kontak").val(),
        alamat = $("#alamat").val(),
        kota = $("#kota").val(),
        provinsi = $("#provinsi").val(),
        negara = $("#negara").val(),        
        telp = $("#telp").val(),
        faks = $("#faks").val();

  var def = 0;
  if ($('#chkdefault').is(":checked")) def = 1;

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);
  rey.set('divisi',divisi);  
  rey.set('kontak',kontak);
  rey.set('alamat',alamat);    
  rey.set('kota',kota);
  rey.set('provinsi',provinsi);
  rey.set('negara',negara);
  rey.set('telp',telp);
  rey.set('faks',faks);        
  rey.set('def',def);

  $.ajax({ 
    "url"    : base_url+"Master_Gudang/savedata", 
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
      console.error(xhr.responseText);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        $('#modal').modal('hide');                
        toastr.success("Data gudang berhasil disimpan");                  
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

    $.ajax({ 
      "url"    : base_url+"Master_Gudang/getdata",       
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
          const _divisi = $("<option selected='selected'></option>").val(result.data[0]['iddivisi']).text(result.data[0]['divisi']);;

          $('#id').val(result.data[0]['id']);            
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#divisi').append(_divisi);  
          $('#kontak').val(result.data[0]['kontak']);          
          $('#alamat').val(result.data[0]['alamat']);
          $('#kota').val(result.data[0]['kota']);
          $('#provinsi').val(result.data[0]['propinsi']);
          $('#negara').val(result.data[0]['negara']);
          $('#telp').val(result.data[0]['telp']);
          $('#faks').val(result.data[0]['faks']);

          if(result.data[0]['def']=='1'){
            $("#chkdefault").prop('checked',true);
          }

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}