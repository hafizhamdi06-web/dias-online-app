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

  $("#bfilter").click(function() {
    if($("#fDataTable").hasClass("d-none")){
      $("#riwayat-table").removeClass("w-100");
      $("#riwayat-table").addClass("w-75");
      $("#fDataTable").removeClass("d-none");
    }else {
      $("#riwayat-table").removeClass("w-75");
      $("#riwayat-table").addClass("w-100");
      $("#fDataTable").addClass("d-none");
    }
  });

  $("#submitfilter").click(function() {
    _reloaddatatable();
    if (window.matchMedia('screen and (max-width: 768px)').matches) {
      $("#riwayat-table").removeClass("w-75");
      $("#riwayat-table").addClass("w-100");
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

  tabel=$('#riwayat-table').DataTable({
    "destroy": true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",
    "select":true,
    "order": [[ 0, 'desc' ]],
    "dom": '<"top"fpi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Transaksi_Full/view_persetujuan_riwayat",
        "type":"post",
        "data": function(data){
          data.status = $('#status').val();
          data.jenis = $('#jenis').val();
          data.referensi = $('#referensi').val();
          data.keterangan = $('#keterangan').val();
          data.tgldari = $('#tgldari').val();
          data.tglsampai = $('#tglsampai').val();
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
          { "data": "jenis" },
          { "data": "referensi" },
          { "data": "keterangan" },
          { "data": "pemohon" },
          { "data": "approver" },
          { "data": "status" },
          { "data": "tglminta" },
          { "data": "tglrespon" },
          { "data": "catatan" }
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
  $('#riwayat-table').DataTable().ajax.reload();
}
