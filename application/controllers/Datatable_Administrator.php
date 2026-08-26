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
        $isWhere = "";
        
       // if($_SESSION['kode']==0){
       //   $isWhere = null;
       // } else {
        //  $isWhere = " ukode<>0 ";          
       // } 
        
        if(!empty($_POST['nama'])){
          $isWhere .= " unama like '%".$_POST['nama']."%' ";
        }else{
          $isWhere .= "";
        }        
        
        
        
       // $isWhere .= "unama LIKE '%".$this->input->post('nama')."%'";
        
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_table_menu() {
        $query  = "SELECT A.mid,A.mnama,A.mdescription,
                          CASE WHEN A.mtype=0 THEN 'Module'
                               WHEN A.mtype=1 THEN 'Laporan'
                               WHEN A.mtype=2 THEN 'Transaksi'
                               WHEN A.mtype=3 THEN 'Master Data'
                               ELSE 'Administrator'
                          END AS 'mtype',
                          A.micon,A.murutan,IF(A.mactive='1','Aktif','Tidak Aktif') AS 'mactive',
                          A.mparent,
                          CASE WHEN A.mparent=0 THEN A.mnama ELSE B.mnama END AS 'mgroup',
                          COALESCE(B.murutan,A.murutan) AS 'mgrouporder'
                     FROM aamenu A LEFT JOIN aamenu B ON A.mparent=B.mid";
        $search = array('A.mnama','A.mdescription');
        $where  = null;
        $isWhere = "A.mnama LIKE '%".$this->input->post('nama')."%'";

        if(!empty($this->input->post('tipe')) && $this->input->post('tipe') != '') {
          $isWhere .= " AND A.mtype='".$this->input->post('tipe')."'";
        }

        $isOrder = "mgrouporder ASC, A.mparent ASC, A.murutan ASC";

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
        $query  = "SELECT ulid AS 'id',ulusername AS 'user',ulcomputer AS 'komputer',DATE_FORMAT(uldate,'%d/%m/%Y') AS 'tanggal',
                          DATE_FORMAT(ultime,'%r') AS 'jam',ulactivity AS 'kegiatan',
                          CASE WHEN ullevel=0 THEN 'Hapus' 
                               WHEN ullevel=1 THEN 'Tambah' 
                               WHEN ullevel=2 THEN 'Edit' 
                               WHEN ullevel=3 THEN 'Cetak' 
                          END AS 'level' 
                     FROM aauserlog";
        $search = array('ulusername','ulcomputer');
        $where  = null;         
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

}