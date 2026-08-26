/* ========================================================================================== */
/* File Name : table-bank-masuk.js
/* Info Lain : 
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';
import { Component_Select2_Account } from '../../component.js';

var tabel = null;
var saldo = 0;
var debit = 0;
var kredit = 0;

$(function() {

	Component_Inputmask_Date('.datepicker');
	Component_Scrollbars('.tab-wrap','scroll','scroll');
	Component_Select2_Account(
		`#coa,#coasampai`,
		`${base_url}Select_Master/view_coa`,
		null,
		null
	);

	if(!parent.window.$(".loader-wrap").hasClass("d-none")){
		parent.window.$(".loader-wrap").addClass("d-none");
	}

	this.addEventListener('contextmenu', (e) => {
		e.preventDefault();
	});

	$(this).on('select2:open', () => {
		this.querySelector('.select2-search__field').focus();
	});  

	var clearFilter = () => {
		$('#tgldari').datepicker('setDate','01-mm-yy');
		$('#tglsampai').datepicker('setDate','dd-mm-yy');
		$('#coa').val('').trigger('change');
		$('#coasampai').val('').trigger('change');		
	}

	clearFilter();

	tabel=$('#table').DataTable({
		"processing": true,
		"serverSide": true,
		"lengthChange": false,
		"searching": false,
		"ordering": false,
		"pagingType":"simple",    
		"select":true,  
		"dom": 'B<"top"pi>tr<"clear">',
		"ajax": {
		    "url":base_url+"Datatable_Transaksi_Full/view_histori_ledger",
		    "type":"post",
		    "dataType": "json",
	        "data": (data) => {
				data.akun = $('#coa').val();
				data.akunsampai = $('#coasampai').val();				
				data.dari = $('#tgldari').val();
				data.sampai = $('#tglsampai').val();          
	        },
		},
		"deferRender": true,
		"bInfo":true,    
	    "language": 
	    {          
	      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
	    },    		    
		"columns": [
			{ "data": "id" },
			{
				orderable:      false,
				data:           null,
				defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
			},    
			{ "data": "nomor" },
			{ "data": "tanggal" },
			{ "data": "sumber" },		      
			{ "data": "kontak" },
			{ "data": "uraian" },
			{ "data": "debit", 
			  "className": 'aright',		      
			  "render": (data, type, row,meta) => {
					debit = data;
					data = accounting.formatMoney(data);
					return data;
			  }		      	
			},
			{ "data": "kredit", 
			  "className": 'aright',		      
			  "render": (data, type, row, meta) => {
					kredit = data;
					data = accounting.formatMoney(data);
					return data;		        		
			  }		      	
			},
			{ "data": "saldo",
			  "className": 'aright',		        		      			      
			  "render": (data, type, row, meta) => {
//			  		return row.nomor;
//				    if (meta.row==0) {
					if (row.nomor == 'SALDO AWAL') {
				    	saldo = data;		        	
						data = accounting.formatMoney(saldo);
						return data;		        			                	
					} else {
				    	saldo = Number(saldo)+Number(debit)-Number(kredit);
						data = accounting.formatMoney(saldo);
						return data;						
					}
			  }
			},
			{ "data": "link",
			  "visible": false
			},								      
			{ "data": "captionlink",
			  "visible": false
			},								      			
			{ "data": "coa"
			},								      						
		],
		"buttons": [{
	        extend: 'excelHtml5',
	        text: '<i class="fa fa-file-excel px-1"></i> Excel File',
	        title: title + ' - ' + copy,
			autoFilter: false,	        
			messageTop: () => {
				let data = $('#table_info').text();
				if(typeof data == undefined || data == null) return '';
				return data;
			},                
	        exportOptions: {
				format: {
					body: (data, row, column, node) => {
					    return column === 7 || column === 6 || column === 5 ?
					        data.split('.').join('').toString().replace(',','.') :
					        data;
					}
				},                	
	            columns: [2,3,4,5,6,7,8,9,12],
				modifier: {
				    order: 'current',
				    page: 'all',
				    selected: null,
				}	            
	        },
			customize: (xlsx) => {
	            var sheet = xlsx.xl.worksheets['sheet1.xml'];
	            $('row c[r^="A2"]', sheet).attr( 's', '2' );
			}	        				                
        }
        ],		
		"fnInfoCallback": () => {
			let data = $('#coa').val();
			if(typeof data == undefined || data == null) return 'Akun : belum dipilih';

			let coaInfo = $('#coa').select2('data');
			let coaInfo2 = $('#coasampai').select2('data');			

			if(coaInfo[0].text != coaInfo2[0].text) {
				return coaInfo[0].text + ' s/d ' + coaInfo2[0].text;
			} else {
				return coaInfo[0].text;
			}
        },		
	    "drawCallback": () => {
		      let total = tabel.data().count();

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

		      saldo = 0;
		      debit = 0;
		      kredit = 0;		  			  	  
		}                    
	});

	tabel.buttons().container().appendTo( '#btnExpor' );

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
	});

	$("#dtgldari").click(() => {
		$("#tgldari").focus();
	});

	$("#dtglsampai").click(() => {
		$("#tglsampai").focus();
	});

	$("#brefresh").click(() => {
		_reloaddatatable();
	});	

	$("#bprint").click(() => {
	});	

	$('#table').on('dblclick','tr',function(e){
		e.preventDefault();
		e.stopPropagation();
		tabel.rows(this).select();

        const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();
        const nomor = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),2).data();
		const pagelink = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),10).data();
		const pagecaption = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),11).data();		

        if(typeof id=='undefined' || id==0) return;

        parent.window.openTabLedger(nomor, pagelink.replace('/','-'), pagecaption);

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
	});

	$("#submitfilter").click(() => {
		if ($("#coa").val()=='' || $("#coa").val()==null) {
			return;
		}
		if ($("#coasampai").val()=='' || $("#coasampai").val()==null) {
			let coa = $("<option selected='selected'></option>").val($("#coa").val()).text($("#coa option:selected").text());
			$("#coasampai").append(coa).trigger('change');
		}
		if (window.matchMedia('screen and (max-width: 768px)').matches) {
			$("#table").removeClass("w-75");
			$("#table").addClass("w-100");
			$("#fDataTable").addClass("d-none");    
		}  
		$('#table').DataTable().ajax.reload();  		
	});

	var _reloaddatatable = () => {
		clearFilter();
		$('#table').DataTable().ajax.reload();  
	}  

})