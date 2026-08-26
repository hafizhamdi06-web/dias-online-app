<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Item extends CI_Controller {

    function __construct() { 
        parent::__construct();
        if(!$this->session->has_userdata('nama')) redirect(base_url('exception'));
        $this->load->model('M_Master_Item');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if($this->input->post('id')==''){
          echo $this->M_Master_Item->tambahData();
        }else{
          echo $this->M_Master_Item->ubahData();      
        }
    }

    function deletedata(){
        echo $this->M_Master_Item->hapusData();          
    }

    function getdefaultcoa(){
        $query = "SELECT A.cccoa 'idcoa', B.cnama 'coa'
                    FROM cconfigcoa A
               LEFT JOIN bcoa B ON A.cccoa=B.cid
                   WHERE A.cckode = '".$this->input->post('id')."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);    
    }

    function getdata(){
        if($this->input->post('id') == '' || $this->input->post('id') == null) {
          echo _pesanError("Data tidak ditemukan !");
          exit;
        }

        $query = "SELECT A.iid 'id', A.ikode 'kode', A.inama 'nama', A.isatuan 'idsatuan', B.skode 'satuan', A.isatuand 'idsatuand', 
                         C.skode 'satuand', A.ijenisitem 'jenis', A.itipeitem 'tipe', A.istatus 'status',
                         IFNULL(A.istockminimal,0) 'stokmin', IFNULL(A.istockmaksimal,0) 'stokmaks', 
                         CASE WHEN A.ijenisitem=0 THEN IFNULL(A.istocktotal,0) 
                              WHEN A.ijenisitem=1 THEN 0
                              ELSE IFNULL(A.istocktotal,0)
                         END AS 'stoktotal', 
                         IFNULL(A.istockreorder,0) 'reorder',
                         IFNULL(A.ihargabeli,0) 'hargabeli', IFNULL(A.ihargajual1,0) 'hargajual1',
                         IFNULL(A.ihargajual2,0) 'hargajual2', IFNULL(A.ihargajual3,0) 'hargajual3',
                         IFNULL(A.ihargajual4,0) 'hargajual4', A.icoapersediaan 'idcoapersediaan',
                         A.icoapendapatan 'idcoapendapatan', A.icoahpp 'idcoahpp',
                         D.cnama 'coapersediaan',E.cnama 'coapendapatan',F.cnama 'coahpp',
                         A.ibarcode 'barcode' 
                    FROM bitem A
               LEFT JOIN bsatuan B ON A.isatuan=B.sid  
               LEFT JOIN bsatuan C ON A.isatuand = C.sid
               LEFT JOIN bcoa D ON A.icoapersediaan=D.cid 
               LEFT JOIN bcoa E ON A.icoapendapatan=E.cid
               LEFT JOIN bcoa F ON A.icoahpp=F.cid
                   WHERE A.iid='".$this->input->post('id')."'";

        $transcode = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan',NID));                
        $query2 = "SELECT H.sunotransaksi 'nomorsa', DATE_FORMAT(H.sutanggal,'%d-%m-%Y') 'tglsa',
                          G.sdgudang 'idgudangsa', I.gnama 'gudangsa', G.sdharga 'hargasa', G.sdmasuk 'qtysa',
                          H.sukontak 'idkontaksa',J.knama 'kontaksa'
                     FROM fstokd G 
               INNER JOIN fstoku H ON H.suid=G.sdidsu AND H.susumber='".$transcode."' 
                LEFT JOIN bgudang I ON G.sdgudang=I.gid 
                LEFT JOIN bkontak J ON H.sukontak=J.kid
                    WHERE G.sditem = '".$this->input->post('id')."' 
                  ";

       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query_second($query,$query2);        
    }

}