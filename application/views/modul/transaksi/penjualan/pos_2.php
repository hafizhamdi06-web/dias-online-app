 

<body id="<?= $id; ?>" class="layout-fixed bg-transparent overflow-hidden" data-panel-auto-height-mode="height"> 
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page-pos.css');?>">

  <!-- Loading Page -->  
  <div class="loader-wrap d-none">
    <div class="loader">
      <div class="box-1 box"></div>
      <div class="box-2 box"></div>
      <div class="box-3 box"></div>
      <div class="box-4 box"></div>
      <div class="box-5 box"></div>
    </div>
  </div>

  <div class="content-wrapper tab-wrap mx-0"  style="width:100%; height:50%">
    <form id="form-<?= $id; ?>" class="form-horizontal">
        <div class="content-header bg-white px-0 py-0 position-fixed w-100">  
                        <div class="card card-body">  
                        
                 
  
  
  
         
                                            <div class="total-wrap">
                                            <div class="pos-header-top">
                                              <div class="pos-pasien-col">

                                                <input type="hidden" id="idkontak" name="idkontak">
                                                <input type="hidden" id="kontaktipe" name="kontaktipe">
                                                <input type="hidden" id="kontak" name="kontak">

                                                <div class="d-flex align-items-center flex-wrap">
                                                    <input type="text" id="namakontak" name="namakontak" class="pos-nama-pasien kuncitext" autocomplete="off">
                                                    <div id="divtglexpiredmember" class="d-flex flex-wrap ml-2"></div>
                                                    <span id="namakontak-measure" class="pos-nama-pasien-measure"></span>
                                                </div>

                                                <div class="pos-idpasien-row">
                                                    <span class="pos-meta-item"><i class="fa fa-id-card"></i><span id="pasienid"></span></span>
                                                </div>

                                                <div class="pos-actions-meta-row">
                                                    <div class="pos-pasien-actions">
                                                        <span id="carikontak" role="button" class="pos-btn-cari input-group-append"><i class="fa fa-search"></i>Cari Pasien</span>
                                                        <span id="bpasien" role="button" class="pos-action-icon input-group-append"><i class="fa fa-info-circle"></i>Info</span>
                                                        <span id="bpasienhistori" role="button" class="pos-action-icon input-group-append"><i class="fa fa-history"></i>History</span>
                                                    </div>

                                                    <div class="pos-meta-row">
                                                        <span class="pos-meta-item"><i class="fa fa-hashtag"></i><input type="text" id="nomor" name="nomor" class="pos-meta-input kuncitext" placeholder="New Trans" disabled></span>
                                                        <span class="pos-meta-item"><i class="fa fa-calendar-alt"></i><input type="text" id="tgl" name="tgl" class="pos-meta-input datepicker kuncitext" autocomplete="off"></span>
                                                        <span class="pos-meta-item"><i class="fa fa-user"></i><input type="text" id="salesman" name="salesman" class="pos-meta-input kuncitext" autocomplete="off" disabled></span>
                                                    </div>
                                                </div>

                                              </div>

                                              <div class="total-overlay-static">
                                                  <input id="tsubtotal" type="text" class="total numeric kuncitext" value="0">
                                              </div>
                                            </div>

                                            <div class="d-none">
                                                <input type="hidden" id="idpaket" name="idpaket" value="">
                                                <input type="hidden" id="jumlahkupon" name="jumlahkupon" value="">
                                                <input type="hidden" id="jumlahpaket" name="jumlahpaket" value="0">
                                                <input type="hidden" id="nopaketheader" name="nopaketheader" value="">
                                                <input type="hidden" id="idpromo" name="idpromo" value="">
                                                <input type="hidden" id="medid" name="medid">
                                                <input type="hidden" id="webid" name="webid">
                                                <input type="hidden" id="webidbelumiv" name="webidbelumiv">
                                                <input type="hidden" id="txtidbarang" name="txtidbarang">
                                                <input type="hidden" id="appcanceltransaksi" name="appcanceltransaksi">
                                                <input type="hidden" id="cabang" name="cabang" value="<? echo @$_SESSION['cabang']; ?>">
                                                <input type="hidden" id="nomorlama" name="nomorlama">
                                                <input type="hidden" id="iddiskonvocer" name="iddiskonvocer">
                                                <input id="totaltanpadp" type="hidden">
                                                <input type="hidden" id="idsalesman" name="idsalesman">
                                                <input id="totalbayar" type="text" class="total numeric kuncitext" value="0">
                                                <input id="totalsisa" type="text" class="total numeric kuncitext" value="0">
                                            </div>

                                            </div>


                        </div>

        </div>


    <input type="hidden" id="id" name="id" value="">
    <input type="hidden" id="status" name="status">
    <input type="hidden" class="noclear" id="multidivisi" name="multidivisi" value="<?= $multidivisi ?>">
    <input type="hidden" class="noclear" id="multiproyek" name="multiproyek" value="<?= $multiproyek ?>">
    <input type="hidden" class="noclear" id="multisatuan" name="multisatuan" value="<?= $multisatuan ?>">
    <input type="hidden" class="noclear" id="multikurs" name="multikurs" value="<?= $multikurs ?>">        
    <input type="hidden" class="noclear" id="decimalqty" name="decimalqty" value="<?= $decimalqty ?>">       
    <input type="hidden" class="noclear" id="cabanguser" name="cabanguser" value="<? echo @$_SESSION['cabang']; ?>">         
    
    <input type="text" class="noclear" id="bisatambah" name="bisatambah" value="<?= $bisatambah ?>">       
    <input type="text" class="noclear" id="bisaedit" name="bisaedit" value="<?= $bisaedit ?>">       
    <input type="text" class="noclear" id="bisaprint" name="bisaprint" value="<?= $bisaprint ?>">       
    <input type="text" class="noclear" id="bisahapus" name="bisahapus" value="<?= $bisahapus ?>">        
    <input type="text" class="noclear" id="bisapprove" name="bisapprove" value="<?= $bisapprove ?>">  
    
    
    <section class="content" style="margin-top:110px;">
      <div class="container-fluid">
               
          <div class="card card-primary card-outline card-outline-tabs mt-2" style="box-shadow: none">
 
      
            <div class="card card-body card-outline-tabs-body height=100px">    
                <div class="tab-content">  
                
                    <div class="tab-pane fade active show text-sm" id="tab-menu" role="tabpanel" aria-labelledby="btn-tab-menu">                
                        <div class="row">              
                            <div class="table-responsive pt-0" tabindex="-1">
                                <table id="tdetil" class="table table-hover  table-sm  ">  
                                 <thead class="bg-light">
                                    <tr>
                                      <th> 
                                      <div class="form-group row my-0">
                                            <label class="col-3 col-form-label text-sm px-3 font-weight-bold pos-aksi-header"  >Nama </label>
                                            <label class="col-1 col-form-label text-sm px-1 font-weight-bold text-right pos-aksi-header"  >Qty </label>
                                            <label class="col-2 col-form-label text-sm px-1 font-weight-bold text-right pos-aksi-header"  >Harga </label>
                                            <label class="col-1 col-form-label text-sm px-1 font-weight-bold pos-aksi-header"  >Dis 1 </label>
                                            <label class="col-1 col-form-label text-sm px-1 font-weight-bold pos-aksi-header"  >Dis 2 </label>
                                            <label class="col-2 col-form-label text-sm px-1 font-weight-bold text-right pos-aksi-header"  >Sub Total </label>
                                            <label class="col-1 col-form-label text-sm px-1 font-weight-bold text-center pos-aksi-header"  >Aksi </label>
                                     </div>
                                      </th>
                                      <th class="text-center pos-aksi-header">Hapus</th>
                                    </tr>
                                  </thead>
                                    <tbody>
                                    </tbody> 
                                    <tfoot> 
                                    </tfoot>
                                </table>  
                                                <div class="py-3"></div>
                            </div> 
                        </div>
                    </div>
                    <div class="sm-none ">
                    <div class="tab-pane fade text-sm" id="tab-dp" role="tabpanel" aria-labelledby="btn-tab-dp">
                      <div class="row px-0 mt-0">                       
                      <div class="table-responsive">
                            <table id="tvoucher" class="table table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th> 
                                            <div class="form-group row my-0"> 
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >Nomor </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >Nilai </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >Item </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >Baris </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >Untuk 1 bon </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >item kedua </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >persentase </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >nilai2 </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >freeitem </label>  
                                                <label class="col-1 col-form-label text-sm px-3 font-weight-normal"  >Id Voucher </label>  
                                             </div> 
                                        </th> 
                                    </tr>
                                </thead>
                              <tbody>
                              </tbody>
                              <tfoot>
                              </tfoot>
                            </table>
                      </div>
                    </div>                                
                    <div style='clear:both'></div>
                    </div> 
                    </div>
            
                    
                    
                    
                </div> 
            </div> 
            
            
               
            
        </div>      
           
      </div>  
    </section>   
  

      <footer id="footer" class="mainx-footer bg-white text-sm text-gray border-0 py-2 my-0 px-0">  
         
         <div class="row"> <!--begin::Col--> 
            <div class="col-md-12"> <!--begin::Quick Example-->  
                   <div class="form-group row my-0 d-none"> 
                     <div class="col-12 px-3">
                     <button type="button" id="baddrow" class="btn btn-primary btn-step1 text-sm mb-2"><i class="fa fa-plus px-2 d-none"></i>Tambah Data</button>
                     <span id="loader-detil-legacy" class="ml-2 text-sm d-none"><i class="fas fa-spinner fa-spin mx-2  d-none"></i>loading item data...</span>
   
            
                  </div>   
                  </div> 
                  
            
                <div class="form-group row my-0">
                    <div class="col-8  px-3 ">
                        <button type="button" id="btambahitem2" class="btn btn-success btn-step1 btn-block pos-btn-tambahitem buttontambahan" role="button"><i class="fa fa-cart-plus px-2"></i>F2 Tambah Item</button>
                        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                            <button type="button" id="bpaket" class="btn btn-outline-secondary btn-step1 text-sm btn-sm  buttontambahan" role="button"><i class="fa fa-box px-2"></i>Paket</button>
                            <button type="button" id="bpromo" class="btn btn-outline-secondary btn-step1 text-sm btn-sm  buttontambahan" role="button"><i class="fa fa-percent px-2"></i>Promo</button>
                            <button type="button" id="bpro" class="btn btn-outline-secondary btn-step1 text-sm btn-sm  buttontambahan" role="button"><i class="fa fa-briefcase-medical px-2"></i>PRO</button>
                            <div class="dropdown ">
                              <button class="btn btn-outline-secondary text-sm btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-book-medical px-2"></i>Medlib
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="medlibbyjokul();" >Data Invoice by Jokul</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="medlibbeluminvoice();" >Data Medlib Belum Invoice</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="medlibbeluminvoice_cara2();" >Data Medlib Belum Invoice 2</a>
                              </div>
                            </div>


                     <!--begin::Col -->  <button type="button" id="bkirimulangemail" class="btn btn-outline-secondary btn-step1 text-sm btn-sm  buttontambahan d-none" role="button"><i class="fa fa-envelope px-2"></i>Kirim Ulang Email</button>

                     <button type="button" id="bkupon" class="btn btn-outline-secondary btn-step1 text-sm btn-sm  buttontambahan " role="button"><i class="fa fa-ticket-alt px-2"></i>Kupon</button>

                     <button type="button" id="briwayathariini" class="btn btn-outline-secondary btn-step1 text-sm btn-sm  buttontambahan " role="button"><i class="fa fa-history px-2"></i>Transaksi Hari Ini</button>
                     
                     
                         
                         </div> 
                         <span id="loader-detil" class="ml-2 text-sm d-none"><i class="fas fa-spinner fa-spin mx-2"></i>loading data...</span>
                    </div>
                
                    <div class="col-2   px-0 ">
                            <button type="button" id="bbayar" class="btn btn-primary btn-step1 btn-block buttontambahan"><i class="fa fa-money-bill-wave px-2"></i>Bayar</button>
                    </div>
                    <div class="col-2   px-0 ">
                            <button type="button" id="bsave" class="btn btn-success btn-step1 btn-block" title="Simpan"><i class="fa fa-save px-2"></i>Simpan</button>
                    </div>
                </div>
                
                  <div class="form-group row my-0"> 
                <div class="col-8 px-3 "> 
                       <div class="position-relative"><div class="position-absolute top-0 start-50 translate-middle-x"> 
                          <div class="btn-group2">          
                            <a id="badd" class="btn btn-app btn-step2 disabled" title="Tambah">
                              <i class="fas fa-plus" title="Tambah"></i>
                            </a>
                            <a id="bedit" class="btn btn-app btn-step2 disabled" title="Ubah">
                              <i class="fas fa-edit" title="Ubah"></i>
                            </a>
                            <a id="bdelete" class="btn btn-app btn-step2 disabled" title="Hapus">
                              <i class="fas fa-trash" title="Hapus"></i>
                            </a>    
                            <a id="bsearch" class="btn btn-app btn-step2 disabled" title="Cari Data">
                              <i class="fas fa-search" title="Cari Data"></i>
                            </a>
                            <a id="bprint" class="btn btn-app btn-step2 disabled" title="Cetak">
                              <i class="fas fa-print" title="Cetak"></i>
                            </a>        
                            <a id="bcancel" class="btn btn-app btn-step1" title="Batal">
                              <i class="fas fa-times" title="Batal"></i>
                            </a>                      
                            <a id="bcanceltransaksi" class="btn btn-app btn-step1" title="Cancel Transaksi">
                              <i class="fas fa-ban" title="Cancel Transaksi"></i>
                            </a>
                            <a id="bapprovecanceltransaksi" class="btn btn-app btn-step2 disabled" title="Approve Cancel Transaksi">
                              <i class="fas fa-check-double" title="Approve Cancel Transaksi"></i>
                            </a>
                            </div>  
                      </div> </div> 
                      
                </div> 
                <div class="col-2 px-0 ">  
                            
                            <div class="dropdown ">
                              <button class="btn btn-outline-secondary text-sm btn-block dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-folder-open px-2"></i>Data Lainnya
                              </button>
                              <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void(0)" onclick="datalainnya();" >Data Lainnya</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="catatanplanning();" >Catatan Planning</a>
                                <a class="dropdown-item" href="javascript:void(0)" onclick="datateman();" >Data Teman</a>
                              </div>
                            </div>


                  </div>
                <div class="col-2 px-0 ">
                            <button type="button" id="bdatasurgery" class="btn btn-outline-secondary btn-step1 btn-block buttontambahan"><i class="fa fa-syringe px-2"></i>Data Surgery</button>
                  </div>
                
                </div>       
            
                   
               
        
            
                
            
            <div class="form-group row my-0  d-none"
             <div class="col-md-7"> <!--begin::Quick Example-->
 
                  
                            <div class="form-group row my-0  d-none">                                                        
                                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Total Tanpa DP</label>
                                    <div class="col-8">  
                                    </div>       
                            </div> 
                             <div class="form-group row my-0">                                                        
                                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Total Transaksi</label>
                                    <div class="col-8">  
                                        <input id="tsubtotalx" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
                                    </div>       
                            </div>   
                              <div class="form-group row my-0  d-none">                                                        
                                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Total Bayar</label>
                                    <div class="col-8">  
                                        <input id="totalbayarx" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
                                    </div>       
                            </div>      
                              <div class="form-group row my-0  d-none">                                                        
                                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Sisa</label>
                                    <div class="col-8">  
                                        <input id="totalsisax" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>  
                                    </div>       
                            </div> 
                                 
            </div>  </div>   
        </div>             
      </footer> 
   </form>   
