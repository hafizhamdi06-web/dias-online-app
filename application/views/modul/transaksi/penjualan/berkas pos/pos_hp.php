<body id="<?= $id; ?>" class="layout-fixed bg-transparent overflow-hidden" data-panel-auto-height-mode="height"> 
  <!-- Custom CSS -->  
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/transaksi-page.css');?>">  

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

  <div class="content-wrapper tab-wrap mx-0"  style="width:100%">
    <div class="content-header bg-white px-2 py-3 position-fixed w-100">
      <div class="row">
      <span class="text-md text-olive  pl-3"><?= $page_caption;?></span>                
      <ul class="navbar-nav">
        <li class="nav-item dropdown d-sm-inline-block">
          <a href="#" class="nav-link my-0 py-0 mx-2" tabindex="-1" data-toggle="dropdown">
            <i class="fas fa-caret-down px-2 text-olive text-lg"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-sm dropdown-menu-left"> 
            <a id="bTable" href="#" class="dropdown-item text-sm"><i class="fas fa-folder-open text-gray"></i>
            <span class="ml-1">Data <?= $page_caption;?></span></a> 
          </div>        
        </li>
      </ul>  
      </div>
      <div class="row bg-white">
          <div class="btn-group">          
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
            <a id="bsave" class="btn btn-app btn-step1" title="Simpan">
              <i class="fas fa-save" title="Simpan"></i>
            </a>                     
            <a id="bcancel" class="btn btn-app btn-step1" title="Batal">
              <i class="fas fa-times" title="Batal"></i>
            </a>        
            </div>   
      
      </div> 
      
    </div>

    <form id="form-<?= $id; ?>" class="form-horizontal">
    <input type="hidden" id="id" name="id" value="">
    <input type="hidden" id="status" name="status">
    <input type="hidden" class="noclear" id="multidivisi" name="multidivisi" value="<?= $multidivisi ?>">
    <input type="hidden" class="noclear" id="multiproyek" name="multiproyek" value="<?= $multiproyek ?>">
    <input type="hidden" class="noclear" id="multisatuan" name="multisatuan" value="<?= $multisatuan ?>">
    <input type="hidden" class="noclear" id="multikurs" name="multikurs" value="<?= $multikurs ?>">        
    <input type="hidden" class="noclear" id="decimalqty" name="decimalqty" value="<?= $decimalqty ?>">         
    <input type="hidden" class="noclear" id="cabang" name="cabang" value="<? echo @$_SESSION['cabang']; ?>">  
    
    
    

     
    
    
    <section class="content" style="margin-top:100px">   
      <div class="container-fluid">  
        <div class="row g-4"> <!--begin::Col-->
         <div class="col-md-5"> <!--begin::Quick Example-->
            <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
               <div class="card-body">            
                 <div class="form-group row my-0">
                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No Transaksi</label>
                    <div class="col-8">
                        <input type="text" id="nomor" name="nomor" class="form-control form-control-sm" placeholder="New Trans"   disabled>  
                        <input type="hidden" class="noclear" id="idpaket" name="idpaket" value="">        
                        <input type="hidden" class="noclear" id="nopaketheader" name="nopaketheader" value=""> 
                        <input type="hidden" class="noclear" id="idpromo" name="idpromo" value="">      
                    </div>          
                  </div> 
            
                 <div class="form-group row my-0">
                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Tanggal *</label>
                    <div class="col-8">
                          <div class="input-group date">
                            <input type="text" id="tgl" name="tgl" class="form-control form-control-sm datepicker" autocomplete="off">
                            <div id="dTgl" class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                            </div>
                          </div>   
                    </div>          
                  </div>
          
                   <div class="form-group row my-0">
                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Karyawan *</label> 
                     <div class="col-8">
                          <div class="input-group" data-target-input="nearest">
                            <input type="hidden" id="idsalesman" name="idsalesman">                    
                            <input type="text" id="salesman" name="salesman" class="form-control form-control-sm" disabled>
                            <div id="carisalesman" class="input-group-append" role="button">
                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                            </div>
                          </div>  
                    </div>  
                  </div> 
             

                        <div class="form-group row my-0">
                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Ket DKK</label> 
                         <div class="col-8">
                               <select id="dkkwalkin" class="form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto">
                                      <option value=''></option>
                                      <option value='0'>Hanya Beli</option>
                                      <option value='1'>DKK</option>
                                      <option value='2'>Walk In</option>  
                                  </select> 
                        </div>  
                        </div>   
          
                        <div class="form-group row my-0">
                        <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Pasien *</label>
                        <div class="col-8">
                              <div class="input-group" data-target-input="nearest">
                                <input type="hidden" id="idkontak" name="idkontak"> 
                                <input type="hidden" id="kontaktipe" name="kontaktipe">   
                                <input type="hidden" id="kontak" name="kontak">                  
                                <input type="text" id="namakontak" name="namakontak" class="form-control form-control-sm" readonly>
                                <div id="carikontak" class="input-group-append" role="button">
                                    <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                </div>
                              </div>                
                        </div>  
                      </div>
           
 
          
                   <div class="form-group row my-0">
                    <div class="col-12">    
                           <div class="card collapsed-card card-light  ">
                            <div class="card-header" data-card-widget="collapse" role="button">
                              <h3 class="card-title text-sm font-weight-bold">Detail Data Pasien</h3>
                              <div class="card-tools py-0 my-0">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body mx-0 px-0">  
                               
                                 <div class="form-group row my-0">
                                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Tipe Pasien :</label>
                                    <div class="col-8">  
                                    <div id="pasientipe" class=" col-form-label text-sm font-weight-normal"></div>    
                                    </div>        
                                  </div> 
                                  <div class="form-group row my-0">
                                    <label class="col-4 col-form-label text-sm px-3 font-weight-normal">ID Pasien :</label>
                                    <div class="col-8">  
                                    <div id="pasienid" class=" col-form-label text-sm font-weight-normal"></div>    
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
                                 </div>                
                                </div> 
                    </div>   
                    </div>   
        
                        <div class="form-group row my-0">
                        <div class="col-12">    
                           <div class="card collapsed-card card-light  ">
                            <div class="card-header" data-card-widget="collapse" role="button">
                              <h3 class="card-title text-sm font-weight-bold">Catatan Planning</h3>
                              <div class="card-tools py-0 my-0">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md"></i>
                                </button>
                              </div>
                            </div>
                            <div class="card-body mx-0 px-0">   
                                 <div class="form-group row my-0"> 
                                    <div class="col-12"> 
                                            <textarea class="form-control form-control-sm"   id="rekammedis" name="rekammedis" style="height:4em"></textarea>
                                    </div>        
                                  </div>   
                             </div>                
                            </div> 
                    </div>   
                </div> 
        
                        <div class="form-group row my-0">
                    <div class="col-12">    
                           <div class="card collapsed-card card-light  ">
                                <div class="card-header" data-card-widget="collapse" role="button">
                                  <h3 class="card-title text-sm font-weight-bold">Data Lainnya</h3>
                                  <div class="card-tools py-0 my-0">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md"></i>
                                    </button>
                                  </div>
                                </div>
                                <div class="card-body mx-0 px-0">   
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
                                  
                                </div>                
                            </div> 
                        </div>   
                    </div>  

                        <div class="card collapsed-card  card-light  ">
                        <div class="card-header" data-card-widget="collapse" role="button">
                          <h3 class="card-title text-sm text-grey font-weight-bold">Data Surgery</h3> 
                        </div>
                        <div class="card-body mx-0 px-0">    
                             <div class="form-group row my-0">
                                 
                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No IP</label>
                                <div class="col-8">   
                                       <div class="input-group" data-target-input="nearest">
                                            <input type="text" id="surgerydpidu" id="surgerydpidu">                    
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
        
                        <div class="form-group row my-0">        
                            <div class="col-12">  
                            <div class="btn-group" role="group" aria-label="Basic example"> 
                          
                                <button type="button" id="bpaket" class="btn btn-primary btn-step1 text-sm btn-lg" role="button"><i class="fa fa-plus px-2"></i>Paket</button>
                                <button type="button" id="bpromo" class="btn btn-secondary btn-step1 text-sm btn-lg" role="button"><i class="fa fa-plus px-2"></i>Promo</button>
                                <button type="button" id="bpro" class="btn btn-success btn-step1 text-sm btn-lg" role="button"><i class="fa fa-plus px-2"></i>PRO</button>
                                <button type="button" id="bweb" class="btn btn-danger btn-step1 text-sm btn-lg" role="button"><i class="fa fa-plus px-2"></i>Medlib</button>  
        
                            </div>
                        </div>       
                        </div>  
                 </div><!-- body --> 
            </div><!-- card --> 
                     
            <!--begin::Quick Example-->
            <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
                    <div class="form-group row my-0  d-none">                                                        
                            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Total Tanpa DP</label>
                            <div class="col-8">  
                                <input id="totaltanpadp" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
                            </div>       
                    </div> 
                     <div class="form-group row my-0">                                                        
                            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Total Transaksi</label>
                            <div class="col-8">  
                                <input id="tsubtotal" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
                            </div>       
                    </div>   
                      <div class="form-group row my-0">                                                        
                            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Total Bayar</label>
                            <div class="col-8">  
                                <input id="totalbayar" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>
                            </div>       
                    </div>      
                      <div class="form-group row my-0">                                                        
                            <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Sisa</label>
                            <div class="col-8">  
                                <input id="totalsisa" type="text" class="total form-control form-control-sm numeric border-0" value="0" disabled>  
                            </div>       
                    </div> 
            
            </div>    
            
            <!--begin::Quick Example-->
            <div class="card card-primary card-outline mb-4"> <!--begin::Header-->
            
            
                    <div class="form-group row my-0">
                            
                            <div class="col-6">    
                                               <div class="card collapsed-card  card-success  ">
                                                <div class="card-header" data-card-widget="collapse" role="button">
                                                  <h3 class="card-title text-sm text-white font-weight-bold">Detail Pembayaran</h3>
                                                  <div class="card-tools py-0 my-0">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-white"></i>
                                                    </button>
                                                  </div>
                                                </div>
                                                <div class="card-body mx-0 px-0">  
                                                
                                                    <div class="card collapsed-card  card-light  ">
                                                        <div class="card-header" data-card-widget="collapse" role="button">
                                                          <h3 class="card-title text-sm text-grey font-weight-bold">Kas</h3> 
                                                        </div>
                                                        <div class="card-body mx-0 px-0">    
                                                             <div class="form-group row my-0">
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Tunai</label>
                                                                <div class="col-8">   
                                                                         <div class="input-group" data-target-input="nearest">
                                                                            <input id="kasjumlah" type="text" class="total form-control form-control-sm numeric " value="0">
                                                                            <div id="bsetkas" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
                                                                          </div 
                                                                </div>        
                                                              </div> 
                                                        </div>        
                                                      </div> 
                                                      
                                                      
                                                      <div class="card collapsed-card  card-light  ">
                                                        <div class="card-header" data-card-widget="collapse" role="button">
                                                          <h3 class="card-title text-sm text-grey font-weight-bold">Debit</h3> 
                                                        </div>
                                                        <div class="card-body mx-0 px-0">  
                                                              <div class="form-group row my-0 ">
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Debit</label>
                                                                <div class="col-8">   
                                                                         <div class="input-group" data-target-input="nearest">
                                                                            <input id="debitjumlah" type="text" class="total form-control form-control-sm numeric" value="0">
                                                                            <div id="bsetdebit" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
                                                                          </div > 
                                                                </div>  
                                                                
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
                                                          <h3 class="card-title text-sm text-grey font-weight-bold">Kredit</h3> 
                                                        </div>
                                                        <div class="card-body mx-0 px-0">        
                                                              <div class="form-group row my-0 ">
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Kredit</label>
                                                                <div class="col-8">   
                                                                         <div class="input-group" data-target-input="nearest">
                                                                            <input id="kreditjumlah" type="text" class="total form-control form-control-sm numeric" value="0">
                                                                            <div id="bsetkredit" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
                                                                          </div > 
                                                                          
                                                                </div> 
                                                                
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
                                                      
                                                       <div class="card collapsed-card  card-light  ">
                                                        <div class="card-header" data-card-widget="collapse" role="button">
                                                          <h3 class="card-title text-sm text-grey font-weight-bold">Transfer</h3> 
                                                        </div>
                                                        <div class="card-body mx-0 px-0">      
                                                        
                    </div>  
                    <div class="col-6">        
                                                               <div class="form-group row my-0 ">
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Transfer</label>
                                                                <div class="col-8">   
                                                                         <div class="input-group" data-target-input="nearest">
                                                                            <input id="transferjumlah" type="text" class="total form-control form-control-sm numeric" value="0"> 
                                                                            <div id="bsettransfer" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
                                                                          </div >  
                                                                </div>  
                                                                
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
                                                          <h3 class="card-title text-sm text-grey font-weight-bold">DP</h3> 
                                                        </div>
                                                        <div class="card-body mx-0 px-0">   
                                                               <div class="form-group row my-0 "> 
                                                                
                                                                
                                                                 <label class="col-4 col-form-label text-sm px-3 font-weight-normal">No</label>
                                                                <div class="col-8">  
                                                                         <div class="input-group" data-target-input="nearest">
                                                                            <input type="hidden" id="dpid" id="dpid">                    
                                                                            <input type="text" id="dpno" id="dpno" class="form-control form-control-sm" disabled>
                                                                            <div id="caridp" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
                                                                          </div>     
                                                                </div>
                                                                
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">Jenis DP</label>
                                                                <div class="col-8">  
                                                                <select id="dpjenis" class="merchant form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select>
                                                                </div>   
                                                                <label class="col-4 col-form-label text-sm px-3 font-weight-normal">DP</label>
                                                                <div class="col-8">  
                                                                <input id="dpjumlah" type="text" class="total form-control form-control-sm numeric" value="0">  
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
                                                                            <input id="merchantjumlah" type="text" class="total form-control form-control-sm numeric" value="0">
                                                                            <div id="bsetmerchant" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
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
                                                                            <input type="hidden" id="voucherid" id="voucherid">                    
                                                                            <input type="text" id="voucherno" id="voucherno" class="form-control form-control-sm" disabled>
                                                                            <div id="carivoucher" class="input-group-append" role="button">
                                                                                <div class="input-group-text"><i class="fa fa-ellipsis-h"></i></div>
                                                                            </div>
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
            </div>          
    
            </div> <!-- md6 -->    

            
            <div class="col-md-7"> <!--begin::Quick Example-->
                <div class="card card-primary card-outline mb-4"> <!--begin::Header-->    
                        
                        <div class="card-body card-outline-tabs-body">
                          <div class="tab-content">
                            <div class="row">             
                            <div class="table-responsive pt-0" tabindex="-1">
                              <table id="tdetil" class="table table-hover table-sm table-transaksi">
                                <div data-spy="scroll" data-target="#navbar-example2" data-offset="0"> 
                                <tbody>
                                </tbody>
                                </div>
                                <tfoot>
                                </tfoot>
                              </table>
                            <button type="button" id="baddrow" class="btn btn-primary btn-step1 text-sm mb-2"><i class="fa fa-plus px-2"></i>Tambah Data</button>
                            <span id="loader-detil" class="ml-2 text-sm d-none"><i class="fas fa-spinner fa-spin mx-2"></i>loading item data...</span>
                            </div>
                            </div>                
                          </div>
                        </div>
                     
                     
                    
            </div><!-- card --> 
        </div> <!-- md6 -->   
                
                
                
             
                	
                
                
            </div> <!-- row -->  
        </div> <!-- <div class="container-fluid pt-4"> -->  
        
        
        
        
 

