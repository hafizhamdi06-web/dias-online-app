<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }	
	$tampilNol =  $_POST['saldo'];		
    

    $CI =& get_instance();  
 
	 
	                   
    $query = "SELECT 
                sum(nilai_1) 'nilai_1' , 
                sum(nilai_2) 'nilai_2' , 
                sum(nilai_3) 'nilai_3' , 
                sum(nilai_4) 'nilai_4' , 
                sum(nilai_5) 'nilai_5' , 
                sum(nilai_6) 'nilai_6' , 
                sum(nilai_13) 'nilai_13' 
                
                from 
                ( 
                            SELECT
                                npnama,
                                npid,
                                ikode,  

                                CASE WHEN npid = 1  THEN sum(qty * icogs) ELSE 0 END AS nilai_1,
                                CASE WHEN npid = 2  THEN sum(qty * icogs) ELSE 0 END AS nilai_2,
                                CASE WHEN npid = 3  THEN sum(qty * icogs) ELSE 0 END AS nilai_3,
                                CASE WHEN npid = 4  THEN sum(qty * icogs) ELSE 0 END AS nilai_4,
                                CASE WHEN npid = 5  THEN sum(qty * icogs) ELSE 0 END AS nilai_5,
                                CASE WHEN npid = 6  THEN sum(qty * icogs) ELSE 0 END AS nilai_6,
                                CASE WHEN npid = 13 THEN sum(qty * icogs) ELSE 0 END AS nilai_13

                            FROM
                            (
                                SELECT
                                    npnama,
                                    npid,
                                    ikode,
                                    icogs,

                                    IFNULL(SUM(sdmasuk - IF(sddaripaket <> 0 aND sdkedatangan = 0,0,sdkeluar)),0) AS qty

                                FROM fstokd
                                INNER JOIN bitem
                                    ON iid = sditem
                                INNER JOIN fstoku
                                    ON suid = sdidsu
                                INNER JOIN bgudang
                                    ON gid = sucabang
                                INNER JOIN bnamapt
                                    ON npid = gpt
                                WHERE
                                    sustatus <> 9
                                    AND sdcancel = 0
                                    AND sutanggal < '".tgl_database($date1)."'
                                    "; 

                                    $query .= "

                                        GROUP BY
                                            npnama,
                                            npid,
                                            ikode,
                                            icogs

                                        HAVING qty > 0

                            ) a  group by npnama, npid, ikode
                ) b
                          ";
 
 
	  
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport); 




                       
    $query = "SELECT 
                sum(nilai_1) 'nilai_1' , 
                sum(nilai_2) 'nilai_2' , 
                sum(nilai_3) 'nilai_3' , 
                sum(nilai_4) 'nilai_4' , 
                sum(nilai_5) 'nilai_5' , 
                sum(nilai_6) 'nilai_6' , 
                sum(nilai_13) 'nilai_13' 
                
                from 
                ( 
                            SELECT
                                npnama,
                                npid,
                                ikode,  

                                CASE WHEN npid = 1  THEN sum(qty * icogs) ELSE 0 END AS nilai_1,
                                CASE WHEN npid = 2  THEN sum(qty * icogs) ELSE 0 END AS nilai_2,
                                CASE WHEN npid = 3  THEN sum(qty * icogs) ELSE 0 END AS nilai_3,
                                CASE WHEN npid = 4  THEN sum(qty * icogs) ELSE 0 END AS nilai_4,
                                CASE WHEN npid = 5  THEN sum(qty * icogs) ELSE 0 END AS nilai_5,
                                CASE WHEN npid = 6  THEN sum(qty * icogs) ELSE 0 END AS nilai_6,
                                CASE WHEN npid = 13 THEN sum(qty * icogs) ELSE 0 END AS nilai_13

                            FROM
                            (
                                SELECT
                                    npnama,
                                    npid,
                                    ikode,
                                    icogs,

                                    IFNULL(SUM(sdmasuk - IF(sddaripaket <> 0 aND sdkedatangan = 0,0,sdkeluar)),0) AS qty

                                FROM fstokd
                                INNER JOIN bitem
                                    ON iid = sditem
                                INNER JOIN fstoku
                                    ON suid = sdidsu
                                INNER JOIN bgudang
                                    ON gid = sucabang
                                INNER JOIN bnamapt
                                    ON npid = gpt
                                WHERE
                                    sustatus <> 9
                                    AND sdcancel = 0
                                    AND sutanggal <= '".tgl_database($date2)."'
                                    "; 

                                    $query .= "

                                        GROUP BY
                                            npnama,
                                            npid,
                                            ikode,
                                            icogs

                                        HAVING qty > 0

                            ) a  group by npnama, npid, ikode
                ) b
                          ";
 
 
	  
	  

    $datareport2 = $CI->M_transaksi->get_data_query($query);
    $datareport2 = json_decode($datareport2); 
   

?>
 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark" >
				<th class="left px-1" colspan=10>B. SALDO PERSEDIAAN PER OPCO (Rp'000)</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Keterangan</th>  
				<th class="left px-1">IGY</th> 	 
				<th class="left px-1">EIH</th> 	 
				<th class="left px-1">RNI</th> 	 
				<th class="left px-1">RNJ</th> 	 
				<th class="left px-1">IAA</th> 	 
				<th class="left px-1">IAK</th> 	 
				<th class="left px-1">DAPS</th>  
			</tr>
		</thead>
		<tbody> 
			<? 
				foreach ($datareport->data as $row) {  
					$sales=0; 
    					echo "<tr>"; 
    					echo "<td>Persediaan Awal</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_1,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_2,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_3,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_4,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_5,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_6,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_13,0)."</td>";  
    					echo "</tr>";	  
				} 
			?>
			<? 
				foreach ($datareport2->data as $row) {  
					$sales=0; 
    					echo "<tr>"; 
    					echo "<td>Persediaan Akhir</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_1,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_2,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_3,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_4,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_5,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_6,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_13,0)."</td>";  
    					echo "</tr>";	  
				} 
			?>
		</tbody>
		<tfoot>
		 	 
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

</div>