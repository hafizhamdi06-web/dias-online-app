<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['idkontak'])){
    	$kontak = $_POST['idkontak'];
    } else {
    	$kontak = "";
    }		
    if(isset($_POST['gudang'])){
    	$gudang = $_POST['gudang'];
    } else {
    	$gudang = "";
    }	
    
    
      $cabang  = @$_SESSION['cabang'] ;



    $CI =& get_instance();
    $transcode = element('PJ_Penjualan_Tunai',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.suuraian 'uraian', IFNULL(A.sutotaltransaksi,0) 'totaltrans',
                        A.sutotalkas-A.sutotalsisa+A.sutotalkartudebit+A.sutotalkartukredit+A.sutotaltransfer+A.sumerchantjumlah  'total', 
                        A.sutotalkas-A.sutotalsisa 'kasjumlah',
                       A.sutotalkartudebit 'debitjumlah', A.sunokartudebit 'debitno', A.sunamadebit 'debitnama',
                       A.subankdebit 'debitbank', A.sudebitjenis 'debitjenis', A.suattention 'debitbanklain',
                       A.sutotalkartukredit 'kreditjumlah', A.sunokartukredit 'kreditno', A.sunamakredit 'kreditnama',
                       A.subankkredit 'kreditbank', A.sukreditjenis 'kreditjenis', A.sunofakturpajak 'kreditbanklain',
                       A.sutotaltransfer 'transferjumlah', A.sunotransfer 'transferno', A.sunamatransfer 'transfernama',
                       A.subanktransfer 'transferbank' ,
                       A.sutotalvoucher 'voucherjumlah', A.sunovoucher 'voucherno', A.sustatuskirim 'voucherid',
                       A.sutotaldp  'dpjumlah', A.sudp1 'dpjumlah', A.sudpid 'dpid' , A.sujenisdp 'dpjenis',  
                       A.sucabang 'cabang', A.surekammedis 'rekammedis', A.sutotaltada 'totaltanpadp',
                       A.sumerchantjumlah  'merchantjumlah', A.sumerchantno 'merchantno' , A.sumerchantjenis 'merchantjenis' 
                       
                       
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid 
	                WHERE A.susumber = '".$transcode."'  and A.sustatus <> 9 
	                  AND A.sutanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'
	                  and A.sucabang = '".$cabang."'
	                  
	                  ";            

    if($kontak != ""){
    	$query .= " AND A.sukontak='".$kontak."'";
    }
    
    
    if($gudang != ""){
    	$query .= " AND A.sucabang='".$gudang."'";
    }

    $query .= " GROUP BY A.suid,A.sunotransaksi,A.sutanggal";

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);

?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>		
	<h3><?= $title; ?></h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="10%">Tanggal</th>
				<th class="left px-1" width="11%">Nomor</th>				
				<th class="left px-1">Kontak</th>	
				<th class="right px-1">Total Real</th> 
				<th class="right px-1">Kas</th> 	
				<th class="right px-1">Debit</th> 	
				<th class="right px-1">Kredit</th> 	
				<th class="right px-1">Transfer</th> 
				<th class="right px-1">Merchant</th> 	
				<th class="right px-1">Tarik DP</th> 							
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0; $totaldp = 0; $totaltagihan = 0; $totalppn = 0; $totalpph22 = 0;
				$tkas = 0; $tdebit = 0 ; $tkredit = 0; $ttransfer = 0; $tdp=0; $tmerchant=0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->total,2)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->kasjumlah,2)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->debitjumlah,2)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->kreditjumlah,2)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->transferjumlah,2)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->merchantjumlah,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->dpjumlah,2)."</td>";  
					echo "</tr>";
					
					$total += $row->total; 								
					$tkas += $row->kasjumlah; 							
					$tdebit += $row->debitjumlah; 							
					$tkredit += $row->kreditjumlah; 							
					$ttransfer += $row->transferjumlah; 							
					$tmerchant += $row->merchantjumlah; 							
					$tdp += $row->dpjumlah; 				
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" class="px-1">Total</td>	
				<td class="right px-1"><?= eFormatNumber($total,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkas,2); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tdebit,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkredit,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($ttransfer,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tmerchant,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tdp,2); ?></td>			
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>