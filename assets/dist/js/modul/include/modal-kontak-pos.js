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
      "min-height": "35px",
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
        "url": base_url + "Datatable_Master/view_table_kontak_pos2/"+katId,
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
          { "data": "kode"  },
          { "data": "nama" },
          { "data": "tipe" },
          { "data": "cabang" },
          { "data": "alamat" },          
    ],
    "drawCallback": function(settings, json) {
      var total = tabelkontak.data().count();

      if(total>0){
        $(".modal-body").removeClass("noresultfound");                                   
      }else{
        $(".modal-body").addClass("noresultfound");                                   
      }
      $('#modal input').focus();                                     
      $('#contact-table').removeClass("d-none"); 
    }        
  });    
}

var _lstkategorikontak = function(){
 
}

var _setjenis = function(){
  $.ajax({ 
    "url"    : base_url+"Select_Master/view_kategori_kontak", 
    "type"   : "POST", 
    "dataType" : "json", 
    error: function(){
      console.log('error ambil kategori kontak...');
      return;
    },
    success: function(data) { 
        
             var list = `<option value=''>Semua Jenis Pasien</option>  `;
             $('#jenispasien').append(list); 
             
               $.each(data, function(index,element) {
                list = `<option value=`+element.id+`>`+element.text+`</option>  `;
                 $('#jenispasien').append(list);
              });
       
         
  } 
  }); 
  _setcabang();
}


var _setcabang = function(){
  $.ajax({ 
    "url"    : base_url+"Select_Master/view_gudang", 
    "type"   : "POST", 
    "dataType" : "json", 
    error: function(){
      console.log('error ambil cabang...');
      return;
    },
    success: function(data) { 
        
             var list = `<option value=''>Semua Cabang</option>  `;
             $('#cabang').append(list); 
             
               $.each(data, function(index,element) {
                list = `<option value=`+element.id+`>`+element.text+`</option>  `;
                 $('#cabang').append(list);
              }); 
              
             
        
         
  } 
  }); 
}
 
 
  $('#jenispasien').on('change',function(){ 
      _pilihkategorikontak( $('#jenispasien').val() ) ; 
  });
  
  
  
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
      nama = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),3).data(),
      alamat = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),5).data(),     
      trigger = $('#modaltrigger').val(),
      coltrigger = $('#coltrigger').val();
  
  if(coltrigger=='vendor'){
    $("#"+trigger).contents().find("#idkontak").val(id); 
    $("#"+trigger).contents().find("#kontak").val(kode);                     
    $("#"+trigger).contents().find("#namakontak").html(nama);                                           
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#kontak").focus();
  }else if(coltrigger=='kontak'){
    $("#"+trigger).contents().find("#idkontak").val(id); 
    $("#"+trigger).contents().find("#kontak").val(kode);                     
    $("#"+trigger).contents().find("#namakontak").html(nama);                                           
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#kontak").focus();
  }else if(coltrigger=='kontak2'){
    $("#"+trigger).contents().find("#idkontak2").val(id); 
    $("#"+trigger).contents().find("#kontak2").val(kode);                     
    $("#"+trigger).contents().find("#namakontak2").html(nama);                                           
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#kontak2").focus();          
  }else if(coltrigger=='customer'){
    $("#"+trigger).contents().find("#idkontak").val(id); 
    $("#"+trigger).contents().find("#kontak").val(kode);                     
    $("#"+trigger).contents().find("#namakontak").html(nama);                
    $("#"+trigger).contents().find("#alamat").val(alamat);                                                          
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#kontak").focus();          
  }else if(coltrigger=='bagbeli'){
    $("#"+trigger).contents().find("#idbagbeli").val(id); 
    $("#"+trigger).contents().find("#bagbeli").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#bagbeli").focus();
  }else if(coltrigger=='salesman'){
    $("#"+trigger).contents().find("#idsalesman").val(id); 
    $("#"+trigger).contents().find("#salesman").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#salesman").focus();
  }else if(coltrigger=='baggudang'){
    $("#"+trigger).contents().find("#idbaggudang").val(id); 
    $("#"+trigger).contents().find("#baggudang").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#baggudang").focus();          
  }else if(coltrigger=='karyawan'){
    $("#"+trigger).contents().find("#idkaryawan").val(id); 
    $("#"+trigger).contents().find("#karyawan").val(nama);                     
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#karyawan").focus();          
  }else if(coltrigger=='pasien'){
    $("#"+trigger).contents().find("#idkontak").val(id); 
    $("#"+trigger).contents().find("#kontak").val(nama);                     
    $("#"+trigger).contents().find("#namakontak").html(nama);                                           
    $('#modal').modal('hide');        
    $("#"+trigger).contents().find("#kontak").focus();
  }                    
  return;        
}