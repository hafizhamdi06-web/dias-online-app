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
	               WHERE sdbariskepala=0 and sales.KJENISKARYAWAN in (3,4) and  SUSTATUS<>9 and SUSUMBER = 'IP'    
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
	               WHERE  sdbariskepala=0 and sales.KJENISKARYAWAN in (3,4) and  SUSTATUS<>9 and SUSUMBER = 'IP'    
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
				<th class="left px-1" colspan="3">Komisi Tindakan 2 %</th>   
			</tr>
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
    			    
    			    if($komisi == 0 && $tampilNol == 0){
        				continue;
        			}
 
    					echo "<tr>"; 
    					echo "<td class='px-1'>".$row->namasales."</td>";  
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
	
	
	

<?
               
	   
                        $query2 = "";
                         $query2 = " select namasales, kodesales, gkode,
                         sum(
                                    case 
                                    when ikelompok2020 = 10 and  tipex=1 and coalesce(sdprorecom,0) = 0  then 50/100*subtotal
                                    when ikelompok2020 = 10 and  tipex=1 and coalesce(sdprorecom,0) <> 0  then 25/100*subtotal
                                    when ikelompok2020 = 10 and  tipex=0 and coalesce(susalesmarketing,0) > 0  then 50/100*subtotal
                                    when ikelompok2020 = 10  then subtotal
                                    when ikelompok2020 = 9 and  (ikode like '%SWAB ANTIGEN%' or ikode like '%PCR%' ) then 0
                                    when ikelompok2020 = 9  then subtotal 
                                    when ikelompok2020 <>5 and  ikelompok2020 <>6 and ikelompok2020 <>8 and ikelompok2020 <>10    then subtotal  
                                    else 0 
                                    end
                         ) as subtotalx
                                    from (
                         
                         
                         
                                    select sdid,ikomisi2020 as ikelompok2020,sdprorecom,susalesmarketing,ikode,
                                    0 as tipex, sales.knama AS namasales,sales.kkode AS kodesales, gkode,
                                    case when iresep=1 then coalesce(F_SUBTOTAL_RESEP_NONAPOTEK(suid),0) else sdkeluar*(sdharga-sddiskon) end    as subtotal  
                                    FROM fstokd 
                                    LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM    
                                    inner JOIN bkontak sales ON   case when  coalesce(sdreferal,0) <> 0  then  sdreferal else sddokter end = sales.KID 
                                    left join bgudang on gid=kcabang  left join epaketu on puid=sdidpotongstok
                                    WHERE   sdbariskepala=0 
                                    and sales.KJENISKARYAWAN in (3,4) and  SUSTATUS<>9 and SUSUMBER = 'IP'
                    	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'     ";  
                    	                  
                    	                     if($idgudang != ""){
                                            	$query2 .= " AND sucabang='".$idgudang."'";
                                            }   
                                           
                    	                  
                    	                  
                    	                   $query2 .= " union " ;
                    	                  
                    	   $query2 .= " select sdid,ikomisi2020 as ikelompok2020,sdprorecom,susalesmarketing,ikode,
                    	            1 as tipex,  sales.knama AS namasales,sales.kkode AS kodesales, gkode, 
                                    case when iresep=1 then coalesce(F_SUBTOTAL_RESEP_NONAPOTEK(suid),0) else sdkeluar*(sdharga-sddiskon) end    as subtotal  
                                    FROM fstokd 
                                    LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM    inner JOIN bkontak sales ON   susalesmarketing = sales.KID     
                                    left join bgudang on gid=kcabang  left join epaketu on puid=sdidpotongstok
                                    WHERE  sales.kdokterbedah<>0  and sdbariskepala=0 
                                    and sales.KJENISKARYAWAN in (3,4) and  SUSTATUS<>9 and SUSUMBER = 'IP'
                    	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'      ";   
                    	                  
                    	                     if($idgudang != ""){
                                            	$query2 .= " AND sucabang='".$idgudang."'";
                                            }   
                                           
                    	                	$query2 .= " ) a group by namasales, kodesales, gkode " ;  
                    	                
                                            
                        
                        
             
            $datareport2 = $CI->M_transaksi->get_data_query($query2);
            $datareport2 = json_decode($datareport2);
    
    
    
    
?>

 
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan="4">Komisi Omset</th>   
			</tr>
			<tr class="bg-dark">
				<th class="left px-1" width="20%">Dokter</th> 
				<th class="left px-1" width="20%">Cabang</th> 
				<th class="right px-1" width="10%">Nilai Penjualan</th> 
				<th class="right px-1" width="10%">Komisi</th> 	 
			</tr>
		</thead>
		<tbody>
			<?	
			
			    $subtotal = 0  ; $komisi = 0 ; 
			    $tnilaisales = 0 ; $tkomisi = 0 ;
				
				foreach ($datareport2->data as $row) {
				    
				    $subtotal=$row->subtotalx; 
    			    $tnilaisales += $subtotal ;   
    			    $komisi  = 0 ;


                            if ( $subtotal >= 700000000)  {
                                $komisi = $komisi + (  11/100 * ($subtotal - 700000000) );
                                $komisi = $komisi + (  8/100 * 150000000 );
                                $komisi = $komisi + (  5/100 * 150000000 );
                                $komisi = $komisi + (  2.5/100 * 150000000 );
                                $komisi = $komisi + (  1/100 * 249999999 ) ; }
                                
                                else if ($subtotal >= 550000000)  {
                                 $komisi = $komisi + (  8/100 * ($subtotal - 550000000) ) ;
                                $komisi = $komisi + (  5/100 * 150000000 );
                                $komisi = $komisi + (  2.5/100 * 150000000 );
                                $komisi = $komisi + (  1/100 * 249999999 ) ;  }
                                
                                else if ($subtotal >= 400000000)  {
                                $komisi = $komisi + (  5/100 * ($subtotal - 400000000) )  ;
                                $komisi = $komisi + (  2.5/100 * 150000000 );
                                $komisi = $komisi + (  1/100 * 249999999 )  ;}
                                
                                else if ($subtotal >= 250000000)    {
                                $komisi = $komisi + (  2.5/100 * ($subtotal - 250000000) )  ;
                                $komisi = $komisi + (  1/100 * 249999999 ) ; }
                                
                                else if ($subtotal >= 100000000 )  {
                                $komisi = $komisi + (  1/100 * ($subtotal  ) )  ;} 
                                
                                else if ($subtotal >= 80000000 )  {
                                $komisi = $komisi + (  0.5/100 * ($subtotal  ) ) ;  }
                                
                                
    			    
    			    $tkomisi += $komisi ;
    			    
    			      if($komisi == 0 && $tampilNol == 0){
        				continue;
        			}
 
    					echo "<tr>"; 
    					echo "<td class='px-1'>".$row->namasales."</td>";  
    					echo "<td class='px-1'>".$row->gkode."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
    					echo "</tr>";	
    					 
    				 
				   
				}
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1" colspan="2">Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-1"><?= eFormatNumber($tkomisi,2); ?></td> 
			</tr> 	
			
				<tr>
				<td class="px-1" colspan="2">Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-1"><?= eFormatNumber($tkomisi,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
	
<?
      	
	
	                     $query2 = "";
                      
                    	                	
                    
                      
                         $query2 = " SELECT gkode,gid, sales.knama as namasales, sutanggal, sunotransaksi, ikomisi2020 as ikelompok2020,ik2kode, ikode , sum(sdkeluar*(sdharga-sddiskon)) as subtotal,  imodel,
                         sum( case when sdkeluar*(sdharga-sddiskon)>=150000 then 1 else 0 end) as qty_tindakan_inj, count(sunotransaksi) as jumlahtransaksi ,
                         sum(case when ikode like '%PRP%' then
                                case when sdkeluar*(sdharga-sddiskon) > 1000000 then 5/100 * sdkeluar*(sdharga-sddiskon)
                                     when sdkeluar*(sdharga-sddiskon) > 500000 then 3.5/100 * sdkeluar*(sdharga-sddiskon)
                                     when sdkeluar*(sdharga-sddiskon) > 300000 then 2.5/100 * sdkeluar*(sdharga-sddiskon) else 0 end
                         else 0 end) as komisiprp,
                         case when imodel in ('ANY IV INJECTION','ANY MESSO INJ','EXOGLOW','PRP') THEN imodel else '' end as model,
                         concat(sdkaryawan,sutanggal,ikomisi2020) as salestanggalkelompok, F_SUBTOTAL_SPA(SUID) as totalspa
                                FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM  LEFT JOIN bsatuan ON SID=ISATUAN   
                                LEFT JOIN bkontak pelanggan ON SUKONTAK=pelanggan.KID inner JOIN bkontak sales ON sdkaryawan=sales.KID   
                                left join bitemkelompok on ikid=ikelompokbaru left join bitemkelompok2020 on ik2id=ikomisi2020   
                                left join bkontak referal on sdreferal=referal.kid  left join epaketu on puid=sdidpotongstok   
                                left join bgudang on gid=sucabang left join bnamapt on npid=gpt  
                                
                                WHERE    SUSTATUS<>9 and SUSUMBER = 'IP' 
                                 and (ikomisi2020 = 1 or ikomisi2020 = 2 or (ikomisi2020 = 3 and sddaripaket=0) 
                                 or ikomisi2020 = 4  or ikomisi2020 = 10 or (ikomisi2020 = 11 and sddaripaket=0) or ikomisi2020 = 12   )    
                                 
                                AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'      "; 
                    	                	
                    	         if($idgudang != ""){
                                            	$query2 .= " AND sucabang='".$idgudang."'";
                                            }                           
                        
                        $query2 .= " group by gkode,gid,sales.knama, ik2kode, sutanggal,sunotransaksi,ikode, ikomisi2020, imodel,  F_SUBTOTAL_SPA(SUID) ";
             
            $datareport2 = $CI->M_transaksi->get_data_query($query2);
            $datareport2 = json_decode($datareport2);
    
    
    
    
?>

 
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan="5">Komisi Perawat Therapist 2</th>   
			</tr>
			<tr class="bg-dark">
				<th class="left px-1" width="10%">Cabang</th> 
				<th class="left px-1" width="10%">Nama Perawat/Therapist</th> 
				<th class="left px-1" width="10%">Kelompok</th> 
				<th class="left px-1" width="10%">Model</th> 
				<th class="left px-1" width="10%">Tanggal</th> 
				<th class="left px-1" width="10%">No Transaksi</th> 
				<th class="left px-1" width="10%">Produk/Tindakan</th> 
				<th class="right px-1" width="10%">Nilai Penjualan</th> 
				<th class="right px-1" width="10%">Komisi</th> 	 
			</tr>
		</thead>
		<tbody>
			<?	
			
			    $subtotal = 0  ; $komisi = 0 ; 
			    $tnilaisales = 0 ; $tkomisi = 0 ; $ikelompok2020 = 0;
			    $qty_tindakan_inj = 0 ; $jumlahtransaksi = 0 ;
			    $nama = ''; $subtotalmedical = 0 ; $salestanggalkelompok = '' ; $tanggal = '' ; $totalspa = 0 ; $qty_spa_perhari = 0; $noip = ''; $gkomisi = 0;
				
				foreach ($datareport2->data as $row) { 
				      $komisi=0 ;
				      
				      if ($ikelompok2020==4 or $ikelompok2020==3 ) {
				          
				                if ($ikelompok2020==3) { 
        				                if ( $noip != $row->sunotransaksi and $noip != '' ) { 
                        			          if ( round($totalspa,1) >= 150000 ) { 
                            			          $qty_spa_perhari = $qty_spa_perhari + 1 ;
                        			          } 
                            			          $totalspa=0;
                        			      }  
				                }
				          
				          
    			    
                    			    if ($salestanggalkelompok != $row->salestanggalkelompok and $salestanggalkelompok != ''    ) {
                    			    //if ($tanggal != $row->sutanggal and $tanggal != ''    ) {   
                    			         if ($ikelompok2020==4) {  
                                			        if ($subtotalmedical > 5000000 ) {
                                			            $komisi = 10/100 * $subtotalmedical ;
                                			        }
                                			        else if ($subtotalmedical >= 2500000 ) {
                                			            $komisi = 5/100 * $subtotalmedical ;
                                			        } 
                            
                                                    if ($komisi > 0) {
                                                             $komisi = $komisi * 10/100 ;
                                                        
                                                    }
                    			         }
                    			         else if ($ikelompok2020==3) {  
                    			             
                    			             if ($qty_spa_perhari > 20 ) {
                    			                 $komisi = $qty_spa_perhari * 40000 ;
                    			             } 
                    			             else  if ($qty_spa_perhari > 10 ) {
                    			                 $komisi = $qty_spa_perhari * 25000;
                    			             } 
                    			             else  if ( $row->gid==32 ) {
                    			                 $komisi = $qty_spa_perhari * 10000;
                    			             } 
                    			             else  {
                    			                 $komisi = $qty_spa_perhari * 5000;
                    			             } 
                    			             
                    			                  
                    			         }
                    			         
                    			         $tkomisi+=$komisi ;
                    			         $gkomisi+=$komisi ;
                                        
                        
                                             
                    					
                                             
                        			        echo "<tr>"; 
                        					echo "<td class='px-1'></td>";  
                        					echo "<td class='px-1'></td>"; 
                        					echo "<td class='px-1'></td>"; 
                        					echo "<td class='px-1'></td>"; 
                        					echo "<td class='px-1' colspan=2>Jumlah ".$tanggal."</td>";  
                        					echo "<td class='px-1'>".$qty_spa_perhari."</td>";   
                        					echo "<td class='right px-1'>".eFormatNumber($totalspa,2)."</td>"; 
                        					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
                        					echo "</tr>";		
                        					
                        					$subtotalmedical=0;
                        					$qty_spa_perhari=0;  
                    			        
                    			        
                    			    }   
				     
    			    
    			    
				      }
    			    
    			    if ($nama != $row->namasales and $nama != '' ) {
    			        echo "<tr>";  
    					echo "<td class='px-1 ' colspan=7>".$nama."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($tnilaisales,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					$tnilaisales=0;
    					$tkomisi=0;
    			    }
    			    
    			    
    			    $subtotal=$row->subtotal; 
    			    $tnilaisales += $subtotal ;   
    			    $komisi  = 0 ;
    			    $ikelompok2020 =$row->ikelompok2020; 
    			    if ($ikelompok2020==4) $subtotalmedical += $row->subtotal;  
    			    
    			        if ($ikelompok2020==3) {   
        			              if (substr_count($row->ikode,"ESSENTIAL FACIAL")>0)   {
                			            $totalspa +=  $row->totalspa + $row->subtotal  ;
                			        }  
                			      else if ( substr_count($row->ikode,"FACE SLIMMING I/M")>0 or substr_count($row->ikode,"NH FACE SLIMMING")>0 or substr_count($row->ikode,"BODY SLIMMING I/M")>0  or substr_count($row->ikode,"NH BODY SLIMMING")>0 or substr_count($row->ikode,"CRYOLIPO")>0  )   {
                			            $totalspa +=  0  ;
                			        }  
                			      else {
                			            $totalspa +=   $row->subtotal  ; 
                			      } 
    			         } 
    			         
    			            
    			     
    			    $noip = $row->sunotransaksi ;  
    			    
    			   if ($ikelompok2020==1) {
    			       $komisi= 5/100 * $subtotal ;
    			       $komisi= 10/100 * $komisi ;
    			   }
    			   else if ($ikelompok2020==2) { 
    			           $qty_tindakan_inj  = $row->qty_tindakan_inj ;
    			           $jumlahtransaksi  = $row->jumlahtransaksi ;
    			           $komisiprp  = $row->komisiprp ;
    			           
    			        if (substr_count($row->ikode,"COUTER")>0)   {
    			            $komisi= 10/100 * $subtotal ;
    			        } 
    			        else if (substr_count($row->ikode,"DERMAPEN")>0)   {
    			            $komisi= 10/100 * $subtotal ;
    			        } 
    			        else if (substr_count($row->ikode,"COLLAGEN")>0)   {
    			            $komisi= 5/100 * $subtotal ;
    			        } 
    			        else if (substr_count($row->ikode,"PUNCH")>0)   {
    			            $komisi= 10/100 * $subtotal ;
    			        } 
    			        else if (substr_count($row->ikode,"EXSISI")>0)   {
    			            $komisi= 30/100 * $subtotal ;
    			        } 
    			        else if (substr_count($row->ikode,"JAHIT LUKA")>0)   {
    			            $komisi= 30/100 * $subtotal ;
    			        } 
    			        else if (substr_count($row->imodel,"ANY IV INJECTION")>0)   {
    			            $komisi= 5000 * $qty_tindakan_inj ;
    			        } 
    			        else if (substr_count($row->imodel,"ANY MESSO INJ")>0)   {
    			            $komisi= 5000 * $qty_tindakan_inj ;
    			        } 
    			        else if (substr_count($row->ikode,"INJ")>0)   {
    			            $komisi= 5000 * $qty_tindakan_inj ;
    			        } 
    			        else if (substr_count($row->ikode,"SUBCISION")>0)   {
    			            $komisi= 20000 * $jumlahtransaksi ;
    			        } 
    			        else if (substr_count($row->ikode,"PEEL")>0)   {
    			            $komisi= 20000 * $jumlahtransaksi ; 
    			        }  
    			        else if (substr_count($row->imodel,"EXOGLOW")>0)   {
    			            $komisi= 5000   ; 
    			        } 
    			        else if (substr_count($row->imodel,"PRP")>0)   {
    			            $komisi= $komisiprp ; 
    			        }  
    			        
    			        if ($komisi>0 ) { 
            			         if (substr_count($row->imodel,"PRP")>0)   {
            			           $komisi=30/100 * $komisi ;
            			            }  
            			         else {
            			             $komisi=10/100 * $komisi ;
            			         }
    			        } 
    			   }
    			   else if ($ikelompok2020==3) { 
    			           $qty_spa  = $row->qty_tindakan_inj ; 
    			           
    			        if (substr_count($row->ikode,"BODY SLIMMING")>0)   {
    			            $komisi= 50000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"FACE SLIMMING")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"FACE EXILLIS")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"V REJUVENATION")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"V EXILIS")>0)   {
    			            $komisi= 50000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"CRYOLIPO")>0)   {
    			            $komisi= 100000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"PREMIUM FACE REJUVENATION")>0)   {
    			            $komisi= 500000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"SPA SIGNATURE")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"BODY TIGHTENING")>0)   {
    			            $komisi= 50000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"JAWLINE & NECK TIGHTENING")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"FACIAL TIGHTENING")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }   
    			   }
    			   else if ($ikelompok2020==11) { 
    			           $qty_spa  = $row->qty_tindakan_inj ; 
    			           
    			        if (substr_count($row->ikode,"BODY SLIMMING")>0)   {
    			            $komisi= 50000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"FACE SLIMMING")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"FACE EXILLIS")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"V REJUVENATION")>0)   {
    			            $komisi= 25000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"V EXILIS")>0)   {
    			            $komisi= 50000 * $qty_spa ;
    			        }  
    			        else if (substr_count($row->ikode,"CRYOLIPO")>0)   {
    			            $komisi= 100000 * $qty_spa ;
    			        }   
    			   }
    			   
    			   
    			    $nama = $row->namasales; 
    			    $salestanggalkelompok = $row->salestanggalkelompok ;
    			    $tanggal = $row->sutanggal; 
    		
 
    			    
    			    $tkomisi += $komisi ;
    			    $gkomisi += $komisi ;
    			    
    			    
    			     
    			  
    					echo "<tr>"; 
    					echo "<td class='px-1'>".$row->gkode."</td>";  
    					echo "<td class='px-1'>".$row->namasales."</td>"; 
    					echo "<td class='px-1'>".$row->ik2kode."</td>"; 
    					echo "<td class='px-1'>".$row->model."</td>"; 
    					echo "<td class='px-1'>".$row->sutanggal."</td>";  
    					echo "<td class='px-1'>".$row->sunotransaksi."</td>";  
    					echo "<td class='px-1'>".$row->ikode."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
    					echo "</tr>";	
    			   
    					 
    				 
				   
				}
				
				             
        				                
                        			          if ( round($totalspa,1) >= 150000 ) { 
                            			          $qty_spa_perhari = $qty_spa_perhari + 1 ;
                        			          }  
			 
                    			        
                    			         if ($ikelompok2020==4) {  
                                			        if ($subtotalmedical > 5000000 ) {
                                			            $komisi = 10/100 * $subtotalmedical ;
                                			        }
                                			        else if ($subtotalmedical >= 2500000 ) {
                                			            $komisi = 5/100 * $subtotalmedical ;
                                			        } 
                            
                                                    if ($komisi > 0) {
                                                             $komisi = $komisi * 10/100 ;
                                                        
                                                    }
                    			         }
                    			         else if ($ikelompok2020==3) {  
                    			             
                    			             if ($qty_spa_perhari > 20 ) {
                    			                 $komisi = $qty_spa_perhari * 40000 ;
                    			             } 
                    			             else  if ($qty_spa_perhari > 10 ) {
                    			                 $komisi = $qty_spa_perhari * 25000;
                    			             } 
                    			             else  if ( $row->gid==32 ) {
                    			                 $komisi = $qty_spa_perhari * 10000;
                    			             } 
                    			             else  {
                    			                 $komisi = $qty_spa_perhari * 5000;
                    			             } 
                    			             
                    			                  
                    			         }
                    			         
                    			         $tkomisi+=$komisi ;
                                         $gkomisi+=$komisi ;
                        
                                         
                                        
                            			    
                                         
                    			        echo "<tr>"; 
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1'></td>"; 
                    					echo "<td class='px-1'></td>"; 
                    					echo "<td class='px-1'></td>"; 
                    					echo "<td class='px-1' colspan=2>Jumlah ".$tanggal."</td>";  
                    					echo "<td class='px-1'>".$qty_spa_perhari."</td>";   
                    					echo "<td class='right px-1'>".eFormatNumber($subtotalmedical,2)."</td>"; 
                    					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
                    					echo "</tr>";	
                    					
                            		
                    					$subtotalmedical=0;
                    					$qty_spa_perhari=0;
                    					
                    			         
                    			    
				 
    			        echo "<tr>";  
    					echo "<td class='px-1'  colspan=7 >".$nama."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($tnilaisales,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					 
    					
    					$tnilaisales=0;
    					$tkomisi=0;
				
			?>
		</tbody>
		<tfoot>
		 	 
		<?
		
		echo "<tr>";  
    					echo "<td class='px-1'  colspan=7 >TOTAL</td>";  
    					echo "<td class='right px-1'></td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($gkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					
    					?>
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
	
	<?
	
	     
     


	
	  $query2 = " SELECT gkode,gid, sales.knama as namasales, sdcatatankoli,sdkedatangan,concat(sdcatatankoli,sdkedatangan) as nopaketkedatangan, sunotransaksi,sutanggal,ikode,coalesce(ikomisi2020,0) as ikelompok2020,ik2kode, npnamaclinic,
                         concat(sdkaryawan,sutanggal,ikomisi2020) as salestanggalkelompok, coalesce(F_SUBTOTAL_SPA_KE0(sdcatatankoli,sditem,sukontak),0) as totalspa 

                                FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM  LEFT JOIN bsatuan ON SID=ISATUAN   
                                LEFT JOIN bkontak pelanggan ON SUKONTAK=pelanggan.KID inner JOIN bkontak sales ON sdkaryawan=sales.KID   
                                left join bitemkelompok on ikid=ikelompokbaru left join bitemkelompok2020 on ik2id=ikomisi2020   
                                left join bkontak referal on sdreferal=referal.kid  left join epaketu on puid=sdidpotongstok   
                                left join bgudang on gid=sucabang left join bnamapt on npid=gpt  
                                
                                WHERE    SUSTATUS<>9 and SUSUMBER = 'IP' and ikomisi2020 in (3,11) and sdkedatangan >0 and sddaripaket<>0   
                                AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'      "; 
                    	                	
                    	         if($idgudang != ""){
                                            	$query2 .= " AND sucabang='".$idgudang."'";
                                            }                           
                        
                        $query2 .= " order by gkode,gid,sales.knama, ik2kode, sutanggal,sunotransaksi,ikode ";
             
            $datareport2 = $CI->M_transaksi->get_data_query($query2);
            $datareport2 = json_decode($datareport2);
            
            ?>
	
		<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan="5">Komisi SPA Paket</th>   
			</tr>
			<tr class="bg-dark">
				<th class="left px-1" width="10%">Cabang</th> 
				<th class="left px-1" width="10%">Nama Perawat/Therapist</th> 
				<th class="left px-1" width="10%">Kelompok</th>  
				<th class="left px-1" width="5%">Tanggal</th> 
				<th class="left px-1" width="5%">No Transaksi</th> 
				<th class="left px-1" width="10%">Produk/Tindakan</th> 
				<th class="left px-1" width="5%">No</th> 
				<th class="left px-1" width="5%">Ke</th> 
				<th class="left px-1" width="5%">Jumlah</th> 
				<th class="right px-1" width="10%">Nilai Penjualan</th> 
				<th class="right px-1" width="10%">Komisi</th> 	 
			</tr>
		</thead>
		<tbody>
			<?	
			
			    $subtotal = 0  ; $komisi = 0 ; 
			    $tnilaisales = 0 ; $tkomisi = 0 ; $ikelompok2020 = 0;
			    $qty_tindakan_inj = 0 ; $jumlahtransaksi = 0 ;
			    $nama = ''; $subtotalmedical = 0 ; $salestanggalkelompok = '' ; $tanggal = '' ; $totalspa = 0 ; $qty_spa_perhari = 0; $noip = ''; $gkomisi = 0;
			    $t_qty_tindakan_spa=0; $tkomisi=0; $qty_tindakan_spa = 0 ; $nopaketkedatangan = '' ; 
			    
			        
				
				foreach ($datareport2->data as $row) { 
				      $komisi=0 ; 
				      
				      
				      
				       if ($ikelompok2020==3 or $ikelompok2020==11) {   
    			    
                    			    if ( $salestanggalkelompok != $row->salestanggalkelompok and $salestanggalkelompok != ''    ) {
                    			        
                    			          	        if ($t_qty_tindakan_spa > 20 ) {
                                			            $komisi = 40000 * $t_qty_tindakan_spa ;
                                			        }
                                			        else if ($t_qty_tindakan_spa > 10 ) {
                                			            $komisi = 25000 * $t_qty_tindakan_spa ;
                                			        } 
                                			        else   {
                                			            $komisi = 5000 * $t_qty_tindakan_spa ;
                                			        }  
                                			        
                    			         $tkomisi+=$komisi ;
                    			         $gkomisi+=$komisi ; 
                    			         
                    			           
                                         
                    			        echo "<tr>"; 
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1'></td>"; 
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1' colspan=2>Jumlah ".$tanggal."</td>";  
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1'></td>";   
                    					echo "<td class='px-1'>".$t_qty_tindakan_spa."</td>";   
                    					echo "<td class='right px-1'>".eFormatNumber(0,2)."</td>"; 
                    					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
                    					echo "</tr>";		
                    					
                    					$subtotalmedical=0;
                    					$qty_spa_perhari=0; 
                    					$qty_tindakan_spa=0 ;  
                    					$t_qty_tindakan_spa=0;
                    					$komisi = 0 ;
                    			    }   
				      }
				      
    			    
    			    if ($nama != $row->namasales and $nama != '' ) {
    			        echo "<tr>";  
    					echo "<td class='px-1 ' colspan=9>".$nama."</td>";    
                    					echo "<td class='right px-1'></td>"; 
                    					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					$tnilaisales=0;
    					$tkomisi=0;
    			    }
    			    
    			     
    			    
    			    $totalspa = $row->totalspa ;
    			    if ($totalspa>=150000) {
            			        if (substr_count($row->ikode,"BODY SLIMMING")>0) { 
            			            $komisi= 50000   ;
            			        } 
            			        else if (substr_count($row->ikode,"FACE SLIMMING")>0) { 
            			            $komisi= 25000   ;
            			        } 
            			        else if (substr_count($row->ikode,"FACE EXILLIS")>0) { 
            			            $komisi= 25000   ;
            			        } 
            			        else if (substr_count($row->ikode,"V REJUVENATION")>0) { 
            			            $komisi= 25000   ;
            			        } 
            			        else if (substr_count($row->ikode,"V EXILIS")>0) { 
            			            $komisi= 50000   ;
            			        } 
            			        else if (substr_count($row->ikode,"CRYOLIPO")>0) { 
            			            $komisi= 100000  ;
            			        } 
            			        else if (substr_count($row->ikode,"PREMIUM FACE REJUVENATION")>0) { 
            			            $komisi= 50000  ;
            			        } 
            			        else if (substr_count($row->ikode,"BODY TIGHTENING")>0) { 
            			            $komisi= 50000  ;
            			        } 
            			        else if (substr_count($row->ikode,"JAWLINE & NECK TIGHTENING")>0) { 
            			            $komisi= 25000   ;
            			        } 
            			        else if (substr_count($row->ikode,"FACIAL TIGHTENING")>0) { 
            			            $komisi= 25000  ;
            			        } 
            			        else { 
            			            if ( $nopaketkedatangan != '' and $nopaketkedatangan != $row->nopaketkedatangan )  $t_qty_tindakan_spa++ ;
            			            if ( $nopaketkedatangan == ''  )  $t_qty_tindakan_spa++ ;
            			            $komisi= 0  ;
            			        }
    			        }
    			     
    			    
    			    
    			    $subtotal=$row->totalspa; 
    			    $tnilaisales += $subtotal ;   
    			   
    			    $ikelompok2020 =$row->ikelompok2020;   
    			    $noip = $row->sunotransaksi ;  
    			    $nama = $row->namasales; 
    			    $salestanggalkelompok = $row->salestanggalkelompok ;
    			    $tanggal = $row->sutanggal;  
    			    $tkomisi += $komisi ;
    			    $gkomisi += $komisi ;
    			    $subtotalmedical += $subtotal ;
    			    $nopaketkedatangan = $row->nopaketkedatangan;   
    			    
    			  
    					echo "<tr>"; 
    					echo "<td class='px-1'>".$row->gkode."</td>";  
    					echo "<td class='px-1'>".$row->namasales."</td>"; 
    					echo "<td class='px-1'>".$row->ik2kode."</td>";  
    					echo "<td class='px-1'>".$row->sutanggal."</td>";  
    					echo "<td class='px-1'>".$row->sunotransaksi."</td>";  
    					echo "<td class='px-1'>".$row->ikode."</td>";  
    					echo "<td class='px-1'>".$row->sdcatatankoli."</td>";  
    					echo "<td class='px-1'>".$row->sdkedatangan."</td>";  
    					echo "<td class='px-1'>".$qty_tindakan_spa."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
    					echo "</tr>";	
    					
    				 
    					 
    				 
				   
				}
				
				                        if ($t_qty_tindakan_spa > 20 ) {
                                			            $komisi = 40000 * $t_qty_tindakan_spa ;
                                			        }
                                			        else if ($t_qty_tindakan_spa > 10 ) {
                                			            $komisi = 25000 * $t_qty_tindakan_spa ;
                                			        } 
                                			        else   {
                                			            $komisi = 5000 * $t_qty_tindakan_spa ;
                                			        }  
                                			        
                    			         $tkomisi+=$komisi ;
                    			         $gkomisi+=$komisi ; 
                    			         
                    			    
                            			    
				
				                        echo "<tr>"; 
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1'></td>"; 
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1' colspan=2>Jumlah ".$tanggal."</td>";  
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1'></td>";  
                    					echo "<td class='px-1'></td>";   
                    					echo "<td class='px-1'>".$t_qty_tindakan_spa."</td>";   
                    					echo "<td class='right px-1'>".eFormatNumber(0,2)."</td>"; 
                    					echo "<td class='right px-1'>".eFormatNumber($komisi,2)."</td>"; 
                    					echo "</tr>";
                            		
				
				 	
                    					
                    					$subtotalmedical=0;
                    					$qty_spa_perhari=0;
                    					
                    			         
                    			    
				 
    			        echo "<tr>";  
    					echo "<td class='px-1'  colspan=9 >".$nama."</td>";  
    					echo "<td class='right px-1'></td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					 
    					
    					$tnilaisales=0;
    					$tkomisi=0;
				
			?>
		</tbody>
		<tfoot>
		 	 
		<?
		
		echo "<tr>";  
    					echo "<td class='px-1'  colspan=9 >TOTAL</td>";  
    					echo "<td class='right px-1'></td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($gkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					
    					?>
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
	
	
	
</div>