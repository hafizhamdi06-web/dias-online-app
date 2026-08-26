<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];
	$tampilNol =  $_POST['saldo'];

    $CI =& get_instance();

    $query  = "SELECT * FROM bcoagrup WHERE cgid>10";
    $query .= "  ORDER BY cgid ASC";            

    $grup = $CI->M_transaksi->get_data_query($query);
    $grup = json_decode($grup);
?>
<div class="header-report">
	<table class="table border-0">
		<thead>
			<tr>
				<th colspan="3" class="center"><h3 class="text-blue"><?= $company_name; ?></h3></th>				
			</tr>
			<tr>
				<th colspan="3" class="center"><p><?= $title; ?></p></th>				
			</tr>			
			<tr>
				<th colspan="3" class="center"><span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span></th>				
			</tr>						
		</thead>
	</table>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th colspan="2" class="center">Keterangan</th>				
				<th class="right">Saldo</th>
			</tr>
		</thead>
		<tbody>
			<?
				$grandtotal = 0;
				foreach ($grup->data as $row_grup) {
					if($row_grup->CGID == 13){
						echo "<tr>
									<td colspan='2'><b>Laba / Rugi Kotor</b></td>
									<td class='right' style=\"border-top:.5px solid black\"><b>".eFormatNumber(($grandtotal*-1),2)."</b></td>
							 </tr>";					    				    												
					}
					echo "<tr>
								<td colspan='3' class='text-blue'>$row_grup->CGNAMA</td>
						 </tr>";	
					$query = "SELECT A.cid, A.cnocoa, A.cnama, A.cgd, 
									  (SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0)  
										 FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."' 
										WHERE AA.cdnocoa=A.cid) 'saldo' 
								  FROM bcoa A WHERE A.ctipe='".$row_grup->CGID."' AND A.cgd='D' ORDER BY A.cnocoa ASC";
				    $coa = $CI->M_transaksi->get_data_query($query);
				    $coa = json_decode($coa);		
				    $total = 0;
				    foreach ($coa->data as $row) {
				    	if($row->cgd == 'G'){
							echo "<tr>
										<td colspan='2'>$row->cnama</td>
										<td></td>									
								 </tr>";					    
				    	} else {
							if(abs($row->saldo) == 0 && $tampilNol == 0){
							} else {
								echo "<tr>
											<td colspan='2'>$row->cnocoa &nbsp;&nbsp; $row->cnama</td>
											<td class='right'>".eFormatNumber(abs($row->saldo),2)."</td>									
									 </tr>";					    				    		
							}
				    	}
						$total += $row->saldo;
						$grandtotal += $row->saldo;
					}
					echo "<tr>
								<td colspan='2' class='text-blue'>Total $row_grup->CGNAMA</td>
								<td class='right' style=\"border-top:.5px solid black\">".eFormatNumber(abs($total),2)."</td>
						 </tr>";																			
				}
				echo "<tr>
							<td colspan='2'><b>Laba / Rugi Bersih Sebelum Pajak</b></td>
							<td class='right' style=\"border-top:.5px solid black\"><b>".eFormatNumber(($grandtotal*-1),2)."</b></td>
					 </tr>";					    				    						
			?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</div>