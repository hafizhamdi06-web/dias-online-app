<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
	$tampilNol =  $_POST['saldo'];

    $CI =& get_instance();

    $query  = "SELECT A.cid,A.cnocoa, A.cnama, 
    				CASE WHEN A.ctipe<11 THEN
					  (SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0)   
				         FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal <= '".tgl_database($date2)."' 
				        WHERE AA.cdnocoa=A.cid)
				    ELSE 
					  (SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0)   
				         FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."'  
				        WHERE AA.cdnocoa=A.cid)
				    END 'saldo'
				 FROM bcoa A WHERE A.cgd='D'";
	if(isset($_POST['coa']) && !isset($_POST['coasampai'])) {
	    $query .= "  AND A.cid='".$_POST['coa']."'";            		
	}
	if(isset($_POST['coa']) && isset($_POST['coasampai'])) {
	    $query .= "  AND A.cid BETWEEN '".$_POST['coa']."' AND '".$_POST['coasampai']."'";            		
	}	
    $query .= "  ORDER BY A.cnocoa";            

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);	

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
	<?
		foreach ($datareport->data as $row) {
			if($row->saldo == 0 && $tampilNol == 0){
				continue;
			}
			// Ambil Saldo Awal
			$query = "SELECT IFNULL(SUM(ROUND(AA.cddebit,2)),0)-IFNULL(SUM(ROUND(AA.cdkredit,2)),0) 'saldo'   
				         FROM ctransaksid AA INNER JOIN ctransaksiu AB ON AA.cdidu=AB.cuid AND AB.cutanggal < '".tgl_database($date1)."' 
				    LEFT JOIN bcoa AC ON AA.cdnocoa=AC.cid  
				        WHERE AA.cdnocoa='".$row->cid."' AND AC.ctipe<11";
			$saldoawal = $CI->M_transaksi->get_data_query($query);
			$saldoawal = json_decode($saldoawal);
			foreach ($saldoawal->data as $saldo) {
				$saldoawalval = $saldo->saldo;
			}
			//
			// Detil Transaksi GL
			$query = "SELECT DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal', A.cunotransaksi 'nomor',
							 CASE WHEN B.cdcatatan='' THEN A.cuuraian
							 	  WHEN B.cdcatatan IS NULL THEN A.cuuraian
							 	  ELSE B.cdcatatan
							 END 'catatan',			 
							 ROUND(B.cddebit,2) 'debit', ROUND(B.cdkredit,2) 'kredit'  
					    FROM ctransaksiu A INNER JOIN ctransaksid B ON A.cuid=B.cdidu 
					   WHERE A.cutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."' 
					     AND B.cdnocoa ='".$row->cid."' ORDER BY A.cutanggal ASC, A.cuid ASC";
			$detilreport = $CI->M_transaksi->get_data_query($query);
			$detilreport = json_decode($detilreport); 
			$saldoakhir = $saldoawalval;
	?>
				<table class="table">
					<thead>
						<tr class="border-0 bg-dark">
							<th colspan="6" class="left">
								<b><?= $row->cnocoa." - ".$row->cnama; ?></b>
							</th>
						</tr>
						<tr>
							<th class="left" width="11%">Tanggal</th>
							<th class="left" width="12%">Nomor</th>				
							<th class="left">Catatan/Uraian</th>
							<th class="right" width="18%">Debit</th>
							<th class="right" width="18%">Kredit</th>																
							<th class="right" width="18%">Saldo</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="3">Saldo Awal:</td>
							<td class="right"></td>
							<td class="right"></td>
							<td class="right"><?= eFormatNumber($saldoawalval,2); ?></td>				
						</tr>
						<?
							foreach ($detilreport->data as $detil) {
								$saldoakhir += $detil->debit - $detil->kredit;
								echo "<tr>
											<td>$detil->tanggal</td>
											<td>$detil->nomor</td>
											<td>$detil->catatan</td>
											<td class=\"right\">".eFormatNumber($detil->debit,2)."</td>
											<td class=\"right\">".eFormatNumber($detil->kredit,2)."</td>
											<td class=\"right\">".eFormatNumber($saldoakhir,2)."</td>																				
									  </tr>";
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">Saldo Akhir <?= $row->cnama; ?>:</td>
							<td></td>
							<td></td>
							<td class="right"><?= eFormatNumber($row->saldo,2); ?></td>				
						</tr>
					</tfoot>
				</table>
				<div class="clear">&nbsp;</div>
	<?
		}
	?>
</div>