<!-- /.control-sidebar -->
</div>


<!-- Modal modalpassword -->
                <div class="modal fade" id="modalpassword" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu">Password</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                                        <div class="form-group row my-0">
                                            <div class="col-12">    
                                                <div class="card  card-light  ">
                                                <div class="card-body mx-0 px-0">   
                                                     <div class="form-group row my-0"> 
                                                        <div class="col-12">  
                                                             <div class="form-label-group">
                                            	                  <input type="text" name="username" id="username" class="form-control text-sm" placeholder="Nama User" required autofocus autocomplete="off"> 
                                            	                </div>
                                            	                <div class="form-label-group">
                                            	                  <input type="password" name="password" id="password" class="form-control text-sm" placeholder="Password" required autocomplete="off"> 
                                                                    <input id="jenispassword" type="hidden" class="jenispassword form-control form-control-sm border-1 " value=""> 
                                            	                </div>
                                                        </div>        
                                                      </div>   
                                                 </div>                
                                                </div> 
                                            </div>   
                                        </div> 
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-sm" id="bokmodalpassword">OK</button>  
                                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" >Cancel</button>  
                            </div>
                        </div>
                    </div>
                </div>
                
   

<!-- Modal modalvoucherweb -->
                <div class="modal fade" id="modalvoucherweb" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu">Masukkan No Voucher</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                                        <div class="form-group row my-0">
                                            <div class="col-12">    
                                                <div class="card  card-light  ">
                                                <div class="card-body mx-0 px-0">   
                                                     <div class="form-group row my-0"> 
                                                        <div class="col-12">  
                                                            <input id="novoucherweb" type="text" class="novoucherweb form-control form-control-sm border-1 " value=""> 
                                                        </div>        
                                                      </div>   
                                                 </div>                
                                                </div> 
                                            </div>   
                                        </div> 
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-sm" id="bokmodalvoucherweb">OK</button>  
                                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" >Cancel</button>  
                            </div>
                        </div>
                    </div>
                </div>
                
                

	<!-- Modal modaldatateman -->
                <div class="modal fade" id="modaldatateman" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu">Data Teman</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                                        <div class="form-group row my-0">
                                            <div class="col-12">    
                                                <div class="card  card-light  ">
                                                <div class="card-body mx-0 px-0">   
                                                     <div class="form-group row my-0"> 
                                                        <div class="col-12"> 
                                                        
                                                            
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Nama Teman</label> 
                                                         <div class="col-8">
                                                               <select id="teman" name="teman" class="teman form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>  
                                                        </div>  
                                                        </div> 
                                                            
                                                        <div class="form-group row my-0">
                                                         <div class="col-4"></div>
                                                         <div class="col-8"> 
                                                                <!-- <button type="button" class="btn btn-warning btn-sm form-control"  id="bkirimulangpointteman" role="button">Kirim Ulang Point</button> -->  
                                                        </div>  
                                                        </div> 
                                                            
                                                             
                                                            
                                                            
                                                        </div>        
                                                      </div>   
                                                 </div>                
                                                </div> 
                                            </div>   
                                        </div> 
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokmodaldatateman">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>


	<!-- Modal alasan edit -->
                <div class="modal fade" id="modalalasanedit" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu">Alasan Edit</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                                        <div class="form-group row my-0">
                                            <div class="col-12">    
                                                <div class="card  card-light  ">
                                                <div class="card-body mx-0 px-0">   
                                                     <div class="form-group row my-0"> 
                                                        <div class="col-12"> 
                                                            <textarea class="form-control form-control-sm"   id="alasanedit" name="alasanedit" style="height:4em"></textarea> 
                                                        </div>        
                                                      </div>   
                                                 </div>                
                                                </div> 
                                            </div>   
                                        </div> 
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block"   id="bokmodalalasanedit">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>
                
                
	<!-- Modal modalcatatanplanning -->
                <div class="modal fade" id="modalcatatanplanning" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu">Catatan Planning</h4>
                                <button type="button" class="close" data-dismiss="modal" id="btutupcatatanplanning">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                                        <div class="form-group row my-0">
                                            <div class="col-12">    
                                                <div class="card  card-light  ">
                                                <div class="card-body mx-0 px-0">   
                                                     <div class="form-group row my-0"> 
                                                        <div class="col-12"> 
                                                            <textarea class="form-control form-control-sm"   id="rekammedis" name="rekammedis" style="height:4em"></textarea>
                                                                        <input type="hidden" id="untuksave" name="untuksave" value="0">     
                                                        </div>        
                                                      </div>   
                                                 </div>                
                                                </div> 
                                            </div>   
                                        </div> 
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" id="bokmodalcatatanplanning">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                

	<!-- Modal data pasien -->
                <div class="modal fade" id="modaldatapasien" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu">Data Pasien</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">
                                    
                    				<div class="form-group row my-0">
                                        <div class="col-md-12"> <!--begin::Quick Example-->
                                            <div class="card card-primary card-outline mb-4 "> <!--begin::Header-->
                                                <div class="card-body">    
                          
                                                     <div class="form-group row my-0">
                                                        <div class="col-12">    
                                                            <div class="card card-light  "> 
                                                                <div class="card-body mx-0 px-0">  
                                                                   
                                                                     <div class="form-group row my-0">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Tipe Pasien :</label>
                                                                        <div class="col-8">  
                                                                        <div id="pasientipe" class=" col-form-label text-sm font-weight-normal"></div> 
                                                                        <input type="hidden" id="statusmember" name="statusmember" value="0">     
                                                                        </div>        
                                                                      </div> 
                                                                      <div class="form-group row my-0">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Alamat Pasien :</label>
                                                                        <div class="col-8">  
                                                                        <div id="pasienalamat" class=" col-form-label text-sm font-weight-normal"></div>      
                                                                        </div>        
                                                                      </div>
                                                                       <div class="form-group row my-0">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No HP :</label>
                                                                        <div class="col-8">  
                                                                        <div id="pasiennohp" class=" col-form-label text-sm font-weight-normal"></div>      
                                                                        </div>        
                                                                      </div>  
                                                                       <div class="form-group row my-0">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Tanggal Transaksi Akhir :</label>
                                                                        <div class="col-8">  
                                                                        <div id="tgltransaksiakhir" class=" col-form-label text-sm font-weight-normal"></div>      
                                                                        </div>        
                                                                      </div>   
                                                                       <div class="form-group row my-0">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Tanggal Buat :</label>
                                                                        <div class="col-8">  
                                                                        <div id="tglbuat" class=" col-form-label text-sm font-weight-normal"></div>      
                                                                        </div>        
                                                                      </div>   
                                                                       <div class="form-group row my-0">
                                                                           <label class="col-4 col-form-label text-sm px-3 font-weight-normal"></label>
                                                                            <div class="col-8">  
                                                                                <input type="hidden" id="pasienbaru" name="pasienbaru"> 
                                                                                <div id="statuspasienbaru"></div>
                                                                            </div>   
                                                                                
                                                                      </div>  
                                                                      
                                                                      
      
      
                                                                </div>                
                                                            </div> 
                                                        </div>   
                                                    </div>   
                        
                                                         
                                                </div><!-- body --> 
                                            </div><!-- card -->  
                                        </div> <!-- md5 -->    
                                    </div>	 
                					
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokmodaldatapasien">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>
         
       	<!-- Modal PAKET -->
                <div class="modal fade" id="modalPaket" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelModalKu">Masukkan No Paket</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">
                                    
                					<input id="nopaketnya" type="text" class="form-control form-control-sm " value="0">
                					
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="boknopaket">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>
                
                
                	
                	<!-- Modal Pilihan Item untuk Paket -->
                <div class="modal fade" id="modalPilihan" role="dialog"  data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelmodalPilihan">Pilihan Paket</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                					<select id="pilihanpaket" name="pilihanpaket" class="form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                						<input id="pilihanpaketnya" type="hidden" class="form-control form-control-sm " >
                						<input id="keberapa" type="hidden" class="form-control form-control-sm " >
                						<input id="statuspaket" type="hidden" class="form-control form-control-sm " value="0">
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokpilihanpaket">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>    
          
 
 
                	<!-- Modal modalDKK -->
                <div class="modal fade" id="modalDKK" role="dialog" data-backdrop="static"  >
                    <div class="modal-dialog  modal-sm">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelmodalDKK">Pilih DKK</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">  
                						
                				    <div class="form-group row my-0">
                                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Ket DKK</label> 
                                     <div class="col-8">
                                           <select id="dkkwalkin" name="dkkwalkin" class="form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto">
                                                  <option value=''></option>
                                                  <option value='0'>Hanya Beli</option>
                                                  <option value='1'>DKK</option>
                                                  <option value='2'>Walk In</option>  
                                              </select> 
                                    </div>  
                                    </div>  
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokpilihdkk"  name="bokpilihdkk" >OK</button>  
                            </div>
                        </div>
                    </div>
                </div>   
                            
                            
    
                	<!-- Modal bawa kartu or tidak -->
                <div class="modal fade" id="modalkartumember" role="dialog" data-backdrop="static" data-keyboard="false" >
                    <div class="modal-dialog modal-sm ">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="labelmodalkartumember">Konfirmasi</h5>
                            </div>
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">  
                                    <div class="custom-radio radio-lg">
                                        <input class="custom-radio-input"  type="radio" value="1" id="optbawakartu" name="kartu" checked />
                                        <label class="custom-radio-label"  for="optbawakartu">Bawa Kartu</label>
                                    </div>
                                    <div class="custom-radio radio-lg">
                                        <input class="custom-radio-input"  type="radio" value="0" id="opttidakbawakartu" name="kartu" />
                                        <label class="custom-radio-label"  for="opttidakbawakartu">Tidak Bawa</label>
                                    </div> 
                                         
                                </form>  
                            </div> 
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokpilihkartu">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>   
                
     
       
                           
    
                	<!-- TRANSAKSI HARI INI -->
                <div class="modal fade" id="modalriwayathariini" role="dialog" data-backdrop="static">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title">Transaksi Hari Ini</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <!-- Modal Body -->
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-hover">
                                        <thead>
                                        <tr>
                                        <th class="text-sm">No Transaksi</th>
                                        <th class="text-sm">Jam</th>
                                        <th class="text-sm">Pasien</th>
                                        <th class="text-sm text-right">Total</th>
                                        <th class="text-sm"></th>
                                        </tr>
                                        </thead>
                                        <tbody id="riwayathariini-body"></tbody>
                                    </table>
                                </div>
                                <div id="riwayathariini-empty" class="text-center text-muted py-4 d-none">
                                    <i class="fas fa-receipt fa-2x mb-2"></i>
                                    <p class="mb-0">Belum ada transaksi hari ini.</p>
                                </div>
                            </div>

                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                	<!-- PEMBAYARAN -->
                <div class="modal fade" id="modalbayar" role="dialog" data-backdrop="static">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                
                                <h5 class="modal-title" id="labelmodalbayar">Pembayaran</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span> 
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">    
                		            <div class="card card-primary card-outline mb-4"> <!--begin::Header--> 
                                        <div class="row g-4"> <!--begin::Col--> 
                                                <div class="col-md-6"> <!--begin::Quick Example-->
                                                    <div class="card card-primary  "> 
                                                        <div class="card-body mx-0 px-0">  
                                                        
                                                            <div class="card card-light  ">
                                                                <div class="card-header"   role="button">  
                                                                     <div class="form-group row my-0">
                                                                        <h3 class="col-4 px-3 card-title text-sm text-grey font-weight-bold">Tunai</h3> 
                                                                        <div class="col-8">   
                                                                                <div class="input-group" data-target-input="nearest">
                                                                                        <div id="bsetkas" class="input-group-prepend" role="button">
                                                                                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                                        </div> 
                                                                                    <input id="kasjumlah" type="text" class="total form-control form-control-sm numeric " value="0">
                                                                                      
                                                                                </div>  
                                                                        </div>        
                                                                      </div> 
                                                                </div>      
                                                              </div> 
                                                              
                                                              
                                                              <div class="card collapsed-card  card-light  ">
                                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                                     <div class="form-group row my-0">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Debit</label>
                                                                        <div class="col-8">   
                                                                                     <div class="input-group" data-target-input="nearest">
                                                                                          <div id="bsetdebit" class="input-group-prepend" role="button">
                                                                                            <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                                        </div>
                                                                                        <input id="debitjumlah" type="text" class="total form-control form-control-sm numeric" value="0">
                                                                                       
                                                                                      </div > 
                                                                        </div>  
                                                                    </div>   
                                                                </div>
                                                                <div class="card-body mx-0 px-0">  
                                                                      <div class="form-group row my-0 ">
                                                                        
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No</label>
                                                                        <div class="col-8">  
                                                                        <input id="debitno" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>  
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Bank</label>
                                                                        <div class="col-8">  
                                                                        <select id="debitbank" class="bank form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>
                                                                        </div>   
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Nama</label>
                                                                        <div class="col-8">  
                                                                        <input id="debitnama" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>   
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Jenis Kartu</label>
                                                                        <div class="col-8">  
                                                                          <select name="debitjenis" id="debitjenis" class="form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto">
                                                                              <option value=''></option>
                                                                              <option value='DEBIT'>DEBIT</option>
                                                                              <option value='MAESTRO'>MAESTRO</option>
                                                                              <option value='SWITCHING'>SWITCHING</option> 
                                                                          </select>   
                                                                        </div> 
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Bank Lain</label>
                                                                        <div class="col-8">  
                                                                        <input id="debitbanklain" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>  
                                                                    </div> 
                                                                </div>     
                                                                </div>     
                                                              
                                                              
                                                               <div class="card collapsed-card  card-light  ">
                                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                                    <div class="form-group row my-0 "> 
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Kredit</label>
                                                                        <div class="col-8">   
                                                                                 <div class="input-group" data-target-input="nearest">
                                                                                    <div id="bsetkredit" class="input-group-prepend" role="button">
                                                                                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                                    </div>
                                                                                    <input id="kreditjumlah" type="text" class="total form-control form-control-sm numeric" value="0">
                                                                                  </div > 
                                                                                  
                                                                        </div> 
                                                                    </div>     
                                                                        
                                                                </div>
                                                                <div class="card-body mx-0 px-0">        
                                                                      <div class="form-group row my-0 ">
                                                                        
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No</label>
                                                                        <div class="col-8">  
                                                                        <input id="kreditno" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>  
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Bank</label>
                                                                        <div class="col-8">  
                                                                        <select id="kreditbank" class="bank form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>
                                                                        </div>   
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Nama</label>
                                                                        <div class="col-8">  
                                                                        <input id="kreditnama" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>   
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Jenis Kartu</label>
                                                                        <div class="col-8">  
                                                                          <select id="kreditjenis" class="form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto">
                                                                              <option value=''></option>
                                                                              <option value='DEBIT'>DEBIT</option>
                                                                              <option value='MAESTRO'>MAESTRO</option>
                                                                              <option value='SWITCHING'>SWITCHING</option> 
                                                                              <option value='MASTER'>MASTER</option> 
                                                                              <option value='VISA'>VISA</option> 
                                                                          </select>   
                                                                        </div> 
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Bank Lain</label>
                                                                        <div class="col-8">  
                                                                        <input id="kreditbanklain" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>  
                                                                      </div>   
                                                                    </div>  
                                                                  </div>
                                                              
                                                        </div>                
                                                    </div>           
                                                </div>    
                                                <div class="col-md-6"> <!--begin::Quick Example-->
                                                    <div class="card card-primary  "> 
                                                        <div class="card-body mx-0 px-0">   
                                                        
                                                               <div class="card collapsed-card  card-light  ">
                                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                                    <div class="form-group row my-0 ">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Transfer</label>
                                                                        <div class="col-8">   
                                                                                 <div class="input-group" data-target-input="nearest">
                                                                                    <div id="bsettransfer" class="input-group-prepend" role="button">
                                                                                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                                    </div>
                                                                                    <input id="transferjumlah" type="text" class="total form-control form-control-sm numeric" value="0"> 
                                                                                  </div >  
                                                                        </div>  
                                                                    </div>      
                                                                        
                                                                </div>
                                                                <div class="card-body mx-0 px-0">      
                                                                
                                                                       <div class="form-group row my-0 "> 
                                                                         <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No</label>
                                                                        <div class="col-8">  
                                                                        <input id="transferno" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>  
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Bank</label>
                                                                        <div class="col-8">  
                                                                        <select id="transferbank" class="bank form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>
                                                                        </div>   
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Nama</label>
                                                                        <div class="col-8">  
                                                                        <input id="transfernama" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>   
                                                                      </div>   
                                                                </div>   
                                                              </div> 
                                                              
                                                                <div class="card collapsed-card  card-light  ">
                                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                                    <div class="form-group row my-0 ">
                                                                      <label class="col-4 col-form-label text-sm px-3 font-weight-normal">DP</label>
                                                                        <div class="col-8">  
                                                                        <input id="dpjumlah" type="text" class="total form-control form-control-sm numeric" value="0">  
                                                                        </div>  
                                                                    </div>
                                                                </div>
                                                                <div class="card-body mx-0 px-0">   
                                                                       <div class="form-group row my-0 "> 
                                                                        
                                                                        
                                                                         <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No</label>
                                                                        <div class="col-8">  
                                                                                 <div class="input-group" data-target-input="nearest">
                                                                                    <div id="caridp" class="input-group-prepend" role="button">
                                                                                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                                    </div>
                                                                                    <input type="hidden" id="dpid" id="dpid">                    
                                                                                    <input type="text" id="dpno" id="dpno" class="form-control form-control-sm" disabled>
                                                                                  </div>     
                                                                        </div>
                                                                        
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Jenis DP</label>
                                                                        <div class="col-8">  
                                                                        <select id="dpjenis" class="merchant form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>
                                                                        </div>   
                                                                        
                                                                        
                                                                      </div>  
                                                                </div>    
                                                              </div> 
                                                              
                                                                <div class="card collapsed-card  card-light  ">
                                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                                  <h3 class="card-title text-sm text-grey font-weight-bold">Merchant</h3> 
                                                                </div>
                                                                <div class="card-body mx-0 px-0">   
                                                                       <div class="form-group row my-0 ">  
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Merchant</label>
                                                                        <div class="col-8">   
                                                                                 <div class="input-group" data-target-input="nearest">
                                                                                    <div id="bsetmerchant" class="input-group-prepend" role="button">
                                                                                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                                    </div>
                                                                                    <input id="merchantjumlah" type="text" class="total form-control form-control-sm numeric" value="0">
                                                                                  </div >   
                                                                        </div> 
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Jenis</label>
                                                                        <div class="col-8">  
                                                                        <select id="merchantjenis" class="merchant form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>
                                                                        </div>  
                                                                         <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No</label>
                                                                        <div class="col-8">  
                                                                        <input id="merchantno" type="text" class="form-control form-control-sm" value="">    
                                                                        </div>    
                                                                      </div>  
                                                                </div>    
                                                              </div> 
                                                              
                                                              
                                                                <div class="card collapsed-card  card-light  ">
                                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                                  <h3 class="card-title text-sm text-grey font-weight-bold">Voucher</h3> 
                                                                </div>
                                                                <div class="card-body mx-0 px-0">   
                                                                       <div class="form-group row my-0 ">  
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Voucher</label>
                                                                        <div class="col-8">  
                                                                        <input id="voucherjumlah" type="text" class="total form-control form-control-sm numeric" value="0">  
                                                                        </div> 
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Nomer</label>
                                                                        <div class="col-8">   
                                                                               <div class="input-group" data-target-input="nearest">
                                                                                    <div id="carivoucher" class="input-group-prepend" role="button">
                                                                                        <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                                    </div>
                                                                                    <input type="hidden" id="voucherid" id="voucherid">                    
                                                                                    <input type="text" id="voucherno" id="voucherno" class="form-control form-control-sm" disabled>
                                                                                  </div>   
                                                                        </div>   
                                                                      </div>  
                                                                </div>    
                                                              </div> 
                                                              
                                                              <div class="card collapsed-card  card-light  ">
                                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                                  <h3 class="card-title text-sm text-grey font-weight-bold">Piutang Surgery</h3> 
                                                                </div>
                                                                <div class="card-body mx-0 px-0">    
                                                                     <div class="form-group row my-0">
                                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Piutang Surgery</label>
                                                                        <div class="col-8">  
                                                                                <input id="piutangjumlah" type="text" class="total form-control form-control-sm numeric " value="0">
                                                                        </div>        
                                                                      </div> 
                                                                </div>        
                                                              </div> 
                                                             
                                                              
                                                              
                                                              
                                                             
                                                        </div>                
                                                    </div> 
                                                </div>  
                                        </div>   
                                    </div>  
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" id="bokbayar">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>   
                
                
                
                	<!-- DATA LAINNYA -->
                <div class="modal fade" id="modaldatalainnya" role="dialog" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                
                                <h5 class="modal-title" id="labelmodalbayar">Data Lainnya</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span> 
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">   
            		                <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                                        <div class="form-group row my-0">
                                            <div class="col-12">    
                                                <div class="card card-primary  ">
                                                    <div class="card-body mx-0 px-0"> 
                                                    
                                                    
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Catatan</label> 
                                                         <div class="col-8">
                                                               <input id="catatan" type="text" class="form-control form-control-sm" value="">      
                                                        </div>  
                                                        </div> 
                                                        
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Training</label> 
                                                         <div class="col-8">
                                                               <select id="training" name="training" class="karyawan form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                                                        </div>  
                                                        </div> 
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Farmasi</label> 
                                                         <div class="col-8">
                                                               <select id="farmasi" name="farmasi" class="karyawan form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                                                        </div>  
                                                        </div>  
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Farmasi Asisten</label> 
                                                         <div class="col-8">
                                                               <select id="farmasiasisten" name="farmasiasisten" class="karyawan form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                                                        </div>  
                                                        </div>  
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Sales Marketing</label> 
                                                         <div class="col-8">
                                                               <select id="salesmarketing" name="salesmarketing" class="karyawan form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                                                        </div>  
                                                        </div>   
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Klinik Luar</label> 
                                                         <div class="col-8">
                                                               <select id="kliniklain" name="kliniklain" class="klinikluar form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                                                        </div>  
                                                        </div>  
                                                        
                                                    
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Kode Tele</label> 
                                                         <div class="col-8">
                                                               <input id="kodetele" type="text" class="form-control form-control-sm" value="">      
                                                        </div>  
                                                        </div> 
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">ID Medlib</label> 
                                                         <div class="col-8">   
                                                                <input id="idmedlib" type="hidden"  value="">   
                                                                <input id="kodemedlib" type="text" class="form-control form-control-sm kuncitext" value="">  
                                                        </div>  
                                                        </div> 
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">ID PRO</label> 
                                                         <div class="col-8">     
                                                                <input id="lmcidpro" type="text" class="form-control form-control-sm kuncitext" value="">  
                                                        </div>  
                                                        </div> 
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Review Nilai</label> 
                                                         <div class="col-8">  
                                                               <input id="reviewnilai" type="text" class="total form-control form-control-sm numeric " value="0">
                                                        </div>  
                                                        </div> 
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Review Catatan</label> 
                                                         <div class="col-8">
                                                               <input id="reviewcatatan" type="text" class="form-control form-control-sm" value="">      
                                                        </div>  
                                                        </div> 
                                                        
                                                        <div class="form-group row my-0">
                                                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Konsul Saja</label> 
                                                         <div class="col-8">
                                                                <div class="form-group form-check">
                                                                <input type="checkbox" class="form-check-input" id="chkkonsulsaja">
                                                                <label class="form-check-label col-form-label text-sm px-3 font-weight-normal" for="chkkonsulsaja">Konsul Saja</label>
                                                              </div>
                                                        </div>  
                                                        </div> 
                                                        
                                                      
                                              
                                              
                                  
                                  
                                                    </div>                
                                                </div> 
                                            </div>   
                                        </div>   
                                    </div>  
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokdatalainnya">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>   
                               
                               
                
                	<!-- DATA modaldatasurgery -->
                <div class="modal fade" id="modaldatasurgery" role="dialog" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                
                                <h5 class="modal-title" id="labelmodaldatasurgery">Data Surgery</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span> 
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">   
            		                <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                                        <div class="form-group row my-0">
                                            <div class="col-12">    
                                                <div class="card card-primary  ">
                                                    <div class="card-body mx-0 px-0">   
                                                    
                                                           <div class="form-group row my-0">
                             
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No IP</label>
                                                                <div class="col-8">   
                                                                       <div class="input-group" data-target-input="nearest">
                                                                            <input type="hidden" id="surgerydpidu" id="surgerydpidu">                    
                                                                            <input type="text" id="surgerydpno" id="surgerydpno" class="form-control form-control-sm" disabled>
                                                                            <div id="carisurgerydp" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
                                                                          </div>   
                                                                </div>  
                                                                
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Total Transaksi</label>
                                                                <div class="col-8">  
                                                                        <input id="surgerydptotal" type="text" class="total form-control form-control-sm numeric " value="0">
                                                                </div>    
                                                                
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Pembayaran</label>
                                                                <div class="col-8">  
                                                                        <input id="surgerydppembayaran" type="text" class="total form-control form-control-sm numeric " value="0">
                                                                </div>    
                                                                
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Nilai Piutang Dibayar</label>
                                                                <div class="col-8">  
                                                                        <input id="surgerydppiutang" type="text" class="total form-control form-control-sm numeric " value="0">
                                                                </div>
                                                                
                                                                
                                                              </div> 
                          
                              
                                  
                                                    </div>                
                                                </div> 
                                            </div>   
                                        </div>   
                                    </div>  
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokdatasurgery">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>   
                               
                               
               
 	
                	<!-- Modal ITEM -->
                <div class="modal fade" id="modaltambahitem" role="dialog" data-backdrop="static">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelmodaltambahitem">Pilih Produk</h4>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                					<select id="pilihanitem" name="pilihanitem" class="item2 form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto" placeholder='Pilih Item'></select> 
                						<input id="pilihanitemnya" type="hidden" class="form-control form-control-sm " >
                						<input id="itemkeberapa" type="hidden" class="form-control form-control-sm " >
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" data-dismiss="modal" id="bokpilihitem">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>   
                

 	 
                
               
                	<!-- Modal ITEM -->
                <div class="modal fade" id="modaloperator" role="dialog" data-backdrop="static" data-keyboard="false">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="labelmodaloperator">Pilih Operator</h4>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                				
                					<select id="pilihanoperator" name="pilihanoperator" class="operator2 form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                					<input id="txtpilihanoperator" type="hidden" class="form-control form-control-sm " >
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" id="bokpilihoperator">OK</button>
                            </div>
                        </div>
                    </div>
                </div>   
                               
                					               
                               
 	
                	<!-- Modal modaldokter -->
                <div class="modal fade" id="modaldokter" role="dialog"  data-backdrop="static" data-keyboard="false" >
                    <div class="modal-dialog  modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h6 class="modal-title" id="labelmodaldokter">Pilih Dokter</h4>
                               
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                					<select id="pilihandokter" name="pilihandokter" class="dokter2 form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>  
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" id="bokpilihdokter">OK</button>
                            </div>
                        </div>
                    </div>
                </div> 
                
                
                <div class="modal fade" id="modalnoref" role="dialog"  data-backdrop="static" data-keyboard="false"   tabindex="-1" >
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h6 class="modal-title" id="labelmodalnoref">Masukkan No Reff</h4>
                               
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">
                                    
                					<input id="norefnya" type="text" class="form-control form-control-sm " value="0" autofocus>
                					
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" id="boknoref">OK</button>
                            </div>
                        </div>
                    </div>
                </div> 
                
                
                
                <div class="modal" id="modalnoic" role="dialog"  data-backdrop="static" data-keyboard="false"  tabindex="-1" >
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h6 class="modal-title" id="labelmodalnoic" tabindex="-1">Masukkan No IC</h4>
                               
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">
                                    
                					<input id="noicnya" type="text" class="form-control form-control-sm " value="0" autofocus>
                					
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-block" id="boknoic" >OK</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                     

<!-- JS Vendor -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/jquery-ui/jquery-ui.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<? echo base_url('assets/dist/js/adminlte.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/datepicker/bootstrap-datepicker.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input_hidden.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/pos_2.js'); ?>"></script>
</body>
</html>