<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.pbuid 'id',A.pbunotransaksi 'nomor',DATE_FORMAT(A.pbutanggal,'%d/%m/%Y') 'tanggal',
                      A.pbunoref 'noref', A.pbukaryawan 'idkaryawan',
                      C.knama 'karyawan', D.gnama 'cabang', D.gnamapt 'gnamapt',
                      E.gnama 'gudangtujuan', F.lnama 'tipe'
                 FROM fpermintaanbarangu A
            LEFT JOIN bkontak C ON A.pbukaryawan=C.kid
            LEFT JOIN bgudang D ON A.pbugudang=D.gid
            LEFT JOIN bgudang E ON A.pbugudangsumber=E.gid
            LEFT JOIN blain F ON A.pbutipepermintaan=F.lid
                WHERE A.pbuid = '".$id."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);

    $querydtl  = "SELECT B.inama 'item', A.pbdqty 'qty', A.pbdstok 'stokreal', C.skode 'satuan', A.pbdcatatan 'catatan'
                 FROM fpermintaanbarangd A
            LEFT JOIN bitem B ON A.pbditem = B.iid
            LEFT JOIN bsatuan C ON A.pbdsatuan=C.sid
                WHERE A.pbdidsu = '".$id."' ORDER BY A.pbdurutan ASC";

    $detil = $CI->M_transaksi->get_data_query($querydtl);
    $detil = json_decode($detil);

    $nomor = $tanggal = $noref = $karyawan = $cabang = $gudangtujuan = $tipe = '';
    $namapt = $company_name;

    foreach ($header->data as $row) {
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$noref = empty($row->noref) ? '-' : $row->noref;
    	$karyawan = $row->karyawan;
    	$cabang = $row->cabang;
    	$gudangtujuan = $row->gudangtujuan;
    	$tipe = $row->tipe;
    	if(!empty($row->gnamapt)) $namapt = $row->gnamapt;
    }

    $totalqty = 0;
?>
<style scoped>
.formulir-header {
	font-size: 11pt;
	margin-bottom: 0;
}
.formulir-header .uppercase {
	text-transform: uppercase;
}
.formulir-title {
	text-align: center;
	font-size: 13pt;
	font-weight: bold;
	margin-top: 4pt;
	margin-bottom: 10pt;
}
.formulir-info td {
	padding: 1pt 2pt;
}
.formulir-detil th {
	border-bottom: 1px solid #000;
	padding: 3pt 2pt;
}
.formulir-detil td {
	border-bottom: 1px dashed #000;
	padding: 3pt 2pt;
}
.formulir-detil tfoot td {
	border-bottom: none;
	border-top: 1px solid #000;
	font-weight: bold;
}
.ttd-box {
	text-align: center;
	color: #2255aa;
	font-weight: bold;
}
</style>
<div>
	<b class="formulir-header"><span class="uppercase"><?= $company_name; ?></span> <?= $namapt; ?></b>
</div>
<div class="formulir-title">Surat Permintaan Pelanggan</div>

<table class="table formulir-info">
	<tbody>
		<tr>
			<td width="20%">Nama Karyawan</td>
			<td width="2%">:</td>
			<td width="28%"><?= $karyawan; ?></td>
			<td width="12%">No PO</td>
			<td width="2%">:</td>
			<td><?= $nomor; ?></td>
		</tr>
		<tr>
			<td>Dari Cabang</td>
			<td>:</td>
			<td><?= $cabang; ?></td>
			<td>Tanggal</td>
			<td>:</td>
			<td><?= $tanggal; ?></td>
		</tr>
		<tr>
			<td>Tipe</td>
			<td>:</td>
			<td><?= $tipe; ?></td>
			<td>No Ref</td>
			<td>:</td>
			<td><?= $noref; ?></td>
		</tr>
		<tr>
			<td>Gudang / Supplier Tujuan</td>
			<td>:</td>
			<td colspan="4"><?= $gudangtujuan; ?></td>
		</tr>
	</tbody>
</table>

<table class="table formulir-detil" style="margin-top: 10pt;">
	<thead>
		<tr>
			<th align="center" width="6%">No</th>
			<th align="left">Nama Item</th>
			<th align="right" width="10%">Qty</th>
			<th align="right" width="10%">Real Stok</th>
			<th align="center" width="10%">Satuan</th>
			<th align="left" width="20%">Catatan</th>
		</tr>
	</thead>
	<tbody>
		<?
			$i = 1;
			foreach ($detil->data as $row) {
				$totalqty += $row->qty;
		?>
		<tr>
			<td align="center"><?= $i; ?></td>
			<td align="left"><?= $row->item; ?></td>
			<td align="right"><?= number_format($row->qty,2,',','.'); ?></td>
			<td align="right"><?= number_format($row->stokreal,2,',','.'); ?></td>
			<td align="center"><?= $row->satuan; ?></td>
			<td align="left"><?= $row->catatan; ?></td>
		</tr>
		<?
				$i++;
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2" align="right">Total Qty</td>
			<td align="right"><?= number_format($totalqty,2,',','.'); ?></td>
			<td colspan="3"></td>
		</tr>
	</tfoot>
</table>

<table class="table" style="margin-top: 40pt; width: 90%;">
	<tbody>
		<tr>
			<td width="33%" class="ttd-box">Dibuat Oleh</td>
			<td width="33%" class="ttd-box">Diketahui Oleh</td>
			<td width="33%" class="ttd-box">Disetujui Oleh</td>
		</tr>
		<tr>
			<td class="py-4">&nbsp;</td>
			<td class="py-4">&nbsp;</td>
			<td class="py-4">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</td>
			<td align="center">( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</td>
			<td align="center">( &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; )</td>
		</tr>
	</tbody>
</table>
