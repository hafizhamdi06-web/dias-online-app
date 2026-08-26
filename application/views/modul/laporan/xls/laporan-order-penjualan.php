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
    $transcode = element('PJ_Order_Penjualan',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.souuraian 'uraian', ROUND(IFNULL(A.soutotaltransaksi,0),2) 'total',0 'totalv',
                          CASE WHEN SUM(IFNULL(C.sodkeluar,0))=0  THEN 'Belum Proses' 
                               WHEN SUM(IFNULL(C.sodorder,0))-SUM(IFNULL(C.sodkeluar,0))=0 THEN 'Selesai' 
                               ELSE 'Diproses Sebagian' 
                          END 'status',                          
                          SUM(IFNULL(C.sodorder,0)) 'qtyorder',
                          SUM(IFNULL(C.sodkeluar,0)) 'qtykirim',
                          SUM(IFNULL(C.sodmasuk,0)) 'qtyretur'
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid
                LEFT JOIN esalesorderd C ON A.souid=C.sodidsou 
	                WHERE A.sousumber = '".$transcode."'  
	                  AND A.soutanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'";            

    if($kontak != ""){
    	$query .= " AND A.soukontak='".$kontak."'";
    }

    $query .= " GROUP BY A.souid,A.sounotransaksi,A.soutanggal";

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
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left" width="11%">Tanggal</th>
				<th class="left" width="12%">Nomor</th>				
				<th class="left">Kontak</th>
				<th class="left">Keterangan</th>
				<th class="right" width="20%">Total Transaksi</th>
				<th class="left" width="11%">Status</th>				
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->kontak."</td>";
					echo "<td>".$row->uraian."</td>";
					echo "<td class='right'>".eFormatNumber($row->total,2)."</td>";
					echo "<td>".$row->status."</td>";
					echo "</tr>";								
					$total += $row->total;												
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="px-1">Total</td>
				<td class="right"><?= eFormatNumber($total,2); ?></td>				
				<td class="px-1"></td>				
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>