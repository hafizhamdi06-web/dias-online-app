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


     
	                  
	                   $query = "SELECT 
					   coabaru.CTNAMA  as ctnamabaru,
					   coalama.CTNAMA, coalama.CTTIDAKDITARIK, coalama.CTTIPEPRODUK, gkode,
	                   sum((IPDHARGA-IPDDISKON)*IPDKELUAR) as subtotal ,
                       sum(icogs*ipdkeluar) as cogs
	                  
	                  
	                  from einvoicepenjualand left join bitem on IID = IPDITEM   
                      left join einvoicepenjualanu on ipuid=ipdidipu
	                  left join bgudang on gid=ipugudang 
	                  left join bcoatipe_perpt coalama on coalama.ctid=icoa2021 
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
	                  WHERE (ipusumber = 'IV' or ipusumber = 'IVM')
	                  AND iputanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND ipugudang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by 
                    	  gkode, coalama.CTID,coalama.CTNAMA,coalama.CTTIDAKDITARIK,coalama.CTTIPEPRODUK,coabaru.CTNAMA   
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
				<th class="left px-1">Coa Lama</th> 
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
				$tglnilaiproduk = 0 ; $tglsubtotal = 0 ; $jumlahdata=0;
				
				$tanggal = ''; $gudang = '';
				
				foreach ($datareport->data as $row) {
				    
				    $subtotal=$row->subtotal; 
                    $nilaiproduk=$row->cogs; 
    			    $tnilaisales += $subtotal ; 
    			    
    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >"; 
    					echo "<td class='px-1' >".$row->gkode."</td>";   
    					echo "</tr>";	
    			   } 
    			    
    			    $gudang = $row->gkode ;  
    				    
    					echo "<tr>"; 
    					echo "<td class='px-1'>".$row->CTNAMA."</td>";  
    					echo "<td class='px-1'>".$row->ctnamabaru."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiproduk,2)."</td>"; 
    					echo "</tr>";	
    					 
    					$tnilaiproduk += $nilaiproduk; 	
    			        $tglnilaiproduk += $nilaiproduk; 
    					$tglsubtotal += $subtotal;

    					$jumlahdata ++;
    					
    					
				        $nilaiproduk = 0 ; $subtotal = 0 ; 
				
    					
				   
				}
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1" colspan='2'>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisales,2); ?></td>  
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

 
	
</div>