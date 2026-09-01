import { Component_Scrollbars } from '../component.js';

var tabel = null;

$(function () {

  $.fn.dataTable.ext.errMode = 'none';

  Component_Scrollbars('.tab-wrap','scroll','scroll');

  this.addEventListener('contextmenu', (e) => {
    e.preventDefault();
  });

  $("#badd").focus();

  tabel=$('#item-pos-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "pagingType":"simple",
    "order": [[ 2, 'asc' ]],
    "select":true,
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Master/view_table_item_pos_list",
        "type":"post",
        "data": (data) => {
          data.kode = $('#kode').val();
          data.nama = $('#nama').val();
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
          { "data": "kategori" },
          { "data": "status" },
          { "data": "satuan" }
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
      isResizable: (column) => {
        return column.idx !== 1;
      },
      onResize: (column) => {
      },
      onResizeEnd: (column, columns) => {
      }
  });

  $("#brefresh").click(() => {
    _reloaddatatable();
  });

  $("#badd").click(() => {
    if(!$('#badd').hasClass('disabled')){
      $.ajax({
        "url"    : base_url+"Modal/form_item_pos",
        "type"   : "POST",
        "dataType" : "html",
        "beforeSend": () => {
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");
          parent.window.$(".modal-title").html("Tambah Item POS");
          parent.window.$("#modaltrigger").val("iframe-page-mitempos");
        },
        "error": () => {
          parent.window.$(".loader-wrap").addClass("d-none");
          console.log('error menampilkan modal form item pos...');
          return;
        },
        "success":async (result) => {
          await parent.window.$(".main-modal-body").html(result);
          await parent.window._getDataCabang('');
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
          parent.window.$(".loader-wrap").addClass("d-none");
        }
      })
    }
  })

  $("#bdelete").click(() => {
    if(!$('#bdelete').hasClass('disabled')){
      const id = $('#item-pos-table').DataTable().cell($('#item-pos-table').DataTable().rows({selected:true}),2).data();

      if(id=="" || id==null) return;

      parent.window.Swal.fire({
        title: 'Anda yakin akan menghapus '+id+'?',
        showDenyButton: false,
        showCancelButton: true,
        confirmButtonText: `Iya`,
      }).then((result) => {
          if (result.isConfirmed) {
            _deleteData();
          }
      })
    }
  });

  $("#bedit").click(() => {
    if(!$('#bedit').hasClass('disabled')){
      const id = $('#item-pos-table').DataTable().cell($('#item-pos-table').DataTable().rows({selected:true}),0).data();

      if(id=="" || id==null) return;

      $.ajax({
        "url"    : base_url+"Modal/form_item_pos",
        "type"   : "POST",
        "dataType" : "html",
        "beforeSend": () => {
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");
          parent.window.$(".modal-title").html("Item POS");
          parent.window.$("#modaltrigger").val("iframe-page-mitempos");
        },
        "error": () => {
          parent.window.$(".loader-wrap").addClass("d-none");
          console.error('error menampilkan form item pos...');
          return;
        },
        "success": async (result) => {
          await parent.window.$(".main-modal-body").html(result);
          await parent.window._getData(id);
          await parent.window._getDataCabang(id);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
          parent.window.$(".loader-wrap").addClass("d-none");
        }
      })
    }
  });

  $('#item-pos-table').on('dblclick','tr',function(e){
      e.preventDefault();
      e.stopPropagation();
      tabel.rows(this).select();
      $('#bedit').click();
  })

  $("#bfilter").click(() => {
    if($("#fDataTable").hasClass("d-none")){
      $("#item-pos-table").removeClass("w-100");
      $("#item-pos-table").addClass("w-75");
      $("#fDataTable").removeClass("d-none");
    }else {
      $("#item-pos-table").removeClass("w-75");
      $("#item-pos-table").addClass("w-100");
      $("#fDataTable").addClass("d-none");
    }
  })

  $("#submitfilter").click(() => {
    $('#item-pos-table').DataTable().ajax.reload();

    if (window.matchMedia('screen and (max-width: 768px)').matches) {
      $("#item-pos-table").removeClass("w-75");
      $("#item-pos-table").addClass("w-100");
      $("#fDataTable").addClass("d-none");
    }
  })

  $('#kode,#nama').keydown((e) => {
    if(e.keyCode==13) $('#submitfilter').click();
  });

});

var _deleteData = (() => {
  const id = $('#item-pos-table').DataTable().cell($('#item-pos-table').DataTable().rows({selected:true}),0).data();

  if(id == "" || id == null) return;

  $.ajax({
    "url"    : base_url+"Master_Item_POS/deletedata",
    "type"   : "POST",
    "data"   : "id="+id,
    "cache"    : false,
    "beforeSend" : () => {
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": (xhr, status, error) => {
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Error : "+xhr.status+", "+error);
      console.error(xhr.responseText);
      return;
    },
    "success": (result) => {
      parent.window.$(".loader-wrap").addClass("d-none");
      if(result=='sukses'){
          parent.window.toastr.success("Data item POS berhasil dihapus");
          _reloaddatatable();
          return;
      } else {
          parent.window.toastr.error(result);
          return;
      }
    }
  })
})

function _reloaddatatable(){
  clearFilter();
  $('#item-pos-table').DataTable().ajax.reload();
}

function clearFilter(){
  $('#kode,#nama').val('');
}
