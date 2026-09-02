<?php
    include ('style.php');

    $date1 = $_POST['tgldari'];
    $date2 = $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang']) ? $_POST['gudang'] : "";

    $CI =& get_instance();

    $filgudang = ($idgudang != "") ? " AND H.sucabang = '".$CI->db->escape_str($idgudang)."'" : "";

    // Detail: per cabang > minggu (Senin-Minggu) > tindakan/produk
    $q = "SELECT G.gkode 'cabangkode', G.gnama 'cabangnama',
                 YEARWEEK(H.sutanggal,3) 'yw',
                 DATE_FORMAT(DATE_SUB(H.sutanggal, INTERVAL WEEKDAY(H.sutanggal) DAY),'%d/%m/%Y') 'wkawal',
                 DATE_FORMAT(DATE_ADD(DATE_SUB(H.sutanggal, INTERVAL WEEKDAY(H.sutanggal) DAY), INTERVAL 6 DAY),'%d/%m/%Y') 'wkakhir',
                 I.inama 'barang',
                 SUM(D.sdkeluar) 'qty',
                 SUM(D.sdkeluar*(D.sdharga - D.sddiskon)) 'nilai',
                 COUNT(DISTINCT H.sukontak) 'pasien'
            FROM fstokd D
      INNER JOIN fstoku H ON H.suid = D.sdidsu
      INNER JOIN bitem  I ON I.iid  = D.sditem
       LEFT JOIN bgudang G ON G.gid = H.sucabang
           WHERE H.sustatus <> 9 AND H.susumber IN ('IP','AL')
             AND H.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'".$filgudang."
        GROUP BY G.gid, YEARWEEK(H.sutanggal,3), D.sditem
        ORDER BY G.gkode, YEARWEEK(H.sutanggal,3), I.inama";

    $rows = json_decode($CI->M_transaksi->get_data_query($q))->data;

    // Jumlah pasien unik per minggu / per cabang / grand (ROLLUP)
    $qp = "SELECT G.gkode 'cabangkode', YEARWEEK(H.sutanggal,3) 'yw', COUNT(DISTINCT H.sukontak) 'pasien'
             FROM fstoku H LEFT JOIN bgudang G ON G.gid = H.sucabang
            WHERE H.sustatus <> 9 AND H.susumber IN ('IP','AL')
              AND H.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'".$filgudang."
         GROUP BY G.gkode, YEARWEEK(H.sutanggal,3) WITH ROLLUP";

    $prows = json_decode($CI->M_transaksi->get_data_query($qp))->data;

    $pWeek = array(); $pCabang = array(); $pGrand = 0;
    foreach ($prows as $p) {
        if (is_null($p->cabangkode))      $pGrand = $p->pasien;
        elseif (is_null($p->yw))          $pCabang[$p->cabangkode] = $p->pasien;
        else                              $pWeek[$p->cabangkode.'|'.$p->yw] = $p->pasien;
    }

?>
<div class="header-report">
    <h4 class="text-blue"><?= $company_name; ?></h4>
    <h3><?= $title; ?></h3>
    <span>Periode : <?= $date1; ?> s/d <?= $date2; ?> &nbsp;|&nbsp; Sumber : IP &amp; AL</span>
</div>
<div class="content-report">
    <table class="table">
        <thead>
            <tr class="bg-dark">
                <th class="left px-1">Tindakan / Produk</th>
                <th class="right px-1" style="width:110px">Qty Transaksi</th>
                <th class="right px-1" style="width:130px">Nilai</th>
                <th class="right px-1" style="width:90px">Pasien</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $curCabang = null; $curWeek = null;
                $wQty = 0; $wNilai = 0;
                $cQty = 0; $cNilai = 0;
                $gQty = 0; $gNilai = 0;
                $wLabel = ''; $cLabel = '';

                $flushWeek = function() use (&$wQty, &$wNilai, &$wLabel, &$curCabang, &$curWeek, &$pWeek) {
                    $ps = isset($pWeek[$curCabang.'|'.$curWeek]) ? $pWeek[$curCabang.'|'.$curWeek] : 0;
                    echo "<tr style='border-top:1px dashed #999;'>";
                    echo "<td class='px-1' style='padding-left:20px;'><b>Total ".$wLabel."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($wQty,2)."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($wNilai,0)."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($ps,0)."</b></td>";
                    echo "</tr>";
                };
                $flushCabang = function() use (&$cQty, &$cNilai, &$cLabel, &$curCabang, &$pCabang) {
                    $ps = isset($pCabang[$curCabang]) ? $pCabang[$curCabang] : 0;
                    echo "<tr style='border-top:1px dashed #333;'>";
                    echo "<td class='px-1'><b>Total Cabang ".$cLabel."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($cQty,2)."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($cNilai,0)."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($ps,0)."</b></td>";
                    echo "</tr>";
                };

                foreach ($rows as $r) {
                    if ($curCabang !== $r->cabangkode) {
                        if ($curWeek !== null) { $flushWeek(); }
                        if ($curCabang !== null) { $flushCabang(); }
                        $curCabang = $r->cabangkode;
                        $cLabel = $r->cabangnama;
                        $curWeek = null;
                        $cQty = 0; $cNilai = 0;
                        echo "<tr><td colspan='4' class='px-1' style='background:#f0f0f0;'><b>".$r->cabangnama."</b></td></tr>";
                    }
                    if ($curWeek !== $r->yw) {
                        if ($curWeek !== null) { $flushWeek(); }
                        $curWeek = $r->yw;
                        $wLabel = "Minggu ".$r->wkawal." - ".$r->wkakhir;
                        $wQty = 0; $wNilai = 0;
                        echo "<tr><td colspan='4' class='px-1' style='padding-left:12px;'><i>".$wLabel."</i></td></tr>";
                    }

                    echo "<tr>";
                    echo "<td class='left px-1' style='padding-left:28px;'>".$r->barang."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($r->qty,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($r->nilai,0)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($r->pasien,0)."</td>";
                    echo "</tr>";

                    $wQty += $r->qty; $wNilai += $r->nilai;
                    $cQty += $r->qty; $cNilai += $r->nilai;
                    $gQty += $r->qty; $gNilai += $r->nilai;
                }
                if ($curWeek !== null) { $flushWeek(); }
                if ($curCabang !== null) { $flushCabang(); }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1"><b>Grand Total</b></td>
                <td class="right px-1"><b><?= eFormatNumber($gQty,2); ?></b></td>
                <td class="right px-1"><b><?= eFormatNumber($gNilai,0); ?></b></td>
                <td class="right px-1"><b><?= eFormatNumber($pGrand,0); ?></b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
