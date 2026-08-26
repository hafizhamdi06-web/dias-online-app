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

$totalnilaiawal = 0;

$query =
	"SELECT  npnama, gkode, ctnamalama,ctnamabaru, ikode, inama, iid, isatuan, icogs , qty, qty*icogs 'cogs' from
                      (
                      select
                      npnama, gkode, coalama.ctnama as ctnamalama, coabaru.ctnama as ctnamabaru, iid, isatuan, ikode, inama, icogs ,
                      IFNULL(SUM(SDMASUK-( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) ),0) as qty

	                  from fstokd left join bitem on IID = sditem
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sdgudang
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
	                  WHERE sustatus<>9 and  SDCANCEL=0
	                  AND sutanggal < '" .
	tgl_database($date1) .
	"'";

if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " GROUP by npnama,gkode,coalama.ctnama,coabaru.ctnama, ikode, inama, iid, isatuan, icogs
                         ) a

                        ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>


<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3>MODEL HPP v4</h3>
	<span>Periode : <?= $date1 ?> s/d <?= $date2 ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark" >
				<th class="left px-1" colspan=9>SALDO AWAL</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Nama PT</th>
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis Lama</th>
				<th class="left px-1">Jenis Baru</th>
				<th class="left px-1">Kode</th>
				<th class="left px-1">Nama</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">COGS</th>
				<th class="right px-1">Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;
				$jumlahdata=0;
				$nilaiprodukperpt=0; $nilaisalesperpt=0;
				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {
    			    $namapt = $row->npnama ;
    					echo "<tr>";
    					echo "<td class='px-1'>".$row->npnama."</td>";
    					echo "<td class='px-1'>".$row->gkode."</td>";
    					echo "<td class='px-1'>".$row->ctnamalama."</td>";
    					echo "<td class='px-1'>".$row->ctnamabaru."</td>";
    					echo "<td class='px-1'>".$row->ikode."</td>";
    					echo "<td class='px-1'>".$row->inama."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->icogs,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";
    					$tnilaiproduk += $row->cogs ;
    					$jumlahdata ++;
				}
				$totalnilaiawal=$tnilaiproduk ;
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" colspan=8>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
			</tr>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>

<?
$query =
	"SELECT sunotransaksi 'nopbc', sutanggal 'tanggalpbc', ikode 'kodeitem', inama 'namaitem', sdmasuk 'qty',
                     icogs 'cogs',   icogs * sdmasuk 'subtotal'

                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku ON suid = sdidsu
                    LEFT JOIN bgudang      on gid=sucabang
                    LEFT JOIN bnamapt      on npid=gpt

                    WHERE susumber = 'PBCX'
                        AND sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " ORDER BY sunotransaksi,sutanggal,sdurutan ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark" >
				<th class="left px-1" colspan=10>PEMBELIAN KE VENDOR BERDASARKAN PENERIMAAN BARANG CABANG</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">No PBC</th>
				<th class="left px-1">Tanggal PBC</th>
				<th class="left px-1">Kode Item</th>
				<th class="left px-1">Nama Item</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Harga Beli Clinic</th>
				<th class="left px-1">Sub Total</th>
			</tr>
		</thead>
		<tbody>
			<?

                        $pembelian_total=0;

				foreach ($datareport->data as $row) {

					$sales=0;

    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->nopbc."</td>";
    					echo "<td class='left px-1'>".$row->tanggalpbc."</td>";
    					echo "<td class='left px-1'>".$row->kodeitem."</td>";
    					echo "<td class='left px-1'>".$row->namaitem."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->cogs,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "</tr>";

                        $pembelian_total+=$row->subtotal;


				}

				echo "<tr>";
    					echo "<td class='left px-1' colspan=6>Total Pembelian</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";




			?>
		</tbody>
		<tfoot>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>


