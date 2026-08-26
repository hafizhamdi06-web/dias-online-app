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
    $transcode = element('PJ_Uang_Muka_Penjualan',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.dpketerangan 'uraian', IFNULL(A.dpjumlah,0) 'total',0 'totalv',
                          CASE WHEN SUM(IFNULL(A.dpjumlahbayar,0))=0  THEN 'Belum Dibayar' 
                               WHEN SUM(IFNULL(A.dpjumlah,0))-SUM(IFNULL(A.dpjumlahbayar,0))=0 THEN 'Lunas' 
                               ELSE 'Dibayar Sebagian' 
                          END 'status',                          
                          SUM(IFNULL(A.dppakaiiv,0)) 'dipakaifaktur'
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid 
	                WHERE A.dpsumber = '".$transcode."'  
	                  AND A.dptanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'";            

    if($kontak != ""){
    	$query .= " AND A.dpkontak='".$kontak."'";
    }

    $query .= " GROUP BY A.dpid,A.dpnotransaksi,A.dptanggal";

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);

?>
<div class="header-report">
	<table class="table border-0">
		<thead>
			<tr>
				<th colspan="7" class="center"><h3 class="text-blue"><?= $company_name; ?></h3></th>				
			</tr>
			<tr>
				<th colspan="7" class="center"><p><?= $title; ?></p></th>				
			</tr>			
			<tr>
				<th colspan="7" class="center"><span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span></th>				
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
				<th class="left">Uraian</th>
				<th class="left" width="8%">Status</th>								
				<th class="right">Jumlah</th>
				<th class="right">Dipakai Invoice</th>
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0; $totalpakai = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td>".$row->uraian."</td>";
					echo "<td>".$row->status."</td>";					
					echo "<td class='right'>".eFormatNumber($row->total,2)."</td>";
					echo "<td class='right'>".eFormatNumber($row->dipakaifaktur,2)."</td>";					
					echo "</tr>";								
					$total += $row->total;
					$totalpakai += $row->dipakaifaktur;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" class="px-1">Total</td>
				<td class="right"><?= eFormatNumber($total,2); ?></td>				
				<td class="right"><?= eFormatNumber($totalpakai,2); ?></td>
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>