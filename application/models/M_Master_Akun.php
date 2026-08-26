<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Akun extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        $id = $this->input->post('id');

        if(empty($_POST['subcoa']) || $_POST['subcoa'] == 'null' || $_POST['subcoa'] == '' ){
            $hParent = 0;
        } else {
            $hParent = 1;            
        }

        if(empty($_POST['uang'])){
            $uang = 1;
        } else {
            $uang = $_POST['uang'];
        }

        $data = array(
                'cnocoa' => $_POST['nomor'],
                'cnama' => $_POST['nama'],
                'ctipe' => $_POST['tipe'],
                'csubdari' => $hParent,                
                'cparent' => $_POST['subcoa'],                
                'cgd' => $_POST['gd'],
                'cuang' => $uang,
                'cbank' => $_POST['bank'],
                'cnoac' => $_POST['nomorbank'],
                'cdivisi' => $_POST['divisi'],
                'cactive' => $_POST['status'],                
                'cmodifu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->where('cid',$id);        
        $this->db->update('bcoa',$data);

        //delete old jurnal saldoawal ctransaksiu
        $jurnal = array(
                'cusumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Akun',NID)),
                'curekkas' => $id
        );
        $this->db->where($jurnal);        
        $this->db->delete('ctransaksiu');

        //delete old saldoawal di tabel bcoasa
        $this->db->where('cscoa',$id);        
        $this->db->delete('bcoasa');        

        $d = json_decode($_POST['saldoawal']);
        //return sizeof($d);
        foreach($d as $item){ 
            $jumlahsa = $item->jumlah;
            $tglsa = $item->tanggal;
            $kontaksa = $item->kontak;

            $datasa = array(
                    'cscoa' => $id,
                    'cskontak' => $kontaksa,
                    'csjumlah' => $jumlahsa,
                    'cstanggal' => tgl_database($tglsa) 
            );        
            $this->db->insert('bcoasa',$datasa);
            $this->tambahsaldoawal($id,$tglsa,$jumlahsa,$kontaksa,$_POST['tipe']);
        }        

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }    

    function hapusData()
    {
        $id = $this->input->post('id');

        $this->db->trans_start();
        //delete jurnal saldoawal ctransaksiu
        $jurnal = array(
                'cusumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Akun',NID)),
                'curekkas' => $id
        );
        $this->db->where($jurnal);        
        $this->db->delete('ctransaksiu');
        $this->db->where('cid', $id);
        $this->db->delete('bcoa');
        $this->db->where('cscoa', $id);
        $this->db->delete('bcoasa');        
        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }    	
    }

    function tambahData()
    {
        if(empty($_POST['subcoa']) || $_POST['subcoa'] == 'null' || $_POST['subcoa'] == '' ){
            $hParent = 0;
        } else {
            $hParent = 1;            
        }

        if(empty($_POST['uang']) || $_POST['uang'] == 'null'){
            $uang = 1;
        } else {
            $uang = $_POST['uang'];
        }

        $data = array(
                'cnocoa' => $_POST['nomor'],
                'cnama' => $_POST['nama'],
                'ctipe' => $_POST['tipe'],
                'csubdari' => $hParent,                
                'cparent' => $_POST['subcoa'],                
                'cgd' => $_POST['gd'],
                'cuang' => $uang,
                'cbank' => $_POST['bank'],
                'cnoac' => $_POST['nomorbank'],
                'cdivisi' => $_POST['divisi'],
                'cactive' => $_POST['status'],                                
                'ccreateu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->insert('bcoa',$data);
        $cid = $this->db->insert_id();

        $d = json_decode($_POST['saldoawal']);
        //return sizeof($d);
        foreach($d as $item){ 
            $jumlahsa = $item->jumlah;
            $tglsa = $item->tanggal;
            $kontaksa = $item->kontak;

            $datasa = array(
                    'cscoa' => $cid,
                    'cskontak' => $kontaksa,
                    'csjumlah' => $jumlahsa,
                    'cstanggal' => tgl_database($tglsa) 
            );        
            $this->db->insert('bcoasa',$datasa);
            $this->tambahsaldoawal($cid,$tglsa,$jumlahsa,$kontaksa,$_POST['tipe']);
        }        

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function tambahsaldoawal($cid,$tgl,$nilai,$kontak,$tipe) {
            $nomor = $this->autonumber($tgl);
            // Insert Header Trans
            $data_header = array(
                            'cusumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Akun',NID)),
                            'cunotransaksi' => $nomor,
                            'cutanggal' => tgl_database($tgl),
                            'cukontak' => $kontak,
                            'cuuraian' => "SALDO AWAL",
                            'curekkas' => $cid,
                            'cutotaltrans' => abs($nilai),
                            'custatus' => 0,
                            'cucreateu' => $this->session->id                
            );        
            $this->db->insert('ctransaksiu',$data_header);
            $id = $this->db->insert_id();

            // Insert Detil Trans
            $coabalance = $this->getcoabalance();

            if($tipe<=6){
                if($nilai > 0)
                {
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 1,
                            'cdnocoa' => $cid,
                            'cddebit' => abs($nilai),
                            'cdkredit' => 0
                    );
                    $this->db->insert('ctransaksid',$data_detil);                        
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 2,
                            'cdnocoa' => $coabalance,
                            'cddebit' => 0,                        
                            'cdkredit' => abs($nilai)
                    );
                    $this->db->insert('ctransaksid',$data_detil);                                        
                } else {
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 1,
                            'cdnocoa' => $coabalance,
                            'cddebit' => abs($nilai),                        
                            'cdkredit' => 0
                    );
                    $this->db->insert('ctransaksid',$data_detil);                                        
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 2,
                            'cdnocoa' => $cid,
                            'cddebit' => 0,
                            'cdkredit' => abs($nilai)
                    );
                    $this->db->insert('ctransaksid',$data_detil);                        
                }                
            }else{
                if($nilai > 0)
                {
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 1,
                            'cdnocoa' => $coabalance,
                            'cddebit' => abs($nilai),                        
                            'cdkredit' => 0
                    );
                    $this->db->insert('ctransaksid',$data_detil);                                        
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 2,
                            'cdnocoa' => $cid,
                            'cddebit' => 0,
                            'cdkredit' => abs($nilai)
                    );
                    $this->db->insert('ctransaksid',$data_detil);                        
                } else {
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 1,
                            'cdnocoa' => $cid,
                            'cddebit' => abs($nilai),
                            'cdkredit' => 0
                    );
                    $this->db->insert('ctransaksid',$data_detil);                        
                    $data_detil = array(
                            'cdidu' => $id,
                            'cdurutan' => 2,
                            'cdnocoa' => $coabalance,
                            'cddebit' => 0,                        
                            'cdkredit' => abs($nilai)
                    );
                    $this->db->insert('ctransaksid',$data_detil);                                        
                }                                
            }
            
            // USERLOG
            $uactivity = _anomor(element('Saldo_Awal_Akun',NID));
            $uactivity = $uactivity['keterangan'];        
            $userlog = array(
                'uluser' => $this->session->id,
                'ulusername' => $this->session->nama,
                'ulcomputer' => $this->input->ip_address(),
                'ulactivity' => $uactivity.' '.$nomor,
                'ullevel'=> 1                                                                                    
            );
            $this->db->insert('auserlog',$userlog);                                        
    }

    function autonumber($tgl)
    {
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Akun',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT IFNULL(MAX(RIGHT(cunotransaksi,4)),0) as 'maks' 
                  FROM ctransaksiu 
                 WHERE LEFT(cunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
        $query = $this->db->query($sql);
        foreach ($query->result() as $res) {
            $nomor = number_format($res->maks)+1;
        }

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
        
        return $nomor;
    }

    function getcoabalance()
    {
        $coa='';
        $where = array(
            'cckode' => 'RL',
            'ccketerangan' => 'BERJALAN'
        );
        $hasil = $this->db->get_where('cconfigcoa',$where);
        foreach ($hasil->result() as $row) {
            $coa = $row->CCCOA;
        }
        return $coa;
    }     

    function getnomorjurnal($cuid)
    {
        $where = array(
            'cuid' => $cuid
        );
        $hasil = $this->db->get_where('ctransaksiu',$where);
        foreach ($hasil->result() as $row) {
            return $row->CUNOTRANSAKSI;
        }        
        return 0;
    }         

}