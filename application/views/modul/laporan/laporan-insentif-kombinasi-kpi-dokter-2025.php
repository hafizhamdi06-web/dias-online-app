<?php
	include ('style.php');
	$date1 = $_POST['tgldari'];
	$date2 = $_POST['tglsampai'];	
    if(isset($_POST['gudang'])){
    	$idgudang = $_POST['gudang'];
    } else {
    	$idgudang = "";
    }		
    if(isset($_POST['item'])){
    	$iditem = $_POST['item'];
    } else {
    	$iditem = "";
    }	
    

    $CI =& get_instance();
    
	                  
                    	$query = "SELECT gkode,ik2kode,inama, sutanggal,sunotransaksi, ikode,sdkeluar, sdkeluar*(sdharga-sddiskon) as nilai, knama, sdharga-sddiskon as harga
                    FROM fstokd 
                    LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM LEFT JOIN bgudang ON GID=SUCABANG  
                    left join bitemkelompok on ikid=ikelompokbaru left join bitemkelompok2020 on ik2id=ikelompok2020 left join bkontak on kid=sukontak
                    
                    WHERE SUSTATUS<>9 and SUSUMBER = 'IP'   
	                  AND sutanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'
	                  ";  
	                  
	               	$query = "SELECT   case WHEN iresep=1 then  F_ADARESEP_NILAI_SKINCARENDRUG(SUID) else sdkeluar*(sdharga-sddiskon) end as nilai,  
	               	        coalesce(sdreferal,0) as referal, iresep,iresepitter,ik2kode, ikomisi2020 as ikelompok2020, 
                            IMODEL,coalesce(F_TOTAL_ALKES_BY_URUTAN(suid,case when sdurutanawal=0 then sdurutan else sdurutanawal end),0)  as alkes,sdkeluar,sdharga,sddiskon,sunotransaksi,
                            sutanggal,ikode,ikelompok21,  sales.knama AS namasales,sales.KKODE AS KODEsales  ,  sales.KIDABSENSI AS IDABSENSI, sales.KIDEMPLOYEE  AS IDEMPLOYEE ,  
                            sales.KNAMAEMPLOYEE AS NAMAEMPLOYEE   
                            FROM fstokd LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM  LEFT JOIN bsatuan ON SID=ISATUAN   
                            inner JOIN bkontak sales ON sddokter=sales.KID   left join bitemkelompok on ikid=ikelompokbaru left join bitemkelompok2020 on ik2id=ikomisi2020   
                            left join bgudang on gid=sucabang WHERE  sales.KJENISKARYAWAN in (3,4) and  SUSTATUS<>9 and SUSUMBER = 'IP'  
                            and F_ADARESEP_DAN_124_NILAINYA(suid)>0  
                            and  sutanggal BETWEEN '".tgl_database($date1)."'    AND '".tgl_database($date2)."' ";  
   
	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        } 
	                  
	                   if($iditem != ""){
                        	$query .= " AND sditem='".$iditem."'";
                        }
    
    
    $query .= " order by 
	  gkode,namasales,ikode,sutanggal,sunotransaksi  ";

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
				<th class="left px-1">Nama Dokter</th>
				<th class="left px-1">No Transaksi</th>
				<th class="left px-1">Tanggal</th>  
				<th class="left px-1">Tindakan/Produk</th>
				<th class="right px-1">Qty</th> 
				<th class="right px-1">Nilai</th>					
			</tr>
		</thead>
		<tbody>
			<?	
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				foreach ($datareport->data as $row) {
					echo "<tr>";  
					echo "<td>".$row->namasales."</td>";
					echo "<td>".$row->sunotransaksi."</td>";
					echo "<td>".$row->sutanggal."</td>";
					echo "<td>".$row->inama."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->sdkeluar,2)."</td>"; 
					echo "<td class='right px-1'>".eFormatNumber($row->nilai,2)."</td>";
					echo "</tr>";	
					 
					$nilai += $row->nilai; 			
					$qty += $row->sdkeluar; 			
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($qty,2); ?></td> 
				<td class="right px-1"><?= eFormatNumber($nilai,2); ?></td>	 
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>