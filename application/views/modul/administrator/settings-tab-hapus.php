  <style scoped>
    @media (max-width:767px){
      .tab-pane{
        margin-top: 115px;
      }
      .table-utils{
        margin-top: 15px; 
      }
      .fDataTable{
        margin-top:15px;
      }
      table.dataTable thead{
        top:154px;
      }        
    }    
  </style>


<div class="tab-pane text-sm" id="tab-hapus" role="tabpanel" aria-labelledby="btn-tab-hapus">
    <div class="table-utils table-utils-del d-none">
      <button id="bfilter-del" type="button" class="btn btn-light btn-sm" style="text-shadow: none;">
        <i class="fas fa-filter text-sm text-primary"></i> Filter Data
      </button>
    </div>              
    <table id="del-table" class="table table-sm table-striped table-hover w-100 bg-light nowrap">
      <thead>
      <tr>
      <th class="d-none"></th>
      <th><input type='checkbox' id='all-del' class="mt-1"></th>
      <th class="text-sm">Nomor</th>
      <th class="text-sm">Tanggal</th>
      <th class="text-sm">Kontak</th>  
      <th class="text-sm">Uraian</th> 
      <th class="text-sm text-right">Total</th>
      </tr>
      </thead>
    </table>
    <div id="fDataTable-del" class="fDataTable d-none">
      <div class="row mt-2 mx-1">
          <div class="col-sm-12">
            <label class="col-form-label text-sm font-weight-normal">Tipe Transaksi :</label>
            <div class="input-group">
              <select id="tipe-del" class="form-control form-control-sm select2">
              </select>
            </div>                                
          </div>
      </div>                  
      <div class="row mt-0 mx-1">
          <div class="col-sm-12">
            <label class="col-form-label text-sm font-weight-normal">Dari Tanggal :</label>
            <div class="input-group date">
              <input id="tgldaridel" type="text" class="form-control form-control-sm datepicker">
              <div id="dtgldaridel" class="input-group-append" role="button">
                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
              </div>
            </div>                                
          </div>
      </div>
      <div class="row mt-0 pt-0 mx-1">
          <div class="col-sm-12">
            <label class="col-form-label text-sm font-weight-normal">Sampai Tanggal :</label>
            <div class="input-group date">
              <input id="tglsampaidel" type="text" class="form-control form-control-sm datepicker">
              <div id="dtglsampaidel" class="input-group-append" role="button">
                  <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
              </div>
            </div>                                
          </div>
      </div>
      <div class="row ml-3 mt-4 pt-0">
        <div class="col-sm-12">
          <button type="button" id="bfilterdel" class="btn btn-primary btn-sm"><span>Tampilkan</span></button>
        </div>
      </div>                              
    </div>                       
</div>