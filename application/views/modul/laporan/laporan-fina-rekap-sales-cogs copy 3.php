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

 	                  
	     $query = " SELECT  
                    'Biaya Pembelian untuk OpCo (Rp''000)' AS ket,

                    SUM(pt1)/1000  AS pt1,
                    SUM(pt2)/1000  AS pt2,
                    SUM(pt3)/1000  AS pt3,
                    SUM(pt4)/1000  AS pt4,
                    SUM(pt5)/1000  AS pt5,
                    SUM(pt6)/1000  AS pt6,
                    SUM(pt13)/1000 AS pt13,
                    SUM(pt99)/1000 AS pt99,
                    SUM(pt0)/1000  AS pt0,
                    SUM(pt_ip)/1000 AS pt_ip

                FROM (

                    SELECT  
                        npnama, 
                        icogs * sdkeluar AS pt0, 
                        CASE WHEN npid=1  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt1,
                        CASE WHEN npid=2  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt2,
                        CASE WHEN npid=3  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt3,
                        CASE WHEN npid=4  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt4,
                        CASE WHEN npid=5  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt5,
                        CASE WHEN npid=6  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt6,
                        CASE WHEN npid=13 AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt13,
                        CASE WHEN npid>6  AND npid<>13 AND susumber='SJ' THEN icogs*sdkeluar ELSE 0  END AS pt99,
                        0 AS pt_ip

                    FROM fstokd

                    LEFT JOIN bitem
                        ON iid = sditem

                    LEFT JOIN fstoku
                        ON suid = sdidsu

                    LEFT JOIN bgudang gudangtujuan
                        ON gudangtujuan.gid = sugudangtujuan

                    LEFT JOIN bgudang gudangsumber
                        ON gudangsumber.gid = sucabang

                    LEFT JOIN bnamapt
                        ON npid = gudangtujuan.gpt

                    WHERE
                        susumber = 'SJ'
                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                        AND '".tgl_database($date2)."'
                        AND gudangsumber.gpt = 1

                ) a

                UNION ALL  SELECT
                    'Biaya Pembelian untuk Eksternal IGY (Rp''000)' AS ket,

                    SUM(pt1)/1000  AS pt1,
                    SUM(pt2)/1000  AS pt2,
                    SUM(pt3)/1000  AS pt3,
                    SUM(pt4)/1000  AS pt4,
                    SUM(pt5)/1000  AS pt5,
                    SUM(pt6)/1000  AS pt6,
                    SUM(pt13)/1000 AS pt13,
                    SUM(pt99)/1000 AS pt99,
                    SUM(pt0)/1000  AS pt0,
                    SUM(pt_ip)/1000 AS pt_ip

                FROM (

                    SELECT
                        npnama,

                        (case when sutanggal < '2026/04/11' and susumber='IP' then icogs_lama else icogs end) * sdkeluar AS pt0,

                        0 AS pt1,
                        0 AS pt2,
                        0 AS pt3,
                        0 AS pt4,
                        0 AS pt5,
                        0 AS pt6,
                        0 AS pt13,
                        0 AS pt99,

                        (case when sutanggal < '2026/04/11' and susumber='IP' then icogs_lama else icogs end) * sdkeluar AS pt_ip

                    FROM fstokd

                    LEFT JOIN bitem
                        ON iid = sditem

                    LEFT JOIN fstoku
                        ON suid = sdidsu

                    LEFT JOIN bgudang 
                        on gid=sucabang

                    LEFT JOIN bnamapt 
                        on npid=gpt

                    WHERE
                        sustatus <> 9
                        AND susumber = 'IP' AND SDKELUAR*(SDHARGA-SDDISKON)>0
                        AND sucabang = 1
                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                        AND '".tgl_database($date2)."'

                ) b
                ";
	  
	  
	  

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport); 
    

