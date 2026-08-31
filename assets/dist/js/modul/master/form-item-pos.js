var _inputFormatPos = () => {
  $('.qty').inputmask({
    alias:'numeric',
    digits:$("#decqty").val(),
    digitsOptional:false,
    isNumeric: true,
    prefix:'',
    groupSeparator:".",
    placeholder: '0',
    radixPoint:",",
    autoGroup:true,
    autoUnmask:true,
    onBeforeMask: function (value, opts) {
      return value;
    },
    removeMaskOnSubmit:false
  });

  $('.numeric').inputmask({
    alias:'numeric',
    digits:'2',
    digitsOptional:false,
    isNumeric: true,
    prefix:'',
    groupSeparator:".",
    placeholder: '0',
    radixPoint:",",
    autoGroup:true,
    autoUnmask:true,
    onBeforeMask: function (value, opts) {
      return value;
    },
    removeMaskOnSubmit:false
  });
}

$('#kategori').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "minimumResultsForSearch": "Infinity"
});

$('#status').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "minimumResultsForSearch": "Infinity"
});

$('#satuandasar').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "ajax": {
        "url": base_url+"Select_Master/view_satuan",
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

$('#satuandefault').select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "ajax": {
        "url": base_url+"Select_Master/view_satuan",
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

$('#tipepersediaan').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "minimumResultsForSearch": "Infinity"
});

$('#model').select2({
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "minimumResultsForSearch": "Infinity"
});

