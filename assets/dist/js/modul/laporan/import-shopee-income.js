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
        "url":base_url+"Import_Shopee_Income/view_riwayat",
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
          { "data": "no_pesanan" },
          { "data": "username_pembeli" },
          { "data": "tgl_pesanan" },
          { "data": "tgl_dana_dilepaskan" },
          { "data": "metode_pembayaran" },
          { "data": "total_penghasilan" },
          { "data": "diimport_oleh" },
          { "data": "diimport_pada" }
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

  $("#bimport").click(function() {
    _importExcel();
  });

});

function _reloaddatatable(){
  $('#riwayat-table').DataTable().ajax.reload();
}

function _importExcel(){
  var fileInput = document.getElementById('file-excel');
  if (!fileInput.files || fileInput.files.length === 0) {
    toastr.error('Pilih file Excel (.xlsx) terlebih dahulu !');
    return;
  }

  var formData = new FormData();
  formData.append('file', fileInput.files[0]);

  $.ajax({
    "url": base_url+"Import_Shopee_Income/upload",
    "type": "POST",
    "data": formData,
    "processData": false,
    "contentType": false,
    "dataType": "json",
    "cache": false,
    "beforeSend": function(){
      $("#bimport").prop('disabled', true).html('<i class="fas fa-circle-notch fa-spin"></i> Memproses...');
    },
    "error": function(xhr, status, error){
      $("#bimport").prop('disabled', false).html('<i class="fas fa-file-import"></i> Import');
      toastr.error("Gagal mengupload file : "+xhr.status+" "+error);
    },
    "success": function(result){
      $("#bimport").prop('disabled', false).html('<i class="fas fa-file-import"></i> Import');

      if (result.pesan === 'sukses') {
        Swal.fire({
          title: 'Import berhasil',
          text: result.jumlah+' baris data pesanan berhasil diimport/diperbarui.',
          icon: 'success',
          confirmButtonText: 'OK'
        });
        $('#file-excel').val('');
        _reloaddatatable();
      } else {
        toastr.error(result.error || 'Gagal mengimport data.');
      }
    }
  });
}
