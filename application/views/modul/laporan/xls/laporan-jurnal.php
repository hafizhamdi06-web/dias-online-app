<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	

    $CI =& get_instance();

    $query  = "SELECT A.cuid 'id', A.cusumber 'sumber', A.cunotransaksi 'nomor', 
    				  DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal', A.cuuraian 'uraian',
					  B.cdnocoa 'idcoa', C.cnocoa 'nocoa', C.cnama 'coa', 
					  ROUND(B.cddebit,2) 'debit', ROUND(B.cdkredit,2) 'kredit', B.cdcatatan 'catatan'
				 FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu LEFT JOIN bcoa C ON B.cdnocoa=C.cid 
				WHERE A.cutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."' ";

	if(isset($_POST['sumber'])) {
	    $transcode = $CI->M_transaksi->prefixtrans($_POST['sumber']);        		
		//echo $transcode;
	    $query .= " AND A.cusumber='".$transcode."' ";            		
	}

    $query .= "  ORDER BY A.cutanggal ASC, A.cuid ASC, A.cunotransaksi ASC, B.cdurutan ASC";            

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);	
    $idtrans="";

?>
<div class="header-report">
	<table class="table border-0">
		<thead>
			<tr>
				<th colspan="6" class="center"><h3 class="text-blue"><?= $company_name; ?></h3></th>				
			</tr>
			<tr>
				<th colspan="6" class="center"><p><?= $title; ?></p></th>				
			</tr>			
			<tr>
				<th colspan="6" class="center"><span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span></th>				
			</tr>						
		</thead>
	</table>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left" width="11%">Tanggal</th>
				<th class="left" width="16%">Nomor/Akun</th>	
				<th class="left" width="11%">Sumber</th>										
				<th class="left">Uraian/Catatan</th>
				<th class="right px-1" width="18%">Debit</th>
				<th class="right px-1" width="18%">Kredit</th>																
			</tr>
		</thead>
		<tbody>
			<?
				$r = 0;
				$totaldb = 0;
				$totalcr = 0;
				foreach ($datareport->data as $row) {
					if($r != 0 && $idtrans != $row->id) {
						echo "<tr>
									<td colspan='4' class='py-1'></td>
									<td class=\"right px-1 py-1\" style=\"border-top: .5px solid #000;\">".eFormatNumber($totaldb,2)."</td>
									<td class=\"right px-1 py-1\" style=\"border-top: .5px solid #000;\">".eFormatNumber($totalcr,2)."</td>
							 </tr>";	
						$totaldb = 0;
						$totalcr = 0;											
					}
					if($row->id != $idtrans) {
						echo "<tr>
									<td>$row->tanggal</td>
									<td>$row->nomor</td>
									<td>$row->sumber</td>
									<td colspan='3'>$row->uraian</td>
							 </tr>";						
						echo "<tr>
									<td>".$row->nocoa."&nbsp;</td>
									<td colspan='2'>$row->coa</td>
									<td>$row->catatan</td>									
									<td class=\"right\">".eFormatNumber($row->debit,2)."</td>
									<td class=\"right\">".eFormatNumber($row->kredit,2)."</td>									
							 </tr>";												
					} else {
						echo "<tr>
									<td>".$row->nocoa."&nbsp;</td>
									<td colspan='2'>$row->coa</td>
									<td>$row->catatan</td>									
									<td class=\"right\">".eFormatNumber($row->debit,2)."</td>
									<td class=\"right\">".eFormatNumber($row->kredit,2)."</td>									
							 </tr>";												
					}
					$idtrans = $row->id;
					$r++;
					$totaldb += $row->debit;
					$totalcr += $row->kredit;
				}
				echo "<tr>
							<td colspan='4'></td>
							<td class=\"right\" style=\"border-top: .5px solid #000;\">".eFormatNumber($totaldb,2)."</td>
							<td class=\"right\" style=\"border-top: .5px solid #000;\">".eFormatNumber($totalcr,2)."</td>
					 </tr>";

			?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>