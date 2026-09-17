<?php
    include ('style.php');

    $date1    = $_POST['tgldari'];
    $date2    = $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang'])   ? $_POST['gudang']   : "";
    $idkontak = isset($_POST['idkontak']) ? $_POST['idkontak'] : "";
    $nomor    = isset($_POST['nomor'])    ? trim($_POST['nomor']) : "";

    $CI =& get_instance();

    // Validasi 1 instance paket = WAJIB gabungan 3 kolom ini (unik):
    //   IDPASIEN (fstoku.sukontak) + IDPAKET (epaketu.puid) + NOMORPAKET (fstokd.sdcatatankoli)
    // "inst" = daftar instance paket (3 kolom di atas) yang punya transaksi dlm periode filter (sustatus<>9).
    // "pakai" = tiap baris transaksi pemakaian (No Transaksi/Tanggal/Qty), juga sustatus<>9,
    //           dihitung sepanjang umur instance paket itu (TIDAK dibatasi periode filter)
    //           supaya sisa paket mencerminkan pemakaian sebenarnya.
    $query = "SELECT inst.nomorpaket 'nomorpaket', inst.puid 'puid', inst.idpasien 'idpasien', inst.pasien 'pasien',
                     inst.kidpasien 'kidpasien', inst.notelp 'notelp',
                     inst.pukode 'pukode', inst.punama 'punama', DATE_FORMAT(inst.tglbeli,'%d/%m/%Y') 'tglbeli',
                     E.pdid 'idpaketd', I.ikode 'kodeitem', I.inama 'namaitem', E.pdqty 'qtyalokasi',
                     pakai.notransaksi 'notransaksi', DATE_FORMAT(pakai.tanggal,'%d/%m/%Y') 'tanggal',
                     pakai.kedatangan 'kedatangan', pakai.qty 'qty'
                FROM (
                    SELECT D.sdcatatankoli 'nomorpaket', D.sdidpotongstok 'puid',
                           H.sukontak 'idpasien', MAX(K.knama) 'pasien',
                           MAX(K.kidpasien) 'kidpasien', MAX(K.k1telp1) 'notelp',
                           MAX(U.pukode) 'pukode', MAX(U.punama) 'punama',
                           MIN(H.sutanggal) 'tglbeli'
                      FROM fstokd D
                INNER JOIN fstoku H ON H.suid = D.sdidsu
                INNER JOIN epaketu U ON U.puid = D.sdidpotongstok
                 LEFT JOIN bkontak K ON K.kid = H.sukontak
                     WHERE D.sdidpotongstok > 0 AND H.sustatus <> 9
                       AND D.sdcatatankoli IS NOT NULL AND D.sdcatatankoli <> ''
                       AND H.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'";

    if ($idgudang != "") $query .= " AND H.sucabang = '".$CI->db->escape_str($idgudang)."'";
    if ($idkontak != "") $query .= " AND H.sukontak = '".$CI->db->escape_str($idkontak)."'";
    if ($nomor    != "") $query .= " AND D.sdcatatankoli LIKE '%".$CI->db->escape_like_str($nomor)."%'";

    $query .= "  GROUP BY D.sdcatatankoli, D.sdidpotongstok, H.sukontak
                ) inst
          INNER JOIN epaketd E ON E.pdidu = inst.puid
          INNER JOIN bitem   I ON I.iid   = E.pditem
           LEFT JOIN (
                    SELECT D.sdcatatankoli 'nomorpaket', D.sdidpotongstok 'puid', H.sukontak 'idpasien', D.sdsodurutan 'pdid',
                           H.sunotransaksi 'notransaksi', H.sutanggal 'tanggal',
                           D.sdkedatangan 'kedatangan', D.sdkeluar 'qty'
                      FROM fstokd D
                INNER JOIN fstoku H ON H.suid = D.sdidsu
                     WHERE D.sdidpotongstok > 0 AND H.sustatus <> 9
                ) pakai ON pakai.nomorpaket = inst.nomorpaket AND pakai.puid = inst.puid
                       AND pakai.idpasien = inst.idpasien AND pakai.pdid = E.pdid
               ORDER BY inst.pasien, inst.nomorpaket, inst.puid, E.pdurutan, pakai.tanggal, pakai.notransaksi";

    $datareport = json_decode($CI->M_transaksi->get_data_query($query));

    // Riwayat KEDATANGAN lengkap per instance paket (semua item, TIDAK dibatasi periode filter) --
    // supaya kedatangan ke-0/1/2/dst yang terjadi di item lain / di luar periode tetap kelihatan
    // kapan tanggal & no transaksinya, walau item yg lagi dilihat cuma tampil di kedatangan ke-4 misalnya.
    // Validasi instance paket WAJIB gabungan 3: IDPASIEN(sukontak) + IDPAKET(puid) + NOMORPAKET(sdcatatankoli) -- itu yg unik.
    $riwayat = array();
    $pasangan = array();
    foreach ($datareport->data as $row) {
        $pasangan[$row->nomorpaket.'|'.$row->puid.'|'.$row->idpasien] = array($row->nomorpaket, $row->puid, $row->idpasien);
    }
    if (!empty($pasangan)) {
        $tuple = array();
        foreach ($pasangan as $p) {
            $tuple[] = "('".$CI->db->escape_str($p[0])."',".(int) $p[1].",".(int) $p[2].")";
        }
        $queryRiwayat = "SELECT D.sdcatatankoli 'nomorpaket', D.sdidpotongstok 'puid', H.sukontak 'idpasien',
                                 D.sdkedatangan 'kedatangan', MIN(H.sutanggal) 'tanggal', MIN(H.sunotransaksi) 'notransaksi'
                            FROM fstokd D
                      INNER JOIN fstoku H ON H.suid = D.sdidsu
                           WHERE H.sustatus <> 9
                             AND (D.sdcatatankoli, D.sdidpotongstok, H.sukontak) IN (".implode(',', $tuple).")
                        GROUP BY D.sdcatatankoli, D.sdidpotongstok, H.sukontak, D.sdkedatangan
                        ORDER BY D.sdcatatankoli, D.sdidpotongstok, H.sukontak, D.sdkedatangan";

        $dataRiwayat = json_decode($CI->M_transaksi->get_data_query($queryRiwayat));
        foreach ($dataRiwayat->data as $r) {
            $k = $r->nomorpaket.'|'.$r->puid.'|'.$r->idpasien;
            if (!isset($riwayat[$k])) { $riwayat[$k] = array(); }
            $riwayat[$k][] = $r;
        }
    }
