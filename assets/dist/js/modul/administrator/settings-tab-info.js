$(function() {

	$('#ibulan').select2({
	     "minimumResultsForSearch": "Infinity",                 
	     "theme":"bootstrap4"
	});

	$('#itahun').select2({
	   "allowClear": false,
	   "theme":"bootstrap4",
	   "allowAddLink": true,
	   "addLink":"form_periode",      
	   "linkTitle":"Periode",                                
	   "ajax": {
	      "url": base_url+"Select_Master/view_tahun_periode",
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
	  }
	});	

	$("#btn-tab-menu").click(function(){
	    $(".tab-wrap").removeClass("noresultfound-x");                                   
		$("#badd").addClass("disabled");
		$("#bedit").addClass("disabled");	
		$("#bdelete").addClass("disabled");
		$("#bsave").removeClass("disabled");
		$("#brefresh").addClass("disabled");		
	    setTimeout(function () {
	      $('#inama').focus();        
	    },300);	
	});

	_getData();

	function _getData(){
	    $.ajax({ 
	      "url"    : base_url+"Settings_Info/getdata",       
	      "type"   : "POST", 
	      "dataType" : "json", 
	      "cache"  : false,
	      "beforeSend" : function(){
	      },        
	      "error"  : function(xhr,status,error){
	        parent.window.toastr.error("Error : "+ xhr.status);
	        console.error(xhr.responseText);
	        return;
	      },
	      "success" : function(result) {
	          const _tahun = $("<option selected='selected'></option>").val(result.data[0]['idtahun']).text(result.data[0]['tahun']);	      	
	          const _ppnpos = $("<option selected='selected'></option>").val(result.data[0]['idppnpos']).text(result.data[0]['ippnpos']);
	          const _ppnbeli = $("<option selected='selected'></option>").val(result.data[0]['idppnbeli']).text(result.data[0]['ippnbeli']);
	          const _ppnjual = $("<option selected='selected'></option>").val(result.data[0]['idppnjual']).text(result.data[0]['ippnjual']);
	          const _pph22beli = $("<option selected='selected'></option>").val(result.data[0]['idpph22beli']).text(result.data[0]['ipph22beli']);
	          const _pph22jual = $("<option selected='selected'></option>").val(result.data[0]['idpph22jual']).text(result.data[0]['ipph22jual']);	          	          	          	      		          
	
	          $('#iid').val(result.data[0]['id']);            
	          $('#inama').val(result.data[0]['nama']);
	          $('#ialamat1').val(result.data[0]['alamat1']);
	          $('#ialamat2').val(result.data[0]['alamat2']);	
	          $('#ikota').val(result.data[0]['kota']);	
	          $('#ipropinsi').val(result.data[0]['propinsi']);
	          $('#ikodepos').val(result.data[0]['kodepos']);	          		                              
	          $('#inegara').val(result.data[0]['negara']);
	          $('#itelp1').val(result.data[0]['telp1']);	          
	          $('#itelp2').val(result.data[0]['telp2']);
	          $('#ifaks').val(result.data[0]['faks']);	          	          	          	          
	          $('#iemail').val(result.data[0]['email']);	          
	          $('#ibulan').val(result.data[0]['bulan']).trigger('change');
	          $('#itahun').append(_tahun).trigger('change');
	          $('#metodepersediaan').val("FIFO");	          
	          $('#matauang').val(result.data[0]['uang']);
	          $('#icetakpos').val(result.data[0]['icetakpos']).trigger('change');
	          $('#ibarcodepos').val(result.data[0]['ibarcodepos']).trigger('change');
	          $('#ipajakpos').val(result.data[0]['ipajakpos']).trigger('change');
	          $('#ipajakbeli').val(result.data[0]['ipajakbeli']).trigger('change');	          	          	          	          	          	          	          	          
	          $('#ipajakjual').val(result.data[0]['ipajakjual']).trigger('change');
	          if(result.data[0]['ippnpos'] != null) $('#ippnpos').append(_ppnpos).trigger('change');
	          if(result.data[0]['ippnbeli'] != null) $('#ippnbeli').append(_ppnbeli).trigger('change');	          
	          if(result.data[0]['ippnjual'] != null) $('#ippnjual').append(_ppnjual).trigger('change');
	          if(result.data[0]['ipph22beli'] != null) $('#ipph22beli').append(_pph22beli).trigger('change');	          	          	          
	          if(result.data[0]['ipph22jual'] != null) $('#ipph22jual').append(_pph22jual).trigger('change');	          	          	          	          
	          $('#idkontak').val(result.data[0]['ikontakpos']);	          
	          $('#kontak').val(result.data[0]['kontakkode']);	
	          $('#multidivisi').val(result.data[0]['idivisi']).trigger('change');
	          $('#multiproyek').val(result.data[0]['iproyek']).trigger('change');
	          $('#multisatuan').val(result.data[0]['isatuan']).trigger('change');	          	          	                    	          
	          $('#multikurs').val(result.data[0]['imatauang']).trigger('change');
	          $('#idecimalqty').val(result.data[0]['idecimalqty']);	          
	          return;
	    } 
	  })
	}

})