import { Component_Scrollbars } from '../component.js';

var tabel = null;

$(function () {

  Component_Scrollbars('.tab-wrap','scroll','scroll');

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  $("#badd").focus();

  tabel=$('#user-table').DataTable({
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
        "url":base_url+"Datatable_Administrator/view_table_user",
        "type":"post",
	        "data": function(data){
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
          { "data": "uid" },
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },    
          { "data": "ukode" },
          { "data": "unama" },
          { "data": "unamalengkap" },          
          { "data": "uactive" }
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
  clearFilter();
  _reloaddatatable();
});

$("#badd").click(function() { 
  $.ajax({ 
    "url"    : base_url+"Modal/form_user", 
    "type"   : "POST", 
    "dataType" : "html",
    "beforeSend": function(){
      parent.window.$(".loader-wrap").removeClass("d-none");
      parent.window.$(".modal").modal("show");                  
      parent.window.$(".modal-title").html("Administrasi User");
      parent.window.$("#modaltrigger").val("iframe-page-au");        
    },     
    "error": function(xhr, status, error){
      parent.window.$(".loader-wrap").addClass("d-none");      
      parent.window.toastr.error("Kesalahan : "+xhr.status+", "+error);      
      return;
    },
    "success": function(result) {
      parent.window.$(".main-modal-body").html(result); 
      setTimeout(function () {
        parent.window.$("#kode").focus();
      }, 300);
    } 
  });   
});

$("#bdelete").click(function() {
  if(!$('#bdelete').hasClass('disabled')){
      const id = $('#user-table').DataTable().cell($('#user-table').DataTable().rows({selected:true}),0).data();
      var kode = $('#user-table').DataTable().cell($('#user-table').DataTable().rows({selected:true}),2).data();

      if(kode==null) kode='';

      if(id=="" || id==null) return;
      if(kode==0) return;


      parent.window.Swal.fire({
        title: 'Anda yakin akan menghapus user&nbsp;<u class="text-primary"> '+kode+'?</u>',
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

$("#bedit").click(async function() {
  if(!$('#bedit').hasClass('disabled')){
      const id = $('#user-table').DataTable().cell($('#user-table').DataTable().rows({selected:true}),0).data();

      if(id=="" || id==null) return;

      var kode = "", nama = "", namalengkap = "", status = "";

      await $.ajax({ 
        "url"    : base_url+"Admin_User/getinfouser", 
        "type"   : "POST", 
        "dataType" : "json",
        "data" : "id="+id,
        "success": function(result) {    
          kode = result.data[0]['ukode'];
          nama = result.data[0]['unama'];
          namalengkap = result.data[0]['unamalengkap'];
          status = result.data[0]['uactive'];
        }
      })

      $.ajax({ 
        "url"    : base_url+"Modal/form_user", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){      
          parent.window.$(".loader-wrap").removeClass("d-none");
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Administrasi User");
          parent.window.$("#modaltrigger").val("iframe-page-au");        
        },     
        "error": function(xhr,status,error){
          parent.window.$(".loader-wrap").addClass("d-none");      
          parent.window.toastr.error("Kesalahan : "+xhr.status+", "+error);      
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$("#id").val(id);
          parent.window.$("#kode").val(kode);
          parent.window.$("#nama").val(nama);            
          parent.window.$("#namalengkap").val(namalengkap);

          if(kode==0){
            parent.window.$("#kode").prop('disabled', true);
          }

          if(status==1){
            parent.window.$("#aktif").prop("checked",true);        
          }

          parent.window.$("#rPassword").addClass("d-none");

          setTimeout(function () {
            parent.window.$("#kode").focus();
          }, 300);
        } 
      })   
  }    
});

$('#user-table').on('dblclick','tr',function(e){
    e.preventDefault();
    e.stopPropagation();
    tabel.rows(this).select();
    $('#bedit').click();
})

var _deleteData = (function(){
  const id = $('#user-table').DataTable().cell($('#user-table').DataTable().rows({selected:true}),0).data();

  if(id == "" || id == null) return;

  $.ajax({ 
    "url"    : base_url+"Admin_User/deletedata", 
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
        parent.window.toastr.success("User berhasil dihapus");                  
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
    $("#user-table").removeClass("w-100");
    $("#user-table").addClass("w-75");
    $("#fDataTable").removeClass("d-none");
    $(".noresultfound-x").css("background-position","30% 160px");                                   
  }else {
    $("#user-table").removeClass("w-75");
    $("#user-table").addClass("w-100");
    $("#fDataTable").addClass("d-none");      
    $(".noresultfound-x").css("background-position","45% 160px");                                   
  }
});

$("#submitfilter").click(function() {
  $('#user-table').DataTable().ajax.reload();  
  if (window.matchMedia('screen and (max-width: 768px)').matches) {
    $("#user-table").removeClass("w-75");
    $("#user-table").addClass("w-100");
    $("#fDataTable").addClass("d-none");    
  }  
});

$('#nama').keydown(function(e){
  if(e.keyCode==13) $('#submitfilter').click();
});

});  

function _reloaddatatable(){
  $('#user-table').DataTable().ajax.reload();  
}

function clearFilter(){
  $('#nama').val('');
}