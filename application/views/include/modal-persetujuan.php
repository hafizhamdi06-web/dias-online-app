  <form class='form-horizontal' method="post">
  <input type="hidden" id="idpersetujuan" name="idpersetujuan">
  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Jenis</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="jenispersetujuan" disabled>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Keterangan</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="keteranganpersetujuan" disabled>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Dari User</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="pemohonpersetujuan" disabled>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tanggal Minta</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="tanggalpersetujuan" disabled>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Catatan</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="catatanpersetujuan" name="catatanpersetujuan" autocomplete="off" placeholder="Wajib diisi jika Tolak" data-trigger="manual" data-placement="auto">
    </div>
  </div>
  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Cancel</a>
              <button type="button" id="btolakpersetujuan" name="btolakpersetujuan" class="btn btn-danger btn-sm">Tolak</button>
              <button type="button" id="bsetujupersetujuan" name="bsetujupersetujuan" class="btn btn-primary btn-sm">Setuju</button>
          </div>
      </div>
  </div>
</form>
<script src="<? echo app_url('assets/dist/js/modul/include/modal-persetujuan.js'); ?>"></script>
