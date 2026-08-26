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


     
	                  
	                   $query = "SELECT npnama,  gkode, 
					    ik2kode ,
	                   sum((case when sutanggal < '2026/04/11' then icogs_lama else icogs end)*sdkeluar) as cogs ,
                       sum(sdkeluar) as qty
	                  
	                  
	                  from fstokd left join bitem on IID = sditem   
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  WHERE sustatus<>9 and ( 
                      (susumber = 'IP' and  (sdkeluar*(sdharga-sddiskon)) > 0)  
                      ) and sdkeluar>0
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."' 
                     
                      ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by npnama,gkode,  ik2kode   "; 
	  

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
				<th class="left px-1" colspan='4'>COGS Clinic Harga > 0</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Kelompok</th> 
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
    					echo "<td class='px-3'>-</td>";   
    					echo "<td class='px-3'>".$row->ik2kode."</td>";   
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

<?php
	 
	                  
	                   $query = "SELECT npnama,  gkode, 
					    ik2kode ,
	                   sum((case when sutanggal < '2026/04/11' then icogs_lama else icogs end)*sdkeluar) as cogs ,
                       sum(sdkeluar) as qty
	                  
	                  
	                  from fstokd left join bitem on IID = sditem   
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  WHERE sustatus<>9 and ( 
                      (susumber = 'IP' and  (sdkeluar*(sdharga-sddiskon)) = 0)  
                      ) and sdkeluar>0
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."' 
                     
                      ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by npnama,gkode,  ik2kode   "; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    
    
     
    

?> 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan='4'>COGS Clinic Harga=0</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Kelompok</th> 
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
    					echo "<td class='px-3'>-</td>";   
    					echo "<td class='px-3'>".$row->ik2kode."</td>";   
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

<?php
	 
	                  
	                   $query = "SELECT npnama,  gkode, 
					    ik2kode ,
	                   sum(icogs*detail.sdkeluar) as cogs ,
                       sum(detail.sdkeluar) as qty
	                  
	                  
	                  from fstokd detail left join bitem on IID = detail.sditem   
                      left join fstoku utama on utama.suid=sdidsu
                      left join fstoku tindakan on tindakan.suid=utama.SUIDUALKES
                      left join fstokd tindakandetail on detail.SDIDUALKES=tindakandetail.sdid
	                  left join bgudang on gid=utama.sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  WHERE tindakan.sustatus<>9 and ( 
                      (utama.susumber = 'AL' and  (tindakandetail.sdkeluar*(tindakandetail.sdharga-tindakandetail.sddiskon)) <> 0)  
                      )  
	                  AND utama.sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."' 
                     
                      ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND utama.sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by npnama,gkode,  ik2kode   "; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    
    
     
    

?>
 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan='4'>COGS Alkes Tindakan Clinic Harga Tindakan > 0</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Kelompok</th> 
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
    					echo "<td class='px-3'>-</td>";   
    					echo "<td class='px-3'>".$row->ik2kode."</td>";   
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




<?php
	 
	                  
	                   $query = "SELECT npnama,  gkode, 
					    ik2kode ,
	                   sum(icogs*detail.sdkeluar) as cogs ,
                       sum(detail.sdkeluar) as qty
	                  
	                  
	                  from fstokd detail left join bitem on IID = detail.sditem   
                      left join fstoku utama on utama.suid=sdidsu
                      left join fstoku tindakan on tindakan.suid=utama.SUIDUALKES
                      left join fstokd tindakandetail on detail.SDIDUALKES=tindakandetail.sdid
	                  left join bgudang on gid=utama.sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  WHERE tindakan.sustatus<>9 and ( 
                      (utama.susumber = 'AL' and  (tindakandetail.sdkeluar*(tindakandetail.sdharga-tindakandetail.sddiskon)) =0)  
                      )  
	                  AND utama.sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."' 
                     
                      ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND utama.sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by npnama,gkode,  ik2kode   "; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    
    
     
    

?>
 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan='4'>COGS Alkes Tindakan Clinic Harga Tindakan = 0</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Kelompok</th> 
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
    					echo "<td class='px-3'>-</td>";   
    					echo "<td class='px-3'>".$row->ik2kode."</td>";   
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



