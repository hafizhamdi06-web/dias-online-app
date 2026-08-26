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
                        A.sutotalkas-A.sutotalsisa+A.sutotalkartudebit+A.sutotalkartukredit+A.sutotaltransfer+A.sumerchantjumlah    'total', 
                        A.sutotalkas-A.sutotalsisa 'kasjumlah', A.sutotalsisa 'cashback' ,
                       A.sutotalkartudebit 'debitjumlah', A.sunokartudebit 'debitno', A.sunamadebit 'debitnama',
                       A.subankdebit 'debitbank', A.sudebitjenis 'debitjenis', A.suattention 'debitbanklain',
                       A.sutotalkartukredit 'kreditjumlah', A.sunokartukredit 'kreditno', A.sunamakredit 'kreditnama',
                       A.subankkredit 'kreditbank', A.sukreditjenis 'kreditjenis', A.sunofakturpajak 'kreditbanklain',
                       A.sutotaltransfer 'transferjumlah', A.sunotransfer 'transferno', A.sunamatransfer 'transfernama',
                       A.subanktransfer 'transferbank' ,
                       A.sutotalvoucher 'voucherjumlah', A.sunovoucher 'voucherno', A.sustatuskirim 'voucherid',
                       A.sutotaldp  'dpjumlah', A.sudp1 'dpjumlah', A.sudpid 'dpid' , A.sujenisdp 'dpjenis',  
                       A.sucabang 'cabang', A.surekammedis 'rekammedis', A.sutotaltada 'totaltanpadp',
                       A.sumerchantjumlah  'merchantjumlah', A.sumerchantno 'merchantno' , A.sumerchantjenis 'merchantjenis' ,
                       A.sunilaipiutang 'nilaipiutang', A.supendapatandp 'pendapatandp',  f_dp_perhari(A.suid) 'dpditarik', A.sunilaipiutangbayar 'piutangbayar', A.susurgerydppembayaran 'pembayaransurgery'
                       
                       
                       
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
				<th class="left px-1" width="5%">Tanggal</th>
				<th class="left px-1" width="8%">Nomor</th>				
				<th class="left px-1">Kontak</th>	
				<th class="right px-1">Kas</th> 	
				<th class="right px-1">Debit</th> 	
				<th class="right px-1">Kredit</th> 	
				<th class="right px-1">Transfer</th> 
				<th class="right px-1">Merchant</th> 
				<th class="right px-1">Total Real</th> 	
				<th class="right px-1">DP</th> 			
				<th class="right px-1">Voucher</th> 	
				<th class="right px-1">Piutang Bayar</th> 	
				<th class="right px-1">Piutang</th> 	
				<th class="right px-1">DP Surgery</th> 	
				<th class="right px-1">Surgery</th>  	
				<th class="right px-1">Total Semua</th>  
				<th class="right px-1">Cash Back</th> 							
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0; $totaldp = 0; $totaltagihan = 0; $totalppn = 0; $totalpph22 = 0; 	$totalweb = 0; $totalsemua = 0; $ttotalsemua = 0;
				$tkas = 0; $tdebit = 0 ; $tkredit = 0; $ttransfer = 0; $tdp=0; $tmerchant=0; $tvoucher=0; $tpiutang=0; $tpendapatandp=0; $tdpditarik=0;
				$tpiutangbayar = 0; $tpembayaransurgery = 0; $tcashback = 0; 
				foreach ($datareport->data as $row) {
				    $totalsemua = $row->total+$row->dpjumlah+$row->voucherjumlah+$row->piutangbayar+$row->nilaipiutang+$row->pendapatandp+$row->pembayaransurgery ;
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->kasjumlah,0)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->debitjumlah,0)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->kreditjumlah,0)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->transferjumlah,0)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->merchantjumlah,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->total,0)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->dpjumlah,0)."</td>";  
					echo "<td class='right px-1'>".eFormatNumber($row->voucherjumlah,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->piutangbayar,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->nilaipiutang,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->pendapatandp,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->pembayaransurgery,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($totalsemua,0)."</td>";  
					echo "<td class='right px-1'>".eFormatNumber($row->cashback,0)."</td>";  
					echo "</tr>";
					
					$total += $row->total; 								
					$tkas += $row->kasjumlah; 							
					$tdebit += $row->debitjumlah; 							
					$tkredit += $row->kreditjumlah; 							
					$ttransfer += $row->transferjumlah; 							
					$tmerchant += $row->merchantjumlah; 
					
					$tcashback += $row->cashback;  
					$tvoucher += $row->voucherjumlah; 								
					$tpiutang += $row->nilaipiutang; 								
					$tpendapatandp += $row->pendapatandp; 								
					$tdpditarik  = $row->dpditarik;							
					$tdp += $row->dpjumlah; 							
					$tpiutangbayar += $row->piutangbayar; 				
					$tpembayaransurgery += $row->pembayaransurgery; 
							
					$ttotalsemua += $totalsemua  ;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" class="px-1">Total</td>	
				<td class="right px-1"><?= eFormatNumber($tkas,0); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tdebit,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkredit,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($ttransfer,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tmerchant,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($total,0); ?></td>	
				
				<td class="right px-1"><?= eFormatNumber($tdp,0); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tvoucher,0); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tpiutangbayar,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpiutang,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpendapatandp,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpembayaransurgery,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($ttotalsemua,0); ?></td>
				
				<td class="right px-1"><?= eFormatNumber($tcashback,0); ?></td>
				
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
	
	
	
	<table class="table sm-none">
		<thead>
		</thead>
		<tbody>
		    
		    
			<tr>
				<th class="right px-1">Total Real</th> 
				<th class="right px-1">Kas Nett</th> 	
				<th class="right px-1">Debit</th> 	
				<th class="right px-1">Kredit</th> 	
				<th class="right px-1">Transfer</th> 
				<th class="right px-1">DP</th> 
				<th class="right px-1">Voucher</th> 
				<th class="right px-1">Merchant</th> 	
				<th class="right px-1">Nilai Piutang</th> 
				<th class="right px-1">Pendapatan DP</th>
				<th class="right px-1">DP Ditarik</th>	
				<th class="right px-1" rowspan=2>Termasuk Pembayaran Piutang Surgery</th>							
			</tr>
		    
			<tr> 	
				<td class="right px-1"><?= eFormatNumber($total,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkas,2); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tdebit,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkredit,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($ttransfer,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tdp,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tvoucher,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tmerchant,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpiutang,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpendapatandp,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tdpditarik,2); ?></td>		
			</tr>
		    
		    
			<tr>
				<th class="right px-1">Total Pendapatan</th> 
				<th class="right px-1">Kas Nett</th> 	
				<th class="right px-1">Debit</th> 	
				<th class="right px-1">Kredit</th> 	
				<th class="right px-1">Transfer</th> 
				<th class="right px-1">DP</th> 
				<th class="right px-1">Voucher</th> 
				<th class="right px-1">Merchant</th> 	
				<th class="right px-1">Nilai Piutang</th> 
				<th class="right px-1">Pendapatan DP</th>
				<th class="right px-1">DP Ditarik</th>	
				<th class="right px-1" rowspan=2>TANPA Pembayaran Piutang Surgery</th>							
			</tr>
		    
			<tr> 	
				<td class="right px-1"><?= eFormatNumber($total,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkas,2); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tdebit,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkredit,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($ttransfer,2); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tdp,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tvoucher,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tmerchant,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpiutang,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpendapatandp,2); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tdpditarik,2); ?></td>		
			</tr>
			
			
			
		</tbody>
		<tfoot>
					
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>