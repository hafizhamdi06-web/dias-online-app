<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }		
    

    $CI =& get_instance();
    
	                   
	                  
	                  $query = "SELECT icogs, CTNAMA, 
	                  sdkeluar*(sdharga-sddiskon) as subtotal,sdkeluar,
	                  CTID,CTTIPEID ,CTTIPEPRODUK, 
	                  CASE WHEN SUSURGERYDP<>0 AND SUNILAIPIUTANGBAYAR<>0 THEN 'Piutang Surgery' ELSE  CTNAMA END AS CTNAMA ,
	                  CASE WHEN SUSURGERYDP<>0 AND SUNILAIPIUTANGBAYAR<>0 THEN 1 ELSE  CTTIDAKDITARIK END AS CTTIDAKDITARIK,  
	                  F_NAMACOA2021(ICOA2021) AS NAMACOA, coalesce(mcbiaya,0) as mcbiaya, 
	                  coalesce(F_ALKES_COGS_HPP(sdid,suid),0)  as alkeshpp , coalesce(F_ALKES_COGS(sdid,suid),0)  as alkes ,  coalesce(F_ALKES_HARGAJUAL(sdid,suid),0)    as alkesjual ,  
	                  coalesce(F_SUBTOTAL_SPA_KE0_IDPAKET(sdcatatankoli,sditem,sukontak,sdsodurutan),0) as totalke0  
	                  FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM left join bgudang on gid=sucabang left join bitem2 on i2iditem=iid  
	                  left join  bcoatipe_pendapatan on ctid=i2coapendapatan   left join bmerchant on mckode=sumerchantjenis  
	                  WHERE coalesce(sdkepalaalkes,0) =0 and SUSTATUS<>9 and SUSUMBER = 'IP'   and ( SUNILAIPIUTANGBAYAR=0)  
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";   
	                  
	                  
	                  
	                   $query = "SELECT CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK,
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
	                                case when CTTIPEPRODUK=1 then  ((sdkeluar*(sdharga-sddiskon)*80/100 ) /1.11)/sutotaltransaksi*(sutotaltransaksi-sutotalvoucher) else  sdkeluar*icogs end      
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
    
    
              


    
    
       $query2 = " SELECT gkode,pukode, iresep,iresepitter, ikomisi2020 as ikelompok2020,coalesce(F_SUBTOTAL_SPA_KE0_IDPAKET(sdcatatankoli,sditem,sukontak,sdsodurutan),0) as totalke0,
             SDDARIPAKET,sdkedatangan,  
            IMODEL,case when sdbariskepala=0 then coalesce(F_TOTAL_ALKES_BY_URUTAN(suid,case when sdurutanawal=0 then sdurutan else sdurutanawal end),0) else 0 end as alkes,
            case when iresep=1 then coalesce(F_SUBTOTAL_RESEP_NONAPOTEK(suid),0) else sdkeluar*(sdharga-sddiskon) end as subtotal,
            sdkeluar,sdharga,sddiskon,sunotransaksi,sutanggal,ikode,ikelompok21,  sales.KNAMA AS namasales,sales.KKODE AS kodesales  ,  
            sales.KIDABSENSI AS IDABSENSI, sales.KIDEMPLOYEE  AS IDEMPLOYEE ,  sales.KNAMAEMPLOYEE AS NAMAEMPLOYEE   
            
            FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM    inner JOIN bkontak sales ON sddokter=sales.KID  left join epaketu on puid=sdidpotongstok  
            left join bgudang on gid=sucabang
	               WHERE gpt <>13 and gpt<> 9 and gpt<>11 and sdbariskepala=0 and sales.KJENISKARYAWAN in (3,4) and  SUSTATUS<>9 and SUSUMBER = 'IP'    
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";  
	                  
	   
    
    
    

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
				<th class="left px-1">Pendapatan Dokter</th> 	 
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
    				        if ($row->CTTIPEPRODUK==1){
    				             $nilaiproduk=$subtotal*80/100 ;
    				             $nilaiproduk=$nilaiproduk/1.11;
    				        } 
    				            
    				        else {
    				            $nilaiproduk = $row->COGS ;
    				        } 
    				        
    				    } 
    				    $alkeshpp=$row->alkeshpp;
    				    $nilaiproduk=$row->nilaiproduk;
    					$ppn = ($nilaiproduk+$alkeshpp)*11/100;
    					$pendapatanklinik = ($subtotal-$nilaiproduk-$alkeshpp-$ppn)*70/100 ;
    					$pendapatandokter = ($subtotal-$nilaiproduk-$alkeshpp-$ppn)*30/100 ;
				    }
    				    
    					echo "<tr>"; 
    					echo "<td>".$row->CTNAMA."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiproduk,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($alkeshpp,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($ppn,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($pendapatanklinik,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($pendapatandokter,2)."</td>"; 
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
				<td class="right px-1"><?= eFormatNumber($tpendapatandokter,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 


<?
               
	  
     
            
             $query2 = " select sales.knama AS namasales,sales.kkode AS kodesales,
            sum( 
            case when ikelompok2020 in (1,2,4,12) or (ikelompok2020=11 and 
              ikode not in ('BODY SLIMMING I/M','CRYOLIPO','V REJUVENATION','FACE SLIMMING I/M'))
            then 
            
                case 
                when sddaripaket = 1 and sdkedatangan = 0 then 0
                when sddaripaket = 1 and sdkedatangan > 0 then coalesce(F_SUBTOTAL_SPA_KE0_IDPAKET(sdcatatankoli,sditem,sukontak,sdsodurutan),0)*sdkeluar
                when sddaripaket = 0 then  case when iresep=1 then coalesce(F_SUBTOTAL_RESEP_NONAPOTEK(suid),0) else sdkeluar*(sdharga-sddiskon) end
                else 0 end 
            
                
            else 0 end
            
            ) as subtotal 
            
            FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM    inner JOIN bkontak sales ON sddokter=sales.KID  left join epaketu on puid=sdidpotongstok  
            left join bgudang on gid=sucabang
	               WHERE gpt <>13 and gpt<> 9 and gpt<>11 and sdbariskepala=0 and sales.KJENISKARYAWAN in (3,4) and  SUSTATUS<>9 and SUSUMBER = 'IP'    
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";   
	                  
	                   if($idgudang != ""){
                        	$query2 .= " AND sucabang='".$idgudang."'";
                        }   
                        $query2 .= " group by   sales.knama, sales.kkode  "; 
                        
                        
                        
                        

            $datareport2 = $CI->M_transaksi->get_data_query($query2);
            $datareport2 = json_decode($datareport2);
    
    
    
    
?>

 
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="20%">Dokter</th> 
				<th class="right px-1" width="10%">Sub Total</th> 
				<th class="right px-1" width="10%">Komisi 2 %</th> 	 
			</tr>
		</thead>
		<tbody>
			<?	
			
			    $subtotal = 0  ; $komisi = 0 ; 
			    $tnilaisales = 0 ; $tkomisi = 0 ;
				
				foreach ($datareport2->data as $row) {
				    
				    $subtotal=$row->subtotal; 
    			    $tnilaisales += $subtotal ;   
    			    $komisi  = 2/100 *  $subtotal ; 
    			    $tkomisi += $komisi ;
 
    					echo "<tr>"; 
    					echo "<td>".$row->namasales."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
    					echo "</tr>";	
    					 
    				 
				   
				}
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1" >Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-1"><?= eFormatNumber($tkomisi,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>