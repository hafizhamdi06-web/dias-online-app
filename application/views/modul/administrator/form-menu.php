  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
    <div class="row px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Tipe</label>                    
      <div class="col-sm-10">
        <select id="tipe" name="tipe" class="form-control select2" style="width: 100%">
          <option class="text-sm" value="1">Laporan</option>
          <option class="text-sm" value="2" selected>Transaksi</option>                                                  
          <option class="text-sm" value="3">Master Data</option>
          <option class="text-sm" value="4">Administrator</option>
        </select>                         
      </div>
    </div>
    <div id="namaL" class="row mt-1 px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Nama</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                
    <div id="urutanL" class="row px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Urutan</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm numeric-single" autocomplete="off" placeholder="0" id="urutan">
      </div>
    </div>                                
    <div id="keteranganL" class="row px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Keterangan</label>                    
      <div class="col-sm-10">
        <textarea class="form-control text-sm" id="keterangan" name="keterangan" style="resize:none;height: 4em;"></textarea>
      </div>
    </div>
    <div id="indukL" class="row mt-1 px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Induk</label>                    
      <div class="col-sm-10">
        <select id="induk" name="induk" class="form-control select2" style="width: 100%">
        </select>                         
      </div>
    </div>
    <div id="linkL" class="row mt-1 px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Link</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="link" name="link" autocomplete="off">
      </div>
    </div>                      
    <div id="captionL" class="row px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Caption</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="caption1" name="caption1" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                      
    <div id="iconL" class="row px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Icon</label>                    
      <div class="col-sm-10">
        <input type="text" class="form-control form-control-sm" id="icon" name="icon" autocomplete="off">
      </div>
    </div>                            
    <div id="laporanL" class="row px-2">
      <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">File Laporan</label>                    
      <div class="col-sm-10">
        <select id="laporan" name="laporan" class="form-control select2" style="width: 100%">
        </select>                         
      </div>
    </div> 
    <div class="row px-2">
      <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal px-2">Memo</label>                    
      <div class="col-sm-10">
        <textarea class="form-control text-sm" id="catatan" name="catatan" style="resize:none;height: 4em;"></textarea>
      </div>
    </div>                                     
    <div class="row mt-2 px-2">
      <div class="col-sm-2"></div>
      <div class="col-sm-10">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="aktif">
          <label class="form-check-label text-sm" for="aktif">Aktif</label>
        </div>                    
      </div>
    </div>                                                                                
    <div style='clear:both'></div>
  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
              <button type="button" id="submit" name="submit" class="btn btn-primary btn-sm">Simpan</button>
          </div>
      </div>                
  </div>
</form>                            
<script src="<? echo app_url('assets/dist/js/modul/administrator/form-menu.js'); ?>"></script>     