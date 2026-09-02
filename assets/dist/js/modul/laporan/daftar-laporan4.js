import { Component_Inputmask_Date, 
         Component_Scrollbars, 
         Component_Select2, 
         Component_Select2_Tags,          
         Component_Select2_Account,
         Component_Select2_Item } from '../component.js';

$(function(){

Component_Scrollbars('.content-wrapper','hidden','scroll');

this.addEventListener('contextmenu', (e) => {
  e.preventDefault();
});

$.ajax({ 
    "url"    : base_url+"Laporan/getparent",       
    "type"   : "POST", 
    "dataType" : "json", 
    "cache"  : false,
    "beforeSend" : () => {
      parent.window.$('.loader-wrap').removeClass('d-none');        
    },        
    "error"  : () => {
      parent.window.$('.loader-wrap').addClass('d-none');                  
      return;
    },
    "success" : async (result) => {

      await renderrpt(result.data[0]['MID']);              

      var rows = 0,
          aktif = true,
          isAktif = 'active';

      $.each(result.data, function() {
        if (rows>0) isAktif=''; 
        var html = `<li class="nav-item">
                        <a class="nav-link text-sm border-0 `+isAktif+` py-1 my-0" id="`+result.data[rows]['mlink']+`" data-toggle="pill" href="javascript:void(0)" onClick="renderrpt(`+result.data[rows]['MID']+`)">
                        `+result.data[rows]['MCAPTION1']+`</a>
                    </li>`;
        $("#listparent").append(html); 
        aktif = false;
        rows++;
      });

      parent.window.$('.loader-wrap').addClass('d-none');                                       
      return;

    }
})

})


window.renderrpt = function(id){
   $.ajax({ 
      "url"    : base_url+"Laporan/getreportlist",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data"   : "id="+id,
      "cache"  : false,
      "beforeSend" : () => {
        parent.window.$('.loader-wrap').removeClass('d-none');
      },
      "error"  : () => {
        parent.window.$('.loader-wrap').addClass('d-none');        
        parent.window.Swal.fire({
          title: 'Kesalahan : Gagal Mengambil Data Laporan ke server !',
          showDenyButton: false,
          showCancelButton: false,
          confirmButtonText: `Tutup`,
        })
        return;
      },
      "success" : (result) => {
          $("#tabcontent").html("");
          var rows = 0;
          $.each(result.data, function() {
            if(result.data[rows]['file']){
              var cek = "<i class='fas fa-check-square text-primary'></i>";
            }else{
              var cek = "";
            }
            var html = `<div class="col-sm-4 col-12">
                        <div class='ribbon-small ribbon'>
                          <div class='ribbon bg-transparent'>
                          `+cek+`
                          </div>
                        </div>                
                        <div class="small-box bg-white" role="button" onClick="openRpt('`+result.data[rows]['file']+`','`+result.data[rows]['rptid']+`')">
                        <div class="inner mt-2">
                          <h6 class='text-dark font-weight-normal'>`+result.data[rows]['MCAPTION1']+`</h5>
                          <p class="text-sm">`+result.data[rows]['MDESCRIPTION']+`</p>
                        </div>                
                        <div class="icon py-2"></div>
                        </div>
                      </div>`;
            $("#tabcontent").append(html);
            rows++;
          });
          parent.window.$('.loader-wrap').addClass('d-none');          
          return;
      }
  })
}

