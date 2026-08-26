<div class="tab-pane text-sm mx-2 px-2" id="tab-jurnal" role="tabpanel" aria-labelledby="btn-tab-jurnal">
<div class="row">
  <div class="col-sm-4">
    <div class="card" style="box-shadow: none">
      <div class="card-header">
        <h3 class="card-title text-sm">
          <b>Jurnal Pembelian</b>
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Invoice Pembelian</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpb_d_1">
                  <input type="hidden" id="ketpb_d_1">                  
                  <input type="hidden" id="kodepb_d_1">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pembelian / Persediaan [D]</label>
                    <select id="coapb_d_1" name="coapb_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpb_d_2">
                  <input type="hidden" id="ketpb_d_2">                  
                  <input type="hidden" id="kodepb_d_2">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pajak Masukan [D]</label>
                    <select id="coapb_d_2" name="coapb_d_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpb_k_1">
                  <input type="hidden" id="ketpb_k_1">                  
                  <input type="hidden" id="kodepb_k_1">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Hutang Invoice [K]</label>
                    <select id="coapb_k_1" name="coapb_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2 d-none">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpb_k_2">
                  <input type="hidden" id="ketpb_k_2">                  
                  <input type="hidden" id="kodepb_k_2">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Hutang Konsinyasi [K]</label>
                    <select id="coapb_k_2" name="coapb_k_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                          
            </div>
          </div>
        </div>
        </div>
        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card d-none">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal BKG Retur</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idbkgr_d_1">
                  <input type="hidden" id="ketbkgr_d_1">                  
                  <input type="hidden" id="kodebkgr_d_1">                                                      
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Harga Pokok [D]</label>
                    <select id="coabkgr_d_1" name="coabkgr_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idbkgr_k_1">
                  <input type="hidden" id="ketbkgr_k_1">                  
                  <input type="hidden" id="kodebkgr_k_1">                                                                        
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Persediaan Barang [K]</label>
                    <select id="coabkgr_k_1" name="coabkgr_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>              
            </div>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Retur Pembelian</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrpb_d_1">
                  <input type="hidden" id="ketrpb_d_1">                  
                  <input type="hidden" id="koderpb_d_1">                                                                        
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Hutang Retur [D]</label>
                    <select id="coarpb_d_1" name="coarpb_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrpb_k_1">
                  <input type="hidden" id="ketrpb_k_1">                  
                  <input type="hidden" id="koderpb_k_1">                                                                                          
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Retur Pembelian / Persediaan [K]</label>
                    <select id="coarpb_k_1" name="coarpb_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrpb_k_2">
                  <input type="hidden" id="ketrpb_k_2">                  
                  <input type="hidden" id="koderpb_k_2">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pajak Masukan [K]</label>
                    <select id="coarpb_k_2" name="coarpb_k_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                        
            </div>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Pembayaran Hutang</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpbh_d_1">
                  <input type="hidden" id="ketpbh_d_1">                  
                  <input type="hidden" id="kodepbh_d_1">                                                                                          
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Hutang Invoice [D]</label>
                    <select id="coapbh_d_1" name="coapbh_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpbh_d_2">
                  <input type="hidden" id="ketpbh_d_2">                  
                  <input type="hidden" id="kodepbh_d_2">                                                                                          
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Hutang Uang Muka [D]</label>
                    <select id="coapbh_d_2" name="coapbh_d_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>              
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpbh_k_1">
                  <input type="hidden" id="ketpbh_k_1">                  
                  <input type="hidden" id="kodepbh_k_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Diskon Pembelian [K]</label>
                    <select id="coapbh_k_1" name="coapbh_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpbh_k_2">
                  <input type="hidden" id="ketpbh_k_2">                  
                  <input type="hidden" id="kodepbh_k_2">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Hutang Retur [K]</label>
                    <select id="coapbh_k_2" name="coapbh_k_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpbh_k_3">
                  <input type="hidden" id="ketpbh_k_3">                  
                  <input type="hidden" id="kodepbh_k_3">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Kas/Bank [K]</label>
                    <select id="coapbh_k_3" name="coapbh_k_3" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                                    
            </div>
          </div>
        </div>
        </div>                    
        <div class="row">
          <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Pembelian Lainnya</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2 d-none">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idumb_d_1">
                  <input type="hidden" id="ketumb_d_1">                  
                  <input type="hidden" id="kodeumb_d_1">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Uang Muka Pembelian [D]</label>
                    <select id="coaumb_d_1" name="coaumb_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2 d-none">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idumb_d_2">
                  <input type="hidden" id="ketumb_d_2">                  
                  <input type="hidden" id="kodeumb_d_2">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pajak Masukan [D]</label>
                    <select id="coaumb_d_2" name="coaumb_d_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>              
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idumb_k_1">
                  <input type="hidden" id="ketumb_k_1">                  
                  <input type="hidden" id="kodeumb_k_1">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Hutang Uang Muka [K]</label>
                    <select id="coaumb_k_1" name="coaumb_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idumb_k_2">
                  <input type="hidden" id="ketumb_k_2">                  
                  <input type="hidden" id="kodeumb_k_2">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Penerimaan Barang Belum Faktur [K]</label>
                    <select id="coaumb_k_2" name="coaumb_k_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                                                  
            </div>
          </div>                      
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card" style="box-shadow: none">
      <div class="card-header">
        <h3 class="card-title text-sm">
          <b>Jurnal Penjualan</b>
        </h3>
      </div>
      <div class="card-body">        
        <div class="row d-none">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal BKG Penjualan</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
            </div>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Invoice Penjualan</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idiv_d_1">
                  <input type="hidden" id="ketiv_d_1">                  
                  <input type="hidden" id="kodeiv_d_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Piutang Invoice [D]</label>
                    <select id="coaiv_d_1" name="coaiv_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idiv_k_1">
                  <input type="hidden" id="ketiv_k_1">                  
                  <input type="hidden" id="kodeiv_k_1">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pajak Keluaran [K]</label>
                    <select id="coaiv_k_1" name="coaiv_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idiv_k_2">
                  <input type="hidden" id="ketiv_k_2">                  
                  <input type="hidden" id="kodeiv_k_2">                                                      
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pendapatan [K]</label>
                    <select id="coaiv_k_2" name="coaiv_k_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idbkgj_d_1">
                  <input type="hidden" id="ketbkgj_d_1">                  
                  <input type="hidden" id="kodebkgj_d_1">
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Harga Pokok [D]</label>
                    <select id="coabkgj_d_1" name="coabkgj_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idbkgj_k_1">
                  <input type="hidden" id="ketbkgj_k_1">                  
                  <input type="hidden" id="kodebkgj_k_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Persediaan [K]</label>
                    <select id="coabkgj_k_1" name="coabkgj_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                                                  
            </div>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Retur Penjualan</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrpj_d_1">
                  <input type="hidden" id="ketrpj_d_1">                  
                  <input type="hidden" id="koderpj_d_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Retur Penjualan [D]</label>
                    <select id="coarpj_d_1" name="coarpj_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrpj_d_2">
                  <input type="hidden" id="ketrpj_d_2">                  
                  <input type="hidden" id="koderpj_d_2">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pajak Keluaran [D]</label>
                    <select id="coarpj_d_2" name="coarpj_d_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrpj_k_1">
                  <input type="hidden" id="ketrpj_k_1">                  
                  <input type="hidden" id="koderpj_k_1">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Piutang Retur [K]</label>
                    <select id="coarpj_k_1" name="coarpj_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                      
            </div>
          </div>
        </div>
        </div>                    

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Pembayaran Piutang</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idppj_d_1">
                  <input type="hidden" id="ketppj_d_1">                  
                  <input type="hidden" id="kodeppj_d_1">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Kas/Bank [D]</label>
                    <select id="coappj_d_1" name="coappj_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idppj_d_2">
                  <input type="hidden" id="ketppj_d_2">                  
                  <input type="hidden" id="kodeppj_d_2">                                                      
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Diskon Penjualan [D]</label>
                    <select id="coappj_d_2" name="coappj_d_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idppj_d_3">
                  <input type="hidden" id="ketppj_d_3">                  
                  <input type="hidden" id="kodeppj_d_3">                                                                        
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Piutang Retur [D]</label>
                    <select id="coappj_d_3" name="coappj_d_3" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>              
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idppj_k_1">
                  <input type="hidden" id="ketppj_k_1">                  
                  <input type="hidden" id="kodeppj_k_1">                                                      
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Piutang Invoice [K]</label>
                    <select id="coappj_k_1" name="coappj_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idppj_k_2">
                  <input type="hidden" id="ketppj_k_2">                  
                  <input type="hidden" id="kodeppj_k_2">                                                      
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Piutang Uang Muka [K]</label>
                    <select id="coappj_k_2" name="coappj_k_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                                                                                                                                     
            </div>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Penjualan Lainnya</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="iddpc_d_1">
                  <input type="hidden" id="ketdpc_d_1">                  
                  <input type="hidden" id="kodedpc_d_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Piutang Uang Muka [D]</label>
                    <select id="coadpc_d_1" name="coadpc_d_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="iddpc_d_2">
                  <input type="hidden" id="ketdpc_d_2">                  
                  <input type="hidden" id="kodedpc_d_2">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pengiriman Barang Belum Faktur [D]</label>
                    <select id="coadpc_d_2" name="coadpc_d_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                                                  
              <div class="row px-2 d-none">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="iddpc_k_1">
                  <input type="hidden" id="ketdpc_k_1">                  
                  <input type="hidden" id="kodedpc_k_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pajak Keluaran [K]</label>
                    <select id="coadpc_k_1" name="coadpc_k_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2 d-none">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="iddpc_k_2">
                  <input type="hidden" id="ketdpc_k_2">                  
                  <input type="hidden" id="kodedpc_k_2">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Uang Muka Penjualan [K]</label>
                    <select id="coadpc_k_2" name="coadpc_k_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                      
            </div>
          </div>
        </div>
        </div>

      </div>
    </div>
  </div>
  <div class="col-sm-4">
    <div class="card" style="box-shadow: none">
      <div class="card-header">
        <h3 class="card-title text-sm">
          <b>Jurnal Lainnya</b>
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Rek R/L</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrl_1">
                  <input type="hidden" id="ketrl_1">                  
                  <input type="hidden" id="koderl_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Laba Berjalan</label>
                    <select id="coarl_1" name="coarl_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idrl_2">
                  <input type="hidden" id="ketrl_2">                  
                  <input type="hidden" id="koderl_2">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Laba Ditahan</label>
                    <select id="coarl_2" name="coarl_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>              
            </div>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Akun Default Persediaan</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="iditem_1">
                  <input type="hidden" id="ketitem_1">                  
                  <input type="hidden" id="kodeitem_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Persediaan Barang</label>
                    <select id="coaitem_1" name="coaitem_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="iditem_2">
                  <input type="hidden" id="ketitem_2">                  
                  <input type="hidden" id="kodeitem_2">                                                      
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Pendapatan</label>
                    <select id="coaitem_2" name="coaitem_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="iditem_3">
                  <input type="hidden" id="ketitem_3">                  
                  <input type="hidden" id="kodeitem_3">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Harga Pokok</label>
                    <select id="coaitem_3" name="coaitem_3" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                                      
            </div>
          </div>
        </div>
        </div>

        <div class="row">
        <div class="col-sm-12">
          <div class="card card-light collapsed-card">
            <div class="card-header" data-card-widget="collapse" role="button">
              <h3 class="card-title text-sm text-gray font-weight-bold">Jurnal Penjualan Tunai (POS)</h3>
              <div class="card-tools py-0 my-0">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-angle-down text-md text-gray"></i>
                </button>
              </div>
            </div>
            <div class="card-body bg-light mx-0 px-0">
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpos_1">
                  <input type="hidden" id="ketpos_1">                  
                  <input type="hidden" id="kodepos_1">                  
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Tunai / Kas</label>
                    <select id="coapos_1" name="coapos_1" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpos_2">
                  <input type="hidden" id="ketpos_2">                  
                  <input type="hidden" id="kodepos_2">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Kartu Debit</label>
                    <select id="coapos_2" name="coapos_2" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>
              <div class="row px-2">
                <div class="form-group col-sm-12">
                  <input type="hidden" id="idpos_3">
                  <input type="hidden" id="ketpos_3">                  
                  <input type="hidden" id="kodepos_3">                                    
                  <label class="text-sm px-1 py-0 my-0 font-weight-normal">Kartu Kredit</label>
                    <select id="coapos_3" name="coapos_3" class="form-control select2 coa-jurnal text-sm py-0 my-0" style="width:100%">
                    </select>     
                </div>                
              </div>                                                           
            </div>
          </div>
        </div>
        </div>

      </div>
    </div>
  </div>
</div>
</div>