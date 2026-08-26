<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }	
	$tampilNol =  $_POST['saldo'];		
    

    $CI =& get_instance();
    
	                   
	                  
	                 
	                  
	                  
	                  
	                   $query = "SELECT CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK,
	                  sum(
	                  ((sdkeluar*(sdharga-sddiskon))-sdbayardp)/sutotaltransaksi*(sutotaltransaksi-sutotalvoucher-SUNILAIPIUTANG)
	                  
	                  ) as subtotal,
	                  sum(
	                  case when sdkeluar*(sdharga-sddiskon)>0 then sdkeluar* (case when sutanggal < '2026/04/11' then 
					   (icogs_lama * 1.05) * 1.05
					  else  (icogs * 1.05) * 1.05 end) else 0 end
	                  ) as COGS,
	                  sum(
	                     case when sdkeluar*(sdharga-sddiskon)>0 then    (coalesce(F_ALKES_COGS_HPP(sdid,suid),0) * 1.05 ) * 1.05  else 0 end
	                  ) as alkeshpp,
	                  
	                  sum(
	                  case when sdkeluar*(sdharga-sddiskon)>0 then 
	                                sdkeluar*(case when sutanggal < '2026/04/11' then   (icogs_lama * 1.05) * 1.05  else  (icogs * 1.05) * 1.05 end)       
	                    else 0 end
	                  ) as nilaiproduk 
	                  
	                  
	                  
	                  FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU
	                  LEFT JOIN bitem ON IID=SDITEM 
	                  left join bgudang on gid=sucabang 
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan on ctid=i2coapendapatan   
	                  left join bmerchant on mckode=sumerchantjenis  
	                  WHERE coalesce(sdkepalaalkes,0) =0 and SUSTATUS<>9 and SUSUMBER = 'IP'   and ( SUNILAIPIUTANGBAYAR=0)   
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";   
	                  
	                  
	                  
	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by 
                    	  CTURUTAN,CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK   
                    	  
                    	  
                    	  ";
	  
	  

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
				<th class="left px-1">Jenis</th> 
				<th class="left px-1">Penjualan</th>
				<th class="left px-1">Produk</th>
				<th class="left px-1">Alkes</th>
				<th class="left px-1">PPn 11%</th>
				<th class="left px-1">Pendapatan Klinik</th>   
			</tr>
		</thead>
		<tbody>
			<?	
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ; $nilaialkes = 0 ; $ppn=0; $pendapatanklinik=0; $pendapatandokter=0; $alkeshpp=0;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ; $tnilaialkes = 0 ; $tppn=0;  $tpendapatanklinik=0; $tpendapatandokter=0;
				
				foreach ($datareport->data as $row) {
				    
				    $subtotal=$row->subtotal; 
    			    $tnilaisales += $subtotal ; 
    			    
				    if ($row->CTTIDAKDITARIK==0) {
    				    if ($subtotal>0){ 
    				            $nilaiproduk = $row->COGS ;  
    				    } 
    				    $alkeshpp=$row->alkeshpp;
    				    $nilaiproduk=$row->nilaiproduk;
    					$ppn = ($nilaiproduk+$alkeshpp)*11/100;
    					$pendapatanklinik = ($subtotal-$nilaiproduk-$alkeshpp-$ppn)  ;
    					$pendapatandokter = 0 ;
				    }
    				    
    					echo "<tr>"; 
    					echo "<td>".$row->CTNAMA."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiproduk,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($alkeshpp,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($ppn,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($pendapatanklinik,2)."</td>";  
    					echo "</tr>";	
    					 
    					$tnilaiproduk += $nilaiproduk; 	
    					$tnilaialkes += $alkeshpp ; 
    					$tppn += $ppn ;
    					$tpendapatanklinik += $pendapatanklinik;
    					$tpendapatandokter += $pendapatandokter;
    					
    					
				$nilaiproduk = 0 ; $subtotal = 0 ; $nilaialkes = 0 ; $ppn=0; $pendapatanklinik=0; $pendapatandokter=0; $alkeshpp=0;
    					
				   
				}
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk,2); ?></td> 
				<td class="right px-1"><?= eFormatNumber($tnilaialkes,2); ?></td> 
				<td class="right px-1"><?= eFormatNumber($tppn,2); ?></td> 
				<td class="right px-1"><?= eFormatNumber($tpendapatanklinik,2); ?></td>  
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

 
	
</div>