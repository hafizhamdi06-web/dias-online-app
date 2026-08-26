$.fn.dataTable.ext.errMode = 'none';      

var tabeltransaksi = null;

$('#transaksi-table').on('dblclick','tr',function(e){
  $('#transaksi-table').DataTable().rows(this).select();              
  restable();
});

$("#bpilihtransaksi").click(restable);

var _transaksidatatable = function(_view=''){ 

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
        "url":base_url+"Datatable_Transaksi/"+_view,
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

function restable(){
  const id = $('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),0).data(),
      trigger = $('#modaltrigger').val();         
  $('#modal').modal('hide');        
  $("#"+trigger).contents().find("#id").val(id).trigger('change');
  return;        
}