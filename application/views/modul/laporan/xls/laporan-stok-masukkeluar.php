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
 
	 
     
           
	                   $query = "SELECT npnama,gkode,susumber, 
					   sum(qtymasuk) 'qtymasuk', sum(nilaimasuk) 'nilaimasuk', sum(qtykeluar)'qtykeluar', sum(nilaikeluar) 'nilaikeluar' from
					   (SELECT d.npnama,  c.gkode, ikode,
					    a.susumber ,sum(sdmasuk) 'qtymasuk',sum(sdmasuk*(icogs+(icogs*5/100))) as nilaimasuk,
                       sum(( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar) )) as 'qtykeluar',
                       sum(
					   		( IF(SDDARIPAKET<> 0 AND  SDKEDATANGAN  = 0,0,sdkeluar)) * (icogs+(icogs*5/100))
							) as 'nilaikeluar' 
	                  
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
	                  WHERE a.sustatus<>9 and sdcancel=0 
	                  AND a.sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'  
                      ";         
	                  
	                   if($idgudang != ""){
                        	$query .= " AND a.sucabang='".$idgudang."'";
                        } 
                        $query .= " GROUP by d.npnama,c.gkode,  a.susumber , ikode ) a 
						group by npnama,gkode,susumber
						"; 
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);
    

?>
 
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>		
	<h3><?= $title; ?></h3>
	<span>Periode : <?= $date1; ?> sd <?= $date2; ?> </span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" colspan=4>COGS Produk Terjual dan Terpakai Qty Real</th>  
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Coa Baru</th> 
				<th class="left px-1">Sumber</th> 
				<th class="left px-1">Nilai Masuk</th> 
				<th class="left px-1">Nilai Keluar</th> 	 
			</tr>
		</thead>
		<tbody>
			<?	
				$jumlahdatang = 0;  
				$qtymasuk = 0; $qtykeluar = 0; 

				$jumlahdata=0;

				$tnilaimasuk=0; $tnilaikeluar=0;
				$tnilaimasukperpt=0; $tnilaikeluarperpt=0;
				
				$tanggal = ''; $gudang = ''; $namapt='';
				
				foreach ($datareport->data as $row) {
				    
				    $nilaimasuk=$row->nilaimasuk; 
				    $nilaikeluar=$row->nilaikeluar; 
    			    
    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>"; 
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";  
    					echo "<td class='right px-3'>".eFormatNumber($tnilaimasukperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($tnilaikeluarperpt,2)."</td>"; 
    					echo "</tr>";	 
						
						$tnilaimasukperpt=0; $tnilaikeluarperpt=0;
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
    					echo "<td class='right px-3'>".eFormatNumber($row->nilaimasuk,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->nilaikeluar,2)."</td>"; 
    					echo "</tr>";	
    					 
						$tnilaimasuk += $row->nilaimasuk ; 
						$tnilaikeluar += $row->nilaikeluar ; 

						$tnilaimasukperpt += $row->nilaimasuk ; 
						$tnilaikeluarperpt += $row->nilaikeluar ; 	 

    					$jumlahdata ++; 
				   
				}

                echo "<tr class='bg-dark'>"; 
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";  
    					echo "<td class='right px-3'>".eFormatNumber($tnilaimasukperpt,2)."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($tnilaikeluarperpt,2)."</td>"; 
    					echo "</tr>";	 
				
				
			?>
		</tbody>
		<tfoot>
		 	
			<tr>
				<td class="px-1" colspan=2>Total</td>
				<td class="right px-3"><?= eFormatNumber($tnilaimasuk,2); ?></td>  
				<td class="right px-3"><?= eFormatNumber($tnilaikeluar,2); ?></td> 
			</tr> 	
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

 
	
</div>