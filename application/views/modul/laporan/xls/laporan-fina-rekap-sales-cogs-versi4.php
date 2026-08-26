<?php
include "style.php";
$date1 = $_POST["tgldari"];
$date2 = $_POST["tglsampai"];
if (isset($_POST["gudang"])) {
	$idgudang = $_POST["gudang"];
} else {
	$idgudang = "";
}
if (isset($_POST["namapt"])) {
	$idpt = $_POST["namapt"];
} else {
	$idpt = "";
}
$tampilNol = $_POST["saldo"];

$CI = &get_instance();


$query =
	"SELECT pbc.sunotransaksi 'nopbc', pbc.sutanggal 'tanggalpbc', sum(sdmasuk) 'qty',sum(icogs * sdmasuk) 'subtotal'
	,gsj.gkode 'gudangasal'
                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku pbc ON pbc.suid = sdidsu
                    LEFT JOIN bgudang gpbc      on gpbc.gid=pbc.sucabang
                    LEFT JOIN fstoku sj ON sj.suid = pbc.sunosjapotik
                    LEFT JOIN bgudang gsj      on gsj.gid=sj.sucabang
                    LEFT JOIN bnamapt      on npid=gpbc.gpt

                    WHERE pbc.susumber = 'PBC'
                        AND pbc.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND pbc.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpbc.gpt='" . $idpt . "'";
}

$query .= " GROUP BY gsj.gkode,pbc.sunotransaksi,pbc.sutanggal ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>




<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Pembelian Ke Vendor Berdasarkan Penerimaan Barang Cabang</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>

<div class="content-report">
	<table class="table" >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Pemasok</th>
				<th class="left px-1">No PBC</th>
				<th class="left px-1">Tanggal PBC</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Sub Total</th>
			</tr>
		</thead>
		<tbody>
			<?

                        $pembelian_total=0;

				foreach ($datareport->data as $row) {

					$sales=0;

    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->gudangasal."</td>";
    					echo "<td class='left px-1'>".$row->nopbc."</td>";
    					echo "<td class='left px-1'>".$row->tanggalpbc."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";

                        $pembelian_total+=$row->subtotal;
				}
			?>
		</tbody>
		<tfoot>
			<?
							echo "<tr>";
    					echo "<td class='left px-1' colspan=4>Total Pembelian</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";
			?>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>


<?php
$query =
	"SELECT tmb.sunotransaksi 'nopbc', tmb.sutanggal 'tanggalpbc',sum(sdmasuk) 'qty',
                    sum(icogs * sdmasuk) 'subtotal' , kmb.sunotransaksi 'nokmb', gkmb.gkode 'gudangasal'

                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku tmb ON tmb.suid = sdidsu
                    LEFT JOIN bgudang gtmb      on gtmb.gid=tmb.sucabang
                    LEFT JOIN bnamapt      on npid=gtmb.gpt
                    LEFT JOIN fstoku kmb ON kmb.suid = tmb.SUPRUID
                    LEFT JOIN bgudang gkmb      on gkmb.gid=kmb.sucabang

                    WHERE tmb.susumber = 'TMB' and gtmb.gpt<> gkmb.gpt
                        AND tmb.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND tmb.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gtmb.gpt='" . $idpt . "'";
}

$query .= " GROUP BY gkmb.gkode,tmb.sunotransaksi,tmb.sutanggal,kmb.sunotransaksi ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>

<pagebreak />

<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Pembelian Antar Cabang Beda PT</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table" >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Gudang Asal</th>
				<th class="left px-1">No KMB</th>
				<th class="left px-1">No TMB</th>
				<th class="left px-1">Tanggal TMB</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Sub Total</th>
			</tr>
		</thead>
		<tbody>
			<?

                        $pembelian_total=0;

				foreach ($datareport->data as $row) {

					$sales=0;

    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->gudangasal."</td>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "<td class='left px-1'>".$row->nopbc."</td>";
    					echo "<td class='left px-1'>".$row->tanggalpbc."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";

                        $pembelian_total+=$row->subtotal;


				}
			?>
		</tbody>
		<tfoot>
<?



				echo "<tr>";
    					echo "<td class='left px-1' colspan=5>Total Pembelian</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";




?>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>



<?php
$query =
	"SELECT tmb.sunotransaksi 'nopbc', tmb.sutanggal 'tanggalpbc',sum(sdmasuk) 'qty',
                    sum(icogs * sdmasuk) 'subtotal' , kmb.sunotransaksi 'nokmb', gkmb.gkode 'gudangasal'

                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku tmb ON tmb.suid = sdidsu
                    LEFT JOIN bgudang gtmb      on gtmb.gid=tmb.sucabang
                    LEFT JOIN bnamapt      on npid=gtmb.gpt
                    LEFT JOIN fstoku kmb ON kmb.suid = tmb.SUPRUID
                    LEFT JOIN bgudang gkmb      on gkmb.gid=kmb.sucabang

                    WHERE tmb.susumber = 'TMB' and gtmb.gpt= gkmb.gpt
                        AND tmb.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND tmb.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gtmb.gpt='" . $idpt . "'";
}

$query .= " GROUP BY gkmb.gkode,tmb.sunotransaksi,tmb.sutanggal,kmb.sunotransaksi ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>

<pagebreak />

<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Pembelian Antar Cabang 1 PT</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table" >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Gudang Asal</th>
				<th class="left px-1">No KMB</th>
				<th class="left px-1">No TMB</th>
				<th class="left px-1">Tanggal TMB</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Sub Total</th>
			</tr>
		</thead>
		<tbody>
			<?

                        $pembelian_total=0;

				foreach ($datareport->data as $row) {

					$sales=0;

    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->gudangasal."</td>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "<td class='left px-1'>".$row->nopbc."</td>";
    					echo "<td class='left px-1'>".$row->tanggalpbc."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";

                        $pembelian_total+=$row->subtotal;


				}
			?>
		</tbody>
		<tfoot>
<?



				echo "<tr>";
    					echo "<td class='left px-1' colspan=5>Total Pembelian</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";




?>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>



<?php
$query =
	"SELECT a.sunotransaksi 'nokmb', a.sutanggal 'tanggalkmb',
	sum(sdkeluar) 'qty', sum(icogs * sdkeluar) 'subtotal', gkmb.gkode 'gudangtujuan', sum((icogs * 1.05)*sdkeluar) 'nilaijual'
                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN bgudang b      on b.gid=a.sucabang
                    LEFT JOIN bgudang gkmb   on gkmb.gid=a.SUGUDANGTUJUAN
                    LEFT JOIN bnamapt      on npid=b.gpt
                    WHERE a.susumber = 'KMB' and gkmb.gpt<>b.gpt
                        AND a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND b.gpt='" . $idpt . "'";
}

$query .= " GROUP BY gkmb.gkode,a.sunotransaksi,a.sutanggal ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>

<pagebreak />

