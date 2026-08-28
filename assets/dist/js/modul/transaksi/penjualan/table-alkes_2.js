/* ========================================================================================== */
/* File Name : table-alkes.js
/* Info Lain :
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';

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
		$('#idkontak,#kontak').val('');
	}

	clearFilter();

	if($('#bisaedit').val()==0) $('#bedit').addClass('disabled');
	if($('#bisaprint').val()==0) $('#bprint').addClass('disabled');


	tabel=$('#table').DataTable({
		"processing": true,
		"serverSide": true,
		"lengthChange": false,
		"searching": false,
		"ordering": true,
		"pagingType":"simple",
		"order": [[0, 'desc' ]],
		"select":true,
		"autoWidth": false,
		"dom": '<"top"pi>tr<"clear">',
		"ajax": {
		    "url":base_url+"Datatable_Transaksi_Full/view_alkes",
		    "type":"post",
	        "data": function(data){
	          data.kontak = $('#kontak').val();
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
		      { "data": "noref" },
		      { "data": "uraian" },
		],
		"columnDefs": [
		      { "targets": [0], "visible": false }
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

		  if($(".table").hasClass("d-none")){
		    $(".table").removeClass("d-none");
		  }
		}
	});


	$('#table').on('dblclick','tr',function(e){
	})

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

	$("#bprint").click(() => {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();

        if(typeof id=='undefined') return;

		window.open(`${base_url}Laporan/preview/page-pos/${id}`)
	});

	$("#brefresh").click(function() {
		_reloaddatatable();
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
				  parent.window.$("#modaltrigger").val("iframe-page-alkesData");
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
				  parent.window._setcabang();
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

})
