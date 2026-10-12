<?php
include "style.php";
$date1 = $_POST["tgldari"];
$date2 = $_POST["tglsampai"];
if (isset($_POST["gudang"])) {
	$idgudang = $_POST["gudang"];
} else {
	$idgudang = "";
}
if (isset($_POST["namapt"])) {
	$idpt = $_POST["namapt"];
} else {
	$idpt = "";
}
$tampilNol = $_POST["saldo"];

$CI = &get_instance();

$query =
	" SELECT  GURUTAN,GKODE,CTID,CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK,IKODE,INAMA,notransaksi,namapasien,
				  sum(subtotal) as subtotal,
	              sum(ifnull(alkeshpp,0)*1.05) as alkeshpp,
				  sum(
				  case when icoa2021=1 then subtotal/1.11 else
				  (cogs*1.05) end
				  ) as nilaiproduk,
				  sum(cogs) as totalcogs,
				  sum(ifnull(alkeshpp,0)) as totalcogsalkes

				    from (

	                   SELECT  GURUTAN,GKODE,CTID,CTNAMA,IKODE,INAMA,CTTIDAKDITARIK,CTTIPEPRODUK,SDKELUAR,ICOA2021,sunotransaksi 'notransaksi',
			                kp.knama 'namapasien',
		                ( 	CASE WHEN ICOA2021=12 THEN
						(sdkeluar*(sdharga-sddiskon)-sdbayardp)/sutotaltransaksi*
						(sutotalkartudebit+sutotalkartukredit+sutotaltransfer+(sutotalkas-sutotalsisa)+sutotaldp+
						(sumerchantjumlah-(sumerchantjumlah*coalesce(mcbiaya,0)/100)))
						ELSE
			            sdkeluar*(sdharga-sddiskon)/sutotaltransaksi*
						(sutotalkartudebit+sutotalkartukredit+sutotaltransfer+(sutotalkas-sutotalsisa)+sutotaldp+
						(sumerchantjumlah-(sumerchantjumlah*coalesce(mcbiaya,0)/100)))
						END   ) as subtotal,

	                   sdbeli as cogs,

	                  (

						 select
									SUM(  sdbeli )
												from
												fstokd aa inner join bitem ab on ab.iid=aa.sditem left join bitem2 ac on ac.i2iditem=ab.iid
												WHERE aa.SDIDUALKES=a.sdid

	                  ) as alkeshpp

	                  FROM fstokd a LEFT JOIN fstoku ON SUID=SDIDSU
	                  LEFT JOIN bitem ON IID=SDITEM
	                  left join bgudang on gid=sucabang
	                  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan on ctid=i2coapendapatan
	                  left join bmerchant on mckode=sumerchantjenis
	                  left join bkontak kp on kp.kid=sukontak
	                  WHERE coalesce(sdkepalaalkes,0) =0 and SUSTATUS<>9 and SUSUMBER = 'IP'
	                  AND sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'
					  ";

if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
}

if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= ") a GROUP by
                    	  GURUTAN,GKODE,CTID,CTNAMA,CTTIDAKDITARIK,CTTIPEPRODUK,IKODE,INAMA,notransaksi,namapasien
                    	  ORDER BY GURUTAN,CTID,IKODE,notransaksi
                    	  ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);

