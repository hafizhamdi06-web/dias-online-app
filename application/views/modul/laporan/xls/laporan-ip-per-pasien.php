<?php
    include ('style.php');

    $date1 = $_POST['tgldari'];
    $date2 = $_POST['tglsampai'];
    $idgudang  = isset($_POST['gudang'])    ? $_POST['gudang']    : "";
    $idkontak  = isset($_POST['idkontak'])  ? $_POST['idkontak']  : "";
    $itemArray = isset($_POST['itemarray']) ? $_POST['itemarray'] : array();

    $CI =& get_instance();

    $query = "SELECT H.sukontak 'idpasien', K.knama 'pasien', K.k1telp1 'hp', L.lkode 'sumber',
                     DATE_FORMAT(H.sutanggal,'%d/%m/%Y') 'tgl',
                     H.sunotransaksi 'notrans',
                     I.ikode 'ikode', I.inama 'inama',
                     D.sdkeluar 'qty',
                     (D.sdharga - D.sddiskon) 'harga',
                     D.sdkeluar*(D.sdharga - D.sddiskon) 'subtotal'
                FROM fstokd D
          INNER JOIN fstoku H ON H.suid = D.sdidsu
          INNER JOIN bitem  I ON I.iid  = D.sditem
           LEFT JOIN bkontak K ON H.sukontak = K.kid
           LEFT JOIN blain   L ON K.kmarketingsource = L.lid
               WHERE H.sustatus <> 9 AND H.susumber IN ('IP','AL')
                 AND H.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'";

    if ($idgudang != "") $query .= " AND H.sucabang = '".$CI->db->escape_str($idgudang)."'";
    if ($idkontak != "") $query .= " AND H.sukontak = '".$CI->db->escape_str($idkontak)."'";
    if (!empty($itemArray)) {
        $itemEscaped = array();
        foreach ($itemArray as $it) { $itemEscaped[] = "'".$CI->db->escape_str($it)."'"; }
        $query .= " AND D.sditem IN (".implode(',', $itemEscaped).")";
    }

    $query .= " ORDER BY K.knama, H.sutanggal, H.sunotransaksi";

    $datareport = json_decode($CI->M_transaksi->get_data_query($query));
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
                $curPasien = null;
                $iQty = 0; $iSub = 0;
                $gQty = 0; $gSub = 0; $gPasien = array();

                $flushPasien = function() use (&$iQty, &$iSub) {
                    echo "<tr style='border-top:1px solid #999;'>";
                    echo "<td class='px-1' colspan='4'><b>Jumlah</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($iQty,2)."</b></td>";
                    echo "<td class='right px-1'></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($iSub,2)."</b></td>";
                    echo "</tr>";
                    echo "<tr><td colspan='7' style='height:10px;border:0;'></td></tr>";
                };

                foreach ($datareport->data as $row) {
                    $keyPasien = ($row->idpasien !== null) ? $row->idpasien : 'null';
                    if ($curPasien !== $keyPasien) {
                        if ($curPasien !== null) { $flushPasien(); }
                        $curPasien = $keyPasien;
                        $iQty = 0; $iSub = 0;

                        $namaPasien = ($row->idpasien !== null) ? $row->pasien : '(Tanpa Data Pasien)';

                        echo "<tr><td colspan='7' class='px-1' style='border:0;padding-top:6px;'><b>ID Pasien</b> &nbsp;:&nbsp; ".$row->idpasien."</td></tr>";
                        echo "<tr><td colspan='7' class='px-1' style='border:0;'><b>Nama Pasien</b> &nbsp;:&nbsp; ".$namaPasien."</td></tr>";
                        echo "<tr><td colspan='7' class='px-1' style=\"border:0;mso-number-format:'\@';\"><b>No Telp</b> &nbsp;:&nbsp; ".$row->hp."</td></tr>";
                        echo "<tr><td colspan='7' class='px-1' style='border:0;'><b>Sumber</b> &nbsp;:&nbsp; ".$row->sumber."</td></tr>";
                        echo "<tr class='bg-dark'>";
                        echo "<th class='left px-1'>Tanggal</th>";
                        echo "<th class='left px-1'>No Transaksi</th>";
                        echo "<th class='left px-1'>Kode Barang</th>";
                        echo "<th class='left px-1'>Nama Barang</th>";
                        echo "<th class='right px-1'>Qty</th>";
                        echo "<th class='right px-1'>Harga</th>";
                        echo "<th class='right px-1'>Sub Total</th>";
                        echo "</tr>";
                    }

                    echo "<tr>";
                    echo "<td class='left px-1'>".$row->tgl."</td>";
                    echo "<td class='left px-1'>".$row->notrans."</td>";
                    echo "<td class='left px-1'>".$row->ikode."</td>";
                    echo "<td class='left px-1'>".$row->inama."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->harga,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->subtotal,2)."</td>";
                    echo "</tr>";

                    $iQty += $row->qty; $iSub += $row->subtotal;
                    $gQty += $row->qty; $gSub += $row->subtotal;
                    if ($row->idpasien !== null) { $gPasien[$row->idpasien] = 1; }
                }
                if ($curPasien !== null) { $flushPasien(); }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="3"><b>Grand Total</b></td>
                <td class="left px-1"><b>Pasien <?= count($gPasien); ?></b></td>
                <td class="right px-1"><b><?= eFormatNumber($gQty,2); ?></b></td>
                <td class="right px-1"></td>
                <td class="right px-1"><b><?= eFormatNumber($gSub,2); ?></b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
