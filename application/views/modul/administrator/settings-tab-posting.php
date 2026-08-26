<div class="tab-pane fade text-sm" id="tab-post" role="tabpanel" aria-labelledby="btn-tab-post">
    <div class="table-utils table-utils-post d-none">
      <button id="bfilter-post" type="button" class="btn btn-light btn-sm" style="text-shadow: none;">
        <i class="fas fa-filter text-sm text-primary"></i> Filter Data
      </button>
    </div>              
    <table id="post-table" class="table table-sm table-striped table-hover w-100 bg-light nowrap">
      <thead>
      <tr>
      <th class="d-none"></th>
      <th><input type='checkbox' id='all-post' class="mt-1"></th>
      <th class="text-sm">Nomor</th>
      <th class="text-sm">Tanggal</th>
      <th class="text-sm">Kontak</th>  
      <th class="text-sm">Uraian</th> 
      <th class="text-sm text-right">Total</th>
      </tr>
      </thead>
    </table>
    <div id="fDataTable-Post" class="fDataTable d-none">
      <div class="row mt-2 mx-1">
          <div class="col-sm-12">
            <label class="col-form-label text-sm font-weight-normal">Opsi :</label>
            <div class="input-group">
              <select id="opsi-post" class="form-control form-control-sm select2">
                <option value="0">Posting Transaksi</option>
                <option value="1">Unposting Transaksi</option>                            
              </select>
            </div>                                
          </div>
      </div>
      <div class="row mt-0 mx-1">
          <div class="col-sm-12">
            <label class="col-form-label text-sm font-weight-normal">Tipe Transaksi :</label>
            <div class="input-group">
              <select id="tipe-post" class="form-control form-control-sm select2">
              </select>
            </div>                                
          </div>
      </div>                  
      <div class="row mt-0 mx-1">
          <div class="col-sm-12">
            <label class="col-form-label text-sm font-weight-normal">Dari Tanggal :</label>
            <div class="input-group date">
              <input id="tgldaripost" type="text" class="form-control form-control-sm datepicker">
              <div id="dtgldaripost" class="input-group-append" role="button">
                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
              </div>
            </div>                                
          </div>
      </div>
      <div class="row mt-0 pt-0 mx-1">
          <div class="col-sm-12">
            <label class="col-form-label text-sm font-weight-normal">Sampai Tanggal :</label>
            <div class="input-group date">
              <input id="tglsampaipost" type="text" class="form-control form-control-sm datepicker">
              <div id="dtglsampaipost" class="input-group-append" role="button">
                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
              </div>
            </div>                                
          </div>
      </div>
      <div class="row ml-3 mt-4 pt-0">
        <div class="col-sm-6">
          <button type="button" id="bfilterpost" class="btn btn-primary btn-sm">Tampilkan</button>
        </div>
      </div>                              
      <div class="row ml-3 mt-3 pt-0">
        <div class="col-sm-10">
          <button type="button" id="bprosespost" class="btn btn-primary btn-sm">Mulai Posting</button>
        </div>
      </div>                                                
    </div>                       
</div>