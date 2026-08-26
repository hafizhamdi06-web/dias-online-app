<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct(){
        parent::__construct();     
        $this->load->helper('url');        
        $this->load->model('M_login');
    }

    function index()
    {
        if($this->session->has_userdata('nama')){
            redirect(base_url('dasbor'));
        }else {
            if($this->input->post('username') !== null && $this->input->post('username') !== ''){
                $this->aksi_login();
            }else{
                $data['title'] = 'Login | '.$this->config->item('app_name'); 
                $data['app_name'] = $this->config->item('app_name');   
                $data['vendor_text'] = $this->config->item('vendor_text');                                          
                $this->load->view('include/header', $data);
                $this->load->view('login', $data);                
                $this->load->view('include/footer', $data);                                                
            }
        }       
    }

    function aksi_login(){
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $id = "";
        $namauser = "";
        $cabang = "";
        $idkaryawan = "";
        $kodekaryawan = "";
        $namakaryawan = "";
        $kodecabang = "";
        $namagudang = "";
        $allcabang=0;
        
        

        $where = array(
            'ukode' => $username,
            'upassword' => md5($password), //hash('sha512',md5($password)),
            'uactive' => 1
            );

        $cek = $this->M_login->cek_login(" auser   ",$where);

        if($cek->num_rows() > 0){
            foreach($cek->result() as $row){
                $id = $row->UID;
                $kode = $row->UKODE;
                $namauser = $row->UNAMA;
                $cabang = $row->UCABANG;
                $idkaryawan = $row->UKID;
                $kodecabang = $row->UNOMOR;
            } 
            
           // $datakaryawan = _getdatakaryawan($idkaryawan);
           // foreach($datakaryawan->result() as $row){
           //     $kodekaryawan = $row->kkode; 
           //     $namakaryawan = $row->knama; 
           // }  
            
            $datakaryawan = _getuserinfo2($id);
            foreach($datakaryawan->result() as $row){
                $kodekaryawan = $row->kkode; 
                $namakaryawan = $row->knama; 
                $namagudang = $row->gkode; 
            }  
            
            
             // $where2 = array(
             //   'AUIDMENU' => 3,
             //   'AUIDUSER' => $id,
             //   'AUAPPROVE' => 1
             //   );

             //    $cek2 = $this->M_login->cek_login(" ausermenu  ",$where2);
             //   if($cek2->num_rows() > 0) $allcabang=1;
            
    
              $where2 = array(
                'AURIDROLE' => 17,
                'AURIDUSER' => $id,
                'AURSTATUS' => 1
                );

                 $cek2 = $this->M_login->cek_login(" auuserrole  ",$where2);
                if($cek2->num_rows() > 0) $allcabang=1;
            
    
       
            $data_session = array(
                'nama' => ucwords($namauser),
                'id' => $id,
                'kode' => $kode,
                'cabang' => $cabang,
                'idkaryawan' => $idkaryawan,
                'kodekaryawan' => $kodekaryawan,
                'namakaryawan' => $namakaryawan,
                'kodecabang' => $kodecabang,
                'allcabang' => $allcabang,
                'namagudang' => $namagudang,
                'status' => "login"
                ); 

            $this->session->set_userdata($data_session);
            redirect(base_url('dasbor'));

        } else {

            $data['pesan'] = "<div class=\"alert alert-danger text-sm rounded-1\">
                                <center>
                                Login gagal, mohon periksa kembali login anda...
                                </center>
                              </div>";

            $data['title'] = 'Login | '.$this->config->item('app_name');
            $data['app_name'] = $this->config->item('app_name');               
            $data['vendor_text'] = $this->config->item('vendor_text');                                                      
            $data['username'] = $username;          
            $this->load->view('include/header', $data);
            $this->load->view('login', $data);                
            $this->load->view('include/footer', $data);                                                

        }
    }

    function aksi_logout(){
        $this->session->sess_destroy();
        redirect(base_url());
    }    

}
