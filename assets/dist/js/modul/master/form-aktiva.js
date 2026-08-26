/* Form Init */

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
    //console.dir(opts);
    return value;
  },
  removeMaskOnSubmit:false
});

$('.numeric-single').inputmask({
  alias:'numeric',
  digits:'0',
  digitsOptional:false,
  isNumeric: true,      
  prefix:'',
  groupSeparator:".",
  placeholder: '0',
  radixPoint:",",
  autoGroup:true,
  autoUnmask:true,
  onBeforeMask: function (value, opts) {
    //console.dir(opts);
    return value;
  },
  removeMaskOnSubmit:false
});

$('.datepicker').datepicker();

$('.datepicker').inputmask({
  alias:'dd/mm/yyyy',
  mask: "1-2-y", 
  placeholder: "_", 
  leapday: "-02-29", 
  separator: "-"
});    

$('#metode').select2({
  "theme":'bootstrap4',
  "dropdownParent": parent.window.$('#tabAktiva'),            
  "minimumResultsForSearch": "Infinity"           
}); 

$('#kelaktiva').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": parent.window.$('#modal'),          
     "ajax": {
        "url": base_url+"Select_Master/view_kelompok_aktiva",
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

$('#divisi').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "minimumResultsForSearch": "Infinity",          
     "dropdownParent": parent.window.$('#modal'),          
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

$('#coaaktiva,#coapenyusutan,#coabiaya,#coawo').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": parent.window.$('#tabAktiva'),          
     "ajax": {
        "url": base_url+"Select_Master/view_coa",
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
     "templateResult": textSelect,              
});

function textSelect(par){
  if(!par.id){
    return par.text;
  }
  var $par = $('<span>('+par.kode+') '+par.text+'</span>');
  return $par;
}

$(this).on('shown.bs.tooltip', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});

$("#dTglBeli").click(function() {
  if($(this).attr('role')) {
    $("#tglBeli").focus();
  }
});

$("#dTglPakai").click(function() {
  if($(this).attr('role')) {
    $("#tglPakai").focus();
  }
});

$("#kelaktiva").on("select2:select",function(){
  //alert($(this).val());
  const $kel = $(this).val();

    $.ajax({ 
      "url"    : base_url+"Master_Kelompok_Aktiva/getdata", 
      "type"   : "POST", 
      "data"   : "id="+$(this).val(),
      "dataType" : "json", 
      "cache"  : false,
      "beforeSend" : function(){
        $("#loader-detil").removeClass('d-none');
      },        
      "error"  : function(){
        console.error('error ambil informasi kelompok aktiva...');
        return;
      },
      "success"  : function(result) {
        const _coaaktiva = $("<option selected='selected'></option>").val(result.data[0]['idcoaaktiva']).text(result.data[0]['coaaktiva']),
              _coadepresiasi = $("<option selected='selected'></option>").val(result.data[0]['idcoadepresiasi']).text(result.data[0]['coadepresiasi']),
              _coadepresiasiakum = $("<option selected='selected'></option>").val(result.data[0]['idcoadepresiasiakum']).text(result.data[0]['coadepresiasiakum']),
              _coawriteoff = $("<option selected='selected'></option>").val(result.data[0]['idcoawriteoff']).text(result.data[0]['coawriteoff']);

        $("#utahun").val(result.data[0]['umur']);
        $("#coaaktiva").append(_coaaktiva).trigger("change");
        $("#coapenyusutan").append(_coadepresiasiakum).trigger("change");        
        $("#coabiaya").append(_coadepresiasi).trigger("change");        
        $("#coawo").append(_coawriteoff).trigger("change");
        return;                    
    } 
    });
});

/* End Form Init */

