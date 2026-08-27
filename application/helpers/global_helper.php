<?php

// kolom NID pada tabel anomor
define ('NID', array(
  'Saldo_Awal_Akun'         => 722,
  'Saldo_Awal_Persediaan'   => 717,
  'Saldo_Awal_Tagihan'      => 723,    
	'Fina_Kas_Masuk'			    => 687,
	'Fina_Kas_Keluar'			    => 688,
	'Fina_Bank_Masuk'			    => 689,
	'Fina_Bank_Keluar'			  => 690,
	'Fina_Jurnal_Umum'			  => 691,
  'Fina_Aktiva_Saldo_Awal'  => 692,  				
  'Fina_Aktiva_Pembelian'   => 719,            
  'Fina_Aktiva_Penyusutan'  => 693,              
	'PB_Order_Pembelian'		  => 695,
	'PB_Terima_Barang'			  => 696,	
	'PB_Faktur_Pembelian'		  => 715,
	'PB_Keluar_Barang'			  => 702,
	'PB_Retur_Pembelian'		  => 708,
	'PB_Pembayaran_Hutang'		=> 710,
	'PB_Uang_Muka_Pembelian'	=> 720,
	'PJ_Order_Penjualan'		  => 698,	
	'PJ_Surat_Jalan'			    => 718,
	'PJ_Faktur_Penjualan'		  => 700,
	'PJ_Terima_Retur'			    => 709,
	'PJ_Retur_Penjualan'		  => 701,
	'PJ_Pembayaran_Piutang'		=> 703,
	'PJ_Uang_Muka_Penjualan'	=> 721,
  'PJ_Penjualan_Tunai'      => 724,  
	'STK_Mutasi_Barang'			  => 705,
	'STK_Stok_Opname'			    => 707,
	'STK_Penyesuaian_Barang'	=> 706 	,
	'PJ_Editdepo'	=> 727,
	'PB_Permintaan_Barang' => 728
));

define("DEFAULT_CURRENCY_SYMBOL", "");
define("DEFAULT_MON_DECIMAL_POINT", ",");
define("DEFAULT_MON_THOUSANDS_SEP", ".");
define("DEFAULT_POSITIVE_SIGN", "");
define("DEFAULT_NEGATIVE_SIGN", "-");
define("DEFAULT_FRAC_DIGITS", 2);
define("DEFAULT_P_CS_PRECEDES", TRUE);
define("DEFAULT_P_SEP_BY_SPACE", FALSE);
define("DEFAULT_N_CS_PRECEDES", TRUE);
define("DEFAULT_N_SEP_BY_SPACE", FALSE);
define("DEFAULT_P_SIGN_POSN", 3);
define("DEFAULT_N_SIGN_POSN", 3);

//date_default_timezone_set('Asia/Jakarta'); //

function _anomor($tipe){
    $CI =& get_instance();  
    $CI->db->where('nid', $tipe);
    $hasil = $CI->db->get('aanomor');

  foreach ($hasil->result() as $row) {
    $tabelinfo = array(
      'namatabel' => $row->NTABEL,
      'sumber' => $row->NKODE,
      'kolomid' => $row->NFLDID,
      'kolomnomor' => $row->NFLDNOTRANSAKSI,
      'isjurnal' => $row->NFA,
      'keterangan' => $row->NKETERANGAN
    );
    return $tabelinfo;        
  }
}

function _ainfo($id){
    $CI =& get_instance();  
    $sql = "SELECT A.*, B.pnilai 'ppnbeli', C.pnilai 'pph22beli', D.pnilai 'ppnjual', E.pnilai 'pph22jual'
              FROM aainfo A 
         LEFT JOIN bpajak B ON A.ippnbeli=B.pid 
         LEFT JOIN bpajak C ON A.ipph22beli=C.pid 
         LEFT JOIN bpajak D ON A.ippnjual=D.pid 
         LEFT JOIN bpajak E ON A.ipph22jual=E.pid          
             WHERE A.iid=".$id;
    $hasil = $CI->db->query($sql);

  foreach ($hasil->result() as $row) {
    $ainfo = array(
      'inama' => $row->inama,
      'ialamat1' => $row->ialamat1,
      'ipajakbeli' => $row->ipajakbeli,
      'ippnbeli' => $row->ppnbeli,
      'ipph22beli' => $row->pph22beli,
      'ipajakjual' => $row->ipajakjual,
      'ippnjual' => $row->ppnjual,
      'ipph22jual' => $row->pph22jual,
      'idivisi' => $row->idivisi,
      'iproyek' => $row->iproyek,
      'isatuan' => $row->isatuan,                                          
      'imatauang' => $row->imatauang,
      'idecimalqty' => $row->idecimalqty      
    );
    return $ainfo;        
  }
}



