<?php
    include ('style.php');

    $date1    = $_POST['tgldari'];
    $date2    = $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang']) ? $_POST['gudang'] : "";
    $idpt     = isset($_POST['namapt']) ? $_POST['namapt'] : "";

    $CI =& get_instance();

    $query = "SELECT jenis, CTCOA_HPP, CTCOA_HPPNAMA, namaproduk, notransaksi, namakontak,
                     SUM(nilaimasuk) 'nilaimasuk', SUM(nilaikeluar) 'nilaikeluar'
                FROM (
                  SELECT CASE WHEN a.susumber IN ('KMB','SJ','TMB') OR sdgudang IN (20,42,46)
                              THEN CTCOA_BIAYA_PRODUK ELSE CTCOA_HPP END AS CTCOA_HPP,
                         CASE WHEN a.susumber IN ('KMB','SJ','TMB') OR sdgudang IN (20,42,46)
                              THEN CTCOA_BIAYA_PRODUK_NAMA ELSE CTCOA_HPPNAMA END AS CTCOA_HPPNAMA,
                         CASE WHEN a.susumber = 'IP' THEN 'POS'
                              WHEN a.susumber = 'SJ' THEN 'Surat Jalan'
                              WHEN a.susumber = 'KMB' AND gkmb.gpt <> b.gpt THEN 'Kirim Mutasi Barang Beda PT'
                              WHEN a.susumber = 'KMB' AND gkmb.gpt = b.gpt THEN 'Kirim Mutasi Barang Sama PT'
                              WHEN a.susumber = 'TMB' AND gtmb.gpt <> b.gpt THEN 'Terima Mutasi Barang Beda PT'
                              WHEN a.susumber = 'TMB' AND gtmb.gpt = b.gpt THEN 'Terima Mutasi Barang Sama PT'
                              WHEN a.susumber = 'PY' THEN 'Penyesuaian Stok'
                              WHEN a.susumber = 'AL' THEN 'Alkes'
                              WHEN a.susumber = 'PBC' THEN 'Pembelian'
                              WHEN a.susumber = 'RC' THEN 'Retur Pembelian'
                              WHEN a.susumber = 'APTK' THEN 'Apotek'
                              WHEN a.susumber = 'PR' THEN 'Penerimaan Retur'
                              ELSE 'xLain' END AS jenis,
                         bitem.inama 'namaproduk',
                         a.sunotransaksi 'notransaksi',
                         kk.knama 'namakontak',
                         icogs * sdmasuk 'nilaimasuk',
                         icogs * (IF(SDDARIPAKET <> 0 AND SDKEDATANGAN > 0, 0, sdkeluar)) 'nilaikeluar'
                    FROM fstokd
               LEFT JOIN bitem ON iid = sditem
               LEFT JOIN fstoku a ON a.suid = sdidsu
               LEFT JOIN fstoku tmb ON tmb.suid = a.SUPRUID
               LEFT JOIN bgudang b ON b.gid = a.sucabang
               LEFT JOIN bgudang gtmb ON gtmb.gid = tmb.sucabang
               LEFT JOIN bgudang gkmb ON gkmb.gid = a.SUGUDANGTUJUAN
               LEFT JOIN bnamapt ON npid = b.gpt
               LEFT JOIN bkontak kk ON kk.kid = a.sukontak
               LEFT JOIN bitem2 ON i2iditem = iid
               LEFT JOIN bcoatipe_pendapatan coabaru ON coabaru.ctid = i2coapendapatan
                   WHERE a.sustatus <> 9 AND SDCANCEL = 0
                     AND a.susumber <> 'PBC' AND a.susumber <> 'PB'
                     AND (CASE WHEN a.susumber = 'TMB' THEN gtmb.gpt = b.gpt
                               WHEN a.susumber = 'SJ'  THEN gkmb.gpt <> b.gpt
                               ELSE a.suid <> 0 END)
                     AND a.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'";

    if ($idgudang != "") $query .= " AND a.sucabang = '".$CI->db->escape_str($idgudang)."'";
    if ($idpt != "")     $query .= " AND b.gpt = '".$CI->db->escape_str($idpt)."'";

    $query .= " ) aa GROUP BY jenis, CTCOA_HPP, CTCOA_HPPNAMA, namaproduk, notransaksi, namakontak
                ORDER BY CTCOA_HPP, CTCOA_HPPNAMA, jenis, namaproduk, notransaksi";

    $datareport = json_decode($CI->M_transaksi->get_data_query($query));

    $namapt = ''; $namacabang = '';
    if ($idpt != "") {
        $q = json_decode($CI->M_transaksi->get_data_query("SELECT npnama 'nama' FROM bnamapt WHERE npid='".$CI->db->escape_str($idpt)."'"));
        if (!empty($q->data)) { $namapt = $q->data[0]->nama; }
    }
    if ($idgudang != "") {
        $q = json_decode($CI->M_transaksi->get_data_query("SELECT gnama 'nama' FROM bgudang WHERE gid='".$CI->db->escape_str($idgudang)."'"));
        if (!empty($q->data)) { $namacabang = $q->data[0]->nama; }
    }
?>
<div class="header-report">
    <h4 class="text-blue"><?= $company_name; ?></h4>
    <h3><?= $title; ?></h3>
    <span>Periode : <?= $date1; ?> s/d <?= $date2; ?><?= ($namapt != '') ? ' &nbsp; | &nbsp; PT : '.$namapt : ''; ?><?= ($namacabang != '') ? ' &nbsp; | &nbsp; Cabang : '.$namacabang : ''; ?></span>
</div>
<div class="content-report">
    <table class="table" border="1">
        <tbody>
            <?php
                $printGroupHeader = function () {
                    echo "<tr class='bg-dark'>";
                    echo "<th class='left px-1'>COA HPP / Biaya Produk</th>";
                    echo "<th class='left px-1'>Nama COA HPP / Biaya Produk</th>";
                    echo "<th class='left px-1'>Jenis</th>";
                    echo "<th class='left px-1'>Nama Produk/Tindakan</th>";
                    echo "<th class='left px-1'>No Transaksi</th>";
                    echo "<th class='left px-1'>Nama Kontak</th>";
                    echo "<th class='right px-1'>Nilai Masuk</th>";
                    echo "<th class='right px-1'>Nilai Keluar</th>";
                    echo "</tr>";
                };

                $printJenisSubtotal = function ($jenis, $masuk, $keluar) {
                    echo "<tr style='border-top:1px solid #999;'>";
                    echo "<td class='px-1' colspan='6'><b>Jumlah ".$jenis."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($masuk,2)."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($keluar,2)."</b></td>";
                    echo "</tr>";
                };

                $printCoaTotal = function ($coa, $coaNama, $masuk, $keluar) {
                    echo "<tr style='border-top:1px solid #000;border-bottom:1px solid #000;'>";
                    echo "<td class='px-1' colspan='6'><b>JUMLAH ".$coa." ".$coaNama."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($masuk,2)."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($keluar,2)."</b></td>";
                    echo "</tr>";
                    echo "<tr><td colspan='8' style='height:10px;border:0;'></td></tr>";
                };

                if (empty($datareport->data)) {
                    $printGroupHeader();
                    echo '<tr><td class="px-1" colspan="8">Tidak ada data pada periode/filter ini.</td></tr>';
                }

                $curCoaKey = null; $curJenis = null; $curNama = null;
                $groupCoa = ''; $groupCoaNama = '';
                $coaMasuk = 0; $coaKeluar = 0;
                $jenisMasuk = 0; $jenisKeluar = 0;
                $gMasuk = 0; $gKeluar = 0;

                foreach ($datareport->data as $row) {
                    $coaKey = $row->CTCOA_HPP.'|'.$row->CTCOA_HPPNAMA;

                    $firstRowOfCoa = ($coaKey !== $curCoaKey);
                    if ($firstRowOfCoa) {
                        if ($curCoaKey !== null) {
                            $printJenisSubtotal($curJenis, $jenisMasuk, $jenisKeluar);
                            $printCoaTotal($groupCoa, $groupCoaNama, $coaMasuk, $coaKeluar);
                        }
                        $curCoaKey = $coaKey; $curJenis = null; $curNama = null;
                        $groupCoa = $row->CTCOA_HPP; $groupCoaNama = $row->CTCOA_HPPNAMA;
                        $coaMasuk = 0; $coaKeluar = 0;
                        $printGroupHeader();
                    }

                    $firstRowOfJenis = ($row->jenis !== $curJenis);
                    if ($firstRowOfJenis) {
                        if ($curJenis !== null) {
                            $printJenisSubtotal($curJenis, $jenisMasuk, $jenisKeluar);
                        }
                        $curJenis = $row->jenis; $curNama = null;
                        $jenisMasuk = 0; $jenisKeluar = 0;
                    }

                    $showNama = ($curNama !== $row->namaproduk);
                    $curNama = $row->namaproduk;

                    echo "<tr>";
                    echo "<td class='left px-1'>".($firstRowOfCoa ? $row->CTCOA_HPP : '')."</td>";
                    echo "<td class='left px-1'>".($firstRowOfCoa ? $row->CTCOA_HPPNAMA : '')."</td>";
                    echo "<td class='left px-1'>".($firstRowOfJenis ? $row->jenis : '')."</td>";
                    echo "<td class='left px-1'>".($showNama ? $row->namaproduk : '')."</td>";
                    echo "<td class='left px-1'>".$row->notransaksi."</td>";
                    echo "<td class='left px-1'>".$row->namakontak."</td>";
                    echo "<td class='right px-1'>".($row->nilaimasuk != 0 ? eFormatNumber($row->nilaimasuk,2) : '')."</td>";
                    echo "<td class='right px-1'>".($row->nilaikeluar != 0 ? eFormatNumber($row->nilaikeluar,2) : '')."</td>";
                    echo "</tr>";

                    $jenisMasuk += $row->nilaimasuk; $jenisKeluar += $row->nilaikeluar;
                    $coaMasuk += $row->nilaimasuk; $coaKeluar += $row->nilaikeluar;
                    $gMasuk += $row->nilaimasuk; $gKeluar += $row->nilaikeluar;
                }

                if ($curCoaKey !== null) {
                    $printJenisSubtotal($curJenis, $jenisMasuk, $jenisKeluar);
                    $printCoaTotal($groupCoa, $groupCoaNama, $coaMasuk, $coaKeluar);
                }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="6"><b>Grand Total</b></td>
                <td class="right px-1"><b><?= eFormatNumber($gMasuk,2); ?></b></td>
                <td class="right px-1"><b><?= eFormatNumber($gKeluar,2); ?></b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
