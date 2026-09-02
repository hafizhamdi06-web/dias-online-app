<?php
    include ('style.php');

    $date1 = $_POST['tgldari'];
    $date2 = $_POST['tglsampai'];
    $idgudang = isset($_POST['gudang'])   ? $_POST['gudang']   : "";
    $iditem   = isset($_POST['item'])     ? $_POST['item']     : "";
    $idkontak = isset($_POST['idkontak']) ? $_POST['idkontak'] : "";

    $CI =& get_instance();

    $query = "SELECT I.ikode 'ikode', I.inama 'inama',
                     DATE_FORMAT(H.sutanggal,'%d/%m/%Y') 'tgl',
                     H.sunotransaksi 'notrans',
                     KR.knama 'kasir',
                     H.sukontak 'idpelanggan',
                     K.knama 'pelanggan',
                     D.sdkeluar 'qty',
                     (D.sdharga - D.sddiskon) 'harga',
                     D.sdkeluar*(D.sdharga - D.sddiskon) 'subtotal'
                FROM fstokd D
          INNER JOIN fstoku H ON H.suid = D.sdidsu
          INNER JOIN bitem  I ON I.iid  = D.sditem
           LEFT JOIN bkontak KR ON H.sukaryawan = KR.kid
           LEFT JOIN bkontak K  ON H.sukontak  = K.kid
               WHERE H.sustatus <> 9 AND H.susumber IN ('IP','AL')
                 AND H.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'";

    if ($idgudang != "") $query .= " AND H.sucabang = '".$CI->db->escape_str($idgudang)."'";
    if ($iditem   != "") $query .= " AND D.sditem   = '".$CI->db->escape_str($iditem)."'";
    if ($idkontak != "") $query .= " AND H.sukontak = '".$CI->db->escape_str($idkontak)."'";

    $query .= " ORDER BY I.ikode, H.sutanggal, H.sunotransaksi";

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
                $curItem = null;
                $iQty = 0; $iSub = 0; $iPasien = array();
                $gQty = 0; $gSub = 0; $gPasien = array();

                $flushItem = function() use (&$iQty, &$iSub, &$iPasien) {
                    echo "<tr style='border-top:1px solid #999;'>";
                    echo "<td class='px-1' colspan='2'><b>Jumlah</b></td>";
                    echo "<td class='px-1'></td>";
                    echo "<td class='right px-1'><b>Pasien ".count($iPasien)."</b></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($iQty,2)."</b></td>";
                    echo "<td class='right px-1'></td>";
                    echo "<td class='right px-1'><b>".eFormatNumber($iSub,2)."</b></td>";
                    echo "</tr>";
                    echo "<tr><td colspan='7' style='height:10px;border:0;'></td></tr>";
                };

                foreach ($datareport->data as $row) {
                    if ($curItem !== $row->ikode) {
                        if ($curItem !== null) { $flushItem(); }
                        $curItem = $row->ikode;
                        $iQty = 0; $iSub = 0; $iPasien = array();

                        echo "<tr><td colspan='7' class='px-1' style='border:0;padding-top:6px;'><b>Kode Barang</b> &nbsp;:&nbsp; ".$row->ikode."</td></tr>";
                        echo "<tr><td colspan='7' class='px-1' style='border:0;'><b>Nama Barang</b> &nbsp;:&nbsp; ".$row->inama."</td></tr>";
                        echo "<tr class='bg-dark'>";
                        echo "<th class='left px-1'>Tanggal</th>";
                        echo "<th class='left px-1'>No Transaksi</th>";
                        echo "<th class='left px-1'>Kasir</th>";
                        echo "<th class='left px-1'>Pelanggan</th>";
                        echo "<th class='right px-1'>Qty</th>";
                        echo "<th class='right px-1'>Harga</th>";
                        echo "<th class='right px-1'>Sub Total</th>";
                        echo "</tr>";
                    }

                    echo "<tr>";
                    echo "<td class='left px-1'>".$row->tgl."</td>";
                    echo "<td class='left px-1'>".$row->notrans."</td>";
                    echo "<td class='left px-1'>".$row->kasir."</td>";
                    echo "<td class='left px-1'>".$row->pelanggan."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->harga,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->subtotal,2)."</td>";
                    echo "</tr>";

                    $iQty += $row->qty; $iSub += $row->subtotal;
                    $gQty += $row->qty; $gSub += $row->subtotal;
                    if ($row->idpelanggan !== null) { $iPasien[$row->idpelanggan] = 1; $gPasien[$row->idpelanggan] = 1; }
                }
                if ($curItem !== null) { $flushItem(); }
            ?>
        </tbody>
        <tfoot>
            <tr style="border-top:2px solid #000;">
                <td class="px-1" colspan="2"><b>Grand Total</b></td>
                <td class="px-1"></td>
                <td class="right px-1"><b>Pasien <?= count($gPasien); ?></b></td>
                <td class="right px-1"><b><?= eFormatNumber($gQty,2); ?></b></td>
                <td class="right px-1"></td>
                <td class="right px-1"><b><?= eFormatNumber($gSub,2); ?></b></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
