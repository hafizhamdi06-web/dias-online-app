var tabel = null;

$(function(){

    $('#ukid').select2({
        "allowClear": true,
        "theme":"bootstrap4",
        "dropdownParent": parent.window.$('#modal'),
        "ajax": {
          "url": base_url+"Select_Master/view_karyawan",
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

    $('#ucabang').select2({
        "allowClear": true,
        "theme":"bootstrap4",
        "dropdownParent": parent.window.$('#modal'),
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

    $('#ucabang').on('select2:select', function(e){
      $('#unomor').val(e.params.data.nomor);
    });

    $('#ucabang').on('select2:clear', function(e){
      $('#unomor').val('');
    });

    $('#usalin').select2({
        "allowClear": true,
        "theme":"bootstrap4",
        "dropdownParent": parent.window.$('#modal'),
        "ajax": {
          "url": base_url+"Select_Master/view_user",
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

    $('#btampilkan').click(function(){
      var idSalin = $('#usalin').val();
      if(idSalin==null || idSalin==''){
        toastr.warning("Pilih data user terlebih dahulu");
        return;
      }
      _getDataAksesMenu(idSalin);
      _getDataAksesReport(idSalin);
      _getDataAksesGudang(idSalin);
      _getDataAksesRole(idSalin);
    });

    _getDataAksesMenu();
    _getDataAksesReport();
    _getDataAksesGudang();
    _getDataAksesRole();

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

      var $row = $(this).closest('tr');
      if($row.data('mparent')==0){
        var mid = $row.data('mid');
        $("#tmenu tbody tr").filter(function(){
          return $(this).data('mparent')==mid;
        }).each(function(){
          $(this).find('.view,.add,.edit,.delete,.print').prop('checked', isChecked);
        });
      }
    })

    $(document).on("click","#pilihsemuagudang", function(e){
      var isChecked = $(this).prop("checked");
      $("#tgudang tbody input[name='isgudang']").prop("checked", isChecked);
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
            ukid = $("#ukid").val(),
            ucabang = $("#ucabang").val(),
            unomor = $("#unomor").val(),
            pass = $("#pwd").val();

      var status = 1;

      if($("#aktif").prop('checked')==false) status=0;  

      var detilmenu = [],
      	  detilreport = [],
      	  ucabangpilih = [],
      	  rolepilih = [];

      $("#tgudang input[name^='isgudang']:checked").each(function(){
      	  ucabangpilih.push($(this).val());
      });
      ucabangpilih = ucabangpilih.join(',');

      $("#trole input[name^='isrole']:checked").each(function(){
      	  rolepilih.push($(this).val());
      });

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
      rolepilih = JSON.stringify(rolepilih);

      var rey = new FormData();
      rey.set('id',id);
      rey.set('kode',kode);
      rey.set('nama',nama);
      rey.set('namalengkap',namalengkap);
      rey.set('ukid',ukid);
      rey.set('ucabang',ucabang);
      rey.set('unomor',unomor);
      rey.set('status',status);
      rey.set('pass',pass);
      rey.set('ucabangpilih',ucabangpilih);
      rey.set('rolepilih',rolepilih);
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

function _getDataAksesMenu(sourceId){
	 var idUser = sourceId || $("#id").val();
	 $.ajax({
      "url"    : base_url+"Admin_User/getaksesmenu",
      "type"   : "POST",
      "dataType" : "json",
      "data" : "id="+idUser,
      "cache"  : false,
      "success" : function(result) {
          $('#tmenu tbody').empty();
          var rows = 0;
          var lastgroup = null;
          $.each(result.data, function() {
          	if(result.data[rows]['mgroup'] !== lastgroup){
          		lastgroup = result.data[rows]['mgroup'];
          		$('#tmenu tbody').append("<tr class=\"bg-light\"><td class=\"border-0 py-1 px-1\"></td><td class=\"border-0 py-1\" colspan=\"6\"><strong>"+lastgroup+"</strong></td></tr>");
          	}
  		      var newrow = " <tr data-mid=\""+result.data[rows]['mid']+"\" data-mparent=\""+result.data[rows]['mparent']+"\">";
  		    	newrow += "<td class=\"border-0 py-1 px-1\"><input type=\"hidden\" name=\"idmenu\" value=\""+result.data[rows]['mid']+"\"><i class=\"fas fa-caret-right\"></i></td>";
  		    	if(result.data[rows]['mparent']!=0){
		          newrow += "<td class=\"border-0 py-1\"><span style='margin-left:20px;'>"+result.data[rows]['mnama']+"</span></td>";
  		    	}else{
		          newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['mnama']+"</td>";
  		    	}

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

function _getDataAksesReport(sourceId){
	 var idUser = sourceId || $("#id").val();
	 $.ajax({
      "url"    : base_url+"Admin_User/getaksesreport",
      "type"   : "POST",
      "dataType" : "json",
      "data" : "id="+idUser,
      "cache"  : false,
      "success" : function(result) {
          $('#treport tbody').empty();
          var rows = 0;
          var lastgroup = null;
          $.each(result.data, function() {
          	if(result.data[rows]['mgroup'] !== lastgroup){
          		lastgroup = result.data[rows]['mgroup'];
          		$('#treport tbody').append("<tr class=\"bg-light\"><td class=\"border-0 py-1 px-1\"></td><td class=\"border-0 py-1\" colspan=\"2\"><strong>"+lastgroup+"</strong></td></tr>");
          	}
		        var newrow = " <tr>";
		    	  newrow += "<td class=\"border-0 py-1 px-1\"><input type=\"hidden\" name=\"idreport\" value=\""+result.data[rows]['mid']+"\"><i class=\"fas fa-caret-right\"></i></td>";
		    	  if(result.data[rows]['mparent']!=0){
		          newrow += "<td class=\"border-0 py-1\"><span style='margin-left:20px;'>"+result.data[rows]['mnama']+"</span></td>";
		    	  }else{
		          newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['mnama']+"</td>";
		    	  }

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

function _getDataAksesGudang(sourceId){
	 var idUser = sourceId || $("#id").val();
	 $.ajax({
      "url"    : base_url+"Admin_User/getaksesgudang",
      "type"   : "POST",
      "dataType" : "json",
      "data" : "id="+idUser,
      "cache"  : false,
      "success" : function(result) {
          $('#tgudang tbody').empty();
          var rows = 0;
          var allChecked = result.data.length > 0 && result.data.every(function(item){ return item['dipilih']==1; });
          $('#tgudang tbody').append("<tr class=\"bg-light\"><td class=\"border-0 py-1 px-1\"></td><td class=\"border-0 py-1\" colspan=\"2\"><strong>Pilih Semua</strong></td><td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" id=\"pilihsemuagudang\""+(allChecked ? " checked" : "")+"></td></tr>");
          $.each(result.data, function() {
	        var newrow = " <tr>";
	    	  newrow += "<td class=\"border-0 py-1 px-1\"><i class=\"fas fa-caret-right\"></i></td>";
	        newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['gkode']+"</td>";
	        newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['gnama']+"</td>";

    		   	if(result.data[rows]['dipilih'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isgudang\" value=\""+result.data[rows]['gid']+"\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isgudang\" value=\""+result.data[rows]['gid']+"\"></td>";
    		   	}

  	        newrow += "</tr>";
    		    $('#tgudang tbody').append(newrow);
            rows++;
          });

          return;

        }
  })

}

function _getDataAksesRole(sourceId){
	 var idUser = sourceId || $("#id").val();
	 $.ajax({
      "url"    : base_url+"Admin_User/getaksesrole",
      "type"   : "POST",
      "dataType" : "json",
      "data" : "id="+idUser,
      "cache"  : false,
      "success" : function(result) {
          $('#trole tbody').empty();
          var rows = 0;
          $.each(result.data, function() {
	        var newrow = " <tr>";
	    	  newrow += "<td class=\"border-0 py-1 px-1\"><i class=\"fas fa-caret-right\"></i></td>";
	        newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['nama']+"</td>";

    		   	if(result.data[rows]['dipilih'] == 1){
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isrole\" value=\""+result.data[rows]['id']+"\" checked></td>";
    		   	}else{
    		   		newrow += "<td class=\"border-0 py-1 text-center\"><input type=\"checkbox\" name=\"isrole\" value=\""+result.data[rows]['id']+"\"></td>";
    		   	}

  	        newrow += "</tr>";
    		    $('#trole tbody').append(newrow);
            rows++;
          });

          return;

        }
  })

}