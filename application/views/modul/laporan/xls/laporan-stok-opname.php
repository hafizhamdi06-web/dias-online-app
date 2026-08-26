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
    $transcode = element('STK_Stok_Opname',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                          A.souuraian 'uraian', C.knama 'karyawan', B.gnama 'gudang'
                 FROM fstokopnameu A 
            LEFT JOIN bgudang B ON A.sougudang=B.gid
            LEFT JOIN bkontak C ON A.soukontak=C.kid  
                WHERE A.sousumber = '".$transcode."'  
                  AND A.soutanggal BETWEEN '".tgl_database($date1)."' 
                  AND '".tgl_database($date2)."'";  

    if($kontak != ""){
    	$query .= " AND A.soukontak='".$kontak."'";
    }

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);

?>
<div class="header-report">
	<table class="table border-0">
		<thead>
			<tr>
				<th colspan="5" class="center"><h3 class="text-blue"><?= $company_name; ?></h3></th>				
			</tr>
			<tr>
				<th colspan="5" class="center"><p><?= $title; ?></p></th>				
			</tr>			
			<tr>
				<th colspan="5" class="center"><span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span></th>				
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
				<th class="left">Karyawan</th>				
			</tr>
		</thead>
		<tbody>
			<?	
				foreach ($datareport->data as $row) {
					echo "<tr>";
					echo "<td>".$row->tanggal."</td>";					
					echo "<td>".$row->nomor."</td>";
					echo "<td>".$row->uraian."</td>";
					echo "<td>".$row->gudang."</td>";
					echo "<td>".$row->karyawan."</td>";					
					echo "</tr>";								
					//$total += $row->total;												
				}
			?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>