?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>		
	<h3>MODEL HPP v2 — ASUMSI UTAMA (IGY: TRADING HUB + PENJUALAN SENDIRI)</h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark" >
				<th class="left px-1" colspan=10>A. SPLIT PEMBELIAN IGY — Porsi untuk OpCo vs Pelanggan Eksternal</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Keterangan</th>  
				<th class="left px-1">IGY-IGY</th> 	 
				<th class="left px-1">IGY-EIH</th> 	 
				<th class="left px-1">IGY-RNI</th> 	 
				<th class="left px-1">IGY-RNJ</th> 	 
				<th class="left px-1">IGY-IAA</th> 	 
				<th class="left px-1">IGY-IAK</th> 	 
				<th class="left px-1">IGY-DAPS</th> 
				<th class="left px-1">IGY-Lainnya</th> 	 
				<th class="left px-1">IGY-EXT</th> 	 
				<th class="left px-1">Total</th> 
			</tr>
		</thead>
		<tbody> 
			<?
				        $pt1=0;
                        $pt2=0;
                        $pt3=0;
                        $pt4=0;
                        $pt5=0;
                        $pt6=0;
                        $pt13=0;
                        $pt99=0;
                        $pt_ip=0;
                        $pt0=0;
				
				foreach ($datareport->data as $row) { 

					$sales=0;

    					echo "<tr>"; 
    					echo "<td>".$row->ket."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->pt1,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pt2,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pt3,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pt4,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pt5,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pt6,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pt13,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pt99,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->pt_ip,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->pt0,0)."</td>"; 
    					echo "</tr>";	 

                        $pt1+=$row->pt1;
                        $pt2+=$row->pt2;
                        $pt3+=$row->pt3;
                        $pt4+=$row->pt4;
                        $pt5+=$row->pt5;
                        $pt6+=$row->pt6;
                        $pt13+=$row->pt13;
                        $pt99+=$row->pt99;
                        $pt_ip+=$row->pt_ip;
                        $pt0+=$row->pt0;

				}

				echo "<tr>"; 
    					echo "<td>Total Pembelian</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt1,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt2,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt3,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt4,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt5,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt6,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt13,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt99,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt_ip,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt0,0)."</td>";  
    					echo "</tr>";	 	
					 

				echo "<tr>"; 
    					echo "<td>Markup Antar Perusahaan</td>";  
    					echo "<td class='right px-1'>0</td>";  
    					echo "<td class='right px-1'>5%</td>";  
    					echo "<td class='right px-1'>5%</td>";  
    					echo "<td class='right px-1'>5%</td>";  
    					echo "<td class='right px-1'>5%</td>";  
    					echo "<td class='right px-1'>5%</td>";  
    					echo "<td class='right px-1'>5%</td>";  
    					echo "<td class='right px-1'>5%</td>";  
    					echo "<td class='right px-1'>0</td>";  
    					echo "<td class='right px-1'>0</td>";  
    					echo "</tr>";	 	
					 
				

				echo "<tr>"; 
    					echo "<td>Harga Transfer ke OpCo (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt1,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt2+($pt2*5/100),0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt3+($pt3*5/100),0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt4+($pt4*5/100),0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt5+($pt5*5/100),0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt6+($pt6*5/100),0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt13+($pt13*5/100),0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt99+($pt99*5/100),0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt_ip,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pt1+($pt2+($pt2*5/100))+($pt3+($pt3*5/100))+($pt4+($pt4*5/100))+($pt5+($pt5*5/100))+($pt6+($pt6*5/100))+($pt13+($pt13*5/100))+($pt99+($pt99*5/100))+($pt_ip),0)."</td>";  
    					echo "</tr>";	 
				
			?>
		</tbody>
		<tfoot>
		 	 
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

</div>

