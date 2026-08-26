<?php
	include ('style_pos.php');

    $CI =& get_instance();

    $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak', B.kidpasien 'idpasien',
                      A.suuraian 'uraian', C.knama 'karyawan', B.k1alamat 'alamat', A.sutotaltransaksi 'total', A.sutotalbayar 'totalbayar', A.sutotalsisa 'totalsisa',  
                       A.sutotalkas 'kasjumlah', 
                       A.sutotalkartudebit 'debitjumlah', A.sunokartudebit 'debitno', A.sunamadebit 'debitnama',
                       A.subankdebit 'debitbank', A.sudebitjenis 'debitjenis', A.suattention 'debitbanklain',
                       A.sutotalkartukredit 'kreditjumlah', A.sunokartukredit 'kreditno', A.sunamakredit 'kreditnama',
                       A.subankkredit 'kreditbank', A.sukreditjenis 'kreditjenis', A.sunofakturpajak 'kreditbanklain',
                       A.sutotaltransfer 'transferjumlah', A.sunotransfer 'transferno', A.sunamatransfer 'transfernama',
                       A.subanktransfer 'transferbank' ,
                       A.sutotalvoucher 'voucherjumlah', A.sunovoucher 'voucherno', A.sustatuskirim 'voucherid',
                       A.sutotaldp  'dpjumlah', A.sudp1 'dpjumlah', A.sudpid 'dpid' , A.sujenisdp 'dpjenis',  
                       A.sucabang 'cabang', A.surekammedis 'rekammedis', A.sutotaltada 'totaltanpadp', A.sustatustada 'statustada',
                       A.sumerchantjumlah  'merchantjumlah', A.sumerchantno 'merchantno' , A.sumerchantjenis 'merchantjenis',
                       A.sunilaipiutang 'piutangjumlah', A.susurgerydpidu 'surgerydpidu', A.susurgerydptotal 'surgerydptotal', A.susurgerydppembayaran 'surgerydppembayaran',
                       A.supendapatandp 'pendapatandp',
                       D.gkode 'kodecabang', D.galamat2 'alamat', D.gtelp 'notelp', D.GNOHP 'nohp'
                      
                 FROM fstoku A 
            LEFT JOIN bkontak B ON A.sukontak=B.kid
            LEFT JOIN bkontak C ON A.sukaryawan=C.kid 
            LEFT JOIN bgudang D on A.sucabang=D.gid
                WHERE A.suid = '".$id."'";

    $header = $CI->M_transaksi->get_data_query($query);
    $header = json_decode($header);	

    $querydtl  = "SELECT B.ikode 'noitem',B.inama 'item',A.sdkeluar 'qty',C.skode 'satuan',A.sdcatatan 'catatan', concat(sdnoref,'-',sdlantai2,'-',left(E.knama,10),'-',left(F.knama,10)) 'ket',
                        A.sdharga 'harga', A.sddiskonpersen 'dis1', A.sddiskonpersen2 'dis2', A.sddiskon 'diskon', A.sdkeluar * (A.sdharga-A.sddiskon) 'subtotal',
                        coalesce(H.pukode,'')  'kodepaket' , J.mpukode 'kodepromo' , H.pucetakheadersaja 'cetakheadersaja', A.sdcatatankoli 'nopaket', A.sdkedatangan 'kedatangan', H.pujumlah 'jumlahpaket',
                        case when coalesce(H.pujumlah,0) > 1 then concat('Nomer ',A.sdcatatankoli, ' Ke ',A.sdkedatangan) else '' end 'ketpaket', concat(coalesce(H.pukode,''),A.sdkedatangan) 'kodepaketlengkap'
                        
                 FROM fstokd A 
            LEFT JOIN bitem B ON A.sditem = B.iid 
            LEFT JOIN bsatuan C ON A.sdsatuan=C.sid 
            LEFT JOIN bgudang D ON A.sdgudang=D.gid 
            LEFT JOIN bkontak E ON E.kid=A.sddokter
            LEFT JOIN bkontak F on F.kid=A.sdkaryawan
            LEFT JOIN epaketd G on G.pdid=A.sdsodurutan
            LEFT JOIN epaketu H on H.puid=G.pdidu
            LEFT JOIN emasterpromod I on A.sdidpromo=I.mpdid
            LEFT JOIN emasterpromou J on J.mpuid=I.mpdidU 
            
                WHERE A.sdidsu = '".$id."' ORDER BY coalesce(H.pukode,''),A.sdurutan ASC";

    $detil = $CI->M_transaksi->get_data_query($querydtl);
    $detil = json_decode($detil);

    foreach ($header->data as $row) {
    	$cabang = $row->cabang;
    	$nomor = $row->nomor;
    	$tanggal = $row->tanggal;
    	$kontak = $row->kontak;    	    	
    	$alamat = $row->alamat;    	    	    	
    	$uraian = empty($row->uraian) ? "-" : $row->uraian;    
    	$total = $row->total;   
    	$totalbayar = $row->totalbayar;   
    	$totalsisa = $row->totalsisa; 
    	$totaltanpadp = $row->totaltanpadp;
    	$statustada = $row->statustada;
    	
    	
    	$karyawan = $row->karyawan;  
    	$idpasien = $row->idpasien;  
    	  
    	$kasjumlah = $row->kasjumlah; 
    	$debitjumlah = $row->debitjumlah; 
    	$kreditjumlah = $row->kreditjumlah;  
    	$transferjumlahh = $row->transferjumlah;  
    	$voucherjumlah = $row->voucherjumlah;  
    	$dpjumlah = $row->dpjumlah;  
    	$merchantjumlah = $row->merchantjumlah; 
    	$piutangjumlah = $row->piutangjumlah; 
    	$pendapatandp = $row->pendapatandp; 
    	 
    	$kreditno = $row->kreditno; 
    	$kreditjenis = $row->kreditjenis; 
    	$debitno = $row->debitno; 
    	$debitjenis = $row->debitjenis; 
    	
    	$kreditbank = $row->kreditbank; 
    	$debitbank = $row->debitbank; 
    	$transferbank = $row->transferbank; 
    	$merchantjenis = $row->merchantjenis; 
    	
    	
    	$nohp=$row->nohp;
    	$alamat=$row->alamat;
    	$notelp=$row->notelp;
    	
    }
    
    $point=0;
    if($statustada==1 && $totaltanpadp>=100000)
    {
        $point=fmod($totaltanpadp, 10000);
        $point=($totaltanpadp-$point)/10000;
    }
     



