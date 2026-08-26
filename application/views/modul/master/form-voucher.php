 <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
      
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode</label>                    
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tanggal Terbit</label>                    
    <div class="col-sm-4"> 
       <div class="input-group date">
                  <input id="tglterbit" type="text" class="form-control form-control-sm datepicker">
                  <div id="btglterbit" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>  
    </div>
  </div>  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tanggal Pakai</label>                    
    <div class="col-sm-4"> 
       <div class="input-group date">
                  <input id="tglpakai" type="text" class="form-control form-control-sm datepicker" disabled> 
                </div>  
    </div>
  </div>
   
  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Pasien</label>                    
    <div class="col-sm-9"> 
                    <select id="pasien" name="pasien" class="form-control select2"></select>  
    </div>
  </div> 
  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nilai 1</label>                    
    <div class="col-sm-3"> 
                <input id="diskon1" type="text" class="form-control form-control-sm numeric" autocomplete="off" value="0">
    </div>
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nilai 2</label>                    
    <div class="col-sm-3">
                <input id="diskon2" type="text" class="form-control form-control-sm numeric" autocomplete="off" value="0">
    </div>
  </div>  
  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Jenis</label>                    
    <div class="col-sm-9">
        <select id="jenis" name="jenis" class="form-control select2"></select>    
    </div>
  </div> 
  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Item 1</label>                    
    <div class="col-sm-9">
        <select id="item1" name="item1" class="form-control select2"></select>   
    </div>
  </div> 
  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Item 2</label>                    
    <div class="col-sm-9">
        <select id="item2" name="item2" class="form-control select2"></select>   
    </div>
  </div> 
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Masa Berlaku</label>                    
    <div class="col-sm-4">
                <input id="masaberlaku" type="text" class="form-control form-control-sm numeric" autocomplete="off" value="0">
    </div>
  </div>  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tanggal Expired</label>                    
    <div class="col-sm-4"> 
       <div class="input-group date">
                  <input id="tglexpired" type="text" class="form-control form-control-sm datepicker">
                  <div id="btglexpired" class="input-group-append" role="button">
                      <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>  
    </div>
  </div>
  
  
  <div class="row mx-2">                
    <div class="col-sm-4">
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="chkproduksaja">
                <label class="form-check-label col-form-label text-sm font-weight-normal" for="chkproduksaja">Untuk Produk Saja</label>
              </div>
    </div>
    <div class="col-sm-4">
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="chkrupiah">
                <label class="form-check-label col-form-label text-sm font-weight-normal" for="chkrupiah">Rupiah</label>
              </div>
    </div>
    <div class="col-sm-4">
              <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="chkpakaibytanggal">
                <label class="form-check-label col-form-label text-sm font-weight-normal" for="chkpakaibytanggal">Pemakaian By Tanggal</label>
              </div>
    </div>
  </div>  
   
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Penggunaan</label>                    
    <div class="col-sm-9">
      
       <select id="penggunaan" name="penggunaan" class="form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"> 
                                                  <option value='0'>1 Item</option>
                                                  <option value='1'>1 Bon Jenis Produk</option>
                                                  <option value='2' selected>1 Bon Semua Item</option>  
                                                  </select> 
      
    </div>
  </div> 
  
  
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Item Free</label>                    
    <div class="col-sm-9">
        <select id="itemfree" name="itemfree" class="form-control select2"></select>     
    </div>
  </div> 
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Teman</label>                    
    <div class="col-sm-9">
                    <select id="teman" name="teman" class="form-control select2"></select>           
      
    </div>
  </div> 
  
  
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-voucher.js'); ?>"></script> 