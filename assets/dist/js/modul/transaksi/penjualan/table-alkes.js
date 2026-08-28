/* ========================================================================================== */
/* File Name : table-surat-jalan.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js';

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
		$('#idkontak,#kontak,#notransaksi,#noref').val('');
	}

	clearFilter();

	if($('#bisaedit').val()==0) $('#bedit').addClass('disabled');
	if($('#bisahapus').val()==0) $('#bdelete').addClass('disabled');
	if($('#bisaprint').val()==0) $('#bprint').addClass('disabled');

	Component_Select2('.cabang', base_url+'Select_Master/view_gudang_pilihan');
	const _cabang = $("<option selected='selected'></option>").val($('#cabanguser').val()).text($('#namacabang').val());
	$('#cabang').append(_cabang);
	if($('#allcabang').val()==0) $('#cabang').prop('disabled',true);

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
		    "url":base_url+"Datatable_Transaksi_Full/view_alkes",
		    "type":"post",
	        "data": function(data){
	          data.kontak = $('#idkontak').val();
	          data.cabang = $('#cabang').val();
	          data.notransaksi = $('#notransaksi').val();
	          data.noref = $('#noref').val();
	          data.dari = $('#tgldari').val();
	          data.sampai = $('#tglsampai').val();
	        }
		},
		"deferRender": true,
		"bInfo":true,    
		"aLengthMenu": datapage,    
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
		      { "data": "noref" }
		],
	    "createdRow": function(row, data, dataIndex) {
	      $(row).attr('data-idsupos', data.idsupos);
	    },
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
	})

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

	$("#bedit").click(function() {
        const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
        const idsupos = $($('#table').DataTable().rows({selected:true}).nodes()).attr('data-idsupos');

        if(typeof idsupos=='undefined' || idsupos=='' || idsupos==null) {
          parent.window.toastr.error('Pilih data terlebih dahulu !');
          return;
        }

        $.ajax({
          "url"    : base_url+"Modal/form_editdepo",
          "type"   : "POST",
          "dataType" : "html",
          "beforeSend": function(){
            parent.window.$(".loader-wrap").removeClass("d-none");
            parent.window.$(".modal").modal("show");
            parent.window.$(".modal-title").html("Edit DEPO");
            parent.window.$("#modaltrigger").val("iframe-page-alkesData");
          },
          "error": function(){
            parent.window.$(".loader-wrap").addClass("d-none");
            console.log('error menampilkan modal form edit depo...');
            return;
          },
          "success": async function(result) {
            await parent.window.$(".main-modal-body").html(result);
            await parent.window._getData(idsupos, id);
            parent.window.$(".loader-wrap").addClass("d-none");
          }
        });
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

	$("#bprint").click(() => {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();		

        if(typeof id=='undefined') return;

		window.open(`${base_url}Laporan/preview/page-sj/${id}`)
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

	$("#bfilterkontak").click(function() {
		if($(this).attr('role')) {
			$.ajax({ 
				"url"    : base_url+"Modal/cari_kontak", 
				"type"   : "POST", 
				"dataType" : "html", 
				"beforeSend": function(){
				  parent.window.$(".loader-wrap").removeClass("d-none");          
				  parent.window.$(".modal").modal("show");                  
				  parent.window.$(".modal-title").html("Cari Kontak");
				  parent.window.$("#modaltrigger").val("iframe-page-sj");
				  parent.window.$('#coltrigger').val('vendor');   
				},
				"error": function(){
				  console.error('error menampilkan modal cari kontak...');
				  parent.window.$(".loader-wrap").addClass("d-none");          
				  return;
				},
				"success": function(result) {                
				  parent.window.$(".main-modal-body").html(result);
				  parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
				  parent.window._lstkategorikontak();
				  parent.window._pilihkategorikontak('2'); 

				  setTimeout(function (){
				       parent.window.$('#modal input').focus();
				  }, 500);

				  return;
				} 
			})
		}    
	})

	$("#submitfilter").click(function() {
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
			"url"    : base_url+"PJ_Surat_Jalan/deletedata", 
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