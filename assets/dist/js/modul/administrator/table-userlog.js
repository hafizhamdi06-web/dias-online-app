var tabel = null;

$(function () {

  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
  });

  $("#brefresh").focus();

  $('.tab-wrap').overlayScrollbars({
  className: "os-theme-dark",
  overflowBehavior : {
    x :'scroll',
    y :'scroll' 
  },
  scrollbars : {
    autoHide : 'scroll',
    autoHideDelay : 300,
    snapHandle:true             
  }
  }); 

  tabel=$('#userlog-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "pagingType":"simple",    
    "select":true,
    "order": [[ 0, 'desc' ]], 
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Administrator/view_table_userlog",
        "type":"post"                   
    },
    "deferRender": true,
    "bInfo":true,
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
          { "data": "user" },
          { "data": "komputer" },
          { "data": "tanggal" },
          { "data": "jam" },
          { "data": "kegiatan" },
          { "data": "level" }                              
    ],
    "drawCallback": function(settings) {
      if(!parent.window.$(".loader-wrap").hasClass("d-none")){
        parent.window.$(".loader-wrap").addClass("d-none");
      }
      if($(".table").hasClass("d-none")){   
        $(".table").removeClass("d-none");
      }                  
      $(".dataTables_processing").removeClass("d-none");                                               
    }                    
  });

  $(".dataTables_processing").addClass("d-none");                               

  new $.fn.dataTable.ColResize(tabel, {
    isEnabled: true,
    hoverClass: 'dt-colresizable-hover',
    hasBoundCheck: true,
    minBoundClass: 'dt-colresizable-bound-min',
    maxBoundClass: 'dt-colresizable-bound-max',
    isResizable: function(column) { 
      return column.idx !== 1; 
    },
    onResize: function(column) {
    },
    onResizeEnd: function(column, columns) {
    }
  });

  $("#brefresh").click(function() {
    _reloaddatatable();
  });

});  

function _reloaddatatable(){
  $('#userlog-table').DataTable().ajax.reload();  
}