// Detail alkes per produk/transaksi (kode, nama, qty, cogs) - sumber yg sama dgn
// subquery alkeshpp di atas, tapi di-detail-kan per item alkes, bukan di-SUM.
$queryAlkes =
	" SELECT  a2.notransaksi, a2.ikode 'produkkode',
			  ab.ikode 'alkeskode', ab.inama 'alkesnama',
			  aa.sdkeluar 'alkesqty', aa.sdbeli 'alkescogs'
	     FROM fstokd aa
	    INNER JOIN bitem ab ON ab.iid = aa.sditem
	    INNER JOIN (
	         SELECT pa.sdid, pfu.sunotransaksi 'notransaksi', pb.ikode
	           FROM fstokd pa
	           LEFT JOIN fstoku pfu ON pfu.suid=pa.sdidsu
	           LEFT JOIN bitem pb ON pb.iid=pa.sditem
	           left join bgudang pg on pg.gid=pfu.sucabang
	           left join bnamapt pn on pn.npid=pg.gpt
	          WHERE coalesce(pa.sdkepalaalkes,0)=0 and pfu.sustatus<>9 and pfu.susumber='IP'
	            AND pfu.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'";

if ($idgudang != "") {
	$queryAlkes .= " AND pg.gid='" . $idgudang . "'";
}

if ($idpt != "") {
	$queryAlkes .= " AND pn.npid='" . $idpt . "'";
}

$queryAlkes .= "  ) a2 ON a2.sdid = aa.SDIDUALKES
	    ORDER BY a2.notransaksi, a2.ikode";

$dataAlkes = $CI->M_transaksi->get_data_query($queryAlkes);
$dataAlkes = json_decode($dataAlkes);

$alkesDetail = array();
foreach ($dataAlkes->data as $al) {
	$key = $al->notransaksi . '|' . $al->produkkode;
	if (!isset($alkesDetail[$key])) { $alkesDetail[$key] = array(); }
	$alkesDetail[$key][] = $al;
}
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3><?= $title ?></h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis</th>
				<th class="left px-1">Produk/Tindakan</th>
				<th class="left px-1">No IP</th>
				<th class="left px-1">Nama Pasien</th>
				<th class="left px-1">Penjualan</th>
				<th class="left px-1">Produk</th>
				<th class="left px-1">Alkes</th>
				<th class="left px-1">PPn 11%</th>
				<th class="left px-1">Pendapatan Klinik</th>
				<th class="left px-1">Cogs Produk</th>
				<th class="left px-1">Cogs Alkes</th>
				<th class="left px-1">Kode Alkes</th>
				<th class="left px-1">Nama Alkes</th>
				<th class="left px-1">Qty Alkes</th>
				<th class="left px-1">COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0; $totalcogs=0; $totalcogsalkes =0 ;
				$nilaiproduk = 0 ; $subtotal = 0 ; $nilaialkes = 0 ; $ppn=0; $pendapatanklinik=0; $pendapatandokter=0; $alkeshpp=0;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ; $tnilaialkes = 0 ; $tppn=0;  $tpendapatanklinik=0; $tpendapatandokter=0; $tcogs=0; $tcogsalkes=0;

				$subtotalcoabaru = 0; $nilaiprodukcoabaru=0; $alkeshppcoabaru=0;  $ppncoabaru=0; $pendapatanklinikcoabaru=0;
				$totalcogscoabaru = 0; $totalcogsalkescoabaru = 0;
				$coabaru=''; $jumlahdatacobaru=0;

				foreach ($datareport->data as $row) {

				    $subtotal=$row->subtotal;
    			    $tnilaisales += $subtotal ;

				    if ($row->CTTIDAKDITARIK==0) {
    				    $alkeshpp=$row->alkeshpp;
    				    $nilaiproduk=$row->nilaiproduk;
    				    $totalcogs=$row->totalcogs;
    				    $totalcogsalkes=$row->totalcogsalkes;
    					$ppn = ($nilaiproduk+$alkeshpp)*11/100;
				    }
				    	$pendapatanklinik = ($subtotal-$nilaiproduk-$alkeshpp-$ppn)  ;
    					$pendapatandokter = 0 ;

         			 if ($row->CTNAMA!=$coabaru && $coabaru!=''   ) {
             			 if ($jumlahdatacobaru>1) {


            				echo "<tr>";
        					echo "<td class='left px-1' colspan=5 style='border-top:1px solid #000; border-bottom:1px solid #000;'>Jumlah ".$coabaru."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($subtotalcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($nilaiprodukcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($alkeshppcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($ppncoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($pendapatanklinikcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($totalcogscoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($totalcogsalkescoabaru,2)."</td>";
        					echo "<td class='left px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td><td class='left px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td><td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td><td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>";
        					echo "</tr>";
         				 	$subtotalcoabaru = 0; $nilaiprodukcoabaru=0; $alkeshppcoabaru=0;  $ppncoabaru=0; $pendapatanklinikcoabaru=0;
         				 	$totalcogscoabaru = 0; $totalcogsalkescoabaru = 0;

                 			}
                    		 $jumlahdatacobaru=0;
             			}


    					$alkesKey = $row->notransaksi.'|'.$row->IKODE;
    					$alkesList = !empty($alkesDetail[$alkesKey]) ? $alkesDetail[$alkesKey] : array();

    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->GKODE."</td>";
    					echo "<td class='left px-1'>".$row->CTNAMA."</td>";
    					echo "<td class='left px-1'>".$row->INAMA."</td>";
    					echo "<td class='left px-1'>".$row->notransaksi."</td>";
    					echo "<td class='left px-1'>".$row->namapasien."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($subtotal,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiproduk,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($alkeshpp,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($ppn,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pendapatanklinik,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($totalcogs,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($totalcogsalkes,2)."</td>";
    					if (!empty($alkesList)) {
    						$al0 = $alkesList[0];
    						echo "<td class='left px-1'>".$al0->alkeskode."</td>";
    						echo "<td class='left px-1'>".$al0->alkesnama."</td>";
    						echo "<td class='right px-1'>".eFormatNumber($al0->alkesqty,2)."</td>";
    						echo "<td class='right px-1'>".eFormatNumber($al0->alkescogs,2)."</td>";
    					} else {
    						echo "<td class='left px-1'></td><td class='left px-1'></td><td class='right px-1'></td><td class='right px-1'></td>";
    					}
    					echo "</tr>";

    					for ($ia = 1; $ia < count($alkesList); $ia++) {
    						$al = $alkesList[$ia];
    						echo "<tr>";
    						echo "<td class='left px-1'></td><td class='left px-1'></td><td class='left px-1'></td><td class='left px-1'></td><td class='left px-1'></td>";
    						echo "<td class='right px-1'></td><td class='right px-1'></td><td class='right px-1'></td><td class='right px-1'></td><td class='right px-1'></td><td class='right px-1'></td><td class='right px-1'></td>";
    						echo "<td class='left px-1'>".$al->alkeskode."</td>";
    						echo "<td class='left px-1'>".$al->alkesnama."</td>";
    						echo "<td class='right px-1'>".eFormatNumber($al->alkesqty,2)."</td>";
    						echo "<td class='right px-1'>".eFormatNumber($al->alkescogs,2)."</td>";
    						echo "</tr>";
    					}

    					$tnilaiproduk += $nilaiproduk;
    					$tnilaialkes += $alkeshpp ;
    					$tppn += $ppn ;
    					$tpendapatanklinik += $pendapatanklinik;
    					$tpendapatandokter += $pendapatandokter;
						$tcogs+= $totalcogs;
						$tcogsalkes+= $totalcogsalkes;
						$coabaru=$row->CTNAMA;
						$subtotalcoabaru+=$row->subtotal;
						$nilaiprodukcoabaru+=$nilaiproduk;
						$alkeshppcoabaru+=$alkeshpp;
						$ppncoabaru+=$ppn;
						$pendapatanklinikcoabaru+=$pendapatanklinik;
						$totalcogscoabaru+=$totalcogs;
						$totalcogsalkescoabaru+=$totalcogsalkes;
						$jumlahdatacobaru++;


				$nilaiproduk = 0 ; $subtotal = 0 ; $nilaialkes = 0 ; $ppn=0; $pendapatanklinik=0; $pendapatandokter=0; $alkeshpp=0;
				$totalcogs=0; $totalcogsalkes=0;


				}
					if ($jumlahdatacobaru>1) {
						echo "<tr class='bg-dark'>";
        					echo "<td class='left px-1' colspan=5 style='border-top:1px solid #000; border-bottom:1px solid #000;'>Jumlah ".$coabaru."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($subtotalcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($nilaiprodukcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($alkeshppcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($ppncoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($pendapatanklinikcoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($totalcogscoabaru,2)."</td>";
        					echo "<td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'>".eFormatNumber($totalcogsalkescoabaru,2)."</td>";
        					echo "<td class='left px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td><td class='left px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td><td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td><td class='right px-1' style='border-top:1px solid #000; border-bottom:1px solid #000;'></td>";
        					echo "</tr>";
					}

			?>
		</tbody>
		<tfoot>

			<tr>
				<td class="px-1" colspan=5>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaisales, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tnilaialkes, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tppn, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tpendapatanklinik, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tcogs, 2) ?></td>
				<td class="right px-1"><?= eFormatNumber($tcogsalkes, 2) ?></td>
				<td class="left px-1"></td>
				<td class="left px-1"></td>
				<td class="right px-1"></td>
				<td class="right px-1"></td>
			</tr>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>
