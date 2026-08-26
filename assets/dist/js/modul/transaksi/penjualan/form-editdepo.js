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

  $('.qty').inputmask({
    alias:'numeric',
    digits:$("#decqty").val(),
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

  $('.datepicker').datepicker();

  $('.datepicker').inputmask({
    alias:'dd/mm/yyyy',
    mask: "1-2-y", 
    placeholder: "_", 
    leapday: "-02-29", 
    separator: "-"
  })

 

 

}  

   // $('#tanggaldepo').on('change',function(){
  //     if($('#id').val()!=='') return; 
  //    _set_nomor_ip($('#tanggaldepo').val()); 
  //});   
 // 
  

  $('.kuncitext').attr('disabled','disabled');   

function _clearForm(){
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);  
  $('.datepicker').datepicker('setDate','dd-mm-yy');       
  
  
  $('.kuncitext').attr('disabled','disabled'); 
  
  
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
      $('#kode').attr('data-title','Kode bank harus diisi !');      
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }
    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama bank harus diisi !');      
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }
    return 1;
});

var _saveData = (function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val();

  var rey = new FormData();  
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);

  $.ajax({ 
    "url"    : base_url+"Master_Bank/savedata", 
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
        toastr.success("Data bank berhasil disimpan");                  
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  });
});

var _addRow2 = () => { 
    let newrow = " <tr>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><select name='satuan[]' class='satuan form-control select2 form-control-sm' style=\"width:100%\"></select></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"harga[]\" class=\"harga form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
        newrow += "<td><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"diskon[]\" class=\"diskon form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";      
        newrow += "<td><input type=\"tel\" name=\"persen[]\" class=\"persen form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><input type=\"hidden\" name=\"noref[]\" class=\"noref\"><input type=\"hidden\" name=\"nomordp[]\" class=\"nomordp\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"text\" name=\"subtotal[]\" class=\"subtotal form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" tabindex=\"-1\" readonly></div></td>";      
        newrow += "<td><input type=\"hidden\" name=\"sdid[]\" class=\"sdid\"><textarea name=\"catatan[]\" class=\"form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
        newrow += "<td><input type=\"text\" name=\"sunotrans[]\" class=\"sunotrans form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
        newrow += "</tr>";
    $('#tdatatindakan tbody').append(newrow);
  }
  
 
  
var _addRow1111= () => { 
    let newrow = " <tr>";
        newrow += "<td><input type=\"tel\" name=\"tindakan[]\" class=\"form-control form-control-sm\" autocomplete=\"off\"  readonly></td>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"tel\" name=\"subtotal[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"text\" name=\"noref[]\" class=\"noref form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
        newrow += "<td><button type=\"button\" id=\"bdetailitem\" name=\"bdetailitem\" class=\"bdetailitem btn btn-info btn-step1 text-sm btn-sm \" role=\"button\" aria-expanded=\"false\">PILIH</button></td>";
        newrow += "</tr>";
    $('#tdatatindakan tbody').append(newrow);
  }
   
var _addRow = () => { 
    let newrow = " <tr>";
        newrow += "<td><input type=\"tel\" name=\"tindakan[]\" class=\"form-control form-control-sm\" autocomplete=\"off\"  readonly><input type=\"hidden\" name=\"idtindakan[]\" class=\"idtindakan\"></td>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"tel\" name=\"subtotal[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"text\" name=\"noref[]\" class=\"noref form-control form-control-sm\" autocomplete=\"off\" readonly><input type=\"hidden\" name=\"sdid[]\" class=\"sdid\"><input type=\"hidden\" name=\"sdidalkesnya[]\" class=\"sdidalkesnya\"></td>"; 
        newrow += "<td><input type=\"checkbox\" class=\"pilih form-control form-control-sm\" id=\"chkpilih[]\"  name=\"chkpilih[]\">  </td>";
        newrow += "<td><button type=\"button\" id=\"baddalkes\" name=\"baddalkes\" class=\"baddalkes btn btn-info btn-step1 text-sm btn-sm \" role=\"button\" aria-expanded=\"false\">...</button></td>";
        newrow += "</tr>";
    $('#tdatatindakan tbody').append(newrow);
  }
   
