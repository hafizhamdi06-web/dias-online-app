<?php
include "style.php";

$date1 = $_POST["tgldari"];
$date2 = $_POST["tglsampai"];

$tglDari = tgl_database($date1);
$tglSampai = tgl_database($date2);

$CI = &get_instance();
$CI->load->model('M_Fina_HppRii');

$pt = $CI->M_Fina_HppRii->getPt();

// Keterangan transaksi: dari aanomor.NKETERANGAN by NKODE, dgn cadangan utk kode yg
// dipakai di proses HPP RII tapi blm terdaftar di aanomor (PB/PRO/PL).
$keteranganSusumber = array('PB' => 'Pembelian', 'PRO' => 'Produksi', 'PL' => 'Pemakaian Lain');
$rowsNomor = $CI->db->query("SELECT NKODE, NKETERANGAN FROM aanomor")->result();
foreach ($rowsNomor as $n) {
	$keteranganSusumber[$n->NKODE] = $n->NKETERANGAN;
}

// Saldo awal per item: akumulasi seluruh histori SEBELUM tglDari (< tglDari).
$querySaldoAwal =
	" SELECT HPIKODE 'ikode',
	         SUM(CASE WHEN HPTIPE='MASUK' THEN HPQTY ELSE -HPQTY END) 'saldoqty',
	         SUM(CASE WHEN HPTIPE='MASUK' THEN HPTOTAL ELSE -HPTOTAL END) 'saldonilai'
	    FROM fhpprii
	   WHERE HPPT = '" . $CI->db->escape_str($pt) . "'
	     AND HPTANGGAL < '" . $tglDari . "'
	GROUP BY HPIKODE";

$saldoAwalPerItem = array();
foreach ($CI->db->query($querySaldoAwal)->result() as $r) {
	$saldoAwalPerItem[$r->ikode] = array('qty' => (float) $r->saldoqty, 'nilai' => (float) $r->saldonilai);
}

// Transaksi dlm periode yg dipilih.
$query =
	" SELECT HPIKODE 'ikode', HPINAMA 'inama', HPNOTRANSAKSI 'notransaksi', HPTANGGAL 'tanggal', HPSUSUMBER 'susumber',
	         HPTIPE 'tipe', HPQTY 'qty', HPHARGA 'harga'
	    FROM fhpprii
	   WHERE HPPT = '" . $CI->db->escape_str($pt) . "'
	     AND HPTANGGAL BETWEEN '" . $tglDari . "' AND '" . $tglSampai . "'
	ORDER BY HPIKODE, HPTANGGAL, HPID";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);

$perItem = array();
foreach ($datareport->data as $row) {
	$ikode = $row->ikode;
	if (!isset($perItem[$ikode])) {
		$perItem[$ikode] = array('inama' => $row->inama, 'baris' => array());
	}

	$qty = (float) $row->qty;
	$harga = (float) $row->harga;
	$masuk = ($row->tipe === 'MASUK');
	$nilai = $qty * $harga;
	$keterangan = isset($keteranganSusumber[$row->susumber]) ? $keteranganSusumber[$row->susumber] : $row->susumber;

	$perItem[$ikode]['baris'][] = array(
		'notransaksi' => $row->notransaksi,
		'tanggal'     => $row->tanggal,
		'keterangan'  => $keterangan,
		'qtymasuk'    => $masuk ? $qty : 0,
		'hargamasuk'  => $masuk ? $harga : 0,
		'nilaimasuk'  => $masuk ? $nilai : 0,
		'qtykeluar'   => $masuk ? 0 : $qty,
		'hargakeluar' => $masuk ? 0 : $harga,
		'nilaikeluar' => $masuk ? 0 : $nilai,
	);
}

