<?php
	include ('style.php');
	$date = $_POST['tgl']; 
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }	
	$tampilNol =  $_POST['saldo'];		 
    $CI =& get_instance();  
	                   
 


	                  $query = "SELECT  npnama, gkode, ctnamalama, ctnamabaru, SUM(qty) 'qty', sum(cogs) 'cogs' from
                      (
                      select
                      npnama, gkode as  gkode, coalama.ctnama 'ctnamalama', coabaru.ctnama 'ctnamabaru', ikode, 
                      IFNULL(SUM(SDMASUK-( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) ) ),0) as qty,
                      case   when IHARGABELITERAKHIR=0 then (icogs_lama)+(icogs_lama*5/100) else IHARGABELITERAKHIR end as icogs ,
                      
                      IFNULL(SUM(SDMASUK-( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) ) ),0) *
                      (case   when IHARGABELITERAKHIR=0 then (icogs_lama)+(icogs_lama*5/100) else IHARGABELITERAKHIR end) as cogs 

	                  from fstokd left join bitem on IID = sditem   
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  WHERE sustatus<>9 and  SDCANCEL=0    
	                  AND sutanggal <= '".tgl_database($date)."'    
                      ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        }   
                        $query .= " GROUP by npnama,  coalama.ctnama, coabaru.ctnama, ikode ,gkode
                        having  IFNULL(SUM(SDMASUK-( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) ) ),0)>0 ) a
                        group by npnama, gkode, ctnamalama, ctnamabaru
                        "; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    
    
     
    

?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>		
	<h3><?= $title; ?></h3>
	<span>Periode : <?= $date; ?>  </span>
</div>
<div class="content-report">
	<table class="table">
		<thead> 
			<tr class="bg-dark">
				<th class="left px-1">Coa Lama</th> 
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Qty</th> 
				<th class="left px-1">Total COGS</th> 	 
			</tr>
		</thead>
		<tbody>
			<?	
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ; 
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;  

				$jumlahdata=0;

				$nilaiprodukperpt=0; $nilaisalesperpt=0;
				
				$tanggal = ''; $gudang = ''; $namapt='';
				
				foreach ($datareport->data as $row) {
				    
				    $subtotal=$row->qty; 
                    $nilaiproduk=$row->cogs; 
    			    
    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>"; 
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";  
    					echo "<td class='right px-3'>".eFormatNumber($nilaisalesperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiprodukperpt,2)."</td>"; 
    					echo "</tr>";	 
						
						$nilaiprodukperpt=0; $nilaisalesperpt=0; 
    			   } 

				    if ($namapt !=  $row->npnama  ) { 

    			       	echo "<tr >"; 
    					echo "<td class='px-1' >".$row->npnama."</td>";   
    					echo "</tr>";	
    			   } 
    			    
    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >"; 
    					echo "<td class='px-2' >".$row->gkode."</td>";   
    					echo "</tr>";	
    			   } 
    			    
    			    $gudang = $row->gkode ; 
    			    $namapt = $row->npnama ;  
    				    
    					echo "<tr>"; 
    					echo "<td class='px-3'>".$row->ctnamalama."</td>";   
    					echo "<td class='px-3'>".$row->ctnamabaru."</td>";   
    					echo "<td class='right px-3'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>"; 
    					echo "</tr>";	
    					 
    					$tnilaiproduk += $row->cogs ; 	
    			    	$tnilaisales += $row->qty ; 

    			        $nilaiprodukperpt += $row->cogs;  
    					$nilaisalesperpt += $row->qty ;

    					$jumlahdata ++;
    					 
				
    					
				   
				}
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1" colspan=2>Total</td>
				<td class="right px-3"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-3"><?= eFormatNumber($tnilaiproduk,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

 
	
</div>
