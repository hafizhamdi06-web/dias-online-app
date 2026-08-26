<?php
include "style.php";
$date = $_POST["tgl"];
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
	"SELECT  npnama, gkode, ctnamalama,ctnamabaru, ikode, inama, iid, isatuan, icogs , qty, qty*icogs 'cogs' from
                      (
                      select
                      npnama, gkode, coalama.ctnama as ctnamalama, coabaru.ctnama as ctnamabaru, iid, isatuan, ikode, inama, icogs ,
                      IFNULL(SUM(SDMASUK-( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN  > 0,0,sdkeluar) ) ),0) as qty

	                  from fstokd left join bitem on IID = sditem
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sucabang
                      left join bcoatipe_perpt coalama on coalama.ctid=icoa2021
                      left join bitemkelompok2020 on ik2id=ikelompok2020
					  left join bnamapt on npid=gpt
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan coabaru on coabaru.ctid=i2coapendapatan
	                  WHERE sustatus<>9 and  SDCANCEL=0
	                  AND sutanggal <= '" .
	tgl_database($date) .
	"'
                      ";

if ($idgudang != "") {
	$query .= " AND sucabang='" . $idgudang . "'";
}

if ($idpt != "") {
	$query .= " AND gpt='" . $idpt . "'";
}

$query .= " GROUP by npnama,gkode,coalama.ctnama,coabaru.ctnama, ikode, inama, iid, isatuan, icogs
                        having  IFNULL(SUM(SDMASUK-( IF( SDDARIPAKET<> 0 AND  SDKEDATANGAN   > 0,0,sdkeluar) ) ),0)>0 ) a

                        ";

$datareport = $CI->M_transaksi->get_data_query($query);
$datareport = json_decode($datareport);
?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name ?></h4>
	<h3><?= $title ?></h3>
	<span>Periode : <?= $date ?>  </span>
</div>
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


    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiprodukperpt,2)."</td>";
    					echo "</tr>";

						$nilaiprodukperpt=0; $nilaisalesperpt=0;
    			   }



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
    					$nilaiprodukperpt += $row->cogs ;

    					$jumlahdata ++;

				}

				echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=7>Total ".$namapt."</td>";
    					echo "<td class='right px-1'>".eFormatNumber($nilaiprodukperpt,2)."</td>";
    					echo "</tr>";


			?>
		</tbody>
		<tfoot>

			<tr>
				<td class="px-1" colspan=7>Total</td>
				<td class="right px-1"><?= eFormatNumber($tnilaiproduk, 2) ?></td>
			</tr>


		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>
