<?php
include "style.php";

$date1 = $_POST["tgldari"];
$date2 = $_POST["tglsampai"];
$idgudang = isset($_POST["gudang"]) ? $_POST["gudang"] : "";
$idpt = isset($_POST["namapt"]) ? $_POST["namapt"] : "";

$tglAwal = tgl_database($date1);
$tglAkhir = tgl_database($date2);

$CI = &get_instance();
$CI->load->model('M_Fina_Hpp');

$namapt = $idpt;
$namacabang = $idgudang;

if ($idpt != "") {
	$q = $CI->M_transaksi->get_data_query("SELECT npnama 'nama' FROM bnamapt WHERE npid='" . $CI->db->escape_str($idpt) . "'");
	$q = json_decode($q);
	if (!empty($q->data)) { $namapt = $q->data[0]->nama; }
}
if ($idgudang != "") {
	$q = $CI->M_transaksi->get_data_query("SELECT gnama 'nama' FROM bgudang WHERE gid='" . $CI->db->escape_str($idgudang) . "'");
	$q = json_decode($q);
	if (!empty($q->data)) { $namacabang = $q->data[0]->nama; }
}

$hasil = array();
if ($idpt != "" && $idgudang != "" && $tglAwal != "" && $tglAkhir != "") {
	$hasil = $CI->M_Fina_Hpp->hitungFifo($idpt, $idgudang, $tglAwal, $tglAkhir);
	$hasil = array_values($hasil);
	usort($hasil, function ($a, $b) { return strcmp($a['ikode'], $b['ikode']); });
}

$totalQty = 0;
$totalHpp = 0;
foreach ($hasil as $h) {
	$totalQty += $h['qtyterjual'];
	$totalHpp += $h['totalhpp'];
}
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3><?= $title ?></h3>
	<span>PT : <?= $namapt ?> &nbsp; | &nbsp; Cabang : <?= $namacabang ?></span><br>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?> &nbsp; (Metode FIFO)</span>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Uraian</th>
				<th class="left px-1">No Transaksi</th>
				<th class="left px-1">Tanggal</th>
				<th class="left px-1">Sumber</th>
				<th class="left px-1">No PRO Asal</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Harga / HPP Satuan</th>
				<th class="left px-1">Total HPP</th>
			</tr>
		</thead>
		<tbody>
			<?
			if (empty($hasil)) {
				echo '<tr><td class="px-1" colspan="8">Tidak ada data penjualan pada periode/filter ini (PT, Cabang, dan Tanggal wajib diisi).</td></tr>';
			}

			foreach ($hasil as $h) {
				$hpprata2 = ($h['qtyterjual'] > 0) ? ($h['totalhpp'] / $h['qtyterjual']) : 0;

				echo '<tr class="bg-light">';
				echo '<td class="left px-1"><b>[' . $h['ikode'] . '] ' . $h['inama'] . '</b></td>';
				echo '<td class="left px-1"></td>';
				echo '<td class="left px-1"></td>';
				echo '<td class="left px-1"></td>';
				echo '<td class="left px-1"></td>';
				echo '<td class="right px-1"><b>' . eFormatNumber($h['qtyterjual'], 2) . '</b></td>';
				echo '<td class="right px-1"><b>' . eFormatNumber($hpprata2, 2) . '</b></td>';
				echo '<td class="right px-1"><b>' . eFormatNumber($h['totalhpp'], 2) . '</b></td>';
				echo '</tr>';

				foreach ($h['detail'] as $d) {
					echo '<tr>';
					echo '<td class="left px-1">&nbsp;&nbsp;&nbsp;&nbsp;Transaksi Penjualan</td>';
					echo '<td class="left px-1">' . $d['notransaksi'] . '</td>';
					echo '<td class="left px-1">' . $d['tanggal'] . '</td>';
					echo '<td class="left px-1">' . $d['susumber'] . '</td>';
					echo '<td class="left px-1"></td>';
					echo '<td class="right px-1">' . eFormatNumber($d['qty'], 2) . '</td>';
					echo '<td class="right px-1">' . eFormatNumber($d['hppsatuan'], 2) . '</td>';
					echo '<td class="right px-1">' . eFormatNumber($d['hpp'], 2) . '</td>';
					echo '</tr>';

					foreach ($d['asal'] as $a) {
						$sumberTeks = ($a['susumber'] === 'KOSONG') ? 'Stok kosong (HPP dianggap Rp 0)' : $a['susumber'];
						echo '<tr>';
						echo '<td class="left px-1 text-gray">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sumber Lapisan Stok</td>';
						echo '<td class="left px-1 text-gray">' . $a['notransaksi'] . '</td>';
						echo '<td class="left px-1 text-gray">' . $a['tanggal'] . '</td>';
						echo '<td class="left px-1 text-gray">' . $sumberTeks . '</td>';
						echo '<td class="left px-1 text-gray">' . ($a['pronotransaksi'] ? $a['pronotransaksi'] : '-') . '</td>';
						echo '<td class="right px-1 text-gray">' . eFormatNumber($a['qty'], 2) . '</td>';
						echo '<td class="right px-1 text-gray">' . eFormatNumber($a['harga'], 2) . '</td>';
						echo '<td class="right px-1 text-gray">' . eFormatNumber($a['qty'] * $a['harga'], 2) . '</td>';
						echo '</tr>';
					}
				}
			}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" colspan="5">Total</td>
				<td class="right px-1"><?= eFormatNumber($totalQty, 2) ?></td>
				<td class="right px-1"></td>
				<td class="right px-1"><?= eFormatNumber($totalHpp, 2) ?></td>
			</tr>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>
