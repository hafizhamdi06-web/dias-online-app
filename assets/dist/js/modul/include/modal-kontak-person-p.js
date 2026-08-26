$.fn.dataTable.ext.errMode = 'none';      

var tabelperson = null;

$('#person-table').on('dblclick','tr',function(e){
  $('#person-table').DataTable().rows(this).select();              
  restable();
});

$("#bpilihkontak").click(restable);

var _persondatatable = function(_id_kontak){
  tabelperson=$('#person-table').DataTable({
    "destroy":true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",        
    "order": [[ 2, 'asc' ]],      
    "select":true,      
    "dom": '<"#sTable"f><"top"p>tr<"clear">',        
    "ajax": {
        "url":base_url+"Datatable_Master/view_table_kattention/"+_id_kontak,
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
          { "data": "nama" },
          { "data": "jabatan" },
    ],
    "drawCallback": function(settings, json) {
      var total = tabelperson.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");                                   
      }else{
        $(".modal-body").addClass("noresultfound");                                   
      }

      $('#modal input').focus();                                
      $('#person-table').removeClass("d-none"); 
    }            
  });
}

function restable(){
  var id = $('#person-table').DataTable().cell($('#person-table').DataTable().rows({selected:true}),0).data(),
      nama = $('#person-table').DataTable().cell($('#person-table').DataTable().rows({selected:true}),2).data(),        
      trigger = $('#modaltrigger').val();         
  $('#modal').modal('hide');        
  $("#"+trigger).contents().find("#idperson").val(id);
  $("#"+trigger).contents().find("#person").val(nama);        
  return;        
}