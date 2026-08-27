<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_User extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
        }          
        $this->load->model('M_Admin_User');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Admin_User->tambahData();
        }else{
          echo $this->M_Admin_User->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Admin_User->hapusData();          
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
          echo _pesanError("Data tidak ditemukan !");
          exit;
        }

        $query = "SELECT A.*, B.mnama 'parent'
                    FROM aamenu A LEFT JOIN aamenu B ON A.mparent=B.mid 
                   WHERE A.mid='".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getaksesmenu(){
        if($_SESSION['kode']==0){
          $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint,
                           CASE WHEN B.mparent=0 THEN B.mnama ELSE C.mnama END AS 'mgroup',
                           COALESCE(C.murutan,B.murutan) AS 'mgrouporder'
                      FROM aausermenu A
                RIGHT JOIN aamenu B on A.auidmenu=B.mid AND A.auiduser='".$this->input->post('id')."'
                 LEFT JOIN aamenu C on B.mparent=C.mid
                     WHERE B.mtype<>1 ORDER BY mgrouporder ASC, B.mparent ASC, B.murutan ASC";
        } else {
          $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint,
                           CASE WHEN B.mparent=0 THEN B.mnama ELSE C.mnama END AS 'mgroup',
                           COALESCE(C.murutan,B.murutan) AS 'mgrouporder'
                      FROM aausermenu A
                RIGHT JOIN aamenu B on A.auidmenu=B.mid AND A.auiduser='".$this->input->post('id')."'
                 LEFT JOIN aamenu C on B.mparent=C.mid
                     WHERE B.mtype<>1 AND B.mid<>201 ORDER BY mgrouporder ASC, B.mparent ASC, B.murutan ASC";
        }

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getaksesreport(){
        $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint,
                         CASE WHEN B.mparent=0 THEN B.mnama ELSE C.mnama END AS 'mgroup',
                         COALESCE(C.murutan,B.murutan) AS 'mgrouporder'
                    FROM aausermenu A RIGHT JOIN aamenu B on A.auidmenu=B.mid AND A.auiduser='".$this->input->post('id')."'
                LEFT JOIN aamenu C on B.mparent=C.mid
                   WHERE B.mtype=1
                ORDER BY mgrouporder ASC, B.mparent ASC, B.murutan ASC";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getaksesgudang(){
        $ucabangpilih = "";
        $userQuery = $this->db->query("SELECT UCABANGPILIH FROM auser WHERE uid='".$this->input->post('id')."'")->row();
        if($userQuery && $userQuery->UCABANGPILIH !== null) $ucabangpilih = $userQuery->UCABANGPILIH;

        $pilih = array_filter(array_map('trim', explode(',', $ucabangpilih)));

        $query = "SELECT GID 'gid', GKODE 'gkode', GNAMA 'gnama'
                    FROM bgudang
                   WHERE GAKTIF<>0
                ORDER BY GID ASC";

        $data = $this->db->query($query)->result_array();
        foreach($data as &$row){
          $row['dipilih'] = in_array((string)$row['gid'], $pilih) ? 1 : 0;
        }

        header('Content-Type: application/json');
        echo json_encode(array('data' => $data));
    }

    function getaksesrole(){
        $query = "SELECT A.ARID 'id', A.ARNAMAROLE 'nama',
                         CASE WHEN B.AURID IS NULL THEN 0 ELSE 1 END 'dipilih'
                    FROM aarole A
               LEFT JOIN aauserrole B ON A.ARID=B.AURIDROLE AND B.AURIDUSER='".$this->input->post('id')."' AND B.AURSTATUS=1
                ORDER BY A.ARNAMAROLE ASC";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getinfouser(){
        $query = "SELECT A.uid,A.ukode,A.unama,A.unamalengkap,A.upassword,A.uactive,A.ucreateu, A.umodifu, A.ucabang,A.ukid,A.unomor,
                         B.knama 'namakaryawan', C.gnama 'namacabang'
                    FROM auser A LEFT JOIN bkontak B ON A.ukid=B.kid LEFT JOIN bgudang C ON A.ucabang=C.gid
                   WHERE A.uid = ".$this->input->post('id');

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

}