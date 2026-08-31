var tabel = null;

$(function () {

  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
  });

  $("#brefresh").focus();

  $('.datepicker').inputmask({
    alias:'dd/mm/yyyy',
    mask: "1-2-y",
    placeholder: "_",
    leapday: "-02-29",
    separator: "-"
  });

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    orientation: 'bottom',
    container: 'body'
  }).on('show', function(){
    var $input = $(this);
    var offset = $input.offset();
    $('.datepicker.datepicker-dropdown:visible').css({
      'z-index': 99999,
      'top': (offset.top + $input.outerHeight()) + 'px',
      'left': offset.left + 'px'
    });
  });

  $("#dtgldari").click(function() {
    $("#tgldari").datepicker('show');
  });

  $("#dtglsampai").click(function() {
    $("#tglsampai").datepicker('show');
  });

  $('#tgldari').datepicker('setDate', new Date());
  $('#tglsampai').datepicker('setDate', new Date());

  $('#cabang').select2({
    "allowClear": true,
    "theme": "bootstrap4",
    "ajax": {
      "url": base_url+"Select_Master/view_gudang_pilihan",
      "type": "post",
      "dataType": "json",
      "delay": 800,
      "data": function(params){
        return { search: params.term };
      },
      "processResults": function(data){
        return { results: data };
      }
    }
  });

  var _cabangDefault = $("<option selected='selected'></option>")
    .val($('#cabangdefault').val())
    .text($('#namacabangdefault').val());
  $('#cabang').append(_cabangDefault).trigger('change');

  $("#bfilter").click(function() {
    if($("#fDataTable").hasClass("d-none")){
      $("#userlog-table").removeClass("w-100");
      $("#userlog-table").addClass("w-75");
      $("#fDataTable").removeClass("d-none");
    }else {
      $("#userlog-table").removeClass("w-75");
      $("#userlog-table").addClass("w-100");
      $("#fDataTable").addClass("d-none");
    }
  });

  $("#submitfilter").click(function() {
    _reloaddatatable();
    if (window.matchMedia('screen and (max-width: 768px)').matches) {
      $("#userlog-table").removeClass("w-75");
      $("#userlog-table").addClass("w-100");
      $("#fDataTable").addClass("d-none");
    }
  });

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
        "type":"post",
        "data": function(data){
          data.tgldari = $('#tgldari').val();
          data.tglsampai = $('#tglsampai').val();
          data.cabang = $('#cabang').val();
        }
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
      if($(".table-utils").hasClass("d-none")){
        $(".table-utils").removeClass("d-none");
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