?>
<div class="header-report">
    <h4 class="text-blue"><?= $company_name; ?></h4>
    <h3><?= $title; ?></h3>
    <span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
    <table class="table" border="1">
        <tbody>
            <?php
                $curPaket = null;   // nomorpaket|idpasien
                $curItem  = null;   // idpaketd
                $iqAlokasi = 0; $iqTerpakai = 0;      // subtotal per item
                $pgAlokasi = 0; $pgTerpakai = 0;      // subtotal per nomor paket
                $gAlokasi  = 0; $gTerpakai  = 0; $gPaket = array();

                $warnaSisa = function($sisa) {
                    return $sisa < 0 ? " style='color:#dc3545;'" : "";
                };

                $flushItem = function() use (&$iqAlokasi, &$iqTerpakai, $warnaSisa) {
                    $sisa = $iqAlokasi - $iqTerpakai;
                    echo "<tr>";
                    echo "<td class='px-1' colspan='3'><i>Terpakai item ini</i></td>";
                    echo "<td class='right px-1'".$warnaSisa($sisa).">".eFormatNumber($iqTerpakai,2)." <small>(Sisa: <b>".eFormatNumber($sisa,2)."</b>)</small></td>";
                    echo "</tr>";
                    echo "<tr><td colspan='4' style='height:6px;border:0;'></td></tr>";
                };

                $flushPaket = function() use (&$pgAlokasi, &$pgTerpakai, $warnaSisa) {
                    $sisa = $pgAlokasi - $pgTerpakai;
                    echo "<tr style='border-top:1px solid #999;'>";
                    echo "<td class='px-1' colspan='3'><b>Jumlah Nomor Paket</b></td>";
                    echo "<td class='right px-1'".$warnaSisa($sisa).">".eFormatNumber($pgTerpakai,2)." <small>(Sisa: <b>".eFormatNumber($sisa,2)."</b>)</small></td>";
                    echo "</tr>";
                    echo "<tr><td colspan='4' style='height:12px;border:0;'></td></tr>";
                };

                foreach ($datareport->data as $row) {
                    // nomorpaket (SDCATATANKOLI) TIDAK selalu unik per pembelian paket -- satu nomor
                    // catatan koli bisa dipakai bareng utk >1 paket berbeda (puid beda) dlm 1 transaksi,
                    // jadi kunci grup wajib ikut puid supaya tidak tertukar nama paketnya.
                    $keyPaket = $row->nomorpaket.'|'.$row->puid.'|'.$row->idpasien;

                    if ($curPaket !== $keyPaket) {
                        if ($curItem !== null) { $flushItem(); }
                        if ($curPaket !== null) { $flushPaket(); }
                        $curPaket = $keyPaket; $curItem = null;
                        $pgAlokasi = 0; $pgTerpakai = 0;
                        if (!isset($gPaket[$keyPaket])) { $gPaket[$keyPaket] = 1; }

                        echo "<tr><td colspan='4' class='px-1' style='border:0;padding-top:6px;'><b>Nomor Paket</b> &nbsp;:&nbsp; ".$row->nomorpaket."   &nbsp;&nbsp; <b>Pasien</b> &nbsp;:&nbsp; ".$row->pasien
                            ." &nbsp;(<b>ID Pasien</b> ".($row->kidpasien ?: '-').", <b>No Telp</b> ".($row->notelp ?: '-').")</td></tr>";
                        echo "<tr><td colspan='4' class='px-1' style='border:0;'><b>Paket</b> &nbsp;:&nbsp; [".$row->pukode."] ".$row->punama."   &nbsp;&nbsp; <b>Tgl Beli</b> &nbsp;:&nbsp; ".$row->tglbeli."</td></tr>";

                        // Riwayat kedatangan lengkap (lintas item, lintas periode filter)
                        $kRiwayat = $row->nomorpaket.'|'.$row->puid.'|'.$row->idpasien;
                        $rk = isset($riwayat[$kRiwayat]) ? $riwayat[$kRiwayat] : array();
                        if (!empty($rk)) {
                            $teksRiwayat = array();
                            foreach ($rk as $r) {
                                $teksRiwayat[] = "Ke-".$r->kedatangan." (".date('d/m/Y', strtotime($r->tanggal)).", ".$r->notransaksi.")";
                            }
                            echo "<tr><td colspan='4' class='px-1' style='border:0;'><b>Riwayat Kedatangan</b> &nbsp;:&nbsp; <span style='font-size:10px;'>".implode(" &nbsp;|&nbsp; ", $teksRiwayat)."</span></td></tr>";
                        }
                    }

                    if ($curItem !== $row->idpaketd) {
                        if ($curItem !== null) { $flushItem(); }
                        $curItem = $row->idpaketd;
                        $iqAlokasi = (float) $row->qtyalokasi; $iqTerpakai = 0;
                        $pgAlokasi += (float) $row->qtyalokasi;

                        echo "<tr><td colspan='4' class='px-1' style='border:0;'><b>Item</b> &nbsp;:&nbsp; [".$row->kodeitem."] ".$row->namaitem." &nbsp;&nbsp; (Qty Alokasi: <b>".eFormatNumber($row->qtyalokasi,2)."</b>)</td></tr>";
                        echo "<tr class='bg-dark'>";
                        echo "<th class='left px-1'>No Transaksi</th>";
                        echo "<th class='left px-1'>Tanggal</th>";
                        echo "<th class='right px-1'>Kedatangan</th>";
                        echo "<th class='right px-1'>Qty</th>";
                        echo "</tr>";
                    }

                    if ($row->notransaksi === null) {
                        echo "<tr><td class='left px-1' colspan='3'><i>- belum pernah dipakai -</i></td><td class='right px-1'>0</td></tr>";
                    } else {
                        // Kedatangan ke-0 = transaksi PEMBELIAN paket (bukan pemakaian),
                        // jadi qty-nya ditampilkan tapi TIDAK dihitung ke Terpakai/Sisa.
                        $pembelian = ((int) $row->kedatangan === 0);

                        echo "<tr".($pembelian ? " style='color:#6c757d;'" : "").">";
                        echo "<td class='left px-1'>".$row->notransaksi."</td>";
                        echo "<td class='left px-1'>".$row->tanggal."</td>";
                        echo "<td class='right px-1'>".eFormatNumber($row->kedatangan,0).($pembelian ? " <small>(Pembelian Paket)</small>" : "")."</td>";
                        echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
                        echo "</tr>";

                        if (!$pembelian) {
                            $iqTerpakai += (float) $row->qty;
                            $pgTerpakai += (float) $row->qty;
                        }
                    }
                }
                if ($curItem  !== null) { $flushItem(); }
                if ($curPaket !== null) { $flushPaket(); }

                // Grand total dihitung ulang per item unik (bukan dari variabel loop di atas,
                // supaya tidak dobel kalau satu item punya beberapa baris transaksi).
                // Kedatangan ke-0 (pembelian paket) tetap dikecualikan dari Terpakai.
                $itemUnik = array();
                foreach ($datareport->data as $row) {
                    $k = $row->nomorpaket.'|'.$row->idpasien.'|'.$row->idpaketd;
                    if (!isset($itemUnik[$k])) {
                        $itemUnik[$k] = array('alokasi' => (float) $row->qtyalokasi, 'terpakai' => 0);
                    }
                    if ($row->notransaksi !== null && (int) $row->kedatangan !== 0) {
                        $itemUnik[$k]['terpakai'] += (float) $row->qty;
                    }
                }
                foreach ($itemUnik as $it) { $gAlokasi += $it['alokasi']; $gTerpakai += $it['terpakai']; }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="3"><b>Grand Total &nbsp; (Jumlah Nomor Paket: <?= count($gPaket); ?>)</b></td>
                <td class="right px-1"<?= $warnaSisa($gAlokasi-$gTerpakai); ?>><b><?= eFormatNumber($gTerpakai,2); ?> (Sisa: <?= eFormatNumber($gAlokasi-$gTerpakai,2); ?>)</b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
