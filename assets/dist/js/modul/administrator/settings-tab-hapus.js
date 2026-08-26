var tabeldel = null;
var aktifTipe = null;

$(function() {

//	$.fn.dataTable.ext.errMode = 'none';

	var textSelect = (param) => {
		if(!param.id) return param.text;
		var $param = $('<span>'+param.kode+' - '+param.text+'</span>');
		return $param;
	}

	$('#tipe-del').select2({
	   "allowClear": true,
	   "theme":"bootstrap4",
	   "ajax": {
	      "url": base_url+"Select_Master/view_jenis_transaksi",
	      "type": "post",
	      "dataType": "json",                                       
	      "delay": 800,
	      "data": function(params) {
	        return {
	          search: params.term,
	          nfa:null
	        }
	      },
	      "processResults": function (data, page) {
	      return {
	        results: data
	      };
	    },
	  },
	   "templateResult": textSelect,              	  
	});  

	$('#opsi-del').select2({
		"minimumResultsForSearch": "Infinity",                 		
	    "theme":"bootstrap4"
	});

	$('#all-del').click(function(){
		if ($('#all-del').is(":checked"))
		{
		   $('.chkdel').prop('checked', true); 
		} else {
		   $('.chkdel').prop('checked', false); 
		}
	});  

	$("#dtgldaridel").click(function() {
	  $("#tgldaridel").focus();
	});

	$("#dtglsampaidel").click(function() {
	  $("#tglsampaidel").focus();
	});

	$('#tgldaridel').datepicker('setDate','01-mm-yy');
	$('#tglsampaidel').datepicker('setDate','dd-mm-yy');

	$("#btn-tab-hapus").click(function() {

	$("#badd").addClass("disabled");
	$("#bedit").addClass("disabled");	
	$("#bdelete").removeClass("disabled");
	$("#brefresh").removeClass("disabled");	
//	$("#bsave").addClass("disabled");

  	if(!tabeldel){
			tabeldel=$('#del-table').DataTable({
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
			    "url":base_url+"Datatable_Transaksi_Full/view_transaksi_tabel",
			    "type":"post",
		        "data": function(data){
		          data.dari = $('#tgldaridel').val();
		          data.sampai = $('#tglsampaidel').val();          
		          data.jenis = $('#tipe-del').val();	                    	          
		        },
		        "beforeSend": function() {
					$("#bfilterdel").addClass('disabled');		        	
		        	$("#bfilterdel span").html("<i class='fa fa-circle-notch fa-spin text-white'></i> Menunggu");
		        },
		        "error":function(xhr,status,error){
					parent.window.Swal.fire({
						title: `${error} (${xhr.status})`,
						showDenyButton: false,
						showCancelButton: false,
						confirmButtonText: `Tutup`,
					});		        	
		        	console.error(xhr.responseText);
		        	return;
		        }
			},
			"deferRender": true,
			"bInfo":true,    
		    "aLengthMenu": datapage,
			"columns": [
			      { "data": "id" },
		          { "orderable": false,
		            "render": function ( data, type, row ) {
		                var html ="<input type='checkbox' id='"+row.id+"' name='rowhapus[]' class='chkdel mt-1' value='"+row.id+"'>";
		                return html;
		                }
		          },
			      { "data": "nomor" },
			      { "data": "tanggal" },
			      { "data": "kontak" },
			      { "data": "uraian" },
			      { "data": "total" }
			],
			"columnDefs": [
			      {
			        "render": function (data, type, row) {
			             data = commaSeparateNumber(data);
			             data = "<span style='float:right' class='mx-2'>"+data+"</span>";
			             return data;
			        },
			        "targets": [6]
			      },                              
			],
		    "drawCallback": function() {
				var total = tabeldel.data().count();

				if(total>0){
					$(".tab-wrap").removeClass("noresultfound-x");                                   
				}else{
					$(".tab-wrap").addClass("noresultfound-x"); 
				}  		

				if($(".table-utils-del").hasClass("d-none")){	  
				  $(".table-utils-del").removeClass("d-none");
				}

				$('#all-del').prop('checked', false); 
				$("#bfilterdel").removeClass('disabled');		        	
	        	$("#bfilterdel span").text('Tampilkan');				
			}                    
			});
  	} else {
		var total = tabeldel.data().count();

		if(total>0){
			$(".tab-wrap").removeClass("noresultfound-x");                                   
		}else{
			$(".tab-wrap").addClass("noresultfound-x"); 
		}  		  		
  	}

	new $.fn.dataTable.ColResize(tabeldel, {
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

  	});	  

	$("#bfilter-del").click(function() {
	  if($("#fDataTable-del").hasClass("d-none")){
	    $("#del-table").removeClass("w-100");
	    $("#del-table").addClass("w-75");
	    $("#fDataTable-del").removeClass("d-none");
        $(".noresultfound-x").css("background-position","30% 160px");                                   	    	    
	  }else {
	    $("#del-table").removeClass("w-75");
	    $("#del-table").addClass("w-100");
	    $("#fDataTable-del").addClass("d-none");      
        $(".noresultfound-x").css("background-position","45% 160px");                                   	    	    
	  }
	});

	$("#bfilterdel").click(function() {
	  $('#del-table').DataTable().ajax.reload();  
  	  aktifTipe = $("#tipe-del").val();	  
	});	

	$("#bdelete").click(function(){
		if($("#tab-hapus").hasClass('active')){
			_deltransaksi();
		} 
	});    

	$("#brefresh").click(function(){
		if($("#tab-hapus").hasClass('active')){
			$('#tipe-del').val(null).trigger('change');			
			$('#tgldaridel').datepicker('setDate','01-mm-yy');
			$('#tglsampaidel').datepicker('setDate','dd-mm-yy');
	        $('#del-table').DataTable().ajax.reload(); 			
		} 
	});   	

	var _deltransaksi = () => {
		let data = [];
		let totalcek = $("input:checkbox[name='rowhapus[]']:checked").length;
		let totalrow = $("input:checkbox[name='rowhapus[]']").length; 
		let checktabel = document.getElementsByName('rowhapus[]');

		if(totalcek>0){
			parent.window.Swal.fire({
				title: `Anda yakin akan menghapus ${totalcek} baris transaksi ?`,
				showDenyButton: false,
				showCancelButton: true,
				confirmButtonText: `Iya`,
			}).then((result) => {
				if (result.isConfirmed) {
					for(var i=0;i<totalrow;i++){
						var inp=checktabel[i];
					    if(inp.checked==true){
					      	data.push({
					      		data: inp.value
					      	});    
					    }
					}

					data = JSON.stringify(data);

					var rey = new FormData();
					rey.set('data',data);
					rey.set('tipe',aktifTipe);
					
				    $.ajax({ 
				        "url"    : base_url+"Settings_Hapus_Transaksi/hapustransaksi", 
				        "type"   : "POST", 
				        "data"   : rey,
				        "processData": false,
				        "contentType": false,
				        "cache"    : false,
				        "beforeSend" : () => {
				            parent.window.$(".loader-wrap").removeClass("d-none");
				        },
				        "error": (xhr, status, error) => {
				            parent.window.$(".loader-wrap").addClass("d-none");
				            parent.window.toastr.error("Error : "+xhr.status+", "+error);      
				            console.log(xhr.responseText);      
				            return;
				        },
				        "success": (result) => {
						    $('#del-table').DataTable().ajax.reload();  
				            parent.window.$(".loader-wrap").addClass("d-none");
				            parent.window.toastr.success("Transaksi berhasil dihapus");      				            
				            return;		        	
				        } 
				    })
				}
			})
		}
	}

})