</div>




<?php
$query =
	"  SELECT gkode,sum(sutotaltransaksi) 'totaltransaksi',
                            sum(sutotalkartudebit) 'debit',
                            sum(sutotalkartukredit) 'kredit',
                            sum(sutotaltransfer) 'transfer',
                            sum(sumerchantjumlah-(sumerchantjumlah*coalesce(mcbiaya,0)/100))  'merchant',
                            sum(sumerchantjumlah*coalesce(mcbiaya,0)/100)  'merchantbiaya',
                            sum(sutotalkas-sutotalsisa) 'kas',
                            sum(sutotaldp) 'dp',
                            sum(supendapatandp) 'pendapatandp',
                            sum(sunilaipiutang) 'nilaipiutang',
                            sum(susurgerydppembayaran) 'surgerydppembayaran'
	                  FROM  fstoku
	                  left join bgudang on gid=sucabang
	                  left join bnamapt on npid=gpt
	                  left join bmerchant on mckode=sumerchantjenis
	                  WHERE  SUSTATUS<>9 and SUSUMBER = 'IP'
	                  AND sutanggal BETWEEN '" .
	tgl_database($date1) .
	"'  AND '" .
	tgl_database($date2) .
	"'

					  ";

if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
}

if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " group by gkode  ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>Rekapan Pendapatan Per Jenis Bayar</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table" style="width:50%;">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Debit</th>
				<th class="left px-1">Kredit</th>
				<th class="left px-1">Transfer</th>
				<th class="left px-1">Kas</th>
				<th class="left px-1">Merchant</th>
				<th class="left px-1">Total Pembayaran</th>
				<th class="left px-1">Tarik DP</th>
				<th class="left px-1">Total Dengan DP</th>
			</tr>
		</thead>
		<tbody>
			<?

				foreach ($datareport->data as $row) {

					$totalbayar=0;
					$totalbayartanpadp=$row->debit+$row->kredit+$row->transfer+$row->kas+$row->merchant;
					$totalbayar=$row->debit+$row->kredit+$row->transfer+$row->kas+$row->merchant+$row->dp;

	 				echo "<tr>";
   					echo "<td class='left px-1'>".$row->gkode."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->debit,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->kredit,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->transfer,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->kas,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->merchant,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($totalbayartanpadp,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($row->dp,2)."</td>";
					echo "<td class='right px-1'>".eFormatNumber($totalbayar,2)."</td>";
					echo "</tr>";

										echo "<tr>";
										echo "<td class='left px-1'>".$row->gkode."</td>";
   					echo "<td class='left px-1' colspan=7>Piutang</td>";
        			echo "<td class='right px-1'>".eFormatNumber($row->nilaipiutang,2)."</td>";
           			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
   					echo "<td class='left px-1' colspan=7>DP Surgery</td>";
        			echo "<td class='right px-1'>".eFormatNumber($row->surgerydppembayaran,2)."</td>";
           			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
                 					echo "<td class='left px-1' colspan=7>Biaya Merchant</td>";
                      			echo "<td class='right px-1'>".eFormatNumber($row->merchantbiaya,2)."</td>";
                         			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
                 					echo "<td class='left px-1' colspan=7>Total Transaksi</td>";
                      			echo "<td class='right px-1'>".eFormatNumber($row->totaltransaksi,2)."</td>";
                         			echo "</tr>";


					echo "<tr>";
					echo "<td class='left px-1'>".$row->gkode."</td>";
   					echo "<td class='left px-1' colspan=7>Selisih</td>";
        			echo "<td class='right px-1'>".eFormatNumber($totalbayar+$row->nilaipiutang+$row->surgerydppembayaran + $row->merchantbiaya - $row->totaltransaksi,2)."</td>";
           			echo "</tr>";
				}


			?>
		</tbody>
		<tfoot>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>
