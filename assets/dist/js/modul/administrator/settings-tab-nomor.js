var tabelnomor = null;

$(function() {

	$("#btn-tab-nomor").click(function() {

	$("#badd").removeClass("disabled");
	$("#bedit").removeClass("disabled");	
	$("#bdelete").removeClass("disabled");
	$("#brefresh").removeClass("disabled");	
//	$("#bsave").addClass("disabled");	

  	if(!tabelnomor){
		tabelnomor=$('#nomor-table').DataTable({
		"processing": true,
		"serverSide": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"pagingType":"simple",    
		"order": [[0, 'asc' ]],
		"select":true,  
		"dom": '<"top"pi>tr<"clear">',
		"ajax": {
		    "url":base_url+"Datatable_Transaksi_Full/view_penomoran",
		    "type":"post"
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
		      { "data": "kode" },
		      { "data": "keterangan" },
		      { "data": "tabel" },
		      { "data": "tanggal" },
		      { "data": "sumber" },
		      { "data": "nomor" },
		      { "data": "kontak" },
		      { "data": "uraian" },
		      { "data": "total" },		      		      		      		      		      		      
		      { "data": "nid" },
	          { "orderable": true,
	            "render": function (data,type,row) {
	            	if(row.fa=='1'){
		                var html ="<input type='checkbox' name='rw[]' class='chkfa mt-1' checked>";
	            	}else{
		                var html ="<input type='checkbox' name='rw[]' class='chkfa mt-1'>";
	            	}
	                return html;
	                }
	          },
		      { "data": "menu" ,
		      	"render": function(data,type,row){
					var html;
		      		if(data==null){
		                html = "<span class='pr-2'></span>";
		      		}else{
		                html = "<span class='pr-2'>"+data+"</span>";
		      		}
	                return html;
		      	}
		  	  },		      		      	          
		],
	    "drawCallback": function() {
			var total = tabelnomor.data().count();

			if(total>0){
				$(".tab-wrap").removeClass("noresultfound-x");                                   
			}else{
				$(".tab-wrap").addClass("noresultfound-x"); 
			}  		
			if($(".table-utils-post").hasClass("d-none")){	  
			  	$(".table-utils-post").removeClass("d-none");
			}
		}                    
		});
  	} else {
		var total = tabelnomor.data().count();

		if(total>0){
			$(".tab-wrap").removeClass("noresultfound-x");                                   
		}else{
			$(".tab-wrap").addClass("noresultfound-x"); 
		}  		
  	}

	new $.fn.dataTable.ColResize(tabelnomor, {
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

	$("#bfilter-nomor").click(function() {
	  if($("#fDataTable-Nomor").hasClass("d-none")){
	    $("#nomor-table").removeClass("w-100");
	    $("#nomor-table").addClass("w-75");
	    $("#fDataTable-Nomor").removeClass("d-none");
	  }else {
	    $("#nomor-table").removeClass("w-75");
	    $("#nomor-table").addClass("w-100");
	    $("#fDataTable-Nomor").addClass("d-none");      
	  }
	});

	$('#nomor-table').on('dblclick','tr',function(e){
	  e.preventDefault();
	  e.stopPropagation();
	  tabelnomor.rows(this).select();
	  $('#bedit').click();
	})

	$("#bedit").click(function(){
		if($("#tab-nomor").hasClass('active')){

	      const id = $('#nomor-table').DataTable().cell($('#nomor-table').DataTable().rows({selected:true}),0).data();

	      if(id=="" || id==null) return;

	      $.ajax({ 
	        "url"    : base_url+"Modal/form_penomoran", 
	        "type"   : "POST", 
	        "dataType" : "html",
	        "beforeSend": () => {      
	            parent.window.$(".loader-wrap").removeClass("d-none");
	            parent.window.$(".modal").modal("show");                  
	            parent.window.$(".modal-title").html("Penomoran Transaksi");
	            parent.window.$("#modaltrigger").val("iframe-page-settinga");        
	        },     
	        "error": () => {
	            parent.window.$(".loader-wrap").addClass("d-none");      
	            console.log('error menampilkan form penomoran transaksi...');
	            return;
	        },
	        "success": async (result) => {
	            await parent.window.$(".main-modal-body").html(result);
	            await parent.window._getData(id);
	            parent.window.$(".loader-wrap").addClass("d-none");
	            setTimeout(function(){
	                parent.window.$("#kode").focus();
	            },200);
	        } 
	      })  
		}
	});

	$("#badd").click(function(){
		if($("#tab-nomor").hasClass('active')){
	      $.ajax({ 
	        "url"    : base_url+"Modal/form_penomoran", 
	        "type"   : "POST", 
	        "dataType" : "html",
	        "beforeSend": () => {
	            parent.window.$(".loader-wrap").removeClass("d-none");
	            parent.window.$(".modal").modal("show");                  
	            parent.window.$(".modal-title").html("Penomoran Transaksi");
	            parent.window.$("#modaltrigger").val("iframe-page-settinga");        
	        },     
	        "error": () => {
	            parent.window.$(".loader-wrap").addClass("d-none");      
	            console.log('error menampilkan form penomoran transaksi...');
	            return;
	        },
	        "success": async (result) => {
	            await parent.window.$(".main-modal-body").html(result); 
	            parent.window.$(".loader-wrap").addClass("d-none");
	            setTimeout(function(){
	                parent.window.$("#kode").focus();
	            },200);
	        } 
	      })
		} 
	});   	

	$("#bdelete").click(function(){
		if($("#tab-nomor").hasClass('active')){
			_delnomor();
		} 
	});    

	$("#brefresh").click(function(){
		if($("#tab-nomor").hasClass('active')){
	        $('#nomor-table').DataTable().ajax.reload(); 			
		} 
	});   	

	var _delnomor = () => {
		alert('hapus nomor');
	}

})