<?php
$query =
	"SELECT a.sunotransaksi 'nopbc', a.sutanggal 'tanggalpbc', ikode 'kodeitem', inama 'namaitem', sdmasuk 'qty',
                     icogs 'cogs',   icogs * sdmasuk 'subtotal' , b.sunotransaksi 'nokmb'

                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN bgudang      on gid=a.sucabang
                    LEFT JOIN bnamapt      on npid=gpt
                    LEFT JOIN fstoku b ON b.suid = a.SUPRUID

                    WHERE a.susumber = 'TMBX'
                        AND a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " ORDER BY a.sunotransaksi,a.sutanggal,sdurutan ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark" >
				<th class="left px-1" colspan=10>PEMBELIAN ANTAR CABANG</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">No TMB</th>
				<th class="left px-1">Tanggal PBC</th>
				<th class="left px-1">Kode Item</th>
				<th class="left px-1">Nama Item</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">Harga Beli Clinic</th>
				<th class="left px-1">Sub Total</th>
				<th class="left px-1">No KMB</th>
			</tr>
		</thead>
		<tbody>
			<?

                        $pembelian_total=0;

				foreach ($datareport->data as $row) {

					$sales=0;

    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->nopbc."</td>";
    					echo "<td class='left px-1'>".$row->tanggalpbc."</td>";
    					echo "<td class='left px-1'>".$row->kodeitem."</td>";
    					echo "<td class='left px-1'>".$row->namaitem."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->cogs,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->subtotal,0)."</td>";
    					echo "<td class='left px-1'>".$row->nokmb."</td>";
    					echo "</tr>";

                        $pembelian_total+=$row->subtotal;


				}

				echo "<tr>";
    					echo "<td class='left px-1'>Total Pembelian</td>";
    					echo "<td class='right px-1'>".eFormatNumber($pembelian_total,0)."</td>";
    					echo "</tr>";




			?>
		</tbody>
		<tfoot>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>



<?

$query =
	"SELECT  npnama, gkode, ctnamalama,ctnamabaru, ikode, inama, iid, isatuan, icogs , qty, qty*icogs 'cogs' from
                      (
                      select
                      npnama, gkode, coalama.ctnama as ctnamalama, coabaru.ctnama as ctnamabaru, iid, isatuan, ikode, inama, icogs ,
                      IFNULL(SUM(SDMASUK-( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) ),0) as qty

	                  from fstokd left join bitem on IID = sditem
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sdgudang
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
	                  WHERE sustatus<>9 and  SDCANCEL=0
	                  AND sutanggal <= '" .tgl_database($date2) ."'";

						if ($idgudang != "") {
							$query .= " AND sucabang='" . $idgudang . "'";
						}
						if ($idpt != "") {
							$query .= " AND gpt='" . $idpt . "'";
						}

						$query .= " GROUP by npnama,gkode,coalama.ctnama,coabaru.ctnama, ikode, inama, iid, isatuan, icogs
                         ) a

                        ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>


<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Nama PT</th>
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Jenis Lama</th>
				<th class="left px-1">Jenis Baru</th>
				<th class="left px-1">Kode</th>
				<th class="left px-1">Nama</th>
				<th class="left px-1">Qty</th>
				<th class="left px-1">COGS</th>
				<th class="right px-1">Total COGS</th>
			</tr>
		</thead>
		<tbody>
			<?
				$jumlahdatang = 0; $nilai = 0; $qty = 0;
				$nilaiproduk = 0 ; $subtotal = 0 ;
				$tnilaiproduk = 0 ; $tnilaisales = 0 ;
				$jumlahdata=0;
				$nilaiprodukperpt=0; $nilaisalesperpt=0;
				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {
    			    $namapt = $row->npnama ;
    					echo "<tr>";
    					echo "<td class='px-1'>".$row->npnama."</td>";
    					echo "<td class='px-1'>".$row->gkode."</td>";
    					echo "<td class='px-1'>".$row->ctnamalama."</td>";
    					echo "<td class='px-1'>".$row->ctnamabaru."</td>";
    					echo "<td class='px-1'>".$row->ikode."</td>";
    					echo "<td class='px-1'>".$row->inama."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->qty,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->icogs,2)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";
    					$tnilaiproduk += $row->cogs ;
    					$jumlahdata ++;
				}
			?>
		</tbody>
		<tfoot>
			<tr>
				<td class="px-1" colspan=8>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
			</tr>

		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>




