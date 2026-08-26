<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak', B.kidpasien 'idpasien',
                      A.suuraian 'uraian', C.knama 'karyawan', A.sutotaltransaksi 'total', B.k1alamat 'alamat' , A.sutotaltransaksi 'total'
                      , A.sutotalbayar 'totaldibayar' , A.sutotaltransaksi-A.sutotalbayar 'sisa'
                 FROM fstoku_tes A 
            LEFT JOIN bkontak B ON A.sukontak=B.kid
            LEFT JOIN bkontak C ON A.sukaryawan=C.kid 
                WHERE A.suid = '".$id."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.sdkeluar 'qty',C.skode 'satuan',A.sdcatatan 'catatan', (A.sdharga-A.sddiskon) 'harga',
    					 D.gnama 'gudang',(A.sdharga-A.sddiskon)* A.sdkeluar 'jumlah'
                 FROM fstokd_tes A 
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
    	$idpasien = $row->idpasien;   	    	
    	$alamat = $row->alamat;    	    	    	
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;  
    	$karyawan = $row->karyawan;   	
    	$total = $row->total;    
    	$sisa = $row->sisa ;
    	$dibayar = $row->totaldibayar;  
    }
?>
<div class="header-report">
	 
	<div class="left px-1" width="50%">
		<h3 class="text-blue"><b><?= $company_name; ?></b></h3>	
		IG : nmwskincare - www.nmwskincare.co.id
	</div>
	<div class="right">
		<h3>Invoice Penjualan</h3>
		
				
		<div class="left py-2">
			<table class="table">
				<tbody> 
				    <table class="table">
				        <tbody> 
        					<tr>
        						<td ><?= $idpasien ?></td>
        						<td ><?= $kontak ?></td>				
        					</tr> 
        		    	</tbody>
			        </table> 
			        
				    <table class="table">
				        <tbody> 		
        					<tr>
        						<td ><?= $idpasien ?></td>
        						<td rowspan=2><?= $kontak ?></td>				
        					</tr>
        					<tr>
        						<td align="center" class="border-1"><?= $nomor ?></td>
        						<td>&nbsp;</td>
        						<td align="center" class="border-1"><?= $tanggal ?></td>												
        					</tr>
        				</tbody>
			        </table> 
				</tbody>
			</table>
		</div>
		
		
		
		<div class="right py-2">
			<table class="table">
				<tbody>
					<tr>
						<td align="center" class="border-1 bg-dark">Nomor</td> 
						<td align="center" class="border-1"><?= $nomor ?></td>
					</tr>
					<tr>	 
						<td align="center" class="border-1 bg-dark">Tanggal</td>
						<td align="center" class="border-1"><?= $tanggal ?></td>					
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
					<td align="center" class="border-1 bg-dark">Kasir :</td>
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
				<th align="center">Nama Barang</th>
				<th align="center" width="10%">Qty</th>
				<th align="center" width="15%">Satuan</th>
				<th align="center" width="15%">Harga</th>
				<th align="center" width="15%">Jumlah</th>								
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
					<td class="left py-1"><?= $row->item ?></td>
					<td class="right px-1 py-1"><?= $row->qty ?></td>							
					<td class="right px-1 py-1"><?= $row->satuan ?></td>
					<td class="right px-1 py-1"><?= eFormatNumber($row->harga,2)  ?></td>
					<td class="right px-1 py-1"><?= eFormatNumber($row->jumlah,2)  ?></td>
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
				<th class="right px-1 py-1">Total Transaksi</th>
				<th class="right px-1 py-1"><?= eFormatNumber($total,2)  ?></th>				
			</tr>								
			<tr>
				<td colspan="4" class="left py-1"></td>
				<th class="right px-1 py-1">Total Bayar</th>
				<th class="right px-1 py-1"><?= eFormatNumber($dibayar,2)  ?></th>				
			</tr>									
			<tr>
				<td colspan="4" class="left py-1"></td>
				<th class="right px-1 py-1">Total SIsa</th>
				<th class="right px-1 py-1"><?= eFormatNumber($sisa,2)  ?></th>				
			</tr>						
		</tfoot>
	</table> 						
</div>
