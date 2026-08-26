$.fn.dataTable.ext.errMode = 'none';      

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
                var html ="<input type='checkbox' id='"+row.id+"' name='rw[]' class='mt-1' value='"+row.id+"'>";
                return html;
                }
          },
          { "data": "nomor" },
          { "data": "tanggal" },
          { "data": "jumlah",
            "className": 'aright',          
            "render": (data, type, row,meta) => {
              debit = data;
              data = accounting.formatMoney(data);
              return data;
            }           
          },
          { "data": "uraian" },          
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

async function restable(){
  var id = [];        
  var totalcek = $("input:checkbox[name='rw[]']:checked").length;
  var totalrow = $("input:checkbox[name='rw[]']").length; 
  var inps = document.getElementsByName('rw[]');
  var trigger = $('#modaltrigger').val();
  var totaldp = 0;
  
  if(totalcek>0){
    for(var i=0;i<totalrow;i++){
      var inp=inps[i];
        if(inp.checked==true){
          id.push(inp.value);
          totaldp += Number($('#transaksi-table').DataTable().cell(i,4).data());    
        }
    }
  }else{
    id.push($('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),0).data());         
    totaldp = Number($('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),4).data());
  }

  $('#modal').modal('hide');        
  await $("#"+trigger).contents().find("#tdp").val(totaldp).trigger('change');        
  $("#"+trigger).contents().find("#iddp").val(id).trigger('change');
  return;        
}