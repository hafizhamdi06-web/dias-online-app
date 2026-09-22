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
	$hasil = $CI->M_Fina_HppRii->laporanProduksi($tglDari, $tglSampai);
}
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3><?= $title ?></h3>
	<span>PT. RII &nbsp; | &nbsp; Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<?
	if (empty($hasil)) {
		echo '<p>Tidak ada transaksi produksi pada periode ini (pastikan sudah "Proses Ulang" di menu Fina > Proses HPP PT. RII).</p>';
	}

	foreach ($hasil as $h) {
		$tglTampil = date('d/m/Y', strtotime($h['tanggal']));
		?>
		<table class="table" style="width:40%;">
			<tr><td class="px-1"><b>No Produksi</b></td><td class="px-1"><?= $h['notransaksi'] ?></td></tr>
			<tr><td class="px-1"><b>Tanggal</b></td><td class="px-1"><?= $tglTampil ?></td></tr>
		</table>
		<table class="table table-border">
			<thead>
				<tr class="bg-dark">
					<th class="left px-1" rowspan="2">No</th>
					<th class="left px-1" rowspan="2">Kode Produk</th>
					<th class="left px-1" rowspan="2">Nama</th>
					<th class="right px-1" rowspan="2">Qty Masuk</th>
					<th class="right px-1" rowspan="2">Qty Keluar</th>
					<th class="right px-1" rowspan="2">Harga Satuan</th>
					<th class="right px-1" rowspan="2">Total</th>
					<th class="px-1" rowspan="2"></th>
					<th class="px-1" colspan="4" style="text-align:center">Sumber Harga</th>
				</tr>
				<tr class="bg-dark">
					<th class="left px-1">No Transaksi</th>
					<th class="right px-1">Qty</th>
					<th class="right px-1">Harga</th>
					<th class="right px-1">Total</th>
				</tr>
			</thead>
			<tbody>
				<?
				$noBatch = 1;
				$jumlahLangkah = count($h['langkah']);

				foreach ($h['langkah'] as $idx => $l) {
					$asal = ($l['tipe'] === 'KELUAR') ? $CI->M_Fina_HppRii->asalTransaksi($l['sdid']) : array();
					$jumlahBaris = max(1, count($asal));

					for ($baris = 0; $baris < $jumlahBaris; $baris++) {
						echo '<tr>';

						if ($baris === 0) {
							echo '<td class="left px-1">' . $noBatch . '</td>';
							echo '<td class="left px-1">' . $l['ikode'] . '</td>';
							echo '<td class="left px-1">' . $l['inama'] . '</td>';
							if ($l['tipe'] === 'MASUK') {
								echo '<td class="right px-1">' . eFormatNumber($l['qty'], 2) . '</td>';
								echo '<td class="right px-1"></td>';
							} else {
								echo '<td class="right px-1"></td>';
								echo '<td class="right px-1">' . eFormatNumber($l['qty'], 2) . '</td>';
							}
							echo '<td class="right px-1">' . ((float) $l['hppsatuan'] != 0 ? eFormatNumber($l['hppsatuan'], 2) : '-') . '</td>';
							echo '<td class="right px-1">' . ((float) $l['total'] != 0 ? eFormatNumber($l['total'], 2) : '-') . '</td>';
							echo '<td class="px-1"></td>';
						} else {
							echo '<td class="left px-1"></td><td class="left px-1"></td><td class="left px-1"></td>';
							echo '<td class="right px-1"></td><td class="right px-1"></td><td class="right px-1"></td><td class="right px-1"></td><td class="px-1"></td>';
						}

						if (isset($asal[$baris])) {
							$a = $asal[$baris];
							echo '<td class="left px-1">' . $a['notransaksi'] . '</td>';
							echo '<td class="right px-1">' . eFormatNumber($a['qty'], 2) . '</td>';
							echo '<td class="right px-1">' . eFormatNumber($a['harga'], 2) . '</td>';
							echo '<td class="right px-1">' . eFormatNumber($a['qty'] * $a['harga'], 2) . '</td>';
						} else {
							echo '<td class="left px-1"></td><td class="right px-1"></td><td class="right px-1"></td><td class="right px-1"></td>';
						}

						echo '</tr>';
					}

					$noBatch++;
					if ($l['tipe'] === 'MASUK') {
						$noBatch = 1;
						if ($idx < $jumlahLangkah - 1) {
							echo '<tr><td class="px-1" colspan="12">&nbsp;</td></tr>';
						}
					}
				}
				?>
			</tbody>
		</table>
		<div class="clear">&nbsp;</div>
		<?
	}
	?>
</div>
