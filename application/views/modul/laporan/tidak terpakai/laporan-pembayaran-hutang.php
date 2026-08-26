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
	<h4 class="text-blue"><?= $company_name; ?></h4>		
	<h3><?= $title; ?></h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1" width="8%">Tanggal</th>
				<th class="left px-1" width="8%">Nomor</th>				
				<th class="left px-1">Kontak</th>
				<th class="right px-1">Kas/Bank</th>
				<th class="right px-1">Diskon</th>
				<th class="right px-1">Retur</th>								
				<th class="right px-1">PPh</th>
				<th class="right px-1">Selisih</th>				
				<th class="right px-1">Total Dibayar</th>								
				<th class="left px-1">No. Bupot</th>								
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
					echo "<td class='right px-1'>".eFormatNumber($row->totalkas,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->totaldiskon,2)."</td>";					
					echo "<td class='right px-1'>".eFormatNumber($row->totalretur,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->totalpajak,2)."</td>";					
					echo "<td class='right px-1'>".eFormatNumber($row->totalselisih,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->totalpiutang,2)."</td>";	
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
				<td class="right px-1"><?= eFormatNumber($totalkas,2); ?></td>				
				<td class="right px-1"><?= eFormatNumber($totaldiskon,2); ?></td>
				<td class="right px-1"><?= eFormatNumber($totalretur,2); ?></td>																
				<td class="right px-1"><?= eFormatNumber($totalpajak,2); ?></td>								
				<td class="right px-1"><?= eFormatNumber($totalselisih,2); ?></td>				
				<td class="right px-1"><?= eFormatNumber($totalbayar,2); ?></td>
				<td class="px-1"></td>												
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>