?>
<div class="header-report">
    
    	<table class="table" style="margin-top: 0px;">
			<tbody>
				<tr>
				    
				    <?
				    $namapt='NMW';
				    $namaig='nmwskincare www.nmwskincare.co.id';
				    if ($cabang==26 || $cabang==28 || $cabang==48 )
				    {
				        $namapt='DAPS'; 
				    }elseif ($cabang==32 )
				    {
				        $namapt='NATIONAL HOSPITAL - DAPS'; 
				        $namaig='nh.daps';
				    }
				      ?>  
				           
					<td ><h3 class="text-blue"><b><?= $namapt ?></b></h3></td>
					<td ><span>IG : <?= $namaig ?> WA : <?= $nohp ?></td> 
			
				 	<td align="right "><h3 class="header-report text-blue"><b>Invoice Penjualan</b></h3>	</td>
				</tr>
				<tr>
					<td colspan=3><?= $alamat ?></td> 
				</tr>				
			</tbody>
		</table> 
	 
 
	<div class="line"></div>
	<div class="left">
		<table class="table" >
			<tbody>
				<tr>
					<td width="30%"><?= $idpasien ?></td>
					<td width="20%">No Transaksi</td>
					<td width="20%"><?= $nomor ?></td>
					<td align="right" width="30%">TOTAL TRANSAKSI</td>
				</tr>
				<tr>
					<td rowspan=2 ><?= $kontak ?></td>
					<td >Tanggal</td>
					<td ><?= $tanggal ?></td>
					<td align="right" class="px-0" rowspan=2 ><?= eFormatNumber($total,0) ?></td>
				</tr>
				<tr>
					<td >Kasir</td>
					<td ><?= $karyawan ?></td> 				
				</tr> 
			</tbody>
		</table>
	 							
	</div>	
 
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-none"> 			
				<th align="center" width="30%">Item</th>				
				<th align="center" width="10%" >Ref-IC-Dokt-Perawat</th>	 
				<th align="center" width="10%">Harga</th>
				<th align="center" width="5%">Dis %</th>
				<th align="center" width="10%">Dis Rp</th>
				<th align="center" width="5%">Qty</th>
				<th align="center" width="10%">Sub Total</th>								
			</tr>
		</thead>
		<tbody>
			<?
				$i = 1;
				$qty = 0; $namapaket =''; $namapromo=''; $sumnilaipaket=0; $ke=0; $kodepaket=''; $ket='';
			    foreach ($detil->data as $row) {
			        
			       
    			  
    			  // jika dari paket
    			  // dan jika cetak header saja, maka detailnya tidak ditampilkan
    			  
    			  
    			    if ($row->kodepaket!='' and $row->cetakheadersaja=='1' ) {  
        			     if ($namapaket=='') {     
            	            $sumnilaipaket+=$row->subtotal ; 
        			     }
        			     elseif ($namapaketlengkap==$row->kodepaketlengkap)
        			     {
        			        $sumnilaipaket+=$row->subtotal ;  
        			     } 
        			     else 
        			     {
        			        $sumnilaipaket+=0;
        			     }
    			    }
    			    else
    			     // jika tidak dari paket langsung cetak
    			    {
    			    ?>
    				<tr> 
    					<td class="px-1 py-0"><?= $row->item ?></td>
    					<td class="px-1 py-0"><?= $row->ket ?></td>
    					<td class="right px-1 py-0"><?= eFormatNumber($row->harga,0) ?></td>
    					<td class="right px-1 py-0"><?= eFormatNumber($row->dis1,0) ?></td>
    					<td class="right px-1 py-0"><?= eFormatNumber($row->diskon,0) ?></td>
    					<td class="right px-1 py-0"><?= eFormatNumber($row->qty,2) ?></td>
    					<td class="right px-0 py-0"><?= eFormatNumber($row->subtotal,0) ?></td> 
    				</tr>
    			    <?  
    			    
    			    
                			     $namapaketlengkap='';
                			     $namapaket='';
    			        
    			    }
    			    
    			    
    			     // jika dari paket
    			        // dan jika cetak header saja, maka headernya ditampilkan
    			  
    			     if ($row->kodepaket!='') {
    			          if ($namapaket!='') {
    			            if ($row->kodepaketlengkap <>   $namapaketlengkap  ) { 
                			    ?>
                				<tr> 
                					<td class="px-1 py-0">Paket <?= $namapaket ?></td> 
    					            <td class="px-1 py-0"><?= $ket ?></td>
    					            <td class="px-1 py-0" colspan=4><?= $ketpaket ?></td> 
                					<td class="right px-0 py-0"><?= eFormatNumber($sumnilaipaket,0) ?></td> 
                				</tr>
                			    <? 
                			     $sumnilaipaket=0;
                			     $namapaketlengkap='';
                			     $namapaket='';
        			        } 
    			          } 
    			          
    			          
    			       }
    			        
    			        
    		    $namapaket=$row->kodepaket; 
    		    $namapaketlengkap=$row->kodepaketlengkap;
    		    $ket=$row->ket;   
    		    $ketpaket=$row->ketpaket;   
				$i++;
				$qty += $row->qty;
				}
				
				
				   
    			          if ($namapaket!=''  ) { 
                			    ?>
                				<tr> 
                					<td class="px-1 py-0">Paket<?= $namapaket ?></td> 
    					            <td class="px-1 py-0"><?= $ket ?></td>
    					            <td class="px-1 py-0" colspan=4><?= $ketpaket ?></td> 
                					<td class="right px-0 py-0"><?= eFormatNumber($sumnilaipaket,0) ?></td> 
                				</tr>
                			    <? 
                			     $sumnilaipaket=0;
        			        }  
    			        
    			        
			?>
		</tbody>
		<tfoot>  
		</tfoot>
	</table>
    <div class="line"></div>
		
	<table class="table" >
		<tbody> 
        			<tr>
        				<td width="15%" class="px-1 py-0">Cash</td> 
        				<td width="15%"  class="right px-1 py-0"> <?= eFormatNumber($kasjumlah,0) ?></td>	
        				
        				<td width="15%"  class="px-1 py-0">Kredit <?= $kreditbank ?></td> 
        				<td width="15%"  class="right px-1 py-0"><?= eFormatNumber($kreditjumlah,0) ?></td>	
        				
        				<td width="20%"  class="px-0 py-0">Penjualan</td> 
        				<td width="20%"  class="right px-0 py-0"><?= eFormatNumber($total,0) ?></td>		
        				 	
        			</tr>   
        			<tr>
        				<td class="px-1 py-0">Debit <?= $debitbank ?></td> 
        				<td class="right px-1 py-0"> <?= eFormatNumber($debitjumlah,0) ?></td>	
        				
        				<td class="px-1 py-0">DP</td> 
        				<td class="right px-1 py-0"><?= eFormatNumber($dpjumlah,0) ?></td>	
        				
        				<td class="px-1 py-0">Jumlah Bayar</td> 
        				<td class="right px-0 py-0"><?= eFormatNumber($totalbayar,0) ?></td>		
        				 	
        			</tr>      
        			<tr>
        				<td class="px-1 py-0">Pend DP</td> 
        				<td class="right px-1 py-0"> <?= eFormatNumber($pendapatandp,0) ?></td>	
        				
        				<td class="px-1 py-0">Transfer <?= $transferbank ?></td> 
        				<td class="right px-1 py-0"><?= eFormatNumber($transferjumlahh,0) ?></td>	
        				
        				<td class="px-1 py-0">Kembali</td> 
        				<td class="right px-0 py-0"><?= eFormatNumber($totalsisa,0) ?></td>		
        				 	
        			</tr>       
        			<tr>
        				<td class="px-1 py-0">Piutang</td> 
        				<td class="right px-1 py-3"> <?= eFormatNumber($piutangjumlah,0) ?></td>	
        				
        				<td class="px-1 py-0">Merchant <?= $merchantjenis ?></td> 
        				<td class="right px-1 py-3"><?= eFormatNumber($merchantjumlah,0) ?></td> 
        				 	
        			</tr>   
        			
        			<?
        			
        			    if ($kreditjumlah<>0)
        			    { ?> 
        			        	<tr>
                    				<td class="px-1 py-1"  colspan=2>No Kartu Kredit <?= $kreditno ?> </td>  
                    				<td class="px-1 py-1"  colspan=2>Jenis Kartu Kredit <?= $kreditjenis ?></td>  
                    			</tr>  
        			     <?    }
        			
        			    if ($debitjumlah<>0)
        			    { ?> 
        			        	<tr>
                    				<td class="px-1 py-1" colspan=2>No Kartu Debit <?= $debitno ?></td>  
                    				<td class="px-1 py-1"  colspan=2>Jenis Kartu Debit <?= $debitjenis ?></td> 	 
                    			</tr>  
        			     <?    }  ?>
        			
        				       
        				        
        				       <tr>
                    				<td colspan=6 align="center"  class="px-1 py-3">Sudah termasuk jasa dokter dan ppn 11% atas produk. 
                    				        <? if($point!=0)
        				                    { ?>  Jumlah Point <?= $point ?> 
        				                    <? }  ?>
        				            </td>  
                    			</tr>
        				        <tr>
                    				<td colspan=6 align="center"  class="px-1 py-1">Terima Kasih Atas Kunjungannya<br>Transaksi yang telah dilakukan, tidak dapat dibatalkan.<br> 
                    				<i>Care Love Smile</i></td>  
                    			</tr>
                    			
		</tbody>
	</table>							
</div>
