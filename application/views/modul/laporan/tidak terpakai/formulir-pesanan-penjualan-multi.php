<?php
	include ('style.php');

    $CI =& get_instance();

    $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      A.souuraian 'uraian', A.soukaryawan 'karyawan', A.soutotaltransaksi 'total', (A.soutotalpajak+A.soutotalpph22) 'totalpajak',
					  A.sousubtotal 'subtotal',B.k1alamat 'alamat',C.tnama 'termin', 
					  CASE WHEN A.soupajak=0 THEN 'Tanpa Pajak'
					  	   WHEN A.soupajak=1 THEN 'Belum Termasuk Pajak'
					  	   ELSE 'Termasuk Pajak'
					  END 'tipepajak'                         
                 FROM esalesorderu A 
            LEFT JOIN bkontak B ON A.soukontak=B.kid
            LEFT JOIN btermin C ON A.soutermin=C.tid";

    if(empty($this->input->post('nomorsampai')) && !empty($this->input->post('nomor'))){
	    $query .= " WHERE A.sounotransaksi = '".$this->input->post('nomor')."'";    	
    }
    if(!empty($this->input->post('nomorsampai')) && !empty($this->input->post('nomor'))){
	    $query .= " WHERE A.sounotransaksi BETWEEN '".$this->input->post('nomor')."' AND '".$this->input->post('nomorsampai')."'";    	
    }
    if(empty($this->input->post('nomor'))){
	    $query .= " WHERE A.sounotransaksi = '".$this->input->post('nomor')."'";
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
    	$termin = $row->termin;
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$total = $row->total;   
    	$subtotal = $row->subtotal;
    	$totalpajak = $row->totalpajak;
    	$pajak = $row->tipepajak;

	    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.sodorder 'qty',(A.sodharga-A.soddiskon) 'harga',C.skode 'satuan', A.sodcatatan  
	                 FROM esalesorderd A 
	            LEFT JOIN bitem B ON A.soditem = B.iid 
	            LEFT JOIN bsatuan C ON A.sodsatuan=C.sid 
	                WHERE A.sodidsou = '".$row->id."' ORDER BY A.sodurutan ASC";

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
					<td align="center" class="border-1 bg-dark">Termin :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">Pajak :</td>
					<td width="10"></td>
					<td align="center" class="border-1 bg-dark">No. PO Pelanggan :</td>					
				</tr>
				<tr>
					<td align="center" class="border-1 px-1"><?= $termin ?></td>
					<td>&nbsp;</td>					
					<td class="border-1 px-1"><?= $pajak ?></td>
					<td>&nbsp;</td>
					<td class="border-1 px-1"></td>												
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
				<th align="center" width="21%">Harga</th>
				<th align="center" width="21%">Jumlah</th>								
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
					<td class="right px-1 py-1"><?= eFormatNumber($row->harga,2) ?></td>
					<td class="right px-1 py-1"><?= eFormatNumber(($row->harga*$row->qty),2) ?></td>
				</tr>
			<?
				$i++;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" rowspan="3" class="left py-1" style="vertical-align: middle;">Terbilang : <?= terbilang($total); ?></td>
				<th class="right px-1 py-1">Sub Total</th>
				<th class="right px-1 py-1"><?= eFormatNumber($subtotal,2) ?></th>				
			</tr>						
			<tr>
				<th class="right px-1 py-1">Pajak</th>
				<th class="right px-1 py-1"><?= eFormatNumber($totalpajak,2) ?></th>				
			</tr>							
			<tr>
				<th class="right px-1 py-1">Total</th>
				<th class="right px-1 py-1"><?= eFormatNumber($total,2) ?></th>				
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
<?
}
?>