<?php
include "style.php";

// Laporan saldo per SATU tanggal acuan (ARDATE2F -- pola sama dgn laporan-neraca dkk).
$tanggalAcuan = $_POST["tgl"];
$tglAkhir = tgl_database($tanggalAcuan);

$CI = &get_instance();
$CI->load->model('M_Fina_HppRii');

$pt = $CI->M_Fina_HppRii->getPt();

$query =
	" SELECT HPIKODE 'ikode', HPINAMA 'inama',
	         SUM(CASE WHEN HPTIPE='MASUK' THEN HPQTY ELSE -HPQTY END) 'qty',
	         SUM(CASE WHEN HPTIPE='MASUK' THEN HPTOTAL ELSE -HPTOTAL END) 'nilai'
	    FROM fhpprii
	   WHERE HPPT = '" . $CI->db->escape_str($pt) . "'
	     AND HPTANGGAL <= '" . $tglAkhir . "'
	GROUP BY HPIKODE, HPINAMA
	ORDER BY HPIKODE";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);

$totalQty = 0;
$totalNilai = 0;
foreach ($datareport->data as $row) {
	$totalQty += (float) $row->qty;
	$totalNilai += (float) $row->nilai;
}
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3><?= $title ?></h3>
	<span>PT. RII &nbsp; | &nbsp; Per Tanggal : <?= $tanggalAcuan ?> &nbsp; (Harga mengikuti hasil "Proses HPP PT. RII")</span>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Kode</th>
				<th class="left px-1">Nama</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Harga</th>
				<th class="left px-1">Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?
			if (empty($datareport->data)) {
				echo '<tr><td class="px-1" colspan="5">Tidak ada data s.d. tanggal ini (pastikan sudah "Proses Ulang" di menu Fina > Proses HPP PT. RII).</td></tr>';
			}

			foreach ($datareport->data as $row) {
				$qty = (float) $row->qty;
				$nilai = (float) $row->nilai;
				$harga = ($qty > 0) ? ($nilai / $qty) : 0;

				echo '<tr>';
				echo '<td class="left px-1">' . $row->ikode . '</td>';
				echo '<td class="left px-1">' . $row->inama . '</td>';
				echo '<td class="right px-1">' . eFormatNumber($qty, 2) . '</td>';
				echo '<td class="right px-1">' . eFormatNumber($harga, 2) . '</td>';
				echo '<td class="right px-1">' . eFormatNumber($nilai, 2) . '</td>';
				echo '</tr>';
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" colspan="2">Total</td>
				<td class="right px-1"><?= eFormatNumber($totalQty, 2) ?></td>
				<td class="right px-1"></td>
				<td class="right px-1"><?= eFormatNumber($totalNilai, 2) ?></td>
			</tr>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>
