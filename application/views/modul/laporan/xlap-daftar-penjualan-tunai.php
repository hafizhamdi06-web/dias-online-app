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
    $merchant = isset($_POST['merchant']) ? $_POST['merchant'] : "";


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

    if($merchant != ""){
    	$query .= " AND A.sumerchantjenis='".$CI->db->escape_str($merchant)."'";
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
				<th class="right px-1">Kas Nett</th> 	
				<th class="right px-1">Debit</th> 	
				<th class="right px-1">Kredit</th> 	
				<th class="right px-1">Transfer</th> 
				<th class="right px-1">Merchant</th> 
				<th class="right px-1">Total Real</th> 	
				<th class="right px-1">Voucher</th> 	
				<th class="right px-1">Piutang</th> 	
				<th class="right px-1">DP Surgery</th> 	
				<th class="right px-1">Surgery</th>  
				<th class="right px-1">DP</th> 					
				<th class="right px-1">- Tarik DP</th>  	
				<th class="right px-1">Total Semua</th>  
				<th class="right px-1">Cash Back</th> 	
				<th class="right px-1">Piutang Bayar</th> 							
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0; $totaldp = 0; $totaltagihan = 0; $totalppn = 0; $totalpph22 = 0;  $totalsemua = 0; $ttotalsemua = 0;
				$tkas = 0; $tdebit = 0 ; $tkredit = 0; $ttransfer = 0; $tdp=0; $tmerchant=0; $tvoucher=0; $tpiutang=0; $tpendapatandp=0; $tdpditarik=0;
				$tpiutangbayar = 0; $tpembayaransurgery = 0; $tcashback = 0; 
				
				 $webkas=0;$webdebit=0;$webkredit=0;$webtransfer=0;$webmerchant=0;$webvoucher=0;$webpiutang=0;
				 $webpendapatandp=0;$webdp=0;$webdpditarik=0; 	$totalweb = 0; $webpembayaransurgery=0;  $totalwebreal = 0; 
				  
				            
				            
				foreach ($datareport->data as $row) {
				    $totalsemua = $row->total+$row->dpjumlah+$row->voucherjumlah+$row->nilaipiutang+$row->pendapatandp+$row->pembayaransurgery-$row->dpditarik ;
				   // $totalweb = $row->total+$row->dpjumlah+$row->voucherjumlah+$row->nilaipiutang+$row->pendapatandp+$row->pembayaransurgery-$row->dpditarik ;
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
					echo "<td class='right px-1'>".eFormatNumber($row->voucherjumlah,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->nilaipiutang,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->pendapatandp,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->pembayaransurgery,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->dpjumlah,0)."</td>";  
					echo "<td class='right px-1'>".eFormatNumber($row->dpditarik*-1,0)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($totalsemua,0)."</td>";  
					echo "<td class='right px-1'>".eFormatNumber($row->cashback,0)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->piutangbayar,0)."</td>"; 
					echo "</tr>";
					
					$total += $row->total; 								
					$tkas += $row->kasjumlah; 							
					$tdebit += $row->debitjumlah; 							
					$tkredit += $row->kreditjumlah; 							
					$ttransfer += $row->transferjumlah; 							
					$tmerchant += $row->merchantjumlah; 
					
					$tvoucher += $row->voucherjumlah; 								
					$tpiutang += $row->nilaipiutang; 								
					$tpendapatandp += $row->pendapatandp;
					$tpembayaransurgery += $row->pembayaransurgery;  				
					$tdp += $row->dpjumlah; 												
					$tdpditarik  += $row->dpditarik*-1;			
							
					$ttotalsemua += $totalsemua  ;
					$tcashback += $row->cashback;  						
					$tpiutangbayar += $row->piutangbayar; 	 
					
					
					if ($row->piutangbayar==0) 
					    {
					        $webkas+=$row->kasjumlah ;
					        $webdebit+=$row->debitjumlah  ;
					        $webkredit+=$row->kreditjumlah  ;
					        $webtransfer+=$row->transferjumlah  ;
					        $webmerchant+=$row->merchantjumlah  ;
					        
					        $webvoucher+=$row->voucherjumlah  ;
					        $webpiutang+=$row->nilaipiutang  ; 
					        $webpendapatandp+=$row->pendapatandp  ;
					        $webpembayaransurgery += $row->pembayaransurgery; 
					        $webdp+=$row->dpjumlah  ;
					        $webdpditarik+=$row->dpditarik*-1  ;
					        
					        
				            
					        
					        
					    }
					
					
					
				}
				$totalweb+=	 $webkas+$webdebit+$webkredit+$webtransfer+$webmerchant+$webvoucher+$webpendapatandp+$webpembayaransurgery+$webdp+$webdpditarik;
				$totalwebreal+=	 $webkas+$webdebit+$webkredit+$webtransfer+$webmerchant;
				
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" class="px-1">Total Termasuk Piutang Surgery</td>	
				<td class="right px-1"><?= eFormatNumber($tkas,0); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tdebit,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tkredit,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($ttransfer,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tmerchant,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($total,0); ?></td>	
					 
				<td class="right px-1"><?= eFormatNumber($tvoucher,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tpiutang,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpendapatandp,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($tpembayaransurgery,0); ?></td>
				<td class="right px-1"><?= eFormatNumber($tdp,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($tdpditarik,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($ttotalsemua,0); ?></td>
				
				<td class="right px-1"><?= eFormatNumber($tcashback,0); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($tpiutangbayar,0); ?></td>	
				
			</tr>
			
			<tr>
				<td colspan="3" class="px-1">Total TANPA Piutang Surgery</td>	
				<td class="right px-1"><?= eFormatNumber($webkas,0); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($webdebit,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($webkredit,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($webtransfer,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($webmerchant,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($totalwebreal,0); ?></td>
					 
				<td class="right px-1"><?= eFormatNumber($webvoucher,0); ?></td>	 	
				<td class="right px-1"><?= eFormatNumber($webpiutang,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($webpendapatandp,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($webpembayaransurgery,0); ?></td>
				<td class="right px-1"><?= eFormatNumber($webdp,0); ?></td>	
				<td class="right px-1"><?= eFormatNumber($webdpditarik,0); ?></td>		
				<td class="right px-1"><?= eFormatNumber($totalweb,0); ?></td>
				 
				
			</tr>	 
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
	
	
	  
</div>