<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Penjualan Antar Cabang Beda PT</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table"  >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Gudang Tujuan</th>
				<th class="left px-1">No KMB</th>
				<th class="left px-1">Tanggal KMB</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Sub Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
                        $pembelian_total=0;$nilaijual_kmb_total=0;
				foreach ($datareport->data as $row) {
					$sales=0;
    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->gudangtujuan."</td>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "<td class='left px-1'>".$row->tanggalkmb."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";
                        $pembelian_total+=$row->subtotal;
                        $nilaijual_kmb_total+=$row->subtotal;
				}
			?>
		</tbody>
		<tfoot>
			<?
							echo "<tr>";
    					echo "<td class='left px-1' colspan=4>Total Harga COGS</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";
			?>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>



<?php
$query =
	"SELECT a.sunotransaksi 'nokmb', a.sutanggal 'tanggalkmb',
	sum(sdkeluar) 'qty', sum(icogs * sdkeluar) 'subtotal', gkmb.gkode 'gudangtujuan'
                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN bgudang b      on b.gid=a.sucabang
                    LEFT JOIN bgudang gkmb   on gkmb.gid=a.SUGUDANGTUJUAN
                    LEFT JOIN bnamapt      on npid=b.gpt
                    WHERE a.susumber = 'KMB' and gkmb.gpt=b.gpt
                        AND a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND b.gpt='" . $idpt . "'";
}

$query .= " GROUP BY gkmb.gkode,a.sunotransaksi,a.sutanggal ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>


<pagebreak />

<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Penjualan Antar Cabang 1 PT</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table"  >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Gudang Tujuan</th>
				<th class="left px-1">No KMB</th>
				<th class="left px-1">Tanggal KMB</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Sub Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
                        $pembelian_total=0;
				foreach ($datareport->data as $row) {
					$sales=0;
    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->gudangtujuan."</td>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "<td class='left px-1'>".$row->tanggalkmb."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";
                        $pembelian_total+=$row->subtotal;
				}
			?>
		</tbody>
		<tfoot>
			<?


							echo "<tr>";
    					echo "<td class='left px-1' colspan=4>Total Harga COGS</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";
			?>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>




<?php
$query =
	"SELECT a.sunotransaksi 'nokmb', a.sutanggal 'tanggalkmb',
	sum(sdkeluar) 'qty', sum(icogs * sdkeluar) 'subtotal'
                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN bgudang b      on b.gid=a.sucabang
                    LEFT JOIN bgudang gkmb   on gkmb.gid=a.SUGUDANGTUJUAN
                    LEFT JOIN bnamapt      on npid=b.gpt
                    WHERE a.susumber = 'APTK'
                        AND a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND b.gpt='" . $idpt . "'";
}

$query .= " GROUP BY a.sunotransaksi,a.sutanggal ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>




<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Penjualan Apotik</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table"  >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">No Transaksi</th>
				<th class="left px-1">Tanggal Transaksi</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Sub Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
                        $pembelian_total=0;
				foreach ($datareport->data as $row) {
					$sales=0;
    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "<td class='left px-1'>".$row->tanggalkmb."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";
                        $pembelian_total+=$row->subtotal;
				}
			?>
		</tbody>
		<tfoot>
			<?


							echo "<tr>";
    					echo "<td class='left px-1' colspan=3>Total Harga COGS</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";
			?>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>



<pagebreak />




<?php
$query =
	"SELECT a.sunotransaksi 'nokmb', a.sutanggal 'tanggalkmb',
	sum(sdkeluar) 'qty', sum(icogs * sdkeluar) 'subtotal', gkmb.gkode 'gudangtujuan'
                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN bgudang b      on b.gid=a.sucabang
                    LEFT JOIN bgudang gkmb   on gkmb.gid=a.SUGUDANGTUJUAN
                    LEFT JOIN bnamapt      on npid=b.gpt
                    WHERE a.susumber = 'SJ'
                        AND a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND b.gpt='" . $idpt . "'";
}

$query .= " GROUP BY gkmb.gkode,a.sunotransaksi,a.sutanggal ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>



<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Penjualan DEPO</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table"  >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Gudang Tujuan</th>
				<th class="left px-1">No KMB</th>
				<th class="left px-1">Tanggal KMB</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Sub Total</th>
			</tr>
		</thead>
		<tbody>
			<?
                        $pembelian_total=0;$nilaijual_sj_total=0;
				foreach ($datareport->data as $row) {
					$sales=0;
    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->gudangtujuan."</td>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "<td class='left px-1'>".$row->tanggalkmb."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";
                        $pembelian_total+=$row->subtotal;
                        $nilaijual_sj_total+=$row->subtotal;
				}
			?>
		</tbody>
		<tfoot>
			<?
							echo "<tr>";
    					echo "<td class='left px-1' colspan=4>Total Harga COGS</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";
			?>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>

<pagebreak />



<?php
$query =
	"SELECT a.sunotransaksi 'nokmb', a.sutanggal 'tanggalkmb',
	sum(sdmasuk) 'qtymasuk', sum(icogs * sdmasuk) 'nilaimasuk', sum(sdkeluar) 'qtykeluar', sum(icogs * sdkeluar) 'nilaikeluar'
                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN bgudang b      on b.gid=a.sucabang
                    LEFT JOIN bnamapt      on npid=b.gpt
                    WHERE a.susumber = 'PY'
                        AND a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND b.gpt='" . $idpt . "'";
}

$query .= " GROUP BY a.sunotransaksi,a.sutanggal ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>



<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Daftar Penyesuaian</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table"  >
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">No Transaksi</th>
				<th class="left px-1">Tanggal Transaksi</th>
				<th class="left px-1">Qty Masuk</th>
				<th class="left px-1">Nilai Masuk</th>
				<th class="left px-1">Qty Keluar</th>
				<th class="left px-1">Nilai Keluar</th>
				<th class="left px-1">Selisih</th>
			</tr>
		</thead>
		<tbody>
			<?
                        $pembelian_total=0;
				foreach ($datareport->data as $row) {
					$sales=0;
    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "<td class='left px-1'>".$row->tanggalkmb."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qtymasuk,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qtykeluar,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaikeluar,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk-$row->nilaikeluar,0)."</td>";
    					echo "</tr>";
                        $pembelian_total+=$row->nilaimasuk-$row->nilaikeluar;
				}
			?>
		</tbody>
		<tfoot>
			<?


							echo "<tr>";
    					echo "<td class='left px-1' colspan=6>Total Selisih</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";
			?>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>


<pagebreak />


<?

$totalnilaiawal = 0;

$query =
	"SELECT  npnama, gkode, ctid,ctnamabaru, sum(qty) 'qty', sum(qty*icogs) 'cogs' from
                      (
                      select
                      coabaru.ctid,npnama, gkode, coalama.ctnama as ctnamalama, coabaru.ctnama as ctnamabaru, iid, isatuan, ikode, inama, icogs ,
                      IFNULL(SUM(SDMASUK-( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) ),0) as qty

	                  from fstokd left join bitem on IID = sditem
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sdgudang
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
	                  WHERE sustatus<>9 and  SDCANCEL=0
	                  AND sutanggal < '" .
	tgl_database($date1) .
	"'";

if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " GROUP by ctid,npnama,gkode,coalama.ctnama,coabaru.ctnama, ikode, inama, iid, isatuan, icogs
            having  IFNULL(SUM(SDMASUK-( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) ),0)>0 ) a
            group by npnama, gkode, ctid,ctnamabaru

                        ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>


