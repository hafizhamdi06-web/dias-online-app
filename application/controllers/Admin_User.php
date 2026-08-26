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
          $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint 
                      FROM aausermenu A 
                RIGHT JOIN aamenu B on A.auidmenu=B.mid AND A.auiduser='".$this->input->post('id')."' 
                     WHERE B.mtype<>1 ORDER BY B.murutan";
        } else {
          $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint 
                      FROM aausermenu A 
                RIGHT JOIN aamenu B on A.auidmenu=B.mid AND A.auiduser='".$this->input->post('id')."' 
                     WHERE B.mtype<>1 AND B.mid<>201 ORDER BY B.murutan";
        }
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getaksesreport(){
        $query = "SELECT B.mid,B.mparent,B.mtype,B.mnama,A.auapprove,A.auadd,A.auedit,A.audell,A.auprint 
                    FROM aausermenu A RIGHT JOIN aamenu B on A.auidmenu=B.mid AND A.auiduser='".$this->input->post('id')."' 
                   WHERE B.mtype=1 
                ORDER BY B.murutan";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }   

    function getinfouser(){
        $query = "SELECT uid,ukode,unama,unama as unamalengkap,upassword,uactive,ucreateu, umodifu, ucabang,ukid,unomor
                    FROM auser WHERE uid = ".$this->input->post('id');
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

}