<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      A.suuraian 'uraian', C.knama 'karyawan', A.sutotaltransaksi 'total', B.k1alamat 'alamat'                         
                 FROM fstoku A 
            LEFT JOIN bkontak B ON A.sukontak=B.kid
            LEFT JOIN bkontak C ON A.sukaryawan=C.kid 
                WHERE A.suid = '".$id."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.sdkeluar 'qty',C.skode 'satuan',A.sdcatatan 'catatan',
    					 D.gnama 'gudang' 
                 FROM fstokd A 
            LEFT JOIN bitem B ON A.sditem = B.iid 
            LEFT JOIN bsatuan C ON A.sdsatuan=C.sid 
            LEFT JOIN bgudang D ON A.sdgudang=D.gid 
                WHERE A.sdidsu = '".$id."' ORDER BY A.sdurutan ASC";

    $detil = $CI->M_transaksi->get_data_query($querydtl);
    $detil = json_decode($detil);

    foreach ($header->data as $row) {
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$alamat = $row->alamat;    	    	    	
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$total = $row->total;
    	$karyawan = $row->karyawan;   
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
					<td align="center" class="border-1 bg-dark" width="50%">Pelanggan :</td>
					<td width="10"></td>					
					<td align="center" class="border-1 bg-dark">Alamat :</td>					
				</tr>
				<tr>
					<td class="border-1 px-1 py-1"><?= $kontak ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 py-1"><?= $alamat ?></td>					
				</tr>				
				<tr>
					<td></td>
				</tr>
			</tbody>
		</table>
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td width="70%"></td>
					<td align="center" class="border-1 bg-dark">Bag. Gudang :</td>
				</tr>
				<tr>
					<td></td>
					<td align="center" class="border-1 px-1"><?= $karyawan ?></td>
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
				<th align="center" width="13%">Kode</th>				
				<th align="center">Nama Barang</th>
				<th align="center" width="10%">Qty</th>
				<th align="center" width="15%">Satuan</th>
				<th align="center" width="21%">Gudang</th>								
			</tr>
		</thead>
		<tbody>
			<?
				$i = 1;
				$qty = 0;
			    foreach ($detil->data as $row) {
			?>
				<tr>
					<td align="center" class="py-1"><?= $i ?></td>	
					<td class="left py-1"><?= $row->noitem ?></td>	
					<td class="left py-1"><?= $row->item ?></td>
					<td class="right px-1 py-1"><?= $row->qty ?></td>							
					<td class="right px-1 py-1"><?= $row->satuan ?></td>
					<td class="right px-1 py-1"><?= $row->gudang ?></td>
				</tr>
			<?
				$i++;
				$qty += $row->qty;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="left py-1"></td>
				<th class="right px-1 py-1">Jumlah Item</th>
				<th class="right px-1 py-1"><?= $i-1 ?></th>				
			</tr>						
			<tr>
				<td colspan="4" class="left py-1"></td>
				<th class="right px-1 py-1">Total Qty</th>
				<th class="right px-1 py-1"><?= $qty ?></th>				
			</tr>							
		</tfoot>
	</table>	
	<table class="table" style="margin-top: 20px; width: 50%">
		<tbody>
			<tr>
				<td>Dibuat :</td>
				<td width="10"></td>
				<td>Disetujui :</td>					
			</tr>
			<tr>
				<td align="center" class="py-4 border-bottom-1"></td>
				<td width="10" class="py-4"></td>
				<td align="center" class="py-4 border-bottom-1"></td>									
			</tr>
		</tbody>
	</table>							
</div>
