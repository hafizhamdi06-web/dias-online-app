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

  tabel=$('#produk-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",
    "select":true,
    "order": [[ 4, 'asc' ]],
    "dom": '<"top"fpi>tr<"clear">',
    "ajax": {
        "url":base_url+"Shopee_Api/view_produk_list",
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
          { "data": "item_id" },
          { "data": "item_sku" },
          { "data": "item_name" },
          { "data": "harga", "className": "text-right" },
          { "data": "stok", "className": "text-right" },
          { "data": "item_status" },
          { "data": "cocok_kode_lokal" },
          { "data": "update_time" },
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
    _tarikProduk();
  });

});

function _reloaddatatable(){
  $('#produk-table').DataTable().ajax.reload();
}

function _tarikProduk(){
  $.ajax({
    "url": base_url+"Shopee_Api/cek_produk",
    "type": "POST",
    "dataType": "json",
    "timeout": 120000,
    "beforeSend": function(){
      $("#btarik").prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Menarik dari Shopee...');
    },
    "error": function(xhr, status, error){
      $("#btarik").prop('disabled', false).html('<i class="fas fa-sync"></i> Tarik Produk dari Shopee');
      toastr.error("Gagal menarik data : "+xhr.status+" "+error);
    },
    "success": function(result){
      $("#btarik").prop('disabled', false).html('<i class="fas fa-sync"></i> Tarik Produk dari Shopee');

      if (result.pesan === 'sukses') {
        Swal.fire({
          title: 'Berhasil',
          text: result.jumlah+' produk Shopee berhasil ditarik/diperbarui.',
          icon: 'success',
          confirmButtonText: 'OK'
        });
        _reloaddatatable();
      } else {
        toastr.error(result.error || 'Gagal menarik produk.', "", {timeOut: 20000});
      }
    }
  });
}
