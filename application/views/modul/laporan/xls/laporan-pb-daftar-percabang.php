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




	                   $query = "SELECT  npnama, gkode, sunotransaksi, sutanggal,
                       sum(icogs*sdmasuk) as cogs


	                  from fstokd left join bitem on IID = SDITEM
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sucabang
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan on ctid=i2coapendapatan
					  left join bnamapt on npid=gpt
	                  WHERE (susumber = 'PBC')
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";

	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        }
                        if($idpt != ""){
                            	$query .= " AND gpt='".$idpt."'";
                            }



                        $query .= " GROUP by npnama, gkode, sunotransaksi, sutanggal	  ";


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
				<th class="left px-1">No Transaksi</th>
				<th class="left px-1">Tanggal</th>
				<th class="left px-1">Total Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?

				$jumlahdata=0;

				$nilaiperpt=0;$nilaipercabang=0;

				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {

    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiperpt,2)."</td>";
    					echo "</tr>";

						 $nilaiperpt=0;
    			   }
    			   if ($gudang !=  $row->gkode and $gudang !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$gudang."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaipercabang,2)."</td>";
    					echo "</tr>";

						 $nilaipercabang=0;
    			   }

				    if ($namapt !=  $row->npnama  ) {

    			       	echo "<tr >";
    					echo "<td class='px-1' >".$row->npnama."</td>";
    					echo "</tr>";
    			   }

    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >";
    					echo "<td class='px-2' >".$row->gkode."</td>";
    					echo "</tr>";
    			   }

    			    $gudang = $row->gkode ;
    			    $namapt = $row->npnama ;

    					echo "<tr>";
    					echo "<td class='px-3'>".$row->sunotransaksi."</td>";
    					echo "<td class='px-3'>".$row->sutanggal."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";

    					$nilaiperpt += $row->cogs ;
    					$nilaipercabang += $row->cogs ;

    					$jumlahdata ++;




				}




 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$gudang."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaipercabang,2)."</td>";
    					echo "</tr>";

         			$nilaipercabang=0;


 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiperpt,2)."</td>";
    					echo "</tr>";

         				$nilaiperpt=0;



			?>
		</tbody>
		<tfoot>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>


<pagebreak />


<?php




	                   $query = "SELECT  npnama, gkode,
                       sum(icogs*sdmasuk) as cogs


	                  from fstokd left join bitem on IID = SDITEM
                      left join fstoku on suid=sdidsu
	                  left join bgudang on gid=sucabang
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan on ctid=i2coapendapatan
					  left join bnamapt on npid=gpt
	                  WHERE (susumber = 'PBC')
	                  AND sutanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";

	                   if($idgudang != ""){
                        	$query .= " AND sucabang='".$idgudang."'";
                        }
                        if($idpt != ""){
                            	$query .= " AND gpt='".$idpt."'";
                            }



                        $query .= " GROUP by npnama, gkode	  ";


    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);





?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>
	<h3>Rekap PB Per Cabang</h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">Cabang</th>
				<th class="left px-1">Total Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?

				$jumlahdata=0;

				$nilaiperpt=0;$nilaipercabang=0;

				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {

    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' >Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiperpt,2)."</td>";
    					echo "</tr>";

						 $nilaiperpt=0;
    			   }


				    if ($namapt !=  $row->npnama  ) {

    			       	echo "<tr >";
    					echo "<td class='px-1' >".$row->npnama."</td>";
    					echo "</tr>";
    			   }



    			    $gudang = $row->gkode ;
    			    $namapt = $row->npnama ;

    					echo "<tr>";
    					echo "<td class='px-3'>".$row->gkode."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";

    					$nilaiperpt += $row->cogs ;
    					$nilaipercabang += $row->cogs ;

    					$jumlahdata ++;




				}






 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1'  >Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiperpt,2)."</td>";
    					echo "</tr>";

         				$nilaiperpt=0;



			?>
		</tbody>
		<tfoot>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>




<?php



	                   $query = "SELECT  npnama, gkode, ipunotransaksi, iputanggal,
                       sum(ipdharga*ipdkeluar) as cogs


	                  from einvoicepenjualand left join bitem on IID = IPDITEM
                      left join einvoicepenjualanu on ipuid=ipdidipu
                      left join fstoku on suid=IPDSUID
	                  left join bgudang on gid=SUGUDANGTUJUAN
	                  left join bitem2 on i2iditem=iid
	                  left join bcoatipe_pendapatan on ctid=i2coapendapatan
					  left join bnamapt on npid=gpt
	                  WHERE (ipusumber = 'IV')
	                  AND iputanggal BETWEEN '".tgl_database($date1)."'  AND '".tgl_database($date2)."'   ";

	                   if($idgudang != ""){
                        	$query .= " AND SUGUDANGTUJUAN='".$idgudang."'";
                        }
                        if($idpt != ""){
                            	$query .= " AND gpt='".$idpt."'";
                            }



                        $query .= " GROUP by npnama, gkode, ipunotransaksi, iputanggal	  ";


    $datareport = $CI->M_transaksi->get_data_query($query);
    $datareport = json_decode($datareport);





?>
<div class="header-report">
	<h4 class="text-blue"><?= $company_name; ?></h4>
	<h3>Daftar IV Terhadap Cabang</h3>
	<span>Periode : <?= $date1; ?> s/d <?= $date2; ?></span>
</div>
<div class="content-report">
	<table class="table">
		<thead>
			<tr class="bg-dark">
				<th class="left px-1">No Transaksi</th>
				<th class="left px-1">Tanggal</th>
				<th class="left px-1">Total Nilai</th>
			</tr>
		</thead>
		<tbody>
			<?

				$jumlahdata=0;

				$nilaiperpt=0;$nilaipercabang=0;

				$tanggal = ''; $gudang = ''; $namapt='';

				foreach ($datareport->data as $row) {

    			   if ($namapt !=  $row->npnama and $namapt !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiperpt,2)."</td>";
    					echo "</tr>";

						 $nilaiperpt=0;
    			   }
    			   if ($gudang !=  $row->gkode and $gudang !='') {

 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$gudang."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaipercabang,2)."</td>";
    					echo "</tr>";

						 $nilaipercabang=0;
    			   }

				    if ($namapt !=  $row->npnama  ) {

    			       	echo "<tr >";
    					echo "<td class='px-1' >".$row->npnama."</td>";
    					echo "</tr>";
    			   }

    			   if ($gudang !=  $row->gkode ) {
    			       	echo "<tr >";
    					echo "<td class='px-2' >".$row->gkode."</td>";
    					echo "</tr>";
    			   }

    			    $gudang = $row->gkode ;
    			    $namapt = $row->npnama ;

    					echo "<tr>";
    					echo "<td class='px-3'>".$row->ipunotransaksi."</td>";
    					echo "<td class='px-3'>".$row->iputanggal."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($row->cogs,2)."</td>";
    					echo "</tr>";

    					$nilaiperpt += $row->cogs ;
    					$nilaipercabang += $row->cogs ;

    					$jumlahdata ++;




				}




 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$gudang."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaipercabang,2)."</td>";
    					echo "</tr>";

         			$nilaipercabang=0;


 						echo "<tr class='bg-dark'>";
    					echo "<td class='px-1' colspan=2>Total ".$namapt."</td>";
    					echo "<td class='right px-3'>".eFormatNumber($nilaiperpt,2)."</td>";
    					echo "</tr>";

         				$nilaiperpt=0;



			?>
		</tbody>
		<tfoot>



		</tfoot>
	</table>
	<div class="clear">&nbsp;</div>



</div>
