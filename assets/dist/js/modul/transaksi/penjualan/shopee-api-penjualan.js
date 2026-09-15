var tabel = null;

toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

$(function () {

  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
  });

  $("#brefresh").focus();

  $('.tab-wrap').overlayScrollbars({
    className: "os-theme-dark",
    overflowBehavior : { x :'scroll', y :'scroll' },
    scrollbars : { autoHide : 'scroll', autoHideDelay : 300, snapHandle:true }
  });

  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    orientation: 'bottom',
    container: 'body'
  });
  $("#dtgldari").click(function(){ $("#tgldari").datepicker('show'); });
  $("#dtglsampai").click(function(){ $("#tglsampai").datepicker('show'); });

  var hariIni = new Date();
  $('#tgldari').datepicker('setDate', hariIni);
  $('#tglsampai').datepicker('setDate', hariIni);

  tabel=$('#order-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",
    "select":true,
    "order": [[ 4, 'desc' ]],
    "dom": '<"top"fpi>tr<"clear">',
    "ajax": {
        "url":base_url+"Shopee_Api/view_order_list",
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
          { "data": "order_sn" },
          { "data": "order_status" },
          { "data": "create_time" },
          { "data": "total_amount", "className": "text-right" },
          { "data": "buyer_username" },
          { "data": "recipient_name" },
          { "data": "payment_method" },
          { "data": "jumlah_item", "className": "text-right" },
          { "data": "ditarik_pada" }
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
    isResizable: function(column) { return column.idx !== 1; },
    onResize: function(column) {},
    onResizeEnd: function(column, columns) {}
  });

  $("#brefresh").click(function() {
    _reloaddatatable();
  });

  $("#btarik").click(function() {
    _tarikPenjualan();
  });

});

function _reloaddatatable(){
  $('#order-table').DataTable().ajax.reload();
}

function _tarikPenjualan(){
  var tgldari = $('#tgldari').val();
  var tglsampai = $('#tglsampai').val();
  if (!tgldari || !tglsampai) {
    toastr.error('Isi Dari Tanggal & Sampai Tanggal dulu.');
    return;
  }

  $.ajax({
    "url": base_url+"Shopee_Api/tarik_penjualan",
    "type": "POST",
    "dataType": "json",
    "timeout": 180000,
    "data": { tgldari: tgldari, tglsampai: tglsampai },
    "beforeSend": function(){
      $("#btarik").prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Menarik dari Shopee...');
    },
    "error": function(xhr, status, error){
      $("#btarik").prop('disabled', false).html('<i class="fas fa-sync"></i> Tarik Penjualan dari Shopee');
      toastr.error("Gagal menarik data : "+xhr.status+" "+error);
    },
    "success": function(result){
      $("#btarik").prop('disabled', false).html('<i class="fas fa-sync"></i> Tarik Penjualan dari Shopee');

      if (result.pesan === 'sukses') {
        Swal.fire({
          title: 'Berhasil',
          text: result.jumlah_order+' order ('+result.jumlah_baris+' baris item) berhasil ditarik/diperbarui.',
          icon: 'success',
          confirmButtonText: 'OK'
        });
        _reloaddatatable();
      } else {
        toastr.error(result.error || 'Gagal menarik penjualan.', "", {timeOut: 20000});
      }
    }
  });
}
