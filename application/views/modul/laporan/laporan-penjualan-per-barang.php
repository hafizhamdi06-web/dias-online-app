<?php
    include ('style.php');

    $date1 = $_POST['tgldari'];
    $date2 = $_POST['tglsampai'];

    $idgudang = isset($_POST['gudang'])   ? $_POST['gudang']   : "";
    $merchant = isset($_POST['merchant']) ? $_POST['merchant'] : "";
    $filterpt = isset($_POST['filterpt']) ? $_POST['filterpt'] : "";

    $CI =& get_instance();

    $query = "SELECT H.sunotransaksi 'notrans',
                     DATE_FORMAT(H.sutanggal,'%d-%m-%Y') 'tanggal',
                     K.knama 'pelanggan',
                     G.gnama 'cabang',
                     I.inama 'barang',
                     D.sdkeluar 'qty',
                     D.sdharga 'harga',
                     D.sddiskon 'diskon',
                     (D.sdkeluar * D.sdharga - D.sddiskon) 'subtotal',
                     CONCAT_WS(' + ',
                       CASE WHEN H.sutotalkas>0 THEN 'Tunai' END,
                       CASE WHEN H.sutotalkartudebit>0 THEN 'Kartu Debit' END,
                       CASE WHEN H.sutotalkartukredit>0 THEN 'Kartu Kredit' END,
                       CASE WHEN H.sutotaltransfer>0 THEN 'Transfer' END,
                       CASE WHEN H.sutotalvoucher>0 THEN 'Voucher' END,
                       CASE WHEN IFNULL(H.sutotalmedika,0)>0 THEN 'Medika' END,
                       CASE WHEN H.sumerchantjumlah>0 THEN CONCAT('Merchant ',H.sumerchantjenis) END,
                       CASE WHEN IFNULL(H.sunilaipiutang,0)>0 THEN 'Piutang' END,
                       CASE WHEN IFNULL(H.sutotaldp,0)>0 THEN 'DP' END
                     ) 'pembayaran'
                FROM fstokd D
          INNER JOIN fstoku H ON H.suid = D.sdidsu
          INNER JOIN bitem  I ON I.iid  = D.sditem
           LEFT JOIN bkontak K ON K.kid = H.sukontak
           LEFT JOIN bgudang G ON G.gid = H.sucabang
               WHERE H.sustatus <> 9 AND H.susumber = 'IP'
                 AND H.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'";

    if ($idgudang != "") {
        $query .= " AND H.sucabang = '".$CI->db->escape_str($idgudang)."'";
    }
    if ($merchant != "") {
        $query .= " AND H.sumerchantjenis = '".$CI->db->escape_str($merchant)."'";
    }
    if ($filterpt != "") {
        $query .= " AND IFNULL(G.gpt,0) <> '".$CI->db->escape_str($filterpt)."'";
    }

    $query .= " ORDER BY G.gkode, H.sutanggal, H.sunotransaksi, I.inama";

    $datareport = json_decode($CI->M_transaksi->get_data_query($query));
?>
<div class="header-report">
    <h4 class="text-blue"><?= $company_name; ?></h4>
    <h3><?= $title; ?></h3>
    <span>Periode : <?= $date1; ?> s/d <?= $date2; ?><?php if ($merchant != "") echo ' &nbsp;|&nbsp; Jenis Merchant : '.$merchant; ?></span>
</div>
<div class="content-report">
    <table class="table">
        <thead>
            <tr class="bg-dark">
                <th class="left px-1">No Transaksi</th>
                <th class="left px-1">Tanggal</th>
                <th class="left px-1">Nama Pelanggan</th>
                <th class="left px-1">Cabang</th>
                <th class="left px-1">Nama Barang</th>
                <th class="right px-1">Qty</th>
                <th class="right px-1">Harga</th>
                <th class="right px-1">Diskon</th>
                <th class="right px-1">Sub Total</th>
                <th class="left px-1">Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $tqty = 0; $tdiskon = 0; $tsubtotal = 0;
                foreach ($datareport->data as $row) {
                    echo "<tr>";
                    echo "<td class='left px-1'>".$row->notrans."</td>";
                    echo "<td class='left px-1'>".$row->tanggal."</td>";
                    echo "<td class='left px-1'>".$row->pelanggan."</td>";
                    echo "<td class='left px-1'>".$row->cabang."</td>";
                    echo "<td class='left px-1'>".$row->barang."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->harga,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->diskon,2)."</td>";
                    echo "<td class='right px-1'>".eFormatNumber($row->subtotal,2)."</td>";
                    echo "<td class='left px-1'>".$row->pembayaran."</td>";
                    echo "</tr>";

                    $tqty      += $row->qty;
                    $tdiskon   += $row->diskon;
                    $tsubtotal += $row->subtotal;
                }
            ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="px-1">Total</td>
                <td class="right px-1"><?= eFormatNumber($tqty,2); ?></td>
                <td class="right px-1"></td>
                <td class="right px-1"><?= eFormatNumber($tdiskon,2); ?></td>
                <td class="right px-1"><?= eFormatNumber($tsubtotal,2); ?></td>
                <td class="px-1"></td>
            </tr>
        </tfoot>
    </table>
    <div class="clear">&nbsp;</div>
</div>
