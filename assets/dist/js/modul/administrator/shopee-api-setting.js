toastr.options = {
  "positionClass": "toast-top-center",
  "timeOut": "3000"
};

$(function () {

  this.addEventListener('contextmenu', function(event){
    event.preventDefault();
  });

  $('#cabang').select2({
    "theme":"bootstrap4",
    "placeholder":"Pilih cabang",
    "ajax": {
      "url": base_url+"Select_Master/view_gudang",
      "type": "post",
      "dataType": "json",
      "delay": 500,
      "data": function(params){ return { search: params.term }; },
      "processResults": function(data){ return { results: data }; }
    }
  });

  // default Redirect URI = domain aplikasi + Shopee_Api/callback (case-sensitive di production)
  $('#redirect_uri').val(base_url + 'Shopee_Api/callback');

  _muatSetting();

  $('#bsimpan').click(function(){
    _simpanSetting();
  });

  $('#bhubungkan').click(function(){
    _hubungkan();
  });

  window.addEventListener('message', function(e){
    if (e.data === 'shopee-auth-ok' || e.data === 'shopee-auth-gagal') {
      toastr[e.data === 'shopee-auth-ok' ? 'success' : 'error'](
        e.data === 'shopee-auth-ok' ? 'Toko Shopee berhasil terhubung.' : 'Otorisasi Shopee gagal, cek Shop ID/Partner Key.'
      );
      _muatSetting();
    }
  });

});

function _muatSetting(){
  $.ajax({
    "url": base_url+"Shopee_Api/setting_get",
    "type": "POST",
    "dataType": "json",
    "success": function(result){
      var d = result.data;
      if (!d) {
        $('#badge-status').removeClass('badge-success badge-danger').addClass('badge-secondary').text('Belum diseting');
        return;
      }
      $('#nama_toko').val(d.nama_toko || '');
      $('#partner_id').val(d.partner_id || '');
      $('#partner_key').attr('placeholder', d.partner_key_mask ? d.partner_key_mask+' (kosongkan kalau tidak diganti)' : '(kosongkan kalau tidak diganti)');
      $('#shop_id').val(d.shop_id || '');
      $('#redirect_uri').val(d.redirect_uri || (base_url + 'Shopee_Api/callback'));
      $('#environment').val(d.environment || 'live');

      if (d.cabang) {
        $('#cabang').append(new Option('Cabang #'+d.cabang, d.cabang, true, true)).trigger('change');
      }

      if (d.terhubung == 1) {
        $('#badge-status').removeClass('badge-secondary badge-danger').addClass('badge-success')
          .text('Terhubung (token s/d '+(d.access_token_expire || '-')+')');
      } else {
        $('#badge-status').removeClass('badge-secondary badge-success').addClass('badge-danger').text('Belum terhubung');
      }
    },
    "error": function(){
      toastr.error('Gagal memuat seting.');
    }
  });
}

function _simpanSetting(){
  $.ajax({
    "url": base_url+"Shopee_Api/setting_save",
    "type": "POST",
    "dataType": "json",
    "data": {
      nama_toko: $('#nama_toko').val(),
      partner_id: $('#partner_id').val(),
      partner_key: $('#partner_key').val(),
      shop_id: $('#shop_id').val(),
      redirect_uri: $('#redirect_uri').val(),
      environment: $('#environment').val(),
      cabang: $('#cabang').val()
    },
    "beforeSend": function(){ $('#bsimpan').prop('disabled', true); },
    "success": function(result){
      $('#bsimpan').prop('disabled', false);
      if (result.pesan === 'sukses') {
        toastr.success('Seting tersimpan.');
        $('#partner_key').val('');
        _muatSetting();
      } else {
        toastr.error(result.error || 'Gagal menyimpan.');
      }
    },
    "error": function(xhr,status,error){
      $('#bsimpan').prop('disabled', false);
      toastr.error('Gagal menyimpan : '+xhr.status+' '+error);
    }
  });
}

function _hubungkan(){
  $.ajax({
    "url": base_url+"Shopee_Api/auth_url",
    "type": "POST",
    "dataType": "json",
    "beforeSend": function(){ $('#bhubungkan').prop('disabled', true); },
    "success": function(result){
      $('#bhubungkan').prop('disabled', false);
      if (result.pesan !== 'sukses') {
        toastr.error(result.error || 'Gagal membuat URL otorisasi.');
        return;
      }
      window.open(result.url, 'shopeeAuth', 'width=520,height=680');
    },
    "error": function(xhr,status,error){
      $('#bhubungkan').prop('disabled', false);
      toastr.error('Gagal : '+xhr.status+' '+error);
    }
  });
}
