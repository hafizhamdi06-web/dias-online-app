  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
  <div class="row">
    <label for="" class="col-sm-12 col-form-label text-sm text-brown font-weight-normal">Periode tahun :</label>                    
  </div>                              
  <div class="row">
    <div class="col-sm-12">
      <input type="text" class="form-control form-control-sm numeric" placeholder="" id="tahun" name="tahun" autocomplete="off" data-trigger="manual" data-placement="auto">
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

  <script src="<? echo app_url('assets/dist/js/modul/administrator/form-periode.js'); ?>"></script> 