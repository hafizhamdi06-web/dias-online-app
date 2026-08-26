<div class="modal-body">
<input type="hidden" id="idkatkontak" name="idkatkontak">

 

    <div class="container-fluid px-3">  
                <div class="col-12">
                    <div class="form-group row my-0">  
                        <div class="col-7">
                               <select id="jenispasien" name="jenispasien" class="jenispasien form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                        </div> 
                        
                         <div class="col-4">   
                                <button type="button" id="btampilkan" name="btampilkan" class="btn btn-primary btn-sm px-4">Tampilkan</button>
                        </div>   
                        
                        
                        
                        
                        <div class="col-4 d-none">
                             <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="" id="chkaktifsaja">
                              <label class="form-check-label" for="chkaktifsaja">
                                Aktif Saja
                              </label>
                            </div>  
                        </div>   
                    
                        
                    </div>  
                </div>    
                <div class="col-12">
                    <div class="form-group row my-0">  
                        <div class="col-7">
                               <select id="cabang" name="cabang" class="cabang form-control select2 form-control-sm"  data-trigger="manual" data-placement="auto"></select> 
                        </div> 
                         
                        
                       
                    
                        
                    </div>  
                </div>   
    </div> 
                                  
                                  

<div class="container-fluid px-0">
  <table id="contact-table" class="table table-sm table-striped table-hover table-responsive w-100 bg-light nowrap d-none">
    <thead>
    <tr>
    <th class="d-none"></th>            
    <th></th>
    <th class="text-sm">Kode</th>
    <th class="text-sm">Nama</th>
    <th class="text-sm">Kategori</th>
    <th class="text-sm">Cabang</th>
    <th class="text-sm">Alamat</th>
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

<script src="<? echo app_url('assets/dist/js/modul/include/modal-kontak-pos.js'); ?>"></script> 