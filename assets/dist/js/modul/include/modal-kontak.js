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
        "url":base_url+"Datatable_Master/view_table_kontak/"+katId,
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
          { "data": "tipe" },
          { "data": "alamat" },          
    ],
    "drawCallback": function(settings, json) {
      var total = tabelkontak.data().count();

      if(total>0){
        $("#modalfilter .modal-body").removeClass("noresultfound");                                   
      }else{
        $("#modalfilter .modal-body").addClass("noresultfound");                                   
      }
      $('#modalfilter input').focus();                                     
      $('#contact-table').removeClass("d-none"); 
    }        
  });    
}

var _lstkategorikontak = function(){
  $.ajax({ 
    "url"    : base_url+"Select_Master/view_kategori_kontak", 
    "type"   : "POST", 
    "dataType" : "json", 
    error: function(){
      console.log('error ambil kategori kontak...');
      return;
    },
    success: function(data) {
      var list = ` <a href="javascript:void(0)" class="dropdown-item text-sm" onClick="_pilihkategorikontak('All');">
                    <i id="cAll" class="cTrue fas fa-check mr-2 d-none"></i>Semua
                  </a> `;
      $('#modalfilter .list-kategori').append(list);

      $.each(data, function(index,element) {
        list = ` <a href="javascript:void(0)" class="dropdown-item text-sm" onClick="_pilihkategorikontak('`+element.id+`');">
                      <i id="c`+element.id+`" class="cTrue fas fa-check mr-2 d-none"></i> `+element.text+
               ` </a> `;
        $('#modalfilter .list-kategori').append(list);
      });
  } 
  });
  $("#nav-fkontak").removeClass("d-none");  
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
      kode = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),2).data(),
      nama = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),3).data();

  $("#modal").contents().find("#idkontak").val(id); 
  $("#modal").contents().find("#kontak").val(kode);                     
  $("#modal").contents().find("#namakontak").html(nama);                                           
  $('#modalfilter').modal('hide');        
  $("#modal").contents().find("#kontak").focus();  
  return;        
}