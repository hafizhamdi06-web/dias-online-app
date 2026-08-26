import { Component_Scrollbars } from '../../component.js';
import { Component_Inputmask_Date } from '../../component.js';

var tabel = null;

$(function() {

  $.fn.dataTable.ext.errMode = 'none';

  Component_Scrollbars('.tab-wrap','scroll','scroll');
  Component_Inputmask_Date('.datepicker');
  
  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
  });

  $(this).on('shown.bs.tooltip', function (e) {
      setTimeout(function () {
        $(e.target).tooltip('hide');
      }, 2000);
  });    

  tabel=$('#aktiva-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "pagingType":"simple",    
    "order": [[ 2, 'asc' ]],
    "select":true,  
    "dom": 'tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Transaksi_Full/view_aktiva_saldo_awal",
        "type":"post",
        "data": function(data){
          data.kode = null;
          data.nama = null;
        }                                                                                                                                       
    },
    "deferRender": true,
    "bInfo":false,    
    "aLengthMenu": [[100000],[100000]],   
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },                 
    "columns": [
          { "data": "id" },
          { "orderable": false,
            "render": function ( data, type, row ) {
                var html ="<input type='checkbox' id='"+row.id+"' name='rw[]' class='chkrow mt-1' value='"+row.id+"'>";
                return html;
                }
          },
          { "data": "kode" },
          { "data": "nama" },
          { "data": "kelompok" },
          { "data": "tglbeli" },
          { "data": "tglpakai" },                    
          { "data": "nilai" },
          { "data": "akumulasi" },
          { "data": "buku" },                                                  
    ],
    "columnDefs": [
          {
            "render": function (data, type, row) {
                 data = commaSeparateNumber(data);
                 data = "<span style='float:right' class='mx-1'>"+data+"</span>";
                 return data;
            },
            "targets": [7,8,9]
          },                              
    ],
    "drawCallback": function(settings) {
      if(!parent.window.$(".loader-wrap").hasClass("d-none")){
        parent.window.$(".loader-wrap").addClass("d-none");
      }
      if($(".table").hasClass("d-none")){   
        $(".table").removeClass("d-none");
      }
      $(".dataTables_processing").removeClass("d-none");                             
    }                    
  });

  $(".dataTables_processing").addClass("d-none");

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

  $('#tgl').datepicker('setDate','dd-mm-yy');       

  $("#dTgl").click(function() {
    if($(this).attr('role')) {
      $("#tgl").focus();
    }
  });

	$("#bBack,#bcancel").click(function() {
		parent.window.$('.loader-wrap').removeClass('d-none');
		location.href=base_url+"page/fa";      
	});

  $('#all-check').click(function(){
    if ($('#all-check').is(":checked"))
    {
       $('.chkrow').prop('checked', true); 
    } else {
       $('.chkrow').prop('checked', false); 
    }
  });  

  $("#carikontak").click(function() {
    if($(this).attr('role')) {
      $.ajax({ 
        "url"    : base_url+"Modal/cari_kontak", 
        "type"   : "POST", 
        "dataType" : "html", 
        "beforeSend": function(){
          parent.window.$(".loader-wrap").removeClass("d-none");                                      
          parent.window.$(".modal").modal("show");                  
          parent.window.$(".modal-title").html("Cari Kontak");
          parent.window.$("#modaltrigger").val("iframe-page-fa");
          parent.window.$('#coltrigger').val('vendor');                
        },
        "error": function(){
          parent.window.$(".loader-wrap").addClass("d-none");                              
          console.log('error menampilkan modal cari kontak...');
          return;
        },
        "success": function(result) {
          parent.window.$(".main-modal-body").html(result);
          parent.window.$('.modal-body').css('min-height','calc(100vh - 30vh)');          
          parent.window._lstkategorikontak();
          parent.window._kontakdatatable();
          setTimeout(function (){
               parent.window.$('#modal input').focus();
          }, 500);
          return;
        } 
      });
    }    
  });

  setTimeout(function () {
      $('#kontak').focus();        
  },300);

  $("#bsave").click(function() {
      if (_IsValid()===0) return;
      _saveData();
  });

  var _clearForm = () => {
      $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
      $(":checkbox").prop("checked", false); 
      $('#namakontak').html('');       
      $('#tgl').datepicker('setDate','dd-mm-yy'); 
      $('#kontak').focus();
  }

  var _IsValid = () => {
      let totalcek = $("input:checkbox[name='rw[]']:checked").length;    

      if($('#idkontak').val()==''){
          $('#kontak').attr('data-title','Kontak harus diisi !');
          $('#kontak').tooltip('show');
          $('#kontak').focus();
          return 0;
      }

      if(totalcek==0 || typeof totalcek==undefined){
          parent.window.Swal.fire({
            title: `Tidak ada aktiva yang dipilih !`,
            showDenyButton: false,
            showCancelButton: false,
            confirmButtonText: `Tutup`,
          });        
          return 0;
      }

      return 1;
  };

  var _saveData = () => {
      let data = [];
      let totalcek = $("input:checkbox[name='rw[]']:checked").length;
      let totalrow = $("input:checkbox[name='rw[]']").length; 
      let checktabel = document.getElementsByName('rw[]');

    if(totalcek>0){
      parent.window.Swal.fire({
        title: `Anda yakin akan memproses transaksi ini ?`,
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

//          alert(data); return;

          var rey = new FormData();
          rey.set('data',data);
          rey.set('nomor', $("#nomor").val());          
          rey.set('kontak', $("#idkontak").val());
          rey.set('tgl', $("#tgl").val());

            $.ajax({ 
                "url"    : base_url+"Fina_Aktiva_Tetap/createsaldoawal", 
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
                    if(result=='sukses'){
                        $('#aktiva-table').DataTable().ajax.reload();  
                        _clearForm();
                        parent.window.$(".loader-wrap").addClass("d-none");
                        parent.window.toastr.success("Transaksi berhasil disimpan");                         
                        return;             
                    } else {
                        parent.window.$(".loader-wrap").addClass("d-none");
                        parent.window.toastr.error("Error : "+result);      
                        console.error(result);      
                        return;                        
                     }
                } 
            })
        }
      })
    }


  };

});