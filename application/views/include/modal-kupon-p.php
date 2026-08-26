<div class="modal-body">
<input type="hidden" id="idkatkontak" name="idkatkontak">  

<div class="form-group row my-0">
    <label class="col-6 col-form-label text-sm font-weight-normal" >Id Transaksi</label>
    <input class="col-3 col-form-label form-control form-control-sm " type="text" id="idu" name="idu" disabled> 
</div> 

<div class="form-group row my-0">
    <label class="col-6 col-form-label text-sm font-weight-normal" >Pasien Mendapatkan Voucher Sebanyak</label>
    <input class="col-3 col-form-label form-control form-control-sm " type="text" id="jumlahkupon" name="jumlahkupon" disabled> 
</div>  

<div class="container-fluid px-0">
  <table id="contact-table" class="table table-sm table-striped table-hover table-responsive w-100 bg-light nowrap d-none">
    <thead>
    <tr>
    <th class="d-none"></th>            
    <th></th>
    <th class="text-sm">No Voucher</th>  
    </tr>
    </thead>
  </table>              
</div>            
</div>
<div class="modal-footer">
    <div class="form-group">
        <div class="col-sm-offset-3"> 
            <a class="btn btn-outline-primary btn-sm px-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
            <button type="button" id="bpilihkontak" name="bpilihkontak" class="btn btn-primary btn-sm px-4">Simpan</button>
        </div>
    </div>                
</div>

<script src="<? echo app_url('assets/dist/js/modul/include/modal-kupon-p.js'); ?>"></script> 