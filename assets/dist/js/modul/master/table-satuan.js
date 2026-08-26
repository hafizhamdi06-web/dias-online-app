import { Component_Scrollbars } from '../component.js';

var tabel = null;

$(function () {

  $.fn.dataTable.ext.errMode = 'none';

  Component_Scrollbars('.tab-wrap','scroll','scroll');

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $("#badd").focus();

  tabel=$('#satuan-table').DataTable({
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
        "url":base_url+"Datatable_Master/view_table_satuan",
        "type":"post",
        "data": function(data){
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
          { "data": "nama" }
    ],
    "drawCallback": function() {
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

$("#badd").click(function() { 
  $.ajax({ 
    "url"    : base_url+"Modal/form_satuan", 
    "type"   : "POST", 
    "dataType" : "html",
    "beforeSend": function(){
      parent.window.$(".loader-wrap").removeClass("d-none");      
      parent.window.$(".modal").modal("show");                  
      parent.window.$(".modal-title").html("Tambah Satuan");
      parent.window.$("#modaltrigger").val("iframe-page-satuan");        
    },     
    "error": function(){
      parent.window.$(".loader-wrap").addClass("d-none");            
      console.log('error menampilkan form satuan...');
      return;
    },
    "success": async function(result) {
      await parent.window.$(".main-modal-body").html(result);    
      parent.window.$(".loader-wrap").addClass("d-none");        
    } 
  });   
});

$("#bdelete").click(function() {

  const id = $('#satuan-table').DataTable().cell($('#satuan-table').DataTable().rows({selected:true}),2).data();

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
});

$("#bedit").click(function() {
  const id = $('#satuan-table').DataTable().cell($('#satuan-table').DataTable().rows({selected:true}),0).data();

  if(id=="" || id==null) return;

  $.ajax({ 
    "url"    : base_url+"Modal/form_satuan", 
    "type"   : "POST", 
    "dataType" : "html",
    "beforeSend": function(){      
      parent.window.$(".loader-wrap").removeClass("d-none");
      parent.window.$(".modal").modal("show");                  
      parent.window.$(".modal-title").html("Data Satuan");
      parent.window.$("#modaltrigger").val("iframe-page-satuan");        
    },     
    "error": function(){
      parent.window.$(".loader-wrap").addClass("d-none");      
      console.log('error menampilkan form satuan...');
      return;
    },
    "success": async function(result) {
      await parent.window.$(".main-modal-body").html(result);
      await parent.window._getData(id);
      parent.window.$(".loader-wrap").addClass("d-none");
    } 
  });   
});

$('#satuan-table').on('dblclick','tr',function(e){
    e.preventDefault();
    e.stopPropagation();
    tabel.rows(this).select();
    $('#bedit').click();
})

var _deleteData = (function(){
  const id = $('#satuan-table').DataTable().cell($('#satuan-table').DataTable().rows({selected:true}),0).data();

  if(id == "" || id == null) return;

  $.ajax({ 
    "url"    : base_url+"Master_Satuan/deletedata", 
    "type"   : "POST", 
    "data"   : "id="+id,
    "cache"    : false,
    "beforeSend" : function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");
      parent.window.toastr.error("Perbaiki kesalahan ini : "+xhr.status+", "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      parent.window.$(".loader-wrap").addClass("d-none");        
      if(result=='sukses'){
        parent.window.toastr.success("Data satuan berhasil dihapus");                  
        _reloaddatatable();
        return;
      } else {        
        parent.window.toastr.error(result);                          
        return;
      }
    } 
  });  
});

$("#bfilter").click(function() {
  if($("#fDataTable").hasClass("d-none")){
    $("#satuan-table").removeClass("w-100");
    $("#satuan-table").addClass("w-75");
    $("#fDataTable").removeClass("d-none");
        $(".noresultfound-x").css("background-position","30% 160px");                                   
  }else {
    $("#satuan-table").removeClass("w-75");
    $("#satuan-table").addClass("w-100");
    $("#fDataTable").addClass("d-none");      
        $(".noresultfound-x").css("background-position","45% 160px");                                   
  }
});

$("#submitfilter").click(function() {
  $('#satuan-table').DataTable().ajax.reload();  
  if (window.matchMedia('screen and (max-width: 768px)').matches) {
    $("#satuan-table").removeClass("w-75");
    $("#satuan-table").addClass("w-100");
    $("#fDataTable").addClass("d-none");    
  }  
});

$('#kode,#nama').keydown(function(e){
  if(e.keyCode==13) $('#submitfilter').click();
});

});  

function _reloaddatatable(){
  clearFilter();
  $('#satuan-table').DataTable().ajax.reload();  
}

function clearFilter(){
  $('#kode,#nama').val('');
}
