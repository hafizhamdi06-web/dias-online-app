<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Pasien extends CI_Controller {

   function __construct() {
		parent::__construct();
    if(!$this->session->has_userdata('nama')){
      redirect(base_url('exception'));
    }
    $this->load->model('M_Master_Pasien');
    $this->load->model('M_transaksi');
   }

   function savedata(){
    if($_POST['id']==''){
      echo $this->M_Master_Pasien->tambahData();
    }else{
      echo $this->M_Master_Pasien->ubahData();
    }
   }

   function deletedata(){
    echo $this->M_Master_Pasien->hapusData();
   }

   function getdata(){
      if($_POST['id'] == '' || $_POST['id'] == null) {
        echo _pesanError("Data tidak ditemukan !");
        exit;
      }

      $query = "SELECT A.kid 'id', A.kkode 'kode', A.knama 'nama', A.knomember 'nomember',
                       A.ktipe 'idkategori', B.ktnama 'kategori',
                       A.kidpasien 'idpasien', A.knoktp 'noktp',
                       A.kcabang 'idcabang', G.gnama 'cabang',
                       DATE_FORMAT(A.ktglkontrak,'%d-%m-%Y') 'tglkontrak',
                       DATE_FORMAT(A.ktgllahir,'%d-%m-%Y') 'tgllahir',
                       A.kpekerjaan 'pekerjaan', A.ktempatlahir 'tempatlahir',
                       A.k1alamat 'alamat', A.k1telp1 'telp', A.k1email 'email',
                       A.k1kota 'idkota', W1.bnama 'kota',
                       A.k1kecamatan 'idkecamatan', W2.bnama 'kecamatan',
                       A.kcard 'nokartu', A.kkodelama 'kodetada',
                       A.kkaryawan 'idkaryawan', E.knama 'karyawan',
                       A.kkaryawantraining 'idkaryawantraining', F.knama 'karyawantraining',
                       A.kreff 'insider',
                       A.kmarketingsource 'idmarketingsource', L.lkode 'marketingsource',
                       IFNULL(A.kbarulama,0) 'barulama',
                       IFNULL(A.kjeniskelamin,0) 'kelamin',
                       IFNULL(A.kaktif,0) 'aktif',
                       IFNULL(A.kpoint,0) 'point',
                       DATE_FORMAT(A.kcreated,'%d-%m-%Y %H:%i') 'tglbuat',
                       U.unama 'userbuat'
                  FROM bkontak A
             LEFT JOIN bkontaktipe B ON A.ktipe=B.ktid
             LEFT JOIN bgudang G ON A.kcabang=G.gid
             LEFT JOIN bwilayah W1 ON A.k1kota=W1.bwid
             LEFT JOIN bwilayah W2 ON A.k1kecamatan=W2.bwid
             LEFT JOIN bkontak E ON A.kkaryawan=E.kid
             LEFT JOIN bkontak F ON A.kkaryawantraining=F.kid
             LEFT JOIN blain L ON A.kmarketingsource=L.lid
             LEFT JOIN auser U ON A.kcreateu=U.uid
                 WHERE A.kid='".$_POST['id']."'";

      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }


}
