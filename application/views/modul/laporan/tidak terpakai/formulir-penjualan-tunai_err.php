<?php
	include ('style.php');

     
?>
<div class="header-report">
	<div class="logo left">	
		<img src="assets/dist/img/logo.png" />
	</div>
 
	<div class="right"> 	
		<div class="right py-2">
			<table class="table">
				<tbody>
					<tr>
						<td align="center" class="border-1 bg-dark">Nomor</td>
						<td width="10"></td>
						<td align="center" class="border-1 bg-dark">Tanggal</td>					
					</tr>
					<tr>
						<td align="center" class="border-1"> </td>
						<td>&nbsp;</td>
						<td align="center" class="border-1"> </td>												
					</tr>				
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="content-report">
	<table class="table table-border">
		<thead>
			<tr class="bg-dark">
				<th align="center" width="7%">No</th>				
				<th align="center" width="13%">Kode</th>				
				<th align="center">Nama Barang</th>
				<th align="center" width="10%">Qty</th>
				<th align="center" width="21%">Harga</th>
				<th align="center" width="21%">Jumlah</th>								
			</tr>
		</thead>
		<tbody>
		 
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4" rowspan="4" class="left py-1 px-1" style="vertical-align: middle;">Terbilang :  </td>
				<th class="right px-1 py-1">Sub Total</th>
				<th class="right px-1 py-1"> </th>				
			</tr> 										
			<tr>
				<th class="right px-1 py-1">Dibayar</th>
				<th class="right px-1 py-1"> </th>				
			</tr>						
			<tr>
				<th class="right px-1 py-1">Kembalian</th>
				<th class="right px-1 py-1"> </th>				
			</tr>														
		</tfoot>
	</table>	
	<table class="table" style="margin-top: 20px; width: 20%">
		<tbody>
			<tr>
				<td>Love, Care & Smile</td>
			</tr>
			<tr>
				<td align="center" class="py-4 border-bottom-1"></td>
			</tr>
		</tbody>
	</table>							
</div>
