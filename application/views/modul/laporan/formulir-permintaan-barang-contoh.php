<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.pbuid 'id',A.pbunotransaksi 'nomor',DATE_FORMAT(A.pbutanggal,'%d-%m-%Y') 'tanggal',
                      B.knama 'kontak', A.pbuuraian 'uraian', A.pbucatatan 'catatan', A.pbunoref 'noref',
                      C.knama 'karyawan', D.gnama 'gudang', E.gnama 'gudangsumber',
                      CASE WHEN A.pbutipepermintaan=1 THEN 'PO Ke Supplier'
                           WHEN A.pbutipepermintaan=2 THEN 'Mutasi Antar Gudang'
                           ELSE '-'
                      END 'tipe',
                      CASE WHEN A.pbustatus=1 THEN 'Belum Diproses'
                           WHEN A.pbustatus=2 THEN 'Sebagian Diproses'
                           WHEN A.pbustatus=3 THEN 'Selesai'
                           WHEN A.pbustatus=9 THEN 'Dibatalkan'
                           ELSE '-'
                      END 'status'
                 FROM fpermintaanbarangu A
            LEFT JOIN bkontak B ON A.pbukontak=B.kid
            LEFT JOIN bkontak C ON A.pbukaryawan=C.kid
            LEFT JOIN bgudang D ON A.pbugudang=D.gid
            LEFT JOIN bgudang E ON A.pbugudangsumber=E.gid
                WHERE A.pbuid = '".$id."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);

    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.pbdqty 'qty',C.skode 'satuan', A.pbdcatatan
                 FROM fpermintaanbarangd A
            LEFT JOIN bitem B ON A.pbditem = B.iid
            LEFT JOIN bsatuan C ON A.pbdsatuan=C.sid
                WHERE A.pbdidsu = '".$id."' ORDER BY A.pbdurutan ASC";

    $detil = $CI->M_transaksi->get_data_query($querydtl);
    $detil = json_decode($detil);

    foreach ($header->data as $row) {
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$tipe = $row->tipe;
    	$status = $row->status;
    	$karyawan = $row->karyawan;
    	$gudang = $row->gudang;
    	$gudangsumber = empty($row->gudangsumber) ? "-" : $row->gudangsumber;
    	$kontak = empty($row->kontak) ? "-" : $row->kontak;
    	$noref = empty($row->noref) ? "-" : $row->noref;
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;
    	$catatan = empty($row->catatan) ? "-" : $row->catatan;
    }
?>
<div class="header-report">
	<div class="logo left">
		<img src="assets/dist/img/logo.png" />
	</div>
	<div class="left px-1" width="38%">
		<h4 class="text-blue"><b><?= $company_name; ?></b></h4>
		<span><?= $company_addr ?>, Kode Pos : <?= $company_kodepos ?>, Email : <?= $company_email ?>, Telp : <?= $company_phone ?></span>
	</div>
	<div class="right">
		<h3><?= $title; ?></h3>
		<div class="right py-2">
			<table class="table">
				<tbody>
					<tr>
						<td align="center" class="border-1 bg-dark">Nomor</td>
						<td width="10"></td>
						<td align="center" class="border-1 bg-dark">Tanggal</td>
					</tr>
					<tr>
						<td align="center" class="border-1"><?= $nomor ?></td>
						<td>&nbsp;</td>
						<td align="center" class="border-1"><?= $tanggal ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="line"></div>
	<div class="left">
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark" width="50%">Tipe Permintaan :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">Status :</td>
				</tr>
				<tr>
					<td class="border-1 px-1 py-1"><?= $tipe ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 py-1"><?= $status ?></td>
				</tr>
			</tbody>
		</table>
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark" width="50%">Peminta :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">Gudang Peminta :</td>
				</tr>
				<tr>
					<td class="border-1 px-1 py-1"><?= $karyawan ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 py-1"><?= $gudang ?></td>
				</tr>
			</tbody>
		</table>
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark" width="50%">Vendor :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">Gudang Sumber :</td>
				</tr>
				<tr>
					<td class="border-1 px-1 py-1"><?= $kontak ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 py-1"><?= $gudangsumber ?></td>
				</tr>
			</tbody>
		</table>
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td align="center" class="border-1 bg-dark" width="50%">No. Referensi :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">Uraian :</td>
				</tr>
				<tr>
					<td class="border-1 px-1 py-1"><?= $noref ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 py-1"><?= $uraian ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th align="center" width="7%">No</th>
				<th align="center" width="15%">Kode</th>
				<th align="center">Nama Barang</th>
				<th align="center" width="12%">Qty</th>
				<th align="center" width="12%">Satuan</th>
				<th align="center" width="25%">Catatan</th>
			</tr>
		</thead>
		<tbody>
			<?
				$i = 1;
			    foreach ($detil->data as $row) {
			?>
				<tr>
					<td align="center" class="py-1"><?= $i ?></td>
					<td class="left py-1"><?= $row->noitem ?></td>
					<td class="left py-1"><?= $row->item ?></td>
					<td class="right px-1 py-1"><?= $row->qty ?></td>
					<td class="center px-1 py-1"><?= $row->satuan ?></td>
					<td class="left px-1 py-1"><?= $row->pbdcatatan ?></td>
				</tr>
			<?
				$i++;
				}
			?>
		</tbody>
	</table>
	<table class="table" style="margin-top: 10px; width: 100%">
		<tbody>
			<tr>
				<td>Catatan : <?= $catatan ?></td>
			</tr>
		</tbody>
	</table>
	<table class="table" style="margin-top: 20px; width: 70%">
		<tbody>
			<tr>
				<td>Diminta :</td>
				<td width="10"></td>
				<td>Disetujui :</td>
				<td width="10"></td>
				<td>Diserahkan :</td>
			</tr>
			<tr>
				<td align="center" class="py-4 border-bottom-1"></td>
				<td width="10" class="py-4"></td>
				<td align="center" class="py-4 border-bottom-1"></td>
				<td width="10" class="py-4"></td>
				<td align="center" class="py-4 border-bottom-1"></td>
			</tr>
		</tbody>
	</table>
</div>
