<form method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body">
  <div class="row mx-1">
    <label class="col-sm-3 col-form-label text-sm font-weight-normal">Kode</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="kode" name="kode" readonly>
    </div>
  </div>
  <div class="row mx-1 mt-2">
    <label class="col-sm-3 col-form-label text-sm font-weight-normal">Nama</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="nama" name="nama" readonly>
    </div>
  </div>
  <div class="row mx-1 mt-2">
    <label class="col-sm-3 col-form-label text-sm font-weight-normal">Harga Jual 1</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm text-right numeric" id="hargajual1" name="hargajual1" readonly>
    </div>
  </div>
  <div class="row mx-1 mt-2">
    <label class="col-sm-3 col-form-label text-sm font-weight-normal">Harga Jual MP *</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm text-right numeric" id="hargamp" name="hargamp" autocomplete="off" placeholder="0">
    </div>
  </div>
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-update-harga-mp.js'); ?>"></script>