<?
	 
	                   
    $query = "SELECT 
                SASALDOAWALIGY 'nilai_awal_igy' , 
                SASALDOAWALEIH 'nilai_awal_eih' , 
                SASALDOAWALRNI 'nilai_awal_rni' , 
                SASALDOAWALRNY 'nilai_awal_rny' , 
                SASALDOAWALIAA 'nilai_awal_iaa' , 
                SASALDOAWALIAK 'nilai_awal_iak' , 
                SASALDOAWALDAPS 'nilai_awal_daps', 
                SASALDOAKHIRIGY 'nilai_akhir_igy' , 
                SASALDOAKHIREIH 'nilai_akhir_eih' , 
                SASALDOAKHIRRNI 'nilai_akhir_rni' , 
                SASALDOAKHIRRNY 'nilai_akhir_rny' , 
                SASALDOAKHIRIAA 'nilai_akhir_iaa' , 
                SASALDOAKHIRIAK 'nilai_akhir_iak' , 
                SASALDOAKHIRDAPS 'nilai_akhir_daps' 
                
                from 
                fstoksaldo "; 

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport); 

 
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
    					echo "<tr>"; 
    					echo "<td>Persediaan Awal</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_igy,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_eih,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_rni,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_rny,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_iaa,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_iak,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_daps,0)."</td>"; 
    					echo "</tr>";	  
				 
    					echo "<tr>"; 
    					echo "<td>Persediaan Akhir</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_igy,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_eih,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_rni,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_rny,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_iaa,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_iak,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_daps,0)."</td>";  
    					echo "</tr>";	  
				} 
			?>
		</tbody>
		<tfoot>
		 	 
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

</div>


<?
	 
       $query = "  SELECT  ket as ket,   SUM(sj)/1000 as sj, sum(ip)/1000  AS ip 
                   FROM (
                    SELECT '1. Pendapatan Penjualan' as ket,
                    CASE WHEN npid<>1 and susumber='SJ' THEN (icogs+(icogs*5/100))*sdkeluar ELSE 0  END AS sj ,0 as ip
                                    FROM fstokd
                                    LEFT JOIN bitem ON iid = sditem
                                    LEFT JOIN fstoku ON suid = sdidsu
                                    LEFT JOIN bgudang gudangtujuan ON gudangtujuan.gid = sugudangtujuan
                                    LEFT JOIN bgudang gudangsumber  ON gudangsumber.gid = sucabang
                                    LEFT JOIN bnamapt   ON npid = gudangtujuan.gpt
                                    WHERE
                                        susumber = 'SJ'
                                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                                        AND '".tgl_database($date2)."'
                                        AND gudangsumber.gpt = 1
                    UNION ALL  " ;
                    
        $query .= "   SELECT '1. Pendapatan Penjualan'  as ket, 0 as sj,
                     (sdharga-sddiskon) * sdkeluar AS ip
                                    FROM fstokd
                                    LEFT JOIN bitem ON iid = sditem
                                    LEFT JOIN fstoku ON suid = sdidsu
                                    LEFT JOIN bgudang on gid=sucabang
                                    LEFT JOIN bnamapt on npid=gpt
                                    WHERE
                                        sustatus <> 9 and icogs>0
                                        AND susumber = 'IP'  
                                        AND sucabang = 1
                                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                                        AND '".tgl_database($date2)."'
                    UNION ALL  " ;
                    
        $query .= "   SELECT '2. HPP terkait'  as ket,
                     (icogs * sdkeluar)*-1 AS sj,  0 as ip
                                   FROM fstokd
                                    LEFT JOIN bitem ON iid = sditem
                                    LEFT JOIN fstoku ON suid = sdidsu
                                    LEFT JOIN bgudang gudangtujuan ON gudangtujuan.gid = sugudangtujuan
                                    LEFT JOIN bgudang gudangsumber  ON gudangsumber.gid = sucabang
                                    LEFT JOIN bnamapt   ON npid = gudangtujuan.gpt
                                    WHERE
                                        susumber = 'SJ'
                                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                                        AND '".tgl_database($date2)."'
                                        AND gudangsumber.gpt = 1 and npid<>1
                    UNION ALL  " ;
                    
        $query .= "   SELECT '2. HPP terkait'  as ket, 0 as sj,
                     ((case when sutanggal < '2026/04/11' and susumber='IP' then icogs_lama else icogs end) * sdkeluar)*-1 AS ip
                                    FROM fstokd
                                    LEFT JOIN bitem ON iid = sditem
                                    LEFT JOIN fstoku ON suid = sdidsu
                                    LEFT JOIN bgudang on gid=sucabang
                                    LEFT JOIN bnamapt on npid=gpt
                                    WHERE
                                        sustatus <> 9 and ((sdharga-sddiskon) * sdkeluar) > 0
                                        AND susumber = 'IP'  
                                        AND sucabang = 1
                                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                                        AND '".tgl_database($date2)."' ";

                                
  $query .= " ) a  group by ket
                ";
    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport); 

 
