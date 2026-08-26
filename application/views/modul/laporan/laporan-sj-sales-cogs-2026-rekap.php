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


     
	                  
	                   $query = "SELECT npnama,
					   ctnama, gkode, ipunotransaksi,
	                   sum((IPDHARGA-IPDDISKON)*IPDKELUAR) as subtotal ,
                       sum(icogs*ipdkeluar) as cogs
	                  
	                  
	                  from einvoicepenjualand left join bitem on IID = IPDITEM   
                      left join einvoicepenjualanu on ipuid=ipdidipu
	                  left join bgudang on gid=ipugudang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan on ctid=i2coapendapatan  
					  left join bnamapt on npid=gpt
	                  WHERE (ipusumber = 'IV' or ipusumber = 'IVM')
	                  AND iputanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND ipugudang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by npnama,gkode, ctnama , ipunotransaksi	  "; 
	  

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
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Penjualan</th> 
				<th class="left px-1">COGS</th> 	 
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
				    
				    $subtotal=$row->subtotal; 
                    $nilaiproduk=$row->cogs; 
    			    
    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>"; 
    					echo "<td class='px-1'>Total ".$namapt."</td>";  
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
    					echo "<td class='px-3'>".$row->ctnama."</td>";  
    					echo "<td class='px-3'>".$row->ipunotransaksi."</td>";  
    					echo "<td class='right px-3'>".eFormatNumber($row->subtotal,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>"; 
    					echo "</tr>";	
    					 
    					$tnilaiproduk += $row->cogs ; 	
    			    	$tnilaisales += $row->subtotal ; 

    			        $nilaiprodukperpt += $row->cogs;  
    					$nilaisalesperpt += $row->subtotal ;

    					$jumlahdata ++;
    					 
				
    					
				   
				}
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1">Total</td>
				<td class="right px-3"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-3"><?= eFormatNumber($tnilaiproduk,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

 
	
</div>