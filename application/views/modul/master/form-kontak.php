<form class='form-horizontal' method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body my-0" style="padding-top:15px;padding-bottom:0px;">
<div class="row">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode</label>                    
  <div class="col-sm-6">
    <input type="search" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
  </div>
</div>                              
<div class="row mt-0">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama</label>                    
  <div class="col-sm-9">
    <input type="search" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
  </div>
</div> 
<div class="row mt-0">
  <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kategori</label>                    
  <div class="col-sm-6">
    <select id="kategori" name="kategori" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto">
    </select>                         
  </div>
</div>                           
<div class="row mt-3">
<div class="col-sm-12">
    <div id="tabKontak" class="card card-primary card-outline card-outline-tabs" style="box-shadow: none;">
      <div class="card-header card-header-sm p-0">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link text-sm active" id="btn-tab-alamat" data-toggle="pill" href="#tab-alamat" role="tab" aria-controls="tab-alamat" aria-selected="true">Alamat</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-sm" id="btn-tab-transaksi" data-toggle="pill" href="#tab-transaksi" role="tab" aria-controls="tab-transaksi" aria-selected="false">Transaksi</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-sm" id="btn-tab-lain" data-toggle="pill" href="#tab-lain" role="tab" aria-controls="tab-lain" aria-selected="false">Lain-Lain</a>
          </li>                      
          <li class="nav-item">
            <a class="nav-link text-sm" id="btn-tab-person" data-toggle="pill" href="#tab-person" role="tab" aria-controls="tab-person" aria-selected="false">Kontak Person</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-sm" id="btn-tab-kontaksa" data-toggle="pill" href="#tab-kontaksa" role="tab" aria-controls="tab-kontaksa" aria-selected="false">Saldo Awal</a>
          </li>                                                      
        </ul>
      </div>
      <div class="card-body card-outline-tabs-body mt-0 pt-1 pb-0 mb-0 px-0 mx-0">
        <div class="tab-content">
          <div class="tab-pane fade active show text-sm" id="tab-alamat" role="tabpanel" aria-labelledby="btn-tab-alamat">
            <!-- Tab Alamat -->
            <div class="card card-white card-outline card-tabs" style="box-shadow: none">
                <div class="card-body px-0">
                <div class="tab-content">
                  <div class="tab-pane fade active show text-sm py-0 my-0 mx-2" id="tab-a1" role="tabpanel" aria-labelledby="btn-tab-a1">
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Alamat</label>                    
                      <div class="col-sm-10">
                        <textarea id="a1alamat" name="a1alamat" class="form-control form-control-sm" rows="2" placeholder=""></textarea>
                      </div>
                    </div>
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kota</label>                    
                      <div class="col-sm-4">
                        <select id="a1kota" name="a1kota" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode Pos</label>                    
                      <div class="col-sm-4">
                        <input id="a1kodepos" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>                                  
                    </div>                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Provinsi</label>                    
                      <div class="col-sm-4">
                        <select id="a1prov" name="a1prov" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Negara</label>                    
                      <div class="col-sm-4">
                        <select id="a1negara" name="a1negara" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>                                  
                    </div>                                                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Telepon</label>                    
                      <div class="col-sm-6">
                        <input id="a1telp" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Faks</label>                    
                      <div class="col-sm-6">
                        <input id="a1faks" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Email</label>                    
                      <div class="col-sm-6">
                        <input id="a1email" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                                                                                                
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kontak</label>                    
                      <div class="col-sm-6">
                        <input id="a1kontak" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                    
                  </div>
                  <div class="tab-pane fade text-sm mx-2" id="tab-a2" role="tabpanel" aria-labelledby="btn-tab-a2">
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Alamat</label>                    
                      <div class="col-sm-10">
                        <textarea id="a2alamat" name="a2alamat" class="form-control form-control-sm" rows="2" placeholder=""></textarea>
                      </div>
                    </div>
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kota</label>                    
                      <div class="col-sm-4">
                        <select id="a2kota" name="a2kota" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode Pos</label>                    
                      <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>                                  
                    </div>                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Provinsi</label>                    
                      <div class="col-sm-4">
                        <select id="a2prov" name="a2prov" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Negara</label>                    
                      <div class="col-sm-4">
                        <select id="a2negara" name="a2negara" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>                                  
                    </div>                                                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Telepon</label>                    
                      <div class="col-sm-6">
                        <input id="a2telp" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Faks</label>                    
                      <div class="col-sm-6">
                        <input id="a2faks" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Email</label>                    
                      <div class="col-sm-6">
                        <input id="a2email" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                                                                                                
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kontak</label>                    
                      <div class="col-sm-6">
                        <input id="a2kontak" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                    
                  </div>
                  <div class="tab-pane fade text-sm mx-2" id="tab-a3" role="tabpanel" aria-labelledby="btn-tab-a3">
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Alamat</label>                    
                      <div class="col-sm-10">
                        <textarea id="a3alamat" name="a3alamat" class="form-control form-control-sm" rows="2" placeholder=""></textarea>
                      </div>
                    </div>
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kota</label>                    
                      <div class="col-sm-4">
                        <select id="a3kota" name="a3kota" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode Pos</label>                    
                      <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>                                  
                    </div>                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Provinsi</label>                    
                      <div class="col-sm-4">
                        <select id="a3prov" name="a3prov" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Negara</label>                    
                      <div class="col-sm-4">
                        <select id="a3negara" name="a3negara" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>                                  
                    </div>                                                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Telepon</label>                    
                      <div class="col-sm-6">
                        <input id="a3telp" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Faks</label>                    
                      <div class="col-sm-6">
                        <input id="a3faks" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Email</label>                    
                      <div class="col-sm-6">
                        <input id="a3email" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                                                                                                
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kontak</label>                    
                      <div class="col-sm-6">
                        <input id="a3kontak" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                    
                  </div>
                  <div class="tab-pane fade text-sm mx-2" id="tab-a4" role="tabpanel" aria-labelledby="btn-tab-a4">
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Alamat</label>                    
                      <div class="col-sm-10">
                        <textarea id="a4alamat" name="a4alamat" class="form-control form-control-sm" rows="2" placeholder=""></textarea>
                      </div>
                    </div>
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kota</label>                    
                      <div class="col-sm-4">
                        <select id="a4kota" name="a4kota" class="form-control select2" style="width: 100%">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode Pos</label>                    
                      <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>                                  
                    </div>                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Provinsi</label>                    
                      <div class="col-sm-4">
                        <select id="a4prov" name="a4prov" class="form-control select2">
                        </select>                         
                      </div>
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Negara</label>                    
                      <div class="col-sm-4">
                        <select id="a4negara" name="a4negara" class="form-control select2">
                        </select>                         
                      </div>                                  
                    </div>                                                
                    <div class="row mt-1">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Telepon</label>                    
                      <div class="col-sm-6">
                        <input id="a4telp" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Faks</label>                    
                      <div class="col-sm-6">
                        <input id="a4faks" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Email</label>                    
                      <div class="col-sm-6">
                        <input id="a4email" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                                                                                                
                    <div class="row mt-0">
                      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kontak</label>                    
                      <div class="col-sm-6">
                        <input id="a4kontak" type="text" class="form-control form-control-sm" autocomplete="off" placeholder="">
                      </div>
                    </div>                    
                  </div>                              
                </div>
              </div>
              <div class="card-header px-0 py-0">
                <ul class="nav nav-tabs nav-justified py-0 my-0" id="tabs-alamat" role="tablist">
                  <li class="nav-item py-0 my-0">
                    <a class="nav-link text-sm active" id="btn-tab-a1" data-toggle="pill" href="#tab-a1" role="tab" aria-controls="tab-alamat" aria-selected="true">Alamat<br/>Tagihan</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-sm" id="btn-tab-a2" data-toggle="pill" href="#tab-a2" role="tab" aria-controls="tab-a2" aria-selected="false">Alamat<br/>Pengantaran</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link text-sm" id="btn-tab-a3" data-toggle="pill" href="#tab-a3" role="tab" aria-controls="tab-a3" aria-selected="false">Alamat<br/>Gudang</a>
                  </li>                      
                  <li class="nav-item">
                    <a class="nav-link text-sm" id="btn-tab-a4" data-toggle="pill" href="#tab-a4" role="tab" aria-controls="tab-a4" aria-selected="false">Alamat<br/>Pajak</a>
                  </li>                                            
                </ul>
              </div>                                            
            </div>
            <!-- ./Tab Alamat -->                        
          </div>
          <div class="tab-pane fade text-sm mx-2" id="tab-transaksi" role="tabpanel" aria-labelledby="btn-tab-transaksi">
            <!-- Tab Transaksi -->
            <div class="row pt-2">
              <label for="" class="col-sm-6 col-form-label text-md text-secondary">Pembelian</label>                    
              <label for="" class="col-sm-6 col-form-label text-md text-secondary">Penjualan</label>
            </div>
            <div class="row mt-0">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Batas Hutang</label>  
              <div class="col-sm-3">
                <input id="batashutang" type="text" class="form-control form-control-sm numeric" autocomplete="off" value="0">
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Batas Piutang</label>
              <div class="col-sm-3">
                <input id="bataspiutang" type="text" class="form-control form-control-sm numeric" autocomplete="off" value="0">
              </div>                          
            </div>                        
            <div class="row mt-0">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Termin Beli</label>  
              <div class="col-sm-3">
                <select id="terminbeli" name="terminbeli" class="form-control select2">
                </select>                         
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Termin Jual</label>
              <div class="col-sm-3">
                <select id="terminjual" name="terminjual" class="form-control select2">
                </select>                         
              </div>                          
            </div> 
            <div class="row mt-0">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Bag Pembelian</label>  
              <div class="col-sm-3">
                <select id="bagbeli" name="bagbeli" class="form-control select2">
                </select>                         
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Bag Penjualan</label>
              <div class="col-sm-3">
                <select id="bagjual" name="bagjual" class="form-control select2">
                </select>                         
              </div>                          
            </div>                                                                                                             
            <div class="row mt-0">
              <div class="col-sm-6">
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Bag Penagihan</label>
              <div class="col-sm-3">
                <select id="bagtagih" name="bagtagih" class="form-control select2">
                </select>                         
              </div>                          
            </div>                                                                                                             
            <div class="row mt-0">
              <div class="col-sm-6">
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Level Harga</label>
              <div class="col-sm-3">
                <select id="lvlharga" name="lvlharga" class="form-control select2">     
                  <option value="null">&nbsp;</option>                             
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>                                                      
                </select>                         
              </div>                          
            </div>                                                                                                             
            <div class="row mt-0">
              <div class="col-sm-6">
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Diskon</label>
              <div class="col-sm-3">
                <input id="diskonjual" type="text" class="form-control form-control-sm numeric" autocomplete="off" value="0">
              </div>                          
            </div>                                                                                                             
            <div class="row mt-2">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">NPWP</label>
              <div class="col-sm-9">
                <input id="npwp" type="text" class="form-control form-control-sm" placeholder="">                            
              </div>                          
            </div>
            <div class="row mt-0 pb-4">
              <div class="col-sm-3">
              </div>       
              <div class="col-sm-6">                                 
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="chkpkp">
                  <label class="form-check-label text-sm" for="chkpkp">PKP</label>
                </div>
              </div>
            </div>                                                                                                              
            <!-- ./Tab Transaksi -->                        
          </div>
          <div class="tab-pane fade text-sm mx-2" id="tab-lain" role="tabpanel" aria-labelledby="btn-tab-lain">
            <!-- Tab Lain -->
            <div class="row pt-4">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Jenis Kelamin</label>  
              <div class="col-sm-3">
                <select id="kelamin" name="kelamin" class="form-control select2">
                  <option value="0">&nbsp;</option>
                  <option value="1">Wanita</option>
                  <option value="2">Laki-Laki</option>                              
                </select>                         
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tgl Kontrak</label>
              <div class="col-sm-3">
                <div class="input-group date">
                  <input id="tglkontrak" type="text" class="form-control form-control-sm datepicker">
                  <div id="bTglKontrak" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>                
              </div>                          
            </div>                        
            <div class="row mt-0">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Mata Uang</label>  
              <div class="col-sm-3">
                <select id="matauang" name="matauang" class="form-control select2">
                </select>                         
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tgl Lahir</label>
              <div class="col-sm-3">
                <div class="input-group date">
                  <input id="tgllahir" type="text" class="form-control form-control-sm datepicker">
                  <div id="bTglLahir" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>                
              </div>                          
            </div> 
            <div class="row mt-0">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Bank</label>  
              <div class="col-sm-3">
                <select id="bank" name="bank" class="form-control select2">
                </select>                         
              </div>
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kelompok</label>
              <div class="col-sm-3">
                <select id="lainkelompok" name="lainkelompok" class="form-control select2">
                </select>                         
              </div>                          
            </div>                                                                                                             
            <div class="row mt-0">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nomor A/C</label>
              <div class="col-sm-3">
                <input id="noakunbank" type="text" class="form-control form-control-sm" placeholder="">                            
              </div>                                                    
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Komisi</label>
              <div class="col-sm-3">
                <input id="komisi" type="text" class="form-control form-control-sm numeric" autocomplete="off">                            
              </div>                                                    
            </div>                                                                                                             
            <div class="row mt-0 pb-4">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Rekening</label>
              <div class="col-sm-3">
                <input id="namarek" type="text" class="form-control form-control-sm" placeholder="">                            
              </div>                                                    
            </div>                                                                                                             
            <div class="row mt-0 pb-4 d-none">
              <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Jns Kiriman</label>
              <div class="col-sm-3">
                <input type="text" class="form-control form-control-sm" placeholder="">                            
              </div>                          
            </div>                                                                                                                                                                                     
            <!-- ./Tab Lain -->                        
          </div>                      
          <div class="tab-pane fade text-sm" id="tab-person" role="tabpanel" aria-labelledby="btn-tab-person">
            <div class="row mx-2 mt-2">
              <div class="col-sm-12 text-center">
                Fitur kontak person belum tersedia.
              </div>
            </div>
            <div style='clear:both'></div>
          </div>                      
          <div class="tab-pane fade text-sm" id="tab-kontaksa" role="tabpanel" aria-labelledby="btn-tab-kontaksa">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header card-header-sm p-0 border-bottom-0">
              </div>
              <div class="card-body card-outline-tabs-body px-0 mx-0 py-0 mt-2 w-100">
                <div class="tab-content">
                  <div class="table-responsive" tabindex="-1" style="border:1px solid #dee2e6;height:calc(100vh - 420px);overflow: auto;">
                        <table id="tsaldo" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-light">
                            <tr>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 100px">Nomor #</th>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 100px">Tanggal</th>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 80px">Termin</th>
                              <th class="text-sm text-label text-right px-1 border-0" style="width: 120px">Jumlah</th>
                              <th class="text-sm text-label text-left px-1 border-0" style="width: 200px">Catatan</th>
                              <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                  </div>
                  <div class="row mt-2 px-2">
                      <button type="button" id="baddrow" class="btn btn-light text-sm">
                          <i class="fa fa-plus px-1 text-primary"></i> Tambah
                      </button>
                  </div>
                </div>
              </div>
            </div>  
            <div style='clear:both'></div>
          </div>                                
        </div>
      </div>
      <!-- /.card -->
    </div>
</div>
</div>
</div>
<div class="modal-footer pt-0 mt-0">
    <div class="form-group">
        <div class="col-sm-offset-3">
            <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
            <button type="button" id="submit" name='submit' class="btn btn-primary btn-sm">Simpan</button>
        </div>
    </div>                
</div>
</form>

<script src="<? echo app_url('assets/dist/js/modul/master/form-kontak.js'); ?>"></script> 