function _getdatakaryawan($id){
    $CI =& get_instance(); 
    $sql = "SELECT kid,knama,kkode  
              FROM bkontak WHERE kid='".$id."'";
 
    $hasil = $CI->db->query($sql);
    return $hasil;
}

function _getuserinfo2($id){
    $CI =& get_instance(); 
    $sql = "SELECT kid,kkode,knama,gkode,gid from auser left join bkontak on kid=ukid left join bgudang on gid=ucabang 
               WHERE uid='".$id."'";
 
    $hasil = $CI->db->query($sql);
    return $hasil;
}


function _amenux($link){
    $CI =& get_instance();  
    $sql = "SELECT A.* 
              FROM aamenu A    
             WHERE A.mlink='".$link."'";
    $hasil = $CI->db->query($sql);

  foreach ($hasil->result() as $row) {
    $amenu = array(
      'MCAPTION1' => $row->MCAPTION1      
    );
    return $amenu;        
  }
}

function _amenu($link,$userid){
    $CI =& get_instance();  
    $sql = "SELECT coalesce(auadd,0) 'bisatambah', coalesce(auedit,0) 'bisaedit', coalesce(auprint,0) 'bisaprint', coalesce(audell,0) 'bisahapus', coalesce(auapprove,0) 'bisapprove' , aamenu.*
                    from aausermenu left join aamenu on auidmenu=mid
                    WHERE auiduser ='".$userid."' and mlink='".$link."'";
     
    $hasil = $CI->db->query($sql);

  foreach ($hasil->result() as $row) {
    $amenu = array(
      'MCAPTION1' => $row->MCAPTION1 , 
      'MTYPE' => $row->MTYPE , 
      'bisatambah' => $row->bisatambah  , 
      'bisaedit' => $row->bisaedit  , 
      'bisaprint' => $row->bisaprint    , 
      'bisahapus' => $row->bisahapus   , 
      'bisapprove' => $row->bisapprove    
    );
    return $amenu;        
  }
}

function _pesanError($txt){
   $callback = array(    
        'pesan' => $txt,    
        'data'=> null
   );

	return json_encode($callback);
}

