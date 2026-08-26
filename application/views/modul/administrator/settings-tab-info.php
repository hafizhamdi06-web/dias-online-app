<div class="tab-pane active show text-sm px-2 mx-2" id="tab-menu" role="tabpanel" aria-labelledby="btn-tab-menu">
            <div class="row mt-1">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Nama Perusahaan *</label>
              <div class="col-sm-4">
                <input type="hidden" name="iid" id="iid">
                <input type="text" class="form-control form-control-sm" id="inama" name="inama" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>
            </div>                              
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Alamat 1 *</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="ialamat1" name="ialamat1" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>
            </div>                
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Alamat 2</label>
              <div class="col-sm-10">
                <input type="text" class="form-control form-control-sm" id="ialamat2" name="ialamat2" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>
            </div>                              
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kota</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="ikota" name="ikota" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Propinsi</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="ipropinsi" name="ipropinsi" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>                
            </div>                                            
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Kode Pos</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="ikodepos" name="ikodepos" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Negara</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="inegara" name="inegara" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>                
            </div>
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Telepon 1</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="itelp1" name="itelp1" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Telepon 2</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="itelp2" name="itelp2" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>                
            </div>
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Faks</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="ifaks" name="ifaks" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Email</label>
              <div class="col-sm-4">
                <input type="text" class="form-control form-control-sm" id="iemail" name="iemail" autocomplete="off" data-trigger="manual" data-placement="auto">
              </div>                
            </div>
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Metode Persediaan</label>
              <div class="col-sm-2">
                <input type="text" class="form-control form-control-sm" id="metodepersediaan" name="metodepersediaan" autocomplete="off" data-trigger="manual" data-placement="auto" disabled>
              </div>
            </div>
            <div class="row mt-0">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Mata Uang</label>
              <div class="col-sm-2">
                <input type="text" class="form-control form-control-sm" id="matauang" name="matauang" autocomplete="off" data-trigger="manual" data-placement="auto" disabled>
              </div>
            </div>            
            <div class="row mt-4 d-none">
              <label class="col-sm-2 col-form-label text-sm text-brown font-weight-normal">Periode Akuntansi</label>
              <div class="col-sm-4">
              <div class="input-group">                  
                <select class="select2 form-control form-control-sm w-50" id="ibulan" name="ibulan" data-trigger="manual" data-placement="auto">
                  <option value="1">Januari</option>
                  <option value="2">Februari</option>
                  <option value="3">Maret</option>
                  <option value="4">April</option>
                  <option value="5">Mei</option>
                  <option value="6">Juni</option>
                  <option value="7">Juli</option>                           
                  <option value="8">Agustus</option>
                  <option value="9">September</option>
                  <option value="10">Oktober</option>
                  <option value="11">November</option>
                  <option value="12">Desember</option>
                </select>
                <div class="input-group-append w-50 pl-1">
                  <select class="select2 form-control form-control-sm" id="itahun" name="itahun" data-trigger="manual" data-placement="auto">
                  </select>
                </div>
                </div>                  
              </div>
            </div>
            <div class="row mt-0 d-none">
              <div class="col-sm-2"></div>
              <div class="col-sm-2 pt-1">
                <a href="javascript:void(0)" class="btn btn-primary">Hitung Ulang</a>
              </div>
            </div>                                                                                              
            <div style='clear:both'></div>
</div>