<div class="modal-body">   
<input type="hidden" id="namaview" class="noclear" name="namaview">
<input type="hidden" id="cabanguser" class="noclear" name="cabanguser" value="<? echo @$_SESSION['cabang']; ?>">  
<input type="hidden" id="kodecabang" class="noclear" name="kodecabang" value="<? echo @$_SESSION['kodecabang']; ?>">
<input type="hidden" id="namacabang" class="noclear" name="namacabang" value="<? echo @$_SESSION['namagudang']; ?>">
<input type="hidden" id="allcabang" class="noclear" name="allcabang" value="<? echo @$_SESSION['allcabang']; ?>">  

<div class="container-fluid px-0">  
                <div class="col-12">
                    <div class="form-group row my-0">   
                        <div class="col-7">
                               <select id="cabang" name="cabang" class="cabang form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto" placeholder="[Auto]" ></select> 
                        </div> 
                          
                      <div class="col-3">
                               <button type="button" id="btampilkan" name="btampilkan" class="btn btn-primary btn-sm px-4">Tampilkan</button>
                        </div>   
                        
                    </div>  
                </div>   
    </div> 
    
    
    
    
    
  <table id="transaksi-table" class="table table-sm table-striped table-hover table-responsive w-100 bg-light nowrap d-none">
    <thead>
    <tr>
    <th class="d-none"></th>            
    <th></th>
    <th class="text-sm">Nomor</th>
    <th class="text-sm">Tanggal</th>
    <th class="text-sm">Nama Kontak</th>
    </tr>
    </thead>
  </table>              
</div>            
</div>
<div class="modal-footer">
    <div class="form-group">
        <div class="col-sm-offset-3">
            <a class="btn btn-outline-primary btn-sm px-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
            <button type="button" id="bpilihtransaksi" name="bpilihtransaksi" class="btn btn-primary btn-sm px-4">Pilih</button>
        </div>
    </div>                
</div>

<script src="<? echo app_url('assets/dist/js/modul/include/modal-transaksi.js'); ?>"></script> 