$.fn.dataTable.ext.errMode = 'none';      

var tabelkontak = null,
    katId = null;

$('#contact-table').on('dblclick','tr',function(e){
  $('#contact-table').DataTable().rows(this).select();              
  restable();
});

$("#bpilihkontak").click(restable);

var _kontakdatatable = function(){
  katId = $('#idkatkontak').val();
  tabelkontak= $('#contact-table').DataTable({
    "destroy":true,
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": true,
    "ordering": true,
    "pagingType":"simple",        
    "order": [[ 2, 'asc' ]],      
    "select":true,      
    "dom": '<"#sTable"f><"top"p>tr<"clear">',        
    "ajax": {
        "url":base_url+"Datatable_Master/view_table_item_pos/"+katId,
        "type":"post",
        "beforeSend": function(){
          $(".loader-wrap").addClass("d-none");            
        }                   
    },
    "deferRender": true,
    "bInfo":false,
    "aLengthMenu": [[20, 50, 100],[20, 50, 100]],
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
          { "data": "kode" },
          { "data": "nama" },
          { "data": "hargajual" },
          { "data": "stok" },  
          { "data": "tipeitem" },          
    ], 
	"columnDefs": [
	      {
	        "render": function (data, type, row) {
	             data = commaSeparateNumber(data);
	             data = "<span style='float:right' class='mx-2'>"+data+"</span>";
	             return data;
	        },
	        "targets": [4,5]
	      }
	],
    "drawCallback": function(settings, json) {
      var total = tabelkontak.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");
        tabelkontak.rows(0).select();
      }else{
        $(".modal-body").addClass("noresultfound");
      }
      $('#sTable input[type="search"]').focus();
      $('#contact-table').removeClass("d-none");
    }
  });
}

var _moveSelectionItem = (direction) => {
  var allIdx = tabelkontak.rows().indexes().toArray();
  if(allIdx.length===0) return;

  var selected = tabelkontak.rows({selected:true}).indexes().toArray();
  var pos = selected.length ? allIdx.indexOf(selected[0]) : -1;
  var newPos = pos + direction;
  if(newPos < 0) newPos = 0;
  if(newPos > allIdx.length-1) newPos = allIdx.length-1;

  tabelkontak.rows().deselect();
  tabelkontak.row(allIdx[newPos]).select();

  var node = tabelkontak.row(allIdx[newPos]).node();
  if(node) node.scrollIntoView({block:'nearest'});
}

$('.main-modal-body').off('keydown.caribarangpos').on('keydown.caribarangpos', '#sTable input[type="search"], #contact-table', function(e){
  if(e.keyCode==13){
    e.preventDefault();
    restable();
  } else if(e.keyCode==40){
    e.preventDefault();
    _moveSelectionItem(1);
  } else if(e.keyCode==38){
    e.preventDefault();
    _moveSelectionItem(-1);
  }
});

var _lstkategorikontak = function(){
  $.ajax({ 
    "url"    : base_url+"Select_Master/view_kategori_itemkelompok2020", 
    "type"   : "POST", 
    "dataType" : "json", 
    error: function(){
      console.log('error ambil kategori item...');
      return;
    },
    success: function(data) {
      var list = ` <a href="javascript:void(0)" class="dropdown-item text-sm" onClick="_pilihkategorikontak('All');">
                    <i id="cAll" class="cTrue fas fa-check mr-2 d-none"></i>Semua
                  </a> `;
      $('.list-kategori').append(list);

      $.each(data, function(index,element) {
        list = ` <a href="javascript:void(0)" class="dropdown-item text-sm" onClick="_pilihkategorikontak('`+element.id+`');">
                      <i id="c`+element.id+`" class="cTrue fas fa-check mr-2 d-none"></i> `+element.text+
               ` </a> `;
        $('.list-kategori').append(list);
      });
  } 
  });
  $("#nav-kkontak").removeClass("d-none");  
}

function _pilihkategorikontak(id){
  if(id!=='All'){
    $('#idkatkontak').val(id);
  }else{
    $('#idkatkontak').val('');          
  }

  $('.cTrue').addClass('d-none');
  $('#c'+id).removeClass('d-none');  
  _kontakdatatable();
}       

function restable(){
const id = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),0).data(),
        stok = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),5).data(),
        tipeitem = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),6).data(),
      trigger = $('#modaltrigger').val(),
      coltrigger = $('#coltrigger').val();

      if (id==null || typeof id=='undefined') return;

      if (tipeitem ==0 && stok <= 0)
      {
          alert('Stok ' + stok +' Produk tidak bisa dipilih');
          return ;
      }
  
    
    $("#"+trigger).contents().find("#txtidbarang").val(id);   
    
    $('#modal').modal('hide');                                 
  return;        
}