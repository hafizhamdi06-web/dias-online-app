<?php
    include ('style.php');

    $date1    = $_POST['tgldari'];
    $date2    = $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang'])   ? $_POST['gudang']   : "";
    $idkontak = isset($_POST['idkontak']) ? $_POST['idkontak'] : "";
    $nomor    = isset($_POST['nomor'])    ? trim($_POST['nomor']) : "";
    $sisasaja = isset($_POST['sisasaja']) && $_POST['sisasaja'] == '1';

    $CI =& get_instance();

    // Validasi 1 instance paket = WAJIB gabungan 3 kolom ini (unik):
    //   IDPASIEN (fstoku.sukontak) + IDPAKET (epaketu.puid) + NOMORPAKET (fstokd.sdcatatankoli)
    // "inst" = daftar instance paket (3 kolom di atas) yang punya transaksi dlm periode filter (sustatus<>9).
    // "pakai" = tiap baris transaksi pemakaian (No Transaksi/Tanggal/Qty), juga sustatus<>9,
    //           dihitung sepanjang umur instance paket itu (TIDAK dibatasi periode filter)
    //           supaya sisa paket mencerminkan pemakaian sebenarnya.
    // Detail diurutkan berdasarkan Kedatangan (bukan tanggal/no transaksi).
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
               ORDER BY inst.pasien, inst.nomorpaket, inst.puid, E.pdurutan, pakai.kedatangan";

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

    // ---- Pass 1: susun data mentah jadi struktur bertingkat Paket -> Item -> baris pemakaian ----
    // (dibuffer dulu, bukan langsung di-echo, supaya bisa disaring "masih ada sisa" per item
    //  sebelum dicetak -- baru diketahui sisanya setelah semua baris item itu selesai dibaca).
    $grup = array();
    foreach ($datareport->data as $row) {
        // nomorpaket (SDCATATANKOLI) TIDAK selalu unik per pembelian paket -- satu nomor catatan
        // koli bisa dipakai bareng utk >1 paket berbeda (puid beda) dlm 1 transaksi, jadi kunci
        // grup wajib ikut puid supaya tidak tertukar nama paketnya.
        $kPaket = $row->nomorpaket.'|'.$row->puid.'|'.$row->idpasien;
        if (!isset($grup[$kPaket])) {
            $grup[$kPaket] = array(
                'nomorpaket' => $row->nomorpaket, 'puid' => $row->puid, 'idpasien' => $row->idpasien,
                'pasien' => $row->pasien, 'kidpasien' => $row->kidpasien, 'notelp' => $row->notelp,
                'pukode' => $row->pukode, 'punama' => $row->punama, 'tglbeli' => $row->tglbeli,
                'items' => array(),
            );
        }

        $kItem = $row->idpaketd;
        if (!isset($grup[$kPaket]['items'][$kItem])) {
            $grup[$kPaket]['items'][$kItem] = array(
                'kodeitem' => $row->kodeitem, 'namaitem' => $row->namaitem,
                'alokasi' => (float) $row->qtyalokasi, 'terpakai' => 0, 'baris' => array(),
            );
        }

        if ($row->notransaksi !== null) {
            // Kedatangan ke-0 = transaksi PEMBELIAN paket (bukan pemakaian): baris tetap dicatat
            // (supaya kelihatan), tapi qty-nya TIDAK dihitung ke Terpakai/Sisa.
            $pembelian = ((int) $row->kedatangan === 0);
            $grup[$kPaket]['items'][$kItem]['baris'][] = array(
                'notransaksi' => $row->notransaksi, 'tanggal' => $row->tanggal,
                'kedatangan' => $row->kedatangan, 'qty' => $row->qty, 'pembelian' => $pembelian,
            );
            if (!$pembelian) {
                $grup[$kPaket]['items'][$kItem]['terpakai'] += (float) $row->qty;
            }
        }
    }

    $warnaSisa = function ($sisa) {
        return $sisa < 0 ? " style='color:#dc3545;'" : "";
    };
