<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'namapasien',
                      A.suuraian 'uraian', A.sukaryawan 'karyawan', A.sutotaltransaksi 'total' , D.knama 'namakaryawan',
					  B.k1alamat 'alamat',  A.sutotalbayar 'totaldibayar' , A.sutotaltransaksi-A.sutotalbayar 'sisa'
                 FROM fstoku_tes A 
            LEFT JOIN bkontak B ON A.sukontak=B.kid 
            LEFT JOIN bkontak D ON A.sukaryawan=D.kid   
                WHERE A.suid = '".$id."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.sdkeluar 'qty',(A.sdharga-A.sddiskon) 'harga',C.skode 'satuan'  
                 FROM fstokd_tes A 
            LEFT JOIN bitem B ON A.sditem = B.iid 
            LEFT JOIN bsatuan C ON A.sdsatuan=C.sid 
                WHERE A.sdidsu = '".$id."' ORDER BY A.sdurutan ASC";

    $detil = $CI->M_transaksi->get_data_query($querydtl);
    $detil = json_decode($detil);

    foreach ($header->data as $row) {
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$alamat = $row->alamat;   	
    	$total = $row->total;    
    	$sisa = $row->sisa ;
    	$dibayar = $row->totaldibayar;
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
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th align="center" width="7%">No</th>				
				<th align="center" width="13%">Kode</th>				
				<th align="center">Nama Barang</th>
				<th align="center" width="10%">Qty</th>
				<th align="center" width="21%">Harga</th>
				<th align="center" width="21%">Jumlah</th>								
			</tr>
		</thead>
		<tbody>
			<?
				$i = 1;
				$subtotal = 0;
			    foreach ($detil->data as $row) {
			?>
				<tr>
					<td align="center" class="py-1"><?= $i ?></td>	
					<td class="left py-1"><?= $row->noitem ?></td>	
					<td class="left py-1"><?= $row->item ?></td>
					<td class="right px-1 py-1"><?= $row->qty ?></td>							
					<td class="right px-1 py-1"><?= eFormatNumber($row->harga,2) ?></td>
					<td class="right px-1 py-1"><?= eFormatNumber(($row->harga*$row->qty),2) ?></td>
				</tr>
			<?
				$i++;
				$subtotal += $row->harga*$row->qty;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" rowspan="4" class="left py-1 px-1" style="vertical-align: middle;">Terbilang : <?= terbilang($total); ?></td>
				<th class="right px-1 py-1">Sub Total</th>
				<th class="right px-1 py-1"><?= eFormatNumber($total,2) ?></th>				
			</tr> 										
			<tr>
				<th class="right px-1 py-1">Dibayar</th>
				<th class="right px-1 py-1"><?= eFormatNumber($dibayar,2) ?></th>				
			</tr>						
			<tr>
				<th class="right px-1 py-1">Kembalian</th>
				<th class="right px-1 py-1"><?= eFormatNumber($sisa,2) ?></th>				
			</tr>														
		</tfoot>
	</table>	
	<table class="table" style="margin-top: 20px; width: 20%">
		<tbody>
			<tr>
				<td>Love, Care & Smile</td>
			</tr>
			<tr>
				<td align="center" class="py-4 border-bottom-1"></td>
			</tr>
		</tbody>
	</table>							
</div>