<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Saldo Awal Persediaan</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Nama PT</th>
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis Baru</th>
				<th class="right px-1">Qty</th>
				<th class="right px-1">Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;
				$jumlahdata=0;
				$nilaiprodukperpt=0; $nilaisalesperpt=0;
				$tanggal = ''; $gudang = ''; $namapt='';


				$sasurgical=0;$saminimally=0;$saregenerativ=0;
				$saenergy=0;$samedical=0;$saiv=0;$saskincare=0;$salab=0;

				foreach ($datareport->data as $row) {
    			    $namapt = $row->npnama ;
    					echo "<tr>";
    					echo "<td class='px-1'>".$row->npnama."</td>";
    					echo "<td class='px-1'>".$row->gkode."</td>";
    					echo "<td class='px-1'>".$row->ctnamabaru."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";
    					$tnilaiproduk += $row->cogs ;
    					$jumlahdata ++;

         			if($row->ctid==1)
            			{$sasurgical=$row->cogs;}
               		elseif($row->ctid==2)
                 	    {$saminimally=$row->cogs;}
              		elseif($row->ctid==3)
               	    	{$saregenerativ=$row->cogs;}
              		elseif($row->ctid==4)
              	    	{$saenergy=$row->cogs;}
                    elseif($row->ctid==5)
                  	    {$samedical=$row->cogs;}
                    elseif($row->ctid==6)
                    	{$saiv=$row->cogs;}
                     elseif($row->ctid==7)
                     	{$saskincare=$row->cogs;}
                      elseif($row->ctid==12)
                      	{$salab=$row->cogs;}

				}
				$totalnilaiawal=$tnilaiproduk ;
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" colspan=4>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
			</tr>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>



<?

$query =
	"SELECT  npnama, gkode, ctid, ctnamabaru, sum(nilaimasuk) 'nilaimasuk', sum(nilaikeluar) 'nilaikeluar' from
                      (
                      select
                      npnama, gkode, coalama.ctnama as ctnamalama,coabaru.ctid, coabaru.ctnama as ctnamabaru,
                      icogs * sdmasuk   'nilaimasuk'  ,
                      icogs*( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) 'nilaikeluar'

	                  from fstokd left join bitem on IID = sditem
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sdgudang
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
	                  WHERE sustatus<>9 and  SDCANCEL=0 and
							sutanggal BETWEEN '" .
							tgl_database($date1) .
							"' AND '" .
							tgl_database($date2) .
							"' ";

						if ($idgudang != "") {
							$query .= " AND sucabang='" . $idgudang . "'";
						}
						if ($idpt != "") {
							$query .= " AND gpt='" . $idpt . "'";
						}

						$query .= "
                        ) a
                       group by npnama, gkode,ctid, ctnamabaru

                        ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>




