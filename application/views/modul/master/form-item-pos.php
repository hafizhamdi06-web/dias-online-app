<form method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body">
<div class="row mx-1">
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Kode *</label>
<div class="col-sm-4">
  <input type="search" class="form-control form-control-sm" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Nama Web</label>
<div class="col-sm-4">
  <input type="search" class="form-control form-control-sm" id="namaweb" name="namaweb" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
</div>
<div class="row mx-1">
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Nama *</label>
<div class="col-sm-10">
  <input type="search" class="form-control form-control-sm" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
</div>
<div class="row mt-3 mx-1">
<div class="col-sm-12">
  <div id="tabItemPos" class="card card-primary card-outline card-outline-tabs" style="box-shadow: none">
    <div class="card-header p-0">
      <ul class="nav nav-tabs" id="custom-tabs-itempos-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link text-sm active" id="btn-tab-detail" data-toggle="pill" href="#tab-detail" role="tab" aria-controls="tab-detail" aria-selected="true" tabindex="-1">Detail</a>
        </li>
      </ul>
    </div>
    <div class="card-body card-outline-tabs-body px-0 mx-0">
      <div class="tab-content">
        <div class="tab-pane active show text-sm" id="tab-detail" role="tabpanel" aria-labelledby="btn-tab-detail">
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kategori</label>
            <div class="col-sm-4">
              <select id="kategori" name="kategori" class="form-control select2 w-100" style="width:100%">
                <option value="0">Biasa</option>
                <option value="1">Ada Penyusun</option>
                <option value="2">Penyusun Langsung</option>
              </select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Status</label>
            <div class="col-sm-4">
              <select id="status" name="status" class="form-control select2 w-100" style="width:100%">
                <option value="0">Aktif</option>
                <option value="1">Tidak Aktif</option>
                <option value="2">Tidak Terpakai</option>
              </select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Satuan Dasar *</label>
            <div class="col-sm-4">
              <select id="satuandasar" name="satuandasar" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Satuan Default *</label>
            <div class="col-sm-4">
              <select id="satuandefault" name="satuandefault" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Maksimal</label>
            <div class="col-sm-4">
              <input id="stokmaks" type="text" class="form-control form-control-sm qty" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Minimal</label>
            <div class="col-sm-4">
              <input id="stokmin" type="text" class="form-control form-control-sm qty" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Reorder</label>
            <div class="col-sm-4">
              <input id="stokreorder" type="text" class="form-control form-control-sm qty" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Max Order</label>
            <div class="col-sm-4">
              <input id="maxorder" type="text" class="form-control form-control-sm qty" value="3">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Qty Per Box</label>
            <div class="col-sm-4">
              <input id="qtyperbox" type="text" class="form-control form-control-sm qty" value="1">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-2">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="serial">
                <label class="form-check-label text-sm" for="serial" role="button">Serial</label>
              </div>
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
      </div>
    </div>
  </div>
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
<script src="<? echo app_url('assets/dist/js/modul/master/form-item-pos.js'); ?>"></script>
