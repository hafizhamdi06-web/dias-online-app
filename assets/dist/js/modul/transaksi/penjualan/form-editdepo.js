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
$(this).off('shown.bs.tooltip.editdepo').on('shown.bs.tooltip.editdepo', function (e) {
  setTimeout(function () {
    $(e.target).tooltip('hide');
  }, 2000);
});
/* End Form Init */

$("#submit").click(function(){
  if (_IsValid()===0) return;
  _saveData();
});

var _IsValid = () => {
    if ($('#tanggaldepo').val()==''){
      $('#tanggaldepo').attr('data-title','Tanggal Depo harus diisi !');
      $('#tanggaldepo').tooltip('show');
      $('#tanggaldepo').focus();
      return 0;
    }
    if ($('.pilih:checked').length===0){
      toastr.error('Pilih salah satu Tindakan terlebih dahulu !');
      return 0;
    }
    var _index = $('.pilih:checked').index('.pilih');
    var $detailRow = $('tr.row-tindakan').eq(_index).next('.row-alkes-detail');
    if ($detailRow.find('tbody.tbody-alkes tr').length===0){
      toastr.error('Data Alkes untuk Tindakan ini masih kosong !');
      return 0;
    }
    return 1;
};

var _saveData = () => {
  var _index = $('.pilih:checked').index('.pilih');
  var $tindakanRow = $('tr.row-tindakan').eq(_index);
  var $detailRow = $tindakanRow.next('.row-alkes-detail');
  var $alkesRows = $detailRow.find('tbody.tbody-alkes tr');

  var sdidtindakan = $tindakanRow.find("input[name='sdid[]']").val();
  var namatindakan = $tindakanRow.find("input[name^='tindakan']").val();

  var detil = [];
  $alkesRows.each(function(){
    var $row = $(this);
    detil.push({
      iid: $row.find("input[name^='idproduk_alkes']").val(),
      qty: Number($row.find("input[name^='qty_alkes']").val().split('.').join('').toString().replace(',','.')),
      idsatuan: $row.find("input[name^='idsatuan_alkes']").val(),
      qtystandar: Number($row.find("input[name^='qtystandar_alkes']").val().split('.').join('').toString().replace(',','.'))
    });
  });

  var rey = new FormData();
  rey.set('id', $('#id').val());
  rey.set('tanggaldepo', $('#tanggaldepo').val());
  rey.set('idkontak', $('#idkontak').val());
  rey.set('idcabang', $('#idcabang').val());
  rey.set('noip', $('#noip').val());
  rey.set('idsupos', $('#idsupos').val());
  rey.set('sdidtindakan', sdidtindakan);
  rey.set('namatindakan', namatindakan);
  rey.set('detil', JSON.stringify(detil));

  $.ajax({
    "url"    : base_url+"PJ_Editdepo/savedata",
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
      result = JSON.parse(result);
      $(".loader-wrap").addClass("d-none");

      if(result.pesan=='sukses'){
        $('#id').val(result.id);
        $tindakanRow.find("input[name^='sdidalkesnya']").val(result.id);
        $('#modal').modal('hide');
        toastr.success("Data Depo berhasil disimpan");
        return;
      } else {
        toastr.error("Gagal menyimpan data, silakan coba lagi");
        return;
      }
    }
  });
};

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
    $('#tdatatindakan > tbody').append(newrow);
  }
  
 
  
var _addRow1111= () => { 
    let newrow = " <tr>";
        newrow += "<td><input type=\"tel\" name=\"tindakan[]\" class=\"form-control form-control-sm\" autocomplete=\"off\"  readonly></td>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"tel\" name=\"subtotal[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"text\" name=\"noref[]\" class=\"noref form-control form-control-sm\" autocomplete=\"off\" readonly></td>";
        newrow += "<td><button type=\"button\" id=\"bdetailitem\" name=\"bdetailitem\" class=\"bdetailitem btn btn-info btn-step1 text-sm btn-sm \" role=\"button\" aria-expanded=\"false\">PILIH</button></td>";
        newrow += "</tr>";
    $('#tdatatindakan > tbody').append(newrow);
  }
   
