/* Form Init */

$('.select2').select2({
    "theme":'bootstrap4',
    "minimumResultsForSearch": "Infinity"                
});

$('#tabel').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "minimumResultsForSearch": "Infinity",                
     "dropdownParent": parent.window.$('#modal'),          
     "ajax": {
        "url": base_url+"Select_Master/view_tabel_list",
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

$('#menu').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": parent.window.$('#modal'),          
     "ajax": {
        "url": base_url+"Select_Master/view_menu_list",
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


$('#tanggal,#sumber,#notrans,#kontak,#uraian,#total,#idtrans').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "minimumResultsForSearch": "Infinity",                
     "dropdownParent": parent.window.$('#modal'),          
     "ajax": {
        "url": base_url+"Select_Master/view_field_list",
        "type": "post",
        "dataType": "json",                                       
        "delay": 800,
        "data": function(params) {
          return {
            tabel: $('#tabel').val()
          }
        },
        "processResults": function (data, page) {
        return {
          results: data
        };
      },
    },
});

$("#submit").click(() => {
    if (_IsValid()===0) return;
    _saveData();
});

var _IsValid = () => {
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#keterangan').val()==''){
      $('#keterangan').attr('data-title','Keterangan harus diisi !');      
      $('#keterangan').tooltip('show');
      $('#keterangan').focus();
      return 0;
    }

    return 1;
};

var _saveData = () => {

    const rey = new FormData();  
    rey.set('id',$("#id").val());
    rey.set('kode',$("#kode").val());
    rey.set('keterangan',$("#keterangan").val());
    rey.set('tabel',$("#tabel").val());
    rey.set('tanggal',$("#tanggal").val());    
    rey.set('sumber',$("#sumber").val());        
    rey.set('notrans',$("#notrans").val());            
    rey.set('kontak',$("#kontak").val()); 
    rey.set('uraian',$("#uraian").val());                    
    rey.set('totaltrans',$("#total").val());                        
    rey.set('idtrans',$("#idtrans").val());                        
    rey.set('nfa',$("#nfa").val());                            
    rey.set('menu',$("#menu").val());                                

    $.ajax({ 
      "url"    : base_url+"Settings_Nomor/savedata", 
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
          toastr.error("Error : "+xhr.status+" "+error);      
          console.error(xhr.responseText);      
          return;
      },
      "success": (result) => {
          $(".loader-wrap").addClass("d-none");        
          if(result=='sukses'){
            $('#modal').modal('hide');                
            toastr.success("Data berhasil disimpan");                  
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
      "url"    : base_url+"Settings_Nomor/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data"   : "id="+id,
      "cache"  : false,
      "beforeSend" : () => {
          $('.loader-wrap').removeClass('d-none');        
      },        
      "error"  : (xhr,status,error) => {
          $(".main-modal-body").html('');        
          toastr.error("Error : "+xhr.status+" "+error);
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
            $('#id').val(result.data[0]['NID']);            
            $('#kode').val(result.data[0]['NKODE']);
            $('#keterangan').val(result.data[0]['NKETERANGAN']);    
            $('#tabel').append($("<option selected='selected'></option>").val(result.data[0]['NTABEL']).text(result.data[0]['NTABEL'].toUpperCase())).trigger('change');                                                      
            $('#tanggal').append($("<option selected='selected'></option>").val(result.data[0]['NFLDTANGGAL']).text(result.data[0]['NFLDTANGGAL'])).trigger('change');                                          
            $('#sumber').append($("<option selected='selected'></option>").val(result.data[0]['NFLDSUMBER']).text(result.data[0]['NFLDSUMBER'])).trigger('change');
            $('#notrans').append($("<option selected='selected'></option>").val(result.data[0]['NFLDNOTRANSAKSI']).text(result.data[0]['NFLDNOTRANSAKSI'])).trigger('change');
            $('#kontak').append($("<option selected='selected'></option>").val(result.data[0]['NFLDKONTAK']).text(result.data[0]['NFLDKONTAK'])).trigger('change');
            $('#uraian').append($("<option selected='selected'></option>").val(result.data[0]['NFLDURAIAN']).text(result.data[0]['NFLDURAIAN'])).trigger('change');
            $('#total').append($("<option selected='selected'></option>").val(result.data[0]['NFLDTOTALTRANS']).text(result.data[0]['NFLDTOTALTRANS'])).trigger('change');
            $('#idtrans').append($("<option selected='selected'></option>").val(result.data[0]['NFLDID']).text(result.data[0]['NFLDID'])).trigger('change');                                    
            $('#nfa').val(result.data[0]['NFA']).trigger('change');
            if(result.data[0]['menu'] != null) $('#menu').append($("<option selected='selected'></option>").val(result.data[0]['NFMENU']).text(result.data[0]['menu'])).trigger('change');                      
            $('.loader-wrap').addClass('d-none');                                       
            return;
        }
    } 
  })
}