<?php
$query =
	" 	select sum(nilaimasuk) 'nilaimasuk', sum(nilaikeluar) 'nilaikeluar', jenis
	from (
	SELECT  case when a.susumber = 'IP' then 'POS'
				when a.susumber = 'SJ' then 'Penjualan'
				when a.susumber = 'KMB' and gkmb.gpt<>b.gpt then 'Kirim Mutasi Barang Beda PT'
				when a.susumber = 'KMB' and gkmb.gpt=b.gpt then 'Kirim Mutasi Barang Sama PT'
				when a.susumber = 'TMB' and gtmb.gpt<>b.gpt then 'Terima Mutasi Barang Beda PT'
				when a.susumber = 'TMB' and gtmb.gpt=b.gpt then 'Terima Mutasi Barang Sama PT'
				when a.susumber = 'PY' then 'Penyesuaian Stok'
				when a.susumber = 'AL'  then 'Alkes'
				when a.susumber = 'PBC' then 'Pembelian'
				else 'xLain' end as jenis,


                     icogs 'cogs',   icogs * sdmasuk 'nilaimasuk'  ,
                     icogs*( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) 'nilaikeluar'

                    FROM fstokd
                    LEFT JOIN bitem    ON iid = sditem
                    LEFT JOIN fstoku a ON a.suid = sdidsu
                    LEFT JOIN fstoku tmb ON tmb.suid = a.SUPRUID
                    LEFT JOIN bgudang b      on b.gid=a.sucabang
                    LEFT JOIN bgudang gtmb   on gtmb.gid=tmb.sucabang
                    LEFT JOIN bgudang gkmb   on gkmb.gid=a.SUGUDANGTUJUAN
                    LEFT JOIN bnamapt      on npid=b.gpt

                    WHERE a.sustatus<>9 and SDCANCEL=0 and
                    a.sutanggal BETWEEN '" .
	tgl_database($date1) .
	"' AND '" .
	tgl_database($date2) .
	"' ";

if ($idgudang != "") {
	$query .= " AND a.sucabang='" . $idgudang . "'";
}
if ($idpt != "") {
	$query .= " AND b.gpt='" . $idpt . "'";
}

$query .= ") aa GROUP BY jenis ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>

<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark" >
				<th class="left px-1" colspan=3>Mutasi Stok</th>
			</tr>
			<tr class="bg-dark">
				<th class="left px-1">Jenis</th>
				<th class="left px-1">Nilai Masuk</th>
				<th class="left px-1">Nilai Keluar</th>
				<th class="left px-1">Update Nilai Stok</th>
			</tr>
		</thead>
		<tbody>
			<?

                        $masuk=0;$keluar=0;	$totalnilaiakhir=0;
                        	 ;

				foreach ($datareport->data as $row) {


    					echo "<tr>";
    					echo "<td class='left px-1'>".$row->jenis."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaikeluar,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($row->nilaimasuk-$row->nilaikeluar,0)."</td>";
    					echo "</tr>";

                        $masuk+=$row->nilaimasuk;
                        $keluar+=$row->nilaikeluar;

				}

				echo "<tr>";
    					echo "<td class='left px-1'>Total</td>";
    					echo "<td class='right px-1'>".eFormatNumber($masuk,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($keluar,0)."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($masuk-$keluar,0)."</td>";
    					echo "</tr>";

         	$totalnilaiakhir=$totalnilaiawal+$masuk-$keluar;


				echo "<tr>";
                       					echo "<td class='left px-1'>Total Nilai Awal</td>";
                       					echo "<td class='right px-1'></td>";
                       					echo "<td class='right px-1'></td>";
                       					echo "<td class='right px-1'>".eFormatNumber($totalnilaiawal,0)."</td>";
                       					echo "</tr>";

				echo "<tr>";
             					echo "<td class='left px-1'>Total Nilai Akhir</td>";
             					echo "<td class='right px-1'></td>";
             					echo "<td class='right px-1'></td>";
             					echo "<td class='right px-1'>".eFormatNumber($totalnilaiakhir,0)."</td>";
             					echo "</tr>";




			?>
		</tbody>
		<tfoot>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>

</div>
