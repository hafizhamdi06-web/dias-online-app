/* ========================================================================================== */
/* File Name : table-persetujuan.js (mobile card-list layout)
/* ========================================================================================== */

toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

$(function() {

  this.addEventListener('contextmenu', function(e){
    e.preventDefault();
  });

  var _muatData = () => {
    $.ajax({
      "url"    : base_url+"Persetujuan/listpending",
      "type"   : "POST",
      "dataType" : "json",
      "cache"  : false,
      "beforeSend" : function(){
        $("#loader").removeClass('d-none');
      },
      "error"  : function(xhr,status,error){
        $("#loader").addClass('d-none');
        toastr.error("Gagal mengambil data : "+xhr.status+" "+error);
      },
      "success" : function(result) {
        $("#loader").addClass('d-none');
        _renderList(result.data || []);
      }
    });
  };

  var _renderList = (data) => {
    $("#pm-list").html('');

    if (data.length === 0) {
      $("#pm-empty").removeClass('d-none');
      return;
    }
    $("#pm-empty").addClass('d-none');

    data.forEach(function(row){
      var card =
        '<div class="pm-card" data-id="'+row.id+'">' +
          '<span class="pm-card-jenis">'+row.jenis+'</span>' +
          '<div class="pm-card-keterangan">'+row.keterangan+'</div>' +
          '<div class="pm-card-meta"><i class="fas fa-user"></i> '+row.pemohon+'</div>' +
          '<div class="pm-card-meta"><i class="fas fa-clock"></i> '+row.tanggal+'</div>' +
          '<div class="pm-card-actions">' +
            '<button type="button" class="pm-btn pm-btn-tolak" data-id="'+row.id+'">Tolak</button>' +
            '<button type="button" class="pm-btn pm-btn-setuju" data-id="'+row.id+'">Setuju</button>' +
          '</div>' +
        '</div>';
      $("#pm-list").append(card);
    });
  };

  var _kirimRespon = (url, id, catatan) => {
    $.ajax({
      "url"    : base_url+url,
      "type"   : "POST",
      "data"   : "id="+id+"&catatan="+encodeURIComponent(catatan || ''),
      "cache"  : false,
      "beforeSend" : function(){
        $("#loader").removeClass('d-none');
      },
      "error": function(xhr, status, error){
        $("#loader").addClass('d-none');
        toastr.error("Err: "+xhr.status+", "+error);
      },
      "success": function(result) {
        $("#loader").addClass('d-none');
        if(result=='sukses'){
          toastr.success("Respon berhasil disimpan");
          _muatData();
        } else {
          toastr.error(result);
        }
      }
    });
  };

  $("#pm-list").on('click', '.pm-btn-setuju', function(){
    var id = $(this).data('id');

    Swal.fire({
      title: 'Setujui permintaan ini?',
      showCancelButton: true,
      confirmButtonText: 'Ya, Setuju',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        _kirimRespon('Persetujuan/setuju', id, '');
      }
    });
  });

  $("#pm-list").on('click', '.pm-btn-tolak', function(){
    var id = $(this).data('id');

    Swal.fire({
      title: 'Tolak Permintaan',
      input: 'text',
      inputPlaceholder: 'Alasan penolakan (wajib diisi)',
      showCancelButton: true,
      confirmButtonText: 'Tolak',
      cancelButtonText: 'Batal',
      inputValidator: (value) => {
        if (!value) {
          return 'Catatan harus diisi jika menolak !';
        }
      }
    }).then((result) => {
      if (result.isConfirmed) {
        _kirimRespon('Persetujuan/tolak', id, result.value);
      }
    });
  });

  $("#brefresh").click(function(){
    _muatData();
  });

  _muatData();

});