<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Mutasi</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Nama PT</th>
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis Baru</th>
				<th class="left px-1">Nilai Masuk</th>
				<th class="right px-1">Nilai Keluar</th>
				<th class="right px-1">Update Stok</th>
			</tr>
		</thead>
		<tbody>
			<?
		 $masuk=0;$keluar=0;
			$mssurgical=0;$msminimally=0;$msregenerativ=0;
			$msenergy=0;$msmedical=0;$msiv=0;$msskincare=0;$mslab=0;

				foreach ($datareport->data as $row) {
    			    $namapt = $row->npnama ;
    					echo "<tr>";
    					echo "<td class='px-1'>".$row->npnama."</td>";
    					echo "<td class='px-1'>".$row->gkode."</td>";
    					echo "<td class='px-1'>".$row->ctnamabaru."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaikeluar,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk-$row->nilaikeluar,2)."</td>";
    					echo "</tr>";

         			$masuk+=$row->nilaimasuk;
                    $keluar+=$row->nilaikeluar;

                   	if($row->ctid==1)
                    			{$mssurgical=$row->nilaimasuk-$row->nilaikeluar;}
                       		elseif($row->ctid==2)
                         	    {$msminimally=$row->nilaimasuk-$row->nilaikeluar;}
                      		elseif($row->ctid==3)
                       	    	{$msregenerativ=$row->nilaimasuk-$row->nilaikeluar;}
                      		elseif($row->ctid==4)
                      	    	{$msenergy=$row->nilaimasuk-$row->nilaikeluar;}
                            elseif($row->ctid==5)
                          	    {$msmedical=$row->nilaimasuk-$row->nilaikeluar;}
                            elseif($row->ctid==6)
                            	{$msiv=$row->nilaimasuk-$row->nilaikeluar;}
                             elseif($row->ctid==7)
                             	{$msskincare=$row->nilaimasuk-$row->nilaikeluar;}
                              elseif($row->ctid==7)
                              	{$mslab=$row->nilaimasuk-$row->nilaikeluar;}
				}


			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" colspan=3>Total</td>
				<td class="right px-1"><?= eFormatNumber($masuk, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($keluar, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($masuk-$keluar, 2) ?></td>
			</tr>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>



<?

$query =
	"SELECT  npnama, gkode, ctnamabaru, ctid,sum(qty) 'qty', sum(qty*icogs) 'cogs' from
                      (
                      select
                      npnama, gkode, coalama.ctnama as ctnamalama,coabaru.ctid, coabaru.ctnama as ctnamabaru, iid, isatuan, ikode, inama, icogs ,
                      IFNULL(SUM(SDMASUK-( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) ),0) as qty

	                  from fstokd left join bitem on IID = sditem
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sdgudang
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
	                  WHERE sustatus<>9 and  SDCANCEL=0
	                  AND sutanggal <= '" .tgl_database($date2) ."'";

						if ($idgudang != "") {
							$query .= " AND sucabang='" . $idgudang . "'";
						}
						if ($idpt != "") {
							$query .= " AND gpt='" . $idpt . "'";
						}

						$query .= " GROUP by npnama,gkode,coalama.ctnama,coabaru.ctnama, ikode, inama, iid, isatuan, icogs
                       having  IFNULL(SUM(SDMASUK-( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) ),0)>0  ) a
                       group by npnama, gkode, ctid,ctnamabaru

                        ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>




<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Saldo Akhir</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Nama PT</th>
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis Baru</th>
				<th class="left px-1">Qty</th>
				<th class="right px-1">Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;
				$jumlahdata=0;
				$nilaiprodukperpt=0; $nilaisalesperpt=0;
				$tanggal = ''; $gudang = ''; $namapt='';
				$nilaisaldoakhirtarikan=0;


				$saksurgical=0;$sakminimally=0;$sakregenerativ=0;
				$sakenergy=0;$sakmedical=0;$skiv=0;$sakskincare=0;$saklab=0;

				foreach ($datareport->data as $row) {
    			    $namapt = $row->npnama ;
    					echo "<tr>";
    					echo "<td class='px-1'>".$row->npnama."</td>";
    					echo "<td class='px-1'>".$row->gkode."</td>";
    					echo "<td class='px-1'>".$row->ctnamabaru."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";
    					$tnilaiproduk += $row->cogs ;
    					$jumlahdata ++;


        		if($row->ctid==1)
         			{$saksurgical=$row->cogs;}
            	elseif($row->ctid==2)
              	    {$sakminimally=$row->cogs;}
           		elseif($row->ctid==3)
            	   {$sakregenerativ=$row->cogs;}
           		elseif($row->ctid==4)
           	    	{$sakenergy=$row->cogs;}
                 elseif($row->ctid==5)
               	    {$sakmedical=$row->cogs;}
                 elseif($row->ctid==6)
                 	{$sakiv=$row->cogs;}
                  elseif($row->ctid==7)
                  	{$sakskincare=$row->cogs;}
                   elseif($row->ctid==12)
                   	{$saklab=$row->cogs;}



				}

				$nilaisaldoakhirtarikan=$tnilaiproduk;
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" colspan=4>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
			</tr>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>



<pagebreak/>

<?php
$query =
	" 	select sum(nilaimasuk) 'nilaimasuk', sum(nilaikeluar) 'nilaikeluar', jenis
	from (
	SELECT  case when a.susumber = 'IP' then 'POS'
				when a.susumber = 'SJ' then 'Penjualan'
				when a.susumber = 'KMB' and gkmb.gpt<>b.gpt then 'Kirim Mutasi Barang Beda PT'
				when a.susumber = 'KMB' and gkmb.gpt=b.gpt then 'Kirim Mutasi Barang Sama PT'
				when a.susumber = 'TMB' and gtmb.gpt<>b.gpt then 'Terima Mutasi Barang Beda PT'
				when a.susumber = 'TMB' and gtmb.gpt=b.gpt then 'Terima Mutasi Barang Sama PT'
				when a.susumber = 'PY' then 'Penyesuaian Stok'
				when a.susumber = 'AL'  then 'Alkes'
				when a.susumber = 'PBC'   then 'Pembelian'
				when a.susumber = 'PB' then 'Pembelian Supplier'
				when a.susumber = 'RC' then 'Retur Pembelian'
				when a.susumber = 'APTK' then 'Apotek'
				else 'xLain' end as jenis,


                     icogs 'cogs',  case when a.susumber='PB' then (sdharga-sddiskon)*sdmasuk else icogs * sdmasuk end 'nilaimasuk'  ,
                     icogs*( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) 'nilaikeluar'

                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN fstoku tmb ON tmb.suid = a.SUPRUID
                    LEFT JOIN bgudang b      on b.gid=a.sucabang
                    LEFT JOIN bgudang gtmb   on gtmb.gid=tmb.sucabang
                    LEFT JOIN bgudang gkmb   on gkmb.gid=a.SUGUDANGTUJUAN
                    LEFT JOIN bnamapt      on npid=b.gpt

                    WHERE a.sustatus<>9 and SDCANCEL=0 and
                    a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND b.gpt='" . $idpt . "'";
}

$query .= ") aa GROUP BY jenis ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>



<pagebreak />


<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Mutasi Stok</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Jenis</th>
				<th class="left px-1">Nilai Masuk</th>
				<th class="left px-1">Nilai Keluar</th>
				<th class="left px-1">Update Nilai Stok</th>
			</tr>
		</thead>
		<tbody>
			<?

                        $masuk=0;$keluar=0;	$totalnilaiakhir=0;
                        $cogsposreal=0;$cogsalreal=0;$nilaitmbbpt=0;$nilaitmbspt=0;$nilaikmbbpt=0;$nilaikmbspt=0;
                        $nilaipy=0;$nilaipb=0;$nilairpb=0;$nilailain=0;$nilaiapotek=0;$nilaipbs=0;

				foreach ($datareport->data as $row) {


    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->jenis."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaikeluar,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk-$row->nilaikeluar,0)."</td>";
    					echo "</tr>";

                        $masuk+=$row->nilaimasuk;
                        $keluar+=$row->nilaikeluar;

                        if($row->jenis=='POS') {
                        $cogsposreal=$row->nilaimasuk-$row->nilaikeluar; }
                        elseif($row->jenis=='Alkes') {
                        $cogsalreal=$row->nilaimasuk-$row->nilaikeluar; }
                        elseif($row->jenis=='Terima Mutasi Barang Beda PT') {
                        $nilaitmbbpt=$row->nilaimasuk-$row->nilaikeluar; }
                        elseif($row->jenis=='Terima Mutasi Barang Sama PT') {
                        $nilaitmbspt=$row->nilaimasuk-$row->nilaikeluar; }
                        elseif($row->jenis=='Kirim Mutasi Barang Beda PT')
                        {$nilaikmbbpt=$row->nilaimasuk-$row->nilaikeluar;}
                        elseif($row->jenis=='Kirim Mutasi Barang Sama PT')
                        {$nilaikmbspt=$row->nilaimasuk-$row->nilaikeluar;}
                        elseif($row->jenis=='Penyesuaian Stok')
                        {$nilaipy=$row->nilaimasuk-$row->nilaikeluar;}
                        elseif($row->jenis=='Pembelian')
                        {$nilaipb=$row->nilaimasuk-$row->nilaikeluar;}
                        elseif($row->jenis=='Pembelian Supplier')
                        {$nilaipbs=$row->nilaimasuk-$row->nilaikeluar;}
                        elseif($row->jenis=='Retur Pembelian')
                        {$nilairpb=$row->nilaimasuk-$row->nilaikeluar;}
                        elseif($row->jenis=='Apotek')
                        {$nilaiapotek=$row->nilaimasuk-$row->nilaikeluar;}
                        elseif($row->jenis=='xLain')
                        {$nilailain=$row->nilaimasuk-$row->nilaikeluar;}

				}





			?>
		</tbody>
		<tfoot>

			<?
			echo "<tr>";
    					echo "<td class='left px-1'>Total</td>";
    					echo "<td class='right px-1'>".eFormatNumber($masuk,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($keluar,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($masuk-$keluar,0)."</td>";
    					echo "</tr>";

         	$totalnilaiakhir=$totalnilaiawal+$masuk-$keluar;


			echo "<tr>";
                       					echo "<td class='left px-1'>Total Nilai Awal</td>";
                       					echo "<td class='right px-1'></td>";
                       					echo "<td class='right px-1'></td>";
                       					echo "<td class='right px-1'>".eFormatNumber($totalnilaiawal,0)."</td>";
                       					echo "</tr>";

			echo "<tr>";
             					echo "<td class='left px-1'>Total Nilai Akhir Rumus</td>";
             					echo "<td class='right px-1'></td>";
             					echo "<td class='right px-1'></td>";
             					echo "<td class='right px-1'>".eFormatNumber($totalnilaiakhir,0)."</td>";
             					echo "</tr>";
                  echo "<tr>";
                           					echo "<td class='left px-1'>Total Nilai Akhir Tarik Stok</td>";
                           					echo "<td class='right px-1'></td>";
                           					echo "<td class='right px-1'></td>";
                           					echo "<td class='right px-1'>".eFormatNumber($nilaisaldoakhirtarikan,0)."</td>";
                           					echo "</tr>";

                                $nilaiselisihrumusdantarikan=$nilaisaldoakhirtarikan-$totalnilaiakhir;
                  echo "<tr>";
                           					echo "<td class='left px-1'>Selisih Nilai Akhir Rumus - Tarikan</td>";
                           					echo "<td class='right px-1'></td>";
                           					echo "<td class='right px-1'></td>";
                           					echo "<td class='right px-1'>".eFormatNumber($nilaiselisihrumusdantarikan,0)."</td>";
                           					echo "</tr>";



            ?>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>






<?php
$query =
	" SELECT  GURUTAN,GKODE,CTID,CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK,
				  sum(subtotal) as subtotal,
	              sum(ifnull(alkeshpp,0)*1.05) as alkeshpp,
				  sum(
				  case when icoa2021=1 then subtotal/1.11 else
				  sdkeluar*(cogs*1.05) end
				  ) as nilaiproduk,
				  sum(sdkeluar*cogs) as totalcogs,
				  sum(ifnull(alkeshpp,0)) as totalcogsalkes

				    from (

	                   SELECT  GURUTAN,GKODE,CTID,CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK,SDKELUAR,ICOA2021,
	                  (
						CASE WHEN ICOA2021=12 THEN
						(sdkeluar*(sdharga-sddiskon)-sdbayardp)/sutotaltransaksi*
						(sutotalkartudebit+sutotalkartukredit+sutotaltransfer+(sutotalkas-sutotalsisa)+sutotaldp+
						(sumerchantjumlah-(sumerchantjumlah*coalesce(mcbiaya,0)/100)))

						ELSE
	                  	sdkeluar*(sdharga-sddiskon)/sutotaltransaksi*
						(sutotalkartudebit+sutotalkartukredit+sutotaltransfer+(sutotalkas-sutotalsisa)+sutotaldp+
						(sumerchantjumlah-(sumerchantjumlah*coalesce(mcbiaya,0)/100)))

					END

	                  ) as subtotal,

	                   icogs as cogs,

	                  (

						 select
									SUM(  (ab.icogs) * aa.SDKELUAR )
												from
												fstokd aa inner join bitem ab on ab.iid=aa.sditem
												WHERE aa.SDIDUALKES=a.sdid

	                  ) as alkeshpp

	                  FROM fstokd a LEFT JOIN fstoku ON SUID=SDIDSU
	                  LEFT JOIN bitem ON IID=SDITEM
	                  left join bgudang on gid=sucabang
	                  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan on ctid=i2coapendapatan
	                  left join bmerchant on mckode=sumerchantjenis
	                  WHERE coalesce(sdkepalaalkes,0) =0 and SUSTATUS<>9 and SUSUMBER = 'IP'
	                  AND sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'
					   AND sdkeluar*(sdharga-sddiskon)>0
					  ";

if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
}

if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= ") a GROUP by
                    	  GURUTAN,GKODE,CTID,CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK

                    	  ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>



<pagebreak />
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Data Penjualan dan Pemecahannya</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis</th>
				<th class="left px-1">Penjualan</th>
				<th class="left px-1">Produk</th>
				<th class="left px-1">Alkes</th>
				<th class="left px-1">PPn 11%</th>
				<th class="left px-1">Pendapatan Klinik</th>
				<th class="left px-1">Total COGS Produk</th>
				<th class="left px-1">Total COGS Alkes</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0; $totalcogs=0; $totalcogsalkes =0 ;
				$nilaiproduk = 0 ; $subtotal = 0 ; $nilaialkes = 0 ; $ppn=0; $pendapatanklinik=0; $pendapatandokter=0; $alkeshpp=0;
				$tnilaiprodukip = 0 ; $tnilaisalesip = 0 ; $tnilaialkes = 0 ; $tppn=0;  $tpendapatanklinik=0; $tpendapatandokter=0; $tcogs=0; $tcogsalkes=0;

				foreach ($datareport->data as $row) {

				    $subtotal=$row->subtotal;
    			    $tnilaisalesip += $subtotal ;

				    if ($row->CTTIDAKDITARIK==0) {
    				    $alkeshpp=$row->alkeshpp;
    				    $nilaiproduk=$row->nilaiproduk;
    				    $totalcogs=$row->totalcogs;
    				    $totalcogsalkes=$row->totalcogsalkes;
    					$ppn = ($nilaiproduk+$alkeshpp)*11/100;
				    }
						$pendapatanklinik = ($subtotal-$nilaiproduk-$alkeshpp-$ppn)  ;
    					$pendapatandokter = 0 ;

    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->GKODE."</td>";
    					echo "<td class='left px-1'>".$row->CTNAMA."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiproduk,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($alkeshpp,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($ppn,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pendapatanklinik,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($totalcogs,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($totalcogsalkes,2)."</td>";
    					echo "</tr>";

    					$tnilaiprodukip += $nilaiproduk;
    					$tnilaialkes += $alkeshpp ;
    					$tppn += $ppn ;
    					$tpendapatanklinik += $pendapatanklinik;
    					$tpendapatandokter += $pendapatandokter;
						$tcogs+= $totalcogs;
						$tcogsalkes+= $totalcogsalkes;


				$nilaiproduk = 0 ; $subtotal = 0 ; $nilaialkes = 0 ; $ppn=0; $pendapatanklinik=0; $pendapatandokter=0; $alkeshpp=0;
				$totalcogs=0; $totalcogsalkes=0;


				}


			?>
		</tbody>
		<tfoot>

			<tr>
				<td class="px-1" colspan=2>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisalesip, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tnilaiprodukip, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tnilaialkes, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tppn, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tpendapatanklinik, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tcogs, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tcogsalkes, 2) ?></td>
			</tr>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>


<?php



                       $query =
                       	"  SELECT gkode,sum(sutotaltransaksi) 'totaltransaksi',
                            sum(sutotalkartudebit) 'debit',
                            sum(sutotalkartukredit) 'kredit',
                            sum(sutotaltransfer) 'transfer',
                            sum(sumerchantjumlah-(sumerchantjumlah*coalesce(mcbiaya,0)/100))  'merchant',
                            sum(sumerchantjumlah*coalesce(mcbiaya,0)/100)  'merchantbiaya',
                            sum(sutotalkas-sutotalsisa) 'kas',
                            sum(sutotaldp) 'dp',
                            sum(supendapatandp) 'pendapatandp',
                            sum(sunilaipiutang) 'nilaipiutang',
                            sum(susurgerydppembayaran) 'surgerydppembayaran'
	                  FROM  fstoku
	                  left join bgudang on gid=sucabang
	                  left join bnamapt on npid=gpt
	                  left join bmerchant on mckode=sumerchantjenis
	                  WHERE  SUSTATUS<>9 and SUSUMBER = 'IP'
	                  AND sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'

					  ";

                       if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
                       }

                       if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
                       }

                       $query .= " group by gkode  ";



    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);






