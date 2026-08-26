/* ========================================================================================== */
/* File Name : table-bank-keluar.js
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

	this.addEventListener('contextmenu', (e) => {
		e.preventDefault();
	});

	var clearFilter = () => {
		$('#tgldari').datepicker('setDate','01-mm-yy');
		$('#tglsampai').datepicker('setDate','dd-mm-yy');
		$('#idkontak,#kontak').val('');
	}

	clearFilter();

	tabel=$('#table').DataTable({
		"processing": false,
		"serverSide": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"pagingType":"simple",    
		"order": [[0, 'desc' ]],
		"select":true,  
		"dom": '<"top"pi>tr<"clear">',
		"ajax": {
		    "url":base_url+"Datatable_Transaksi_Full/view_bank_keluar",
		    "type":"post",
	        "data": (data) => {
	          data.kontak = $('#idkontak').val();
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
		      { "data": "kontak" },
		      { "data": "uraian" },
		      { "data": "total" },
		      { "data": "totalv" },
		],
		"columnDefs": [
		      {
		        "render": (data, type, row) => {
		             data = commaSeparateNumber(data);
		             data = "<span style='float:right' class='mx-2'>"+data+"</span>";
		             return data;
		        },
		        "targets": [6,7]
		      },                              
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
		}                    
	})

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
	})

	$("#dtgldari").click(() => {
		$("#tgldari").focus();
	});

	$("#dtglsampai").click(() => {
		$("#tglsampai").focus();
	});

	$("#badd").click(function() {
		parent.window.$('.loader-wrap').removeClass('d-none');
		location.href=base_url+"page/bbk";      
	})

	$("#bedit").click(() => {
        const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();

        if(typeof id=='undefined') return;

		parent.window.$('.loader-wrap').removeClass('d-none');
		location.href=`${base_url}page/bbk/?id=${id}`;      
	})

	$("#brefresh").click(() => {
		_reloaddatatable();
	})	

	$("#bprint").click(() => {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();		

        if(typeof id=='undefined') return;

		window.open(`${base_url}Laporan/preview/page-bbk/${id}`)
	});	

	$("#bdelete").click(() => {
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
	})

	$('#table').on('dblclick','tr',function(e){
		e.preventDefault();
		e.stopPropagation();
		tabel.rows(this).select();
		$('#bedit').click();
	})

	$("#bfilter").click(() => {
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
	})

	$("#bfilterkontak").click(function() {
		if($(this).attr('role')) {
		  $.ajax({ 
			    "url"    : base_url+"Modal/cari_kontak", 
			    "type"   : "POST", 
			    "dataType" : "html", 
			    "beforeSend": () => {
			      parent.window.$(".loader-wrap").removeClass("d-none");          
			      parent.window.$(".modal").modal("show");                  
			      parent.window.$(".modal-title").html("Cari Kontak");
			      parent.window.$("#modaltrigger").val("iframe-page-bbk");
			      parent.window.$('#coltrigger').val('kontak');   
			    },
			    "error": () => {
			      console.log('error menampilkan modal cari kontak...');
			      parent.window.$(".loader-wrap").addClass("d-none");          
			      return;
			    },
			    "success": (result) => {                
			      parent.window.$(".main-modal-body").html(result);
			      parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
			      parent.window._lstkategorikontak();
		          parent.window._kontakdatatable();
			      setTimeout(function (){
			           parent.window.$('#modal input').focus();
			      }, 500);

			      return;
			    } 
		  })
		}    
	})

	$("#submitfilter").click(() => {
	  $('#table').DataTable().ajax.reload(); 

	  if (window.matchMedia('screen and (max-width: 768px)').matches) {
		    $("#coa-table").removeClass("w-75");
		    $("#coa-table").addClass("w-100");
		    $("#fDataTable").addClass("d-none");    
	  }  
	})

	var _reloaddatatable = () => {
		clearFilter();
		$('#table').DataTable().ajax.reload();  
	}  

	var _deleteData = () => {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
		const nomor = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),2).data();

		if(typeof id=='undefined') return;

		$.ajax({ 
			"url"    : base_url+"Fina_Bank_Keluar/deletedata", 
			"type"   : "POST", 
			"data"   : "id="+id+"&nomor="+nomor,
			"cache"    : false,
			"beforeSend" : () => {
				parent.window.$(".loader-wrap").removeClass("d-none");
			},
			"error": (xhr, status, error) => {
				parent.window.$(".loader-wrap").addClass("d-none");
				parent.window.toastr.error("Err: "+xhr.status+", "+error);      
				console.log(xhr.responseText);      
				return;
			},
			"success": (result) => {
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