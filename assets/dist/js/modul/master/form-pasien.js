var _modalEl = (window.parent && window.parent.$) ? window.parent.$('#modal') : $('#modal');

var _selectAjax = (el, url) => {
  $(el).select2({
    "allowClear": true,
    "theme": "bootstrap4",
    "dropdownParent": _modalEl,
    "ajax": {
      "url": base_url + url,
      "type": "post",
      "dataType": "json",
      "delay": 600,
      "data": function (params) {
        return { search: params.term };
      },
      "processResults": function (data) {
        return { results: data };
      }
    }
  });
};

// Bootstrap modal (tabindex="-1") menahan fokus; paksa fokus ke kolom pencarian select2
$(document).off('select2:open.pasien').on('select2:open.pasien', function () {
  setTimeout(function () {
    var s = document.querySelector('.select2-container--open .select2-search__field');
    if (s) s.focus();
  }, 0);
});

var _inputFormat = () => {
  $('.datepicker').datepicker();
  $('.datepicker').inputmask({
    alias: 'dd/mm/yyyy',
    mask: "1-2-y",
    placeholder: "_",
    leapday: "-02-29",
    separator: "-"
  });
};

_inputFormat();

_selectAjax('#kategori', 'Select_Master/view_kategori_kontak');
_selectAjax('#cabang', 'Select_Master/view_gudang');
_selectAjax('#kota', 'Select_Master/view_wilayah_kota');
_selectAjax('#kecamatan', 'Select_Master/view_wilayah_kecamatan');
_selectAjax('#karyawan', 'Select_Master/view_karyawan');
_selectAjax('#karyawantraining', 'Select_Master/view_karyawan');
_selectAjax('#marketingsource', 'Select_Master/view_marketing_source');

$("#bTglLahir").click(function () {
  if ($(this).attr('role')) $("#tgllahir").focus();
});
$("#bTglKontrak").click(function () {
  if ($(this).attr('role')) $("#tglkontrak").focus();
});

function _clearForm() {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);
}

// Default kategori untuk pasien baru: TUNAI (ktid 14)
if ($('#id').val() == '') {
  $('#kategori').append($("<option selected='selected'></option>").val(14).text('TUNAI')).trigger('change');
}

setTimeout(function () { $('#kode').focus(); }, 500);

$("#submit").click(function () {
  if (_IsValid() === 0) return;
  _saveData();
});

var _IsValid = (function () {
  if ($('#kode').val() == '') {
    $('#kode').attr('data-title', 'Kode pasien harus diisi !');
    $('#kode').tooltip('show');
    $('#kode').focus();
    return 0;
  }
  if ($('#nama').val() == '') {
    $('#nama').attr('data-title', 'Nama pasien harus diisi !');
    $('#nama').tooltip('show');
    $('#nama').focus();
    return 0;
  }
  return 1;
});

var _saveData = function () {
  var rey = new FormData();
  rey.set('id', $("#id").val());
  rey.set('kode', $("#kode").val());
  rey.set('nama', $("#nama").val());
  rey.set('kategori', $("#kategori").val() || '');
  rey.set('nomember', $("#nomember").val());
  rey.set('idpasien', $("#idpasien").val());
  rey.set('noktp', $("#noktp").val());
  rey.set('cabang', $("#cabang").val() || '');
  rey.set('tglkontrak', $("#tglkontrak").val());
  rey.set('tgllahir', $("#tgllahir").val());
  rey.set('pekerjaan', $("#pekerjaan").val());
  rey.set('tempatlahir', $("#tempatlahir").val());
  rey.set('alamat', $("#alamat").val());
  rey.set('kota', $("#kota").val() || '');
  rey.set('kecamatan', $("#kecamatan").val() || '');
  rey.set('telp', $("#telp").val());
  rey.set('email', $("#email").val());
  rey.set('nokartu', $("#nokartu").val());
  rey.set('kodetada', $("#kodetada").val());
  rey.set('karyawan', $("#karyawan").val() || '');
  rey.set('karyawantraining', $("#karyawantraining").val() || '');
  rey.set('insider', $("#insider").val());
  rey.set('marketingsource', $("#marketingsource").val() || '');
  rey.set('barulama', $('input[name="barulama"]:checked').val() || 0);
  rey.set('kelamin', $('input[name="kelamin"]:checked').val() || 0);
  rey.set('aktif', $('#aktif').is(":checked") ? 1 : 0);

  $.ajax({
    "url": base_url + "Master_Pasien/savedata",
    "type": "POST",
    "data": rey,
    "processData": false,
    "contentType": false,
    "cache": false,
    "beforeSend": function () {
      $(".loader-wrap").removeClass("d-none");
    },
    "error": function (xhr, status, error) {
      $(".loader-wrap").addClass("d-none");
      toastr.error("Perbaiki masalah ini : " + xhr.status + " " + error);
      console.log(xhr.responseText);
      return;
    },
    "success": function (result) {
      $(".loader-wrap").addClass("d-none");
      if (result == 'sukses') {
        $('#modal').modal('hide');
        toastr.success("Data pasien berhasil disimpan");
        return;
      } else {
        toastr.error(result);
        return;
      }
    }
  });
};

