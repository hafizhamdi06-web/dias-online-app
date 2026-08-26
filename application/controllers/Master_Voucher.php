<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Voucher extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
        }          
        $this->load->model('M_Master_Voucher');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Master_Voucher->tambahData();
        }else{
          echo $this->M_Master_Voucher->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Voucher->hapusData();          
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.vid 'id', A.vnomor 'kode', DATE_FORMAT(A.vtglterbit,'%d-%m-%Y') 'tglterbit', 
                    DATE_FORMAT(A.vtglguna,'%d-%m-%Y')  'tglpakai', A.vkontak 'idpasien', B.knama 'namapasien',
                    A.vnilai 'diskon1', A.vjenis 'idjenis', C.lkode 'namajenis',
                    A.vitem 'iditem', D.ikode 'kodeitem',
                    A.v1transaksi 'penggunaan', A.vproduksaja 'produksaja', A.vmasaberlaku 'masaberlaku',
                    A.vitem2 'iditem2', E.ikode 'kodeitem2',
                    A.vrupiah 'rupiah', A.vnilai2 'diskon2',
                    A.vpemakaianbytgl 'pakaibytanggal', A.vfreeitem 'iditemfree', F.ikode 'kodeitemfree',
                    A.vkontakteman 'idteman', G.kkode 'kodeteman', G.knama 'namateman',
                    DATE_FORMAT(A.VTGLEXPIRED,'%d-%m-%Y') 'tglexpired'
                    FROM bvoucher A
                    left join bkontak B on B.kid=vkontak
                    left join blain C on C.lid=vjenis
                    left join bitem D on D.iid=vitem
                    left join bitem E on E.iid=vitem2
                    left join bitem F on F.iid=vfreeitem
                    left join bkontak G on G.kid=vkontakteman
                    
                   WHERE A.vid='".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }
          
         
        
        
        

}