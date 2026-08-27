$(document).on('click', '#profilakun', function(e){
  e.preventDefault();

  $.ajax({
    "url"    : base_url+"Modal/profil_akun",
    "type"   : "POST",
    "dataType" : "html",
    "beforeSend": function(){
      $(".loader-wrap").removeClass("d-none");
      $(".modal").modal("show");
      $(".modal-title").html("Profil Akun");
      $("#modaltrigger").val("");
    },
    "error": function(xhr, status, error){
      $(".loader-wrap").addClass("d-none");
      console.log('error menampilkan profil akun...');
      return;
    },
    "success": function(result) {
      $(".main-modal-body").html(result);
      $(".loader-wrap").addClass("d-none");
    }
  });
});
