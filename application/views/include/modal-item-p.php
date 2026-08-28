<div class="modal-body">
<input type="hidden" id="idkatkontak" name="idkatkontak">
<style>
#contact-table th, #contact-table td {
  font-size: 0.95rem;
  padding-top: 6px;
  padding-bottom: 6px;
}
</style>
<div class="container-fluid px-0">
  <table id="contact-table" class="table table-striped table-hover  table-responsive w-100 bg-light nowrap d-none"  >
    <thead>
    <tr>
    <th class="d-none"></th>
    <th></th>
    <th class="font-weight-bold">Kode</th>
    <th class="font-weight-bold" width="10%" >Nama</th>
    <th class="font-weight-bold" width="10%" >Harga Jual</th>
    <th class="font-weight-bold" width="10%" >Stok</th>
    <th class="font-weight-bold" width="10%" >Tipe</th>
    </tr>
    </thead>
  </table>
</div>
</div>
<div class="modal-footer">
    <div class="form-group">
        <div class="col-sm-offset-3">
            <a class="btn btn-outline-primary btn-sm px-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
            <button type="button" id="bpilihkontak" name="bpilihkontak" class="btn btn-primary btn-sm px-4">Pilih</button>
        </div>
    </div>                
</div>

<script src="<? echo app_url('assets/dist/js/modul/include/modal-item-p.js'); ?>"></script> 