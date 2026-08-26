/* ========================================================================================== */
/* File Name : kas-masuk.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Inputmask_Numeric } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';
import { Component_Select2_Account } from '../../component.js';

$(function() {

  const qparam = new URLSearchParams(this.location.search);  

  setupHiddenInputChangeListener($('#id')[0]);
  setupHiddenInputChangeListener($('#notrans')[0]);  

  Component_Inputmask_Date('.datepicker');
  Component_Scrollbars('.tab-wrap','hidden','scroll');
  Component_Select2_Account(
    `#coadebet`,
    `${base_url}Select_Master/view_coa_kas`,
    `form_coa`,
    `Akun`
  );

  if(!parent.window.$(".loader-wrap").hasClass("d-none")){
      parent.window.$(".loader-wrap").addClass("d-none");
  }

/* ========================================================================================== */

  this.addEventListener('contextmenu', (e) => {
      e.preventDefault();
  });

  $('#kontak').keydown((e) => {
      if(e.keyCode==13) $('#carikontak').click();
  });

  $(this).on('select2:open', () => {
      this.querySelector('.select2-search__field').focus();
  });

  $("#dTgl").click(() => {
      if($('#dTgl').attr('role')) {
          $("#tgl").focus();
      }
  });

  $("#bTable").click(() => {
      parent.window.$('.loader-wrap').removeClass('d-none');
      location.href=base_url+"page/bkmData";      
  });

  $("#bViewJurnal").click(() => {

      if($("#id").val()=="") return;

      $.ajax({ 
        "url"    : base_url+"Modal/lihat_jurnal", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": () => {
            parent.window.$('.loader-wrap').removeClass('d-none');
            parent.window.$(".modal").modal("show");            
            parent.window.$(".modal-title").html("Jurnal "+$("#nomor").val());
            parent.window.$("#modaltrigger").val("iframe-page-bkm");
            parent.window.$('#coltrigger').val('');                
        },        
        "error": () => {
            parent.window.$('.loader-wrap').addClass('d-none');      
            console.log('error menampilkan modal jurnal...');
            return;
        },
        "success": (result) => {
            parent.window.$(".main-modal-body").html(result);
            parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
            parent.window._transaksidatatable($("#nomor").val());
            return;
        } 
      })
  });  

  $("#carikontak").click(() => {
      if($('#carikontak').attr('role')) { 
          $.ajax({ 
              "url"    : base_url+"Modal/cari_kontak", 
              "type"   : "POST", 
              "dataType" : "html", 
              "beforeSend": () => {
                  parent.window.$(".loader-wrap").removeClass("d-none");          
                  parent.window.$(".modal").modal("show");
                  parent.window.$(".modal-title").html("Cari Kontak");
                  parent.window.$("#modaltrigger").val("iframe-page-bkm");
                  parent.window.$('#coltrigger').val('vendor');                
              },        
              "error": () => {
                  parent.window.$(".loader-wrap").addClass("d-none");                    
                  console.error('error menampilkan modal cari kontak...');
                  return;
                },
              "success": (result) => {
                  parent.window.$(".main-modal-body").html(result);
                  parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
                  parent.window._lstkategorikontak();
                  parent.window._kontakdatatable();
                  setTimeout(function (){
                       parent.window.$('#modal input').focus();
                  }, 500);                    
                  return;
              } 
          });
      }    
  });

  $("#badd").click(() => {
      _clearForm();
      _addRow();      
      _inputFormat();         
      _formState1();
  });

  $("#bedit").click(() => {
      if($('#id').val()=='') return;

      if($('#status').val()=='1'){       
          parent.window.toastr.info("Transaksi sudah di posting, tidak bisa diedit !");      
      } else {
          _formState1();
      }
  });  

  $("#bdelete").click(() => {
      if($('#id').val()=='') return;

      if($('#status').val()=='1'){       
          parent.window.toastr.info("Transaksi sudah di posting, tidak bisa dihapus !");      
      } else {
          const nomor = $("#nomor").val();
    
          parent.window.Swal.fire({
              title: `Anda yakin akan menghapus ${nomor} ?`,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Iya`,
          }).then((result) => {
              if (result.isConfirmed) {
                  _deleteData();      
              }
          })
      }
  });

  $("#bsearch").click(() => {                      
      $.ajax({ 
          "url"    : base_url+"Modal/cari_transaksi", 
          "type"   : "POST", 
          "dataType" : "html",
          "beforeSend": () => {
              parent.window.$(".loader-wrap").removeClass("d-none");                  
              parent.window.$(".modal").modal("show");
              parent.window.$(".modal-title").html("Cari Transaksi");
              parent.window.$("#modaltrigger").val("iframe-page-bkm"); 
              parent.window.$('#coltrigger').val('transaksi');        
          },       
          "error": () => {
              parent.window.$(".loader-wrap").addClass("d-none");          
              console.error('error menampilkan modal cari transaksi...');
              return;
          },
          "success": (result) => {                               
              parent.window.$(".main-modal-body").html(result);    
              parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
              parent.window._transaksidatatable('view_kas_masuk');
              setTimeout(function (){
                   parent.window.$('#modal input').focus();
              }, 500);
              return;
          } 
      })   
  });

  $("#baddrow").click(() => {
      _addRow();
      _inputFormat();
      $("select[name^='coakredit']").last().focus();    
  });

  $("#bcancel").click(() => {
      _clearForm();
      _addRow();
      _inputFormat();      
      _formState2();
  });

  $("#bprint").click(() => {
      if($('#id').val()=='') return;    
      window.open(`${base_url}Laporan/preview/page-bkm/${$("#id").val()}`)
  });

  $("#bsave").click(() => {  
      if (_IsValid()===0) return;
      _saveData();
  });

  $('#id').on('change', function(e){
      var idtrans = $(this).val();
      _formState2();
      _getDataTransaksi(idtrans);
  });  

  $('#notrans').on('change',function(){
    var notrans = $(this).val();
    _formState2();
    _getDataTransaksiNomor(notrans);
  });    

  $(this).on("keyup", "input[name^='amtkredit']", async function(e) {
      let _idx = $(this).index('.amtkredit');
      let jumlah = await _hitungJumlahDetil(_idx);
      _hitungsubtotal();
      return;
  });

  $(this).on("select2:select", "select[name^='coakredit']", function(){

      if($(this).val()=="" || $(this).val()==null) return;

      let _idx = $(this).index('.coakredit');

      $.ajax({ 
          "url"    : base_url+"Fina_Kas_Masuk/get_coa", 
          "type"   : "POST", 
          "data"   : "id="+$(this).val(),
          "dataType" : "json", 
          "cache"  : false,
          "beforeSend" : () => {
              $("#loader-detil").removeClass('d-none');
          },        
          "error"  : () => {
              console.error('error mengambil informasi coa...');
              $("#loader-detil").addClass('d-none');          
              return;
          },
          "success"  : async (result) => {
              let jumlah = await _hitungJumlahDetil(_idx);
              _hitungsubtotal();
              $("#loader-detil").addClass('d-none');    
              return;                    
          } 
      });
  });

  $(this).on('shown.bs.tooltip', (e) => {
      setTimeout(function () {
        $(e.target).tooltip('hide');
      }, 2000);
  });  

/**/

/* ========================================================================================== */

  var _clearForm = () => {
      $(":input").not(":button, :submit, :reset, :checkbox, :radio, .noclear").val('');
      $(":checkbox").prop("checked", false); 
      $('.select2').val('').change(); 
      $('#namakontak').html('');       
      $('#tdetil tbody').html('');                                    
      $('#tgl').datepicker('setDate','dd-mm-yy'); 
      $('#iduangdebet').val('1');
      $('#uangdebet').val('Rp');
      $('#kursdebet').val('1');
      $('#totalrp').val('0');
      $('#totalvalas').val('0');    
  }

  var _addRow = () => {
      let newrow = " <tr>";
          newrow += "<td><select name=\"coakredit[]\" class=\"coakredit form-control select2 form-control-sm\" style=\"width:100%;\" data-trigger=\"manual\" data-placement=\"auto\"></select></td>";
          newrow += "<td><input type=\"hidden\" name=\"idmuangkredit[]\" class=\"idmuangkredit\"><div class=\"input-group\"><div class=\"input-group-append\"><div class=\"muangkredit input-group-text bg-white border-right-0 py-0 px-2\">Rp</div></div><input type=\"tel\" name=\"amtkredit[]\" class=\"amtkredit form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\"></div></td>";
          newrow += "<td><input type=\"text\" name=\"kurs[]\" class=\"kurs form-control form-control-sm numeric\" autocomplete=\"off\" value=\"1\" readonly></td>";
          newrow += "<td class=\"d-none\"><input type=\"text\" name=\"valas[]\" class=\"valas form-control form-control-sm numeric\" autocomplete=\"off\" value=\"0\" readonly></td>";
          if($("#multidivisi").val()==1){
            newrow += "<td><select name=\"divisi[]\" class=\"divisi form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";            
          } else {
            newrow += "<td class=\"d-none\"><select name=\"divisi[]\" class=\"divisi form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>";                        
          }
          if($("#multiproyek").val()==1){
            newrow += "<td><select name=\"proyek[]\" class=\"proyek form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>"; 
          } else {
            newrow += "<td class=\"d-none\"><select name=\"proyek[]\" class=\"proyek form-control select2 form-control-sm\" style=\"width:100%;\"></select></td>"; 
          }          
          newrow += "<td><textarea name=\"catatan[]\" class=\"catatan form-control form-control-sm\" rows=\"1\" autocomplete=\"off\"></textarea></td>";
          newrow += "<td><a href=\"javascript:void(0)\" class=\"btn btn-step1 btn-delrow\" onclick=\"_hapusbaris($(this));\" tabindex=\"-1\"><i class=\"fa fa-minus text-primary\"></i></a></td>";
          newrow += "</tr>";
      $('#tdetil tbody').append(newrow);
  }

  var _inputFormat = () => {
      Component_Inputmask_Numeric('.numeric');
      Component_Select2_Account('.coakredit',`${base_url}Select_Master/view_coa`,'form_coa','Akun');
      Component_Select2('.divisi',`${base_url}Select_Master/view_divisi_kode`,'form_divisi','Divisi');
      Component_Select2('.proyek',`${base_url}Select_Master/view_proyek_kode`,'form_proyek','Proyek');
  }

  var _formState1 = () => {
      $('#carikontak').attr('data-dismiss','modal');
      $('#carikontak').attr('data-toggle','modal');
      $('#carikontak').attr('role','button');  
      $('.btn-step2').addClass('disabled');
      $('.btn-step1').removeClass('disabled');
      $('#baddrow').removeAttr('disabled');     
      $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").removeAttr('disabled');

      setTimeout(function () {
        $('#kontak').focus();        
      },300);
  }

  var _formState2 = () => {
      $('.btn-step2').removeClass('disabled');
      $('.btn-step1').addClass('disabled');
      $('#baddrow').attr('disabled','disabled'); 
      $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
      $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");   
      $('#carikontak').removeAttr('data-dismiss').removeAttr('data-toggle').removeAttr('role');  
  }

/**/

/* CRUD
/* ========================================================================================== */
  var _IsValid = () => {
      if($('#idkontak').val()==''){
          $('#kontak').attr('data-title','Kontak harus diisi !');
          $('#kontak').tooltip('show');
          $('#kontak').focus();
          return 0;
      }
      if ($('#uraian').val()==''){
          $('#uraian').attr('data-title','Uraian harus diisi !');      
          $('#uraian').tooltip('show');
          $('#uraian').focus();
          return 0;
      }
      if ($('#coadebet').val()=='' || $('#coadebet').val()==null){
          $('#coadebet').attr('data-title','Akun yang di debet harus diisi !');      
          $('#coadebet').tooltip('show');
          $('#coadebet').focus();
          return 0;
      }

      let totalbaris = $(".coakredit").length;
      for(let i=0;i<totalbaris;i++){
        if($("select[name^='coakredit']").eq(i).val()=='' || $("select[name^='coakredit']").eq(i).val()==null){
            $("select[name^='coakredit']").eq(i).attr('data-title','Akun harus diisi !');      
            $("select[name^='coakredit']").eq(i).tooltip('show');      
            $("select[name^='coakredit']").eq(i).focus();
            return 0;
        }
      }
      return 1;
  };

  var _deleteData = () => {

      const id = $("#id").val(),
            nomor = $("#nomor").val();

      $.ajax({ 
          "url"    : base_url+"Fina_Kas_Masuk/deletedata", 
          "type"   : "POST", 
          "data"   : "id="+id+"&nomor="+nomor,
          "cache"    : false,
          "beforeSend" : () => {
              parent.window.$(".loader-wrap").removeClass("d-none");
          },
          "error": (xhr, status, error) => {
              parent.window.$(".loader-wrap").addClass("d-none");
              parent.window.toastr.error(`Error : ${xhr.status} ${error}`);                  
              console.error(xhr.responseText);      
              return;
          },
          "success": (result) => {
              parent.window.$(".loader-wrap").addClass("d-none");        

              if(result=='sukses'){
                  _clearForm();
                  _addRow();
                  _inputFormat();
                  _formState1();
                  parent.window.toastr.success("Transaksi berhasil dihapus");                  
                  return;
              } else {        
                  parent.window.toastr.error(result);      
                  return;
              }
          } 
      })
  };

  var _saveData = () => {

      let id = $("#id").val(),
          tgl = $("#tgl").val(),
          nomor = $("#nomor").val(),
          kontak = $("#idkontak").val(),
          uraian = $("#uraian").val(),
          coadb = $("#coadebet").val(),
          uangdb = $("#iduangdebet").val(),
          kursdb = Number($("#kursdebet").val().split('.').join('').toString().replace(',','.'));

      let detil = [];

      $("select[name^='coakredit']").each(function(index,element){  
          detil.push({
                   coa:this.value,
                   jml:Number($("input[name^='amtkredit']").eq(index).val().split('.').join('').toString().replace(',','.')),
                   kurs:Number($("input[name^='kurs']").eq(index).val().split('.').join('').toString().replace(',','.')),
                   valas:Number($("input[name^='valas']").eq(index).val().split('.').join('').toString().replace(',','.')),
                   divisi:$("select[name^='divisi']").eq(index).val(),
                   proyek:$("select[name^='proyek']").eq(index).val(),
                   catatan:$("textarea[name^='catatan']").eq(index).val()
                 });

      }); 

      detil = JSON.stringify(detil);  

      //Total Transaksi
      let totalRp = Number($("#totalrp").val().split('.').join('').toString().replace(',','.')),
          totalValas = Number($("#totalvalas").val().split('.').join('').toString().replace(',','.'));  

      var rey = new FormData();  
      rey.set('id',id);
      rey.set('tgl',tgl);
      rey.set('nomor',nomor);
      rey.set('kontak',kontak);
      rey.set('uraian',uraian);
      rey.set('coadb',coadb);
      rey.set('uangdb',uangdb);
      rey.set('kursdb',kursdb);
      rey.set('totalRp',totalRp);      
      rey.set('totalValas',totalValas);          
      rey.set('detil',detil);

      $.ajax({ 
          "url"    : base_url+"Fina_Kas_Masuk/savedata", 
          "type"   : "POST", 
          "data"   : rey,
          "processData": false,
          "contentType": false,
          "cache"    : false,
          "beforeSend" : () => {
              parent.window.$(".loader-wrap").removeClass("d-none");
          },
          "error": (xhr, status, error) => {
              parent.window.$(".loader-wrap").addClass("d-none");
              parent.window.toastr.error(`Error : ${xhr.status} ${error}`);      
              console.error(xhr.responseText);      
              return;
          },
          "success": (result) => {
              result = JSON.parse(result);
              parent.window.$(".loader-wrap").addClass("d-none");                                            
              if(result.pesan=='sukses'){
                  parent.window.Swal.fire({
                      title: `Anda ingin mencetak transaksi ini ?`,
                      showDenyButton: false,
                      showCancelButton: true,
                      confirmButtonText: `Iya`,
                  }).then((printing) => {
                      if (printing.isConfirmed) {
                        window.open(`${base_url}Laporan/preview/page-bkm/${result.nomor}`)
                      }
                      parent.window.toastr.success("Transaksi berhasil disimpan");                                                             
                      _clearForm();
                      _addRow();
                      _inputFormat();
                      _formState1();
                      return;
                  })
              }                  
          } 
      })
  };

  var _tampildata = (result) => {
      if (typeof result.pesan !== 'undefined') {
          parent.window.toastr.error(result.pesan);
          parent.window.$('.loader-wrap').addClass('d-none');                  
          return;
      } else {
          $('#tdetil tbody').html('');
          for (let i = 0; i < result.data.length; i++) {
            _addRow();
          }
          _inputFormat();

          /* Isi Header Transaksi */
          var coaDb = $("<option selected='selected'></option>").val(result.data[0]['coadbid']).text(result.data[0]['coadb']);            
          $('#id').val(result.data[0]['id']);            
          $('#nomor').val(result.data[0]['nomor']);
          $('#tgl').datepicker('setDate',result.data[0]['tanggal']);
          $('#uraian').val(result.data[0]['uraian']);            
          $('#idkontak').val(result.data[0]['kontakid']);
          $('#kontak').val(result.data[0]['kontakkode']);
          $('#namakontak').html(result.data[0]['kontak']);                        
          $('#iduangdebet').val(result.data[0]['iduangdebet']);                        
          $('#uangdebet').val(result.data[0]['uangdebet']);                        
          $('#kursdebet').val(result.data[0]['kursdebet'].replace(".", ","));                        
          $('#coadebet').append(coaDb).trigger('change');
          $('#totalrp').val(result.data[0]['total'].replace(".", ","));                        
          $('#totalvalas').val(result.data[0]['valas'].replace(".", ","));                                    
          $('#status').val(result.data[0]['status']);                        
          /**/
          /* Isi Detil Transaksi */
          var rows = 0;
          $.each(result.data, function() {
            var coaCr = $("<option selected='selected'></option>").val(result.data[rows]['coadetilid']).text(result.data[rows]['coadetil']);            
            var divisi = $("<option selected='selected'></option>").val(result.data[rows]['divisidetilid']).text(result.data[rows]['divisidetil']);            

            $("select[name^='coakredit']").eq(rows).append(coaCr).trigger('change');            
            $("input[name^='amtkredit']").eq(rows).val(result.data[rows]['jmldetil'].replace(".", ","));
            $("input[name^='kurs']").eq(rows).val(result.data[rows]['kursdetil'].replace(".", ","));                   
            $("input[name^='valas']").eq(rows).val(result.data[rows]['jmldetilv'].replace(".", ","));    
            $("select[name^='divisi']").eq(rows).append(divisi).trigger('change');                                                                           
            $("textarea[name^='catatan']").eq(rows).val(result.data[rows]['catatandetil']);              

            //atur placeholder numeric jika 0
            if(result.data[rows]['jmldetil']==0) $("input[name^='amtkredit']").eq(rows).attr('placeholder','0,00');            
            if(result.data[rows]['jmldetilv']==0) $("input[name^='valas']").eq(rows).attr('placeholder','0,00');

            rows++;
          });
          /**/

          if($('.btn-step1').hasClass('disabled')){
            $('.btn-delrow').addClass('disabled');
            $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").attr('disabled','disabled');   
            $(":input").not(":button, :submit, :reset, :checkbox, :radio, .total").css("background-color", "#ffffff");
          }
          parent.window.$('.loader-wrap').addClass('d-none');                                       
          return;
      }
  }

  var _getDataTransaksi = (id) => {

      if(id=='' || id==null) return;    

      if($('#notrans').val()!=="") return;

      $.ajax({ 
          "url"    : base_url+"Fina_Kas_Masuk/getdata",       
          "type"   : "POST", 
          "dataType" : "json", 
          "data" : "id="+id,
          "cache"  : false,
          "beforeSend" : () => {
              parent.window.$('.loader-wrap').removeClass('d-none');        
          },        
          "error"  : () => {
              parent.window.toastr.error('Error : Gagal mengambil data transaksi kas masuk !');
              parent.window.$('.loader-wrap').addClass('d-none');                  
              return;
          },
          "success" : (result) => {
              _tampildata(result);
          } 
    })
  }

  var _getDataTransaksiNomor = (nomor) => {

    if(nomor=='' || nomor==null) return;    

    $.ajax({ 
      "url"    : base_url+"Fina_Kas_Masuk/getdata",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "nomor="+nomor,
      "cache"  : false,
      "beforeSend" : function(){
        parent.window.$('.loader-wrap').removeClass('d-none');                  
      },        
      "error"  : function(){
        parent.window.toastr.error('Error : Gagal mengambil data transaksi kas masuk !');
        parent.window.$('.loader-wrap').addClass('d-none');                  
        return;
      },
      "success" : function(result) {
        _tampildata(result);
      } 
  })

  }

/**/

  if(qparam.get('id')!=null){
      _clearForm();
      _formState2();  
      $("#id").val(qparam.get('id')).trigger('change');          
  }else if(qparam.get('nomor')!=null){
      _clearForm();
      _formState2();  
      $("#notrans").val(qparam.get('nomor')).trigger('change');          
  }else {
      _clearForm();
      _addRow();
      _inputFormat();
      _formState1();  
  }

});

var _hitungJumlahDetil = (idx) => {  
  return;
}

var _hitungsubtotal = () => {
  let trupiah = 0, tvalas = 0;
  
  $('.coakredit').each(function(index,element) {
    trupiah += Number($("input[name^='amtkredit']").eq(index).val().split('.').join('').toString().replace(',','.')); 
    tvalas += Number($("input[name^='valas']").eq(index).val().split('.').join('').toString().replace(',','.'));     
  });  

  trupiah = trupiah.toString().replace('.',',');
  tvalas = tvalas.toString().replace('.',',');            

  if(trupiah==0) trupiah='0,00';
  if(tvalas==0) tvalas='0,00';

  $('#totalrp').val(trupiah).attr('placeholder',trupiah);
  $('#totalvalas').val(tvalas).attr('placeholder',tvalas);      
  return;
}

window._hapusbaris = (obj) => {
  if($(obj).hasClass('disabled')) return;    
   
  $(obj).parent().parent().remove();
  _hitungsubtotal();
}