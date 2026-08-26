/* Form Init */

$('.select2').select2({
    "theme":'bootstrap4',
    "minimumResultsForSearch": "Infinity"           
});

$('.numeric').inputmask({
  alias:'numeric',
  digits:'0',
  digitsOptional:false,
  isNumeric: true,      
  prefix:'',
  groupSeparator:".",
  placeholder: '0',
  radixPoint:"",
  rightAlign:false,
  autoGroup:true,
  autoUnmask:true,
  onBeforeMask: function (value, opts) {
    return value;
  },
  removeMaskOnSubmit:false
});

$("#submit").click(() => {
    if (_IsValid()===0) return;
    _saveData();
});

var _IsValid = () => {
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama laporan harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }
    return 1;
};

var _saveData = () => {

    var _aktif = 0,
        _fTgl1 = 0,
        _fTgl2 = 0,
        _fKontak = 0,
        _fAkun = 0,
        _fSumber = 0,
        _fItem = 0,
        _fSaldo = 0;
        _fGudang = 0;        
        _fNomor = 0;           
        _fNamapt = 0;               

    if($('#aktif').prop('checked')) _aktif = 1;
    if($('#fRentangTgl').prop('checked')) _fTgl1 = 1;    
    if($('#fPerTgl').prop('checked')) _fTgl2 = 1;  
    if($('#fKontak').prop('checked')) _fKontak = 1;
    if($('#fAkun').prop('checked')) _fAkun = 1;                  
    if($('#fSumber').prop('checked')) _fSumber = 1;
    if($('#fItem').prop('checked')) _fItem = 1;        
    if($('#fSaldo0').prop('checked')) _fSaldo = 1;            
    if($('#fGudang').prop('checked')) _fGudang = 1;                  
    if($('#fNomor').prop('checked')) _fNomor = 1;                 
    if($('#fNamapt').prop('checked')) _fNamapt = 1;                    

    const rey = new FormData();  
    rey.set('id',$("#id").val());
    rey.set('nama',$("#nama").val());
    rey.set('nama2',$("#nama2").val());
    rey.set('judul',$("#judul").val());
    rey.set('link',$("#link").val());  
    rey.set('ukuran',$("#ukuran").val());    
    rey.set('orientasi',$("#orientasi").val());
    rey.set('marginl',$("#marginL").val());    
    rey.set('margint',$("#marginT").val());        
    rey.set('aktif',_aktif);              
    rey.set('f1',_fTgl1);
    rey.set('f2',_fTgl2);                      
    rey.set('f3',_fKontak);
    rey.set('f4',_fAkun);
    rey.set('f5',_fSumber);
    rey.set('f6',_fItem);                                                                                                        
    rey.set('f7',_fSaldo);
    rey.set('f8',_fGudang);
    rey.set('f9',_fNomor);     
    rey.set('f10',_fNamapt);                                                                                                                   

    $.ajax({ 
      "url"    : base_url+"Admin_Report/savedata", 
      "type"   : "POST", 
      "data"   : rey,
      "processData": false,
      "contentType": false,
      "cache"    : false,
      "beforeSend" : () => {
          $(".loader-wrap").removeClass("d-none");
      },
      "error": (xhr, status, error) => {
          $(".loader-wrap").addClass("d-none");
          toastr.error("Perbaiki masalah ini : "+xhr.status+" "+error);      
          console.error(xhr.responseText);      
          return;
      },
      "success": (result) => {

          $(".loader-wrap").addClass("d-none");        

          if(result=='sukses'){
            $('#modal').modal('hide');                
            toastr.success("Data laporan berhasil disimpan");                  
            return;
          } else {        
            toastr.error(result);                          
            return;
          }
      } 
    });
};

var _getData = (id) => {

    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"Admin_Report/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data"   : "id="+id,
      "cache"  : false,
      "beforeSend" : () => {
          $('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : (xhr,status,error) => {
          $(".main-modal-body").html('');        
          toastr.error("Perbaiki kesalahan ini : "+xhr.status+" "+error);
          console.error(xhr.responseText);
          $('.loader-wrap').addClass('d-none');                  
          return;
      },
      "success" : (result) => {
        if (typeof result.pesan !== 'undefined') {
            toastr.error(result.pesan);
            $('.loader-wrap').addClass('d-none'); 
            return;
        } else {
            $('#id').val(result.data[0]['ARID']);            
            $('#nama').val(result.data[0]['ARNAME']);
            $('#nama2').val(result.data[0]['ARNAME2']);          
            $('#link').val(result.data[0]['ARLINK']);                      
            $('#judul').val(result.data[0]['ARTITLE']);          
            $('#marginL').val(result.data[0]['ARMARGINLEFT']);                      
            $('#marginT').val(result.data[0]['ARMARGINTOP']);                                  
            $('#orientasi').val(result.data[0]['ARPAPERORINTED']).trigger('change');
            $('#ukuran').val(result.data[0]['ARPAPERSIZE']).trigger('change');

            if(result.data[0]['ARACTIVE']=='1'){
                $("#aktif").prop('checked',true);
            }
            if(result.data[0]['ARDATE1F']=='1'){
                $("#fRentangTgl").prop('checked',true);
            }            
            if(result.data[0]['ARDATE2F']=='1'){
                $("#fPerTgl").prop('checked',true);
            }                        
            if(result.data[0]['ARKONTAKF']=='1'){
                $("#fKontak").prop('checked',true);
            }            
            if(result.data[0]['ARCOAF']=='1'){
                $("#fAkun").prop('checked',true);
            }                        
            if(result.data[0]['ARSOURCEF']=='1'){
                $("#fSumber").prop('checked',true);
            }                                    
            if(result.data[0]['ARITEMF']=='1'){
                $("#fItem").prop('checked',true);
            }                                                
            if(result.data[0]['ARSALDOF']=='1'){
                $("#fSaldo0").prop('checked',true);
            }                                                
            if(result.data[0]['ARGUDANGF']=='1'){
                $("#fGudang").prop('checked',true);
            }                                                            
            if(result.data[0]['ARNOMORF']=='1'){
                $("#fNomor").prop('checked',true);
            }                                                            
            if(result.data[0]['ARPTF']=='1'){
                $("#fNamapt").prop('checked',true);
            }                                         
                                                                                    

            $('.loader-wrap').addClass('d-none');                                       
            return;
        }
    } 
  })
}