?>
<div class="header-report">
    <h4 class="text-blue"><?= $company_name; ?></h4>
    <h3><?= $title; ?></h3>
    <span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
    <table class="table">
        <tbody>
            <?php
                $gAlokasi = 0; $gTerpakai = 0; $jumlahPaketTampil = 0;

                foreach ($grup as $paket) {
                    // Saring item2 sesuai ceklist "Tampilkan yang masih ada sisa saja"
                    $itemTampil = array();
                    foreach ($paket['items'] as $kItem => $item) {
                        $sisaItem = $item['alokasi'] - $item['terpakai'];
                        if ($sisasaja && $sisaItem <= 0) continue;
                        $itemTampil[$kItem] = $item;
                    }
                    if (empty($itemTampil)) continue; // seluruh item paket ini sudah habis -> skip grup

                    $jumlahPaketTampil++;

                    echo "<tr><td colspan='4' class='px-1' style='border:0;padding-top:6px;'><b>Nomor Paket</b> &nbsp;:&nbsp; ".$paket['nomorpaket']."   &nbsp;&nbsp; <b>Pasien</b> &nbsp;:&nbsp; ".$paket['pasien']
                        ." &nbsp;(<b>ID Pasien</b> ".($paket['kidpasien'] ?: '-').", <b>No Telp</b> ".($paket['notelp'] ?: '-').")</td></tr>";
                    echo "<tr><td colspan='4' class='px-1' style='border:0;'><b>Paket</b> &nbsp;:&nbsp; [".$paket['pukode']."] ".$paket['punama']."   &nbsp;&nbsp; <b>Tgl Beli</b> &nbsp;:&nbsp; ".$paket['tglbeli']."</td></tr>";

                    // Riwayat kedatangan lengkap (lintas item, lintas periode filter)
                    $kRiwayat = $paket['nomorpaket'].'|'.$paket['puid'].'|'.$paket['idpasien'];
                    $rk = isset($riwayat[$kRiwayat]) ? $riwayat[$kRiwayat] : array();
                    if (!empty($rk)) {
                        $teksRiwayat = array();
                        foreach ($rk as $r) {
                            $teksRiwayat[] = "Ke-".$r->kedatangan." (".date('d/m/Y', strtotime($r->tanggal)).", ".$r->notransaksi.")";
                        }
                        echo "<tr><td colspan='4' class='px-1' style='border:0;'><b>Riwayat Kedatangan</b> &nbsp;:&nbsp; <span style='font-size:10px;'>".implode(" &nbsp;|&nbsp; ", $teksRiwayat)."</span></td></tr>";
                    }

                    $pgAlokasi = 0; $pgTerpakai = 0;

                    foreach ($itemTampil as $item) {
                        $iqAlokasi = $item['alokasi']; $iqTerpakai = $item['terpakai'];
                        $pgAlokasi += $iqAlokasi; $pgTerpakai += $iqTerpakai;

                        echo "<tr><td colspan='4' class='px-1' style='border:0;'><b>Item</b> &nbsp;:&nbsp; [".$item['kodeitem']."] ".$item['namaitem']." &nbsp;&nbsp; (Qty Alokasi: <b>".eFormatNumber($iqAlokasi,2)."</b>)</td></tr>";
                        echo "<tr class='bg-dark'>";
                        echo "<th class='left px-1'>No Transaksi</th>";
                        echo "<th class='left px-1'>Tanggal</th>";
                        echo "<th class='right px-1'>Kedatangan</th>";
                        echo "<th class='right px-1'>Qty</th>";
                        echo "</tr>";

                        if (empty($item['baris'])) {
                            echo "<tr><td class='left px-1' colspan='3'><i>- belum pernah dipakai -</i></td><td class='right px-1'>0</td></tr>";
                        } else {
                            foreach ($item['baris'] as $b) {
                                echo "<tr".($b['pembelian'] ? " style='color:#6c757d;'" : "").">";
                                echo "<td class='left px-1'>".$b['notransaksi']."</td>";
                                echo "<td class='left px-1'>".$b['tanggal']."</td>";
                                echo "<td class='right px-1'>".eFormatNumber($b['kedatangan'],0).($b['pembelian'] ? " <small>(Pembelian Paket)</small>" : "")."</td>";
                                echo "<td class='right px-1'>".eFormatNumber($b['qty'],2)."</td>";
                                echo "</tr>";
                            }
                        }

                        $sisaItem = $iqAlokasi - $iqTerpakai;
                        echo "<tr>";
                        echo "<td class='px-1' colspan='3'><i>Terpakai item ini</i></td>";
                        echo "<td class='right px-1'".$warnaSisa($sisaItem).">".eFormatNumber($iqTerpakai,2)." <small>(Sisa: <b>".eFormatNumber($sisaItem,2)."</b>)</small></td>";
                        echo "</tr>";
                        echo "<tr><td colspan='4' style='height:6px;border:0;'></td></tr>";
                    }

                    $sisaPaket = $pgAlokasi - $pgTerpakai;
                    echo "<tr style='border-top:1px solid #999;'>";
                    echo "<td class='px-1' colspan='3'><b>Jumlah Nomor Paket</b></td>";
                    echo "<td class='right px-1'".$warnaSisa($sisaPaket).">".eFormatNumber($pgTerpakai,2)." <small>(Sisa: <b>".eFormatNumber($sisaPaket,2)."</b>)</small></td>";
                    echo "</tr>";
                    echo "<tr><td colspan='4' style='height:12px;border:0;'></td></tr>";

                    $gAlokasi += $pgAlokasi; $gTerpakai += $pgTerpakai;
                }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="3"><b>Grand Total &nbsp; (Jumlah Nomor Paket: <?= $jumlahPaketTampil; ?>)</b></td>
                <td class="right px-1"<?= $warnaSisa($gAlokasi-$gTerpakai); ?>><b><?= eFormatNumber($gTerpakai,2); ?> (Sisa: <?= eFormatNumber($gAlokasi-$gTerpakai,2); ?>)</b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