window.openRpt = function(rpt,rptid){
  if(typeof rpt==undefined || rpt=='null'){
    parent.window.Swal.fire({
      title: 'Kesalahan : Laporan Belum Tersedia !',
      showDenyButton: false,
      showCancelButton: false,
      confirmButtonText: `Tutup`,
    })
  }else{

      $.ajax({ 
        "url"    : base_url+"Laporan/getinforeport",       
        "type"   : "POST", 
        "dataType" : "json", 
        "data"   : "id="+rptid,
        "cache"  : false,
        "beforeSend" : () => {
            $('.loader-wrap').removeClass('d-none');        
        },        
        "error"  : (xhr,status,error) => {
            $(".main-modal-body").html('');        
            parent.window.toastr.error("Perbaiki kesalahan ini : "+xhr.status+" "+error);
            console.error(xhr.responseText);
            $('.loader-wrap').addClass('d-none');                  
            return;
        },
        "success" : (result) => {
          if (typeof result.pesan !== 'undefined') {
              parent.window.toastr.error(result.pesan);
              $('.loader-wrap').addClass('d-none'); 
              return;
          } else {
              var $html = `<form id="frmlaporan" class='form-horizontal' method="post" action="${base_url}laporan" target="_blank">
                              <input type="hidden" id="id" name="id" value="${rptid}">
                              <input type="hidden" id="title" name="title" value="${rpt}"> 
                              <input type="hidden" id="saldo" name="saldo" value="1">
                              <input type="hidden" id="satuanbesar" name="satuanbesar" value="0">                              
                              <input type="hidden" id="minimum" name="minimum" value="1">
                              <input type="hidden" id="hidedetil" name="hidedetil" value="0">     
                              <input type="hidden" id="tanpanh" name="tanpanh" value="1">                             
                              <div class="modal-body">`;
              if(result.data[0]['ARDATE1F']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Dari Tanggal</label>                
                              <div class="col-sm-4">
                              <div class="input-group date">
                                <input type="text" id="tgldari" name="tgldari" class="form-control form-control-sm datepicker" autocomplete="off">
                                <div id="dTglDari" class="input-group-append" role="button" onClick="dTglDari()">
                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                              </div> 
                              </div>
                              <label class="col-sm-1 col-form-label text-sm font-weight-normal">s/d</label>                                    
                              <div class="col-sm-4">
                              <div class="input-group date">
                                <input type="text" id="tglsampai" name="tglsampai" class="form-control form-control-sm datepicker" autocomplete="off">
                                <div id="dTglSampai" class="input-group-append" role="button" onClick="dTglSampai()">
                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                              </div>                                            
                              </div>
                            </div>`;
              }
              if(result.data[0]['ARDATE2F']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Per Tanggal</label>                
                              <div class="col-sm-4">
                              <div class="input-group date">
                                <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                                <div id="dTgl" class="input-group-append" role="button" onClick="dTgl()">
                                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                                </div>
                              </div> 
                              </div>
                            </div>`;
              }
              if(result.data[0]['ARTAHUNF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Tahun</label>
                              <div class="col-sm-8">
                                <select id="tahun" name="tahun" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;
              }                     
              if(result.data[0]['ARKONTAKF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Kontak</label>
                              <div class="col-sm-3">
                                    <div class="input-group">
                                      <input type="hidden" id="idkontak" name="idkontak">                    
                                      <input type="text" class="form-control form-control-sm" id="kontak" name="kontak" autocomplete="off">
                                      <div id="carikontak" class="input-group-append" role="button" onClick="carikontak()">
                                          <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                      </div>
                                    </div>                
                              </div>
                              <div id="namakontak" class="col-sm-6 col-form-label-sm"></div>
                            </div>`;
              }
              if(result.data[0]['ARCOAF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Dari Akun</label>
                              <div class="col-sm-8">
                                <select id="coa" name="coa" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Sampai Akun</label>
                              <div class="col-sm-8">
                                <select id="coasampai" name="coasampai" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;                            
              }
              if(result.data[0]['ARBANKF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Dari Rekening</label>
                              <div class="col-sm-8">
                                <select id="bank" name="bank" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Sampai Rekening</label>
                              <div class="col-sm-8">
                                <select id="banksampai" name="banksampai" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;                            
              }   
              if(result.data[0]['ARDIVISIF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Divisi</label>
                              <div class="col-sm-8">
                                <select id="divisi" name="divisi" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;
              }                      
              if(result.data[0]['ARITEMF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Item</label>
                              <div class="col-sm-8">
                                <select id="item" name="item" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;
              }                            
              if(result.data[0]['ARGUDANGF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Cabang</label>
                              <div class="col-sm-8">
                                <select id="gudang" name="gudang" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;
              }                             
              if(result.data[0]['ARPTF']==1){                    
              
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Nama PT</label>
                              <div class="col-sm-8">
                                <select id="namapt" name="namapt" class="form-control form-control-sm select2" style="width: 100%">
                                </select>     
                              </div>
                            </div>`;
               } 
              if(result.data[0]['ARITEMTAHUNF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Tahun Item</label>
                              <div class="col-sm-8">
                                <input type="text" id="tahunitem" name="tahunitem" class="form-control form-control-sm" autocomplete="off">
                              </div>
                            </div>`;
              }                                                                                         
             if(result.data[0]['ARNOMORF']==1){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Dari Nomor</label>
                              <div class="col-sm-8">
                                <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" autocomplete="off">
                              </div>
                            </div>`;
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Sampai Nomor</label>
                              <div class="col-sm-8">
                                <input type="text" id="nomorsampai" name="nomorsampai" class="form-control form-control-sm" autocomplete="off">
                              </div>
                            </div>`;                            
              }

              if(result.data[0]['ARLINK']=='laporan-penjualan-per-barang'){
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Jenis Merchant</label>
                              <div class="col-sm-8">
                                <select id="merchant" name="merchant" class="form-control form-control-sm select2" style="width: 100%">
                                  <option value="">(Semua)</option>
                                </select>
                              </div>
                            </div>`;
                  $html += `<div class="row mx-2 mt-1">
                              <label class="col-sm-3 col-form-label text-sm font-weight-normal">Kecualikan PT</label>
                              <div class="col-sm-8">
                                <select id="filterpt" name="filterpt" class="form-control form-control-sm select2" style="width: 100%">
                                  <option value="">(Tidak ada)</option>
                                </select>
                              </div>
                            </div>`;
              }


              $html += `<div class="row mx-2 mt-1">
                          <label class="col-sm-3 col-form-label text-sm font-weight-normal">Pilihan</label>
                          <div class="col-sm-8">
                            <select id="mode" name="mode" class="form-control form-control-sm select2" onChange="mode_click()" style="width: 100%">
                              <option value='1'>Print Preview</option>
                              <option value='2'>Pdf Download</option>                                                            
                              <option value='3'>Excel Download</option>                              
                              <option value='4'>Send Pdf to Email</option>                                                            
                            </select>     
                          </div>
                        </div>`;

              $html += `<div class="email row mx-2 mt-1 d-none">
                          <label class="col-sm-3 col-form-label text-sm font-weight-normal">Email Ke</label>
                          <div class="col-sm-8">
                              <select id="emailto" name="emailto" class="form-control form-control-sm select2" style="width: 100%">
                              </select>     
                          </div>
                        </div>`;

              $html += `<div class="email row mx-2 mt-1 d-none">
                          <label class="col-sm-3 col-form-label text-sm font-weight-normal">Subjek</label>
                          <div class="col-sm-8">
                            <input type="search" id="subject" name="subject" class="form-control form-control-sm" autocomplete="off">
                          </div>
                        </div>`;

              $html += `<div class="email row mx-2 mt-1 d-none">
                          <label class="col-sm-3 col-form-label text-sm font-weight-normal">Teks Mail</label>
                          <div class="col-sm-8">
                            <textarea id="textmail" name="textmail" class="form-control form-control-sm" autocomplete="off" style="height:80px"></textarea>
                          </div>
                        </div>`;                                                

              if(result.data[0]['ARSALDOF']==1){
                  $html += `<div class="row mx-2 mt-4">
                              <div class="form-check col-sm-12">
                                <input type="checkbox" class="form-check-input" id="chk0" onClick="chk0_click()" checked>
                                <label class="form-check-label text-sm" for="chk0">Tampilkan yang bersaldo 0</label>
                              </div>                  
                            </div>`;
              }                                          

              if(result.data[0]['ARLINK']=='laporan-neraca' || result.data[0]['ARLINK']=='laporan-neraca-tee'){
                  $html += `<div class="row mx-2 mt-2">
                              <div class="form-check col-sm-12">
                                <input type="checkbox" class="form-check-input" id="chkHide" onClick="chkHide_click()">
                                <label class="form-check-label text-sm" for="chkHide">Tampilkan hanya akun induk</label>
                              </div>                  
                            </div>`;                 
              }

              if(result.data[0]['ARLINK']=='laporan-kartu-stok' || result.data[0]['ARLINK']=='laporan-kartu-persediaan' || result.data[0]['ARLINK']=='laporan-daftar-item'){
                  $html += `<div class="row mx-2 mt-2">
                              <div class="form-check col-sm-12">
                                <input type="checkbox" class="form-check-input" id="chksatuan" onClick="chksatuan_click()">
                                <label class="form-check-label text-sm" for="chksatuan">Dalam satuan terbesar</label>
                              </div>                  
                            </div>`;
              }                                          
              
              if(result.data[0]['ARMINIMUMF']==1){
                  $html += `<div class="row mx-2 mt-2">
                              <div class="form-check col-sm-12">
                                <input type="checkbox" class="form-check-input" id="chk1" onClick="chk1_click()" checked>
                                <label class="form-check-label text-sm" for="chk1">Tampilkan Hanya Yang Mencapai Minimum</label>
                              </div>                  
                            </div>`;
              }                                            
              
               
                
              
              

              $html += `</div>
                          <div class="modal-footer">
                            <div class="form-group">
                                <div class="col-sm-offset-3">
                                    <a class="btn btn-outline-primary btn-sm px-4 mx-1" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>                                    
                                    <button type="button" onClick="submitrpt_click()" id="submitrpt" name='submitrpt' class="btn btn-primary btn-sm px-2">Lanjutkan</button>
                                </div>
                            </div>                
                          </div>
                        </form>`;

              parent.window.$(".modal").modal("show");                                
              parent.window.$(".main-modal-body").append($html);                  
              parent.window.$(".modal-title").html(`Filter - ${rpt}`);
              parent.window.$("#modaltrigger").val("modal");

              Component_Inputmask_Date("#tgldari,#tglsampai", true);
              Component_Select2("#sumber",`${base_url}Select_Master/view_jenis_transaksi`,null,null,true);
              Component_Select2_Tags("#emailto",`${base_url}Select_Master/view_email`,null,null,true);              
              Component_Select2_Account("#coa,#coasampai",`${base_url}Select_Master/view_coa_nocoa`,null,null,true);
              Component_Select2("#bank,#banksampai",`${base_url}Select_Master/view_rekening_kasbank`,null,null,true);       
              Component_Select2("#divisi",`${base_url}Select_Master/view_divisi`,null,null,true);       
              Component_Select2("#tahun",`${base_url}Select_Master/view_tahun_periode2`,null,null,true);       
             // Component_Select2("#item",`${base_url}Select_Master/view_item`,null,null,true);                       
              Component_Select2("#mode",null,null,null,true);
              Component_Select2("#merchant",`${base_url}Select_Master/view_merchant_jenis`,null,null,true);
              Component_Select2("#filterpt",`${base_url}Select_Master/view_namapt`,null,null,true);
              parent.window.$(".datepicker").datepicker('setDate', 'dd-mm-yy');
              //parent.window.$("#tgldari").datepicker('setDate', '01-mm-yy');     
              
              
             // Component_Select2("#item",`${base_url}Select_Master/,null,null,true`,null,null,true);   
              Component_Select2("#item",`${base_url}Select_Master/view_item`,null,null,true);               

              var currentTime = new Date();              
              var thn = $("<option selected='selected'></option>").val(currentTime.getFullYear()).text(currentTime.getFullYear());              
              parent.window.$("#tahun").append(thn).trigger('change');

            
              
             
              if ( parent.window.$('#allcabang').val()==1) 
              { 
              Component_Select2("#gudang",`${base_url}Select_Master/view_gudang`,null,null,true);    
              Component_Select2("#namapt",`${base_url}Select_Master/view_namapt`,null,null,true);       
              }
              
                const _cbg = $("<option selected='selected'></option>").val(parent.window.$('#idcabang').val()).text(parent.window.$('#namacabang').val());        
              parent.window.$('#gudang').append(_cbg).trigger('change');



              $('.loader-wrap').addClass('d-none');                                       
              return;
          }
      } 
    })
  }
}

parent.window.submitrpt_click = () => {
  if(parent.window.$('#mode').val()==4){
      let mailto = parent.window.$('#emailto').val();
      let subject = parent.window.$('#subject').val();
      let contentmail = parent.window.$('#textmail').val();
      let id = parent.window.$('#id').val();
      let title = parent.window.$('#title').val();      
      let saldo = parent.window.$('#saldo').val();            
      let tgldari = parent.window.$('#tgldari').val();                  
      let tglsampai = parent.window.$('#tglsampai').val(); 
      let tgl = parent.window.$('#tgl').val();       
      let mode = 4;                      
      let gudang = parent.window.$('#gudang').val();    
      let namapt = parent.window.$('#namapt').val();               

      if (mailto == null) {
        parent.window.toastr.error('Alamat email yang dituju harus diisi !');
        parent.window.$('#emailto').focus();
        return;
      }

      var rey = new FormData();  
      rey.set('mailto',mailto);
      rey.set('subject',subject);
      rey.set('contentmail',contentmail);
      rey.set('id',id);
      rey.set('title',title);
      rey.set('saldo',saldo);
      rey.set('tgldari',tgldari);
      rey.set('tglsampai',tglsampai);
      rey.set('tgl',tgl);
      rey.set('mode',mode);   
      rey.set('gudang',gudang);   
      rey.set('namapt',namapt);     

      $.ajax({ 
        "url"    : base_url+"Laporan", 
        "type"   : "POST", 
        "data"   : rey,
        "processData": false,
        "contentType": false,
        "cache"    : false,
        "beforeSend" : function(){
          parent.window.$(".loader-wrap").removeClass("d-none");
        },
        "error": function(xhr, status, error){
          parent.window.$(".loader-wrap").addClass("d-none");
          parent.window.toastr.error("Perbaiki masalah ini : "+xhr.status+" "+error);      
          console.log(xhr.responseText);      
          return;
        },
        "success": function(result) {
          parent.window.$(".loader-wrap").addClass("d-none");        

          if(result=='sukses'){
            parent.window.$('#modal').modal('hide');                
            parent.window.toastr.success("Email telah terkirim");                  
            return;
          } else {        
            parent.window.toastr.error("Email gagal dikirim");
            console.log(result);                          
            return;
          }
        } 
      });

  }else{
    parent.window.$("#frmlaporan").submit();
  }
}

parent.window.mode_click = () => {
  if(parent.window.$('#mode').val()==4){
    parent.window.$('.email').removeClass('d-none');    
  }else{
    parent.window.$('.email').addClass('d-none');        
  }
}

parent.window.chk0_click = () => {
  if(parent.window.$("#chk0").prop("checked")==true){
    parent.window.$("#saldo").val('1');    
  }else{
    parent.window.$("#saldo").val('0');    
  }
}

parent.window.chk1_click = () => {
  if(parent.window.$("#chk1").prop("checked")==true){
    parent.window.$("#minimum").val('1');    
  }else{
    parent.window.$("#minimum").val('0');    
  }
}

parent.window.chksatuan_click = () => {
  if(parent.window.$("#chksatuan").prop("checked")==true){
    parent.window.$("#satuanbesar").val('1');    
  }else{
    parent.window.$("#satuanbesar").val('0');    
  }
}

parent.window.chkHide_click = () => {
  if(parent.window.$("#chkHide").prop("checked")==true){
    parent.window.$("#hidedetil").val('1');    
  }else{
    parent.window.$("#hidedetil").val('0');    
  }
}

 

parent.window.dTgl = () => {
  parent.window.$("#tgl").focus();
}

parent.window.dTglDari = () => {
  parent.window.$("#tgldari").focus();
}

parent.window.dTglSampai = () => {
  parent.window.$("#tglsampai").focus();
}

parent.window.carikontak = () => {
  var $html = `<div class="modal fade" id="modalfilter" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
                  <div id="modalsize" class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h5 class="modal-title text-md" id="myModalLabel"></h5> 
                        <ul id="nav-fkontak" class="navbar-nav d-none">
                          <li class="nav-item dropdown d-sm-inline-block">
                            <a href="javascript:void(0)" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
                              <i class="fas fa-caret-down px-2 text-light"></i>
                            </a>
                            <div class="list-kategori dropdown-menu dropdown-menu-sm dropdown-menu-left"> 
                            </div>        
                          </li>
                        </ul>                        
                        <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true" tabindex="-1">&times;</button>
                      </div>
                      <div class="main-modal-body">
                      </div>
                    </div>
                  </div>
                </div>`;

    parent.window.$(".usable").html($html);

    $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak_report", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": () => {
            parent.window.$(".loader-wrap").removeClass("d-none");                              
            parent.window.$("#modalfilter .modal-title").html("Cari Kontak");
        },         
        "error": () => {
            parent.window.$(".loader-wrap").addClass("d-none");                    
            parent.window.toastr.error('Error menampilkan modal cari kontak...');
            return;
        },
        "success": (result) => {
            parent.window.$("#modalfilter .main-modal-body").html(result);
            parent.window.$('#modalfilter .modal-body').css('min-height','calc(100vh - 30vh)');          
            parent.window.$("#modalfilter").modal("show");
            parent.window._lstkategorikontak();
            parent.window._kontakdatatable();
            setTimeout(function (){
                 parent.window.$('#modalfilter input').focus();
            }, 500);
            return;
        } 
    })  
}

parent.window.carikaryawan = () => {
  var $html = `<div class="modal fade" id="modalfilter" data-backdrop="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2" aria-hidden="true">
                  <div id="modalsize" class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-primary">
                        <h5 class="modal-title text-md" id="myModalLabel"></h5> 
                        <ul id="nav-fkontak" class="navbar-nav d-none">
                          <li class="nav-item dropdown d-sm-inline-block">
                            <a href="javascript:void(0)" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
                              <i class="fas fa-caret-down px-2 text-light"></i>
                            </a>
                            <div class="list-kategori dropdown-menu dropdown-menu-sm dropdown-menu-left"> 
                            </div>        
                          </li>
                        </ul>                        
                        <button type="button" class="close text-light" data-dismiss="modal" aria-hidden="true" tabindex="-1">&times;</button>
                      </div>
                      <div class="main-modal-body">
                      </div>
                    </div>
                  </div>
                </div>`;

    parent.window.$(".usable").html($html);

    $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak_report", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": () => {
            parent.window.$(".loader-wrap").removeClass("d-none");                              
            parent.window.$("#modalfilter .modal-title").html("Cari Mekanik");
            parent.window.$("#coltrigger").val('mekanik');
        },         
        "error": () => {
            parent.window.$(".loader-wrap").addClass("d-none");                    
            parent.window.toastr.error('Error menampilkan modal cari mekanik...');
            return;
        },
        "success": (result) => {
            parent.window.$("#modalfilter .main-modal-body").html(result);
            parent.window.$('#modalfilter .modal-body').css('min-height','calc(100vh - 30vh)');          
            parent.window.$("#modalfilter").modal("show");
            parent.window._lstkategorikontak();
            parent.window._kontakdatatable();
            setTimeout(function (){
                 parent.window.$('#modalfilter input').focus();
            }, 500);
            return;
        } 
    })  
}