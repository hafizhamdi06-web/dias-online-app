$.fn.dataTable.ext.errMode = 'none';

var tabelgudang = null;

$('#gudang-table').on('dblclick','tr',function(e){
  $('#gudang-table').DataTable().rows(this).select();
  restable();
});

$("#bpilihgudang").click(restable);

var _gudangdatatable = function(){
  tabelgudang = $('#gudang-table').DataTable({
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
        "url": base_url + "Datatable_Master/view_table_gudang",
        "type":"post",
        "data": function(data){
          data.aktifsaja = 1;
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
          { "data": "kota" },
    ],
    "drawCallback": function(settings, json) {
      var total = tabelgudang.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");
      }else{
        $(".modal-body").addClass("noresultfound");
      }
      $('#modal input').focus();
      $('#gudang-table').removeClass("d-none");
    }
  });
}

_gudangdatatable();

function restable(){
const id = $('#gudang-table').DataTable().cell($('#gudang-table').DataTable().rows({selected:true}),0).data(),
      nama = $('#gudang-table').DataTable().cell($('#gudang-table').DataTable().rows({selected:true}),3).data(),
      trigger = $('#modaltrigger').val();

  if(id==null || typeof id=='undefined') return;

  $("#"+trigger).contents().find("#gudang").val(id);
  $("#"+trigger).contents().find("#gudangnama").val(nama);
  $('#modal').modal('hide');
  return;
}
