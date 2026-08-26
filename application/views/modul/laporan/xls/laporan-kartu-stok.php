<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
	$item =  @$_POST['item'];
	$gudang =  @$_POST['gudang'];	
	$tampilNol =  $_POST['saldo'];	

    $CI =& get_instance();

	if(empty($gudang)) {
		$namagudang = 'Semua';
		$querygudang = "";
		$querygudang2 = "";
	} else {
		$namagudang = json_decode($CI->gettabelvalue('bgudang','gnama','gid',$gudang));
		foreach ($namagudang->data as $row) {
			$namagudang = $row->gnama;
		}
		$querygudang = " AND AA.sdgudang='".$gudang."'";
		$querygudang2 = " AND B.sdgudang='".$gudang."'";		
	}

    $query  = "SELECT A.iid,A.ikode, A.inama,A.isatuan, 
					  (SELECT IFNULL(SUM(AA.sdmasuk),0)-IFNULL(SUM(AA.sdkeluar),0)  
				         FROM fstokd AA INNER JOIN fstoku AB ON AA.sdidsu=AB.suid AND AB.sutanggal <= '".tgl_database($date2)."' 
				        WHERE AA.sditem=A.iid ".$querygudang.") 'saldo'
				 FROM bitem A WHERE A.ijenisitem=0";

	if(!empty($item)) {
	    $query .= "  AND A.iid='".$item."'";            		
	}

    $query .= "  ORDER BY A.ikode ASC";            
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
			$query = "SELECT IFNULL(SUM(AA.sdmasuk),0)-IFNULL(SUM(AA.sdkeluar),0) 'saldo'   
				         FROM fstokd AA INNER JOIN fstoku AB ON AA.sdidsu=AB.suid AND AB.sutanggal < '".tgl_database($date1)."' 
				        WHERE AA.sditem='".$row->iid."' ".$querygudang;
			$saldoawal = $CI->M_transaksi->get_data_query($query);
			$saldoawal = json_decode($saldoawal);
			foreach ($saldoawal->data as $saldo) {
				$saldoawalval = $saldo->saldo;
			}
			//
			// Detil Transaksi GL
			$query = "SELECT DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal', A.sunotransaksi 'nomor', C.nketerangan 'uraian', 
							 B.sdmasuk 'masuk', B.sdkeluar 'keluar'  
					    FROM fstoku A INNER JOIN fstokd B ON A.suid=B.sdidsu LEFT JOIN anomor C ON A.susumber=C.nkode  
					   WHERE A.sutanggal BETWEEN '".tgl_database($date1)."' AND '".tgl_database($date2)."' 
					     AND B.sditem ='".$row->iid."' ".$querygudang2." ORDER BY A.sutanggal ASC, A.suid ASC";
			$detilreport = $CI->M_transaksi->get_data_query($query);
			$detilreport = json_decode($detilreport); 
			$saldoakhir = $saldoawalval;
	?>
				<table class="table">
					<thead>
						<tr class="border-0 bg-dark">
							<th colspan="3" class="left">
								<?= $row->ikode; ?> : <?= $row->inama; ?>
							</th>
							<th colspan="3" class="left">
								GUDANG : <?= $namagudang; ?>
							</th>							
						</tr>
						<tr>
							<th class="left" width="11%">Tanggal</th>
							<th class="left" width="12%">Nomor</th>				
							<th class="left">Jenis/Sumber</th>
							<th class="right" width="18%">Masuk</th>
							<th class="right" width="18%">Keluar</th>																
							<th class="right" width="18%">Jumlah</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="3">Stok Awal:</td>
							<td class="right"></td>
							<td class="right"></td>
							<td class="right"><?= eFormatNumber($saldoawalval,$digitqty); ?></td>				
						</tr>
						<?
							foreach ($detilreport->data as $detil) {
								$saldoakhir += $detil->masuk - $detil->keluar;
								echo "<tr>
											<td>$detil->tanggal</td>
											<td>$detil->nomor</td>
											<td>$detil->uraian</td>
											<td class=\"right\">".eFormatNumber($detil->masuk,$digitqty)."</td>
											<td class=\"right\">".eFormatNumber($detil->keluar,$digitqty)."</td>
											<td class=\"right\">".eFormatNumber($saldoakhir,$digitqty)."</td>						
									  </tr>";
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">Stok Akhir :</td>
							<td></td>
							<td></td>
							<td class="right"><?= eFormatNumber($row->saldo,$digitqty); ?></td>				
						</tr>
					</tfoot>
				</table>
				<div class="clear">&nbsp;</div>
	<?
		}
	?>
</div>