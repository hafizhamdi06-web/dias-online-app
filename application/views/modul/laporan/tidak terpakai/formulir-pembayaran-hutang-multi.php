<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      B.k1alamat 'alamat',A.piuuraian 'uraian', A.piujmlkas 'jmlkas', A.piudiskon 'jmldiskon',
                      A.piutotalretur 'jmlretur', A.piunilaibuktipotong 'jmlpph',
                      (A.piutotalpiutang-A.piudiskon-A.piutotalretur-A.piunilaibuktipotong) 'jmlpiu',
                      CASE WHEN A.piutipe=1 THEN 'Cek/Giro'
                      	   WHEN A.piutipe=2 THEN 'Transfer'
                      	   ELSE 'Tunai'
                      END 'carabayar',
                      CASE WHEN A.piutipe=1 THEN CONCAT_WS(' - ', A.piunoac, DATE_FORMAT(A.piutgltempo,'%d-%m-%Y'))
                      	   WHEN A.piutipe=2 THEN CONCAT_WS(' - ', C.bnama, A.piunoac)
                      	   ELSE '-'
                      END 'infobayar'                       
                 FROM epembayaraninvoiceu A 
            LEFT JOIN bkontak B ON A.piukontak=B.kid
            LEFT JOIN bbank C ON A.piubank=C.bid";

    if(empty($this->input->post('nomorsampai')) && !empty($this->input->post('nomor'))){
	    $query .= " WHERE A.piunotransaksi = '".$this->input->post('nomor')."'";    	
    }
    if(!empty($this->input->post('nomorsampai')) && !empty($this->input->post('nomor'))){
	    $query .= " WHERE A.piunotransaksi BETWEEN '".$this->input->post('nomor')."' AND '".$this->input->post('nomorsampai')."'";    	
    }
    if(empty($this->input->post('nomor'))){
	    $query .= " WHERE A.piunotransaksi = '".$this->input->post('nomor')."'";
    }

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $data = 0;

    foreach ($header->data as $row) {
    	$data++;
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$alamat = $row->alamat;    	    	    	
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$totalkas = $row->jmlkas;
    	$totaldiskon = $row->jmldiskon;
    	$totalretur = $row->jmlretur;
    	$totalpph = $row->jmlpph;    	    	    	
    	$carabayar = $row->carabayar;
    	$infobayar = $row->infobayar;    	
    	$totalpiu = $row->jmlpiu;

	    $querydtl  = "SELECT CASE WHEN A.piitipe=1 THEN B.ipunotransaksi
	    						  WHEN A.piitipe=0 THEN C.dpnotransaksi
	    				     END 'nofaktur',
	    				     CASE WHEN A.piitipe=1 THEN DATE_FORMAT(B.iputanggal,'%d-%m-%Y')
	    						  WHEN A.piitipe=0 THEN DATE_FORMAT(C.dptanggal,'%d-%m-%Y')
	    				     END 'tglfaktur',
	    				     A.piijmlbayar 'jmlbayar'  
	                 FROM epembayaraninvoicei A 
	            LEFT JOIN einvoicepenjualanu B ON A.piiidinvoice = B.ipuid AND A.piitipe=1 
	            LEFT JOIN ddp C ON A.piiiddp = C.dpid AND A.piitipe=0 
	                WHERE A.piiidu = '".$row->id."' ORDER BY A.piiurutan ASC";

	    $detil = $CI->M_transaksi->get_data_query($querydtl);
	    $detil = json_decode($detil);
	 	if($data!==1) {
?>
<pagebreak>
</pagebreak>	
<?
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
					<td align="center" class="border-1 bg-dark" width="50%">Pemasok :</td>
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
					<td align="center" class="border-1 bg-dark">Cara Bayar :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">Bank :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">Sejumlah :</td>					
				</tr>
				<tr>
					<td align="center" class="border-1 px-1"><?= $carabayar; ?></td>
					<td>&nbsp;</td>					
					<td align="center" class="border-1 px-1"><?= $infobayar; ?></td>
					<td>&nbsp;</td>
					<td align="right" class="border-1 px-1"><?= eFormatNumber($totalkas,2) ?></td>												
				</tr>				
			</tbody>
		</table>	
	</div>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th align="center" width="5%">No</th>				
				<th align="center" width="25%">No. Faktur</th>				
				<th align="center" width="20%">Tanggal</th>
				<th align="center" width="25%">Total Faktur</th>
				<th align="center" width="25%">Dibayar</th>
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
					<td class="left py-1 px-1"><?= $row->nofaktur ?></td>	
					<td class="left py-1 px-1"><?= $row->tglfaktur ?></td>
					<td class="right px-1 py-1">N/A</td>							
					<td class="right px-1 py-1"><?= eFormatNumber($row->jmlbayar,2) ?></td>
				</tr>
			<?
				$i++;
				$subtotal += $row->jmlbayar;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" rowspan="5" class="left py-1 px-1" style="vertical-align: middle;">Terbilang : <?= terbilang($totalkas); ?></td>
				<th class="right px-1 py-1">Sub Total</th>
				<th class="right px-1 py-1"><?= eFormatNumber($subtotal,2) ?></th>				
			</tr>						
			<tr>
				<th class="right px-1 py-1">Diskon</th>
				<th class="right px-1 py-1"><?= eFormatNumber($totaldiskon,2) ?></th>				
			</tr>							
			<tr>
				<th class="right px-1 py-1">Retur</th>
				<th class="right px-1 py-1"><?= eFormatNumber($totalretur,2) ?></th>				
			</tr>										
			<tr>
				<th class="right px-1 py-1">PPh</th>
				<th class="right px-1 py-1"><?= eFormatNumber($totalpph,2) ?></th>				
			</tr>										
			<tr>
				<th class="right px-1 py-1">Total</th>
				<th class="right px-1 py-1"><?= eFormatNumber($totalpiu,2) ?></th>				
			</tr>											
		</tfoot>
	</table>	
	<table class="table" style="margin-top: 20px; width: 20%">
		<tbody>
			<tr>
				<td>Mengetahui :</td>					
			</tr>
			<tr>
				<td align="center" class="py-4 border-bottom-1"></td>
			</tr>
		</tbody>
	</table>							
</div>
<?
}
?>