?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>
	<h3>Rekapan Pendapatan Per Jenis Bayar</h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
	<table class="table" style="width:50%;">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Debit</th>
				<th class="left px-1">Kredit</th>
				<th class="left px-1">Transfer</th>
				<th class="left px-1">Kas</th>
				<th class="left px-1">Merchant</th>
				<th class="left px-1">Total Pembayaran</th>
				<th class="left px-1">Tarik DP</th>
				<th class="left px-1">Total Dengan DP</th>
			</tr>
		</thead>
		<tbody>
			<?

				foreach ($datareport->data as $row) {

					$totalbayar=0;
					$totalbayartanpadp=$row->debit+$row->kredit+$row->transfer+$row->kas+$row->merchant;
					$totalbayar=$row->debit+$row->kredit+$row->transfer+$row->kas+$row->merchant+$row->dp;

	 				echo "<tr>";
   					echo "<td class='left px-1'>".$row->gkode."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->debit,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->kredit,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->transfer,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->kas,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->merchant,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($totalbayartanpadp,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->dp,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($totalbayar,2)."</td>";
					echo "</tr>";

										echo "<tr>";
										echo "<td class='left px-1'>".$row->gkode."</td>";
   					echo "<td class='left px-1' colspan=7>Piutang</td>";
        			echo "<td class='right px-1'>".eFormatNumber($row->nilaipiutang,2)."</td>";
           			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
   					echo "<td class='left px-1' colspan=7>DP Surgery</td>";
        			echo "<td class='right px-1'>".eFormatNumber($row->surgerydppembayaran,2)."</td>";
           			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
                 					echo "<td class='left px-1' colspan=7>Biaya Merchant</td>";
                      			echo "<td class='right px-1'>".eFormatNumber($row->merchantbiaya,2)."</td>";
                         			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
                 					echo "<td class='left px-1' colspan=7>Total Transaksi</td>";
                      			echo "<td class='right px-1'>".eFormatNumber($row->totaltransaksi,2)."</td>";
                         			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
   					echo "<td class='left px-1' colspan=7>Selisih</td>";
        			echo "<td class='right px-1'>".eFormatNumber($totalbayar+$row->nilaipiutang+$row->surgerydppembayaran + $row->merchantbiaya - $row->totaltransaksi,2)."</td>";
           			echo "</tr>";
				}


			?>
		</tbody>
		<tfoot>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>



