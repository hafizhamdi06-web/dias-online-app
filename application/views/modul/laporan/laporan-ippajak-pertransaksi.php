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
    
	                  
                    	$query = "SELECT cttipeid, CTTIDAKDITARIK,CTNAMA,gkode,ik2kode,inama, sutanggal,sunotransaksi, ikode,sdkeluar, sdkeluar*(sdharga-sddiskon) as nilai, knama, sdharga-sddiskon as harga,
                    	coalesce(F_ALKES_COGS(sdid,suid),0)  as alkes, sutotaltransaksi 'total', sutotaltransaksi-SUTOTALVOUCHER 'totalbayar' , coalesce(F_ALKES_COGS_HPP(sdid,suid),0)  as alkeshpp , sdkeluar*icogs 'cogs'
                    FROM fstokd 
                    LEFT JOIN fstoku ON SUID=SDIDSU LEFT JOIN bitem ON IID=SDITEM LEFT JOIN bgudang ON GID=SUCABANG  
                    left join bitemkelompok on ikid=ikelompokbaru left join bitemkelompok2020 on ik2id=ikelompok2020 left join bkontak on kid=sukontak
                    join bcoatipe_perpt on ctid=icoa2021 
                    
                    WHERE SUSTATUS<>9 and SUSUMBER = 'IP'   
	                  AND sutanggal BETWEEN '".tgl_database($date1)."' 
	                  AND '".tgl_database($date2)."'
	                  ";  
	                  
	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        } 
	                  
	                   if($iditem != ""){
                        	$query .= " AND sditem='".$iditem."'";
                        }
    
    
    $query .= " order by 
	  CTNAMA,gkode,ik2kode,ikode,sutanggal,sunotransaksi  ";

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
				<th class="left px-1">Kelompok</th>
				<th class="left px-1">Tindakan/Produk</th>
				<th class="left px-1">Tanggal</th>
				<th class="left px-1">No IP</th>
				<th class="left px-1">Nama Pasien</th>
				<th class="right px-1">Qty</th>
				<th class="right px-1">Harga</th>
				<th class="right px-1">Nilai</th>	
				<th class="right px-1">PPN</th>					
			</tr>
		</thead>
		<tbody>
			<?	
				$jumlahdatang = 0; $nilai = 0; $qty = 0; $cabang=''; $totalppn = 0 ; 
				foreach ($datareport->data as $row) {
				    
				     $harga=$row->nilai+$row->alkes;
				     $total=$row->total;
				     $totalbayar=$row->totalbayar;
				     $tidakditarik=$row->CTTIDAKDITARIK;
				     $cttipeid=$row->cttipeid;
				     $alkeshpp=$row->alkeshpp;
				     $cogs=$row->cogs;
				     $alkescogs=0;
				     $produkcogs=0;
				     $ppn=0;
				     $nilaitransaksi=0;
				     
				     
				  
				    
				    //mencari nilai transaksi bersih dan yang kena perhitungan saja, yang tidak DITARIK ( DP, ONGKIR, DLL )
				    if ($tidakditarik==1)
				        {
				            $nilaitransaksi=0;
				        }
				    else { 
				            $nilaitransaksi=$harga/$total*$totalbayar;
				    }
				    
				    
				    //hitung cogs Produk
				    if ($nilaitransaksi>0)   
				    {
				        
				        $alkescogs=$alkeshpp/$total*$totalbayar;
				    
				       if($cttipeid==1 || $cttipeid==2 || $cttipeid==9 || $cttipeid==11   )  {
				           
				          $produkcogs= $nilaitransaksi * 80/100 ;
				          $produkcogs= $produkcogs/1.11 ;
				          
				       }
				       else {
				          $produkcogs=$cogs/$total*$totalbayar; 
				       }
				       
				        $ppn=($alkescogs+$produkcogs)*11/100 ; 
				    
				    }
				    
				   
				    
				    if ($ppn>0){
				    
					echo "<tr>"; 
					echo "<td>".$row->CTNAMA."</td>";
					echo "<td>".$row->inama."</td>";
					echo "<td>".$row->sutanggal."</td>";
					echo "<td>".$row->sunotransaksi."</td>";
					echo "<td class='left px-1'>".$row->knama."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->sdkeluar,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->harga,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->nilai,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($ppn,2)."</td>";
					echo "</tr>";
				    
					 
					$nilai += $row->nilai; 			
					$qty += $row->sdkeluar; 
					$totalppn +=  $ppn; 	
					
				    }
					
					$cabang=$row->gkode;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" class="px-1">Total</td>
				<td class="right px-1"><?= eFormatNumber($qty,2); ?></td>
				<td class="right px-1"></td>					
				<td class="right px-1"><?= eFormatNumber($nilai,2); ?></td>	 
				<td class="right px-1"><?= eFormatNumber($totalppn,2); ?></td>	 
			</tr>			
		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>	
</div>