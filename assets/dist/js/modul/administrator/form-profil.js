$('#ukid').select2({
    "theme":"bootstrap4",
    "dropdownParent": $('#modal')
});

$('#ucabang').select2({
    "theme":"bootstrap4",
    "dropdownParent": $('#modal')
});

_getProfilAkun();
_getGudangPilihanProfil();

function _getProfilAkun(){
  $.ajax({
    "url"    : base_url+"Admin_User/getprofilakun",
    "type"   : "POST",
    "dataType" : "json",
    "cache"  : false,
    "beforeSend" : function(){
      $(".loader-wrap").removeClass("d-none");
    },
    "error"  : function(xhr,status,error){
      toastr.error("Perbaiki kesalahan ini : "+xhr.status+" "+error);
      console.error(xhr.responseText);
      $('.loader-wrap').addClass('d-none');
      return;
    },
    "success" : function(result) {
      var row = result.data[0];

      $('#id').val(row['uid']);
      $('#kode').val(row['ukode']);
      $('#nama').val(row['unama']);
      $('#namalengkap').val(row['unamalengkap']);
      $('#aktif').prop('checked', row['uactive']==1);

      if(row['ukid']!=null && row['ukid']!=''){
        var _karyawan = $("<option selected='selected'></option>").val(row['ukid']).text(row['namakaryawan']);
        $('#ukid').append(_karyawan).trigger('change');
      }

      if(row['ucabang']!=null && row['ucabang']!=''){
        var _cabang = $("<option selected='selected'></option>").val(row['ucabang']).text(row['namacabang']);
        $('#ucabang').append(_cabang).trigger('change');
      }

      $('.loader-wrap').addClass('d-none');
      return;
    }
  })
}

function _getGudangPilihanProfil(){
  $.ajax({
    "url"    : base_url+"Admin_User/getgudangpilihanprofil",
    "type"   : "POST",
    "dataType" : "json",
    "cache"  : false,
    "success" : function(result) {
      $('#tgudangprofil tbody').empty();
      var rows = 0;
      $.each(result.data, function() {
        var isaktif = result.data[rows]['aktif']==1;
        var newrow = " <tr class=\"gudang-pilihan-row\" style=\"cursor:pointer;\" data-gid=\""+result.data[rows]['gid']+"\""+(isaktif ? " data-aktif=\"1\"" : "")+">";
        newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['gkode']+"</td>";
        newrow += "<td class=\"border-0 py-1\">"+result.data[rows]['gnama']+"</td>";
        newrow += "<td class=\"border-0 py-1 text-center\">"+(isaktif ? "<i class=\"fas fa-check-circle text-success\"></i>" : "")+"</td>";
        newrow += "</tr>";
        $('#tgudangprofil tbody').append(newrow);
        rows++;
      });
      return;
    }
  })
}

$(document).on('click', '.gudang-pilihan-row', function(){
  if($(this).data('aktif')==1) return;

  var gid = $(this).data('gid');
  var gnama = $(this).find('td').eq(1).text();

  Swal.fire({
    title: 'Jadikan '+gnama+' sebagai cabang aktif?',
    showDenyButton: false,
    showCancelButton: true,
    confirmButtonText: `Iya`,
  }).then((res) => {
    if (res.isConfirmed) {
      $.ajax({
        "url"    : base_url+"Admin_User/gantiCabang",
        "type"   : "POST",
        "data"   : "gid="+gid,
        "cache"  : false,
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
            toastr.success("Cabang aktif berhasil diubah");
            location.reload();
          } else {
            toastr.error(result);
          }
        }
      });
    }
  })
});