<?php
$query =
	"SELECT npnama,  gkode,
					    ik2kode ,
	                   sum(icogs*detail.sdkeluar) as cogs ,
                       sum(detail.sdkeluar) as qty


	                  from fstokd detail left join bitem on IID = detail.sditem
                      left join fstoku utama on utama.suid=sdidsu
                      left join fstoku tindakan on tindakan.suid=utama.SUIDUALKES
                      left join fstokd tindakandetail on detail.SDIDUALKES=tindakandetail.sdid
	                  left join bgudang on gid=detail.sdgudang
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  WHERE tindakan.sustatus<>9 and (
                      (utama.susumber = 'AL' and  (tindakandetail.sdkeluar*(tindakandetail.sdharga-tindakandetail.sddiskon)) <> 0)
                      )
	                  AND utama.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'

                      ";

if ($idgudang != "") {
	$query .= " AND utama.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " GROUP by npnama,gkode,  ik2kode   ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>



<pagebreak />

<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Rincian COGS Alkes</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>


<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan='4'>COGS Alkes Tindakan Clinic Harga Tindakan > 0</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th>
				<th class="left px-1">Kelompok</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;

				$jumlahdata=0;

				$nilaiprodukperpt=0; $nilaisalesperpt=0;

				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {

				    $subtotal=$row->qty;
                    $nilaiproduk=$row->cogs;

    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaisalesperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiprodukperpt,2)."</td>";
    					echo "</tr>";

						$nilaiprodukperpt=0; $nilaisalesperpt=0;
    			   }

				    if ($namapt !=  $row->npnama  ) {

    			       	echo "<tr >";
    					echo "<td class='px-1' >".$row->npnama."</td>";
    					echo "</tr>";
    			   }

    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >";
    					echo "<td class='px-2' >".$row->gkode."</td>";
    					echo "</tr>";
    			   }

    			    $gudang = $row->gkode ;
    			    $namapt = $row->npnama ;

    					echo "<tr>";
    					echo "<td class='px-3'>-</td>";
    					echo "<td class='px-3'>".$row->ik2kode."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";

    					$tnilaiproduk += $row->cogs ;
    			    	$tnilaisales += $row->qty ;

    			        $nilaiprodukperpt += $row->cogs;
    					$nilaisalesperpt += $row->qty ;

    					$jumlahdata ++;




				}


			?>
		</tbody>
		<tfoot>

			<tr>
				<td class="px-1" colspan=2>Total</td>
				<td class="right px-3"><?= eFormatNumber($tnilaisales, 2) ?></td>
				<td class="right px-3"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
			</tr>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>




<?php
$query =
	"SELECT npnama,  gkode,
					   coalama.ctnama as  ik2kode ,
	                   sum(a.icogs*detail.sdkeluar) as cogs ,
	                   sum((a.icogs*detail.sdkeluar)*11/100) as ppn ,
                       sum(detail.sdkeluar) as qty


	                  from fstokd detail left join bitem a on a.IID = detail.sditem
                      left join fstoku utama on utama.suid=sdidsu
                      left join fstoku tindakan on tindakan.suid=utama.SUIDUALKES
                      left join fstokd tindakandetail on detail.SDIDUALKES=tindakandetail.sdid
	                  left join bgudang on gid=detail.sdgudang
	                  left join bitem2 on i2iditem=a.iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
                      left join bitemkelompok2020 on ik2id=a.ikelompok2020
					  left join bnamapt on npid=gpt
					  left join bitem b on b.iid=tindakandetail.sditem
                      left join bcoatipe_perpt coalama on coalama.ctid=b.icoa2021
	                  WHERE tindakan.sustatus<>9
					  and (
                      (utama.susumber = 'AL' and  (tindakandetail.sdkeluar*(tindakandetail.sdharga-tindakandetail.sddiskon)) =0)
                      )
	                  AND utama.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'

                      ";

if ($idgudang != "") {
	$query .= " AND utama.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " GROUP by npnama,gkode,  coalama.ctnama   ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan='5'>COGS Alkes Tindakan Clinic Harga Tindakan = 0</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th>
				<th class="left px-1">Kelompok</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Total COGS</th>
				<th class="left px-1">PPN 11%</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;  $tnilaippn=0;

				$jumlahdata=0;

				$nilaiprodukperpt=0; $nilaisalesperpt=0; $nilaippnperpt=0;

				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {

				    $subtotal=$row->qty;
                    $nilaiproduk=$row->cogs;

    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaisalesperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiprodukperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaippnperpt,2)."</td>";
    					echo "</tr>";

						$nilaiprodukperpt=0; $nilaisalesperpt=0; $nilaippnperpt=0;
    			   }

				    if ($namapt !=  $row->npnama  ) {

    			       	echo "<tr >";
    					echo "<td class='px-1' >".$row->npnama."</td>";
    					echo "</tr>";
    			   }

    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >";
    					echo "<td class='px-2' >".$row->gkode."</td>";
    					echo "</tr>";
    			   }

    			    $gudang = $row->gkode ;
    			    $namapt = $row->npnama ;

    					echo "<tr>";
    					echo "<td class='px-3'>-</td>";
    					echo "<td class='px-3'>".$row->ik2kode."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->ppn,2)."</td>";
    					echo "</tr>";

    					$tnilaiproduk += $row->cogs ;
    			    	$tnilaisales += $row->qty ;
    			    	$tnilaippn += $row->ppn ;

    			        $nilaiprodukperpt += $row->cogs;
    					$nilaisalesperpt += $row->qty ;
    					$nilaippnperpt += $row->ppn ;

    					$jumlahdata ++;




				}


			?>
		</tbody>
		<tfoot>

			<tr>
				<td class="px-1" colspan=2>Total</td>
				<td class="right px-3"><?= eFormatNumber($tnilaisales, 2) ?></td>
				<td class="right px-3"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
				<td class="right px-3"><?= eFormatNumber($tnilaippn, 2) ?></td>
			</tr>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>