var _addRow = () => {
    let newrow = " <tr class=\"row-tindakan\">";
        newrow += "<td><input type=\"tel\" name=\"tindakan[]\" class=\"form-control form-control-sm\" autocomplete=\"off\"  readonly><input type=\"hidden\" name=\"idtindakan[]\" class=\"idtindakan\"></td>";
        newrow += "<td><input type=\"tel\" name=\"qty[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"tel\" name=\"subtotal[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"text\" name=\"noref[]\" class=\"noref form-control form-control-sm\" autocomplete=\"off\" readonly><input type=\"hidden\" name=\"sdid[]\" class=\"sdid\"><input type=\"hidden\" name=\"sdidalkesnya[]\" class=\"sdidalkesnya\"></td>";
        newrow += "<td><input type=\"checkbox\" class=\"pilih form-control form-control-sm\" id=\"chkpilih[]\"  name=\"chkpilih[]\">  </td>";
        newrow += "<td><button type=\"button\" id=\"baddalkes\" name=\"baddalkes\" class=\"baddalkes btn btn-info btn-step1 text-sm btn-sm \" role=\"button\" aria-expanded=\"false\">...</button></td>";
        newrow += "</tr>";
        newrow += "<tr class=\"row-alkes-detail d-none\"><td colspan=\"6\" class=\"bg-light p-0\">";
        newrow += "<table class=\"table table-sm mb-0 table-alkes-inline\"><thead><tr>";
        newrow += "<th class=\"text-sm text-label text-left px-1 border-0\" style=\"width: 40%\">Nama Alkes #</th>";
        newrow += "<th class=\"text-sm text-label text-right px-1 border-0\">Qty</th>";
        newrow += "<th class=\"text-sm text-label text-right px-1 border-0\">Qty Standar</th>";
        newrow += "<th class=\"text-sm text-label text-left px-1 border-0\">Satuan</th>";
        newrow += "<th class=\"text-sm text-label text-center border-0\" style=\"width: 40px\">Hapus</th>";
        newrow += "</tr></thead><tbody class=\"tbody-alkes\"></tbody></table>";
        newrow += "</td></tr>";
    $('#tdatatindakan > tbody').append(newrow);
  }

var _addRow_alkes = ($targetTbody) => {
    let newrow = " <tr>";
        newrow += "<td><input type=\"tel\" name=\"produk_alkes[]\" class=\"form-control form-control-sm\" autocomplete=\"off\"  readonly><input type=\"hidden\" name=\"idproduk_alkes[]\" class=\"idproduk_alkes\"></td>";
        newrow += "<td><input type=\"tel\" name=\"qty_alkes[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\"></td>";
        newrow += "<td><input type=\"tel\" name=\"qtystandar_alkes[]\" class=\"qty form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly></td>";
        newrow += "<td><input type=\"text\" name=\"satuan_alkes[]\" class=\"form-control form-control-sm\" autocomplete=\"off\" value=\"0\" readonly><input type=\"hidden\" name=\"idsatuan_alkes[]\" class=\"idsatuan_alkes\"></td>";
        newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a>  </td>";
        newrow += "</tr>";
    $targetTbody.append(newrow);
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

        var _selectTindakanRow = (_index) => {
                    var _idtindakan = $("input[name^='idtindakan']").eq(_index).val();
                    var _idalkesnya = $("input[name^='sdidalkesnya']").eq(_index).val();
                    var _sdid = $("input[name='sdid[]']").eq(_index).val();

                    $('#id').val('');
                    $('.row-alkes-detail').addClass('d-none').find('tbody.tbody-alkes').html('');
                    $(".pilih").eq(_index).prop("checked", true);
                    $("input[name^='tindakan']").each(function(index,element){
                        if(index!==_index)   $(".pilih").eq(index).prop("checked", false);
                    });

                    var $detailRow = $('tr.row-tindakan').eq(_index).next('.row-alkes-detail');
                    var $targetTbody = $detailRow.find('tbody.tbody-alkes');
                    $targetTbody.html('');
                    $detailRow.removeClass('d-none');

                    if (_idalkesnya!=='') {
                       _getData_tindakan_alkes(_sdid, $targetTbody);
                    }
                    else {
                       _getData_alkes(_idtindakan, $targetTbody);
                    }
        }

        $(document).off("click.editdepochkpilih").on("click.editdepochkpilih","#tdatatindakan input[name^='chkpilih']", function(e){
                    var _index = $(this).index('.pilih');
                    var isChecked = $(this).prop("checked");

                     if (isChecked==true) {
                       _selectTindakanRow(_index);
                    } else {
                       $('.row-alkes-detail').addClass('d-none').find('tbody.tbody-alkes').html('');
                    }
        })

          $(document).off("click.editdepobaddalkes").on("click.editdepobaddalkes","#baddalkes", function(e){
                    var _index = $(this).index('.pilih');
                    _selectTindakanRow(_index);
        })
 
 
        
 
function _getData_tindakan_alkes(id, $targetTbody){
    if(id=='' || id==null) return;
    if(!$targetTbody || $targetTbody.length==0) return;

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

            $('#nodepo').val(result.data[0]['sunotransaksi']);
            $('#tgldepo').val(result.data[0]['sutanggal']);
            $('#id').val(result.data[0]['id']);



        $.each(result.data, function() {
          _addRow_alkes($targetTbody);
          _inputFormat();

        // alert(result.data[0]['produk_alkes']);
          $targetTbody.find("input[name^='produk_alkes']").eq(rows).val(result.data[rows]['inama']);
          $targetTbody.find("input[name^='idproduk_alkes']").eq(rows).val(result.data[rows]['iid']);
          $targetTbody.find("input[name^='satuan_alkes']").eq(rows).val(result.data[rows]['skode']);
          $targetTbody.find("input[name^='idsatuan_alkes']").eq(rows).val(result.data[rows]['sdsatuan']);
          $targetTbody.find("input[name^='qty_alkes']").eq(rows).val(result.data[rows]['sdkeluar'].replace(".", ","));
          $targetTbody.find("input[name^='qtystandar_alkes']").eq(rows).val(result.data[rows]['sdqtydasar'].replace(".", ","));


          //atur placeholder numeric jika 0
          if(result.data[rows]['sdkeluar']==0) $targetTbody.find("input[name^='qty_alkes']").eq(rows).attr('placeholder','0,00');
          if(result.data[rows]['sdqtydasar']==0) $targetTbody.find("input[name^='qtystandar_alkes']").eq(rows).attr('placeholder','0,00');

          rows++;
        });
         

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
} 
 
function _getData_alkes(id, $targetTbody){

    if(id=='' || id==null) return;
    if(!$targetTbody || $targetTbody.length==0) return;

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
        } else if (result.data.length==0 || result.data[0]['produk_alkes'] == '') { // Jika kosong
          toastr.error('Tidak ada data penyusun');
          $('.loader-wrap').addClass('d-none');  
          return;
        } else { // Jika tidak ada pesan tampilkan json ke form 
        
        var rows = 0 ;

         $('#nodepo').val('');

        $.each(result.data, function() {
          _addRow_alkes($targetTbody);
          _inputFormat();

        // alert(result.data[0]['produk_alkes']);
          $targetTbody.find("input[name^='produk_alkes']").eq(rows).val(result.data[rows]['produk_alkes']);
          $targetTbody.find("input[name^='idproduk_alkes']").eq(rows).val(result.data[rows]['idproduk_alkes']);
          $targetTbody.find("input[name^='satuan_alkes']").eq(rows).val(result.data[rows]['satuan_alkes']);
          $targetTbody.find("input[name^='idsatuan_alkes']").eq(rows).val(result.data[rows]['idsatuan_alkes']);
          $targetTbody.find("input[name^='qty_alkes']").eq(rows).val(result.data[rows]['qtystandar_alkes'].replace(".", ","));
          $targetTbody.find("input[name^='qtystandar_alkes']").eq(rows).val(result.data[rows]['qtystandar_alkes'].replace(".", ","));


          //atur placeholder numeric jika 0
          if(result.data[rows]['qtystandar_alkes']==0) $targetTbody.find("input[name^='qty_alkes']").eq(rows).attr('placeholder','0,00');
          if(result.data[rows]['qtystandar_alkes']==0) $targetTbody.find("input[name^='qtystandar_alkes']").eq(rows).attr('placeholder','0,00');

          rows++;
        });
         

          /**/
          $('.loader-wrap').addClass('d-none');                                       
          return;
        }
    } 
  })
}


