<?php
    include ('style.php');

    $date1 = $_POST['tgldari'];
    $date2 = $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang']) ? $_POST['gudang'] : "";

    $CI =& get_instance();

    $query = "SELECT K.knama 'nama',
                     CASE WHEN K.ktgllahir IS NULL OR YEAR(K.ktgllahir) < 1901 THEN '' ELSE DATE_FORMAT(K.ktgllahir,'%d/%m/%Y') END 'tgllahir',
                     K.kidpasien 'idpasien',
                     G.gnama 'cabang',
                     COUNT(DISTINCT H.sutanggal) 'kedatangan',
                     SUM(IFNULL(H.sutotaltransaksi,0)) 'subtotal',
                     DATE_FORMAT(MAX(H.sutanggal),'%d/%m/%Y') 'terakhir',
                     K.k1telp1 'notelp',
                     CASE WHEN IFNULL(K.kjeniskelamin,0)=0 THEN 'PR' ELSE 'LK' END 'jk',
                     W2.bnama 'kecamatan',
                     W1.bnama 'kota'
                FROM fstoku H
          INNER JOIN bkontak K  ON K.kid = H.sukontak
           LEFT JOIN bgudang G  ON G.gid = H.sucabang
           LEFT JOIN bwilayah W1 ON K.k1kota      = W1.bwid
           LEFT JOIN bwilayah W2 ON K.k1kecamatan = W2.bwid
               WHERE H.sustatus <> 9 AND H.susumber = 'IP'
                 AND H.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'";

    if ($idgudang != "") $query .= " AND H.sucabang = '".$CI->db->escape_str($idgudang)."'";

    $query .= " GROUP BY H.sukontak, H.sucabang
                ORDER BY K.knama, G.gnama";

    $datareport = json_decode($CI->M_transaksi->get_data_query($query));
?>
<div class="header-report">
    <h4 class="text-blue"><?= $company_name; ?></h4>
    <h3><?= $title; ?></h3>
    <span>Periode : <?= $date1; ?> s/d <?= $date2; ?> &nbsp;|&nbsp; Sumber : IP</span>
</div>
<div class="content-report">
    <table class="table">
        <thead>
            <tr class="bg-dark">
                <th class="right px-1">No</th>
                <th class="left px-1">Nama Pasien</th>
                <th class="left px-1">Tgl Lahir</th>
                <th class="left px-1">Id Pasien</th>
                <th class="left px-1">Cabang</th>
                <th class="right px-1">Kedatangan</th>
                <th class="right px-1">Sub Total</th>
                <th class="left px-1">Kedatangan Terakhir</th>
                <th class="left px-1">No Telp</th>
                <th class="left px-1">JK</th>
                <th class="left px-1">Kecamatan</th>
                <th class="left px-1">Kota</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $no = 0; $tKedatangan = 0; $tSub = 0;
                foreach ($datareport->data as $row) {
                    $no++;
                    echo "<tr>";
                    echo "<td class='right px-1'>".$no."</td>";
                    echo "<td class='left px-1'>".$row->nama."</td>";
                    echo "<td class='left px-1'>".$row->tgllahir."</td>";
                    echo "<td class='left px-1'>".$row->idpasien."</td>";
                    echo "<td class='left px-1'>".$row->cabang."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->kedatangan,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->subtotal,2)."</td>";
                    echo "<td class='left px-1'>".$row->terakhir."</td>";
                    echo "<td class='left px-1'>".$row->notelp."</td>";
                    echo "<td class='left px-1'>".$row->jk."</td>";
                    echo "<td class='left px-1'>".$row->kecamatan."</td>";
                    echo "<td class='left px-1'>".$row->kota."</td>";
                    echo "</tr>";

                    $tKedatangan += $row->kedatangan;
                    $tSub        += $row->subtotal;
                }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="4"><b>Total &mdash; <?= $no; ?> pasien</b></td>
                <td class="px-1"></td>
                <td class="right px-1"><b><?= eFormatNumber($tKedatangan,2); ?></b></td>
                <td class="right px-1"><b><?= eFormatNumber($tSub,2); ?></b></td>
                <td class="px-1" colspan="5"></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
