/* ========================================================================================== */
/* File Name : table-verifikasi-permintaan-barang.js
/* ========================================================================================== */

import { Component_Inputmask_Date } from '../../component.js';
import { Component_Scrollbars } from '../../component.js';

var tabel = null;

$(function() {

	$.fn.dataTable.ext.errMode = 'none';

	Component_Scrollbars('.tab-wrap','scroll','scroll');
	Component_Inputmask_Date('.datepicker');

	if(!parent.window.$(".loader-wrap").hasClass("d-none")){
		parent.window.$(".loader-wrap").addClass("d-none");
	}

	this.addEventListener('contextmenu', function(e){
		e.preventDefault();
	});

	$('#cabang').select2({
	    "allowClear": true,
	    "theme":"bootstrap4",
	    "placeholder": "Semua",
	    "ajax": {
	      "url": base_url+"Select_Master/view_gudang",
	      "type": "post",
	      "dataType": "json",
	      "delay": 800,
	      "data": (params) => {
	          return {
	            search: params.term
	          }
	      },
	      "processResults": (data, page) => {
	          return {
	            results: data
	          }
	      },
	    }
	});

	$('#status').select2({
	    "theme":"bootstrap4",
	    "minimumResultsForSearch": -1
	});

	var clearFilter = () => {
		$('#idkontak,#kontak').val('');
		$('#tujuan,#tujuannama').val('');
		$('#cabang').val('').trigger('change');
		$('#status').val('').trigger('change');
		$('#tgldari').datepicker('setDate','01-mm-yy');
		$('#tglsampai').datepicker('setDate','dd-mm-yy');
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
	    "url":base_url+"Datatable_Transaksi_Full/view_verifikasi_permintaan_barang",
	    "type":"post",
        "data": function(data){
          data.idkontak = $('#idkontak').val();
          data.dari = $('#tgldari').val();
          data.sampai = $('#tglsampai').val();
          data.cabang = $('#cabang').val();
          data.tujuan = $('#tujuan').val();
          data.status = $('#status').val();
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
	      { "data": "karyawan" },
	      { "data": "keterangan" },
	      { "data": "tujuan" },
	      {
	      orderable:      false,
	      data:           null,
	      defaultContent: "<a href=\"javascript:void(0)\" class=\"btn-verifikasi\"><i class=\"fas fa-check-circle text-primary\"></i> Verifikasi</a>"
	      },
	      { "data": "status" },
	      { "data": "userverifikasi" },
	      { "data": "jenis" },
	      { "data": "gudangtujuan" },
	      { "data": "catatanverifikasi" },
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

      if($(".table").hasClass("d-none")){
        $(".table").removeClass("d-none");
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

	$("#bedit").click(function() {
		const id = $('#table').DataTable().cell($('#table').DataTable().rows({selected:true}),0).data();

		if(typeof id=='undefined') return;

		parent.window.$('.loader-wrap').removeClass('d-none');
		location.href=base_url+"page/pmb/?id="+id;
	});

	$('#table').on('dblclick','tr',function(e){
		e.preventDefault();
		e.stopPropagation();
		tabel.rows(this).select();
		$('#bedit').click();
	})

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

	$("#brefresh").click(function() {
		_reloaddatatable();
	});

	$("#bfilterkontak").click(function() {
		if($(this).attr('role')) {
		  $.ajax({
		    "url"    : base_url+"Modal/cari_kontak",
		    "type"   : "POST",
		    "dataType" : "html",
		    "beforeSend": function(){
		      parent.window.$(".loader-wrap").removeClass("d-none");
		      parent.window.$(".modal").modal("show");
		      parent.window.$(".modal-title").html("Cari Karyawan");
		      parent.window.$("#modaltrigger").val("iframe-page-pmbverif");
		      parent.window.$('#coltrigger').val('vendor');
		    },
		    "error": function(){
		      console.log('error menampilkan modal cari kontak...');
		      parent.window.$(".loader-wrap").addClass("d-none");
		      return;
		    },
		    "success": function(result) {
		      parent.window.$(".main-modal-body").html(result);
		      parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
		      parent.window._lstkategorikontak();
		      parent.window._pilihkategorikontak('4');

		      setTimeout(function (){
		           parent.window.$('#modal input').focus();
		      }, 500);

		      return;
		    }
		  });
		}
	});

	$("#carijenis").click(function() {
		if($(this).attr('role')) {
		  $.ajax({
		    "url"    : base_url+"Modal/cari_tujuan",
		    "type"   : "POST",
		    "dataType" : "html",
		    "beforeSend": function(){
		      parent.window.$(".loader-wrap").removeClass("d-none");
		      parent.window.$(".modal").modal("show");
		      parent.window.$(".modal-title").html("Cari Jenis");
		      parent.window.$("#modaltrigger").val("iframe-page-pmbverif");
		    },
		    "error": function(){
		      console.log('error menampilkan modal cari tujuan...');
		      parent.window.$(".loader-wrap").addClass("d-none");
		      return;
		    },
		    "success": function(result) {
		      parent.window.$(".main-modal-body").html(result);
		      parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');
		      setTimeout(function (){
		           parent.window.$('#modal input').focus();
		      }, 500);
		      return;
		    }
		  });
		}
	});

	$('#table').on('click', '.btn-verifikasi', function() {
		var rowdata = tabel.row($(this).closest('tr')).data();
		var id = rowdata ? rowdata.id : null;

		if(id=="" || id==null) return;

		$.ajax({
		    "url"    : base_url+"Modal/verifikasi_pmb",
		    "type"   : "POST",
		    "dataType" : "html",
		    "beforeSend": function(){
		      parent.window.$(".loader-wrap").removeClass("d-none");
		      parent.window.$(".modal").modal("show");
		      parent.window.$(".modal-title").html("Verifikasi");
		      parent.window.$("#modaltrigger").val("iframe-page-pmbverif");
		    },
		    "error": function(){
		      console.log('error menampilkan modal verifikasi...');
		      parent.window.$(".loader-wrap").addClass("d-none");
		      return;
		    },
		    "success": function(result) {
		      parent.window.$(".main-modal-body").html(result);
		      parent.window._getVerifikasiData(id);
		      setTimeout(function (){
		           parent.window.$('#modal input').focus();
		      }, 500);
		      return;
		    }
		});
	});

	$("#submitfilter").click(function() {
	  $('#table').DataTable().ajax.reload();
	  if (window.matchMedia('screen and (max-width: 768px)').matches) {
	    $("#table").removeClass("w-75");
	    $("#table").addClass("w-100");
	    $("#fDataTable").addClass("d-none");
	  }
	});

	$('#kontak,#tujuannama').keydown(function(e){
	  if(e.keyCode==13) $('#submitfilter').click();
	});

	window._reloaddatatable = _reloaddatatable;

	var _reloaddatatable = () => {
	    clearFilter();
		$('#table').DataTable().ajax.reload();
	}

})
