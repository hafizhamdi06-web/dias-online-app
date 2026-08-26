$(function() {

	$('.tab-wrap').overlayScrollbars({
		className: "os-theme-dark",
		overflowBehavior : {
		  x :'scroll',
		  y :'scroll' 
		},
		scrollbars : {
		  autoHide : 'scroll',
		  autoHideDelay : 300,
		  snapHandle:true             
		}
	}); 

	$('.datepicker').inputmask({
		alias:'dd/mm/yyyy',
		mask: "1-2-y", 
		placeholder: "_", 
		leapday: "-02-29", 
		separator: "-"
	});    	  

	this.addEventListener('contextmenu', function(event){
		event.preventDefault();
	});

	$(this).on('select2:open', () => {
		this.querySelector('.select2-search__field').focus();
	});

	$("#bsave").click(function(){
	   _saveData();	
	});    

	var _saveData = async function(){

	  const rey = new FormData();  
	  rey.set('id', $("#iid").val());
	  rey.set('nama', $("#inama").val());
	  rey.set('alamat1', $("#ialamat1").val());
	  rey.set('alamat2', $("#ialamat2").val());
	  rey.set('kota', $("#ikota").val());
	  rey.set('propinsi', $("#ipropinsi").val());
	  rey.set('kodepos', $("#ikodepos").val());	  	
	  rey.set('negara', $("#inegara").val());
	  rey.set('telp1', $("#itelp1").val());
	  rey.set('telp2', $("#itelp2").val());
	  rey.set('faks', $("#ifaks").val());
	  rey.set('email', $("#iemail").val());	  	  	  	  	    	  	  	  
	  rey.set('bulan', $("#ibulan").val());
	  rey.set('tahun', $("#itahun").val());
	  rey.set('icetakpos', $("#icetakpos").val());	    
	  rey.set('ibarcodepos', $("#ibarcodepos").val());	    
	  rey.set('ipajakpos', $("#ipajakpos").val());	    
	  rey.set('ikontakpos', $("#idkontak").val());	    	  
	  rey.set('ippnpos', $("#ippnpos").val());	    
	  rey.set('ipajakbeli', $("#ipajakbeli").val());	    	  
	  rey.set('ippnbeli', $("#ippnbeli").val());	    
	  rey.set('ipph22beli', $("#ipph22beli").val());	    	  
	  rey.set('ipajakjual', $("#ipajakjual").val());	    	  
	  rey.set('ippnjual', $("#ippnjual").val());	    
	  rey.set('ipph22jual', $("#ipph22jual").val());	    	  	 
	  rey.set('idivisi', $("#multidivisi").val());	    	  	 	   
	  rey.set('iproyek', $("#multiproyek").val());	    	  	 	   	  
	  rey.set('imatauang', $("#multikurs").val());	    	  	 	   	  	  
	  rey.set('isatuan', $("#multisatuan").val());
	  rey.set('idecimalqty', $("#idecimalqty").val());	  	    	  	 	   	  	  	  

	  const _J1 = new FormData();  
	  _J1.set('id', $("#idpb_d_1").val());
	  _J1.set('kode', $("#kodepb_d_1").val());
	  _J1.set('ket', $("#ketpb_d_1").val());
	  _J1.set('coa', $("#coapb_d_1").val());	  	  	  

	  const _J2 = new FormData();  
	  _J2.set('id', $("#idpb_d_2").val());
	  _J2.set('kode', $("#kodepb_d_2").val());
	  _J2.set('ket', $("#ketpb_d_2").val());
	  _J2.set('coa', $("#coapb_d_2").val());	  	  	  	  

	  const _J3 = new FormData();  
	  _J3.set('id', $("#idpb_k_1").val());
	  _J3.set('kode', $("#kodepb_k_1").val());
	  _J3.set('ket', $("#ketpb_k_1").val());
	  _J3.set('coa', $("#coapb_k_1").val());	  	  	  	  

	  const _J4 = new FormData();  
	  _J4.set('id', $("#idpb_k_2").val());
	  _J4.set('kode', $("#kodepb_k_2").val());
	  _J4.set('ket', $("#ketpb_k_2").val());
	  _J4.set('coa', $("#coapb_k_2").val());	  	  	  	  	  

	  const _J5 = new FormData();  
	  _J5.set('id', $("#idbkgr_d_1").val());
	  _J5.set('kode', $("#kodebkgr_d_1").val());
	  _J5.set('ket', $("#ketbkgr_d_1").val());
	  _J5.set('coa', $("#coabkgr_d_1").val());	  	  	  	  	  	  

	  const _J6 = new FormData();  
	  _J6.set('id', $("#idbkgr_k_1").val());
	  _J6.set('kode', $("#kodebkgr_k_1").val());
	  _J6.set('ket', $("#ketbkgr_k_1").val());
	  _J6.set('coa', $("#coabkgr_k_1").val());	  	  	  	  	  	  	  

	  const _J7 = new FormData();  
	  _J7.set('id', $("#idrpb_d_1").val());
	  _J7.set('kode', $("#koderpb_d_1").val());
	  _J7.set('ket', $("#ketrpb_d_1").val());
	  _J7.set('coa', $("#coarpb_d_1").val());	  	  	  	  	  	  

	  const _J8 = new FormData();  
	  _J8.set('id', $("#idrpb_k_1").val());
	  _J8.set('kode', $("#koderpb_k_1").val());
	  _J8.set('ket', $("#ketrpb_k_1").val());
	  _J8.set('coa', $("#coarpb_k_1").val());	  	  	  	  	  	  	  

	  const _J9 = new FormData();  
	  _J9.set('id', $("#idrpb_k_2").val());
	  _J9.set('kode', $("#koderpb_k_2").val());
	  _J9.set('ket', $("#ketrpb_k_2").val());
	  _J9.set('coa', $("#coarpb_k_2").val());	  	  	  	  	  	  	  	  

	  const _J10 = new FormData();  
	  _J10.set('id', $("#idpbh_d_1").val());
	  _J10.set('kode', $("#kodepbh_d_1").val());
	  _J10.set('ket', $("#ketpbh_d_1").val());
	  _J10.set('coa', $("#coapbh_d_1").val());	  	  	  	  	  	  

	  const _J11 = new FormData();  
	  _J11.set('id', $("#idpbh_k_1").val());
	  _J11.set('kode', $("#kodepbh_k_1").val());
	  _J11.set('ket', $("#ketpbh_k_1").val());
	  _J11.set('coa', $("#coapbh_k_1").val());

	  const _J12 = new FormData();  
	  _J12.set('id', $("#idpbh_k_2").val());
	  _J12.set('kode', $("#kodepbh_k_2").val());
	  _J12.set('ket', $("#ketpbh_k_2").val());
	  _J12.set('coa', $("#coapbh_k_2").val());

	  const _J13 = new FormData();  
	  _J13.set('id', $("#idpbh_k_3").val());
	  _J13.set('kode', $("#kodepbh_k_3").val());
	  _J13.set('ket', $("#ketpbh_k_3").val());
	  _J13.set('coa', $("#coapbh_k_3").val());	  	  	  	  	  	  	  	  

	  const _J14 = new FormData();  
	  _J14.set('id', $("#idbkgj_d_1").val());
	  _J14.set('kode', $("#kodebkgj_d_1").val());
	  _J14.set('ket', $("#ketbkgj_d_1").val());
	  _J14.set('coa', $("#coabkgj_d_1").val());	  	  	  	  	  	  

	  const _J15 = new FormData();  
	  _J15.set('id', $("#idbkgj_k_1").val());
	  _J15.set('kode', $("#kodebkgj_k_1").val());
	  _J15.set('ket', $("#ketbkgj_k_1").val());
	  _J15.set('coa', $("#coabkgj_k_1").val());	  	  	  	  	  	  	  

	  const _J16 = new FormData();  
	  _J16.set('id', $("#idiv_d_1").val());
	  _J16.set('kode', $("#kodeiv_d_1").val());
	  _J16.set('ket', $("#ketiv_d_1").val());
	  _J16.set('coa', $("#coaiv_d_1").val());	  	  	  	  	  	  	  

	  const _J17 = new FormData();  
	  _J17.set('id', $("#idiv_k_1").val());
	  _J17.set('kode', $("#kodeiv_k_1").val());
	  _J17.set('ket', $("#ketiv_k_1").val());
	  _J17.set('coa', $("#coaiv_k_1").val());	  	  	  	  	  	  	  

	  const _J18 = new FormData();  
	  _J18.set('id', $("#idiv_k_2").val());
	  _J18.set('kode', $("#kodeiv_k_2").val());
	  _J18.set('ket', $("#ketiv_k_2").val());
	  _J18.set('coa', $("#coaiv_k_2").val());	  	  	  	  	  	  	  	  

	  const _J19 = new FormData();  
	  _J19.set('id', $("#idrpj_d_1").val());
	  _J19.set('kode', $("#koderpj_d_1").val());
	  _J19.set('ket', $("#ketrpj_d_1").val());
	  _J19.set('coa', $("#coarpj_d_1").val());	  	  	  	  	  	  	  

	  const _J20 = new FormData();  
	  _J20.set('id', $("#idrpj_d_2").val());
	  _J20.set('kode', $("#koderpj_d_2").val());
	  _J20.set('ket', $("#ketrpj_d_2").val());
	  _J20.set('coa', $("#coarpj_d_2").val());	  	  	  	  	  	  	  	  

	  const _J21 = new FormData();  
	  _J21.set('id', $("#idrpj_k_1").val());
	  _J21.set('kode', $("#koderpj_k_1").val());
	  _J21.set('ket', $("#ketrpj_k_1").val());
	  _J21.set('coa', $("#coarpj_k_1").val());	  	  	  	  	  	  	  

	  const _J22 = new FormData();  
	  _J22.set('id', $("#idppj_d_1").val());
	  _J22.set('kode', $("#kodeppj_d_1").val());
	  _J22.set('ket', $("#ketppj_d_1").val());
	  _J22.set('coa', $("#coappj_d_1").val());	  	  	  	  	  	  	  	  

	  const _J23 = new FormData();  
	  _J23.set('id', $("#idppj_d_2").val());
	  _J23.set('kode', $("#kodeppj_d_2").val());
	  _J23.set('ket', $("#ketppj_d_2").val());
	  _J23.set('coa', $("#coappj_d_2").val());	  	  	  	  	  	  	  	  	  

	  const _J24 = new FormData();  
	  _J24.set('id', $("#idppj_d_3").val());
	  _J24.set('kode', $("#kodeppj_d_3").val());
	  _J24.set('ket', $("#ketppj_d_3").val());
	  _J24.set('coa', $("#coappj_d_3").val());	  	  	  	  	  	  	  	  	  

	  const _J25 = new FormData();  
	  _J25.set('id', $("#idppj_k_1").val());
	  _J25.set('kode', $("#kodeppj_k_1").val());
	  _J25.set('ket', $("#ketppj_k_1").val());
	  _J25.set('coa', $("#coappj_k_1").val());	  	  	  	  	  	  	  	  

	  const _J26 = new FormData();  
	  _J26.set('id', $("#idrl_1").val());
	  _J26.set('kode', $("#koderl_1").val());
	  _J26.set('ket', $("#ketrl_1").val());
	  _J26.set('coa', $("#coarl_1").val());	  	  	  	  	  	  	  	  

	  const _J27 = new FormData();  
	  _J27.set('id', $("#idrl_2").val());
	  _J27.set('kode', $("#koderl_2").val());
	  _J27.set('ket', $("#ketrl_2").val());
	  _J27.set('coa', $("#coarl_2").val());	  	  	  	  	  	  	  	  	  

	  const _J28 = new FormData();  
	  _J28.set('id', $("#iditem_1").val());
	  _J28.set('kode', $("#kodeitem_1").val());
	  _J28.set('ket', $("#ketitem_1").val());
	  _J28.set('coa', $("#coaitem_1").val());	  	  	  	  	  	  	  	  

	  const _J29 = new FormData();  
	  _J29.set('id', $("#iditem_2").val());
	  _J29.set('kode', $("#kodeitem_2").val());
	  _J29.set('ket', $("#ketitem_2").val());
	  _J29.set('coa', $("#coaitem_2").val());	  	  	  	  	  	  	  	  	  

	  const _J30 = new FormData();  
	  _J30.set('id', $("#iditem_3").val());
	  _J30.set('kode', $("#kodeitem_3").val());
	  _J30.set('ket', $("#ketitem_3").val());
	  _J30.set('coa', $("#coaitem_3").val());	  	  	  	  	  	  	  	  

	  const _J31 = new FormData();  
	  _J31.set('id', $("#idumb_d_1").val());
	  _J31.set('kode', $("#kodeumb_d_1").val());
	  _J31.set('ket', $("#ketumb_d_1").val());
	  _J31.set('coa', $("#coaumb_d_1").val());	  	  	  	  	  	  	  	  

	  const _J32 = new FormData();  
	  _J32.set('id', $("#idumb_d_2").val());
	  _J32.set('kode', $("#kodeumb_d_2").val());
	  _J32.set('ket', $("#ketumb_d_2").val());
	  _J32.set('coa', $("#coaumb_d_2").val());	  	  	  	  	  	  	  	  	  

	  const _J33 = new FormData();  
	  _J33.set('id', $("#idumb_k_1").val());
	  _J33.set('kode', $("#kodeumb_k_1").val());
	  _J33.set('ket', $("#ketumb_k_1").val());
	  _J33.set('coa', $("#coaumb_k_1").val());	  	  	  	  	  	  	  	  	  

	  const _J34 = new FormData();  
	  _J34.set('id', $("#idpbh_d_2").val());
	  _J34.set('kode', $("#kodepbh_d_2").val());
	  _J34.set('ket', $("#ketpbh_d_2").val());
	  _J34.set('coa', $("#coapbh_d_2").val());	  	  	  	  	  	  


	  const _J35 = new FormData();  
	  _J35.set('id', $("#iddpc_d_1").val());
	  _J35.set('kode', $("#kodedpc_d_1").val());
	  _J35.set('ket', $("#ketdpc_d_1").val());
	  _J35.set('coa', $("#coadpc_d_1").val());	  	  	  	  	  	  	  	  

	  const _J36 = new FormData();  
	  _J36.set('id', $("#iddpc_k_1").val());
	  _J36.set('kode', $("#kodedpc_k_1").val());
	  _J36.set('ket', $("#ketdpc_k_1").val());
	  _J36.set('coa', $("#coadpc_k_1").val());	  	  	  	  	  	  	  	  

	  const _J37 = new FormData();  
	  _J37.set('id', $("#iddpc_k_2").val());
	  _J37.set('kode', $("#kodedpc_k_2").val());
	  _J37.set('ket', $("#ketdpc_k_2").val());
	  _J37.set('coa', $("#coadpc_k_2").val());	  	  	  	  	  	  	  	  

	  const _J38 = new FormData();  
	  _J38.set('id', $("#idppj_k_2").val());
	  _J38.set('kode', $("#kodeppj_k_2").val());
	  _J38.set('ket', $("#ketppj_k_2").val());
	  _J38.set('coa', $("#coappj_k_2").val());	 

	  const _J39 = new FormData();  
	  _J39.set('id', $("#idumb_k_2").val());
	  _J39.set('kode', $("#kodeumb_k_2").val());
	  _J39.set('ket', $("#ketumb_k_2").val());
	  _J39.set('coa', $("#coaumb_k_2").val());	  

	  const _J40 = new FormData();  
	  _J40.set('id', $("#iddpc_d_2").val());
	  _J40.set('kode', $("#kodedpc_d_2").val());
	  _J40.set('ket', $("#ketdpc_d_2").val());
	  _J40.set('coa', $("#coadpc_d_2").val());

	  const _J41 = new FormData();  
	  _J41.set('id', $("#idpos_1").val());
	  _J41.set('kode', $("#kodepos_1").val());
	  _J41.set('ket', $("#ketpos_1").val());
	  _J41.set('coa', $("#coapos_1").val());

	  const _J42 = new FormData();  
	  _J42.set('id', $("#idpos_2").val());
	  _J42.set('kode', $("#kodepos_2").val());
	  _J42.set('ket', $("#ketpos_2").val());
	  _J42.set('coa', $("#coapos_2").val());

	  const _J43 = new FormData();  
	  _J43.set('id', $("#idpos_3").val());
	  _J43.set('kode', $("#kodepos_3").val());
	  _J43.set('ket', $("#ketpos_3").val());
	  _J43.set('coa', $("#coapos_3").val());	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	  	   	  	  	  	  	  	  	  

      parent.window.$(".loader-wrap").removeClass("d-none");

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Info/savedata", 
	    "type"   : "POST", 
	    "data"   : rey,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J1,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J2,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J3,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J4,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J5,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J6,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J7,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J8,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J9,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J10,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J11,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J12,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J13,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J14,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J15,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J16,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J17,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J18,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J19,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J20,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J21,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J22,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J23,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J24,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J25,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J26,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J27,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J28,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  	  	  	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J29,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J30,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J31,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J32,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J33,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J34,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J35,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J36,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J37,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J38,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J39,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J40,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J41,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   	  	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J42,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   	  	  	  

	  await $.ajax({ 
	    "url"    : base_url+"Settings_Jurnal/savedata", 
	    "type"   : "POST", 
	    "data"   : _J43,
	    "processData": false,
	    "contentType": false,
	    "cache"    : false,
	    "beforeSend" : function(){
	    },
	    "error": function(xhr, status, error){
	      console.error(xhr.responseText);      
	    },
	    "success": function(result) {
	      if(result!=='sukses'){
	        console.error(result);                          
	      }
	    } 
	  });	 	   	  	  	  

      parent.window.$('#vendor-text').html($("#inama").val());
      parent.window.$('#decqty').val($("#idecimalqty").val());      
      parent.window.$('#footer a').html($("#inama").val());
	  parent.window.toastr.success('Data berhasil disimpan');
      parent.window.$(".loader-wrap").addClass("d-none");

	};

    setTimeout(function () {
		if(!parent.window.$(".loader-wrap").hasClass("d-none")){
			parent.window.$(".loader-wrap").addClass("d-none");
		}    	
		$('#inama').focus();        
    },200);	

});