/* ========================================================================================== */
/* File Name : table-penjualan-tunai.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2 } from '../../component.js'; 
import { Component_Select2_Account } from '../../component.js';


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
		$('#tgldari').datepicker('setDate','dd-mm-yy');
		$('#tglsampai').datepicker('setDate','dd-mm-yy');
		$('#idkontak,#kontak,#bank,#carabayar').val(''); 
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
		    "url":base_url+"Datatable_Transaksi_Full/view_penjualan_tunai",
		    "type":"post",
	        "data": function(data){
	          data.kontak = $('#kontak').val();
	          data.dari = $('#tgldari').val();
	          data.sampai = $('#tglsampai').val();   
	          data.carabayar = $('#carabayar').val();  
	          data.bank = $('#bank').val();         
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
			  { "data": "total", 
				"className": 'aright',		      
				"render": (data, type, row, meta) => {
					data = accounting.formatMoney(data);
					return data;		        		
				}		      	
			  },	
		      { "data": "status" }, 	      
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
		location.href=base_url+"page/pos";      
	});

	$("#bedit").click(function() {
        const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();

        if(typeof id=='undefined') return;

		parent.window.$('.loader-wrap').removeClass('d-none');
		location.href=base_url+"page/pos/?id="+id;      
	});

	$('#table').on('dblclick','tr',function(e){
	//	e.preventDefault();
	//	e.stopPropagation();
	//	tabel.rows(this).select();
	//	$('#bedit').click();
	})

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

	$("#bprint").click(() => {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();		

        if(typeof id=='undefined') return;

		window.open(`${base_url}Laporan/preview/page-pos/${id}`)
	});	

	$("#brefresh").click(function() {
		_reloaddatatable();
		_inputFormat();
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
				"url"    : base_url+"Modal/cari_kontak_pos", 
				"type"   : "POST", 
				"dataType" : "html", 
				"beforeSend": function(){
				  parent.window.$(".loader-wrap").removeClass("d-none");          
				  parent.window.$(".modal").modal("show");                  
				  parent.window.$(".modal-title").html("Cari Kontak");
				  parent.window.$("#modaltrigger").val("iframe-page-posData");
				  parent.window.$('#coltrigger').val('pasien');   
				},
				"error": function(){
				  console.log('error menampilkan modal cari kontak...');
				  parent.window.$(".loader-wrap").addClass("d-none");          
				  return;
				},
				"success": function(result) {                
				  parent.window.$(".main-modal-body").html(result);
				  parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
				  
				  parent.window._setjenis();
                  parent.window._lstkategorikontak();
                  parent.window._pilihkategorikontak(''); 

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
	});

	var _reloaddatatable = () => {
		clearFilter();
		$('#table').DataTable().ajax.reload();  
	}  

	var _deleteData = (function(){
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
		const nomor = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),2).data();

		if(typeof id=='undefined') return;

		$.ajax({ 
		"url"    : base_url+"PJ_Penjualan_Tunai/deletedata", 
		"type"   : "POST", 
		"data"   : "id="+id+"&nomor="+nomor,
		"cache"    : false,
		"beforeSend" : function(){
		  parent.window.$(".loader-wrap").removeClass("d-none");
		},
		"error": function(xhr, status, error){
		  parent.window.$(".loader-wrap").addClass("d-none");
		  parent.window.toastr.error("Error : "+xhr.status+", "+error);      
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
		});  
	});
	
	
	

 

    $("#editdepo2").click(function() { 
      $.ajax({ 
        "url"    : base_url+"Modal/form_editdepo", 
        "type"   : "POST", 
        "dataType" : "html",
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");      
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Tambah edit depo");
          parent.window.$("#modaltrigger").val("iframe-page-posData");              
        },     
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");            
          console.log('error menampilkan modal form edit depo...');
          return;
        },
        "success": async function(result) {
          await parent.window.$(".main-modal-body").html(result);      
          parent.window.$(".loader-wrap").addClass("d-none");      
        } 
      });   
    });
    
    
    
$("#editdepo").click(function() { 
    
  const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
   if(id=="" || id==null) return;
  
  $.ajax({ 
    "url"    : base_url+"Modal/form_editdepo", 
    "type"   : "POST", 
    "dataType" : "html",
    "beforeSend": function(){
      parent.window.$(".loader-wrap").removeClass("d-none");      
      parent.window.$(".modal").modal("show");                  
      parent.window.$(".modal-title").html("Edit DEPO");
      parent.window.$("#modaltrigger").val("iframe-page-posData");              
    },     
    "error": function(){
      parent.window.$(".loader-wrap").addClass("d-none");            
      console.log('error menampilkan modal form edit depo...');
      return;
    },
    "success": async function(result) {
      await parent.window.$(".main-modal-body").html(result);   
      await parent.window._getData(id);   
      parent.window.$(".loader-wrap").addClass("d-none");      
    } 
  });   
});


$("#editdepo3").click(function() {
  const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
   

  if(id=="" || id==null) return;

  $.ajax({ 
    "url"    : base_url+"Modal/form_bank", 
    "type"   : "POST", 
    "dataType" : "html",
    "beforeSend": function(){      
      parent.window.$(".loader-wrap").removeClass("d-none");
      parent.window.$(".modal").modal("show");                  
      parent.window.$(".modal-title").html("Bank");
      parent.window.$("#modaltrigger").val("iframe-page-mbank");        
    },     
    "error": function(){
      parent.window.$(".loader-wrap").addClass("d-none");      
      console.log('error menampilkan form bank...');
      return;
    },
    "success": async function(result) {
      await parent.window.$(".main-modal-body").html(result);
      await parent.window._getData(id);
      parent.window.$(".loader-wrap").addClass("d-none");
    } 
  });   
});
	
	
	
	
 
		
		

})