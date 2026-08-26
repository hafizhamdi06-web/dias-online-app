<form class='form-horizontal' method="post">
    <input type="hidden" id="id" name="id">
    <div class="modal-body">
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Kode / NIK</label>                    
      <div class="col-sm-4">
        <input type="text" class="form-control form-control-sm" placeholder="" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
      <div class="col-sm-4">
        <div class="form-check mt-1">
          <input type="checkbox" class="form-check-input" id="aktif" checked>
          <label class="form-check-label text-sm" for="aktif" role="button">Aktif</label>
        </div>                                
      </div>
    </div>                              
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama User</label>                    
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                
    <div class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Nama Lengkap</label>                    
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" placeholder="" id="namalengkap" name="namalengkap" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>                
    <div id="rPassword" class="row">
      <label for="" class="col-sm-3 col-form-label text-sm text-brown font-weight-normal">Password</label>                    
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" id="pwd" name="pwd" autocomplete="off" data-trigger="manual" data-placement="auto">
      </div>
    </div>
    <div class="row mt-2 px-1">
      <div class="card card-primary card-outline card-outline-tabs w-100" style="box-shadow: none">
        <div class="card-header card-header-sm p-0">
          <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true">Menu</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-sm" id="btn-tab-report" data-toggle="pill" href="#tab-report" role="tab" aria-controls="tab-report" aria-selected="false">Laporan</a>
            </li>
          </ul>
        </div>
        <div class="card-body card-outline-tabs-body px-0 mx-0 py-0 my-1">
          <div class="tab-content">
            <div class="tab-pane fade active show text-sm" id="tab-menu" role="tabpanel" aria-labelledby="btn-tab-menu">
                    <div class="row mx-0 px-0">
                      <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;height:calc(100vh - 360px);overflow: auto;">
                          <table id="tmenu" class="table table-hover table-sm table-transaksi py-0 my-0">
                            <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
                              <tr>
                                <th class="text-sm text-label text-left border-0" style="width: 10px"></th>
                                <th class="text-sm text-label text-left border-0" style="width: 150px"></th>
                                <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Buka</th>
                                <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Tambah</th>
                                <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Edit</th>
                                <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Hapus</th>
                                <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Cetak</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                          </table>
                    </div>
                    </div>          
            </div>
            <div class="tab-pane fade text-sm" id="tab-report" role="tabpanel" aria-labelledby="btn-tab-report">
                    <div class="row mx-0 px-0">
                      <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;height:calc(100vh - 360px);overflow: auto;">
                          <table id="treport" class="table table-hover table-sm table-transaksi w-100">
                            <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
                              <tr>
                                <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 10px"></th>
                                <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 220px"></th>
                                <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Buka</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                            </tfoot>
                          </table>
                    </div>
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
                <button type="button" id="submit" name='submit' class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>                
    </div>
</form>                            

<script src="<? echo app_url('assets/dist/js/modul/administrator/form-user.js'); ?>"></script> 