var _select2AjaxPos = function(id, url){
  $(id).select2({
     "allowClear": true,
     "theme":"bootstrap4",
     "dropdownParent": $('#tabItemPos'),
     "ajax": {
        "url": base_url+url,
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
};

_select2AjaxPos('#jenisitem', 'Select_Master/view_itemjenis');
_select2AjaxPos('#jenisitemcoa', 'Select_Master/view_coa_tipe_pendapatan');
_select2AjaxPos('#jeniscoapendapatan', 'Select_Master/view_coa_tipe_pendapatan');
_select2AjaxPos('#kelompokbaru', 'Select_Master/view_itemjenis');
_select2AjaxPos('#kelompok2020', 'Select_Master/view_kategori_itemkelompok2020');
_select2AjaxPos('#kelompok21', 'Select_Master/view_itemkelompok2021');
_select2AjaxPos('#kelompok23', 'Select_Master/view_itemkelompok2023');
_select2AjaxPos('#coa2021', 'Select_Master/view_coa_tipe_perpt');
_select2AjaxPos('#komisi2020', 'Select_Master/view_kategori_itemkelompok2020');
_select2AjaxPos('#jenisweb', 'Select_Master/view_itemjenisweb');

$("#submit").click(function(){
  if (_IsValidPos()===0) return;
  _saveDataPos();
});

var _IsValidPos = function(){
    if ($('#kode').val()==''){
      $('#kode').attr('data-title','Kode item harus diisi !');
      $('#kode').tooltip('show');
      $('#kode').focus();
      return 0;
    }

    if ($('#nama').val()==''){
      $('#nama').attr('data-title','Nama item harus diisi !');
      $('#nama').tooltip('show');
      $('#nama').focus();
      return 0;
    }

    if ($('#satuandasar').val()=='' || $('#satuandasar').val()==null){
        toastr.error('Pesan : Satuan dasar harus diisi !');
        return 0;
    }

    if ($('#satuandefault').val()=='' || $('#satuandefault').val()==null){
        toastr.error('Pesan : Satuan default harus diisi !');
        return 0;
    }

    return 1;
};

var _saveDataPos = function(){
  const id = $("#id").val(),
        kode = $("#kode").val(),
        nama = $("#nama").val(),
        namaweb = $("#namaweb").val(),
        kategori = $("#kategori").val(),
        status = $("#status").val(),
        serial = $("#serial").prop('checked') ? 1 : 0,
        satuand = $("#satuandasar").val(),
        satuan = $("#satuandefault").val(),
        qtyperbox = Number($("#qtyperbox").val().split('.').join('').toString().replace(',','.')),
        stokmaks = Number($("#stokmaks").val().split('.').join('').toString().replace(',','.')),
        stokmin = Number($("#stokmin").val().split('.').join('').toString().replace(',','.')),
        stokreorder = Number($("#stokreorder").val().split('.').join('').toString().replace(',','.')),
        maxorder = Number($("#maxorder").val().split('.').join('').toString().replace(',','.')),
        hargajual1 = Number($("#hargajual1").val().split('.').join('').toString().replace(',','.')),
        hargajual2 = Number($("#hargajual2").val().split('.').join('').toString().replace(',','.')),
        hargabeli = Number($("#hargabeli").val().split('.').join('').toString().replace(',','.')),
        hargakaryawan = Number($("#hargakaryawan").val().split('.').join('').toString().replace(',','.')),
        hargadepo = Number($("#hargadepo").val().split('.').join('').toString().replace(',','.')),
        hargaweb = Number($("#hargaweb").val().split('.').join('').toString().replace(',','.')),
        hargaweb2 = Number($("#hargaweb2").val().split('.').join('').toString().replace(',','.')),
        hargadifaktur = Number($("#hargadifaktur").val().split('.').join('').toString().replace(',','.')),
        hargaproduk = Number($("#hargaproduk").val().split('.').join('').toString().replace(',','.')),
        hargaalkes = Number($("#hargaalkes").val().split('.').join('').toString().replace(',','.')),
        diskon = Number($("#diskon").val().split('.').join('').toString().replace(',','.')),
        cogs = Number($("#cogs").val().split('.').join('').toString().replace(',','.')),
        cogspo = Number($("#cogspo").val().split('.').join('').toString().replace(',','.')),
        cogsrii = Number($("#cogsrii").val().split('.').join('').toString().replace(',','.')),
        jenisitem = $("#jenisitem").val(),
        tipepersediaan = $("#tipepersediaan").val(),
        jenisitemcoa = $("#jenisitemcoa").val(),
        jeniscoapendapatan = $("#jeniscoapendapatan").val(),
        kelompokbaru = $("#kelompokbaru").val(),
        kelompok2020 = $("#kelompok2020").val(),
        kelompok21 = $("#kelompok21").val(),
        kelompok23 = $("#kelompok23").val(),
        coa2021 = $("#coa2021").val(),
        komisi2020 = $("#komisi2020").val(),
        jenisweb = $("#jenisweb").val(),
        model = $("#model").val(),
        tidakdihitungjumlahpasien = $("#tidakdihitungjumlahpasien").prop('checked') ? 1 : 0,
        komisimarketing = $("#komisimarketing").prop('checked') ? 1 : 0,
        komisipaket = $("#komisipaket").prop('checked') ? 1 : 0,
        bisasharing = $("#bisasharing").prop('checked') ? 1 : 0,
        cetak = $("#cetak").prop('checked') ? 1 : 0,
        komisidokter = $("#komisidokter").prop('checked') ? 1 : 0,
        promo = $("#promo").prop('checked') ? 1 : 0,
        hargablank = $("#hargablank").prop('checked') ? 1 : 0,
        paketsemuacabang = $("#paketsemuacabang").prop('checked') ? 1 : 0,
        pasienbarusaja = $("#pasienbarusaja").prop('checked') ? 1 : 0,
        tindakanproduk = $("#tindakanproduk").prop('checked') ? 1 : 0,
        bhp = $("#bhp").prop('checked') ? 1 : 0,
        tidaktampildimedlib = $("#tidaktampildimedlib").prop('checked') ? 1 : 0,
        resep = $("#resep").prop('checked') ? 1 : 0,
        persentasekomisi = Number($("#persentasekomisi").val().split('.').join('').toString().replace(',','.')),
        nilaikomisi = Number($("#nilaikomisi").val().split('.').join('').toString().replace(',','.')),
        nilaikomisiperqty = Number($("#nilaikomisiperqty").val().split('.').join('').toString().replace(',','.')),
        minimalqty = Number($("#minimalqty").val().split('.').join('').toString().replace(',','.')),
        berat = Number($("#berat").val().split('.').join('').toString().replace(',','.')),
        hargapo = Number($("#hargapo").val().split('.').join('').toString().replace(',','.')),
        qtypo = Number($("#qtypo").val().split('.').join('').toString().replace(',','.')),
        kemasan = $("#kemasan").val(),
        coding = $("#coding").val(),
        namapo = $("#namapo").val(),
        dapsfeedokter = Number($("#dapsfeedokter").val().split('.').join('').toString().replace(',','.')),
        dapsfeeperawat = Number($("#dapsfeeperawat").val().split('.').join('').toString().replace(',','.')),
        dapsalkes = Number($("#dapsalkes").val().split('.').join('').toString().replace(',','.')),
        dapsequipment = Number($("#dapsequipment").val().split('.').join('').toString().replace(',','.')),
        dapsfacility = Number($("#dapsfacility").val().split('.').join('').toString().replace(',','.')),
        dapsjasaklinik = Number($("#dapsjasaklinik").val().split('.').join('').toString().replace(',','.')),
        dapssalescomm = Number($("#dapssalescomm").val().split('.').join('').toString().replace(',','.'));

  var cabang = [];
  $("#tcabang input[name^='iscabang']:checked").each(function(){
    cabang.push($(this).val());
  });
  cabang = cabang.length > 0 ? '|'+cabang.join('|')+'|' : '';

  var rey = new FormData();
  rey.set('id',id);
  rey.set('kode',kode);
  rey.set('nama',nama);
  rey.set('namaweb',namaweb);
  rey.set('kategori',kategori);
  rey.set('status',status);
  rey.set('serial',serial);
  rey.set('satuand',satuand);
  rey.set('satuan',satuan);
  rey.set('qtyperbox',qtyperbox);
  rey.set('stokmaks',stokmaks);
  rey.set('stokmin',stokmin);
  rey.set('stokreorder',stokreorder);
  rey.set('maxorder',maxorder);
  rey.set('hargajual1',hargajual1);
  rey.set('hargajual2',hargajual2);
  rey.set('hargabeli',hargabeli);
  rey.set('hargakaryawan',hargakaryawan);
  rey.set('hargadepo',hargadepo);
  rey.set('hargaweb',hargaweb);
  rey.set('hargaweb2',hargaweb2);
  rey.set('hargadifaktur',hargadifaktur);
  rey.set('hargaproduk',hargaproduk);
  rey.set('hargaalkes',hargaalkes);
  rey.set('diskon',diskon);
  rey.set('cogs',cogs);
  rey.set('cogspo',cogspo);
  rey.set('cogsrii',cogsrii);
  rey.set('jenisitem',jenisitem);
  rey.set('tipepersediaan',tipepersediaan);
  rey.set('jenisitemcoa',jenisitemcoa);
  rey.set('jeniscoapendapatan',jeniscoapendapatan);
  rey.set('kelompokbaru',kelompokbaru);
  rey.set('kelompok2020',kelompok2020);
  rey.set('kelompok21',kelompok21);
  rey.set('kelompok23',kelompok23);
  rey.set('coa2021',coa2021);
  rey.set('komisi2020',komisi2020);
  rey.set('jenisweb',jenisweb);
  rey.set('model',model);
  rey.set('tidakdihitungjumlahpasien',tidakdihitungjumlahpasien);
  rey.set('komisimarketing',komisimarketing);
  rey.set('komisipaket',komisipaket);
  rey.set('bisasharing',bisasharing);
  rey.set('cetak',cetak);
  rey.set('komisidokter',komisidokter);
  rey.set('promo',promo);
  rey.set('hargablank',hargablank);
  rey.set('paketsemuacabang',paketsemuacabang);
  rey.set('pasienbarusaja',pasienbarusaja);
  rey.set('tindakanproduk',tindakanproduk);
  rey.set('bhp',bhp);
  rey.set('tidaktampildimedlib',tidaktampildimedlib);
  rey.set('resep',resep);
  rey.set('persentasekomisi',persentasekomisi);
  rey.set('nilaikomisi',nilaikomisi);
  rey.set('nilaikomisiperqty',nilaikomisiperqty);
  rey.set('minimalqty',minimalqty);
  rey.set('berat',berat);
  rey.set('hargapo',hargapo);
  rey.set('qtypo',qtypo);
  rey.set('kemasan',kemasan);
  rey.set('coding',coding);
  rey.set('namapo',namapo);
  rey.set('dapsfeedokter',dapsfeedokter);
  rey.set('dapsfeeperawat',dapsfeeperawat);
  rey.set('dapsalkes',dapsalkes);
  rey.set('dapsequipment',dapsequipment);
  rey.set('dapsfacility',dapsfacility);
  rey.set('dapsjasaklinik',dapsjasaklinik);
  rey.set('dapssalescomm',dapssalescomm);
  rey.set('cabang',cabang);

  $.ajax({
    "url"    : base_url+"Master_Item_POS/savedata",
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
      toastr.error("Error : "+xhr.status+" "+error);
      console.log(xhr.responseText);
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");

      if(result=='sukses'){
        $('#modal').modal('hide');
        toastr.success("Data item POS berhasil disimpan");
        return;
      } else {
        toastr.error(result);
        return;
      }
    }
  })
};

function _getData(id){
    if(id=='' || id==null) return;

    $.ajax({
      "url"    : base_url+"Master_Item_POS/getdata",
      "type"   : "POST",
      "dataType" : "json",
      "data" : "id="+id,
      "cache"  : false,
      "beforeSend" : function(){
        $('.loader-wrap').removeClass('d-none');
      },
      "error"  : function(xhr,status,error){
        $(".main-modal-body").html('');
        toastr.error("Error : "+xhr.status+" "+error);
        console.error(xhr.responseText);
        $('.loader-wrap').addClass('d-none');
        return;
      },
      "success" : function(result) {
        if (typeof result.pesan !== 'undefined') {
          toastr.error(result.pesan);
          $('.loader-wrap').addClass('d-none');
          return;
        } else {
          const _satuand = $("<option selected='selected'></option>").val(result.data[0]['idsatuand']).text(result.data[0]['satuand']),
                _satuan = $("<option selected='selected'></option>").val(result.data[0]['idsatuan']).text(result.data[0]['satuan']),
                _jenisitem = $("<option selected='selected'></option>").val(result.data[0]['idjenisitem']).text(result.data[0]['jenisitem']),
                _jenisitemcoa = $("<option selected='selected'></option>").val(result.data[0]['idjenisitemcoa']).text(result.data[0]['jenisitemcoa']),
                _jeniscoapendapatan = $("<option selected='selected'></option>").val(result.data[0]['idjeniscoapendapatan']).text(result.data[0]['jeniscoapendapatan']),
                _kelompokbaru = $("<option selected='selected'></option>").val(result.data[0]['idkelompokbaru']).text(result.data[0]['kelompokbaru']),
                _kelompok2020 = $("<option selected='selected'></option>").val(result.data[0]['idkelompok2020']).text(result.data[0]['kelompok2020']),
                _kelompok21 = $("<option selected='selected'></option>").val(result.data[0]['idkelompok21']).text(result.data[0]['kelompok21']),
                _kelompok23 = $("<option selected='selected'></option>").val(result.data[0]['idkelompok23']).text(result.data[0]['kelompok23']),
                _coa2021 = $("<option selected='selected'></option>").val(result.data[0]['idcoa2021']).text(result.data[0]['coa2021']),
                _komisi2020 = $("<option selected='selected'></option>").val(result.data[0]['idkomisi2020']).text(result.data[0]['komisi2020']),
                _jenisweb = $("<option selected='selected'></option>").val(result.data[0]['idjenisweb']).text(result.data[0]['jenisweb']);

          $('#id').val(result.data[0]['id']);
          $('#kode').val(result.data[0]['kode']);
          $('#nama').val(result.data[0]['nama']);
          $('#namaweb').val(result.data[0]['namaweb']);
          if(result.data[0]['satuand']!==null) $('#satuandasar').append(_satuand);
          if(result.data[0]['satuan']!==null) $('#satuandefault').append(_satuan);
          if(result.data[0]['jenisitem']!==null) $('#jenisitem').append(_jenisitem);
          if(result.data[0]['jenisitemcoa']!==null) $('#jenisitemcoa').append(_jenisitemcoa);
          if(result.data[0]['jeniscoapendapatan']!==null) $('#jeniscoapendapatan').append(_jeniscoapendapatan);
          if(result.data[0]['kelompokbaru']!==null) $('#kelompokbaru').append(_kelompokbaru);
          if(result.data[0]['kelompok2020']!==null) $('#kelompok2020').append(_kelompok2020);
          if(result.data[0]['kelompok21']!==null) $('#kelompok21').append(_kelompok21);
          if(result.data[0]['kelompok23']!==null) $('#kelompok23').append(_kelompok23);
          if(result.data[0]['coa2021']!==null) $('#coa2021').append(_coa2021);
          if(result.data[0]['komisi2020']!==null) $('#komisi2020').append(_komisi2020);
          if(result.data[0]['jenisweb']!==null) $('#jenisweb').append(_jenisweb);
          $('#kategori').val(result.data[0]['kategori']).trigger('change');
          $('#status').val(result.data[0]['status']).trigger('change');
          $('#serial').prop('checked', result.data[0]['serial']==1);
          $('#qtyperbox').val(String(result.data[0]['qtyperbox']).replace(".", ","));
          $('#stokmaks').val(String(result.data[0]['stokmaks']).replace(".", ","));
          $('#stokmin').val(String(result.data[0]['stokmin']).replace(".", ","));
          $('#stokreorder').val(String(result.data[0]['stokreorder']).replace(".", ","));
          $('#maxorder').val(String(result.data[0]['maxorder']).replace(".", ","));
          $('#hargajual1').val(String(result.data[0]['hargajual1']).replace(".", ","));
          $('#hargajual2').val(String(result.data[0]['hargajual2']).replace(".", ","));
          $('#hargabeli').val(String(result.data[0]['hargabeli']).replace(".", ","));
          $('#hargakaryawan').val(String(result.data[0]['hargakaryawan']).replace(".", ","));
          $('#hargadepo').val(String(result.data[0]['hargadepo']).replace(".", ","));
          $('#hargaweb').val(String(result.data[0]['hargaweb']).replace(".", ","));
          $('#hargaweb2').val(String(result.data[0]['hargaweb2']).replace(".", ","));
          $('#hargadifaktur').val(String(result.data[0]['hargadifaktur']).replace(".", ","));
          $('#hargaproduk').val(String(result.data[0]['hargaproduk']).replace(".", ","));
          $('#hargaalkes').val(String(result.data[0]['hargaalkes']).replace(".", ","));
          $('#diskon').val(String(result.data[0]['diskon']).replace(".", ","));
          $('#cogs').val(String(result.data[0]['cogs']).replace(".", ","));
          $('#cogspo').val(String(result.data[0]['cogspo']).replace(".", ","));
          $('#cogsrii').val(String(result.data[0]['cogsrii']).replace(".", ","));
          $('#tipepersediaan').val(result.data[0]['tipepersediaan']).trigger('change');
          $('#model').val(result.data[0]['model']).trigger('change');
          $('#tidakdihitungjumlahpasien').prop('checked', result.data[0]['tidakdihitungjumlahpasien']==1);
          $('#komisimarketing').prop('checked', result.data[0]['komisimarketing']==1);
          $('#komisipaket').prop('checked', result.data[0]['komisipaket']==1);
          $('#bisasharing').prop('checked', result.data[0]['bisasharing']==1);
          $('#cetak').prop('checked', result.data[0]['cetak']==1);
          $('#komisidokter').prop('checked', result.data[0]['komisidokter']==1);
          $('#promo').prop('checked', result.data[0]['promo']==1);
          $('#hargablank').prop('checked', result.data[0]['hargablank']==1);
          $('#paketsemuacabang').prop('checked', result.data[0]['paketsemuacabang']==1);
          $('#pasienbarusaja').prop('checked', result.data[0]['pasienbarusaja']==1);
          $('#tindakanproduk').prop('checked', result.data[0]['tindakanproduk']==1);
          $('#bhp').prop('checked', result.data[0]['bhp']==1);
          $('#tidaktampildimedlib').prop('checked', result.data[0]['tidaktampildimedlib']==1);
          $('#resep').prop('checked', result.data[0]['resep']==1);
          $('#persentasekomisi').val(String(result.data[0]['persentasekomisi']).replace(".", ","));
          $('#nilaikomisi').val(String(result.data[0]['nilaikomisi']).replace(".", ","));
          $('#nilaikomisiperqty').val(String(result.data[0]['nilaikomisiperqty']).replace(".", ","));
          $('#minimalqty').val(String(result.data[0]['minimalqty']).replace(".", ","));
          $('#berat').val(String(result.data[0]['berat']).replace(".", ","));
          $('#hargapo').val(String(result.data[0]['hargapo']).replace(".", ","));
          $('#qtypo').val(String(result.data[0]['qtypo']).replace(".", ","));
          $('#kemasan').val(result.data[0]['kemasan']);
          $('#coding').val(result.data[0]['coding']);
          $('#namapo').val(result.data[0]['namapo']);
          $('#dapsfeedokter').val(String(result.data[0]['dapsfeedokter']).replace(".", ","));
          $('#dapsfeeperawat').val(String(result.data[0]['dapsfeeperawat']).replace(".", ","));
          $('#dapsalkes').val(String(result.data[0]['dapsalkes']).replace(".", ","));
          $('#dapsequipment').val(String(result.data[0]['dapsequipment']).replace(".", ","));
          $('#dapsfacility').val(String(result.data[0]['dapsfacility']).replace(".", ","));
          $('#dapsjasaklinik').val(String(result.data[0]['dapsjasaklinik']).replace(".", ","));
          $('#dapssalescomm').val(String(result.data[0]['dapssalescomm']).replace(".", ","));

          $('.loader-wrap').addClass('d-none');
          return;
        }
    }
  })
}

function _getDataCabang(id){
    $.ajax({
      "url"    : base_url+"Master_Item_POS/getcabang",
      "type"   : "POST",
      "dataType" : "json",
      "data" : "id="+(id || ''),
      "cache"  : false,
      "success" : function(result) {
          $('#tcabang tbody').empty();
          var rows = 0;
          var allChecked = result.data.length > 0 && result.data.every(function(item){ return item['dipilih']==1; });
          $('#tcabang tbody').append("<tr class=\"bg-light\"><td class=\"border-0 py-1 px-1\"></td><td class=\"border-0 py-1\" colspan=\"2\"><strong>Pilih Semua</strong></td><td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" id=\"pilihsemuacabang\""+(allChecked ? " checked" : "")+"></td></tr>");
          $.each(result.data, function() {
              var newrow = " <tr>";
              newrow += "<td class=\"border-0 py-1 px-1\"><i class=\"fas fa-caret-right\"></i></td>";
              newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['gkode']+"</td>";
              newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['gnama']+"</td>";

              if(result.data[rows]['dipilih'] == 1){
                  newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"iscabang\" value=\""+result.data[rows]['gid']+"\" checked></td>";
              }else{
                  newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"iscabang\" value=\""+result.data[rows]['gid']+"\"></td>";
              }

              newrow += "</tr>";
              $('#tcabang tbody').append(newrow);
              rows++;
          });

          return;
      }
    })
}

$(document).on("click","#pilihsemuacabang", function(e){
  var isChecked = $(this).prop("checked");
  $("#tcabang tbody input[name='iscabang']").prop("checked", isChecked);
})

_inputFormatPos();

setTimeout(function (){
        $('#kode').focus();
}, 500);
