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
    $transcode = element('Fina_Jurnal_Umum',NID); // Lihat di global_helper
    $transcode = $CI->M_transaksi->prefixtrans($transcode);        
    $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                      A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv',C.cnocoa 'coa'   
                 FROM ctransaksiu A 
            LEFT JOIN bkontak B ON A.cukontak=B.kid 
            LEFT JOIN bcoa C ON A.curekkas = C.cid 
                WHERE A.cusumber = '".$transcode."'  
                  AND A.cutanggal BETWEEN '".tgl_database($date1)."' 
                  AND '".tgl_database($date2)."'";            
    if($kontak != ""){
    	$query .= " AND A.cukontak='".$kontak."'";
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
				<th class="left px-1" width="11%">Tanggal</th>
				<th class="left px-1" width="12%">Nomor</th>				
				<th class="left px-1" width="48%">Keterangan</th>
				<th class="right px-1" width="20%">Jumlah</th>
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
					echo "<td class='right px-1'>".eFormatNumber($row->total,2)."</td>";
					echo "</tr>";								
					$total += $row->total;												
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="3" class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($total,2); ?></td>				
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>