<?php
$query =
	"SELECT npnama,  gkode, tindakan.sunotransaksi 'notindakan',  b.ikode 'kodetindakan',
					   coalama.ctnama as  ik2kode ,
	                   sum(a.icogs*detail.sdkeluar) as cogs ,
	                   sum((a.icogs*detail.sdkeluar)*11/100) as ppn ,
                       sum(detail.sdkeluar) as qty


	                  from fstokd detail left join bitem a on a.IID = detail.sditem
                      left join fstoku utama on utama.suid=sdidsu
                      left join fstoku tindakan on tindakan.suid=utama.SUIDUALKES
                      left join fstokd tindakandetail on detail.SDIDUALKES=tindakandetail.sdid
	                  left join bgudang on gid=detail.sdgudang
	                  left join bitem2 on i2iditem=a.iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
                      left join bitemkelompok2020 on ik2id=a.ikelompok2020
					  left join bnamapt on npid=gpt
					  left join bitem b on b.iid=tindakandetail.sditem
                      left join bcoatipe_perpt coalama on coalama.ctid=b.icoa2021
	                  WHERE tindakan.sustatus<>9
					  and (
                      (utama.susumber = 'AL' and  (tindakandetail.sdkeluar*(tindakandetail.sdharga-tindakandetail.sddiskon)) =0)
                      )
	                  AND utama.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'

                      ";

if ($idgudang != "") {
	$query .= " AND utama.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " GROUP by npnama,gkode,  coalama.ctnama ,  tindakan.sunotransaksi  ,  b.ikode  ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan='5'>Detail COGS Alkes Tindakan Clinic Harga Tindakan = 0</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th>
				<th class="left px-1">Kelompok</th>
				<th class="left px-1">No Tindakan</th>
				<th class="left px-1">Nama Tindakan</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Total COGS</th>
				<th class="left px-1">PPN 11%</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;  $tnilaippn=0;

				$jumlahdata=0;

				$nilaiprodukperpt=0; $nilaisalesperpt=0; $nilaippnperpt=0;

				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {

				    $subtotal=$row->qty;
                    $nilaiproduk=$row->cogs;

    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=4>Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaisalesperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiprodukperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaippnperpt,2)."</td>";
    					echo "</tr>";

						$nilaiprodukperpt=0; $nilaisalesperpt=0; $nilaippnperpt=0;
    			   }

				    if ($namapt !=  $row->npnama  ) {

    			       	echo "<tr >";
    					echo "<td class='px-1' >".$row->npnama."</td>";
    					echo "</tr>";
    			   }

    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >";
    					echo "<td class='px-2' >".$row->gkode."</td>";
    					echo "</tr>";
    			   }

    			    $gudang = $row->gkode ;
    			    $namapt = $row->npnama ;

    					echo "<tr>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>".$row->ik2kode."</td>";
    					echo "<td class='px-1'>".$row->notindakan."</td>";
    					echo "<td class='px-1'>".$row->kodetindakan."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->ppn,2)."</td>";
    					echo "</tr>";

    					$tnilaiproduk += $row->cogs ;
    			    	$tnilaisales += $row->qty ;
    			    	$tnilaippn += $row->ppn ;

    			        $nilaiprodukperpt += $row->cogs;
    					$nilaisalesperpt += $row->qty ;
    					$nilaippnperpt += $row->ppn ;

    					$jumlahdata ++;




				}


			?>
		</tbody>
		<tfoot>

			<tr>
				<td class="px-1" colspan=4>Total</td>
				<td class="right px-3"><?= eFormatNumber($tnilaisales, 2) ?></td>
				<td class="right px-3"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
				<td class="right px-3"><?= eFormatNumber($tnilaippn, 2) ?></td>
			</tr>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>


<pagebreak />

<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Rekapitulasi Transaksi Nilai Persediaan</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>

