<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      A.dpketerangan 'uraian', A.dpjumlah 'total', A.dpjumlahv 'totalv',C.cnocoa 'nocoa',
                      C.cnama 'coa',D.cnocoa 'nocoadp',D.cnama 'coadp'   
                 FROM ddp A 
            LEFT JOIN bkontak B ON A.dpkontak=B.kid 
            LEFT JOIN bcoa C ON A.dpckas = C.cid 
            LEFT JOIN bcoa D ON A.dpcdp = D.cid             
                WHERE A.dpid = '".$id."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    foreach ($header->data as $row) {
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$total = $row->total;   
    	$coa = $row->nocoa." - ".$row->coa;    	    	    	 	    	    		    	    	
    	$nocoadp = $row->nocoadp;
    	$coadp = $row->coadp;    	    	    	    	 	    	    		    	    	    	
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
					<td class="border-1 px-1 bg-dark">Uraian :</td>
				</tr>
				<tr>
					<td class="border-1 px-1"><?= $uraian ?></td>
				</tr>				
				<tr>
					<td></td>
				</tr>
			</tbody>
		</table>					
		<table class="table" style="margin-top: 5px;">
			<tbody>
				<tr>
					<td class="border-1 px-1 bg-dark">Dibayarkan Ke :</td>
					<td width="10"></td>
					<td class="border-1 px-1 bg-dark">Dibayar dari :</td>
					<td width="10"></td>
					<td class="border-1 px-1 bg-dark">Jumlah :</td>					
				</tr>
				<tr>
					<td class="border-1 px-1"><?= $kontak ?></td>
					<td>&nbsp;</td>					
					<td class="border-1 px-1"><?= $coa ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1 right"><?= eFormatNumber($total,2) ?></td>												
				</tr>				
			</tbody>
		</table>	
	</div>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="13%">Kode</th>				
				<th class="left px-1">Nama Akun</th>
				<th class="right px-1" width="21%">Jumlah</th>
				<th class="left px-1">Catatan</th>				
			</tr>
		</thead>
		<tbody>
			<tr>
				<td class="left px-1"><?= $nocoadp ?></td>				
				<td class="left px-1"><?= $coadp ?></td>
				<td class="right px-1"><?= eFormatNumber($total,2) ?></td>
				<td class="left px-1"></td>				
			</tr>
		</tbody>
		<tfoot>
		</tfoot>
	</table>	
	<table class="table" style="margin-top: 20px;">
		<tbody>
			<tr>
				<td>Terbilang : <?= terbilang($total); ?></td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</tbody>
	</table>
	<table class="table" style="margin-top: 20px; width: 70%">
		<tbody>
			<tr>
				<td>Diterima :</td>
				<td width="10"></td>
				<td>Dibuat :</td>
				<td width="10"></td>
				<td>Disetujui :</td>					
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
