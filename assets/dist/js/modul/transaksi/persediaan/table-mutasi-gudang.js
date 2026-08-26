/* ========================================================================================== */
/* File Name : table-mutasi-gudang.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';

var tabel = null;

$(function() {

	$.fn.dataTable.ext.errMode = 'none';      

	Component_Inputmask_Date('.datepicker');
	Component_Scrollbars('.tab-wrap','scroll','scroll');

	if(!parent.window.$(".loader-wrap").hasClass("d-none")){
		parent.window.$(".loader-wrap").addClass("d-none");
	}

	this.addEventListener('contextmenu', function(e){
		e.preventDefault();
	});

	$("#dtgldari").click(function() {
	  $("#tgldari").focus();
	});

	$("#dtglsampai").click(function() {
	  $("#tglsampai").focus();
	});	

	var clearFilter = () => {
		$('#tgldari').datepicker('setDate','01-mm-yy');
		$('#tglsampai').datepicker('setDate','dd-mm-yy');
	}

	clearFilter();

	tabel=$('#table').DataTable({
	"processing": true,
	"serverSide": true,
	"lengthChange": false,
	"searching": false,
	"ordering": true,
	"pagingType":"simple",    
	"order": [[0, 'desc' ]],
	"select":true,  
	"dom": '<"top"pi>tr<"clear">',
	"ajax": {
	    "url":base_url+"Datatable_Transaksi_Full/view_mutasi_barang",
	    "type":"post",
        "data": function(data){
          data.dari = $('#tgldari').val();
          data.sampai = $('#tglsampai').val();          
        }		                       	                       
	},
	"deferRender": true,
	"bInfo":true,    
	"aLengthMenu": [[25, 50, 100],[25, 50, 100]],    
	"columns": [
	      { "data": "id" },
	      {
	      orderable:      false,
	      data:           null,
	      defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
	      },    
	      { "data": "nomor" },
	      { "data": "tanggal" },
	      { "data": "gudangasal" },
	      { "data": "gudangtujuan" },
	      { "data": "uraian" }	      	      
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
	}                    
	});

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

	$("#badd").click(function() {
		parent.window.$('.loader-wrap').removeClass('d-none');
		location.href=base_url+"page/mutasi";      
	});

	$("#bprint").click(() => {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();		

        if(typeof id=='undefined') return;

		window.open(`${base_url}Laporan/preview/page-mutasi/${id}`)
	});			

	$("#bedit").click(function() {
        const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();

        if(typeof id=='undefined') return;

		parent.window.$('.loader-wrap').removeClass('d-none');
		location.href=base_url+"page/mutasi/?id="+id;      
	});

	$('#table').on('dblclick','tr',function(e){
		e.preventDefault();
		e.stopPropagation();
		tabel.rows(this).select();
		$('#bedit').click();
	})

	$("#brefresh").click(function() {
		_reloaddatatable();
	});	

	$("#bdelete").click(function() {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),2).data();

		if(typeof id=='undefined') return;
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

	$("#bfilter").click(function() {
		if($("#fDataTable").hasClass("d-none")){
			$("#table").removeClass("w-100");
			$("#table").addClass("w-75");
			$("#fDataTable").removeClass("d-none");
	        $(".noresultfound-x").css("background-position","30% 160px");                 									
		}else {
			$("#table").removeClass("w-75");
			$("#table").addClass("w-100");
			$("#fDataTable").addClass("d-none");			
	        $(".noresultfound-x").css("background-position","45% 160px");                 									
		}
	});

	$("#submitfilter").click(function() {
	  $('#table').DataTable().ajax.reload();  
	  if (window.matchMedia('screen and (max-width: 768px)').matches) {
	    $("#coa-table").removeClass("w-75");
	    $("#coa-table").addClass("w-100");
	    $("#fDataTable").addClass("d-none");    
	  }  
	});

	var _reloaddatatable = () => {
		clearFilter();
		$('#table').DataTable().ajax.reload();  
	}  

	var _deleteData = () => {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
		const nomor = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),2).data();

		if(typeof id=='undefined') return;

		$.ajax({ 
			"url"    : base_url+"STK_Mutasi_Barang/deletedata", 
			"type"   : "POST", 
			"data"   : "id="+id+"&nomor="+nomor,
			"cache"    : false,
			"beforeSend" : function(){
			  parent.window.$(".loader-wrap").removeClass("d-none");
			},
			"error": function(xhr, status, error){
			  parent.window.$(".loader-wrap").addClass("d-none");
			  parent.window.toastr.error("Err: "+xhr.status+", "+error);      
			  console.log(xhr.responseText);      
			  return;
			},
			"success": function(result) {
			  parent.window.$(".loader-wrap").addClass("d-none");        

			  if(result=='sukses'){
			    parent.window.toastr.success("Transaksi berhasil dihapus");                  
			    _reloaddatatable();
			    return;
			  } else {        
			    parent.window.toastr.error(result);      
			    return;
			  }
			} 
		})
	}

})