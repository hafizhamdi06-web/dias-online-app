<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }
    if(isset($_POST['namapt'])){
    	$idpt = $_POST['namapt'];
    } else {
    	$idpt = "";
    }		
	$tampilNol =  $_POST['saldo'];		
    

    $CI =& get_instance();
     
    
    
    

?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>		
	<h3><?= $title; ?></h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
  
 
	<div class="clear">&nbsp;</div>	 
		<? 
		
		 

	
	  $query2 = " SELECT F_ADARESEP(suid) as adaresep, gkode,gid, sales.knama as namasales, kjnama,  sunotransaksi,sutanggal , npnamaclinic, sutotaltransaksi   
                                FROM  fstoku  inner JOIN bkontak sales ON sufarmasi=sales.KID   
                                inner join bgudang on gid=sucabang left join bnamapt on npid=gpt 
                                inner join bkontakjenis on kjid=sales.KJENISKARYAWAN
                                
                                WHERE    SUSTATUS<>9 and SUSUMBER = 'IP'     
                                AND (sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."')      "; 
                    	                	
                    	         if($idgudang != ""){
                                            	$query2 .= " AND sucabang='".$idgudang."' ";
                                            }   
						
	                   if($idpt != ""){
                        	$query2 .= " AND gpt='".$idpt."'";
                        }                          
                        
                        $query2 .= " order by gkode,gid,sales.knama, kjnama,sutanggal,sunotransaksi ";
             
            $datareport2 = $CI->M_transaksi->get_data_query($query2);
            $datareport2 = json_decode($datareport2); 
            ?>
	
		<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan="5">Komisi Apoteker</th>   
			</tr>
			<tr class="bg-dark">
				<th class="left px-1" width="10%">Nama Apoteker</th> 
				<th class="left px-1" width="10%">Jenis</th>   
				<th class="left px-1" width="10%">Cabang</th> 
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
				      $komisi=300 ; 
				      
				       
				      
    			    if ($tkomisi!=0  ) {
            			    if ($nama != $row->namasales and $nama != '' ) {
            			        echo "<tr>";  
            					echo "<td class='px-1 ' >".$nama."</td>";   
            					echo "<td class='px-1 ' >".$kjnama."</td>";  
            					echo "<td class='px-1 ' >".$cabang."</td>";   
                            					echo "<td class='right px-1'></td>"; 
                            					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
            					echo "</tr>";	
            					
            					$tnilaisales=0;
            					$tkomisi=0;
            			    }
    			    }
    			     
    			    
    			     
    			     
    			    
    			    
    			     
    			    $tnilaisales += $subtotal ;   
    			     
    			    $noip = $row->sunotransaksi ;  
    			    $nama = $row->namasales; 
    			    $kjnama = $row->kjnama;  
    			    $tanggal = $row->sutanggal;  
    			    $tkomisi += $komisi ;
    			    $gkomisi += $komisi ; 
    			    $cabang = $row->gkode;    
				}      			        
                    			         $tkomisi+=$komisi ;
                    			         $gkomisi+=$komisi ;   
                    					 
                    			    
				        if ($tkomisi!=0  ) {
				            
    			        echo "<tr>";  
            					echo "<td class='px-1 ' >".$nama."</td>";   
            					echo "<td class='px-1 ' >".$kjnama."</td>";  
            					echo "<td class='px-1 ' >".$cabang."</td>";   
    					echo "<td class='right px-1'></td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
				        }
    					 
    					
    					$tnilaisales=0;
    					$tkomisi=0;
				
			?>
		</tbody>
		<tfoot>
		 	 
		<?
		
		echo "<tr>";  
    					echo "<td class='px-1'  colspan=3 >TOTAL</td>";  
    					echo "<td class='right px-1'></td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($gkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					
    					?>
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
	
		<? 
		 

	
	  $query2 = " SELECT F_ADARESEP(suid) as adaresep, gkode,gid, sales.knama as namasales, kjnama,  sunotransaksi,sutanggal , npnamaclinic, sutotaltransaksi   
                                FROM  fstoku  inner JOIN bkontak sales ON SUFARMASIASISTEN=sales.KID   
                                inner join bgudang on gid=sucabang left join bnamapt on npid=gpt 
                                inner join bkontakjenis on kjid=sales.KJENISKARYAWAN
                                
                                WHERE    SUSTATUS<>9 and SUSUMBER = 'IP'     
                                AND (sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."')      "; 
                    	                	
                    	         if($idgudang != ""){
                                            	$query2 .= " AND sucabang='".$idgudang."' ";
                                            }   		
						
	                   if($idpt != ""){
                        	$query2 .= " AND gpt='".$idpt."'";
                        }                                        
                        
                        $query2 .= " order by gkode,gid,sales.knama, kjnama,sutanggal,sunotransaksi ";
             
            $datareport2 = $CI->M_transaksi->get_data_query($query2);
            $datareport2 = json_decode($datareport2); 
            ?>
	
		<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan="5">Komisi Asisten Apoteker</th>   
			</tr>
			<tr class="bg-dark">
				<th class="left px-1" width="10%">Nama Apoteker</th> 
				<th class="left px-1" width="10%">Jenis</th>   
				<th class="left px-1" width="10%">Cabang</th> 
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
				      $komisi=300 ; 
				      
				       
				      
    			    if ($tkomisi!=0  ) {
            			    if ($nama != $row->namasales and $nama != '' ) {
            			        echo "<tr>";  
            					echo "<td class='px-1 ' >".$nama."</td>";   
            					echo "<td class='px-1 ' >".$kjnama."</td>";  
            					echo "<td class='px-1 ' >".$cabang."</td>";   
                            					echo "<td class='right px-1'></td>"; 
                            					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
            					echo "</tr>";	
            					
            					$tnilaisales=0;
            					$tkomisi=0;
            			    }
    			    }
    			     
    			    
    			     
    			     
    			    
    			    
    			     
    			    $tnilaisales += $subtotal ;   
    			     
    			    $noip = $row->sunotransaksi ;  
    			    $nama = $row->namasales; 
    			    $kjnama = $row->kjnama;  
    			    $tanggal = $row->sutanggal;  
    			    $tkomisi += $komisi ;
    			    $gkomisi += $komisi ; 
    			    $cabang = $row->gkode;    
				}      			        
                    			         $tkomisi+=$komisi ;
                    			         $gkomisi+=$komisi ;   
                    					 
                    			    
				        if ($tkomisi!=0  ) {
				            
    			        echo "<tr>";  
            					echo "<td class='px-1 ' >".$nama."</td>";   
            					echo "<td class='px-1 ' >".$kjnama."</td>";  
            					echo "<td class='px-1 ' >".$cabang."</td>";   
    					echo "<td class='right px-1'></td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($tkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
				        }
    					 
    					
    					$tnilaisales=0;
    					$tkomisi=0;
				
			?>
		</tbody>
		<tfoot>
		 	 
		<?
		
		echo "<tr>";  
    					echo "<td class='px-1'  colspan=3 >TOTAL</td>";  
    					echo "<td class='right px-1'></td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($gkomisi,2)."</td>"; 
    					echo "</tr>";	
    					
    					
    					?>
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
	
</div>