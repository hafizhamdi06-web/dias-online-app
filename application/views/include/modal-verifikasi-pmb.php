  <form class='form-horizontal' method="post">
  <input type="hidden" id="idverif" name="idverif">
  <div class="modal-body">
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Tanggal</label>
    <div class="col-sm-3">
      <input type="text" class="form-control form-control-sm" id="tanggalverif" disabled>
    </div>
    <label for="" class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No PO</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="nomorverif" disabled>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Karyawan</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="karyawanverif" disabled>
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Catatan Verifikasi</label>
    <div class="col-sm-9">
      <input type="text" class="form-control form-control-sm" id="catatanverif" name="catatanverif" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>
  <div class="row mx-2">
    <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Satatus</label>
    <div class="col-sm-9">
      <select id="statusverif" name="statusverif" class="form-control select2" style="width: 100%" data-trigger="manual" data-placement="auto">
        <option value="1">Pending</option>
        <option value="2">Verifikasi Finance</option>
      </select>
    </div>
  </div>
  </div>
  <div class="modal-footer">
      <div class="form-group">
          <div class="col-sm-offset-3">
              <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Cancel</a>
              <button type="button" id="bokverif" name="bokverif" class="btn btn-primary btn-sm">OK</button>
          </div>
      </div>
  </div>
</form>
<script src="<? echo app_url('assets/dist/js/modul/include/modal-verifikasi-pmb.js'); ?>"></script>
