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
    
	                   
	                  
	                
	                  
	                   $query = "SELECT CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK,sutanggal,gkode,
	                  sum(
	                  ((sdkeluar*(sdharga-sddiskon))-sdbayardp)/sutotaltransaksi*(sutotaltransaksi-sutotalvoucher-SUNILAIPIUTANG)
	                  
	                  ) as subtotal,
	                  sum(
	                  case when sdkeluar*(sdharga-sddiskon)>0 then sdkeluar*icogs else 0 end
	                  ) as COGS,
	                  sum(
	                     case when sdkeluar*(sdharga-sddiskon)>0 then    coalesce(F_ALKES_COGS_HPP(sdid,suid),0)  else 0 end
	                  ) as alkeshpp,
	                  
	                  sum(
	                  case when sdkeluar*(sdharga-sddiskon)>0 then 
	                                case when CTTIPEPRODUK=1 then  ((sdkeluar*(sdharga-sddiskon)) /1.11)/sutotaltransaksi*(sutotaltransaksi-sutotalvoucher) else  sdkeluar*icogs end      
	                    else 0 end
	                  ) as nilaiproduk 
	                  
	                  
	                  
	                  FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU
	                  LEFT JOIN bitem ON IID=SDITEM 
	                  left join bgudang on gid=sucabang 
	                  left join bcoatipe_perpt on ctid=icoa2021   
	                  left join bmerchant on mckode=sumerchantjenis  
	                  WHERE coalesce(sdkepalaalkes,0) =0 and SUSTATUS<>9 and SUSUMBER = 'IP'   and ( SUNILAIPIUTANGBAYAR=0)   
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";   
	                  
	                  
	                  
	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by 
                    	  gkode, sutanggal , CTID,CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK  
                    	  
                    	  
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
				<th class="right px-1">Penjualan</th>
				<th class="right px-1">Produk</th> 
				<th class="right px-1">PPn 11%</th>  
			</tr>
		</thead>
		<tbody>
			<?	
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ; $ppn=0; 
				$tnilaiproduk = 0 ; $tnilaisales = 0 ; $tppn=0;  
				$tglnilaiproduk = 0 ; $tglsubtotal = 0 ; $tglppn=0; $jumlahdata=0;
				
				$tanggal = ''; $gudang = '';
				
				foreach ($datareport->data as $row) {
				    
				    $subtotal=$row->subtotal; 
    			    $tnilaisales += $subtotal ; 
    			    
    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >"; 
    					echo "<td class='px-1' colspan=4>".$row->gkode."</td>";   
    					echo "</tr>";	
    			   } 
    			   
    			   if ($tanggal !=  $row->sutanggal ) { 
    			       
    			       if ($tanggal != '' and $jumlahdata>1 ) {
    			           
    					echo "<tr>"; 
    					echo "<td class='px-2'>Jumlah ".$tanggal."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($tglsubtotal,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($tglnilaiproduk,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($tglppn,2)."</td>"; 
    					echo "</tr>";	
    					
				        
    			       }
    			       
    			       $jumlahdata=0;
    			       $tglnilaiproduk = 0 ; $tglsubtotal = 0 ;  $tglppn=0; 
    					
    			       
    			        echo "<tr >"; 
    					echo "<td class='px-2' colspan=4>".$row->sutanggal."</td>";   
    					echo "</tr>";	 
    					
    			   } 
    			   
    			   
    			    $tanggal = $row->sutanggal ; 
    			    $gudang = $row->gkode ;  
    			    
				    if ($row->CTTIDAKDITARIK==0) {
    				    if ($subtotal>0){
    				        if ($row->CTTIPEPRODUK==1){ 
    				             $nilaiproduk=$nilaiproduk/1.11;
    				        } 
    				            
    				        else {
    				            $nilaiproduk = $row->COGS ;
    				        } 
    				        
    				    } 
    				    $nilaiproduk=$row->nilaiproduk;
    					$ppn = ($nilaiproduk)*11/100;
				    }
				    
				    
				    
    				    
    					echo "<tr>"; 
    					echo "<td class='px-3'>".$row->CTNAMA."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiproduk,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($ppn,2)."</td>"; 
    					echo "</tr>";	
    					 
    					$tnilaiproduk += $nilaiproduk; 	
    					$tppn += $ppn ;
    					
    			        $tglnilaiproduk += $nilaiproduk; 
    					$tglppn += $ppn ;
    					$tglsubtotal += $subtotal;
    					$jumlahdata ++;
    					
    					
				        $nilaiproduk = 0 ; $subtotal = 0 ; $ppn=0;
    					
				   
				}
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk,2); ?></td> 
				<td class="right px-1"><?= eFormatNumber($tppn,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

 
	
</div>