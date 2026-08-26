<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }	
    if(isset($_POST['namapt'])){
    	$idpt = $_POST['namapt'];
    } else {
    	$idpt = "";
    }	
	$tampilNol =  $_POST['saldo'];		
    

    $CI =& get_instance();  

 	            


	     $query = " SELECT  
                    '1. Biaya Pembelian untuk OpCo (Rp''000)' AS ket,

                    SUM(pt1)/1000  AS pembelian_igy,
                    SUM(pt2)/1000  AS pembelian_eih,
                    SUM(pt3)/1000  AS pembelian_rni,
                    SUM(pt4)/1000  AS pembelian_rnj,
                    SUM(pt5)/1000  AS pembelian_iaa,
                    SUM(pt6)/1000  AS pembelian_iak,
                    SUM(pt13)/1000 AS pembelian_daps,
                    SUM(pt99)/1000 AS pembelian_lainnya,
                    SUM(pt0)/1000  AS pembelian_total,
                    SUM(pt_ip)/1000 AS pembelian_ip,
                    SUM(pt_tmb)/1000 AS pembelian_tmb

                FROM (

                    SELECT  
                        npnama, 
                        CASE WHEN npid<>1  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt0, 
                        CASE WHEN npid=1  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt1,
                        CASE WHEN npid=2  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt2,
                        CASE WHEN npid=3  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt3,
                        CASE WHEN npid=4  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt4,
                        CASE WHEN npid=5  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt5,
                        CASE WHEN npid=6  AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt6,
                        CASE WHEN npid=13 AND susumber='SJ' THEN icogs*sdkeluar ELSE 0 END AS pt13,
                        CASE WHEN npid>6  AND npid<>13 AND susumber='SJ' THEN icogs*sdkeluar ELSE 0  END AS pt99,
                        0 AS pt_ip, 0  as pt_tmb

                    FROM fstokd

                    LEFT JOIN bitem  ON iid = sditem 
                    LEFT JOIN fstoku  ON suid = sdidsu 
                    LEFT JOIN bgudang gudangtujuan  ON gudangtujuan.gid = sugudangtujuan 
                    LEFT JOIN bgudang gudangsumber  ON gudangsumber.gid = sucabang 
                    LEFT JOIN bnamapt    ON npid = gudangtujuan.gpt 
                    WHERE
                        susumber = 'SJ' and icoa2021=1
                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                        AND '".tgl_database($date2)."'
                       

                ) a

                 UNION ALL
 

                SELECT
                    '2. (-) IGY Beli dari EXCO (Rp''000)' AS ket,

                    0  AS tmb_igy,
                    (SUM(pt2)/1000)*-1  AS tmb_eih,
                    (SUM(pt3)/1000)*-1  AS tmb_rni,
                    (SUM(pt4)/1000)*-1  AS tmb_rnj,
                    (SUM(pt5)/1000)*-1  AS tmb_iaa,
                    (SUM(pt6)/1000)*-1  AS tmb_iak,
                    (SUM(pt13)/1000)*-1 AS tmb_daps,
                    (SUM(pt99)/1000)*-1 AS tmb_lainnya,
                    (SUM(pt0)/1000)*-1  AS tmb_total,0,0 

                FROM (

                    SELECT
                        npnama,

                        (icogs+(icogs*5/100)) * sdmasuk AS pt0,

                        case when npid=1 then icogs * sdmasuk else 0 end AS pt1,
                        case when npid=2 then icogs * sdmasuk else 0 end   AS pt2,
                        case when npid=3 then icogs * sdmasuk  else 0 end  AS pt3,
                        case when npid=4 then icogs * sdmasuk else 0 end   AS pt4,
                        case when npid=5 then icogs * sdmasuk else 0 end  AS pt5,
                        case when npid=6 then icogs * sdmasuk else 0 end   AS pt6,
                        case when npid=13 then icogs * sdmasuk else 0 end   AS pt13,
                        case when npid>6 and npid<>13 then icogs * sdmasuk else 0 end   AS pt99 

                    FROM fstokd 
                    LEFT JOIN bitem    ON iid = sditem 
                    LEFT JOIN fstoku tmb  ON tmb.suid = sdidsu 
                    LEFT JOIN fstoku kmb on kmb.SUID=tmb.SUPRUID
                    LEFT JOIN bgudang      on gid=kmb.sucabang 
                    LEFT JOIN bnamapt      on npid=gpt

                    WHERE
                        tmb.sustatus <> 9 and icoa2021=1
                        AND tmb.susumber = 'TMB'
                        AND tmb.sucabang = 1
                        AND tmb.sutanggal BETWEEN '".tgl_database($date1)."'
                        AND '".tgl_database($date2)."'

                ) c

                UNION ALL  SELECT
                    '3. Biaya Pembelian untuk Eksternal IGY (Rp''000)' AS ket,

                    SUM(pt1)/1000  AS pt1,
                    SUM(pt2)/1000  AS pt2,
                    SUM(pt3)/1000  AS pt3,
                    SUM(pt4)/1000  AS pt4,
                    SUM(pt5)/1000  AS pt5,
                    SUM(pt6)/1000  AS pt6,
                    SUM(pt13)/1000 AS pt13,
                    SUM(pt99)/1000 AS pt99,
                    SUM(pt0)/1000  AS pt0,
                    SUM(pt_ip)/1000 AS pt_ip,
                    0 AS pt_tmb

                FROM (

                    SELECT
                        npnama,

                        icogs * sdmasuk AS pt0,

                        0 AS pt1,
                        0 AS pt2,
                        0 AS pt3,
                        0 AS pt4,
                        0 AS pt5,
                        0 AS pt6,
                        0 AS pt13,
                        0 AS pt99,

                         ((case when sutanggal < '2026/04/11' and susumber='IP' then icogs_lama else icogs end) * sdkeluar)  AS pt_ip
                                    FROM fstokd
                                    LEFT JOIN bitem ON iid = sditem
                                    LEFT JOIN fstoku ON suid = sdidsu
                                    LEFT JOIN bgudang on gid=sucabang
                                    LEFT JOIN bnamapt on npid=gpt
                                    WHERE
                                        sustatus <> 9 and ((sdharga-sddiskon) * sdkeluar) > 0 and icoa2021=1
                                        AND susumber = 'IP'  
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
				<th class="left px-1">RII-IGY</th> 	 
				<th class="left px-1">RII-EIH</th> 	 
				<th class="left px-1">RII-RNI</th> 	 
				<th class="left px-1">RII-RNJ</th> 	 
				<th class="left px-1">RII-IAA</th> 	 
				<th class="left px-1">RII-IAK</th> 	 
				<th class="left px-1">RII-DAPS</th> 
				<th class="left px-1">RII-Lainnya</th> 	 
				<th class="left px-1">RII-EXT</th> 	 
				<th class="left px-1">Total</th> 
			</tr>
		</thead>
		<tbody> 
			<?
 
 
                        $pembelian_igy=0;
                        $pembelian_eih=0;
                        $pembelian_rni=0;
                        $pembelian_rnj=0;
                        $pembelian_iaa=0;
                        $pembelian_iak=0;
                        $pembelian_daps=0;
                        $pembelian_lainnya=0;
                        $pembelian_ip=0;
                        $pembelian_tmb=0;
                        $pembelian_total=0;
				
				foreach ($datareport->data as $row) { 

					$sales=0;

    					echo "<tr>"; 
    					echo "<td>".$row->ket."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_igy,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_eih,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_rni,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_rnj,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_iaa,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_iak,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_daps,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_lainnya,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_ip,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($row->pembelian_total,0)."</td>"; 
    					echo "</tr>";	 

                        $pembelian_igy+=$row->pembelian_igy;
                        $pembelian_eih+=$row->pembelian_eih;
                        $pembelian_rni+=$row->pembelian_rni;
                        $pembelian_rnj+=$row->pembelian_rnj;
                        $pembelian_iaa+=$row->pembelian_iaa;
                        $pembelian_iak+=$row->pembelian_iak;
                        $pembelian_daps+=$row->pembelian_daps;
                        $pembelian_lainnya+=$row->pembelian_lainnya;
                        $pembelian_ip+=$row->pembelian_ip;
                        $pembelian_tmb+=$row->pembelian_tmb;
                        $pembelian_total+=$row->pembelian_total;
 

				}

				echo "<tr>"; 
    					echo "<td>Total Pembelian</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_igy,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_eih,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_rni,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_rnj,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_iaa,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_iak,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_daps,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_lainnya,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_ip,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";  
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
					 
				
                        $pembelian_igy_markup=$pembelian_igy;
                        $pembelian_eih_markup=$pembelian_eih+($pembelian_eih*5/100);
                        $pembelian_rni_markup=$pembelian_rni+($pembelian_rni*5/100);
                        $pembelian_rnj_markup=$pembelian_rnj+($pembelian_rnj*5/100);
                        $pembelian_iaa_markup=$pembelian_iaa+($pembelian_iaa*5/100);
                        $pembelian_iak_markup=$pembelian_iak+($pembelian_iak*5/100);
                        $pembelian_daps_markup=$pembelian_daps+($pembelian_daps*5/100);
                        $pembelian_lainnya_markup=$pembelian_lainnya+($pembelian_lainnya*5/100);
                        $pembelian_ip_markup=$pembelian_ip;
                        $pembelian_tmb_markup=$pembelian_tmb+($pembelian_tmb*5/100);
                        $pembelian_total_markup=$pembelian_igy_markup+$pembelian_eih_markup+$pembelian_rni_markup+$pembelian_rnj_markup+$pembelian_iaa_markup+$pembelian_iak_markup+$pembelian_daps_markup+$pembelian_lainnya_markup+$pembelian_ip;


				echo "<tr>"; 
    					echo "<td>Harga Transfer ke OpCo (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_igy_markup,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_eih_markup,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_rni_markup,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_rnj_markup,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_iaa_markup,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_iak_markup,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_daps_markup,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_lainnya_markup,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_ip_markup,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total_markup,0)."</td>";  
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
                fstoksaldo where saotc=1 "; 

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
                    
                $nilai_awal_igy=$row->nilai_awal_igy;
                $nilai_awal_eih=$row->nilai_awal_eih;
                $nilai_awal_rni=$row->nilai_awal_rni;
                $nilai_awal_rny=$row->nilai_awal_rny;
                $nilai_awal_iaa=$row->nilai_awal_iaa;
                $nilai_awal_iak=$row->nilai_awal_iak;
                $nilai_awal_daps=$row->nilai_awal_daps;

                $nilai_akhir_igy=$row->nilai_akhir_igy;
                $nilai_akhir_eih=$row->nilai_akhir_eih;
                $nilai_akhir_rni=$row->nilai_akhir_rni;
                $nilai_akhir_rny=$row->nilai_akhir_rny;
                $nilai_akhir_iaa=$row->nilai_akhir_iaa;
                $nilai_akhir_iak=$row->nilai_akhir_iak;
                $nilai_akhir_daps=$row->nilai_akhir_daps; 
                
                $nilai_akhir_opco=$nilai_akhir_eih+$nilai_akhir_rni+$nilai_akhir_rny+$nilai_akhir_iaa+$nilai_akhir_iak+$nilai_akhir_daps; 

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
    					echo "<td>Persediaan Akhir — Porsi dari Pembelian AnPerus IGY</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_opco,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_eih,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_rni,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_rny,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_iaa,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_iak,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_daps,0)."</td>";  
    					echo "</tr>";	    
				 
    					echo "<tr>"; 
    					echo "<td>Persediaan Akhir — Porsi dari Pembelian Eksternal IGY sendiri</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($row->nilai_akhir_igy,0)."</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
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
                                        susumber = 'SJ' and icoa2021=1
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
                                        sustatus <> 9 and icogs>0 and icoa2021=1
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
                                        susumber = 'SJ' and icoa2021=1
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
                                        sustatus <> 9 and ((sdharga-sddiskon) * sdkeluar) > 0 and icoa2021=1
                                        AND susumber = 'IP'  
                                        AND sucabang = 1
                                        AND sutanggal BETWEEN '".tgl_database($date1)."'
                                        AND '".tgl_database($date2)."' 
                    UNION ALL  " ;
                    
        $query .= " SELECT '3. IGY beli dari OPCO' as ket,
                    ((icogs+(icogs*5/100))*sdmasuk)*-1 AS sj ,0 as ip
                                   FROM fstokd 
                                    LEFT JOIN bitem    ON iid = sditem 
                                    LEFT JOIN fstoku tmb  ON tmb.suid = sdidsu 
                                    LEFT JOIN fstoku kmb on kmb.SUID=tmb.SUPRUID
                                    LEFT JOIN bgudang      on gid=kmb.sucabang 
                                    LEFT JOIN bnamapt      on npid=gpt 
                                    WHERE
                                        tmb.sustatus <> 9 and icoa2021=1
                                        AND tmb.susumber = 'TMB'
                                        AND tmb.sucabang = 1
                                        AND tmb.sutanggal BETWEEN '".tgl_database($date1)."'
                                        AND '".tgl_database($date2)."'  
                    UNION ALL  " ;
                    
        $query .= "   SELECT '4. HPP IGY beli dari OPCO' as ket, (icogs * sdmasuk)as sj,
                      0 AS ip
                                    FROM fstokd 
                                    LEFT JOIN bitem    ON iid = sditem 
                                    LEFT JOIN fstoku tmb  ON tmb.suid = sdidsu 
                                    LEFT JOIN fstoku kmb on kmb.SUID=tmb.SUPRUID
                                    LEFT JOIN bgudang      on gid=kmb.sucabang 
                                    LEFT JOIN bnamapt      on npid=gpt

                                    WHERE
                                        tmb.sustatus <> 9 and icoa2021=1
                                        AND tmb.susumber = 'TMB'
                                        AND tmb.sucabang = 1
                                        AND tmb.sutanggal BETWEEN '".tgl_database($date1)."'
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
	 
	       
 
?>


<div class="header-report"> 	
	<h4 class="text-blue"><?= $company_name; ?></h4>	
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
               

                        $pembeliancabang=$pembelian_igy_markup+$pembelian_eih_markup+$pembelian_rni_markup+$pembelian_iaa_markup+$pembelian_iak_markup+$pembelian_daps_markup;
				 
    					echo "<tr>"; 
    					echo "<td class='px-1'>Persediaan Awal (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_awal_igy,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($nilai_awal_eih,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($nilai_awal_rni,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($nilai_awal_rny,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($nilai_awal_iaa,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($nilai_awal_iak,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($nilai_awal_daps,0)."</td>";  
    					echo "</tr>";	
                        
                        echo "<tr>"; 
    					echo "<td class='px-1'>(+) Pembelian dari Pemasok Eksternal — Porsi OpCo Lain (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembeliancabang,0)."</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>";  
    					echo "<td class='right px-1'>0</td>";  
    					echo "</tr>";	 
                        
                        echo "<tr>"; 
    					echo "<td class='px-1'>(+) Pembelian dari Pemasok Eksternal — Porsi IGY Sendiri (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_ip,0)."</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>"; 
    					echo "<td class='right px-1'>0</td>";  
    					echo "<td class='right px-1'>0</td>";  
    					echo "</tr>";	 
				 
    					echo "<tr>"; 
    					echo "<td class='px-1'>(+) Pembelian dari IGY — Harga Transfer (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_igy_markup,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_eih_markup,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_rni_markup,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_rnj_markup,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_iaa_markup,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_iak_markup,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_daps_markup,0)."</td>";  
    					echo "</tr>"; 
				 
    					echo "<tr>"; 
    					echo "<td class='px-1'>(-) Persediaan Akhir — AnPerus (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_igy,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_eih,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_rni,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_rny,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_iaa,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_iak,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_daps,0)."</td>";   
    					echo "</tr>";	
				 
    					echo "<tr>"; 
    					echo "<td class='px-1'>(-) Persediaan Akhir — Eksternal IGY (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($nilai_akhir_opco,0)."</td>"; 
    					echo "<td class='right px-1'>0</td>";   
    					echo "<td class='right px-1'>0</td>";   
    					echo "<td class='right px-1'>0</td>";    
    					echo "<td class='right px-1'>0</td>";   
    					echo "<td class='right px-1'>0</td>";    
    					echo "<td class='right px-1'>0</td>";     
    					echo "</tr>";	


                        $hpp_igy = $nilai_awal_igy + $pembeliancabang + $pembelian_ip - $nilai_akhir_igy - $nilai_akhir_opco ;
                        $hpp_eih = $nilai_awal_eih +  $pembelian_eih_markup - $nilai_akhir_eih ;
                        $hpp_rni = $nilai_awal_rni +  $pembelian_rni_markup - $nilai_akhir_rni ;
                        $hpp_rny = $nilai_awal_rny +  $pembelian_rnj_markup - $nilai_akhir_rny ;
                        $hpp_iaa = $nilai_awal_iaa +  $pembelian_iaa_markup - $nilai_akhir_iaa ;
                        $hpp_iak = $nilai_awal_iak +  $pembelian_iak_markup - $nilai_akhir_iak ;
                        $hpp_daps = $nilai_awal_daps + $pembelian_daps_markup - $nilai_akhir_daps ;
				 
    					echo "<tr>"; 
    					echo "<td class='px-1'><b>HPP (Rp'000)</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($hpp_igy,0)."</td>"; 
    					echo "<td class='right px-1'>".eFormatNumber($hpp_eih,0)."</td>";   
    					echo "<td class='right px-1'>".eFormatNumber($hpp_rni,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($hpp_rny,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($hpp_iaa,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($hpp_iak,0)."</td>";  
    					echo "<td class='right px-1'>".eFormatNumber($hpp_daps,0)."</td>";   
    					echo "</tr>";	  
				 
			?>
		</tbody>
		<tfoot>
		 	 
			
			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	 

</div>