<?php
	 
     
           
	                   $query = "SELECT npnama,  gkode, 
					    a.susumber ,
	                   sum((case when a.sutanggal < '2026/04/11' and a.susumber='IP' then icogs_lama else icogs end)*d.sdkeluar) as cogs ,
                       sum(d.sdkeluar) as qty
	                  
	                  
	                  from fstokd d left join bitem on IID = d.sditem   
                      left join fstoku a on a.suid=d.sdidsu   
                      left join fstoku b on b.suid=a.SUIDUALKES
                      left join fstokd c on d.SDIDUALKES=c.sdid
	                  left join bgudang on gid=a.sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  WHERE a.sustatus<>9 and ( 
                      (a.susumber = 'IP' and  (d.sdkeluar*(d.sdharga-d.sddiskon)) > 0)  
                      or (a.susumber = 'AL' and b.sustatus<>9 and (c.sdkeluar*(c.sdharga-c.sddiskon)) > 0) 
                      or (a.susumber <> 'IP' and a.susumber <> 'AL' and d.sdkeluar>0 )
                      )  
	                  AND a.sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."' 
                     
                      ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND a.sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by npnama,gkode,  a.susumber   "; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    

?>
 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan=4>COGS Produk Terjual dan Terpakai Harga Jual > 0</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Sumber</th> 
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
    					echo "<td class='px-3'>-</td>";   
    					echo "<td class='px-3'>".$row->susumber."</td>";   
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

<?php
	 
     
           
	                   $query = "SELECT npnama, c.gkode, 
					    a.susumber ,
	                   sum((case when a.sutanggal < '2026/04/11' and a.susumber='IP' then icogs_lama else icogs end)*
                            ( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) )
                            ) as cogs ,
                       sum(( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) )) as qty
	                  
	                  
	                  from fstokd left join bitem on IID = sditem   
                      left join fstoku a on a.suid=sdidsu   
                      left join fstoku b on b.suid=a.SUIDUALKES
	                  left join bgudang c on c.gid=a.sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=c.gpt
	                  left join bgudang e on e.gid=a.sugudangtujuan   
	                  WHERE a.sustatus<>9 and ( 
                     
                        (a.susumber = 'AL' and b.sustatus<>9 )
                      or ( a.susumber ='KMB' and e.gpt= )
                      or ( a.susumber <> 'AL' and a.susumber <>'KMB'  and sdkeluar>0 )
                      )  
	                  AND a.sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."' 
                     
                      ";    
	                  
	                   if($idgudang != ""){
                        	$query .= " AND a.sucabang='".$idgudang."'";
                        }  
    
    
                        $query .= " GROUP by npnama,c.gkode,  a.susumber   "; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    

?>
 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan=4>COGS Produk Terjual dan Terpakai Qty Real</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Sumber</th> 
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
    					echo "<td class='px-3'>-</td>";   
    					echo "<td class='px-3'>".$row->susumber."</td>";   
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




<?php
	 
     
           
	                   $query = "SELECT d.npnama,  c.gkode, 
					    a.susumber ,
	                   sum((case when a.sutanggal < '2026/04/11' and a.susumber='IP' then icogs_lama else icogs end)*
                            ( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) )
                            ) as cogs ,
                       sum(( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) )) as qty
	                  
	                  
	                  from fstokd left join bitem on IID = sditem   
                      left join fstoku a on a.suid=sdidsu   
                      left join fstoku b on b.suid=a.SUIDUALKES
	                  left join bgudang c on c.gid=a.sucabang  
	                  left join bitem2 on i2iditem=iid  
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan  
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt d on d.npid=c.gpt
	                  left join bgudang e on e.gid=a.sugudangtujuan   
	                  WHERE a.sustatus<>9 and (  
                        (a.susumber = 'AL' and b.sustatus<>9 )
                      or ( a.susumber ='KMB' and c.gpt<>e.gpt )
                      or ( a.susumber <> 'AL' and a.susumber <>'KMB'  and sdkeluar>0 )

                      )  
	                  AND a.sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."' 
                     
                      ";    
	                     
	                   
    
    
                        $query .= " GROUP by d.npnama,c.gkode,  a.susumber   "; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    

?>
 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan=4>COGS Produk Terjual dan Terpakai Qty Real</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Sumber</th> 
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
    					echo "<td class='px-3'>-</td>";   
    					echo "<td class='px-3'>".$row->susumber."</td>";   
    					echo "<td class='right px-3'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>"; 
    					echo "</tr>";	
    					 
    					$tnilaiproduk += $row->cogs ; 	
    			    	$tnilaisales += $row->qty ; 

    			        $nilaiprodukperpt += $row->cogs;  
    					$nilaisalesperpt += $row->qty ;

    					$jumlahdata ++;
    					 
				
    					
				   
				}

                echo "<tr class='bg-dark'>"; 
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";  
    					echo "<td class='right px-3'>".eFormatNumber($nilaisalesperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiprodukperpt,2)."</td>"; 
    					echo "</tr>";	 
				
				
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