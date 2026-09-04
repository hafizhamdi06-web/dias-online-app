<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable_Transaksi_Full extends CI_Controller {

   function __construct() {
      parent::__construct();
      if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
      }
      $this->load->model('M_datatables');
      $this->load->model('M_transaksi');
   }

   // Cabang yang di-POST hanya sah bila termasuk pilihan cabang user
   // (auser.UCABANGPILIH) atau user punya akses semua cabang; kalau tidak,
   // fallback ke cabang aktif sesi.
   private function _cabangUser($cabang) {
      $cabang = !empty($cabang) ? $cabang : @$_SESSION['cabang'];
      if (!empty($this->session->allcabang) && $this->session->allcabang == 1) {
         return $cabang;
      }
      $row = $this->db->query("SELECT UCABANGPILIH FROM auser WHERE UID='".$this->session->id."'")->row();
      $allowed = ($row && $row->UCABANGPILIH !== null)
         ? array_filter(array_map('trim', explode(',', $row->UCABANGPILIH)))
         : array();
      if (!empty($allowed) && !in_array((string) $cabang, $allowed, true)) {
         return @$_SESSION['cabang'];
      }
      return $cabang;
   }

   function view_kas_masuk() {
        $transcode = element('Fina_Kas_Masuk',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv'
                     FROM ctransaksiu A
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.cukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.cusumber='".$transcode."'
                    AND A.cutanggal BETWEEN '".tgl_database(@$_POST['dari'])."'
                    AND '".tgl_database(@$_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_kas_keluar() {
        $transcode = element('Fina_Kas_Keluar',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv'
                     FROM ctransaksiu A
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.cukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.cusumber='".$transcode."'
                    AND A.cutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_bank_masuk() {
        $transcode = element('Fina_Bank_Masuk',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv'
                     FROM ctransaksiu A
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.cukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.cusumber='".$transcode."'
                    AND A.cutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_bank_keluar() {
        $transcode = element('Fina_Bank_Keluar',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv'
                     FROM ctransaksiu A
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.cukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.cusumber='".$transcode."'
                    AND A.cutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_jurnal_umum() {
        $transcode = element('Fina_Jurnal_Umum',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv'
                     FROM ctransaksiu A
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.cukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.cusumber='".$transcode."'
                    AND A.cutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_jurnal_transaksi() {
        $query  = "SELECT B.cdurutan 'id',C.cnama 'coa', ROUND(B.cddebit,2) 'debit', ROUND(B.cdkredit,2) 'kredit', B.cdcatatan 'catatan'
                     FROM ctransaksiu A
                LEFT JOIN ctransaksid B ON A.cuid=B.cdidu
                LEFT JOIN bcoa C ON B.cdnocoa=C.cid";
        $search = array('cnama');
        $where  = null;
        $isWhere = "A.cunotransaksi='".$_POST['nomor']."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_order_pembelian() {
        $transcode = element('PB_Order_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.souuraian 'uraian', ROUND(IFNULL(A.soutotaltransaksi,0),2) 'total',0 'totalv',
                          CASE WHEN SUM(IFNULL(C.sodmasuk,0))=0  THEN 'Belum Proses'
                               WHEN SUM(IFNULL(C.sodorder,0))-(SUM(IFNULL(C.sodmasuk,0))-SUM(IFNULL(C.sodkeluar,0)))=0 THEN 'Selesai'
                               ELSE 'Diproses Sebagian'
                          END 'status',
                          SUM(IFNULL(C.sodorder,0)) 'qtyorder',
                          SUM(IFNULL(C.sodmasuk,0)) 'qtyterima',
                          SUM(IFNULL(C.sodkeluar,0)) 'qtyretur'
                     FROM esalesorderu A
                LEFT JOIN bkontak B ON A.soukontak=B.kid
                LEFT JOIN esalesorderd C ON A.souid=C.sodidsou";
        $search = array('sounotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.soukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.sousumber='".$transcode."' AND A.soutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.souid,A.sounotransaksi,A.soutanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_permintaan_barang() {
        $transcode = element('PB_Permintaan_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.pbuid 'id',A.pbunotransaksi 'nomor',DATE_FORMAT(A.pbutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'karyawan', C.gnama 'gudang', A.pbuuraian 'uraian',
                          CASE A.pbustatus
                               WHEN 0 THEN 'Belum Verifikasi'
                               WHEN 1 THEN 'Pending'
                               WHEN 2 THEN 'Verifikasi Finance'
                               WHEN 3 THEN 'Perintah Kirim'
                               WHEN 4 THEN 'Sedang DiKirim'
                               WHEN 5 THEN 'Progress Diterima Cabang'
                               WHEN 6 THEN 'Selesai Diterima Cabang'
                               WHEN 7 THEN 'Konfirmasi Bag Pembelian'
                          END 'status'
                     FROM fpermintaanbarangu A
                LEFT JOIN bkontak B ON A.pbukontak=B.kid
                LEFT JOIN bgudang C ON A.pbugudang=C.gid";
        $search = array('pbunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.pbukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.pbusumber='".$transcode."' AND A.pbutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_verifikasi_permintaan_barang() {
        $transcode = element('PB_Permintaan_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.pbuid 'id',A.pbunotransaksi 'nomor',DATE_FORMAT(A.pbutanggal,'%d/%m/%Y') 'tanggal',
                          C.knama 'karyawan', A.pbuuraian 'keterangan', E.lnama 'tujuan',
                          CASE A.pbustatus
                               WHEN 0 THEN 'Belum Verifikasi'
                               WHEN 1 THEN 'Pending'
                               WHEN 2 THEN 'Verifikasi Finance'
                               WHEN 3 THEN 'Perintah Kirim'
                               WHEN 4 THEN 'Sedang DiKirim'
                               WHEN 5 THEN 'Progress Diterima Cabang'
                               WHEN 6 THEN 'Selesai Diterima Cabang'
                               WHEN 7 THEN 'Konfirmasi Bag Pembelian'
                          END 'status',
                          IFNULL(F.unama,'') 'userverifikasi',
                          CASE A.pbujenis WHEN 1 THEN 'Mutasi' WHEN 2 THEN 'Pembelian' ELSE '-' END 'jenis',
                          G.gnama 'gudangtujuan',
                          IFNULL(A.pbukonfirmasicatatan,'') 'catatanverifikasi'
                     FROM fpermintaanbarangu A
                LEFT JOIN bkontak C ON A.pbukaryawan=C.kid
                LEFT JOIN blain E ON A.pbutipepermintaan=E.lid
                LEFT JOIN auser F ON A.pbuapproveu=F.uid
                LEFT JOIN bgudang G ON A.pbugudangsumber=G.gid";
        $search = array('A.pbunotransaksi','C.knama');
        $where  = null;
        $isWhere = "A.pbusumber='".$transcode."'";

        if(!empty($_POST['dari']) && !empty($_POST['sampai'])){
          $isWhere .= " AND A.pbutanggal BETWEEN '".tgl_database($_POST['dari'])."' AND '".tgl_database($_POST['sampai'])."'";
        }

        if(!empty($_POST['idkontak'])){
          $isWhere .= " AND A.pbukaryawan='".$_POST['idkontak']."'";
        }

        if(!empty($_POST['cabang'])){
          $isWhere .= " AND A.pbugudang='".$_POST['cabang']."'";
        }

        if(!empty($_POST['tujuan'])){
          $isWhere .= " AND A.pbutipepermintaan='".$_POST['tujuan']."'";
        }

        if(isset($_POST['status']) && $_POST['status'] !== ''){
          $isWhere .= " AND A.pbustatus='".$_POST['status']."'";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_persetujuan() {
        $query  = "SELECT A.APID 'id', A.APJENIS 'jenis', A.APKETERANGAN 'keterangan',
                          COALESCE(B.unamalengkap,B.unama) 'pemohon',
                          DATE_FORMAT(A.APTGLMINTA,'%d-%m-%Y %H:%i') 'tanggal'
                     FROM aapersetujuan A
                LEFT JOIN auser B ON A.APIDUSERMINTA=B.uid";
        $search = array('A.APJENIS','A.APKETERANGAN','B.unama');
        $where  = null;
        $isWhere = "A.APIDUSERSETUJU='".$this->session->id."' AND A.APSTATUS=0";
        $isOrder = 'A.APID DESC';

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere,$isOrder);
    }

   function view_persetujuan_riwayat() {
        $query  = "SELECT A.APID 'id', A.APJENIS 'jenis', A.APREFERENSI 'referensi', A.APKETERANGAN 'keterangan',
                          COALESCE(B.unamalengkap,B.unama) 'pemohon',
                          COALESCE(C.unamalengkap,C.unama) 'approver',
                          CASE A.APSTATUS WHEN 0 THEN 'Pending' WHEN 1 THEN 'Disetujui' WHEN 2 THEN 'Ditolak' ELSE '-' END 'status',
                          DATE_FORMAT(A.APTGLMINTA,'%d-%m-%Y %H:%i') 'tglminta',
                          DATE_FORMAT(A.APTGLRESPON,'%d-%m-%Y %H:%i') 'tglrespon',
                          A.APCATATAN 'catatan'
                     FROM aapersetujuan A
                LEFT JOIN auser B ON A.APIDUSERMINTA=B.uid
                LEFT JOIN auser C ON A.APIDUSERSETUJU=C.uid";
        $search = array('A.APJENIS','A.APKETERANGAN','B.unama','C.unama');
        $where  = null;

        $isWhere = "1=1";

        $status = @$_POST['status'];
        if ($status !== '' && in_array($status, array('0','1','2'), true)) {
            $isWhere .= " AND A.APSTATUS='".$status."'";
        }

        $jenis = trim(@$_POST['jenis']);
        if ($jenis !== '') {
            $isWhere .= " AND A.APJENIS LIKE '%".$this->db->escape_like_str($jenis)."%'";
        }

        $referensi = trim(@$_POST['referensi']);
        if ($referensi !== '') {
            $isWhere .= " AND A.APREFERENSI LIKE '%".$this->db->escape_like_str($referensi)."%'";
        }

        $keterangan = trim(@$_POST['keterangan']);
        if ($keterangan !== '') {
            $isWhere .= " AND A.APKETERANGAN LIKE '%".$this->db->escape_like_str($keterangan)."%'";
        }

        $tgldari = trim(@$_POST['tgldari']);
        $tglsampai = trim(@$_POST['tglsampai']);
        if ($tgldari !== '' && $tglsampai !== '') {
            $isWhere .= " AND DATE(A.APTGLMINTA) BETWEEN '".tgl_database($tgldari)."' AND '".tgl_database($tglsampai)."'";
        }

        $isOrder = 'A.APID DESC';

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere,$isOrder);
    }

   function view_uang_muka_pembelian() {
        $transcode = element('PB_Uang_Muka_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.dpketerangan 'uraian', IFNULL(A.dpjumlah,0) 'total',0 'totalv',
                          CASE WHEN SUM(IFNULL(A.dpjumlahbayar,0))=0  THEN 'Belum Dibayar'
                               WHEN SUM(IFNULL(A.dpjumlah,0))-SUM(IFNULL(A.dpjumlahbayar,0))=0 THEN 'Lunas'
                               ELSE 'Dibayar Sebagian'
                          END 'status',
                          SUM(IFNULL(A.dppakaiiv,0)) 'dipakaifaktur'
                     FROM ddp A
                LEFT JOIN bkontak B ON A.dpkontak=B.kid";
        $search = array('dpnotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.dpkontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.dpsumber='".$transcode."' AND A.dptanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.dpid,A.dpnotransaksi,A.dptanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_uang_muka_penjualan() {
        $transcode = element('PJ_Uang_Muka_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.dpketerangan 'uraian', IFNULL(A.dpjumlah,0) 'total',0 'totalv',
                          CASE WHEN SUM(IFNULL(A.dpjumlahbayar,0))=0  THEN 'Belum Dibayar'
                               WHEN SUM(IFNULL(A.dpjumlah,0))-SUM(IFNULL(A.dpjumlahbayar,0))=0 THEN 'Lunas'
                               ELSE 'Dibayar Sebagian'
                          END 'status',
                          SUM(IFNULL(A.dppakaiiv,0)) 'dipakaifaktur'
                     FROM ddp A
                LEFT JOIN bkontak B ON A.dpkontak=B.kid";
        $search = array('dpnotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.dpkontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.dpsumber='".$transcode."' AND A.dptanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.dpid,A.dpnotransaksi,A.dptanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_penerimaan_barang() {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.suuraian 'uraian', ROUND(IFNULL(A.sutotaltransaksi,0),2) 'total', 0 'totalv',
                          CASE WHEN SUM(IFNULL(C.sdfaktur,0))=0  THEN 'Belum Difaktur'
                               WHEN SUM(IFNULL(C.sdfaktur,0))-(SUM(IFNULL(C.sdmasuk,0))-SUM(IFNULL(C.sdkeluar,0)))=0 THEN 'Selesai'
                               ELSE 'Difaktur Sebagian'
                          END 'status',
                          CASE WHEN A.suposting=0 THEN '-'
                               WHEN A.suposting=1 THEN 'Sudah Posting'
                               ELSE '-'
                          END 'posting',
                          ROUND(SUM(IFNULL(C.sdmasuk,0)),2) 'qty'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak=B.kid
                LEFT JOIN fstokd C ON A.suid=C.sdidsu";
        $search = array('sunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.sukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.susumber='".$transcode."' AND A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.suid, A.sunotransaksi, A.sutanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_faktur_pembelian() {
        $transcode = element('PB_Faktur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.ipuuraian 'uraian', IFNULL(A.iputotaltransaksi,0) 'total', 0 'totalv',
                          CASE WHEN A.iputotalbayar=0 AND (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0)) > 0  THEN 'Belum Dibayar'
                               WHEN (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))-A.iputotalbayar=0 OR (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))=0   THEN 'Lunas'
                               WHEN (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))-A.iputotalbayar<0 THEN 'Lebih Dibayar'
                               ELSE 'Dibayar Sebagian'
                          END 'status',
                          '-' 'posting', ROUND((IFNULL(A.iputotaltransaksi,0)-IFNULL(A.ipujumlahdp,0)),2) 'totaltagihan'
                     FROM einvoicepenjualanu A
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.ipukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1' AND A.iputanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_keluar_barang_pembelian() {
        $transcode = element('PB_Keluar_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.suuraian 'uraian', IFNULL(A.sutotaltransaksi,0) 'total', 0 'totalv',
                          CASE WHEN A.sustatus=0 THEN 'Menunggu'
                               WHEN A.sustatus=1 THEN 'Menunggu'
                               WHEN A.sustatus=3 THEN 'Sudah Retur'
                               ELSE 'Menunggu'
                          END 'status',
                          CASE WHEN A.suposting=0 THEN '-'
                               WHEN A.suposting=1 THEN 'Sudah Posting'
                               ELSE '-'
                          END 'posting',
                          SUM(IFNULL(C.sdkeluar,0)) 'qty'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak=B.kid
                LEFT JOIN fstokd C ON A.suid=C.sdidsu ";
        $search = array('sunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.sukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.susumber='".$transcode."' AND A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.suid, A.sunotransaksi, A.sutanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_retur_pembelian() {
        $transcode = element('PB_Retur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.ipuuraian 'uraian', ROUND(IFNULL(A.iputotaltransaksi,0),2) 'total', 0.00 'totalv'
                     FROM einvoicepenjualanu A
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.ipukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1' AND A.iputanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_pembayaran_hutang() {
        $transcode = element('PB_Pembayaran_Hutang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.piuuraian 'uraian', ROUND(IFNULL(A.piujmlkas,0),2) 'total', ROUND(IFNULL(A.piujmlkasv,0),2) 'totalv'
                     FROM epembayaraninvoiceu A
                LEFT JOIN bkontak B ON A.piukontak=B.kid";
        $search = array('piunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.piukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.piusumber='".$transcode."' AND A.piutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_order_penjualan() {
        $transcode = element('PJ_Order_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.souuraian 'uraian', ROUND(IFNULL(A.soutotaltransaksi,0),2) 'total', 0 'totalv',
                          CASE WHEN SUM(IFNULL(C.sodkeluar,0))=0  THEN 'Belum Proses'
                               WHEN SUM(IFNULL(C.sodorder,0))-SUM(IFNULL(C.sodkeluar,0))=0 THEN 'Selesai'
                               WHEN SUM(IFNULL(C.sodorder,0))-SUM(IFNULL(C.sodkeluar,0))<0 THEN 'Diproses Lebih'
                               ELSE 'Diproses Sebagian'
                          END 'status',
                          SUM(IFNULL(C.sodorder,0)) 'qtyorder',
                          SUM(IFNULL(C.sodmasuk,0)) 'qtyretur',
                          SUM(IFNULL(C.sodkeluar,0)) 'qtykeluar'
                     FROM esalesorderu A
                LEFT JOIN bkontak B ON A.soukontak=B.kid
                LEFT JOIN esalesorderd C ON A.souid=C.sodidsou";
        $search = array('sounotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.soukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.sousumber='".$transcode."' AND A.soutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.souid,A.sounotransaksi,A.soutanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
   }

   function view_surat_jalan() {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.suuraian 'uraian', IFNULL(A.sutotaltransaksi,0) 'total', 0 'totalv',
                          CASE WHEN SUM(IFNULL(C.sdfaktur,0))=0  THEN 'Belum Difaktur'
                               WHEN SUM(IFNULL(C.sdfaktur,0))-SUM(IFNULL(C.sdkeluar,0))=0 THEN 'Selesai'
                               ELSE 'Difaktur Sebagian'
                          END 'status',
                          CASE WHEN A.suposting=0 THEN '-'
                               WHEN A.suposting=1 THEN 'Sudah Posting'
                               ELSE '-'
                          END 'posting',
                          SUM(IFNULL(C.sdkeluar,0)) 'qty'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak=B.kid
                LEFT JOIN fstokd C ON A.suid=C.sdidsu";
        $search = array('sunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.sukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.susumber='".$transcode."' AND A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.suid, A.sunotransaksi, A.sutanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_penjualan_tunaix() {
        $transcode = element('PJ_Penjualan_Tunai',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.ipuuraian 'uraian', IFNULL(A.iputotaltransaksi,0) 'total', 0 'totalv',
                           '-' 'status',
                          '-' 'posting', ROUND((IFNULL(A.iputotaltransaksi,0) 'totaltagihan'
                     FROM einvoicepenjualanu A
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.ipukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1' AND A.iputanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_penjualan_tunai() {

        $cabang  = $this->_cabangUser(@$_POST['cabang']) ;
        $transcode = element('PJ_Penjualan_Tunai',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.suuraian 'uraian', IFNULL(A.sutotaltransaksi,0) 'total',
                          case A.sustatus when 9 then 'Cancel' else 'Aktif' end 'status'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;

        if(!empty($_POST['kontak'])){
          $isWhere = "AND B.knama like '%".$_POST['kontak']."%'";
        }else{
          $isWhere = "";
        }

        $isWhere2="";

        if(!empty($cabang)){
          $isWhere2 = "AND A.sucabang = '".$cabang."'";
        }
        $carabayar=$_POST['carabayar'];
         $bank=$_POST['bank'];
        if(!empty($carabayar)){
            if($carabayar=='DEBIT') {
                 $isWhere2 .= "AND SUTOTALKARTUDEBIT>0";
            }elseif($carabayar=='KREDIT') {
                 $isWhere2 .= "AND SUTOTALKARTUKREDIT>0";

            }elseif($carabayar=='TRANSFER') {
                 $isWhere2 .= "AND SUTOTALTRANSFER>0";

            }elseif($carabayar=='TUNAI') {
                 $isWhere2 .= "AND SUTOTALKAS>0";
            }elseif($carabayar=='MERCHANT') {
                 $isWhere2 .= "AND SUMERCHANTJUMLAH>0";
            }
        }

           if(!empty($bank)){
               $isWhere2 .= " AND (SUBANKTRANSFER='".$bank."' or SUBANKDEBIT='".$bank."' or SUBANKKREDIT='".$bank."' ) ";

           }





        $isWhere = "A.susumber='".$transcode."' AND  A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere  . $isWhere2;

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }


    function view_alkes() {

        $cabang  = !empty($_POST['cabang']) ? $_POST['cabang'] : @$_SESSION['cabang'] ;
        $transcode = element('PJ_Editdepo',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.sunoref 'noref', A.suuraian 'uraian', A.suidualkes 'idsupos'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama','sunoref');
        $where  = null;

        if(!empty($_POST['kontak'])){
          $isWhere = "AND B.knama like '%".$_POST['kontak']."%'";
        }else{
          $isWhere = "";
        }

        if(!empty($_POST['notransaksi'])){
          $isWhere .= " AND A.sunotransaksi like '%".$this->db->escape_like_str($_POST['notransaksi'])."%'";
        }

        if(!empty($_POST['noref'])){
          $isWhere .= " AND A.sunoref like '%".$this->db->escape_like_str($_POST['noref'])."%'";
        }

        $isWhere2="";

        if(!empty($cabang)){
          $isWhere2 = "AND A.sucabang = '".$cabang."'";
        }

        $isWhere = "A.susumber='".$transcode."' AND  A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere  . $isWhere2;

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }


    function view_persetujuankomisi() {

         $cabang  = @$_SESSION['cabang'] ;
         $query  = "SELECT PKID 'id', PKTAHUN 'tahun', PKBULAN 'bulan', PKCABANG 'idcabang', GKODE 'cabang',
         PKWAKTU 'waktu', PKUSER 'user', case when PKSTATUS=0 then 'Belum Di Approve' else 'Sudah' end 'status'
                      FROM cpersetujuankomisi left join bgudang on gid=pkcabang ";
         $search = array('PKTAHUN','PKBULAN');
         $where  = null;

         if(!empty($_POST['cabang'])){
           $isWhere = "AND PKCABANG = ".$_POST['cabang']." ";
         }else{
           $isWhere = "";
         }





         $isWhere = " PKTAHUN=2026 AND PKBULAN=7 " . $isWhere  ;

         header('Content-Type: application/json');
         echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
     }

   function view_faktur_penjualan() {
        $transcode = element('PJ_Faktur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.ipuuraian 'uraian', IFNULL(A.iputotaltransaksi,0) 'total', 0 'totalv',
                          CASE WHEN A.iputotalbayar=0 AND (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0)) > 0  THEN 'Belum Dibayar'
                               WHEN (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))-A.iputotalbayar=0 OR (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))=0   THEN 'Lunas'
                               WHEN (A.iputotaltransaksi-IFNULL(A.ipujumlahdp,0))-A.iputotalbayar<0 THEN 'Lebih Dibayar'
                               ELSE 'Dibayar Sebagian'
                          END 'status',
                          '-' 'posting', ROUND((IFNULL(A.iputotaltransaksi,0)-IFNULL(A.ipujumlahdp,0)),2) 'totaltagihan'
                     FROM einvoicepenjualanu A
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.ipukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1' AND A.iputanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_terima_retur() {
        $transcode = element('PJ_Terima_Retur',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.suuraian 'uraian', ROUND(IFNULL(A.sutotaltransaksi,0),2) 'total', 0 'totalv',
                          CASE WHEN A.sustatus=0 THEN 'Menunggu'
                               WHEN A.sustatus=1 THEN 'Menunggu'
                               WHEN A.sustatus=3 THEN 'Sudah Retur'
                               ELSE 'Menunggu'
                          END 'status',
                          CASE WHEN A.suposting=0 THEN '-'
                               WHEN A.suposting=1 THEN 'Sudah Posting'
                               ELSE '-'
                          END 'posting',
                          SUM(IFNULL(C.sdmasuk,0)) 'qty'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak=B.kid
                LEFT JOIN fstokd C ON A.suid=C.sdidsu ";
        $search = array('sunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.sukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.susumber='".$transcode."' AND A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.suid, A.sunotransaksi, A.sutanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_retur_penjualan() {
        $transcode = element('PJ_Retur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.ipuuraian 'uraian', ROUND(IFNULL(A.iputotaltransaksi,0),2) 'total', 0 'totalv'
                     FROM einvoicepenjualanu A
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;
         if(!empty($_POST['kontak'])){
          $isWhere = "AND A.ipukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1' AND A.iputanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_pembayaran_piutang() {
        $transcode = element('PJ_Pembayaran_Piutang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.piuuraian 'uraian', ROUND(IFNULL(A.piujmlkas,0),2) 'total', IFNULL(A.piujmlkasv,0) 'totalv'
                     FROM epembayaraninvoiceu A
                LEFT JOIN bkontak B ON A.piukontak=B.kid";
        $search = array('piunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.piukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.piusumber='".$transcode."' AND A.piutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_mutasi_barang() {
        $transcode = element('STK_Mutasi_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          A.suuraian 'uraian',
                          B.gnama 'gudangasal',
                          C.gnama 'gudangtujuan'
                     FROM fstoku A
                LEFT JOIN bgudang B ON A.sugudangasal=B.gid
                LEFT JOIN bgudang C ON A.sugudangtujuan=C.gid ";
        $search = array('sunotransaksi');
        $where  = null;
        $isWhere = "A.susumber='".$transcode."' AND A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' ";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_stok_opname() {
        $transcode = element('STK_Stok_Opname',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.souuraian 'uraian',
                          CASE WHEN A.soustatus=0 THEN 'Menunggu'
                               WHEN A.soustatus=1 THEN 'Menunggu'
                               WHEN A.soustatus=3 THEN 'Sudah Proses'
                               ELSE 'Menunggu'
                          END 'status',
                          SUM(IFNULL(C.sodqty,0)) 'qty'
                     FROM fstokopnameu A
                LEFT JOIN bkontak B ON A.soukontak=B.kid
                LEFT JOIN fstokopnamed C ON A.souid=C.sodidsou";
        $search = array('sounotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.soukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.sousumber='".$transcode."' AND A.soutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere . " GROUP BY A.souid, A.sounotransaksi, A.soutanggal";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_penyesuaian_barang() {
        $transcode = element('STK_Penyesuaian_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak', A.suuraian 'uraian', A.sunoref 'noref',
                          CASE WHEN A.suposting=0 THEN 'Posting'
                               WHEN A.suposting=1 THEN '-'
                               ELSE '-'
                          END 'posting'
                     FROM fstoku A
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;
        if(!empty($_POST['kontak'])){
          $isWhere = "AND A.sukontak = '".$_POST['kontak']."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.susumber='".$transcode."' AND A.sutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_posting_trans() {
        $transcode = $_POST['jenis'];
        $transcode = $this->M_transaksi->prefixtrans($transcode);
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv'
                     FROM ctransaksiu A
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;
        if(!empty($transcode)){
          $isWhere = "AND A.cusumber='".$transcode."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.cutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' AND A.custatus='".$_POST['tipe']."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_penomoran() {
        $query  = "SELECT A.nid 'id',A.nkode 'kode',A.nketerangan 'keterangan',
                          A.ntabel 'tabel',A.nfa 'fa',A.nfldid 'nid',A.nfldtanggal 'tanggal',
                          A.nfldsumber 'sumber',A.nfldnotransaksi 'nomor',A.nflduraian 'uraian',
                          A.nfldtotaltrans 'total',A.nfldkontak 'kontak', B.mnama 'menu'
                     FROM aanomor A LEFT JOIN aamenu B ON A.nfmenu=B.mid";
        $search = array('nkode','nketerangan');
        $where  = null;
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_transaksi_settings() {
        $transcode = $_POST['jenis'];
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.cuuraian 'uraian', ROUND(A.cutotaltrans,2) 'total', ROUND(A.cutotaltransv,2) 'totalv'
                     FROM ctransaksiu A
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;
        if(!empty($transcode)){
          $isWhere = "AND A.cusumber='".$transcode."'";
        }else{
          $isWhere = "";
        }
        $isWhere = "A.cutanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."' " . $isWhere;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_transaksi_tabel() {
        $hasil = $this->M_transaksi->get_table_field();
        if($hasil){
          $this->get_table_data($hasil['ntabel'],$hasil['nfldsumber'],$hasil['nfldid'],$hasil['nfldnotransaksi'],$hasil['nfldtanggal'],$hasil['nfldkontak'],$hasil['nflduraian'],$hasil['nfldtotaltrans']);
        }
    }

    function get_table_data($tabel,$sumber,$id,$nomor,$tanggal,$kontak,$uraian,$total){
        $transcode = $this->M_transaksi->prefixtrans($_POST['jenis']);
        $query  = "SELECT $tabel.$id 'id',$tabel.$nomor 'nomor', DATE_FORMAT($tabel.$tanggal,'%d-%m-%Y') 'tanggal',
                          $tabel.$uraian 'uraian', bkontak.knama 'kontak',
                          ROUND(IFNULL($tabel.$total,0),2) 'total'
                     FROM $tabel
                LEFT JOIN bkontak ON $tabel.$kontak=bkontak.kid";

        $search = array($nomor);
        $where  = null;
        $isWhere = " $tabel.$sumber='".$transcode."' AND $tabel.$tanggal BETWEEN '".tgl_database($_POST['dari'])."'
                    AND '".tgl_database($_POST['sampai'])."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_aktiva_saldo_awal() {
        $query  = "SELECT A.aid AS 'id',A.akode AS 'kode',A.anama AS 'nama',B.aknama AS 'kelompok',
                          ROUND(A.ahargabeli,2) AS 'nilai', ROUND(A.aakumbeban,2) AS 'akumulasi',
                          A.aumur AS 'umur',ROUND((A.ahargabeli-A.aakumbeban),2) AS 'buku',
                          DATE_FORMAT(A.atglbeli,'%d-%m-%Y') 'tglbeli', DATE_FORMAT(A.atglpakai,'%d-%m-%Y') 'tglpakai'
                     FROM baktiva A
               INNER JOIN baktivakelompok B ON A.akelompok=B.akid";
        $search = array('akode','anama');
        $where  = null;
        $isWhere = "apin=0";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_aktiva_pembelian() {
        $query  = "SELECT A.aid AS 'id',A.akode AS 'kode',A.anama AS 'nama',B.aknama AS 'kelompok',
                          ROUND(A.ahargabeli,2) AS 'nilai', ROUND(A.aakumbeban,2) AS 'akumulasi',
                          A.aumur AS 'umur',ROUND((A.ahargabeli-A.aakumbeban),2) AS 'buku',
                          DATE_FORMAT(A.atglbeli,'%d-%m-%Y') 'tglbeli', DATE_FORMAT(A.atglpakai,'%d-%m-%Y') 'tglpakai'
                     FROM baktiva A
               INNER JOIN baktivakelompok B ON A.akelompok=B.akid";
        $search = array('akode','anama');
        $where  = null;
        $isWhere = "apin=0 AND aakumbeban=0";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_aktiva_penyusutan() {
        $query  = "SELECT A.aid AS 'id',A.akode AS 'kode',A.anama AS 'nama',B.aknama AS 'kelompok',
                          ROUND(A.ahargabeli,2) AS 'nilai', ROUND(A.aakumbeban,2) AS 'akumulasi',
                          A.aumur AS 'umur',ROUND((A.ahargabeli-A.aakumbeban),2) AS 'buku',
                          A.atgl15 'tgl15', DATE_FORMAT(A.atglpakai,'%d-%m-%Y') 'tglpakai'
                     FROM baktiva A INNER JOIN baktivakelompok B ON A.akelompok=B.akid
                  ";
        $search = array('akode','anama');
        $where  = null;
        $isWhere = " A.apin=1 AND A.atipepenyusutan > 0
                      AND (A.ahargabeli-A.aakumbeban) > 0
                      AND ((YEAR(A.atglpakai) = '".$this->input->post('tahun')."' AND MONTH(A.atglpakai) <= '".$this->input->post('bulan')."')
                      OR (YEAR(A.atglpakai) < '".$this->input->post('tahun')."'))
                      AND A.aid NOT IN (
                          SELECT aid FROM baktiva
                       WHERE YEAR(atglpakai) = '".$this->input->post('tahun')."'
                          AND MONTH(atglpakai) = '".$this->input->post('bulan')."'
                          AND DAY(atglpakai) > 15
                          AND atgl15=1 )
                      AND A.aid NOT IN (
                          SELECT apida FROM baktivapenyusutan
                                   WHERE APTAHUN = '".$this->input->post('tahun')."'
                                     AND APBULAN = '".$this->input->post('bulan')."'
                      ) ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }


    function view_histori_ledger() {
/*
       $query  = "SELECT * FROM (
                        SELECT 0 'id', '' as 'link', '' as 'captionlink', 'SALDO AWAL' as 'nomor', '' as 'sumber',
                               '' as 'tanggal', '' as 'kontak', '' as 'uraian',
                               IFNULL((SELECT SUM(ROUND(B.cddebit,2))
                                         FROM ctransaksiu A
                                   INNER JOIN ctransaksid B ON A.cuid=B.cdidu
                                    LEFT JOIN bcoa C ON B.cdnocoa=C.cid
                                        WHERE B.cdnocoa='".$_POST['akun']."'
                                          AND C.ctipe < 11
                                          AND A.cutanggal < '".tgl_database($_POST['dari'])."'),0) 'debit',
                               IFNULL((SELECT SUM(ROUND(B.cdkredit,2))
                                         FROM ctransaksiu A
                                   INNER JOIN ctransaksid B ON A.cuid=B.cdidu
                                    LEFT JOIN bcoa C ON B.cdnocoa=C.cid
                                        WHERE B.cdnocoa='".$_POST['akun']."'
                                          AND C.ctipe < 11
                                          AND A.cutanggal < '".tgl_database($_POST['dari'])."'),0) 'kredit',
                               IFNULL((SELECT SUM(ROUND(B.cddebit,2)) - SUM(ROUND(B.cdkredit,2))
                                         FROM ctransaksiu A
                                   INNER JOIN ctransaksid B ON A.cuid=B.cdidu
                                    LEFT JOIN bcoa C ON B.cdnocoa=C.cid
                                        WHERE B.cdnocoa='".$_POST['akun']."'
                                          AND C.ctipe < 11
                                          AND A.cutanggal < '".tgl_database($_POST['dari'])."'),0) 'saldo',
                               '' as 'idcoa', '' as 'coa'
                             UNION ALL
                        SELECT A.cuid 'id',F.mlink 'link',F.mcaption1 'captionlink',A.cunotransaksi 'nomor',A.cusumber 'sumber', DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',
                               C.knama 'kontak', A.cuuraian 'uraian', ROUND(B.cddebit,2) 'debit', ROUND(B.cdkredit,2) 'kredit', null 'saldo',
                               D.cnocoa 'idcoa',D.cnama 'coa'
                          FROM ctransaksiu A
                    INNER JOIN ctransaksid B ON A.cuid=B.cdidu
                     LEFT JOIN bkontak C ON A.cukontak=C.kid
                     LEFT JOIN bcoa D ON B.cdnocoa=D.cid
                     LEFT JOIN anomor E ON A.cusumber=E.nkode
                     LEFT JOIN amenu F ON E.nfmenu=F.mid
                         WHERE B.cdnocoa='".$_POST['akun']."'
                           AND A.cutanggal BETWEEN '".tgl_database($_POST['dari'])."' AND '".tgl_database($_POST['sampai'])."') T";
*/
//        $isOrder = " T.id ASC, T.tanggal ASC, T.idcoa ASC";
//        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_ledger();
    }
}
