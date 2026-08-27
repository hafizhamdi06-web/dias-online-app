  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id">
  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Nama</label>
    <div class="col-sm-10">
      <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tipe</label>
    <div class="col-sm-4">
      <select id="tipe" name="tipe" class="form-control select2" style="width: 100%" data-trigger="manual" data-placement="auto"></select>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Gudang Terkait</label>
    <div class="col-sm-6">
      <select id="gudang" name="gudang" class="form-control select2" style="width: 100%"></select>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Keterangan</label>
    <div class="col-sm-10">
      <textarea class="form-control text-sm" id="keterangan" name="keterangan" style="resize:none;height: 4em;"></textarea>
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-lain.js'); ?>"></script>
