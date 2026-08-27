<form class='form-horizontal' method="post">
    <input type="hidden" id="id" name="id">
    <div class="modal-body">
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode / NIK</label>
      <div class="col-sm-4">
        <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" disabled>
      </div>
      <div class="col-sm-4">
        <div class="form-check mt-1">
          <input type="checkbox" class="form-check-input" id="aktif" disabled>
          <label class="form-check-label text-sm" for="aktif">Aktif</label>
        </div>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama User</label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" disabled>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Lengkap</label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="namalengkap" name="namalengkap" autocomplete="off" disabled>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Karyawan</label>
      <div class="col-sm-9">
        <select id="ukid" name="ukid" class="form-control select2" style="width: 100%" disabled></select>
      </div>
    </div>
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Cabang</label>
      <div class="col-sm-9">
        <select id="ucabang" name="ucabang" class="form-control select2" style="width: 100%" disabled></select>
      </div>
    </div>

    <div class="row mt-3 px-2">
      <label class="text-sm text-brown font-weight-bold">Gudang Pilihan</label>
      <div class="col-sm-12 px-0">
        <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;max-height:220px;overflow: auto;">
          <table id="tgudangprofil" class="table table-hover table-sm table-transaksi w-100 mb-0">
            <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
              <tr>
                <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 150px">Kode</th>
                <th class="text-sm text-label text-left border-0 font-weight-normal">Nama Gudang</th>
                <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 90px">Aktif</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
        <span class="text-sm text-gray">Klik salah satu baris untuk menjadikannya cabang aktif.</span>
      </div>
    </div>

    </div>
    <div class="modal-footer">
        <div class="form-group">
            <div class="col-sm-offset-3">
                <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Tutup</a>
            </div>
        </div>
    </div>
</form>

<script src="<? echo app_url('assets/dist/js/modul/administrator/form-profil.js'); ?>"></script>
