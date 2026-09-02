<form class='form-horizontal' method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body my-0" style="padding-top:15px;padding-bottom:0px;">

  <div class="row">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">ID Pasien</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="idpasien" name="idpasien" autocomplete="off">
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Nama</label>
    <div class="col-sm-10">
      <input type="text" class="form-control form-control-sm" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kategori</label>
    <div class="col-sm-4">
      <select id="kategori" name="kategori" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No. Member</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="nomember" name="nomember" autocomplete="off">
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Cabang</label>
    <div class="col-sm-4">
      <select id="cabang" name="cabang" class="form-control select2" style="width:100%"></select>
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No. KTP</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="noktp" name="noktp" autocomplete="off">
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tempat Lahir</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="tempatlahir" name="tempatlahir" autocomplete="off">
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tgl Lahir</label>
    <div class="col-sm-4">
      <div class="input-group date">
        <input id="tgllahir" name="tgllahir" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
        <div id="bTglLahir" class="input-group-append" role="button">
          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Pekerjaan</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="pekerjaan" name="pekerjaan" autocomplete="off">
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tgl Kontrak</label>
    <div class="col-sm-4">
      <div class="input-group date">
        <input id="tglkontrak" name="tglkontrak" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
        <div id="bTglKontrak" class="input-group-append" role="button">
          <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Jenis Kelamin</label>
    <div class="col-sm-4 pt-2">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="kelamin" id="kelaminP" value="0" checked>
        <label class="form-check-label text-sm" for="kelaminP">Perempuan</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="kelamin" id="kelaminL" value="1">
        <label class="form-check-label text-sm" for="kelaminL">Laki-laki</label>
      </div>
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Status</label>
    <div class="col-sm-4 pt-2">
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="barulama" id="pasienBaru" value="0" checked>
        <label class="form-check-label text-sm" for="pasienBaru">Baru</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="barulama" id="pasienLama" value="1">
        <label class="form-check-label text-sm" for="pasienLama">Lama</label>
      </div>
    </div>
  </div>

  <hr class="my-2">

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Alamat</label>
    <div class="col-sm-10">
      <textarea id="alamat" name="alamat" class="form-control form-control-sm" rows="2"></textarea>
    </div>
  </div>

  <div class="row mt-1">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kota</label>
    <div class="col-sm-4">
      <select id="kota" name="kota" class="form-control select2" style="width:100%"></select>
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kecamatan</label>
    <div class="col-sm-4">
      <select id="kecamatan" name="kecamatan" class="form-control select2" style="width:100%"></select>
    </div>
  </div>

  <div class="row mt-1">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Telepon</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="telp" name="telp" autocomplete="off">
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Email</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="email" name="email" autocomplete="off">
    </div>
  </div>

  <hr class="my-2">

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No. Kartu</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="nokartu" name="nokartu" autocomplete="off">
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode TADA</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="kodetada" name="kodetada" autocomplete="off">
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Sales / Karyawan</label>
    <div class="col-sm-4">
      <select id="karyawan" name="karyawan" class="form-control select2" style="width:100%"></select>
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Karyawan Training</label>
    <div class="col-sm-4">
      <select id="karyawantraining" name="karyawantraining" class="form-control select2" style="width:100%"></select>
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Marketing Source</label>
    <div class="col-sm-4">
      <select id="marketingsource" name="marketingsource" class="form-control select2" style="width:100%"></select>
    </div>
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Insider / Ref</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="insider" name="insider" autocomplete="off">
    </div>
  </div>

  <div class="row mt-2">
    <div class="col-sm-2"></div>
    <div class="col-sm-4">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="aktif" name="aktif" checked>
        <label class="form-check-label text-sm" for="aktif">Pasien Aktif</label>
      </div>
    </div>
    <div class="col-sm-6 text-sm text-muted">
      Point: <span id="lblPoint">0</span> &nbsp;|&nbsp;
      Dibuat: <span id="lblTglBuat">-</span> <span id="lblUserBuat"></span>
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

<script src="<? echo app_url('assets/dist/js/modul/master/form-pasien.js'); ?>"></script>
