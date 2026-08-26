var tabel = null;

$(function(){

    _getDataAksesMenu();
    _getDataAksesReport();	

    $("#submit").click(function(){
      if (_IsValid()===0) return;
      _saveData();
    });

    $(document).on("click","#tmenu input[name^='isview']", function(e){
      var _index = $(this).index('.view');
      var isChecked = $(this).prop("checked");
      $(".add").eq(_index).prop("checked", isChecked);
      $(".edit").eq(_index).prop("checked", isChecked);      
      $(".delete").eq(_index).prop("checked", isChecked);            
      $(".print").eq(_index).prop("checked", isChecked);            
    })

    var _IsValid = (function(){
        if ($('#kode').val()==''){
          $('#kode').attr('data-title','Kode / Nik user harus diisi !');      
          $('#kode').tooltip('show');
          $('#kode').focus();
          return 0;
        }
        if ($('#nama').val()==''){
          $('#nama').attr('data-title','Nama user harus diisi !');      
          $('#nama').tooltip('show');
          $('#nama').focus();
          return 0;
        }
        return 1;
    });

    var _saveData = (function(){
      const id = $("#id").val(),
            kode = $("#kode").val(),
            nama = $("#nama").val(),
            namalengkap = $("#namalengkap").val(),
            pass = $("#pwd").val();

      var status = 1;

      if($("#aktif").prop('checked')==false) status=0;  

      var detilmenu = [],
      	  detilreport = [];

      $("input[name^='idmenu']").each(function(index,element){
      	  var isview = 0, isadd = 0, isedit = 0, isdelete = 0, isprint = 0;  
      	  if($("input[name^='isview']").eq(index).prop('checked')==true) isview=1;
      	  if($("input[name^='isadd']").eq(index).prop('checked')==true) isadd=1;
      	  if($("input[name^='isedit']").eq(index).prop('checked')==true) isedit=1;
      	  if($("input[name^='isdelete']").eq(index).prop('checked')==true) isdelete=1;
      	  if($("input[name^='isprint']").eq(index).prop('checked')==true) isprint=1;

          detilmenu.push({
             idmenu:this.value,
             buka:isview,
             tambah:isadd,
             edit:isedit,
             delete:isdelete,
             print:isprint               
          })
      }); 

      $("input[name^='idreport']").each(function(index,element){
      	  var isreport = 0;  
    	  if($("input[name^='isreport']").eq(index).prop('checked')==true) isreport=1;

          detilreport.push({
                   idmenu:this.value,
                   buka:isreport               
                 });
      });   

      detilmenu = JSON.stringify(detilmenu);
      detilreport = JSON.stringify(detilreport);  

      var rey = new FormData();  
      rey.set('id',id);
      rey.set('kode',kode);
      rey.set('nama',nama);
      rey.set('namalengkap',namalengkap);  
      rey.set('status',status);
      rey.set('pass',pass);  
      rey.set('detilmenu',detilmenu);    
      rey.set('detilreport',detilreport);      

      $.ajax({ 
        "url"    : base_url+"Admin_User/savedata", 
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
        "success": async function(result) {
          if(result=='sukses'){
            $('#modal').modal('hide');                
            toastr.success("Data user berhasil disimpan");                  
          } else {        
            toastr.error(result);                          
            return;
          }
          await sidebarmenu_content();
          $(".loader-wrap").addClass("d-none");                                                          
        } 
      });
    });

    var sidebarmenu_content = () => {
      $.ajax({ 
        "url"    : base_url+"Dasbor/refreshsidebarmenu", 
        "type"   : "POST", 
        "dataType" : "html",
        "cache"    : false,
        "error" : (xhr) => {
          console.error(xhr.responseText);
          return;
        },
        "success": (result) => {
          $('aside').fadeOut(250, function() {
            $(this).append(result).fadeIn(250);
          });
          return;                   
        } 
      })        
    };

})

function _getDataAksesMenu(){
	 $.ajax({ 
      "url"    : base_url+"Admin_User/getaksesmenu",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+$("#id").val(),
      "cache"  : false,
      "success" : function(result) {
          var rows = 0;
          $.each(result.data, function() {                          
  		      var newrow = " <tr>";
  		    	newrow += "<td class=\"border-0 py-1 px-1\"><input type=\"hidden\" name=\"idmenu\" value=\""+result.data[rows]['mid']+"\"><i class=\"fas fa-caret-right\"></i></td>";
		        newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['mnama']+"</td>";

    		   	if(result.data[rows]['auapprove'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isview[]\" class=\"view\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isview[]\" class=\"view\"></td>";
    		   	}
    		   	if(result.data[rows]['auadd'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isadd[]\" class=\"add\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isadd[]\" class=\"add\"></td>";
    		   	}
    		   	if(result.data[rows]['auedit'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isedit[]\" class=\"edit\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isedit[]\" class=\"edit\"></td>";
    		   	}
    		   	if(result.data[rows]['audell'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isdelete[]\" class=\"delete\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isdelete[]\" class=\"delete\"></td>";
    		   	}
    		   	if(result.data[rows]['auprint'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isprint[]\" class=\"print\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isprint[]\" class=\"print\"></td>";
    		   	}

  	        newrow += "</tr>";
    		    $('#tmenu tbody').append(newrow);          	
            rows++;
          });

          $('.loader-wrap').addClass('d-none');          	          
          return;
        }
  })

}

function _getDataAksesReport(){
	 $.ajax({ 
      "url"    : base_url+"Admin_User/getaksesreport",       
      "type"   : "POST", 
      "dataType" : "json", 
      "data" : "id="+$("#id").val(),
      "cache"  : false,
      "success" : function(result) {
          var rows = 0;
          $.each(result.data, function() {                          
		        var newrow = " <tr>";
		    	  newrow += "<td class=\"border-0 py-1 px-1\"><input type=\"hidden\" name=\"idreport\" value=\""+result.data[rows]['mid']+"\"><i class=\"fas fa-caret-right\"></i></td>";
		        newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['mnama']+"</td>";

    		   	if(result.data[rows]['auapprove'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isreport\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isreport\"></td>";
    		   	}

  	        newrow += "</tr>";
    		    $('#treport tbody').append(newrow);          	
            rows++;
          });

          return;

        }
  })

}