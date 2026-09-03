<?php
    include ('style.php');

    $tgl = isset($_POST['tgl']) ? $_POST['tgl'] : $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang']) ? $_POST['gudang'] : "";

    $CI =& get_instance();

    $query = "SELECT DATE_FORMAT(MIN(V.tanggal_ip),'%d/%m/%Y') 'tgl',
                     V.nodp 'nodp',
                     SUM(V.nilai) 'nilai',
                     MAX(P.kkode) 'kodepasien',
                     MAX(P.knama) 'namapelanggan'
                FROM v_data_dp V
           LEFT JOIN bkontak P ON P.kid = V.kontak
               WHERE V.tanggal_ip <= '".tgl_database($tgl)."'";

    if ($idgudang != "") $query .= " AND V.sucabang = '".$CI->db->escape_str($idgudang)."'";

    $query .= " GROUP BY V.nodp
                HAVING SUM(V.nilai) <> 0
                ORDER BY V.nodp";

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
                <th class="right px-1">No</th>
                <th class="left px-1">Tanggal</th>
                <th class="left px-1">Kode Pasien</th>
                <th class="left px-1">Nama Pelanggan</th>
                <th class="left px-1">No DP</th>
                <th class="right px-1">Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no = 0; $gNilai = 0;
                foreach ($datareport->data as $row) {
                    $no++;
                    echo "<tr>";
                    echo "<td class='right px-1'>".$no."</td>";
                    echo "<td class='left px-1'>".$row->tgl."</td>";
                    echo "<td class='left px-1'>".$row->kodepasien."</td>";
                    echo "<td class='left px-1'>".$row->namapelanggan."</td>";
                    echo "<td class='left px-1'>".$row->nodp."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->nilai,2)."</td>";
                    echo "</tr>";

                    $gNilai += $row->nilai;
                }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="4"><b>Grand Total &mdash; <?= $no; ?> No DP</b></td>
                <td class="px-1"></td>
                <td class="right px-1"><b><?= eFormatNumber($gNilai,2); ?></b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