</div>
    </section> 
</form>
<!-- /.control-sidebar -->




         
       	<!-- Modal -->
                <div class="modal fade" id="modalPaket" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                                <h4 class="modal-title" id="labelModalKu">Masukkan No Paket</h4>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form">
                                    
                					<input id="nopaketnya" type="text" class="form-control form-control-sm " value="0">
                					
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="boknopaket">OK</button>  
                            </div>
                        </div>
                    </div>
                </div>
                
                
                	
                	<!-- Modal -->
                <div class="modal fade" id="modalPilihan" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">
                                    <span aria-hidden="true">&times;</span>
                                    <span class="sr-only">Tutup</span>
                                </button>
                                <h4 class="modal-title" id="labelmodalPilihan">Pilihan Paket</h4>
                            </div>
                
                            <!-- Modal Body -->
                            <div class="modal-body">
                                <form role="form"> 
                					<select id="pilihanpaket" name="pilihanpaket" class="form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                						<input id="pilihanpaketnya" type="hidden" class="form-control form-control-sm " >
                						<input id="keberapa" type="hidden" class="form-control form-control-sm " >
                                </form>
                            </div>
                
                            <!-- Modal Footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" id="bokpilihanpaket">OK</button>  
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
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/pos_hp.js'); ?>"></script>
</body>
</html>