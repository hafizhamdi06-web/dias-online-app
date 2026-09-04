import { Component_Scrollbars } from '../component.js';

var tabel = null;

$(function () {

  $.fn.dataTable.ext.errMode = 'none';

  Component_Scrollbars('.tab-wrap','scroll','scroll');

  this.addEventListener('contextmenu', (e) => {
    e.preventDefault();
  });

  $("#bedit").focus();

  $('#status').select2({
    "theme":"bootstrap4",
    "dropdownParent": $('#fDataTable'),
    "minimumResultsForSearch": "Infinity"
  });

  tabel=$('#update-harga-mp-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "pagingType":"simple",
    "order": [[ 2, 'asc' ]],
    "select": true,
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Master/view_table_update_harga_mp_list",
        "type":"post",
        "data": (data) => {
          data.kode = $('#kode').val();
          data.nama = $('#nama').val();
          data.status = $('#status').val();
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
          { "data": "kode" },
          { "data": "nama" },
          { "data": "hargajual1", "className": "text-right", "render": (d) => _formatNumber(d) },
          { "data": "hargamp", "className": "text-right", "render": (d) => _formatNumber(d) }
    ],
    "drawCallback": () => {
      var total = tabel.data().count();

      if(total>0){
        $(".tab-wrap").removeClass("noresultfound-x");
      }else{
        $(".tab-wrap").addClass("noresultfound-x");
      }

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
      isResizable: (column) => column.idx !== 1,
      onResize: () => {},
      onResizeEnd: () => {}
  });

  $("#brefresh").click(() => {
    _clearFilterReload();
  });

  $("#bedit").click(() => {
    if($('#bedit').hasClass('disabled')) return;

    const id = $('#update-harga-mp-table').DataTable().cell($('#update-harga-mp-table').DataTable().rows({selected:true}),0).data();
    if(id=="" || id==null) return;

    $.ajax({
      "url"    : base_url+"Modal/form_update_harga_mp",
      "type"   : "POST",
      "dataType" : "html",
      "beforeSend": () => {
        parent.window.$(".loader-wrap").removeClass("d-none");
        parent.window.$(".modal").modal("show");
        parent.window.$(".modal-title").html("Update Harga Marketplace");
        parent.window.$("#modaltrigger").val("iframe-page-updharga_mp");
      },
      "error": () => {
        parent.window.$(".loader-wrap").addClass("d-none");
        console.error('error menampilkan form update harga marketplace...');
      },
      "success": async (result) => {
        await parent.window.$(".main-modal-body").html(result);
        await parent.window._getDataHargaMp(id);
        parent.window.$(".loader-wrap").addClass("d-none");
      }
    });
  });

  $('#update-harga-mp-table').on('dblclick','tr',function(e){
    e.preventDefault();
    e.stopPropagation();
    tabel.rows(this).select();
    $('#bedit').click();
  });

  $("#bfilter").click(() => {
    if($("#fDataTable").hasClass("d-none")){
      $("#update-harga-mp-table").removeClass("w-100").addClass("w-75");
      $("#fDataTable").removeClass("d-none");
    }else {
      $("#update-harga-mp-table").removeClass("w-75").addClass("w-100");
      $("#fDataTable").addClass("d-none");
    }
  });

  $("#submitfilter").click(() => {
    $('#update-harga-mp-table').DataTable().ajax.reload();

    if (window.matchMedia('screen and (max-width: 768px)').matches) {
      $("#update-harga-mp-table").removeClass("w-75").addClass("w-100");
      $("#fDataTable").addClass("d-none");
    }
  });

  $('#kode,#nama').keydown((e) => {
    if(e.keyCode==13) $('#submitfilter').click();
  });

});

function _formatNumber(n){
  n = parseFloat(n) || 0;
  return n.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 2 });
}

// reload tabel TANPA mengubah filter & posisi halaman (dipakai setelah simpan dari modal)
function _reloaddatatable(){
  $('#update-harga-mp-table').DataTable().ajax.reload(null, false);
}

// reset filter lalu reload (tombol Refresh)
function _clearFilterReload(){
  $('#kode,#nama').val('');
  $('#status').val('0').trigger('change');
  $('#update-harga-mp-table').DataTable().ajax.reload();
}

// diakses dari form modal (parent window) setelah simpan
window._reloaddatatable = _reloaddatatable;
