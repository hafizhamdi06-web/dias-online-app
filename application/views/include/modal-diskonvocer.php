<div class="modal-body">
<input type="hidden" id="idkontak" name="idkontak"> 

<div class="container-fluid px-0">
  <table id="transaksi-table" class="table table-sm table-striped table-hover w-100 bg-light nowrap d-none">
    <thead>
    <tr>
    <th class="d-none"></th>            
    <th></th>
     
    <th class="text-sm">Nomor</th> 
    <th class="text-sm">Nilai</th>  
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

<script src="<? echo app_url('assets/dist/js/modul/include/modal-diskonvocer.js'); ?>"></script> 