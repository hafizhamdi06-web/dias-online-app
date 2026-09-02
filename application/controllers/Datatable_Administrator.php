<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable_Administrator extends CI_Controller {

   function __construct() { 
      parent::__construct();
      $this->load->model('M_datatables');
      if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
      }      
  }

   function view_table_user() {
        $query  = "SELECT uid,ukode,unama,unamalengkap,IF(uactive='1','Aktif','Tidak Aktif') AS 'uactive' 
                     FROM auser";
        $search = array('ukode','unama');
        $where  = null;  
        $isWhere = "1=1";

       // if($_SESSION['kode']==0){
       //   $isWhere = null;
       // } else {
        //  $isWhere = " ukode<>0 ";
       // }

        if(!empty($_POST['nama'])){
          $isWhere .= " AND unama like '%".$_POST['nama']."%' ";
        }

        if(!empty($this->input->post('aktifsaja'))) {
          $isWhere .= " AND uactive=1 ";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_table_menu() {
        // A  = menu, P1 = induk langsung, P2 = induk dari induk (kakek)
        $query  = "SELECT A.mid,A.mnama,A.mdescription,
                          CASE WHEN A.mtype=0 THEN 'Module'
                               WHEN A.mtype=1 THEN 'Laporan'
                               WHEN A.mtype=2 THEN 'Transaksi'
                               WHEN A.mtype=3 THEN 'Master Data'
                               ELSE 'Administrator'
                          END AS 'mtype',
                          A.micon,A.murutan,IF(A.mactive='1','Aktif','Tidak Aktif') AS 'mactive',
                          A.mparent,
                          CASE WHEN A.mparent=0 THEN 1
                               WHEN IFNULL(P1.mparent,0)=0 THEN 2
                               ELSE 3 END AS 'mdepth',
                          CASE WHEN A.mparent=0 THEN A.mnama
                               WHEN IFNULL(P1.mparent,0)=0 THEN P1.mnama
                               ELSE P2.mnama END AS 'mgroup',
                          CASE WHEN A.mparent=0 THEN A.murutan
                               WHEN IFNULL(P1.mparent,0)=0 THEN P1.murutan
                               ELSE IFNULL(P2.murutan,0) END AS 'mgrouporder',
                          CASE WHEN A.mparent=0 THEN -1
                               WHEN IFNULL(P1.mparent,0)=0 THEN A.murutan
                               ELSE IFNULL(P1.murutan,0) END AS 'mlvl2order'
                     FROM aamenu A
                LEFT JOIN aamenu P1 ON A.mparent=P1.mid
                LEFT JOIN aamenu P2 ON P1.mparent=P2.mid";
        $search = array('A.mnama','A.mdescription');
        $where  = null;
        $isWhere = "A.mnama LIKE '%".$this->input->post('nama')."%'";

        if(!empty($this->input->post('tipe')) && $this->input->post('tipe') != '') {
          $isWhere .= " AND A.mtype='".$this->input->post('tipe')."'";
        }

        $isOrder = "mgrouporder ASC, mlvl2order ASC, mdepth ASC, A.murutan ASC, A.mid ASC";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere,$isOrder);
    }

   function view_table_report() {
        $query  = "SELECT arid 'id', arname 'nama', arname2 'alias', artitle 'judul',
                          CASE WHEN arpaperorinted=0 THEN 'Default' 
                               WHEN arpaperorinted=1 THEN 'Portrait' 
                               WHEN arpaperorinted=2 THEN 'Landscape' 
                          END 'orientasi',
                          CASE WHEN arpapersize=1 THEN 'Letter' 
                               WHEN arpapersize=2 THEN 'Legal' 
                               WHEN arpapersize=3 THEN 'A4' 
                          END 'ukuran',
                          IF(aractive='1','Aktif','Tidak Aktif') 'aktif' 
                     FROM aareport";
        $search = array('arname');
        $where  = null;         
        $isWhere = "arname LIKE '%".$this->input->post('nama')."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_table_userlog() {
        $query  = "SELECT A.ulid AS 'id',A.ulusername AS 'user',A.ulcomputer AS 'komputer',DATE_FORMAT(A.uldate,'%d/%m/%Y') AS 'tanggal',
                          DATE_FORMAT(A.ultime,'%r') AS 'jam',A.ulactivity AS 'kegiatan',
                          CASE WHEN A.ullevel=0 THEN 'Hapus'
                               WHEN A.ullevel=1 THEN 'Tambah'
                               WHEN A.ullevel=2 THEN 'Edit'
                               WHEN A.ullevel=3 THEN 'Cetak'
                          END AS 'level'
                     FROM aauserlog A
                LEFT JOIN auser B ON A.ULUSER=B.uid";
        $search = array('A.ulusername','A.ulcomputer');
        $where  = null;

        $isWhere = "1=1";

        $tgldari = @$_POST['tgldari'];
        $tglsampai = @$_POST['tglsampai'];
        if (!empty($tgldari) && !empty($tglsampai)) {
          $isWhere .= " AND DATE(A.uldate) BETWEEN '".tgl_database($tgldari)."' AND '".tgl_database($tglsampai)."'";
        }

        $cabang = $this->_cabangValidUserlog(@$_POST['cabang']);
        if (!empty($cabang)) {
          $isWhere .= " AND B.ucabang='".$cabang."'";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

    private function _cabangValidUserlog($cabang) {
        if (empty($cabang)) {
          return '';
        }

        if (!empty($this->session->allcabang) && $this->session->allcabang == 1) {
          return (int) $cabang;
        }

        $ucabangpilih = '';
        $sql = "SELECT UCABANGPILIH FROM auser WHERE UID='".$this->session->id."'";
        $res = $this->db->query($sql);
        foreach ($res->result() as $row) {
          $ucabangpilih = $row->UCABANGPILIH;
        }
        $allowed = array_filter(array_map('trim', explode(',', $ucabangpilih)));
        if (!in_array($cabang, $allowed)) {
          return 0;
        }
        return (int) $cabang;
    }

}