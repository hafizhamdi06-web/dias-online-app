<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Karyawan extends CI_Controller {

   function __construct() {
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }
    $this->load->model('M_Master_Karyawan');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Karyawan->tambahData();
    }else{
      echo $this->M_Master_Karyawan->ubahData();
    }
   }

   function deletedata(){
    echo $this->M_Master_Karyawan->hapusData();
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.kid 'id', A.kkode 'kode', A.knama 'nama',
                       A.ktipe 'idkategori', B.ktnama 'kategori',
                       A.kjeniskaryawan 'idjeniskaryawan', J.kjnama 'jeniskaryawan',
                       A.kcabang 'idcabang', G.gnama 'cabang',
                       IFNULL(A.kaktif,0) 'aktif',

                       IFNULL(A.kdoktersmy,0) 'doktersmy',
                       IFNULL(A.ksalesmarketing,0) 'salesmarketing',
                       IFNULL(A.kaos,0) 'aos',
                       IFNULL(A.kdokterbedah,0) 'dokterbedah',
                       IFNULL(A.kreseller,0) 'reseller',
                       IFNULL(A.kdokterpj,0) 'dokterpj',
                       IFNULL(A.ktampildidokter,0) 'kolomdokter',
                       IFNULL(A.ktampildiperawat,0) 'kolomperawat',
                       IFNULL(A.ktampildiresep,0) 'kolomresep',
                       IFNULL(A.kdokterinsider,0) 'dokterinsider',

                       A.k1alamat 'alamat',
                       A.k1kota 'idkota', W1.bnama 'kota',
                       A.k1kecamatan 'idkecamatan', W2.bnama 'kecamatan',
                       A.k1telp1 'nohp', A.k1email 'email',
                       IFNULL(A.kjeniskelamin,0) 'kelamin',
                       DATE_FORMAT(A.ktgllahir,'%d-%m-%Y') 'tgllahir',
                       A.knoktp 'noktp',
                       A.kuser 'iduser', US.ukode 'user',
                       DATE_FORMAT(A.ktgljoin,'%d-%m-%Y') 'tgljoin',
                       DATE_FORMAT(A.kcreated,'%d-%m-%Y %H:%i') 'tglbuat',
                       UC.unama 'userbuat',

                       A.kidemployee 'nik', A.knamaemployee 'namapanjang',
                       A.kkodeinsider 'kodeinsider',
                       A.kkelompokfu 'idkelompokfu', L.lkode 'kelompokfu'
                  FROM bkontak A
             LEFT JOIN bkontaktipe B ON A.ktipe=B.ktid
             LEFT JOIN bkontakjenis J ON A.kjeniskaryawan=J.kjid
             LEFT JOIN bgudang G ON A.kcabang=G.gid
             LEFT JOIN bwilayah W1 ON A.k1kota=W1.bwid
             LEFT JOIN bwilayah W2 ON A.k1kecamatan=W2.bwid
             LEFT JOIN auser US ON A.kuser=US.uid
             LEFT JOIN auser UC ON A.kcreateu=UC.uid
             LEFT JOIN blain L ON A.kkelompokfu=L.lid
                 WHERE A.kid='".$_POST['id']."'";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }


}