function eFormatNumber($amount, $NumDigitsAfterDecimal, $IncludeLeadingDigit = -2, $UseParensForNegativeNumbers = -2, $GroupDigits = -2) {
  if (!is_numeric($amount))
    return $amount;

  extract(localeconv());

  if (empty($currency_symbol)) $currency_symbol = DEFAULT_CURRENCY_SYMBOL;
  if (empty($mon_decimal_point)) $mon_decimal_point = DEFAULT_MON_DECIMAL_POINT;
  if (empty($mon_thousands_sep)) $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
  if (empty($positive_sign)) $positive_sign = DEFAULT_POSITIVE_SIGN;
  if (empty($negative_sign)) $negative_sign = DEFAULT_NEGATIVE_SIGN;
  if (empty($frac_digits) || $frac_digits == CHAR_MAX) $frac_digits = DEFAULT_FRAC_DIGITS;
  if (empty($p_cs_precedes) || $p_cs_precedes == CHAR_MAX) $p_cs_precedes = DEFAULT_P_CS_PRECEDES;
  if (empty($p_sep_by_space) || $p_sep_by_space == CHAR_MAX) $p_sep_by_space = DEFAULT_P_SEP_BY_SPACE;
  if (empty($n_cs_precedes) || $n_cs_precedes == CHAR_MAX) $n_cs_precedes = DEFAULT_N_CS_PRECEDES;
  if (empty($n_sep_by_space) || $n_sep_by_space == CHAR_MAX) $n_sep_by_space = DEFAULT_N_SEP_BY_SPACE;
  if (empty($p_sign_posn) || $p_sign_posn == CHAR_MAX) $p_sign_posn = DEFAULT_P_SIGN_POSN;
  if (empty($n_sign_posn) || $n_sign_posn == CHAR_MAX) $n_sign_posn = DEFAULT_N_SIGN_POSN;

  if ($NumDigitsAfterDecimal > -1)
    $frac_digits = $NumDigitsAfterDecimal;

  if ($UseParensForNegativeNumbers == -1) {
    $n_sign_posn = 0;
    if ($p_sign_posn == 0) {
      if (DEFAULT_P_SIGN_POSN != 0)
        $p_sign_posn = DEFAULT_P_SIGN_POSN;
      else
        $p_sign_posn = 3;
    }
  } elseif ($UseParensForNegativeNumbers == 0) {
    if ($n_sign_posn == 0)
      if (DEFAULT_P_SIGN_POSN != 0)
        $n_sign_posn = DEFAULT_P_SIGN_POSN;
      else
        $n_sign_posn = 3;
  }

  if ($GroupDigits == -1) {
    $mon_thousands_sep = DEFAULT_MON_THOUSANDS_SEP;
  } elseif ($GroupDigits == 0) {
    $mon_thousands_sep = "";
  }

  $number = number_format(abs($amount),
              $frac_digits,
              $mon_decimal_point,
              $mon_thousands_sep);

  if ($IncludeLeadingDigit == 0) {
    if (substr($number, 0, 2) == "0.")
      $number = substr($number, 1, strlen($number)-1);
  }
  if ($amount < 0) {
    $sign = $negative_sign;
    $key = $n_sign_posn;
  } else {
    $sign = $positive_sign;
    $key = $p_sign_posn;
  }
  $formats = array(
    '0' => '(%s)',
    '1' => $sign . '%s',
    '2' => $sign . '%s',
    '3' => $sign . '%s',
    '4' => $sign . '%s');

  return sprintf($formats[$key], $number);
}

function terbilang($x) {
  if($x<0) {
    $hasil="minus " . trim(kekata($x));
  } else {
    $poin = trim(tkoma($x));
    $hasil  = trim(kekata($x));   
  }
  if($poin) {
    $hasil  = ucwords($hasil) . ' Koma ' . ucwords($poin);
  } else {
    $hasil  = ucwords($hasil);
  } 
  return $hasil . " Rupiah";
}

function kekata($x) {
  $x=abs($x);
  $angka=array("","satu","dua","tiga","empat","lima","enam","tujuh","delapan","sembilan","sepuluh","sebelas");
  $temp=" ";
  
  if($x<12) {
    $temp=" " . $angka[$x];
  } else if ($x<20) {
    $temp=kekata($x-10) ." belas";
  } else if ($x<20) {
    $temp=kekata($x-10) ." belas";
  } else if ($x<100) {
    $temp=kekata($x/10) ." puluh" . kekata($x%10);
  } else if ($x<200) {
    $temp=" seratus" . kekata($x-100);
    } else if ($x <1000) {
        $temp = kekata($x/100) . " ratus" . kekata($x % 100);
    } else if ($x <2000) {
        $temp = " seribu" . kekata($x - 1000);
    } else if ($x <1000000) {
        $temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
    } else if ($x <1000000000) {
        $temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
    } else if ($x <1000000000000) {
        $temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
    } else if ($x <1000000000000000) {
        $temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
    }      
    return $temp;
}

function tkoma($x) {
  $x = stristr($x,'.'); 

  $angka = array("nol", "satu", "dua", "tiga", "empat", "lima",
  "enam", "tujuh", "delapan", "sembilan"); 

  $temp = " ";
  $pjg = strlen($x); 
  $pos = 1;

  while ($pos <$pjg) {
    $char=substr($x,$pos,1);
    $pos++;
    $temp .= " " . $angka[$char];
  }
  return $temp;
}