function _getData(id, selectAlkesId){
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
          $('#idcabang').val(result.data[0]['cabangid']);
          $('#idkontak').val(result.data[0]['kontak']);
          $('#idsupos').val(result.data[0]['idu']);
          
          
          
          
        var rows = 0 ;
        
        $('#tdatatindakan > tbody').html('');

        $.each(result.data, function() {
          _addRow();


          $("input[name^='tindakan']").eq(rows).val(result.data[rows]['tindakan']);
          $("input[name^='idtindakan']").eq(rows).val(result.data[rows]['idtindakan']);
          $("input[name^='qty']").eq(rows).val(result.data[rows]['qty'].replace(".", ","));
          $("input[name^='subtotal']").eq(rows).val(result.data[rows]['subtotal'].replace(".", ","));
          $("input[name^='noref']").eq(rows).val(result.data[rows]['noref']);
          $("input[name='sdid[]']").eq(rows).val(result.data[rows]['sdid']);
          $("input[name^='sdidalkesnya']").eq(rows).val(result.data[rows]['idalkesnya']);



          //atur placeholder numeric jika 0
          if(result.data[rows]['qty']==0) $("input[name^='qty']").eq(rows).attr('placeholder','0,00');
          if(result.data[rows]['subtotal']==0) $("input[name^='subtotal']").eq(rows).attr('placeholder','0,00');

          rows++;
        });

        _inputFormat();
        $('.datepicker').datepicker('setDate','dd-mm-yy');

        if (selectAlkesId!==undefined && selectAlkesId!==null && selectAlkesId!==''){
          var _targetIndex = -1;
          $("input[name^='sdidalkesnya']").each(function(index,element){
            if(String($(this).val())===String(selectAlkesId)) _targetIndex = index;
          });
          if(_targetIndex>-1){
            $('#id').val(selectAlkesId);
            $('tr.row-tindakan').each(function(index,element){
              if(index!==_targetIndex){
                $(this).addClass('d-none');
                $(this).next('.row-alkes-detail').addClass('d-none');
              }
            });
            _selectTindakanRow(_targetIndex);
            $(".pilih").eq(_targetIndex).prop("disabled", true);
          }
        }

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
          $('#idcabang').val(result.data[0]['cabangid']);
          $('#idkontak').val(result.data[0]['kontak']);
          $('#idsupos').val(result.data[0]['idu']);
          
          
          
          
        var rows = 0 ;
        
        $('#tdatatindakan > tbody').html('');

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