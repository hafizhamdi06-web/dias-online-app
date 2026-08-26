  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
    <div class="row">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Status</label>                    
      <div class="col-sm-10 mt-1 pt-1">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="aktif">
          <label class="form-check-label text-sm" for="aktif">Aktif</label>
        </div>                    
      </div>
    </div>   
    <div class="row mt-1 d-none">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Judul</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="judul" name="judul" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                                                                                     
    <div class="row mt-1">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Nama</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                
    <div class="row mt-1">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Alias</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="nama2" name="nama2" autocomplete="off">
      </div>
    </div>                                
    <div class="row mt-1">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Link</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="link" name="link" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>
    <div class="row mt-1">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Ukuran</label>                    
      <div class="col-sm-10">
        <select id="ukuran" name="ukuran" class="form-control select2" style="width: 100%">
          <option class="text-sm" value="1">Letter</option>                            
          <option class="text-sm" value="2">Legal</option>                                                  
          <option class="text-sm" value="3">A4</option>                                                
          <option class="text-sm" value="4">A3</option>
        </select>                         
      </div>
    </div>
    <div class="row mt-1">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Orientasi</label>                    
      <div class="col-sm-10">
        <select id="orientasi" name="orientasi" class="form-control select2" style="width: 100%">
          <option class="text-sm" value="1">Portrait</option>                            
          <option class="text-sm" value="2">Landscape</option>                                                  
        </select>                         
      </div>
    </div> 
    <div class="row mt-1">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Margin Kiri</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm numeric" id="marginL" name="marginL" autocomplete="off" value="6">
      </div>
    </div>     
    <div class="row mt-1">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 font-weight-normal">Margin Atas</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm numeric" id="marginT" name="marginT" autocomplete="off" value="6">
      </div>
    </div>         
    <div class="row mt-2">
      <label for="" class="col-sm-2 col-form-label text-sm px-2 py-0 font-weight-normal">Filter</label>                    
      <div class="col-sm-3 pt-1">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fRentangTgl">
          <label class="form-check-label text-sm" for="fRentangTgl">Rentang Tanggal</label>
        </div>                  
      </div>
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fPerTgl">
          <label class="form-check-label text-sm" for="fPerTgl">Per Tanggal</label>
        </div>                            
      </div>
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fKontak">
          <label class="form-check-label text-sm" for="fKontak">Kontak</label>
        </div>                            
      </div>      
    </div>                   
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-3 pt-1">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fAkun">
          <label class="form-check-label text-sm" for="fAkun">Akun</label>
        </div>                  
      </div>
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fSumber">
          <label class="form-check-label text-sm" for="fSumber">Tipe Transaksi</label>
        </div>                            
      </div>
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fItem">
          <label class="form-check-label text-sm" for="fItem">Item</label>
        </div>                            
      </div> 
    </div>   
    <div class="row">
      <div class="col-sm-2"></div>      
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fSaldo0">
          <label class="form-check-label text-sm" for="fSaldo0">Saldo 0</label>
        </div>                            
      </div>
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fGudang">
          <label class="form-check-label text-sm" for="fGudang">Gudang</label>
        </div>                            
      </div>                                        
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fNomor">
          <label class="form-check-label text-sm" for="fNomor">Nomor Transaksi</label>
        </div>                            
      </div>                                              
    </div>     
      
    <div class="row">
      <div class="col-sm-2"></div>      
      <div class="col-sm-3 pt-1">  
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="fNamapt">
          <label class="form-check-label text-sm" for="fNamapt">Nama PT</label>
        </div>                            
      </div>                                             
    </div>  

    <div style='clear:both'></div>
  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
              <button type="button" id="submit" name='submit' class="btn btn-primary btn-sm">Simpan</button>
          </div>
      </div>                
  </div>
</form>                            
<script src="<? echo app_url('assets/dist/js/modul/administrator/form-report-060626.js'); ?>"></script>     