$.fn.dataTable.ext.errMode = 'none';

var tabeltujuan = null;

$('#tujuan-table').on('dblclick','tr',function(e){
  $('#tujuan-table').DataTable().rows(this).select();
  restable();
});

$("#bpilihtujuan").click(restable);

var _tujuandatatable = function(){
  tabeltujuan = $('#tujuan-table').DataTable({
    "destroy":true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",
    "order": [[ 2, 'asc' ]],
    "select":true,
    "dom": '<"#sTable"f><"top"p>tr<"clear">',
    "ajax": {
        "url": base_url + "Datatable_Master/view_table_tujuan",
        "type":"post",
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
          { "data": "nama"  },
          { "data": "gudang" },
          { "data": "idgudang" },
          { "data": "kode" },
    ],
    "drawCallback": function(settings, json) {
      var total = tabeltujuan.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");
      }else{
        $(".modal-body").addClass("noresultfound");
      }
      $('#modal input').focus();
      $('#tujuan-table').removeClass("d-none");
    }
  });
}

_tujuandatatable();

function restable(){
const id = $('#tujuan-table').DataTable().cell($('#tujuan-table').DataTable().rows({selected:true}),0).data(),
      nama = $('#tujuan-table').DataTable().cell($('#tujuan-table').DataTable().rows({selected:true}),2).data(),
      gudangnama = $('#tujuan-table').DataTable().cell($('#tujuan-table').DataTable().rows({selected:true}),3).data(),
      idgudang = $('#tujuan-table').DataTable().cell($('#tujuan-table').DataTable().rows({selected:true}),4).data(),
      kode = $('#tujuan-table').DataTable().cell($('#tujuan-table').DataTable().rows({selected:true}),5).data(),
      trigger = $('#modaltrigger').val();

  if(id==null || typeof id=='undefined') return;

  $("#"+trigger).contents().find("#tujuan").val(id);
  $("#"+trigger).contents().find("#tujuannama").val(nama);

  if(idgudang!=null && idgudang!='' && idgudang!='0'){
    $("#"+trigger).contents().find("#gudang").val(idgudang);
    $("#"+trigger).contents().find("#gudangnama").val(gudangnama);
  }

  $("#"+trigger).contents().find("#tujuankode").val(kode);

  var iframeWin = document.getElementById(trigger).contentWindow;
  if(iframeWin && typeof iframeWin._applyTujuanLock === 'function'){
    iframeWin._applyTujuanLock(kode);
  }

  $('#modal').modal('hide');
  return;
}
