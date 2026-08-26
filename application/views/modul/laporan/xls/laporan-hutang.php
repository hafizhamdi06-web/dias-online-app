<?php
	include ('style.php');
	$date = $_POST['tgl'];
	$tampilNol =  $_POST['saldo'];	
    if(isset($_POST['idkontak'])){
    	$kontak = $_POST['idkontak'];
    } else {
    	$kontak = "";
    }	

    $CI =& get_instance();
    $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));    
    $transcode_faktur = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID));        
    $transcode_retur = $this->M_transaksi->prefixtrans(element('PB_Retur_Pembelian',NID));            
    $transcode_dp = $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID));            
    $query  = "SELECT A.kid,A.knama,B.ktnama, 
				         (SELECT IFNULL(SUM(AA.iputotaltransaksi)-SUM(AA.ipujumlahdp)-
				                 (SELECT IFNULL(SUM(PIA.piijmlbayar),0) 
				                    FROM epembayaraninvoicei PIA 
				              INNER JOIN epembayaraninvoiceu PUA ON PIA.piiidu=PUA.piuid AND PUA.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIA.piitipe=1 AND PIA.piiidinvoice=AA.ipuid)
				                 ,0)  
						   FROM einvoicepenjualanu AA WHERE AA.iputanggal <= '".tgl_database($date)."' 
							AND AA.ipukontak=A.kid AND AA.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."')) 'saldoinv',
				         (SELECT IFNULL(SUM(AB.dpjumlah)-
				                 (SELECT IFNULL(SUM(PIB.piijmlbayar),0) 
				                    FROM epembayaraninvoicei PIB 
				              INNER JOIN epembayaraninvoiceu PUB ON PIB.piiidu=PUB.piuid AND PUB.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIB.piitipe=0 AND PIB.piiiddp=AB.dpid)                        
				                 ,0)
				           FROM ddp AB WHERE AB.dptanggal <= '".tgl_database($date)."'
				            AND AB.dpkontak=A.kid AND AB.dpsumber='".$transcode_dp."' AND AB.dpckas=0) 'saldodp',
				         (SELECT IFNULL(SUM(AC.iputotaltransaksi)-
				                 (SELECT IFNULL(SUM(PIR.pirjmlbayar),0) 
				                    FROM epembayaraninvoicer PIR 
				              INNER JOIN epembayaraninvoiceu PUC ON PIR.piridu=PUC.piuid AND PUC.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIR.piridreturpembelian=AC.ipuid)
				                 ,0)  
						   FROM einvoicepenjualanu AC WHERE AC.iputanggal <= '".tgl_database($date)."' 
							AND AC.ipukontak=A.kid AND AC.ipusumber='".$transcode_retur."') 'saldoretur'				            
				  FROM bkontak A INNER JOIN bkontaktipe B ON A.ktipe=B.ktid AND A.ktipe=6";

    if($kontak != ""){
    	$query .= " WHERE A.kid='".$kontak."'";
    }

    $query .= "  ORDER BY A.kid ASC";            

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
				<th colspan="5" class="center"><span>Per Tanggal : <?= $date; ?></span></th>				
			</tr>						
		</thead>
	</table>
</div>
<div class="content-report">
	<?
		foreach ($datareport->data as $row) {
			$saldo = $row->saldoinv+$row->saldodp-$row->saldoretur;
			if($saldo == 0 && $tampilNol == 0){
				continue;
			}			

			// Detil Transaksi Tagihan Outstanding			
			$query = "SELECT * FROM(
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal', C.nketerangan 'sumber',
                   		  DATE_FORMAT(DATE_ADD(A.iputanggal, INTERVAL B.ttempo DAY),'%d-%m-%Y') 'tempo',
						  (A.iputotaltransaksi-A.ipujumlahdp-(SELECT IFNULL(SUM(PIA.piijmlbayar),0) 
				                    FROM epembayaraninvoicei PIA 
				              INNER JOIN epembayaraninvoiceu PUA ON PIA.piiidu=PUA.piuid AND PUA.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIA.piitipe=1 AND PIA.piiidinvoice=A.ipuid)) 'hutang'                           
                     FROM einvoicepenjualanu A LEFT JOIN btermin B ON A.iputermin=B.tid LEFT JOIN anomor C ON A.ipusumber=C.nkode 
                    WHERE A.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."') AND A.ipukontak='".$row->kid."' 
                      AND A.iputanggal <= '".tgl_database($date)."'
                      AND (A.iputotaltransaksi-A.ipujumlahdp-(SELECT IFNULL(SUM(PIA.piijmlbayar),0) 
				                    FROM epembayaraninvoicei PIA 
				              INNER JOIN epembayaraninvoiceu PUA ON PIA.piiidu=PUA.piuid AND PUA.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIA.piitipe=1 AND PIA.piiidinvoice=A.ipuid))>0 
                    UNION 
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal', C.nketerangan 'sumber',
                   		  DATE_FORMAT(DATE_ADD(A.iputanggal, INTERVAL B.ttempo DAY),'%d-%m-%Y') 'tempo',
						  (A.iputotaltransaksi-(SELECT IFNULL(SUM(PIA.pirjmlbayar),0) 
				                    FROM epembayaraninvoicer PIA 
				              INNER JOIN epembayaraninvoiceu PUA ON PIA.piridu=PUA.piuid AND PUA.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIA.piridreturpembelian=A.ipuid))*-1 'hutang'                           
                     FROM einvoicepenjualanu A LEFT JOIN btermin B ON A.iputermin=B.tid LEFT JOIN anomor C ON A.ipusumber=C.nkode 
                    WHERE A.ipusumber='".$transcode_retur."' AND A.ipukontak='".$row->kid."' 
                      AND A.iputanggal <= '".tgl_database($date)."'
                      AND (A.iputotaltransaksi-(SELECT IFNULL(SUM(PIA.pirjmlbayar),0) 
				                    FROM epembayaraninvoicer PIA 
				              INNER JOIN epembayaraninvoiceu PUA ON PIA.piridu=PUA.piuid AND PUA.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIA.piridreturpembelian=A.ipuid))>0 
					UNION                    
                   SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal', C.nketerangan 'sumber',
                   		  DATE_FORMAT(DATE_ADD(A.dptanggal, INTERVAL B.ttempo DAY),'%d-%m-%Y') 'tempo',
						  (A.dpjumlah-
				                 (SELECT IFNULL(SUM(PIB.piijmlbayar),0) 
				                    FROM epembayaraninvoicei PIB 
				              INNER JOIN epembayaraninvoiceu PUB ON PIB.piiidu=PUB.piuid AND PUB.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIB.piitipe=0 AND PIB.piiiddp=A.dpid)) 'hutang'                  
                     FROM ddp A LEFT JOIN btermin B ON A.dptermin=B.tid LEFT JOIN anomor C ON A.dpsumber=C.nkode  
                    WHERE A.dpsumber='".$transcode_dp."' AND A.dpkontak='".$row->kid."' AND (A.dpjumlah-
				                 (SELECT IFNULL(SUM(PIB.piijmlbayar),0) 
				                    FROM epembayaraninvoicei PIB 
				              INNER JOIN epembayaraninvoiceu PUB ON PIB.piiidu=PUB.piuid AND PUB.piutanggal <= '".tgl_database($date)."' 
				                   WHERE PIB.piitipe=0 AND PIB.piiiddp=A.dpid)
                    )>0 AND A.dpckas=0 
                ) T ORDER BY T.tanggal ASC";
			$detilreport = $CI->M_transaksi->get_data_query($query);
			$detilreport = json_decode($detilreport);
	?>
				<table class="table">
					<thead>
						<tr class="border-0 bg-dark">
							<th colspan="5" class="left">
								<?= $row->ktnama; ?> : <?= $row->knama; ?>
							</th>
						</tr>
						<tr>
							<th class="left" width="11%">Tanggal</th>
							<th class="left" width="12%">Nomor</th>				
							<th class="left">Jenis/Sumber</th>
							<th class="left" width="30%">Jth Tempo</th>
							<th class="right" width="18%">Saldo</th>
						</tr>
					</thead>
					<tbody>
						<?
							$totalhutang = 0;							
							foreach ($detilreport->data as $detil) {
								echo "<tr>
											<td>$detil->tanggal</td>
											<td>$detil->nomor</td>
											<td>$detil->sumber</td>
											<td>$detil->tempo</td>											
											<td class=\"right\">".eFormatNumber($detil->hutang,2)."</td>						
									  </tr>";
								$totalhutang += $detil->hutang;									  
							}
						?>						
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">Total Hutang:</td>
							<td></td>
							<td></td>
							<td class="right"><?= eFormatNumber($totalhutang,2); ?></td>				
						</tr>
					</tfoot>
				</table>
				<div class="clear">&nbsp;</div>
	<?
		}
	?>
</div>