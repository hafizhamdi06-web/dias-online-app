<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable_Transaksi extends CI_Controller {

   function __construct() { 
      parent::__construct();
      if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
      }            
      $this->load->model('M_datatables');
      $this->load->model('M_transaksi');      
   }

    /* Table ctransaksiu */
   function view_kas_masuk() {
        $transcode = element('Fina_Kas_Masuk',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_kas_keluar() {
        $transcode = element('Fina_Kas_Keluar',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_bank_masuk() {
        $transcode = element('Fina_Bank_Masuk',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_bank_keluar() {
        $transcode = element('Fina_Bank_Keluar',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_jurnal_umum() {
        $transcode = element('Fina_Jurnal_Umum',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.cuid 'id',A.cunotransaksi 'nomor',DATE_FORMAT(A.cutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ctransaksiu A 
                LEFT JOIN bkontak B ON A.cukontak=B.kid";
        $search = array('cunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.cusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    
    /* End ctransaksiu */


    /* Table esalesorderu */
   function view_order_pembelian() {
        $transcode = element('PB_Order_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_order_penjualan() {
        $transcode = element('PJ_Order_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        
    /* End esalesorderu */

    /* Table fstoku */
   function view_penerimaan_barang() {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_keluar_barang_pembelian() {
        $transcode = element('PB_Keluar_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_surat_jalan() {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }          

   function view_terima_retur() {
        $transcode = element('PJ_Terima_Retur',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }          

   function view_mutasi_barang() {
        $transcode = element('STK_Mutasi_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }              

   function view_penyesuaian_barang() {
        $transcode = element('STK_Penyesuaian_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                  
   
    /* End fstoku */

    /* Table einvoicepenjualanu */
   function view_faktur_pembelian() {
        $transcode = element('PB_Faktur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_retur_pembelian() {
        $transcode = element('PB_Retur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_penjualan_tunai() {
        $transcode = element('PJ_Penjualan_Tunai',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }               


   function view_faktur_penjualan() {
        $transcode = element('PJ_Faktur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_retur_penjualan() {
        $transcode = element('PJ_Retur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipusaldoawal<>'1'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

    /* End einvoicepenjualanu */

    /* Table epembayaraninvoiceu */
   function view_pembayaran_hutang() {
        $transcode = element('PB_Pembayaran_Hutang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM epembayaraninvoiceu A 
                LEFT JOIN bkontak B ON A.piukontak=B.kid";
        $search = array('piunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.piusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_pembayaran_piutang() {
        $transcode = element('PJ_Pembayaran_Piutang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.piuid 'id',A.piunotransaksi 'nomor',DATE_FORMAT(A.piutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM epembayaraninvoiceu A 
                LEFT JOIN bkontak B ON A.piukontak=B.kid";
        $search = array('piunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.piusumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    
    /* End epembayaraninvoiceu */


    /* Table fstokopnameu */
   function view_stok_opname() {
        $transcode = element('STK_Stok_Opname',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid AS 'id',A.sounotransaksi AS 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') AS 'tanggal',B.knama AS 'kontak'         
                     FROM fstokopnameu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            
    /* End fstokopnameu */

   function view_cari_order_pembelian($idkontak) {
        $transcode = element('PB_Order_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' AND A.soustatus IN (1,2)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_terima_barang($idkontak) {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2,3)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_terima_barang_r($idkontak) {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2,3)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_faktur_pembelian($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));
        $transcode = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID));    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null; 
        $isWhere = "A.ipusumber IN('".$transcode."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_faktur_pembelian_dp($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));    
        $transcode_faktur = $this->M_transaksi->prefixtrans(element('PB_Faktur_Pembelian',NID));        
        $transcode_dp = $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID));            
        $query  = "SELECT * FROM(
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak'        
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                    WHERE A.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."' 
                      AND (A.iputotaltransaksi-A.ipujumlahdp-A.iputotalbayar)>0 
                    UNION 
                   SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak' 
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid
                    WHERE A.dpsumber='".$transcode_dp."' AND A.dpkontak='".$idkontak."' AND (A.dpjumlah-A.dpjumlahbayar)>0 
                ) T ";
        $search = array('nomor','kontak');
        $where  = null;
        $isWhere = "";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = " T.nomor NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_faktur_penjualan_dp($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));                
        $transcode_faktur = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));        
        $transcode_dp = $this->M_transaksi->prefixtrans(element('PJ_Uang_Muka_Penjualan',NID));            
        $query  = "SELECT * FROM(
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak'        
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                    WHERE A.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."' 
                      AND (A.iputotaltransaksi-A.ipujumlahdp-A.iputotalbayar)>0 
                    UNION 
                   SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak' 
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid
                    WHERE A.dpsumber='".$transcode_dp."' AND A.dpkontak='".$idkontak."' AND (A.dpjumlah-A.dpjumlahbayar)>0 
                ) T ";
        $search = array('nomor','kontak');
        $where  = null;
        $isWhere = "";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = " T.nomor NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_cari_faktur_penjualan($idkontak) {
        $transcode_saldoawal = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));            
        $transcode_faktur = $this->M_transaksi->prefixtrans(element('PJ_Faktur_Penjualan',NID));        
        $query  = "SELECT * FROM(
                   SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',
                          B.knama 'kontak'        
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid 
                    WHERE A.ipusumber IN('".$transcode_faktur."','".$transcode_saldoawal."') AND A.ipukontak='".$idkontak."'
                ) T ";
        $search = array('nomor','kontak');
        $where  = null;         
        $isWhere = "";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                

   function view_cari_retur_pembelian($idkontak) {
        $transcode = element('PB_Retur_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipukontak='".$idkontak."'";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere." AND A.ipunotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_retur_penjualan($idkontak) {
        $transcode = element('PJ_Retur_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.ipuid 'id',A.ipunotransaksi 'nomor',DATE_FORMAT(A.iputanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM einvoicepenjualanu A 
                LEFT JOIN bkontak B ON A.ipukontak=B.kid";
        $search = array('ipunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.ipusumber='".$transcode."' AND A.ipukontak='".$idkontak."'";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere." AND A.ipunotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                

   function view_cari_order_penjualan($idkontak) {
        $transcode = element('PJ_Order_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid AS 'id',A.sounotransaksi AS 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') AS 'tanggal',B.knama AS 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' AND A.soustatus IN (1,2)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_cari_surat_jalan($idkontak) {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_surat_jalan_r($idkontak) {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' AND A.sustatus IN (1,2,3)";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }        

   function view_cari_terima_barang_new($idkontak) {
        $transcode = element('PB_Terima_Barang',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid 
                INNER JOIN fstokd C ON A.suid=C.sdidsu AND C.sdmasuk-C.sdfaktur > 0 ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = " A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.suid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.suid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }        

   function view_cari_surat_jalan_new($idkontak) {
        $transcode = element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid 
                INNER JOIN fstokd C ON A.suid=C.sdidsu AND C.sdkeluar-C.sdfaktur > 0 ";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = " A.susumber='".$transcode."' AND A.sukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.suid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.suid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }        

   function view_cari_order_pembelian_new($idkontak) {
        $transcode = element('PB_Order_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid 
               INNER JOIN esalesorderd C ON A.souid=C.sodidsou AND C.sodorder-C.sodmasuk > 0                 
                ";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.souid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.souid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }                


   function view_cari_order_penjualan_new($idkontak) {
        $transcode = element('PJ_Order_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.souid 'id',A.sounotransaksi 'nomor',DATE_FORMAT(A.soutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM esalesorderu A 
                LEFT JOIN bkontak B ON A.soukontak=B.kid 
               INNER JOIN esalesorderd C ON A.souid=C.sodidsou AND C.sodorder-C.sodkeluar > 0                 
                ";
        $search = array('sounotransaksi','knama');
        $where  = null;         
        $isWhere = "A.sousumber='".$transcode."' AND A.soukontak='".$idkontak."' ";

        if(!empty($_POST['param'])) {
            $isWhere = $isWhere. " AND A.souid NOT IN (".$this->input->post('param').") ";        
        }

        $isGroup = " A.souid ";

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_group($query,$search,$where,$isWhere,$isGroup);
    }                    

   function view_cari_uang_muka_pembelian($idkontak) {
        $transcode = element('PB_Uang_Muka_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.dpjumlah 'jumlah',A.dpketerangan 'uraian'
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid 
                   ";
        $search = array('dpnotransaksi','dpketerangan');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."' AND A.dpkontak='".$idkontak."' AND A.dpjumlah-A.dppakaiiv > 0";


        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere. " AND A.dpnotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_uang_muka_pembelian() {
        $transcode = element('PB_Uang_Muka_Pembelian',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid";
        $search = array('dpnotransaksi','knama');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

  function view_cari_uang_muka_penjualan($idkontak) {
        $transcode = element('PJ_Uang_Muka_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak',
                          A.dpjumlah 'jumlah',A.dpketerangan 'uraian'
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid 
                   ";
        $search = array('dpnotransaksi','dpketerangan');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."' AND A.dpkontak='".$idkontak."' AND A.dpjumlah-A.dppakaiiv > 0";

        if($this->input->post('param') != '' && !empty($this->input->post('param'))) {
            $isWhere = $isWhere. " AND A.dpnotransaksi NOT IN (".$this->input->post('param').")";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_uang_muka_penjualan() {
        $transcode = element('PJ_Uang_Muka_Penjualan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.dpid 'id',A.dpnotransaksi 'nomor',DATE_FORMAT(A.dptanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'  
                     FROM ddp A 
                LEFT JOIN bkontak B ON A.dpkontak=B.kid";
        $search = array('dpnotransaksi','knama');
        $where  = null;         
        $isWhere = "A.dpsumber='".$transcode."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }
    
           

   function view_pos($cabang) {
        $transcode =  element('PJ_Surat_Jalan',NID);
        $transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='IP' ";
        
         if( !empty($cabang)) {
            $isWhere = $isWhere. " AND A.sucabang = '".$cabang."' " ;
        }
        
        
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }  
    
           

   function view_pos_cancel() { 
       
       
        $iduser = @$_SESSION['id'] ;
        //$transcode = $this->M_transaksi->prefixtrans($transcode);    
        $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak'         
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid";
        $search = array('sunotransaksi','knama');
        $where  = null;         
        $isWhere = "A.susumber='IP' and A.sutanggal = current_date and sucreateu = '".$iduser."'  and A.sustatus<>9" ;  
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    
    
        

   function view_pos_histori($idkontak) { 
         $query  = "SELECT A.suid 'id',A.sunotransaksi 'nomor',DATE_FORMAT(A.sutanggal,'%d-%m-%Y') 'tanggal',B.knama 'kontak', D.inama 'namaitem'          
                     FROM fstoku A 
                LEFT JOIN bkontak B ON A.sukontak=B.kid left join fstokd C on C.sdidsu=A.suid left join bitem D on D.iid=C.sditem ";
        $search = array('sunotransaksi','knama','inama');
        $where  = null;         
        $isWhere = "A.susumber='IP'    and A.sustatus<>9   and B.kid='".$idkontak."'  ";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }   
    
        

   function view_pro($idkontak) { 
        $query  = "SELECT DISTINCT B.lmcId 'id',A.medCode 'nomor',DATE_FORMAT(current_date,'%d-%m-%Y') 'tanggal',C.knama 'kontak'         
                     FROM official_nmw.ops_medical A left join official_nmw.log_medlib_cart B on A.medId=B.lmcMedicalId left join official_nmw.log_medlib_detail D on D.lmdCartId=B.lmcId
                LEFT JOIN bkontak C ON B.lmcPatientKID=C.kid ";
        $search = array('medCode','knama');
        $where  = null;         
        $isWhere = " coalesce(B.lmcDiasIP,'') = '' AND B.lmcPaymentStatus=0 and  D.lmdSelected=1  and C.kid='".$idkontak."'  ";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }   

   function view_web($idkontak) { 
       
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT DISTINCT ivhId 'id', ivhCode 'nomor',DATE_FORMAT(ivhDate,'%d-%m-%Y') 'tanggal',knama 'kontak'         
                     from official_nmw.ops_invoice_detail 
                     LEFT join official_nmw.ops_invoice_header on ivdIvhId=ivhId 
                    LEFT join bitem on iid = ivdIID   
                    LEFT join official_nmw.ops_payment on payIvhCode=ivhCode 
                    LEFT join official_nmw.ops_medical on medId=ivhMedId 
                    LEFT join official_nmw.data_users on usrId=medCreateId    
                    LEFT join bkontak on kid=ivhkid   
                        ";
                    
        $search = array('ivhCode','knama');
        $where  = null;         
        $isWhere = " payStatus = 1 And ivhStatusDIAS = 0 and ivhKID='".$idkontak."' and ivhGID='".$cabang."'     ";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }       

   function view_web_blm_iv($idkontak) { 
       
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT DISTINCT medid 'id', medcode 'nomor',DATE_FORMAT(rsddate,'%d-%m-%Y') 'tanggal',pasien.knama 'kontak'       
                     from   official_nmw.ops_medical
                          LEFT join official_nmw.ops_medical_medicine on medid=mdcmedid 
                          LEFT join official_nmw.ops_invoice_header on medId=ivhMedId 
                          LEFT join official_nmw.ops_reservation on rsvMedId=medId 
                          LEFT join official_nmw.ops_reservation_type on rsvRtpId=rtpId    
                          LEFT join official_nmw.ops_payment on payIvhCode=ivhCode 
                          LEFT join bkontak pasien on medKID =pasien.kid 
                          LEFT JOIN official_nmw.ops_reservation_date  ON rsvRsdId = rsdId  
                          LEFT join official_nmw.ops_invoice_detail on ivdIvhId=IvhId     
                        ";
                    
        $search = array('medcode','knama');
        $where  = null;         
        $isWhere = " (coalesce( payStatus ,0) = 0 or (coalesce(payStatus ,0)=1 and coalesce(ivdIID ,0)=2560 )) 
                        and TIMESTAMPDIFF(MONTH, rsddate, NOW())<=1 and medKID='".$idkontak."'   ";
  
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }         

   function view_web_blm_iv_2($idkontak) { 
       
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT  medid 'id', medcode 'nomor',DATE_FORMAT(rsddate,'%d-%m-%Y') 'tanggal',pasien.knama 'kontak'  , inama 'kodeitem', mdcqty  'qty'    
                     from   official_nmw.ops_medical
                          LEFT join official_nmw.ops_medical_medicine on medid=mdcmedid 
                          LEFT join official_nmw.ops_invoice_header on medId=ivhMedId 
                          LEFT join official_nmw.ops_reservation on rsvMedId=medId 
                          LEFT join official_nmw.ops_reservation_type on rsvRtpId=rtpId    
                          LEFT join official_nmw.ops_payment on payIvhCode=ivhCode 
                          LEFT join bkontak pasien on medKID =pasien.kid 
                          LEFT JOIN official_nmw.ops_reservation_date  ON rsvRsdId = rsdId  
                          LEFT join official_nmw.ops_invoice_detail on ivdIvhId=IvhId 
                          INNER join bitem on bitem.iid = ops_medical_medicine.mdcIID
                        ";
                    
        $search = array('medcode','knama');
        $where  = null;         
        $isWhere = "  coalesce(ikode,'') <> '' and  (coalesce( payStatus ,0) = 0 or (coalesce(payStatus ,0)=1 and coalesce(ivdIID ,0)=2560 )) 
                        and TIMESTAMPDIFF(MONTH, rsddate, NOW())<=1 and medKID='".$idkontak."'   ";
  
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }       

}