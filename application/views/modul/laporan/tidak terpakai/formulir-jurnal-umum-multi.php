<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      A.cuuraian 'uraian', A.cutotaltrans 'total', A.cutotaltransv 'totalv',C.cnocoa 'nocoa',
                      C.cnama 'coa'   
                 FROM ctransaksiu A 
            LEFT JOIN bkontak B ON A.cukontak=B.kid 
            LEFT JOIN bcoa C ON A.curekkas = C.cid";

    if(empty($this->input->post('nomorsampai')) && !empty($this->input->post('nomor'))){
	    $query .= " WHERE A.cunotransaksi = '".$this->input->post('nomor')."'";    	
    }
    if(!empty($this->input->post('nomorsampai')) && !empty($this->input->post('nomor'))){
	    $query .= " WHERE A.cunotransaksi BETWEEN '".$this->input->post('nomor')."' AND '".$this->input->post('nomorsampai')."'";    	
    }
    if(empty($this->input->post('nomor'))){
	    $query .= " WHERE A.cunotransaksi = '".$this->input->post('nomor')."'";
    }

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $data = 0;

    foreach ($header->data as $row) {
    	$data++;
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$total = $row->total;   
    	$coa = $row->nocoa." - ".$row->coa;    	    	    	 	    	    		    	    	

	    $querydtl  = "SELECT B.cnocoa 'nocoa',B.cnama 'coa',A.cddebit 'debit',A.cdkredit 'kredit',A.cdcatatan  
	                 FROM ctransaksid A 
	            LEFT JOIN bcoa B ON A.cdnocoa = B.cid 
	                WHERE A.cdidu = '".$row->id."' ORDER BY A.cdurutan ASC";

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
					<td class="border-1 px-1 bg-dark" width="50%">Kontak :</td>
					<td width="10"></td>
					<td class="border-1 px-1 bg-dark">Uraian :</td>
				</tr>
				<tr>
					<td class="border-1 px-1"><?= $kontak ?></td>
					<td>&nbsp;</td>					
					<td class="border-1 px-1"><?= $uraian ?></td>
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
				<th class="left px-1">Catatan</th>								
				<th class="right px-1" width="20%">Debit</th>
				<th class="right px-1" width="20%">Kredit</th>				
			</tr>
		</thead>
		<tbody>
			<? 
				$totalDb = 0;
				$totalCr = 0;
				foreach ($detil->data as $row) { 
			?>
			<tr>
				<td class="left px-1"><?= $row->nocoa ?></td>				
				<td class="left px-1"><?= $row->coa ?></td>
				<td class="left px-1"><?= $row->cdcatatan ?></td>								
				<td class="right px-1"><?= eFormatNumber($row->debit,2) ?></td>
				<td class="right px-1"><?= eFormatNumber($row->kredit,2) ?></td>				
			</tr>
			<? 
				$totalDb += $row->debit;
				$totalCr += $row->kredit;				
				} 
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" class="right px-1"></td>				
				<th class="right px-1 bg-dark"><?= eFormatNumber($totalDb,2) ?></th>
				<th class="right px-1 bg-dark"><?= eFormatNumber($totalCr,2) ?></th>				
			</tr>			
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
<?
}
?>