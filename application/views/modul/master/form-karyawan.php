<form class='form-horizontal' method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body my-0" style="padding-top:15px;padding-bottom:0px;">

  <div class="row">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode</label>
    <div class="col-sm-4">
      <input type="text" class="form-control form-control-sm" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
    </div>
    <div class="col-sm-6 pt-2">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="aktif" name="aktif" checked>
        <label class="form-check-label text-sm" for="aktif">Karyawan Aktif</label>
      </div>
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
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Jenis Karyawan</label>
    <div class="col-sm-4">
      <select id="jeniskaryawan" name="jeniskaryawan" class="form-control select2" style="width:100%"></select>
    </div>
  </div>

  <div class="row mt-0">
    <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Cabang</label>
    <div class="col-sm-4">
      <select id="cabang" name="cabang" class="form-control select2" style="width:100%"></select>
    </div>
  </div>

  <div class="row mt-3">
  <div class="col-sm-12">
    <div id="tabKaryawan" class="card card-primary card-outline card-outline-tabs" style="box-shadow: none;">
      <div class="card-header card-header-sm p-0">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link text-sm active" id="btn-tab-pos" data-toggle="pill" href="#tab-pos" role="tab" aria-controls="tab-pos" aria-selected="true">Seting POS</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-sm" id="btn-tab-alamat" data-toggle="pill" href="#tab-alamat" role="tab" aria-controls="tab-alamat" aria-selected="false">Alamat &amp; Identitas</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-sm" id="btn-tab-payroll" data-toggle="pill" href="#tab-payroll" role="tab" aria-controls="tab-payroll" aria-selected="false">Payroll</a>
          </li>
        </ul>
      </div>
      <div class="card-body card-outline-tabs-body mt-0 pt-2 pb-0 mb-0 px-2 mx-0">
        <div class="tab-content">

          <!-- Tab Seting POS -->
          <div class="tab-pane fade active show text-sm" id="tab-pos" role="tabpanel" aria-labelledby="btn-tab-pos">
            <div class="row pt-2">
              <div class="col-sm-4">
                <div class="form-check"><input type="checkbox" class="form-check-input" id="doktersmy"><label class="form-check-label text-sm" for="doktersmy">Dokter SMY</label></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="salesmarketing"><label class="form-check-label text-sm" for="salesmarketing">Sales Marketing</label></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="aos"><label class="form-check-label text-sm" for="aos">AOS</label></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="dokterbedah"><label class="form-check-label text-sm" for="dokterbedah">Dokter Bedah</label></div>
              </div>
              <div class="col-sm-4">
                <div class="form-check"><input type="checkbox" class="form-check-input" id="reseller"><label class="form-check-label text-sm" for="reseller">Reseller</label></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="dokterpj"><label class="form-check-label text-sm" for="dokterpj">Dokter PJ</label></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="dokterinsider"><label class="form-check-label text-sm" for="dokterinsider">Dokter Insider</label></div>
              </div>
              <div class="col-sm-4">
                <div class="form-check"><input type="checkbox" class="form-check-input" id="kolomdokter"><label class="form-check-label text-sm" for="kolomdokter">Tampil di Kolom Dokter</label></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="kolomperawat"><label class="form-check-label text-sm" for="kolomperawat">Tampil di Kolom Perawat</label></div>
                <div class="form-check"><input type="checkbox" class="form-check-input" id="kolomresep"><label class="form-check-label text-sm" for="kolomresep">Tampil di Kolom Resep</label></div>
              </div>
            </div>
            <div class="pb-3"></div>
          </div>

          <!-- Tab Alamat & Identitas -->
          <div class="tab-pane fade text-sm" id="tab-alamat" role="tabpanel" aria-labelledby="btn-tab-alamat">
            <div class="row mt-2">
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
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No HP</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="nohp" name="nohp" autocomplete="off">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Email</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="email" name="email" autocomplete="off">
              </div>
            </div>
            <div class="row mt-1">
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
            <div class="row mt-1">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">No KTP</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="noktp" name="noktp" autocomplete="off">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Tgl Join</label>
              <div class="col-sm-4">
                <div class="input-group date">
                  <input id="tgljoin" name="tgljoin" type="text" class="form-control form-control-sm datepicker" autocomplete="off">
                  <div id="bTglJoin" class="input-group-append" role="button">
                    <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row mt-1 pb-3">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">User Login</label>
              <div class="col-sm-4">
                <select id="user" name="user" class="form-control select2" style="width:100%"></select>
              </div>
              <div class="col-sm-6 text-sm text-muted pt-2">
                Dibuat: <span id="lblTglBuat">-</span> <span id="lblUserBuat"></span>
              </div>
            </div>
          </div>

          <!-- Tab Payroll -->
          <div class="tab-pane fade text-sm" id="tab-payroll" role="tabpanel" aria-labelledby="btn-tab-payroll">
            <div class="row mt-2">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">NIK</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="nik" name="nik" autocomplete="off">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Nama Panjang</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="namapanjang" name="namapanjang" autocomplete="off">
              </div>
            </div>
            <div class="row mt-1 pb-3">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode Insider</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="kodeinsider" name="kodeinsider" autocomplete="off">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kelompok FU</label>
              <div class="col-sm-4">
                <select id="kelompokfu" name="kelompokfu" class="form-control select2" style="width:100%"></select>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
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

<script src="<? echo app_url('assets/dist/js/modul/master/form-karyawan.js'); ?>"></script>