function _appendOption(el, id, text) {
  if (id == null || id == '' || id == 0 || text == null) return;
  var opt = $("<option selected='selected'></option>").val(id).text(text);
  $(el).append(opt).trigger('change');
}

function _getData(id) {
  if (id == '' || id == null) return;

  $.ajax({
    "url": base_url + "Master_Pasien/getdata",
    "type": "POST",
    "dataType": "json",
    "data": "id=" + id,
    "cache": false,
    "beforeSend": function () {
      $('.loader-wrap').removeClass('d-none');
    },
    "error": function (xhr, status, error) {
      $(".main-modal-body").html('');
      toastr.error("Perbaiki kesalahan ini : " + xhr.status + " " + error);
      console.error(xhr.responseText);
      $('.loader-wrap').addClass('d-none');
      return;
    },
    "success": function (result) {
      if (typeof result.pesan !== 'undefined') {
        toastr.error(result.pesan);
        $('.loader-wrap').addClass('d-none');
        return;
      }

      var d = result.data[0];

      $('#id').val(d['id']);
      $('#kode').val(d['kode']);
      $('#nama').val(d['nama']);
      $('#nomember').val(d['nomember']);
      $('#idpasien').val(d['idpasien']);
      $('#noktp').val(d['noktp']);
      $('#pekerjaan').val(d['pekerjaan']);
      $('#tempatlahir').val(d['tempatlahir']);
      $('#alamat').val(d['alamat']);
      $('#telp').val(d['telp']);
      $('#email').val(d['email']);
      $('#nokartu').val(d['nokartu']);
      $('#kodetada').val(d['kodetada']);
      $('#insider').val(d['insider']);

      if (d['tglkontrak'] && d['tglkontrak'].indexOf('00-00') === -1) $('#tglkontrak').val(d['tglkontrak']);
      if (d['tgllahir'] && d['tgllahir'].indexOf('00-00') === -1) $('#tgllahir').val(d['tgllahir']);

      _appendOption('#kategori', d['idkategori'], d['kategori']);
      _appendOption('#cabang', d['idcabang'], d['cabang']);
      _appendOption('#kota', d['idkota'], d['kota']);
      _appendOption('#kecamatan', d['idkecamatan'], d['kecamatan']);
      _appendOption('#karyawan', d['idkaryawan'], d['karyawan']);
      _appendOption('#karyawantraining', d['idkaryawantraining'], d['karyawantraining']);
      _appendOption('#marketingsource', d['idmarketingsource'], d['marketingsource']);

      $('input[name="kelamin"][value="' + (d['kelamin'] == 1 ? 1 : 0) + '"]').prop('checked', true);
      $('input[name="barulama"][value="' + (d['barulama'] == 1 ? 1 : 0) + '"]').prop('checked', true);
      $('#aktif').prop('checked', d['aktif'] == 1);

      $('#lblPoint').text(d['point'] || 0);
      $('#lblTglBuat').text(d['tglbuat'] || '-');
      $('#lblUserBuat').text(d['userbuat'] ? '(' + d['userbuat'] + ')' : '');

      $('.loader-wrap').addClass('d-none');
      return;
    }
  });
}
