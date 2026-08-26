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
    $transcode = element('STK_Penyesuaian_Barang',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          A.suuraian 'uraian',
                          B.gnama 'gudangasal',
                          C.gnama 'gudangtujuan'                          
                 FROM fstoku A 
            LEFT JOIN bgudang B ON A.sugudangasal=B.gid 
            LEFT JOIN bgudang C ON A.sugudangtujuan=C.gid 
                WHERE A.susumber = '".$transcode."'  
                  AND A.sutanggal BETWEEN '".tgl_database($date1)."' 
                  AND '".tgl_database($date2)."'";            
    if($kontak != ""){
    	$query .= " AND A.sukontak='".$kontak."'";
    }

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);

?>
<div class="header-report">
	<table class="table border-0">
		<thead>
			<tr>
				<th colspan="4" class="center"><h3 class="text-blue"><?= $company_name; ?></h3></th>				
			</tr>
			<tr>
				<th colspan="4" class="center"><p><?= $title; ?></p></th>				
			</tr>			
			<tr>
				<th colspan="4" class="center"><span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span></th>				
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
				<th class="left" width="48%">Keterangan</th>
				<th class="left">Gudang</th>
			</tr>
		</thead>
		<tbody>
			<?	
				$total = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->uraian."</td>";
					echo "<td>".$row->gudangtujuan."</td>";
					echo "</tr>";								
					//$total += $row->total;												
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="px-1"></td>
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>