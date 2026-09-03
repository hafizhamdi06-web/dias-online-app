<?php
    include ('style.php');

    $tgl = isset($_POST['tgl']) ? $_POST['tgl'] : $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang']) ? $_POST['gudang'] : "";

    $CI =& get_instance();

    $query = "SELECT DATE_FORMAT(V.tanggal_ip,'%d/%m/%Y') 'tgl',
                     V.notransaksi 'notrans',
                     V.nodp 'nodp',
                     V.nilai 'nilai',
                     G.gkode 'gkode',
                     G.gnama 'gnama',
                     P.kkode 'kodepasien',
                     P.knama 'namapelanggan'
                FROM v_data_dp V
           LEFT JOIN bkontak P ON P.kid = V.kontak
           LEFT JOIN bgudang G ON G.gid = V.sucabang
               WHERE V.tanggal_ip <= '".tgl_database($tgl)."'";

    if ($idgudang != "") $query .= " AND V.sucabang = '".$CI->db->escape_str($idgudang)."'";

    $query .= " ORDER BY G.gkode, V.notransaksi, V.nodp";

    $datareport = json_decode($CI->M_transaksi->get_data_query($query));
?>
<div class="header-report">
    <h4 class="text-blue"><?= $company_name; ?></h4>
    <h3><?= $title; ?></h3>
    <span>Per Tanggal (Tgl IP &le;) : <?= $tgl; ?></span>
</div>
<div class="content-report">
    <table class="table">
        <thead>
            <tr class="bg-dark">
                <th class="left px-1">Tanggal</th>
                <th class="left px-1">Kode Pasien</th>
                <th class="left px-1">Nama Pelanggan</th>
                <th class="left px-1">No Transaksi</th>
                <th class="left px-1">No DP</th>
                <th class="right px-1">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $curCabang = null;
                $cNilai = 0; $cRows = 0;
                $gNilai = 0; $gRows = 0;
                $cLabel = '';

                $flushCabang = function() use (&$cNilai, &$cRows, &$cLabel) {
                    echo "<tr style='border-top:1px dashed #333;'>";
                    echo "<td class='px-1' colspan='4'><b>Jumlah Cabang ".$cLabel."</b></td>";
                    echo "<td class='right px-1'><b>".$cRows." trx</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($cNilai,2)."</b></td>";
                    echo "</tr>";
                    echo "<tr><td colspan='6' style='height:8px;border:0;'></td></tr>";
                };

                foreach ($datareport->data as $row) {
                    $ckey = $row->gkode.'|'.$row->gnama;
                    if ($curCabang !== $ckey) {
                        if ($curCabang !== null) { $flushCabang(); }
                        $curCabang = $ckey;
                        $cLabel = $row->gnama;
                        $cNilai = 0; $cRows = 0;
                        echo "<tr><td colspan='6' class='px-1' style='background:#f0f0f0;'><b>".$row->gnama."</b></td></tr>";
                    }

                    echo "<tr>";
                    echo "<td class='left px-1'>".$row->tgl."</td>";
                    echo "<td class='left px-1'>".$row->kodepasien."</td>";
                    echo "<td class='left px-1'>".$row->namapelanggan."</td>";
                    echo "<td class='left px-1'>".$row->notrans."</td>";
                    echo "<td class='left px-1'>".$row->nodp."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->nilai,2)."</td>";
                    echo "</tr>";

                    $cNilai += $row->nilai; $cRows++;
                    $gNilai += $row->nilai; $gRows++;
                }
                if ($curCabang !== null) { $flushCabang(); }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="4"><b>Grand Total</b></td>
                <td class="right px-1"><b><?= $gRows; ?> trx</b></td>
                <td class="right px-1"><b><?= eFormatNumber($gNilai,2); ?></b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
