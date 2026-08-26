<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['idkontak'])){
    	$kontak = $_POST['idkontak'];
    } else {
    	$kontak = "";
    }	

    $CI =& get_instance();
    $transcode = element('PB_Pembayaran_Hutang',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.piuuraian 'uraian', IFNULL(A.piujmlkas,0) 'totalkas',
                          IFNULL(A.piutotalretur,0) 'totalretur', IFNULL(A.piutotalpiutang,0) 'totalpiutang',
                          IFNULL(A.piudiskon,0) 'totaldiskon', IFNULL(A.piunilaibuktipotong,0) 'totalpajak',
                          IFNULL(A.piuselisihbayar,0) 'totalselisih', A.piubuktipph 'nobupot'
                     FROM epembayaraninvoiceu A 
                LEFT JOIN bkontak B ON A.piukontak=B.kid 
	                WHERE A.piusumber = '".$transcode."'  
	                  AND A.piutanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'";            

    if($kontak != ""){
    	$query .= " AND A.piukontak='".$kontak."'";
    }

    $query .= " GROUP BY A.piuid,A.piunotransaksi,A.piutanggal";

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);

?>
<div class="header-report">
	<table class="table border-0">
		<thead>
			<tr>
				<th colspan="10" class="center"><h3 class="text-blue"><?= $company_name; ?></h3></th>				
			</tr>
			<tr>
				<th colspan="10" class="center"><p><?= $title; ?></p></th>				
			</tr>			
			<tr>
				<th colspan="10" class="center"><span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span></th>				
			</tr>						
		</thead>
	</table>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left" width="11%">Tanggal</th>
				<th class="left" width="12%">Nomor</th>				
				<th class="left">Kontak</th>
				<th class="right">Kas/Bank</th>
				<th class="right">Diskon</th>
				<th class="right">Retur</th>								
				<th class="right">Pajak</th>
				<th class="right">Selisih</th>				
				<th class="right">Total Dibayar</th>								
				<th class="left">No. Bupot</th>								
			</tr>
		</thead>
		<tbody>
			<?	
				$totalkas = 0; $totaldiskon = 0; $totalretur = 0; $totalpajak = 0; $totalselisih = 0; $totalbayar = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td class='right'>".eFormatNumber($row->totalkas,2)."</td>";
					echo "<td class='right'>".eFormatNumber($row->totaldiskon,2)."</td>";					
					echo "<td class='right'>".eFormatNumber($row->totalretur,2)."</td>";
					echo "<td class='right'>".eFormatNumber($row->totalpajak,2)."</td>";					
					echo "<td class='right'>".eFormatNumber($row->totalselisih,2)."</td>";
					echo "<td class='right'>".eFormatNumber($row->totalpiutang,2)."</td>";	
					echo "<td>".$row->nobupot."</td>";									
					echo "</tr>";								
					$totalkas += $row->totalkas;
					$totaldiskon += $row->totaldiskon;
					$totalretur += $row->totalretur;																						
					$totalpajak += $row->totalpajak;					
					$totalselisih += $row->totalselisih;	
					$totalbayar += $row->totalpiutang;										
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" class="px-1">Total</td>
				<td class="right"><?= eFormatNumber($totalkas,2); ?></td>				
				<td class="right"><?= eFormatNumber($totaldiskon,2); ?></td>
				<td class="right"><?= eFormatNumber($totalretur,2); ?></td>																
				<td class="right"><?= eFormatNumber($totalpajak,2); ?></td>								
				<td class="right"><?= eFormatNumber($totalselisih,2); ?></td>				
				<td class="right"><?= eFormatNumber($totalbayar,2); ?></td>
				<td></td>												
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>