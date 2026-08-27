<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PB_Permintaan_Barang extends CI_Controller {

   function __construct() {
        parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('exception'));
      }
        $this->load->model('M_transaksi');
      $this->load->model('M_PB_Permintaan_Barang');
   }

   function savedata(){
      if($_POST['id']==''){
        echo $this->M_PB_Permintaan_Barang->tambahTransaksi();
      }else{
        echo $this->M_PB_Permintaan_Barang->ubahTransaksi();
      }
   }

   function deletedata(){
      echo $this->M_PB_Permintaan_Barang->hapusTransaksi();
   }

   function getnomor(){
      echo $this->M_PB_Permintaan_Barang->autonumber($this->input->post('tgl'));
   }

   function getgudangnama(){
      $query = "SELECT gnama FROM bgudang WHERE gid='".$this->input->post('id')."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

   function get_item() {
        $query  = "SELECT A.ikode 'kode', A.inama 'nama', A.isatuan AS 'idsatuan', B.snama 'namasatuan'
                     FROM bitem A LEFT JOIN bsatuan B ON A.isatuan=B.sid
                    WHERE A.iid='".$_POST['id']."'";
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

   function refreshstok(){
      $iditem = $this->input->post('item');
      $idgudang = $this->input->post('gudang');

      if(empty($iditem) || empty($idgudang)){
        echo json_encode(array('data'=>array(array('stok'=>0))));
        return;
      }

      $kolom = "F_KOLOMGUDANG('".$idgudang."')";
      $sql = "SELECT ".$kolom." AS 'kolom'";
      $result = $this->db->query($sql)->row();
      $kolomstok = $result->kolom;

      $query = "SELECT IFNULL(".$kolomstok.",0) 'stok' FROM bitem WHERE iid='".$iditem."'";
      header('Content-Type: application/json');
      echo $this->M_transaksi->get_data_query($query);
   }

   function getdata(){
        if(empty($_POST['id'])) {
            echo _pesanError("Id transaksi tidak ditemukan !");
            exit;
        }

      $transcode = $this->M_transaksi->prefixtrans(element('PB_Permintaan_Barang',NID));
        $query = "SELECT A.pbuid 'id', A.pbunotransaksi 'nomor', DATE_FORMAT(A.pbutanggal,'%d-%m-%Y') 'tanggal',
                         A.pbukontak 'idkaryawan', B.kkode 'kodekaryawan', B.knama 'namakaryawan',
                         A.pbugudang 'idgudang', C.gnama 'gudang',
                         A.pbugudangsumber 'idgudangsumber', D.gnama 'gudangsumber',
                         A.pbutipepermintaan 'idtujuan', E.lnama 'tujuan', E.lkode 'tujuankode',
                         A.pbujenis 'jenis', A.pbuuraian 'uraian', A.pbustatus 'status',
                         A.pbukonfirmasicatatan 'catatanverifikasi',
                         F.pbditem 'iditem', G.ikode 'kditem', G.inama 'namaitem', F.pbdcatatan 'catdetil',
                         F.pbdsatuan 'idsatuan', H.skode 'satuan',
                         IFNULL(F.pbdqty,0) 'qtydetil',
                         IFNULL(F.pbdstokreal,0) 'stokrealdetil',
                         IFNULL(F.pbdstok,0) 'stokdetil'
                    FROM fpermintaanbarangu A
               LEFT JOIN bkontak B ON A.pbukontak=B.kid
               LEFT JOIN bgudang C ON A.pbugudang=C.gid
               LEFT JOIN bgudang D ON A.pbugudangsumber=D.gid
               LEFT JOIN blain E ON A.pbutipepermintaan=E.lid
               LEFT JOIN fpermintaanbarangd F ON A.pbuid=F.pbdidsu
               LEFT JOIN bitem G ON F.pbditem=G.iid
               LEFT JOIN bsatuan H ON F.pbdsatuan=H.sid
                  WHERE A.pbusumber='".$transcode."' AND A.pbuid='".$_POST['id']."' ORDER BY F.pbdurutan ASC ";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

}
