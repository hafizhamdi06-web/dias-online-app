import { Component_Scrollbars } from '../component.js';

var tabel = null;

$(function () {

  $.fn.dataTable.ext.errMode = 'none';

  Component_Scrollbars('.tab-wrap','scroll','scroll');

  $("#badd").focus();

  this.addEventListener('contextmenu', (e) => {
      e.preventDefault();
  });

  tabel=$('#report-table').DataTable({
      "processing": true,
      "serverSide": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "pagingType":"simple",    
      "select":true,
      "order": [[ 0, 'asc' ]], 
      "dom": '<"top"pi>tr<"clear">',
      "ajax": {
          "url":base_url+"Datatable_Administrator/view_table_report",
          "type":"post",
          "data": (data) => {
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
          { "data": "nama" },
          { "data": "alias" },          
          { "data": "orientasi" },
          { "data": "ukuran" },
          { "data": "aktif" },          
      ],
      "drawCallback": (settings) => {
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
      clearFilter();  
      _reloaddatatable();
  });

  $("#bfilter").click(() => {
      if($("#fDataTable").hasClass("d-none")){
          $("#report-table").removeClass("w-100");
          $("#report-table").addClass("w-75");
          $("#fDataTable").removeClass("d-none");
          $(".noresultfound-x").css("background-position","30% 160px");                                   
      }else {
          $("#report-table").removeClass("w-75");
          $("#report-table").addClass("w-100");
          $("#fDataTable").addClass("d-none");      
          $(".noresultfound-x").css("background-position","45% 160px");                                   
      }
  });

  $("#submitfilter").click(() => {
      $('#report-table').DataTable().ajax.reload();  
      if (window.matchMedia('screen and (max-width: 768px)').matches) {
        $("#report-table").removeClass("w-75");
        $("#report-table").addClass("w-100");
        $("#fDataTable").addClass("d-none");    
      }  
  });

  $('#nama').keydown((e) => {
      if(e.keyCode==13) $('#submitfilter').click();
  });

  $("#badd").click(() => { 
    if(!$('#bedit').hasClass('disabled')){
        $.ajax({ 
          "url"    : base_url+"Modal/form_report", 
          "type"   : "POST", 
          "dataType" : "html",
          "beforeSend": () => {
              parent.window.$(".loader-wrap").removeClass("d-none");
              parent.window.$(".modal").modal("show");                  
              parent.window.$(".modal-title").html("Tambah Laporan");
              parent.window.$("#modaltrigger").val("iframe-page-ar");        
          },     
          "error": () => {
              parent.window.$(".loader-wrap").addClass("d-none");      
              console.error('error menampilkan form administrasi laporan...');
              return;
          },
          "success": async (result) => {
              await parent.window.$(".main-modal-body").html(result); 
              parent.window.$("#aktif").prop("checked", true);
              parent.window.$(".loader-wrap").addClass("d-none");
              setTimeout(function(){
                parent.window.$("#nama").focus();
              },300);
          } 
        })   
    }
  });

  $("#bdelete").click(() => {
    if(!$('#bdelete').hasClass('disabled')){
        const id = $('#report-table').DataTable().cell($('#report-table').DataTable().rows({selected:true}),2).data();

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
      const id = $('#report-table').DataTable().cell($('#report-table').DataTable().rows({selected:true}),0).data();

      if(id=="" || id==null) return;

      $.ajax({ 
        "url"    : base_url+"Modal/form_report", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": () => {      
            parent.window.$(".loader-wrap").removeClass("d-none");
            parent.window.$(".modal").modal("show");                  
            parent.window.$(".modal-title").html("Administrasi Laporan");
            parent.window.$("#modaltrigger").val("iframe-page-ar");        
        },     
        "error": () => {
            parent.window.$(".loader-wrap").addClass("d-none");      
            console.error('error menampilkan form administrasi laporan...');
            return;
        },
        "success": async (result) => {
            await parent.window.$(".main-modal-body").html(result);
            await parent.window._getData(id);
            parent.window.$(".loader-wrap").addClass("d-none");
            setTimeout(function(){
              parent.window.$("#nama").focus();
            },300);            
        } 
      })   
    }
  });

  $('#report-table').on('dblclick','tr',function(e){
      e.preventDefault();
      e.stopPropagation();
      tabel.rows(this).select();
      $('#bedit').click();
  })

  var _deleteData = (() => {
      const id = $('#report-table').DataTable().cell($('#report-table').DataTable().rows({selected:true}),0).data();

      if(id == "" || id == null) return;

      $.ajax({ 
        "url"    : base_url+"Admin_Report/deletedata", 
        "type"   : "POST", 
        "data"   : "id="+id,
        "cache"    : false,
        "beforeSend" : () => {
            parent.window.$(".loader-wrap").removeClass("d-none");
        },
        "error": (xhr, status, error) => {
            parent.window.$(".loader-wrap").addClass("d-none");
            parent.window.toastr.error("Perbaiki kesalahan ini : "+xhr.status+", "+error);      
            console.error(xhr.responseText);      
            return;
        },
        "success": (result) => {
            parent.window.$(".loader-wrap").addClass("d-none");        
            if(result=='sukses'){
                parent.window.toastr.success("Laporan berhasil dihapus");                  
                _reloaddatatable();
                return;
            } else {        
                parent.window.toastr.error(result);                          
                return;
            }
        } 
      })  
  });

var _reloaddatatable = () => {
    $('#report-table').DataTable().ajax.reload();  
}

var clearFilter = () => {
    $('#nama').val('');
}

});  