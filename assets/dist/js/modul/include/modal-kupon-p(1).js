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
        "url":base_url+"Datatable_Master/view_table_voucher2/"+katId,
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
          { "orderable": false,
            "render": function ( data, type, row ) {
                var html ="<input type='checkbox' id='"+row.id+"' name='rw[]' class='mt-1' value='"+row.id+"'>";
                return html;
                }
          },
          { "data": "kode" },       
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

function restable2(){
const id = $('#contact-table').DataTable().cell($('#contact-table').DataTable().rows({selected:true}),0).data(), 
      trigger = $('#modaltrigger').val(),
      coltrigger = $('#coltrigger').val();
       
    
    $("#"+trigger).contents().find("#txtidbarang").val(id);   
    
    $('#modal').modal('hide');                                 
  return;        
}


var _saveData = (function(){
    
    
  const id = $("#idu").val() ;

  var rey = new FormData();  
  rey.set('idu',idu); 

  $.ajax({ 
    "url"    : base_url+"PJ_POS_HP/updatekupon", 
    "type"   : "POST", 
    "data"   : rey,
    "processData": false,
    "contentType": false,
    "cache"    : false,
    "beforeSend" : function(){
      $(".loader-wrap").removeClass("d-none");
    },
    "error": function(xhr, status, error){
      $(".loader-wrap").addClass("d-none");
      toastr.error("Perbaiki masalah ini : "+xhr.status+" "+error);      
      console.log(xhr.responseText);      
      return;
    },
    "success": function(result) {
      $(".loader-wrap").addClass("d-none");        

      if(result=='sukses'){
        $('#modal').modal('hide');                
        toastr.success("Data kupon berhasil disimpan");                  
        return;
      } else {        
        toastr.error(result);                          
        return;
      }
    } 
  });
});



function restable(){
    _saveData();
    
    
}
    
function restable55(){
  var id = [];        
  var totalcek = $("input:checkbox[name='rw[]']:checked").length;
  var totalrow = $("input:checkbox[name='rw[]']").length; 
  var inps = document.getElementsByName('rw[]');
  var trigger = $('#modaltrigger').val();
  
  if(totalcek>0){
    for(var i=0;i<totalrow;i++){
      var inp=inps[i];
        if(inp.checked==true){
          id.push(inp.value);    
        }
    }
  }else{
    id.push($('#transaksi-table').DataTable().cell($('#transaksi-table').DataTable().rows({selected:true}),0).data());         
  }
  
  
  

  //$('#modal').modal('hide');        
  //$("#"+trigger).contents().find("#idreferensi").val(id);    
  //$("#"+trigger).contents().find("#refnomor").focus(); 
  
  
  
  
  
  return;        
}