var _addRow_alkes = () => { 
    let newrow = " <tr>";
        newrow += "<td><input type=\"tel\" name=\"produk_alkes[]\" class=\"form-control form-control-sm\" autocomplete=\"off\"  readonly><input type=\"hidden\" name=\"idproduk_alkes[]\" class=\"idproduk_alkes\"></td>";
        newrow += "<td><input type=\"tel\" name=\"qty_alkes[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><input type=\"tel\" name=\"qtystandar_alkes[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"text\" name=\"satuan_alkes[]\" class=\"form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly><input type=\"hidden\" name=\"idsatuan_alkes[]\" class=\"idsatuan_alkes\"></td>";
        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a>  </td>";
        newrow += "</tr>";
    $('#tdataalkes tbody').append(newrow);
  }
  
 
              
              
              
  
  var _set_nomor_ip = (xtgl) => { 
    $.ajax({ 
        "url"    : base_url+"PJ_Editdepo/getnomortransaksi",       
        "type"   : "POST", 
        "dataType" : "json", 
        "data" : "tgl="+xtgl,
        "cache"  : false,
        "error"  : () => {
            parent.window.toastr.error('Error : Gagal mengambil no transaksi !');
            parent.window.$('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : (result) => {
             $('#nodepo').val(result.data[0]['no']);
        } 
  })
} 

        $(document).on("click","#tdatatindakan input[name^='chkpilih']", function(e){
                    var _index = $(this).index('.pilih');
                    var isChecked = $(this).prop("checked");
                    var _idtindakan = $("input[name^='idtindakan']").eq(_index).val();
                    var _idalkesnya = $("input[name^='sdidalkesnya']").eq(_index).val();
                    var _sdid = $("input[name^='sdid']").eq(_index).val();
                    
                    
                    
                    
                     if (isChecked==true) { 
                             $("input[name^='tindakan']").each(function(index,element){   
                                if(index!==_index)   $(".pilih").eq(index).prop("checked", false);   
                            }); 
                       if (_idalkesnya!=='') {
                          // alert('Tindakan sudah ada penyusun alkesnya');
                           
                           parent.window.Swal.fire({
                              title: `Tindakan sudah diinput penyusun alkesnya !` 
                          })
          
          
                          _getData_tindakan_alkes(_sdid);  
                       }   
                       else {
                           
                             parent.window.Swal.fire({
                              title: `Tindakan belum diinput penyusun !` 
                          })
                          
                          _getData_alkes(_idtindakan); 
                            
                       }   
                    }  
        })
        
          $(document).on("click","#baddalkes", function(e){
                    var _index = $(this).index('.pilih');
                    var isChecked = $(this).prop("checked");
                    var _idtindakan = $("input[name^='idtindakan']").eq(_index).val();
                    var _idalkesnya = $("input[name^='sdidalkesnya']").eq(_index).val();
                    var _sdid = $("input[name^='sdid']").eq(_index).val(); 
                          
                          _getData_alkes(_idtindakan);  
        })
 
 
        
 
function _getData_tindakan_alkes(id){  
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"PJ_Editdepo/getdata_tindakan_alkes",       
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
        } else if (result.data.length==0) { // Jika ada pesan maka tampilkan pesan
          toastr.error('Data Kosong');
          $('.loader-wrap').addClass('d-none'); 
          return; 
        } else { // Jika tidak ada pesan tampilkan json ke form 
        
        var rows = 0 ; 
       
        $('#tdataalkes tbody').html('');
        
            $('#nodepo').val(result.data[0]['sunotransaksi']);
            $('#tgldepo').val(result.data[0]['sutanggal']);
            $('#id').val(result.data[0]['id']);
        
        

        $.each(result.data, function() {
          _addRow_alkes();
          _inputFormat();       

        // alert(result.data[0]['produk_alkes']);
          $("input[name^='produk_alkes']").eq(rows).val(result.data[rows]['inama']);   
          $("input[name^='idproduk_alkes']").eq(rows).val(result.data[rows]['iid']);   
          $("input[name^='satuan_alkes']").eq(rows).val(result.data[rows]['skode']);   
          $("input[name^='idsatuan_alkes']").eq(rows).val(result.data[rows]['sdsatuan']);  
          $("input[name^='qty_alkes']").eq(rows).val(result.data[rows]['sdkeluar'].replace(".", ","));     
          $("input[name^='qtystandar_alkes']").eq(rows).val(result.data[rows]['sdkeluar'].replace(".", ","));    
                

          //atur placeholder numeric jika 0
          if(result.data[rows]['sdkeluar']==0) $("input[name^='qty_alkes']").eq(rows).attr('placeholder','0,00');    
          if(result.data[rows]['sdkeluar']==0) $("input[name^='qtystandar_alkes']").eq(rows).attr('placeholder','0,00');                     

          rows++;
        });
         

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
} 
 
function _getData_alkes(id){ 
  
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"PJ_Editdepo/getdata_alkes",       
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
        } else if (result.data[0]['produk_alkes'] == '') { // Jika kosong 
          toastr.error('Tidak ada data penyusun');
          $('.loader-wrap').addClass('d-none');  
          return;
        } else { // Jika tidak ada pesan tampilkan json ke form 
        
        var rows = 0 ; 
       
        $('#tdataalkes tbody').html('');
         $('#nodepo').val('');

        $.each(result.data, function() {
          _addRow_alkes();
          _inputFormat();       

        // alert(result.data[0]['produk_alkes']);
          $("input[name^='produk_alkes']").eq(rows).val(result.data[rows]['produk_alkes']);   
          $("input[name^='idproduk_alkes']").eq(rows).val(result.data[rows]['idproduk_alkes']);   
          $("input[name^='satuan_alkes']").eq(rows).val(result.data[rows]['satuan_alkes']);   
          $("input[name^='idsatuan_alkes']").eq(rows).val(result.data[rows]['idsatuan_alkes']);  
          $("input[name^='qty_alkes']").eq(rows).val(result.data[rows]['qtystandar_alkes'].replace(".", ","));     
          $("input[name^='qtystandar_alkes']").eq(rows).val(result.data[rows]['qtystandar_alkes'].replace(".", ","));    
                

          //atur placeholder numeric jika 0
          if(result.data[rows]['qtystandar_alkes']==0) $("input[name^='qty_alkes']").eq(rows).attr('placeholder','0,00');    
          if(result.data[rows]['qtystandar_alkes']==0) $("input[name^='qtystandar_alkes']").eq(rows).attr('placeholder','0,00');                     

          rows++;
        });
         

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}


function _getData(id){
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"PJ_Editdepo/getdata",       
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
          //$('#id').val(result.data[0]['id']);  
          $('#namapasien').val(result.data[0]['namapasien']);
          $('#noip').val(result.data[0]['notransaksi']);
          $('#tanggalip').val(result.data[0]['tanggal']);
          $('#cabang').val(result.data[0]['cabang']);
          $('#cabangid').val(result.data[0]['cabangid']);
          
          
          
          
        var rows = 0 ;
        
        $('#tdatatindakan tbody').html('');
        $('#tdataalkes tbody').html('');

        $.each(result.data, function() {
          _addRow();
          _inputFormat();       

        
          $("input[name^='tindakan']").eq(rows).val(result.data[rows]['tindakan']);  
          $("input[name^='idtindakan']").eq(rows).val(result.data[rows]['idtindakan']);   
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qty'].replace(".", ","));            
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotal'].replace(".", ","));                        
          $("input[name^='noref']").eq(rows).val(result.data[rows]['noref']);                                               
          $("input[name^='sdid']").eq(rows).val(result.data[rows]['sdid']);                                                                    

                

          //atur placeholder numeric jika 0
          if(result.data[rows]['qty']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['subtotal']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');                        

          rows++;
        });
        
        $('.datepicker').datepicker('setDate','dd-mm-yy');   
          

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}

function _getData2(id){
    if(id=='' || id==null) return;    

    $.ajax({ 
      "url"    : base_url+"PJ_Editdepo/getdata",       
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
          //$('#id').val(result.data[0]['id']);  
          $('#namapasien').val(result.data[0]['namapasien']);
          $('#noip').val(result.data[0]['notransaksi']);
          $('#tanggalip').val(result.data[0]['tanggal']);
          $('#cabang').val(result.data[0]['cabang']);
          $('#cabangid').val(result.data[0]['cabangid']);
          
          
          
          
        var rows = 0 ;
        
        $('#tdatatindakan tbody').html('');
        $('#tdataalkes tbody').html('');

        $.each(result.data, function() {
          _addRow();
          _inputFormat();       

        
          $("input[name^='tindakan']").eq(rows).val(result.data[rows]['tindakan']);   
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qty'].replace(".", ","));            
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotal'].replace(".", ","));                        
          $("input[name^='noref']").eq(rows).val(result.data[rows]['noref']);                                                                    

                

          //atur placeholder numeric jika 0
          if(result.data[rows]['qty']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');            
          if(result.data[rows]['subtotal']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');                        

          rows++;
        });
        
        $('.datepicker').datepicker('setDate','dd-mm-yy');   
          

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}


window._hapusbaris = async (obj) => {
  if($(obj).hasClass('disabled')) return;    

  $(obj).parent().parent().remove();
  await _hitungsubtotal();
  _hitungTotal();
}