$.fn.dataTable.ext.errMode = 'none';      



              
var tabeltransaksi = null;

$('#transaksi-table').on('dblclick','tr',function(e){
  $('#transaksi-table').DataTable().rows(this).select();              
  restable();
});

$("#bpilihtransaksi").click(restable);

var _transaksidatatable = function(_view=''  ){  

  cabang = $('#cabang').val();
  
  tabeltransaksi=$('#transaksi-table').DataTable({
    "destroy":true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",        
    "order": [[0, 'desc' ]],      
    "select":true,      
    "dom": '<"#sTable"f><"top"p>tr<"clear">',        
    "ajax": {
        "url":base_url+"Datatable_Transaksi/"+_view+"/"+cabang,
        "type":"post",
        "beforeSend": function(){
          $(".loader-wrap").addClass("d-none");            
        }                                              
    },
    "deferRender": true,
    "bInfo":false,
    "aLengthMenu": [[20, 50, 100],[20, 50, 100]],    
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },                      
    "columns": [
          { "data": "id" },
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },    
          { "data": "nomor" },
          { "data": "tanggal" },
          { "data": "kontak" },
    ],
    "drawCallback": function() {
      var total = tabeltransaksi.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");                                   
      }else{
        $(".modal-body").addClass("noresultfound");                                   
      }

      $('#modal input').focus();                                
      $('#transaksi-table').removeClass("d-none");   
    }    
  });
}


 $("#btampilkan").click(function() {   
  var xview = $('#namaview').val();  
 
  _transaksidatatable(xview); 
     
 });
 
function _setcabang(){

  $('#cabang').select2({
       "allowClear": true,
       "theme":"bootstrap4",        
       "dropdownParent": $('#transaksi-table'),     
       "ajax": {
          "url": base_url+"Select_Master/view_gudang",
          "type": "post",
          "dataType": "json",                                       
          "delay": 800,
          "data": function(params) {
            return {
              search: params.term
            }
          },
          "processResults": function (data, page) {
          return {
            results: data
          };
        },
      }
  });
  
   
        const _cabang = $("<option selected='selected'></option>").val($('#cabanguser').val()).text($('#kodecabang').val());  
        $('#cabang').append(_cabang); 
        var allcabang = $('#allcabang').val();   
        
       if (allcabang==0)  $('#cabang').prop('disabled',true); 
       
}   


function restable(){
  const id = $('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),0).data(),
      trigger = $('#modaltrigger').val();
  $('#modal').modal('hide');

  var iframeWindow = document.getElementById(trigger) ? document.getElementById(trigger).contentWindow : null;

  if(iframeWindow && typeof iframeWindow._pilihTransaksi === 'function'){
    iframeWindow._pilihTransaksi(id);
  } else {
    $("#"+trigger).contents().find("#id").val(id).trigger('change');
  }
  return;
}