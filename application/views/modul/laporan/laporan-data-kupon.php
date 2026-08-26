<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }		
    if(isset($_POST['item'])){
    	$iditem = $_POST['item'];
    } else {
    	$iditem = "";
    }	
    

    $CI =& get_instance();
    
	                  
                    	$query = "   select  VKID,VKNOMOR,KNAMA,GKODE,SUNOTRANSAKSI,SUTANGGAL
                    from bvoucherkupon left join fstokkuponv on SVKIDVOUCHER=vkid left join fstoku on suid = SVKIDU  
                   
                    LEFT JOIN bkontak ON KID=sukontak   left join bgudang on gid=vkcabang   
                  
	                 
	                  ";  
	                  
	                   if($idgudang != ""){
                        	$query .= "   WHERE vkcabang='".$idgudang."'";
                        } 
	                   
    
    
    $query .= " order by 
	  VKNOMOR  ";

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
				<th class="left px-1">Cabang</th>
				<th class="left px-1">No Voucher</th>
				<th class="left px-1">No IP</th>
				<th class="left px-1">Tanggal</th>
				<th class="left px-1">Nama Pasien</th> 			
			</tr>
		</thead>
		<tbody>
			<?	
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->GKODE."</td>"; 
					echo "<td>".$row->VKNOMOR."</td>";
					echo "<td>".$row->SUNOTRANSAKSI."</td>";
					echo "<td>".$row->SUTANGGAL."</td>";
					echo "<td class='left px-1'>".$row->KNAMA."</td>"; 
					echo "</tr>";	
					 
				 	
					$qty ++ ;		
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td  class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($qty,2); ?></td> 
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>