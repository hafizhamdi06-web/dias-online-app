<?php
include "style.php";

$date1 = $_POST["tgldari"];
$date2 = $_POST["tglsampai"];

$tglDari = tgl_database($date1);
$tglSampai = tgl_database($date2);

$CI = &get_instance();
$CI->load->model('M_Fina_HppRii');

$hasil = array();
if ($tglDari !== '' && $tglSampai !== '') {
	$hasil = $CI->M_Fina_HppRii->laporanPeriode($tglDari, $tglSampai);
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
	<span>PT. RII &nbsp; | &nbsp; Periode : <?= $date1 ?> s/d <?= $date2 ?> &nbsp; (Metode FIFO per PT)</span>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Uraian</th>
				<th class="left px-1">No Transaksi</th>
				<th class="left px-1">Tanggal</th>
				<th class="left px-1">Sumber</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">HPP Satuan</th>
				<th class="left px-1">Total HPP</th>
			</tr>
		</thead>
		<tbody>
			<?
			if (empty($hasil)) {
				echo '<tr><td class="px-1" colspan="7">Tidak ada data pada periode ini (pastikan sudah "Proses Ulang" di menu Fina > Proses HPP PT. RII).</td></tr>';
			}

			foreach ($hasil as $h) {
				echo '<tr class="bg-light">';
				echo '<td class="left px-1"><b>[' . $h['ikode'] . '] ' . $h['inama'] . '</b></td>';
				echo '<td class="left px-1"></td>';
				echo '<td class="left px-1"></td>';
				echo '<td class="left px-1"></td>';
				echo '<td class="right px-1"><b>' . eFormatNumber($h['qtyterjual'], 2) . '</b></td>';
				echo '<td class="right px-1"><b>' . eFormatNumber($h['hpprata2'], 2) . '</b></td>';
				echo '<td class="right px-1"><b>' . eFormatNumber($h['totalhpp'], 2) . '</b></td>';
				echo '</tr>';

				$detail = $CI->M_Fina_HppRii->detailItemPeriode($h['ikode'], $tglDari, $tglSampai);
				foreach ($detail as $d) {
					echo '<tr>';
					echo '<td class="left px-1">&nbsp;&nbsp;&nbsp;&nbsp;Transaksi</td>';
					echo '<td class="left px-1">' . $d['notransaksi'] . '</td>';
					echo '<td class="left px-1">' . $d['tanggal'] . '</td>';
					echo '<td class="left px-1">' . $d['susumber'] . '</td>';
					echo '<td class="right px-1">' . eFormatNumber($d['qty'], 2) . '</td>';
					echo '<td class="right px-1">' . eFormatNumber($d['hppsatuan'], 2) . '</td>';
					echo '<td class="right px-1">' . eFormatNumber($d['hpp'], 2) . '</td>';
					echo '</tr>';

					$asal = $CI->M_Fina_HppRii->asalTransaksi($d['sdid']);
					if (empty($asal)) {
						echo '<tr>';
						echo '<td class="left px-1 text-gray" colspan="7">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Stok kosong saat transaksi ini, HPP dianggap Rp 0.</td>';
						echo '</tr>';
					}
					foreach ($asal as $a) {
						echo '<tr>';
						echo '<td class="left px-1 text-gray">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sumber (Harga Beli)</td>';
						echo '<td class="left px-1 text-gray">' . $a['notransaksi'] . '</td>';
						echo '<td class="left px-1 text-gray">' . $a['tanggal'] . '</td>';
						echo '<td class="left px-1 text-gray">' . $a['susumber'] . '</td>';
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
				<td class="px-1" colspan="4">Total</td>
				<td class="right px-1"><?= eFormatNumber($totalQty, 2) ?></td>
				<td class="right px-1"></td>
				<td class="right px-1"><?= eFormatNumber($totalHpp, 2) ?></td>
			</tr>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>
