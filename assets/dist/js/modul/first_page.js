$(function(){

  var _formatAngka = function(n){
    n = Number(n) || 0;
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  };

  var _warna = ['primary','success','warning','info','danger','secondary','dark'];

  var _renderGrup = function(grupId, containerId, rows, icon){
    var $container = $(containerId);
    $container.empty();

    if(!rows || rows.length === 0){
      $(grupId).addClass('d-none');
      return;
    }

    $(grupId).removeClass('d-none');

    $.each(rows, function(i, row){
      var warna = _warna[i % _warna.length];
      var html = "<div class='col-lg-3 col-md-6 col-12'>";
      html += "<div class='gp-ringkasan-box gp-ringkasan-"+warna+"'>";
      html += "<span class='gp-ringkasan-icon'><i class='"+icon+"'></i></span>";
      html += "<div class='rb-body'>";
      html += "<span class='gp-ringkasan-label rb-title'>"+row.label+"</span>";
      html += "<div class='row mx-0 mt-1'>";
      html += "<div class='col-4 text-center px-0'><div class='gp-ringkasan-label'>Hari ini</div><div class='gp-ringkasan-number rb-sub-number'>"+_formatAngka(row.hariini)+"</div></div>";
      html += "<div class='col-4 text-center px-0'><div class='gp-ringkasan-label'>Bulan Ini</div><div class='gp-ringkasan-number rb-sub-number'>"+_formatAngka(row.bulanini)+"</div></div>";
      html += "<div class='col-4 text-center px-0'><div class='gp-ringkasan-label'>Bulan Lalu</div><div class='gp-ringkasan-number rb-sub-number'>"+_formatAngka(row.bulanlalu)+"</div></div>";
      html += "</div></div></div></div>";
      $container.append(html);
    });
  };

  $.ajax({
    "url"    : base_url+"Page_Starter/rekapaktivitas",
    "type"   : "POST",
    "dataType" : "json",
    "cache"  : false,
    "error"  : function(xhr,status,error){
      console.error('error mengambil rekap aktivitas...');
    },
    "success" : function(result){

      $('#greeting-nama').text(result.nama || '-');
      $('#greeting-cabang').text(result.namagudang || '-');

      $('#rekap-namabulanini').text(result.namabulanini || '-');
      $('#rekap-namabulanlalu').text(result.namabulanlalu || '-');

      _renderGrup('#grup-fstoku', '#rekap-fstoku', result.fstoku, 'fas fa-cash-register');
      _renderGrup('#grup-permintaan', '#rekap-permintaan', result.permintaan, 'fas fa-dolly');
      _renderGrup('#grup-salesorder', '#rekap-salesorder', result.salesorder, 'fas fa-file-invoice-dollar');
      _renderGrup('#grup-invoice', '#rekap-invoice', result.invoice, 'fas fa-file-invoice');
      _renderGrup('#grup-kaskecil', '#rekap-kaskecil', result.kaskecil, 'fas fa-wallet');
      _renderGrup('#grup-piutang', '#rekap-piutang', result.piutang, 'fas fa-hand-holding-usd');

    }
  });

});