?>
 
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark" >
				<th class="left px-1" colspan=10>C. PENDAPATAN IGY (Rp'000)</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Keterangan</th>  
				<th class="left px-1">Antar PT</th> 	 
				<th class="left px-1">External</th>   	 
				<th class="left px-1">Total</th> 
			</tr>
		</thead>
		<tbody> 
			<? 
            $labasj=0;
            $labaip=0;
            $labatotal=0;
	                   
				foreach ($datareport->data as $row) {   
    					echo "<tr>"; 
    					echo "<td>".$row->ket."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->sj,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->ip,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->sj+$row->ip,0)."</td>";  
    					echo "</tr>";	 

                        $labasj+=$row->sj;
                        $labaip+=$row->ip;
                        $labatotal+=$row->sj+$row->ip; 
				} 
                
    					echo "<tr>"; 
    					echo "<td>Laba</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($labasj,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($labaip,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($labatotal,0)."</td>";  
    					echo "</tr>";	 
			?>
		</tbody>
		<tfoot>
		 	 
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

</div>

<pagebreak />




<?
	 
	                   
    $query = "SELECT 
                SASALDOAWALIGY/1000 'nilai_awal_igy' , 
                SASALDOAWALEIH/1000 'nilai_awal_eih' , 
                SASALDOAWALRNI/1000 'nilai_awal_rni' , 
                SASALDOAWALRNY/1000 'nilai_awal_rny' , 
                SASALDOAWALIAA/1000 'nilai_awal_iaa' , 
                SASALDOAWALIAK/1000 'nilai_awal_iak' , 
                SASALDOAWALDAPS/1000 'nilai_awal_daps', 
                SASALDOAKHIRIGY/1000 'nilai_akhir_igy' , 
                SASALDOAKHIREIH/1000 'nilai_akhir_eih' , 
                SASALDOAKHIRRNI/1000 'nilai_akhir_rni' , 
                SASALDOAKHIRRNY/1000 'nilai_akhir_rny' , 
                SASALDOAKHIRIAA/1000 'nilai_akhir_iaa' , 
                SASALDOAKHIRIAK/1000 'nilai_akhir_iak' , 
                SASALDOAKHIRDAPS/1000 'nilai_akhir_daps' 
                
                from 
                fstoksaldo "; 

    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport); 

 
?>


<div class="header-report"> 	
	<h3>HPP PER OPCO — TERMASUK SPLIT IGY (Rp'000)</h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead> 
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
    					echo "<tr>"; 
    					echo "<td>Persediaan Awal (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_igy,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_eih,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_rni,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_rny,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_iaa,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_iak,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_awal_daps,0)."</td>"; 
    					echo "</tr>";	  
				 
    					echo "<tr>"; 
    					echo "<td>Persediaan Akhir (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_igy,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_eih,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_rni,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_rny,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_iaa,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_iak,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_daps,0)."</td>";  
    					echo "</tr>";	  
				} 
			?>
		</tbody>
		<tfoot>
		 	 
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

</div>
