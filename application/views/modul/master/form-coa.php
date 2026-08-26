  <form class='form-horizontal' method="post">
  <input type="hidden" id="id" name="id" value="">
  <div class="modal-body">
  <div class="row">
  <div class="col-sm-12">
      <div id="tabCOA" class="card card-primary card-outline card-outline-tabs" style="box-shadow: none">
        <div class="card-header card-header-sm p-0">
          <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link text-sm active" id="btn-tab-menu" data-toggle="pill" href="#tab-menu" role="tab" aria-controls="tab-menu" aria-selected="true">Data Umum</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-sm" id="btn-tab-saldoawalcoa" data-toggle="pill" href="#tab-saldoawalcoa" role="tab" aria-controls="tab-saldoawalcoa" aria-selected="false">Saldo Awal</a>
            </li>
          </ul>
        </div>
        <div class="card-body card-outline-tabs-body px-0 mx-0">
          <div class="tab-content">
            <div class="tab-pane fade active show text-sm px-2" id="tab-menu" role="tabpanel" aria-labelledby="btn-tab-menu">
              <div class="row">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">Tipe *</label>
                <div class="col-sm-9">
                  <select id="tipe" name="tipe" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto">
                  </select>                         
                </div>
              </div>
              <div class="row mt-1">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">Nomor *</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm" id="nomor" name="nomor" autocomplete="off" data-trigger="manual" data-placement="auto">
                </div>
              </div>                              
              <div class="row mt-0">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">Nama *</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
                </div>
              </div> 
              <div class="row">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal"> Sebagai Akun Induk</label>
                <div class="col-sm-9">
                  <select id="gd" name="gd" class="form-control form-control-sm select2" style="width:100%" data-trigger="manual" data-placement="auto">
                    <option value="G">Ya</option>
                    <option value="D" selected>Tidak</option>                              
                  </select>                         
                </div>                
              </div>                             
              <div class="row mt-1">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">Sub Dari</label>
                <div class="col-sm-9">
                  <div class="input-group">                  
                  <div class="input-group-append">
                    <div class="input-group-text border-0 bg-light"><input type="checkbox" id="chksub"></div>
                  </div>                  
                    <select id="indukakun" name="indukakun" class="form-control select2" disabled>
                    </select>                         
                  </div>
                </div>
              </div>                                
              <div class="row mt-1">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">Mata Uang</label>
                <div class="col-sm-9">
                  <select id="uang" name="uang" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto">
                  </select>                         
                </div>
              </div>
              <div class="row mt-1">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">Divisi</label>
                <div class="col-sm-9">
                  <select id="divisi" name="divisi" class="form-control select2" style="width:100%">
                  </select>                         
                </div>
              </div>
              <div class="row mt-1">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">Bank</label>
                <div class="col-sm-9">
                  <select id="bank" name="bank" class="form-control select2" style="width:100%">
                  </select>                         
                </div>
              </div> 
              <div class="row mt-1">
                <label class="col-sm-3 col-form-label text-sm font-weight-normal">No A/C Bank</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control form-control-sm" id="nomorbank" name="nomorbank">
                </div>
              </div> 

              <div class="row mt-2">
                <div class="col-sm-3"></div>
                <div class="col-sm-9">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="aktif">
                    <label class="form-check-label text-sm" for="aktif">Aktif</label>
                  </div>                    
                </div>
              </div>                                                                                                                                                      
              <div style='clear:both'></div>
            </div>
            <div class="tab-pane fade text-sm" id="tab-saldoawalcoa" role="tabpanel" aria-labelledby="btn-tab-saldoawalcoa">
                <div class="card card-primary card-outline card-outline-tabs">
                  <div class="card-header card-header-sm p-0 border-bottom-0">
                  </div>
                  <div class="card-body card-outline-tabs-body px-0 mx-0 py-0 my-0">
                    <div class="tab-content">
                      <div class="table-responsive bg-light" tabindex="-1" style="border:1px solid #dee2e6;height:calc(100vh - 360px);overflow: auto;">
                        <table id="tsaldo" class="table table-hover table-sm table-transaksi">
                          <thead class="bg-primary">
                            <tr>
                              <th class="text-sm text-label text-left px-1 border-0 font-weight-normal" style="width: 250px">Kontak</th>
                              <th class="text-sm text-label text-left px-1 border-0 font-weight-normal" style="width: 100px">Per Tanggal</th>
                              <th class="text-sm text-label text-right px-1 border-0 font-weight-normal" style="width: 140px">Jumlah</th>
                              <th class="text-sm text-label text-center border-0" style="width: 40px"></th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                          <tfoot>
                          </tfoot>
                        </table>
                      </div>
                      <div class="row mt-2 px-2">
                          <button type="button" id="baddrow" class="btn btn-light text-sm">
                              <i class="fa fa-plus px-1 text-primary"></i> Tambah
                          </button>
                      </div>
                    </div>
                  </div>
                </div>                                      
            </div>
          </div>
        </div>
        <!-- /.card -->
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

<script src="<? echo app_url('assets/dist/js/modul/master/form-coa.js'); ?>"></script> 