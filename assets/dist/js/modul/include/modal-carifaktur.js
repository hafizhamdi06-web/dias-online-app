//$.fn.dataTable.ext.errMode = 'none';      

var tabeltransaksi = null;

$('#transaksi-table').on('dblclick','tr',function(e){
  $('#transaksi-table').DataTable().rows(this).select();              
  restable();
});

$("#bpilihtransaksi").click(restable);

var _transaksidatatable = function(_view='',_kontak=''){
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
        "url":base_url+"Datatable_Transaksi/"+_view+"/"+_kontak,
        "type":"post",
        "data": function(data){
          data.param = $('#param1').val();
        },       
        "error": function(xhr){
          console.log(xhr.responseText);
        }, 
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
          { "orderable": false,
            "render": function ( data, type, row ) {
                var html ="<input type='checkbox' id='"+row.id+"' name='rw[]' class='mt-1' value='"+row.nomor+"'>";
                return html;
                }
          },
          { "data": "nomor" },
          { "data": "tanggal" },
          { "data": "kontak" },
    ],
    "drawCallback": function(settings, json) {
      var total = tabeltransaksi.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");                                   
      }else{
        $(".modal-body").addClass("noresultfound");                                   
      }

      $("#modal input[type='search']").focus();                                
      $('#transaksi-table').removeClass("d-none");     
    }    
  });
}

function restable(){
  var id = [];        
  var totalcek = $("input:checkbox[name='rw[]']:checked").length;
  var totalrow = $("input:checkbox[name='rw[]']").length; 
  var inps = document.getElementsByName('rw[]');
  var trigger = $('#modaltrigger').val();
  var coltrigger = $('#coltrigger').val();
  
  if(totalcek>0){
    for(var i=0;i<totalrow;i++){
      var inp=inps[i];
        if(inp.checked==true){
          id.push(JSON.stringify(inp.value));    
        }
    }
  }else{
    id.push(JSON.stringify($('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),2).data()));         
  }

  $('#modal').modal('hide');        
  $("#"+trigger).contents().find("#"+coltrigger).val(id);    
  return;        
}