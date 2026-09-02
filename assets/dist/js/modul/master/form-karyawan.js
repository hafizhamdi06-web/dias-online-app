var _modalEl = (window.parent && window.parent.$) ? window.parent.$('#modal') : $('#modal');

var _selectAjax = (el, url, parentSel) => {
  $(el).select2({
    "allowClear": true,
    "theme": "bootstrap4",
    "dropdownParent": parentSel ? $(parentSel) : _modalEl,
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
$(document).off('select2:open.karyawan').on('select2:open.karyawan', function () {
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

_selectAjax('#kategori', 'Select_Master/view_kategori_kontak_nonpasien');
_selectAjax('#jeniskaryawan', 'Select_Master/view_jenis_karyawan');
_selectAjax('#cabang', 'Select_Master/view_gudang');
_selectAjax('#kota', 'Select_Master/view_wilayah_kota', '#tabKaryawan');
_selectAjax('#kecamatan', 'Select_Master/view_wilayah_kecamatan', '#tabKaryawan');
_selectAjax('#user', 'Select_Master/view_user', '#tabKaryawan');
_selectAjax('#kelompokfu', 'Select_Master/view_kelompok_fu', '#tabKaryawan');

$("#bTglLahir").click(function () {
  if ($(this).attr('role')) $("#tgllahir").focus();
});
$("#bTglJoin").click(function () {
  if ($(this).attr('role')) $("#tgljoin").focus();
});

function _clearForm() {
  $(":input").not(":button, :submit, :reset, :checkbox, :radio").val('');
  $(":checkbox").prop("checked", false);
}

var _posChecks = ['doktersmy','salesmarketing','aos','dokterbedah','reseller','dokterpj',
                  'kolomdokter','kolomperawat','kolomresep','dokterinsider'];

// Default kategori untuk karyawan baru: KARYAWAN (ktid 4)
if ($('#id').val() == '') {
  $('#kategori').append($("<option selected='selected'></option>").val(4).text('KARYAWAN')).trigger('change');
}

setTimeout(function () { $('#kode').focus(); }, 500);

$("#submit").click(function () {
  if (_IsValid() === 0) return;
  _saveData();
});

var _IsValid = (function () {
  if ($('#kode').val() == '') {
    $('#kode').attr('data-title', 'Kode karyawan harus diisi !');
    $('#kode').tooltip('show');
    $('#kode').focus();
    return 0;
  }
  if ($('#nama').val() == '') {
    $('#nama').attr('data-title', 'Nama karyawan harus diisi !');
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
  rey.set('jeniskaryawan', $("#jeniskaryawan").val() || '');
  rey.set('cabang', $("#cabang").val() || '');
  rey.set('aktif', $('#aktif').is(":checked") ? 1 : 0);

  _posChecks.forEach(function (k) {
    rey.set(k, $('#' + k).is(":checked") ? 1 : 0);
  });

  rey.set('alamat', $("#alamat").val());
  rey.set('kota', $("#kota").val() || '');
  rey.set('kecamatan', $("#kecamatan").val() || '');
  rey.set('nohp', $("#nohp").val());
  rey.set('email', $("#email").val());
  rey.set('kelamin', $('input[name="kelamin"]:checked').val() || 0);
  rey.set('tgllahir', $("#tgllahir").val());
  rey.set('noktp', $("#noktp").val());
  rey.set('user', $("#user").val() || '');
  rey.set('tgljoin', $("#tgljoin").val());

  rey.set('nik', $("#nik").val());
  rey.set('namapanjang', $("#namapanjang").val());
  rey.set('kodeinsider', $("#kodeinsider").val());
  rey.set('kelompokfu', $("#kelompokfu").val() || '');

  $.ajax({
    "url": base_url + "Master_Karyawan/savedata",
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
        toastr.success("Data karyawan berhasil disimpan");
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
    "url": base_url + "Master_Karyawan/getdata",
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
      $('#alamat').val(d['alamat']);
      $('#nohp').val(d['nohp']);
      $('#email').val(d['email']);
      $('#noktp').val(d['noktp']);
      $('#nik').val(d['nik']);
      $('#namapanjang').val(d['namapanjang']);
      $('#kodeinsider').val(d['kodeinsider']);

      if (d['tgllahir'] && d['tgllahir'].indexOf('00-00') === -1) $('#tgllahir').val(d['tgllahir']);
      if (d['tgljoin'] && d['tgljoin'].indexOf('00-00') === -1) $('#tgljoin').val(d['tgljoin']);

      _appendOption('#kategori', d['idkategori'], d['kategori']);
      _appendOption('#jeniskaryawan', d['idjeniskaryawan'], d['jeniskaryawan']);
      _appendOption('#cabang', d['idcabang'], d['cabang']);
      _appendOption('#kota', d['idkota'], d['kota']);
      _appendOption('#kecamatan', d['idkecamatan'], d['kecamatan']);
      _appendOption('#user', d['iduser'], d['user']);
      _appendOption('#kelompokfu', d['idkelompokfu'], d['kelompokfu']);

      $('input[name="kelamin"][value="' + (d['kelamin'] == 1 ? 1 : 0) + '"]').prop('checked', true);
      $('#aktif').prop('checked', d['aktif'] == 1);

      _posChecks.forEach(function (k) {
        $('#' + k).prop('checked', d[k] == 1);
      });

      $('#lblTglBuat').text(d['tglbuat'] || '-');
      $('#lblUserBuat').text(d['userbuat'] ? '(' + d['userbuat'] + ')' : '');

      $('.loader-wrap').addClass('d-none');
      return;
    }
  });
}
