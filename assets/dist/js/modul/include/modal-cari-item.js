$.fn.dataTable.ext.errMode = 'none';

var tabelitem = null;

$('#cari-item-table').on('dblclick','tr',function(e){
  $('#cari-item-table').DataTable().rows(this).select();
  restable();
});

$("#bpilihitem").click(restable);

var _itemdatatable = function(){
  tabelitem = $('#cari-item-table').DataTable({
    "destroy":true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",
    "order": [[ 3, 'asc' ]],
    "select":true,
    "dom": '<"#sTable"f><"top"p>tr<"clear">',
    "ajax": {
        "url": base_url + "Datatable_Master/view_table_item",
        "type":"post",
        "data": function(data){
          data.cabangsaja = 1;
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
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },
          { "data": "kode"  },
          { "data": "nama" },
          { "data": "jumlah" },
          { "data": "satuan" },
    ],
    "drawCallback": function(settings, json) {
      var total = tabelitem.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");
      }else{
        $(".modal-body").addClass("noresultfound");
      }
      $('#modal input').focus();
      $('#cari-item-table').removeClass("d-none");
    }
  });
}

_itemdatatable();

function restable(){
const id = $('#cari-item-table').DataTable().cell($('#cari-item-table').DataTable().rows({selected:true}),0).data(),
      trigger = $('#modaltrigger').val(),
      idx = $('#coltrigger').val();

  if(id==null || typeof id=='undefined') return;

  document.getElementById(trigger).contentWindow._pilihItemBaris(idx, id);

  $('#modal').modal('hide');
  return;
}
