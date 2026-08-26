$.fn.dataTable.ext.errMode = 'none';      

var tabeltransaksi = null;

var _transaksidatatable = function(_nomor){
  //alert(_nomor);
  tabeltransaksi=$('#transaksi-table').DataTable({
    "destroy":true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",        
    "order": [[0, 'asc' ]],      
    "select":true,      
    "dom": '<"top"pi>tr<"clear">',        
    "ajax": {
        "url":base_url+"Datatable_Transaksi_Full/view_jurnal_transaksi",
        "type":"post",
        "data": function(data){
          data.nomor = _nomor;
        },
        "beforeSend": function(){
          $(".loader-wrap").addClass("d-none");            
        }                                                      
    },
    "deferRender": true,
    "bInfo":false,
    "aLengthMenu": datapage,  
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
          { "orderable":false,"data": "coa" },
          { "orderable":false,"data": "debit" },
          { "orderable":false,"data": "kredit" },
    ],
    "columnDefs": [
          {
            "render": function (data, type, row) {
                 data = commaSeparateNumber(data);
                 data = "<span style='float:right'>"+data+"</span>";
                 return data;
            },
            "targets": [3,4]
          }                    
    ],    
    "drawCallback": function() {
      var total = tabeltransaksi.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");                                   
      }else{
        $(".modal-body").addClass("noresultfound");                                   
      }

      $('#transaksi-table').removeClass("d-none");   
    }    
  });
}