<div class="content-report">
	<table class="table" style="width:50%;">
		<thead>
			<tr class="bg-dark">
				<td class="left px-1"  >Penjualan</td>
				<td class="right px-1"  >Nilai</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="left px-1">Penjualan Tindakan</td>
				<td class="right px-1"><?= eFormatNumber($tpendapatanklinik, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Penjualan Produk</td>
				<td class="right px-1"><?= eFormatNumber($tnilaiprodukip, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Penjualan Alkes</td>
				<td class="right px-1"><?= eFormatNumber($tnilaialkes, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">PPN 11%</td>
				<td class="right px-1"><?= eFormatNumber($tppn, 2) ?></td>
			</tr>
			<tr>
			 	<td class="left px-1" style="border-top: 1px solid #000;">Total Penjualan</td>
				<td class="right px-1" style="border-top: 1px solid #000;"><?=  eFormatNumber(
    	$tnilaisalesip,
    	2,
    ) ?></td>
			</tr>
			<tr class="bg-dark">
				<td class="left px-1  py-1" >Rincian COGS</td>
				<td class="right px-1  py-1">Nilai</td>
			</tr>
			<tr>
				<td class="left px-1 ">COGS Produk</td>
				<td class="right px-1 "><?= eFormatNumber($cogsposreal*-1, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">COGS Alkes</td>
				<td class="right px-1"><?= eFormatNumber($cogsalreal*-1, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">COGS Apotek</td>
				<td class="right px-1"><?= eFormatNumber($nilaiapotek*-1, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Selisih Penyesuaian</td>
				<td class="right px-1"><?= eFormatNumber($nilaipy*-1, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Selisih Nilai Persediaan</td>
				<td class="right px-1"><?= eFormatNumber(
    	$nilaiselisihrumusdantarikan*-1,
    	2,
    ) ?></td>
			</tr>
			<?
			$totalcogs=($cogsposreal+$cogsalreal+$nilaiapotek+$nilaipy+$nilaiselisihrumusdantarikan)*-1;
			?>
			<tr>
				<td class="left px-1" style="border-top: 1px solid #000;">Total COGS</td>
				<td class="right px-1" style="border-top: 1px solid #000;"><?= eFormatNumber($totalcogs, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Persentase Total COGS Terhadap Total Penjualan</td>
				<td class="right px-1"><?= $tnilaisalesip > 0 ? eFormatNumber(
    	$totalcogs / $tnilaisalesip * 100 ,
    	2,
    ) : 0 ?> % </td>
			</tr>
			<tr class="bg-dark">
				<td class="left px-1  py-1" >Selisih Nilai Persediaan</td>
				<td class="right px-1  py-1">Nilai</td>
			</tr>
			<tr>
				<td class="left px-1  ">Persediaan Akhir dari Tarik Data</td>
				<td class="right px-1 "><?= eFormatNumber($nilaisaldoakhirtarikan, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Persediaan Akhir Dari Rumus</td>
				<td class="right px-1"><?= eFormatNumber($totalnilaiakhir, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1" style="border-top: 1px solid #000;">Selisih Nilai Persediaan</td>
				<td class="right px-1" style="border-top: 1px solid #000;"><?= eFormatNumber(
    	$nilaisaldoakhirtarikan-$totalnilaiakhir ,
    	2,
    ) ?></td>
			</tr>
			<tr>
				<td class="left px-1  py-2">Penjualan Mutasi Antar PT</td>
				<td class="right px-1  py-2"><?= eFormatNumber($nilaijual_kmb_total, 2) ?></td>
			</tr>


						<tr>
							<td class="left px-1">Penjualan DEPO</td>
							<td class="right px-1"><?= eFormatNumber($nilaijual_sj_total, 2) ?></td>
						</tr>

			<tr class="bg-dark">
				<td class="left px-1  py-1" >COGS</td>
				<td class="right px-1  py-1">Nilai</td>
			</tr>
			<tr>
				<td class="left px-1  ">Nilai Awal Persediaan</td>
				<td class="right px-1  "><?= eFormatNumber($totalnilaiawal, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Pembelian</td>
				<td class="right px-1"><?= eFormatNumber($nilaipb,2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Pembelian Supplier</td>
				<td class="right px-1"><?= eFormatNumber($nilaipbs,2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Pembelian Mutasi Antar PT</td>
				<td class="right px-1"><?= eFormatNumber($nilaitmbbpt,2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Pembelian Mutasi 1 PT</td>
				<td class="right px-1"><?= eFormatNumber($nilaitmbspt,2) ?></td>
			</tr>


			<tr>
				<td class="left px-1">Penjualan Mutasi Antar PT</td>
				<td class="right px-1"><?= eFormatNumber($nilaikmbbpt, 2) ?></td>
			</tr>



			<tr>
				<td class="left px-1">Penjualan Mutasi 1 PT</td>
				<td class="right px-1"><?= eFormatNumber($nilaikmbspt, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Retur Pembelian</td>
				<td class="right px-1"><?= eFormatNumber($nilairpb, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Lain</td>
				<td class="right px-1"><?= eFormatNumber($nilailain, 2) ?></td>
			</tr>
		 <? $nilaistoksiapdijual=$totalnilaiawal+$nilaipb+$nilaitmbbpt+$nilaitmbspt+$nilaikmbbpt+$nilaikmbspt+$nilairpb+$nilailain+$nilaipbs;
           ?>
			<tr>
				<td class="left px-1" style="border-top: 1px solid #000;">PERSEDIAAN TERSEDIA U DIJUAL</td>
				<td class="right px-1" style="border-top: 1px solid #000;"><?= eFormatNumber($nilaistoksiapdijual, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1">Nilai Akhir Tarikan</td>
				<td class="right px-1"><?= eFormatNumber($nilaisaldoakhirtarikan, 2) ?></td>
			</tr>
			<tr>
				<td class="left px-1" style="border-top: 1px solid #000;">COGS (Produk, Alkes & Apotek)</td>
				<td class="right px-1" style="border-top: 1px solid #000;"><?= eFormatNumber(
    	$nilaistoksiapdijual - $nilaisaldoakhirtarikan,
    	2,
    ) ?></td>
			</tr>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>




<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Rekap Mutasi Stok</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Nama PT</th>
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis Baru</th>
				<th class="left px-1">Saldo Awal</th>
				<th class="left px-1">Masuk-Keluar</th>
				<th class="left px-1">SA+Masuk-Keluar</th>
				<th class="left px-1">Saldo akhir Tarikan</th>
				<th class="left px-1">Selisih</th>
			</tr>
		</thead>
		<tbody>
			<?


    					echo "<tr>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>Surgical</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sasurgical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($mssurgical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sasurgical+$mssurgical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saksurgical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saksurgical-($sasurgical+$mssurgical),2)."</td>";
    					echo "</tr>";

        				echo "<tr>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>Minimally</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saminimally,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($msminimally,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saminimally+$msminimally,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakminimally,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakminimally-($saminimally+$msminimally),2)."</td>";
    					echo "</tr>";

        				echo "<tr>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>Regenerative</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saregenerativ,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($msregenerativ,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saregenerativ+$msregenerativ,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakregenerativ,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakregenerativ-($saregenerativ+$msregenerativ),2)."</td>";
    					echo "</tr>";

        				echo "<tr>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>Energy-Based</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saenergy,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($msenergy,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saenergy+$msenergy,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakenergy,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakenergy-($saenergy+$msenergy),2)."</td>";
    					echo "</tr>";

        				echo "<tr>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>Medical Weight</td>";
    					echo "<td class='right px-1'>".eFormatNumber($samedical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($msmedical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($samedical+$msmedical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakmedical,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakmedical-($samedical+$msmedical),2)."</td>";
    					echo "</tr>";

        				echo "<tr>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>-</td>";
    					echo "<td class='px-1'>IV & Wellness</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saiv,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($msiv,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($saiv+$msiv,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakiv,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($sakiv-($saiv+$msiv),2)."</td>";
    					echo "</tr>";

            			echo "<tr>";
       					echo "<td class='px-1'>-</td>";
       					echo "<td class='px-1'>-</td>";
       					echo "<td class='px-1'>Skincare & Retail</td>";
       					echo "<td class='right px-1'>".eFormatNumber($saskincare,2)."</td>";
       					echo "<td class='right px-1'>".eFormatNumber($msskincare,2)."</td>";
       					echo "<td class='right px-1'>".eFormatNumber($saskincare+$msskincare,2)."</td>";
       					echo "<td class='right px-1'>".eFormatNumber($sakskincare,2)."</td>";
       					echo "<td class='right px-1'>".eFormatNumber($sakskincare-($saskincare+$msskincare),2)."</td>";
       					echo "</tr>";

                        			echo "<tr>";
                   					echo "<td class='px-1'>-</td>";
                   					echo "<td class='px-1'>-</td>";
                   					echo "<td class='px-1'>Lab</td>";
                   					echo "<td class='right px-1'>".eFormatNumber($salab,2)."</td>";
                   					echo "<td class='right px-1'>".eFormatNumber($mslab,2)."</td>";
                   					echo "<td class='right px-1'>".eFormatNumber($salab+$mslab,2)."</td>";
                   					echo "<td class='right px-1'>".eFormatNumber($saklab,2)."</td>";
                   					echo "<td class='right px-1'>".eFormatNumber($saklab-($salab+$mslab),2)."</td>";
                   					echo "</tr>";

                        $totalsurgical=0;

                       	$totalawal=$sasurgical+$saminimally+$saregenerativ+
                        			$saenergy+$samedical+$saiv+$saskincare+$salab;
                        $totalms=$mssurgical+$msminimally+$msregenerativ+
                        			$msenergy+$msmedical+$msiv+$msskincare+$mslab;
                        $totalakhir=$saksurgical+$sakminimally+$sakregenerativ+
                        			$sakenergy+$sakmedical+$sakiv+$sakskincare+$saklab;
                        $totalrumus=$totalawal+$totalms;
                        $totalselisih=$totalakhir-$totalrumus;


			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" >Total</td>
				<td class="px-1" ></td>
				<td class="px-1" ></td>
				<td class="right px-1"><?= eFormatNumber($totalawal, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($totalms, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($totalrumus, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($totalakhir, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($totalselisih, 2) ?></td>
			</tr>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>
