<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Select_Master extends CI_Controller {

   function __construct() { 
      parent::__construct();
      if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
      }       
      $this->load->model('M_select2');
   }

   function view_tabel_list() {
        header('Content-Type: application/json');
        echo $this->M_select2->get_tabel_db();
   } 

   function view_field_list() {
        header('Content-Type: application/json');
        echo $this->M_select2->get_field_tabel();
   }    

   function view_coa_tipe() {
        $query  = "SELECT A.cgid AS 'id',A.cgnama AS 'text',null AS 'kode' 
                     FROM bcoagrup A";
        $search = array('cgnama');
        $isOrder = 'cgid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
   } 
 

   function view_mata_uang() {
        $query  = "SELECT A.uid AS 'id',CONCAT_WS(' - ',A.ukode,A.unama) AS 'text',null AS 'kode' 
                     FROM buang A";
        $search = array('ukode');
        $isOrder = 'uid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_divisi() {
        $query  = "SELECT A.did AS 'id',A.dnama AS 'text',null AS 'kode' 
                     FROM bdivisi A";
        $search = array('dnama');
        $isOrder = 'did';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_divisi_kode() {
        $query  = "SELECT A.did AS 'id',A.dkode AS 'text',null AS 'kode' 
                     FROM bdivisi A";
        $search = array('dkode');
        $isOrder = 'did';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }        

   function view_bank() {
        $query  = "SELECT A.bid AS 'id',A.bkode AS 'text',null AS 'kode' 
                     FROM bbank A";
        $search = array('bkode');
        $isOrder = 'bid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }       

   function view_bank2() {
        $query  = "SELECT A.bkode AS 'id',A.bkode AS 'text',null AS 'kode' 
                     FROM bbank A";
        $search = array('bkode');
        $isOrder = 'bkode';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }        

   function view_coa() {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode'
                     FROM bcoa A";
        $search = array('cnocoa','cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_coa_kasmasuk() {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode'
                     FROM bcoa A";
        $search = array('cnocoa','cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.cgd='D' AND A.ckasmasuk=1";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_coa_kaskeluar() {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode'
                     FROM bcoa A";
        $search = array('cnocoa','cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.cgd='D' AND A.ckaskeluar=1";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_coa_kas() {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa','cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.ctipe=0 AND A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }           

   function view_coa_bank() {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa','cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.ctipe=1 AND A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrditer);
    }               

   function view_coa_kasbank() {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa','cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.ctipe in(0,1) AND A.cgd='D'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }               

   function view_coa_induk() {
        $query  = "SELECT A.cid AS 'id',A.cnama AS 'text',A.cnocoa AS 'kode' 
                     FROM bcoa A";
        $search = array('cnocoa','cnama');
        $isOrder = 'cnocoa';
        $isWhere = "A.cgd = 'G'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }       

   function view_satuan() {
        $query  = "SELECT A.sid AS 'id',A.skode AS 'text',null AS 'kode' 
                     FROM bsatuan A";
        $search = array('snama');
        $isOrder = 'sid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }           

   function view_termin() {
        $query  = "SELECT A.tid AS 'id',A.tnama AS 'text',null AS 'kode' 
                     FROM btermin A";
        $search = array('tnama');
        $isOrder = 'tid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }               

   function view_kategori_item() {
        $query  = "SELECT A.ikid AS 'id',A.ikid AS 'text',null AS 'kode' 
                     FROM bitemkategori A";
        $search = array('ikid');
        $isOrder = 'ikid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }           

   function view_subkategori_item() {
        $query  = "SELECT A.iskid AS 'id',A.iskid AS 'text',null AS 'kode' 
                     FROM bitemsubkategori A";
        $search = array('iskid');
        $isOrder = 'iskid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }     

   function view_item() {
       
        $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT A.iid AS 'id',A.inama AS 'text',A.ikode AS 'kode' 
                     FROM bitem A";
        $search = array('ikode','inama');
        $isOrder = 'ikode';
        $isWhere = null;
        $isWhere = "istatus=0 and icabang like '%|".$cabang."|%'  ";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }           

   function view_kategori_kontak() {
        $query  = "SELECT A.ktid AS 'id',A.ktnama AS 'text',null AS 'kode' 
                     FROM bkontaktipe A";
        $search = array('ktnama');
        $isOrder = 'ktid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                   

   function view_kelompok_aktiva() {
        $query  = "SELECT A.akid AS 'id',A.aknama AS 'text',null AS 'kode' 
                     FROM baktivakelompok A";
        $search = array('aknama');
        $isOrder = 'akid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                       

   function view_proyek_kode() {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bproyek A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                   

   function view_proyek() {
        $query  = "SELECT A.pid AS 'id',A.pnama AS 'text',A.pkode AS 'kode' 
                     FROM bproyek A";
        $search = array('pkode','pnama');
        $isOrder = 'pid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                       

   function view_pajak() {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bpajak A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                       

   function view_pajak_ppn() {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bpajak A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = 'ptipe=1';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                       

   function view_pajak_pph() {
        $query  = "SELECT A.pid AS 'id',A.pkode AS 'text',null AS 'kode' 
                     FROM bpajak A";
        $search = array('pkode');
        $isOrder = 'pid';
        $isWhere = 'ptipe=2';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                       

   function view_gudang() {
        $query  = "SELECT A.gid AS 'id',A.gnama AS 'text',null AS 'kode',A.galamat1 AS 'nomor'
                     FROM bgudang A";
        $search = array('gnama');
        $isOrder = 'gid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_gudang_pilihan() {
        $ucabangpilih = '';
        $sql = "SELECT UCABANGPILIH FROM auser WHERE UID='".$this->session->id."'";
        $res = $this->db->query($sql);
        foreach ($res->result() as $row) {
            $ucabangpilih = $row->UCABANGPILIH;
        }

        $query  = "SELECT A.gid AS 'id',A.gnama AS 'text',null AS 'kode',A.galamat1 AS 'nomor'
                     FROM bgudang A";
        $search = array('gnama');
        $isOrder = 'gid';
        $isWhere = !empty($ucabangpilih) ? "A.gid IN (".$ucabangpilih.")" : null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_user() {
        $query  = "SELECT A.uid AS 'id',CONCAT(A.ukode,' - ',A.unama) AS 'text',A.ukode AS 'kode'
                     FROM auser A";
        $search = array('A.ukode','A.unama');
        $isOrder = 'A.unama';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_user_approver() {
        $role = $this->input->post('role') ? $this->input->post('role') : 'Approve Edit Depo Overdue';
        $query  = "SELECT A.uid AS 'id',CONCAT(A.ukode,' - ',COALESCE(A.unamalengkap,A.unama)) AS 'text',A.ukode AS 'kode'
                     FROM auser A
               INNER JOIN aauserrole B ON A.uid=B.AURIDUSER AND B.AURSTATUS=1
               INNER JOIN aarole C ON B.AURIDROLE=C.ARID AND C.ARNAMAROLE='".$this->db->escape_str($role)."'";
        $search = array('A.ukode','A.unama','A.unamalengkap');
        $isOrder = 'A.unama';
        $isWhere = "A.uactive=1 AND A.uid!='".$this->session->id."'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_jenis_lain() {
        $query  = "SELECT DISTINCT A.ltipe AS 'id',A.ltipe AS 'text',null AS 'kode'
                     FROM blain A";
        $search = array('ltipe');
        $isOrder = 'ltipe';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_jenis_permintaan() {
        $query  = "SELECT A.lid AS 'id',A.lnama AS 'text',A.lgudangid AS 'kode',A.lgudangnama AS 'gudangnama'
                     FROM blain A";
        $search = array('lnama');
        $isOrder = 'lnama';
        $isWhere = "A.ltipe='Jenis Permintaan'";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_namapt() {
        $query  = "SELECT A.npid AS 'id',A.npnama AS 'text',null AS 'kode' 
                     FROM bnamapt A";
        $search = array('npnama');
        $isOrder = 'npid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                          

   function view_jenis_penyesuaian_barang() {
        $query  = "SELECT A.jid AS 'id',A.jnama AS 'text',null AS 'kode' 
                     FROM bjenispenyesuaian A";
        $search = array('jnama');
        $isOrder = 'jid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                               

   function view_karyawan() {
        $query  = "SELECT A.kid AS 'id',A.knama AS 'text',null AS 'kode' 
                     FROM bkontak A";
        $search = array('knama');
        $isOrder = 'kid';
        $isWhere = " A.ktipe=4";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }  

   function view_tahun_periode() {
        $query  = "SELECT A.pid AS 'id',A.ptahun AS 'text',null AS 'kode' 
                     FROM cperiode A";
        $search = array('ptahun');
        $isOrder = 'ptahun';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    } 

   function view_tahun_periode2() {
        $query  = "SELECT A.ptahun AS 'id',A.ptahun AS 'text',null AS 'kode' 
                     FROM cperiode A";
        $search = array('ptahun');
        $isOrder = 'ptahun';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }     

   function view_parent_menu() {
        $query  = "SELECT A.mid AS 'id',A.mnama AS 'text',null AS 'kode' 
                     FROM aamenu A";
        $search = array('mnama');
        $isOrder = "mid";
        $isWhere = "mparent=0 AND mtype=".$_POST['tipe'];
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    } 

   function view_menu_list() {
        $query  = "SELECT A.mid AS 'id',A.mnama AS 'text',null AS 'kode' 
                     FROM aamenu A";
        $search = array('mnama');
        $isOrder = "murutan";
        $isWhere = "mlink<>'null' AND mlink<>''";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }     

   function view_daftar_laporan() {
        $query  = "SELECT A.arid AS 'id',A.arname AS 'text',null AS 'kode' 
                     FROM aareport A";
        $search = array('arname');
        $isOrder = 'arid';
        $isWhere = 'aractive=1';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }     

   function view_jenis_transaksi() {
        $nfa = @$_POST['nfa'];
        $query  = "SELECT A.nid AS 'id',A.nketerangan AS 'text',A.nkode AS 'kode' 
                     FROM aanomor A";
        $search = array('nketerangan','nkode');
        $isOrder = 'nid';
        if(!empty($nfa)){
            $isWhere = 'A.nfa='.$nfa;
        }else{  
            $isWhere = null;
        }
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }     

   function view_kontak() {
        $query  = "SELECT A.kid AS 'id',A.knama AS 'text', CONCAT(A.kkode,' ( ',B.ktnama, ' ) ') AS 'kode'  
                     FROM bkontak A LEFT JOIN bkontaktipe B ON A.ktipe=B.ktid ";
        $search = array('kkode','knama');
        $isOrder = 'kkode';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }      

   function view_paket() {  
        $query  = "SELECT A.puid AS 'id',A.pukode AS 'text', CONCAT(A.punama,'') AS 'kode'  
                     FROM epaketu A  ";
        $search = array('pukode','punama');
        $isOrder = 'pukode';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }        

   function view_promo() {  
        $query  = "SELECT B.mpdid AS 'id',A.mpukode AS 'text', CONCAT(A.mpunama,'') AS 'kode'  
                     FROM emasterpromou A left join emasterpromod B on B.mpdidu=A.mpuid  ";
        $search = array('mpukode','mpunama');
        $isOrder = 'mpukode';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }          

   function view_merchant() {  
        $query  = "SELECT A.mckode AS 'id',A.mckode AS 'text', A.mckode AS 'kode'  
                     FROM bmerchant A  ";
        $search = array('mckode');
        $isOrder = 'mckode';
        $isWhere = 'MCID<>0';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }      

   function view_klinikluar() {
        $query  = "SELECT A.kid AS 'id',A.knama AS 'text', CONCAT(A.kkode,' ( ',B.ktnama, ' ) ') AS 'kode'  
                     FROM bkontak A LEFT JOIN bkontaktipe B ON A.ktipe=B.ktid ";
        $search = array('kkode','knama');
        $isOrder = 'kkode';
        $isWhere = 'ktipe=25';
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }    

   function view_item_paket() {  
        $query  = "SELECT A.iid AS 'id', A.inama AS 'text',A.ikode AS 'kode' 
                     FROM bitem A";
        $search = array('ikode','inama');
        $isOrder = 'ikode';
        $isWhere = "istatus=0 and ".$_POST['tipe'] ;  
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }      
    
                                  

   function view_dokter() {
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT A.kid AS 'id',A.knama AS 'text',null AS 'kode' 
                     FROM bkontak A";
        $search = array('knama');
        $isOrder = 'kid';
        $isWhere = " A.ktipe=4 and A.KTAMPILDIDOKTER<>0 and A.kaktif<>0   "; //and A.kcabang = '".$cabang."' 
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                              

   function view_operator() {
       
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT A.kid AS 'id',A.knama AS 'text',null AS 'kode' 
                     FROM bkontak A";
        $search = array('knama');
        $isOrder = 'kid';
        $isWhere = " A.ktipe=4 and A.KTAMPILDIPERAWAT<>0 and A.kaktif<>0  and A.kcabang = '".$cabang."'   ";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }                            

   function view_pasien() {
       
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT A.kid AS 'id',concat(A.knama,' (',A.kidpasien,') ') AS 'text', A.knama AS 'kode' 
                     FROM bkontak A";
        $search = array('knama');
        $isOrder = ' case ktipe when 14 then 1 when 12 then 2 else 3 end ';
        $isWhere = "   A.kaktif<>0   ";
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    } 
    
             

   function view_kategori_itemkelompok2020() {
        $query  = "SELECT A.ik2id AS 'id',A.ik2kode AS 'text',null AS 'kode' 
                     FROM bitemkelompok2020 A";
        $search = array('ik2kode');
        $isOrder = 'ik2id';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }
    
             

   function view_jenisvoucher() {
        $query  = "SELECT A.lid AS 'id',A.lkode AS 'text',null AS 'kode' 
                     FROM blain A";
        $search = array('lkode');
        $isOrder = 'lkode';
        $isWhere = " LTIPE = 'Jenis Voucher' " ;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_coa_tipe_pendapatan() {
        $query  = "SELECT A.ctid AS 'id',A.ctnama AS 'text',null AS 'kode'
                     FROM bcoatipe_pendapatan A";
        $search = array('ctnama');
        $isOrder = 'cttipeid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_itemjenis() {
        $query  = "SELECT A.jid AS 'id',A.jkode AS 'text',null AS 'kode'
                     FROM bitemjenis A";
        $search = array('jkode');
        $isOrder = 'jid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_itemkelompok2021() {
        $query  = "SELECT A.ik21id AS 'id',A.ik21kode AS 'text',null AS 'kode'
                     FROM bitemkelompok2021 A";
        $search = array('ik21kode');
        $isOrder = 'ik21id';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_itemkelompok2023() {
        $query  = "SELECT A.ik23id AS 'id',A.ik23kode AS 'text',null AS 'kode'
                     FROM bitemkelompok2023 A";
        $search = array('ik23kode');
        $isOrder = 'ik23kode';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_coa_tipe_perpt() {
        $query  = "SELECT A.ctid AS 'id',A.ctnama AS 'text',null AS 'kode'
                     FROM bcoatipe_perpt A";
        $search = array('ctnama');
        $isOrder = 'cttipeid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }

   function view_itemjenisweb() {
        $query  = "SELECT A.ijid AS 'id',A.ijkode AS 'text',null AS 'kode'
                     FROM bitemjenisweb A";
        $search = array('ijkode');
        $isOrder = 'ijid';
        $isWhere = null;
        header('Content-Type: application/json');
        echo $this->M_select2->get_select_query($query,$search,$isWhere,$isOrder);
    }



}
