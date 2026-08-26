$(function(){

	$('#icetakpos,#ibarcodepos,#ipajakpos,#ipajakbeli,#ipajakjual,#multidivisi,#multiproyek,#multisatuan,#multikurs').select2({
	     "minimumResultsForSearch": "Infinity",                 
	     "theme":"bootstrap4"
	});

	$('#ippnbeli,#ippnjual,#ippnpos').select2({
	     "allowClear": true,
	     "theme":"bootstrap4",
		 "allowAddLink": true,
		 "addLink": "form_pajak",  
		 "linkTitle": "Pajak",	     
	     "ajax": {
	        "url": base_url+"Select_Master/view_pajak_ppn",
	        "type": "post",
	        "dataType": "json",                                       
	        "delay": 800,
	        "data": function(params) {
	          return {
	            search: params.term
	          }
	        },
	        "processResults": function (data, page) {
	        return {
	          results: data
	        };
	      },
	    },
	});

	$('#ipph22beli,#ipph22jual').select2({
	     "allowClear": true,
	     "theme":"bootstrap4",
		 "allowAddLink": true,
		 "addLink": "form_pajak",  
		 "linkTitle": "Pajak",	     	     
	     "ajax": {
	        "url": base_url+"Select_Master/view_pajak_pph",
	        "type": "post",
	        "dataType": "json",                                       
	        "delay": 800,
	        "data": function(params) {
	          return {
	            search: params.term
	          }
	        },
	        "processResults": function (data, page) {
	        return {
	          results: data
	        };
	      },
	    },
	});	

	$("#btn-tab-utility").click(function(){
	    $(".tab-wrap").removeClass("noresultfound-x");                                   
		$("#badd").addClass("disabled");
		$("#bedit").addClass("disabled");	
		$("#bdelete").addClass("disabled");
		$("#bsave").removeClass("disabled");
		$("#brefresh").addClass("disabled");		
	    setTimeout(function () {
	      $('#icetakpos').focus();        
	    },300);	
	});

	$("#carikontak").click(function() {
		if($(this).attr('role')) {
		  $.ajax({ 
		    "url"    : base_url+"Modal/cari_kontak", 
		    "type"   : "POST", 
		    "dataType" : "html", 
		    "beforeSend": function(){
		      parent.window.$('.loader-wrap').removeClass('d-none');
		      parent.window.$(".modal").modal("show");                  
		      parent.window.$(".modal-title").html("Cari Kontak");
		      parent.window.$("#modaltrigger").val("iframe-page-settings");
		      parent.window.$('#coltrigger').val('kontak');                
		    },
		    "error": function(){
		      parent.window.$('.loader-wrap').addClass('d-none');
		      console.log('error menampilkan modal cari kontak...');
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
		  });
		}    
	});

})