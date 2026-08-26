<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PJ_Editdepo extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
        }          
        $this->load->model('M_PJ_Editdepo');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_PJ_Editdepo->tambahData();
        }else{
          echo $this->M_PJ_Editdepo->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_PJ_Editdepo->hapusData();          
    }

    function getdata(){
        
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT sunotransaksi 'notransaksi', suid 'idu', DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal', sukontak 'kontak', 
        kkode 'kodekontak', knama 'namapasien', sucabang 'cabangid', gkode 'cabang',
        iid 'idtindakan', ikode 'kodeitem', inama 'tindakan', sdkeluar 'qty', sdharga 'harga', sddiskon 'diskon', (sdharga-sddiskon)*sdkeluar 'subtotal', sdnoref 'noref', sdid 'idd', sdidalkesnya 'idalkesnya', sdid 'sdid'
        from fstokd B left join fstoku A on suid=sdidsu left join bitem on iid=sditem left join bkontak on kid=sukontak  left join bgudang on gid=sucabang
                   WHERE A.suid='".$this->input->post('id')."'";
       $query .= " and ijenisitem in(1,4,5) and iresep=0 " ;
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getdata_tindakan_alkes(){
        
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        } 
          

        $query = "select suid 'id', sutanggal,sunotransaksi,sucabang,sukontak,knama,iid, inama, ikode, sdkeluar, skode, sdsatuan, sdidualkes ,sdqtydasar  from 
          fstokd  left join bitem on IID = SDITEM  left join bsatuan on sdsatuan=sid left join fstoku on suid=sdidsu left join bkontak on sukontak=kid 
                   WHERE SDIDUALKES='".$this->input->post('id')."' "; 
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }
    
    

    function getdata_alkes(){
        
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }
        
          

        $query = "SELECT a.iid 'idproduk_alkes', a.IKODE 'kode',a.INAMA 'produk_alkes',IPIDQTY 'qtystandar_alkes',skode 'satuan_alkes',sid 'idsatuan_alkes', skode 'satuan_alkes'    FROM 
        bitemalkes inner JOIN bitem a ON IPIDBB=a.IID 
        inner JOIN bsatuan ON SID=IPIDSATUAN  
        inner JOIN bitem b ON IPIDB=b.IID
        
                   WHERE b.iid='".$this->input->post('id')."' "; 
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }
    
    
          

function getnomortransaksi(){
        $cabang  = @$_SESSION['cabang'] ;
        $kodecabang  = @$_SESSION['kodecabang'] ;  
        $tgl=$this->input->post('tgl');
        $nomor = 0;
        $nomor1 =  $this->M_transaksi->prefixtrans(element('PJ_Editdepo',NID));
        $nomor2 =  tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(sunotransaksi,4)) as 'maks' 
                  FROM fstoku 
                 WHERE MID(sunotransaksi,4,".$notrans_length.")='".$nomor1.$nomor2."' and sucabang='".$cabang."'  "; 
                 
        $query = $this->db->query($sql);

        foreach ($query->result() as $res) {
            $nomor = $res->maks;
        }
        $nomor++;

        switch(strlen($nomor)){
        case 1:
          $nomor=$nomor1.$nomor2."000".$nomor;
          break;
        case 2:
          $nomor=$nomor1.$nomor2."00".$nomor;
          break;
        case 3:
          $nomor=$nomor1.$nomor2."0".$nomor;
          break;
        case 4:
          $nomor=$nomor1.$nomor2.$nomor;
          break;
        }
          $nomor=$kodecabang."-".$nomor ;
          $query = "select '".$nomor."' as no from buang where uid=1 " ; 
          header('Content-Type: application/json');
          echo $this->M_transaksi->get_data_query($query);
    }
    


}