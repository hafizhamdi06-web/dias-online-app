var tabelpost = null;

$(function() {

	$.fn.dataTable.ext.errMode = 'none';

	var textSelect = (param) => {
		if(!param.id) return param.text;
		var $param = $('<span>'+param.kode+' - '+param.text+'</span>');
		return $param;
	}

	$('#tipe-post').select2({
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
	          nfa: 1
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

	$('#opsi-post').select2({
		"minimumResultsForSearch": "Infinity",                 		
	    "theme":"bootstrap4"
	});

	$('#all-post').click(function(){
		if ($('#all-post').is(":checked"))
		{
		   $('.chkpost').prop('checked', true); 
		} else {
		   $('.chkpost').prop('checked', false); 
		}
	});  

	$("#dtgldaripost").click(function() {
	  $("#tgldaripost").focus();
	});

	$("#dtglsampaipost").click(function() {
	  $("#tglsampaipost").focus();
	});

	$('#tgldaripost').datepicker('setDate','01-mm-yy');
	$('#tglsampaipost').datepicker('setDate','dd-mm-yy');

	$("#btn-tab-post").click(function() {

	$("#badd").addClass("disabled");
	$("#bedit").addClass("disabled");	
	$("#bdelete").addClass("disabled");
//	$("#bsave").addClass("disabled");
	$("#brefresh").removeClass("disabled");	

  	if(!tabelpost){
		tabelpost=$('#post-table').DataTable({
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
		    "url":base_url+"Datatable_Transaksi_Full/view_posting_trans",
		    "type":"post",
	        "data": function(data){
	          data.dari = $('#tgldaripost').val();
	          data.sampai = $('#tglsampaipost').val();          
	          data.tipe = $('#opsi-post').val();
	          data.jenis = $('#tipe-post').val();	                    	          
	        }
		},
		"deferRender": true,
		"bInfo":true,    
		"aLengthMenu": [[100000], [100000]],    
		"columns": [
		      { "data": "id" },
	          { "orderable": false,
	            "render": function ( data, type, row ) {
	                var html ="<input type='checkbox' id='"+row.id+"' name='rw[]' class='chkpost mt-1' value='"+row.id+"'>";
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
	      var total = tabelpost.data().count();

	      if(total>0){
	        $(".tab-wrap").removeClass("noresultfound-x");                                   
	      }else{
	        $(".tab-wrap").addClass("noresultfound-x"); 
	      }

		  if($(".table-utils-post").hasClass("d-none")){	  
			  $(".table-utils-post").removeClass("d-none");
		  }
		  $('#all-post').prop('checked', false); 
		}                    
		});
  	} else {
		var total = tabelpost.data().count();

		if(total>0){
		$(".tab-wrap").removeClass("noresultfound-x");                                   
		}else{
		$(".tab-wrap").addClass("noresultfound-x"); 
		}  		
  	}

	new $.fn.dataTable.ColResize(tabelpost, {
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

	$("#bfilter-post").click(function() {
	  if($("#fDataTable-Post").hasClass("d-none")){
	    $("#post-table").removeClass("w-100");
	    $("#post-table").addClass("w-75");
	    $("#fDataTable-Post").removeClass("d-none");
        $(".noresultfound-x").css("background-position","30% 160px");                                   	    
	  }else {
	    $("#post-table").removeClass("w-75");
	    $("#post-table").addClass("w-100");
	    $("#fDataTable-Post").addClass("d-none");      
        $(".noresultfound-x").css("background-position","45% 160px");                                   	    
	  }
	});

	$("#bfilterpost").click(function() {
	  $('#post-table').DataTable().ajax.reload();  
	});	

	$("#brefresh").click(function(){
		if($("#tab-post").hasClass('active')){
			$("#opsi-post").val(0).trigger('change');
			$("#tipe-post").val(null).trigger('change');			
			$('#tgldaripost').datepicker('setDate','01-mm-yy');
			$('#tglsampaipost').datepicker('setDate','dd-mm-yy');
	        $('#post-table').DataTable().ajax.reload(); 			
		} 
	});   	

})