// Saldo berjalan (running balance), MULAI DARI saldo awal (bukan 0).
foreach ($perItem as $ikode => &$it) {
	$saldoQty = isset($saldoAwalPerItem[$ikode]) ? $saldoAwalPerItem[$ikode]['qty'] : 0;
	$saldoNilai = isset($saldoAwalPerItem[$ikode]) ? $saldoAwalPerItem[$ikode]['nilai'] : 0;
	$it['saldoawalqty'] = $saldoQty;
	$it['saldoawalnilai'] = $saldoNilai;
	foreach ($it['baris'] as &$b) {
		$saldoQty += $b['qtymasuk'] - $b['qtykeluar'];
		$saldoNilai += $b['nilaimasuk'] - $b['nilaikeluar'];
		$b['saldoqty'] = $saldoQty;
		$b['saldonilai'] = $saldoNilai;
	}
	unset($b);
}
unset($it);
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3><?= $title ?></h3>
	<span>PT. RII &nbsp; | &nbsp; Periode : <?= $date1 ?> s/d <?= $date2 ?> &nbsp; (Harga mengikuti hasil "Proses HPP PT. RII")</span>
</div>
<div class="content-report">
	<?
	if (empty($perItem)) {
		echo '<p>Tidak ada data pada periode ini (pastikan sudah "Proses Ulang" di menu Fina > Proses HPP PT. RII).</p>';
	}

	foreach ($perItem as $ikode => $it) {
		?>
		<table class="table" style="width:40%;">
			<tr><td class="px-1"><b>Kode Barang</b></td><td class="px-1"><?= $ikode ?></td></tr>
			<tr><td class="px-1"><b>Nama Barang</b></td><td class="px-1"><?= $it['inama'] ?></td></tr>
		</table>
		<table class="table table-border">
			<thead>
				<tr class="bg-dark">
					<th class="left px-1" rowspan="2">No Transaksi</th>
					<th class="left px-1" rowspan="2">Tanggal</th>
					<th class="left px-1" rowspan="2">Keterangan</th>
					<th class="px-1" colspan="3">MASUK</th>
					<th class="px-1" colspan="3">KELUAR</th>
					<th class="px-1" colspan="2">SALDO</th>
				</tr>
				<tr class="bg-dark">
					<th class="right px-1">Qty</th>
					<th class="right px-1">Harga</th>
					<th class="right px-1">Nilai</th>
					<th class="right px-1">Qty</th>
					<th class="right px-1">Harga</th>
					<th class="right px-1">Nilai</th>
					<th class="right px-1">Qty</th>
					<th class="right px-1">Nilai</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="px-1" colspan="9" style="text-align:center"><b>SALDO AWAL</b></td>
					<td class="right px-1"><b><?= eFormatNumber($it['saldoawalqty'], 2) ?></b></td>
					<td class="right px-1"><b><?= eFormatNumber($it['saldoawalnilai'], 2) ?></b></td>
				</tr>
				<?
				foreach ($it['baris'] as $b) {
					echo '<tr>';
					echo '<td class="left px-1">' . $b['notransaksi'] . '</td>';
					echo '<td class="left px-1">' . $b['tanggal'] . '</td>';
					echo '<td class="left px-1">' . $b['keterangan'] . '</td>';
					echo '<td class="right px-1">' . ($b['qtymasuk'] > 0 ? eFormatNumber($b['qtymasuk'], 2) : '') . '</td>';
					echo '<td class="right px-1">' . ($b['qtymasuk'] > 0 ? eFormatNumber($b['hargamasuk'], 2) : '') . '</td>';
					echo '<td class="right px-1">' . ($b['qtymasuk'] > 0 ? eFormatNumber($b['nilaimasuk'], 2) : '') . '</td>';
					echo '<td class="right px-1">' . ($b['qtykeluar'] > 0 ? eFormatNumber($b['qtykeluar'], 2) : '') . '</td>';
					echo '<td class="right px-1">' . ($b['qtykeluar'] > 0 ? eFormatNumber($b['hargakeluar'], 2) : '') . '</td>';
					echo '<td class="right px-1">' . ($b['qtykeluar'] > 0 ? eFormatNumber($b['nilaikeluar'], 2) : '') . '</td>';
					echo '<td class="right px-1">' . eFormatNumber($b['saldoqty'], 2) . '</td>';
					echo '<td class="right px-1">' . eFormatNumber($b['saldonilai'], 2) . '</td>';
					echo '</tr>';
				}
				?>
			</tbody>
		</table>
		<div class="clear">&nbsp;</div>
		<?
	}
	?>
</div>
