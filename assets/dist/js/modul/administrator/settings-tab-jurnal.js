$(function() {

	$('.coa-jurnal').select2({
	   "allowClear": true,
	   "theme":"bootstrap4",
	   "allowAddLink": true,
	   "addLink":"form_coa",      
	   "linkTitle":"Akun",                                
	   "ajax": {
	      "url": base_url+"Select_Master/view_coa",
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
	   "templateResult": textSelect,              
	});

	function textSelect(par){
		if(!par.id){ return par.text; }
		var $par = $('<span>('+par.kode+') '+par.text+'</span>');
		return $par;
	}

	$("#btn-tab-jurnal").click(function() {
	    $(".tab-wrap").removeClass("noresultfound-x");                                   		
		$("#badd").addClass("disabled");
		$("#bedit").addClass("disabled");	
		$("#bdelete").addClass("disabled");
//		$("#bsave").removeClass("disabled");
		$("#brefresh").addClass("disabled");		
	});

	_getData();

	function _getData(){

		// Uang Muka Pembelian (Jurnal Uang Muka Pembelian)
	    $.ajax({ 
	      "url"    : base_url+"Settings_Jurnal/getdata",       
	      "type"   : "POST", 
	      "dataType" : "json", 
	      "data":"kode=DPV&keterangan=uangmukapembelian",
	      "cache"  : false,
	      "error"  : function(xhr,status,error){
	        console.error(xhr.responseText);
	        return;
	      },
	      "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idumb_d_1").val(result.data[0]['id']);
			$("#ketumb_d_1").val(result.data[0]['keterangan']);	          	    
			$("#kodeumb_d_1").val("DPV");
			$('#coaumb_d_1').append(_coa).trigger('change');	          
			return;
	    } 
	  	});		

		// Uang Muka Pembelian (Jurnal Uang Muka Pembelian)
	    $.ajax({ 
	      "url"    : base_url+"Settings_Jurnal/getdata",       
	      "type"   : "POST", 
	      "dataType" : "json", 
	      "data":"kode=DPV&keterangan=pajakmasukan",
	      "cache"  : false,
	      "error"  : function(xhr,status,error){
	        console.error(xhr.responseText);
	        return;
	      },
	      "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idumb_d_2").val(result.data[0]['id']);
			$("#ketumb_d_2").val(result.data[0]['keterangan']);	          	    
			$("#kodeumb_d_2").val("DPV");
			$('#coaumb_d_2').append(_coa).trigger('change');	          
			return;
	    } 
	  	});			  	

		// Hutang Uang Muka Pembelian (Jurnal Uang Muka Pembelian)
	    $.ajax({ 
	      "url"    : base_url+"Settings_Jurnal/getdata",       
	      "type"   : "POST", 
	      "dataType" : "json", 
	      "data":"kode=DPV&keterangan=hutanguangmuka",
	      "cache"  : false,
	      "error"  : function(xhr,status,error){
	        console.error(xhr.responseText);
	        return;
	      },
	      "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idumb_k_1").val(result.data[0]['id']);
			$("#ketumb_k_1").val(result.data[0]['keterangan']);	          	    
			$("#kodeumb_k_1").val("DPV");
			$('#coaumb_k_1').append(_coa).trigger('change');	          
			return;
	    } 
	  	});			  	

		// Penerimaan Barang Belum Faktur (Jurnal Uang Muka Pembelian)
	    $.ajax({ 
	      "url"    : base_url+"Settings_Jurnal/getdata",       
	      "type"   : "POST", 
	      "dataType" : "json", 
	      "data":"kode=TB&keterangan=persediaanbelumfaktur",
	      "cache"  : false,
	      "error"  : function(xhr,status,error){
	        console.error(xhr.responseText);
	        return;
	      },
	      "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idumb_k_2").val(result.data[0]['id']);
			$("#ketumb_k_2").val(result.data[0]['keterangan']);	          	    
			$("#kodeumb_k_2").val("TB");
			$('#coaumb_k_2').append(_coa).trigger('change');	          
			return;
	    } 
	  	});			  		  	

		// Persediaan Barang (Jurnal Pembelian)
	    $.ajax({ 
	      "url"    : base_url+"Settings_Jurnal/getdata",       
	      "type"   : "POST", 
	      "dataType" : "json", 
	      "data":"kode=PJ&keterangan=persediaanbarang",
	      "cache"  : false,
	      "error"  : function(xhr,status,error){
	        console.error(xhr.responseText);
	        return;
	      },
	      "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpb_d_1").val(result.data[0]['id']);
			$("#ketpb_d_1").val(result.data[0]['keterangan']);	          	    
			$("#kodepb_d_1").val("PJ");
			$('#coapb_d_1').append(_coa).trigger('change');	          
			return;
	    } 
	  	});		

		// Pajak Masukan (Jurnal Pembelian)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=PJ&keterangan=pajakmasukan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpb_d_2").val(result.data[0]['id']);
			$("#ketpb_d_2").val(result.data[0]['keterangan']);	          
			$("#kodepb_d_2").val("PJ");
			$('#coapb_d_2').append(_coa).trigger('change');	          
		    return;
		} 
		});

		// Hutang Dagang (Jurnal Pembelian)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=PJ&keterangan=hutangdagang",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpb_k_1").val(result.data[0]['id']);
			$("#ketpb_k_1").val(result.data[0]['keterangan']);	          
			$("#kodepb_k_1").val("PJ");
			$('#coapb_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});

		// Hutang Konsinyasi (Jurnal Pembelian)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=PJ&keterangan=hutangkonsinyasi",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpb_k_2").val(result.data[0]['id']);
			$("#ketpb_k_2").val(result.data[0]['keterangan']);	          
			$("#kodepb_k_2").val("PJ");
			$('#coapb_k_2').append(_coa).trigger('change');	          
		    return;
		} 
		});				

		// Harga Pokok (Jurnal BKG Retur)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=BKG&keterangan=hargapokok",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idbkgr_d_1").val(result.data[0]['id']);
			$("#ketbkgr_d_1").val(result.data[0]['keterangan']);	          
			$("#kodebkgr_d_1").val("BKG");
			$('#coabkgr_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});						

		// Persediaan Barang (Jurnal BKG Retur)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=BKG&keterangan=persediaanbarang",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idbkgr_k_1").val(result.data[0]['id']);
			$("#ketbkgr_k_1").val(result.data[0]['keterangan']);	          
			$("#kodebkgr_k_1").val("BKG");
			$('#coabkgr_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});								

		// Hutang Retur (Jurnal Retur Pembelian)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=PR&keterangan=hutangretur",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrpb_d_1").val(result.data[0]['id']);
			$("#ketrpb_d_1").val(result.data[0]['keterangan']);	          
			$("#koderpb_d_1").val("PR");
			$('#coarpb_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});						

		// Retur Pembelian (Jurnal Retur Pembelian)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=PR&keterangan=returpembelian",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrpb_k_1").val(result.data[0]['id']);
			$("#ketrpb_k_1").val(result.data[0]['keterangan']);	          
			$("#koderpb_k_1").val("PR");
			$('#coarpb_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});								

		// Pajak Masukan (Jurnal Retur Pembelian)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=PR&keterangan=pajakmasukan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrpb_k_2").val(result.data[0]['id']);
			$("#ketrpb_k_2").val(result.data[0]['keterangan']);	          
			$("#koderpb_k_2").val("PR");
			$('#coarpb_k_2').append(_coa).trigger('change');	          
		    return;
		} 
		});										

		// Hutang Dagang (Jurnal Pembayaran Hutang)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=VP&keterangan=hutangdagang",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpbh_d_1").val(result.data[0]['id']);
			$("#ketpbh_d_1").val(result.data[0]['keterangan']);	          
			$("#kodepbh_d_1").val("VP");
			$('#coapbh_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});

		// Hutang Dagang (Jurnal Pembayaran Hutang)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=VP&keterangan=hutanguangmuka",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpbh_d_2").val(result.data[0]['id']);
			$("#ketpbh_d_2").val(result.data[0]['keterangan']);	          
			$("#kodepbh_d_2").val("VP");
			$('#coapbh_d_2').append(_coa).trigger('change');	          
		    return;
		} 
		});		

		// Diskon Pembelian (Jurnal Pembayaran Hutang)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=VP&keterangan=diskonpembelian",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpbh_k_1").val(result.data[0]['id']);
			$("#ketpbh_k_1").val(result.data[0]['keterangan']);	          
			$("#kodepbh_k_1").val("VP");
			$('#coapbh_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});

		// Hutang Retur (Jurnal Pembayaran Hutang)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=VP&keterangan=hutangretur",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpbh_k_2").val(result.data[0]['id']);
			$("#ketpbh_k_2").val(result.data[0]['keterangan']);	          
			$("#kodepbh_k_2").val("VP");
			$('#coapbh_k_2').append(_coa).trigger('change');	          
		    return;
		} 
		});		

		// Kas Bank (Jurnal Pembayaran Hutang)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=VP&keterangan=kasbank",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpbh_k_3").val(result.data[0]['id']);
			$("#ketpbh_k_3").val(result.data[0]['keterangan']);	          
			$("#kodepbh_k_3").val("VP");
			$('#coapbh_k_3').append(_coa).trigger('change');	          
		    return;
		} 
		});				

		// Harga Pokok (Jurnal BKG Jual)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=SJ&keterangan=hargapokok",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idbkgj_d_1").val(result.data[0]['id']);
			$("#ketbkgj_d_1").val(result.data[0]['keterangan']);	          
			$("#kodebkgj_d_1").val("SJ");
			$('#coabkgj_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});						

		// Persediaan Barang (Jurnal BKG Retur)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=SJ&keterangan=persediaanbarang",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idbkgj_k_1").val(result.data[0]['id']);
			$("#ketbkgj_k_1").val(result.data[0]['keterangan']);	          
			$("#kodebkgj_k_1").val("SJ");
			$('#coabkgj_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});								

		// Piutang Uang Muka (Jurnal Uang Muka Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=DPC&keterangan=piutanguangmuka",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#iddpc_d_1").val(result.data[0]['id']);
			$("#ketdpc_d_1").val(result.data[0]['keterangan']);	          
			$("#kodedpc_d_1").val("DPC");
			$('#coadpc_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});					

		// Pengiriman Barang Belum Faktur (Jurnal Penjualan Lainnya)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=SJ&keterangan=persediaanterkirim",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#iddpc_d_2").val(result.data[0]['id']);
			$("#ketdpc_d_2").val(result.data[0]['keterangan']);	          
			$("#kodedpc_d_2").val("SJ");
			$('#coadpc_d_2').append(_coa).trigger('change');	          
		    return;
		} 
		});							

		// Pajak Keluaran Uang Muka (Jurnal Uang Muka Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=DPC&keterangan=pajakkeluaran",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#iddpc_k_1").val(result.data[0]['id']);
			$("#ketdpc_k_1").val(result.data[0]['keterangan']);	          
			$("#kodedpc_k_1").val("DPC");
			$('#coadpc_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});					

		// Uang Muka Penjualan (Jurnal Uang Muka Penjualan)
		/*
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=DPC&keterangan=uangmukapenjualan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#iddpc_k_2").val(result.data[0]['id']);
			$("#ketdpc_k_2").val(result.data[0]['keterangan']);	          
			$("#kodedpc_k_2").val("DPC");
			$('#coadpc_k_2').append(_coa).trigger('change');	          
		    return;
		} 
		});					
		*/
		
		// Piutang Dagang (Jurnal Invoice Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=IV&keterangan=piutangdagang",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idiv_d_1").val(result.data[0]['id']);
			$("#ketiv_d_1").val(result.data[0]['keterangan']);	          
			$("#kodeiv_d_1").val("IV");
			$('#coaiv_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});					

		// Pajak Keluaran (Jurnal Invoice Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=IV&keterangan=pajakkeluaran",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idiv_k_1").val(result.data[0]['id']);
			$("#ketiv_k_1").val(result.data[0]['keterangan']);	          
			$("#kodeiv_k_1").val("IV");
			$('#coaiv_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});								

		// Pendapatan (Jurnal Invoice Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=IV&keterangan=pendapatanpenjualan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idiv_k_2").val(result.data[0]['id']);
			$("#ketiv_k_2").val(result.data[0]['keterangan']);	          
			$("#kodeiv_k_2").val("IV");
			$('#coaiv_k_2').append(_coa).trigger('change');	          
		    return;
		} 
		});										

		// Retur Penjualan (Jurnal Retur Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=SR&keterangan=returpenjualan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrpj_d_1").val(result.data[0]['id']);
			$("#ketrpj_d_1").val(result.data[0]['keterangan']);	          
			$("#koderpj_d_1").val("SR");
			$('#coarpj_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});					

		// Pajak Keluaran (Jurnal Retur Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=SR&keterangan=pajakkeluaran",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrpj_d_2").val(result.data[0]['id']);
			$("#ketrpj_d_2").val(result.data[0]['keterangan']);	          
			$("#koderpj_d_2").val("SR");
			$('#coarpj_d_2').append(_coa).trigger('change');	          
		    return;
		} 
		});							

		// Piutang Retur (Jurnal Retur Penjualan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=SR&keterangan=piutangretur",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrpj_k_1").val(result.data[0]['id']);
			$("#ketrpj_k_1").val(result.data[0]['keterangan']);	          
			$("#koderpj_k_1").val("SR");
			$('#coarpj_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});									

		// Kas/Bank (Jurnal Penerimaan Pembayaran)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=CP&keterangan=kasbank",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idppj_d_1").val(result.data[0]['id']);
			$("#ketppj_d_1").val(result.data[0]['keterangan']);	          
			$("#kodeppj_d_1").val("CP");
			$('#coappj_d_1').append(_coa).trigger('change');	          
		    return;
		} 
		});				

		// Diskon Penjualan (Jurnal Penerimaan Pembayaran)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=CP&keterangan=diskonpenjualan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idppj_d_2").val(result.data[0]['id']);
			$("#ketppj_d_2").val(result.data[0]['keterangan']);	          
			$("#kodeppj_d_2").val("CP");
			$('#coappj_d_2').append(_coa).trigger('change');	          
		    return;
		} 
		});							

		// Piutang Retur (Jurnal Penerimaan Pembayaran)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=CP&keterangan=piutangretur",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idppj_d_3").val(result.data[0]['id']);
			$("#ketppj_d_3").val(result.data[0]['keterangan']);	          
			$("#kodeppj_d_3").val("CP");
			$('#coappj_d_3').append(_coa).trigger('change');	          
		    return;
		} 
		});							

		// Piutang Dagang (Jurnal Penerimaan Pembayaran)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=CP&keterangan=piutangdagang",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idppj_k_1").val(result.data[0]['id']);
			$("#ketppj_k_1").val(result.data[0]['keterangan']);	          
			$("#kodeppj_k_1").val("CP");
			$('#coappj_k_1').append(_coa).trigger('change');	          
		    return;
		} 
		});

		// Piutang Uang Muka (Jurnal Penerimaan Pembayaran)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=CP&keterangan=piutanguangmuka",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idppj_k_2").val(result.data[0]['id']);
			$("#ketppj_k_2").val(result.data[0]['keterangan']);	          
			$("#kodeppj_k_2").val("CP");
			$('#coappj_k_2').append(_coa).trigger('change');	          
		    return;
		} 
		});		

		// Laba Berjalan (Jurnal Rek R/L)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=RL&keterangan=berjalan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrl_1").val(result.data[0]['id']);
			$("#ketrl_1").val(result.data[0]['keterangan']);	          
			$("#koderl_1").val("RL");
			$('#coarl_1').append(_coa).trigger('change');	          
		    return;
		} 
		});						

		// Laba Ditahan (Jurnal Rek R/L)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=RL&keterangan=ditahan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idrl_2").val(result.data[0]['id']);
			$("#ketrl_2").val(result.data[0]['keterangan']);	          
			$("#koderl_2").val("RL");
			$('#coarl_2').append(_coa).trigger('change');	          
		    return;
		} 
		});								

		// Persediaan Barang (Jurnal Default Persediaan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=ITP&keterangan=persediaan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#iditem_1").val(result.data[0]['id']);
			$("#ketitem_1").val(result.data[0]['keterangan']);	          
			$("#kodeitem_1").val("ITP");
			$('#coaitem_1').append(_coa).trigger('change');	          
		    return;
		} 
		});										

		// Pendapatan (Jurnal Default Persediaan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=ITT&keterangan=pendapatan",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#iditem_2").val(result.data[0]['id']);
			$("#ketitem_2").val(result.data[0]['keterangan']);	          
			$("#kodeitem_2").val("ITT");
			$('#coaitem_2').append(_coa).trigger('change');	          
		    return;
		} 
		});												

		// Harga Pokok (Jurnal Default Persediaan)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=ITH&keterangan=hargapokok",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#iditem_3").val(result.data[0]['id']);
			$("#ketitem_3").val(result.data[0]['keterangan']);	          
			$("#kodeitem_3").val("ITH");
			$('#coaitem_3').append(_coa).trigger('change');	          
		    return;
		} 
		});												

		// Tunai Kas (Jurnal Penjualan Tunai)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=IP&keterangan=kasbank",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpos_1").val(result.data[0]['id']);
			$("#ketpos_1").val(result.data[0]['keterangan']);	          
			$("#kodepos_1").val("IP");
			$('#coapos_1').append(_coa).trigger('change');	          
		    return;
		} 
		});

		// Piutang Kartu Debit (Jurnal Penjualan Tunai)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=IP&keterangan=piutangkartudebit",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpos_2").val(result.data[0]['id']);
			$("#ketpos_2").val(result.data[0]['keterangan']);	          
			$("#kodepos_2").val("IP");
			$('#coapos_2').append(_coa).trigger('change');	          
		    return;
		} 
		});																

		// Piutang Kartu Debit (Jurnal Penjualan Tunai)
		$.ajax({ 
		  "url"    : base_url+"Settings_Jurnal/getdata",       
		  "type"   : "POST", 
		  "dataType" : "json", 
		  "data":"kode=IP&keterangan=piutangkartukredit",
		  "cache"  : false,
		  "error"  : function(xhr,status,error){
		    console.error(xhr.responseText);
		    return;
		  },
		  "success" : function(result) {
			const _coa = $("<option selected='selected'></option>").val(result.data[0]['idcoa']).text(result.data[0]['coa']);	      	
			$("#idpos_3").val(result.data[0]['id']);
			$("#ketpos_3").val(result.data[0]['keterangan']);	          
			$("#kodepos_3").val("IP");
			$('#coapos_3').append(_coa).trigger('change');	          
		    return;
		} 
		});																		

	}

})