$("#submit").click(function(){
//  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = (function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode aktiva harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama aktiva harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }
    if ($('#kelaktiva').val()=='' || $('#kelaktiva').val()==null){
      $('#kelaktiva').attr('data-title','Kelompok aktiva harus diisi !');      
      $('#kelaktiva').tooltip('show');
      $('#kelaktiva').focus();
      return 0;
    }
    if ($('#tglBeli').val()==''){
      $('#tglBeli').attr('data-title','Tanggal beli harus diisi !');      
      $('#tglBeli').tooltip('show');
      $('#tglBeli').focus();
      return 0;
    }        
    if ($('#tglPakai').val()==''){
      $('#tglPakai').attr('data-title','Tanggal pakai harus diisi !');      
      $('#tglPakai').tooltip('show');
      $('#tglPakai').focus();
      return 0;
    }            
    if ($('#hargabeli').val()=='' || Number($("#hargabeli").val().split('.').join('').toString().replace(',','.'))==0){
      $('#hargabeli').attr('data-title','Harga beli harus diisi !');      
      $('#hargabeli').tooltip('show');
      $('#hargabeli').focus();
      return 0;
    }            
    return 1;
});

var _saveData = (function(){

  var _tgl15 = 0, _intangible = 0;
  if($('#chk15').prop('checked')){
    _tgl15 = 1;
  }
  if($('#chkintangible').prop('checked')){
    _intangible = 1;
  }  

  const rey = new FormData();  
  rey.set('id',$("#id").val());
  rey.set('kode',$("#kode").val());
  rey.set('serial',$("#serial").val());  
  rey.set('nama',$("#nama").val());
  rey.set('divisi',$("#divisi").val());
  rey.set('kelompok',$("#kelaktiva").val());
  rey.set('lokasi',$("#lokasi").val());  
  rey.set('tglbeli',$("#tglBeli").val());  
  rey.set('tglpakai',$("#tglPakai").val());    
  rey.set('hargabeli',Number($("#hargabeli").val().split('.').join('').toString().replace(',','.')));
  rey.set('bebanpenyusutan',Number($("#beban").val().split('.').join('').toString().replace(',','.')));    
  rey.set('akumpenyusutan',Number($("#totalakum").val().split('.').join('').toString().replace(',','.')));      
  rey.set('utahun',Number($("#utahun").val()));    
  rey.set('ubulan',Number($("#ubulan").val()));      
  rey.set('residu',Number($("#residu").val().split('.').join('').toString().replace(',','.')));  
  rey.set('metode',$("#metode").val());      
  rey.set('qty',Number($("#qty").val().split('.').join('').toString().replace(',','.')));  
  rey.set('tgl15',_tgl15);
  rey.set('intangible',_intangible);
  rey.set('coaaktiva',$("#coaaktiva").val());      
  rey.set('coaakum',$("#coapenyusutan").val());       
  rey.set('coabiaya',$("#coabiaya").val());          
  rey.set('coawo',$("#coawo").val());       

  $.ajax({ 
    "url"    : base_url+"Master_Aktiva/savedata", 
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
        toastr.success("Data aktiva berhasil disimpan");                  
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
      "url"    : base_url+"Master_Aktiva/getdata",       
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
          const _kelompok = $("<option selected='selected'></option>").val(result.data[0]['idkelompok']).text(result.data[0]['kelompok']),
                _divisi = $("<option selected='selected'></option>").val(result.data[0]['iddivisi']).text(result.data[0]['divisi']),
                _wo = $("<option selected='selected'></option>").val(result.data[0]['idwo']).text(result.data[0]['wo']),
                _coaaktiva = $("<option selected='selected'></option>").val(result.data[0]['idcoaaktiva']).text(result.data[0]['coaaktiva']),
                _coapenyusutan = $("<option selected='selected'></option>").val(result.data[0]['idcoapenyusutan']).text(result.data[0]['coapenyusutan']),
                _coaakum = $("<option selected='selected'></option>").val(result.data[0]['idcoaakum']).text(result.data[0]['coaakum']);

          $('#id').val(result.data[0]['id']);            
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#serial').val(result.data[0]['serial']);
          $('#lokasi').val(result.data[0]['lokasi']);
          if(result.data[0]['divisi']!==null) $('#divisi').append(_divisi);               
          if(result.data[0]['kelompok']!==null) $('#kelaktiva').append(_kelompok);                    
          $('#tglBeli').datepicker('setDate',result.data[0]['tglbeli']);
          $('#tglPakai').datepicker('setDate',result.data[0]['tglpakai']);          
          $('#metode').val(result.data[0]['metode']).trigger('change');
          $('#qty').val(result.data[0]['qty'].replace(".", ","));
          $('#hargabeli').val(result.data[0]['hargabeli'].replace(".", ","));          
          $('#totalakum').val(result.data[0]['akum'].replace(".", ","));                    
          $('#beban').val(result.data[0]['perbulan'].replace(".", ","));                    
          $('#utahun').val(result.data[0]['utahun'].replace(".", ","));
          $('#ubulan').val(result.data[0]['ubulan'].replace(".", ","));
          $('#nilaibuku').val(result.data[0]['nilaibuku'].replace(".", ","));          
          $('#residu').val(result.data[0]['residu'].replace(".", ","));

          //atur placeholder numeric jika 0
          if(result.data[0]['utahun']==0) $("#utahun").attr('placeholder','0');            
          if(result.data[0]['ubulan']==0) $("#ubulan").attr('placeholder','0');
          if(result.data[0]['perbulan']==0) $("#beban").attr('placeholder','0,00');                        
          if(result.data[0]['akum']==0) $("#totalakum").attr('placeholder','0,00');
          if(result.data[0]['hargabeli']==0) $("#hargabeli").attr('placeholder','0,00');
          if(result.data[0]['qty']==0) $("#qty").attr('placeholder','0,00');
          if(result.data[0]['nilaibuku']==0) $("#nilaibuku").attr('placeholder','0,00');
          if(result.data[0]['residu']==0) $("#residu").attr('placeholder','0,00');

          if(result.data[0]['intangible']=='1'){
            $("#chkintangible").prop('checked',true);
          }
          if(result.data[0]['tgl15']=='1'){
            $("#chk15").prop('checked',true);
          }

          if(result.data[0]['wo']!==null) $('#coawo').append(_wo);
          if(result.data[0]['coaaktiva']!==null) $('#coaaktiva').append(_coaaktiva);
          if(result.data[0]['coapenyusutan']!==null) $('#coabiaya').append(_coapenyusutan);          
          if(result.data[0]['coaakum']!==null) $('#coapenyusutan').append(_coaakum);
          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}

$("#hargabeli,#utahun,#ubulan,#residu,#totalakum").on("keyup", function(){
  _hitungpenyusutan();
  return;
});

$("#metode").on("select2:select", function(){
  _hitungpenyusutan();
  return;
});

var _hitungpenyusutan = function(){
  const hargabeli = Number($("#hargabeli").val().split('.').join('').toString().replace(',','.')),
        residu = Number($("#residu").val().split('.').join('').toString().replace(',','.')),
        tahun = Number($("#utahun").val().split('.').join('').toString().replace(',','.')),
        bulan = Number($("#ubulan").val().split('.').join('').toString().replace(',','.')),
        metode = $("#metode").val(),
        umur = (tahun*12)+bulan;

  var biaya = 0;

  if(metode==0 || hargabeli==0){
    $('#beban').val(biaya).attr('placeholder','0,00');
    return;
  }
  if(metode==1 && hargabeli>0){
    if(umur>0) biaya = (hargabeli-residu)/umur;
    biaya = biaya.toString().replace('.',',');
    if (biaya==0) biaya = '0,00';
    $('#beban').val(biaya).attr('placeholder',biaya);
    return;
  }  
  if(metode==2 && hargabeli>0){
//    if(umur>0) biaya = (hargabeli-residu)/(umur/2);
//    biaya = biaya.toString().replace('.',',');
//    if (biaya==0) biaya = '0,00';
//    $('#beban').val(biaya).attr('placeholder',biaya);
    $('#beban').val(biaya).attr('placeholder','0,00');
    return;
  }  

}