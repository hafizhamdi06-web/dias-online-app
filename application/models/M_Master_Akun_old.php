<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Akun extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        $id = $this->input->post('id');
        $cuid = $this->input->post('cuid');

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

        // Update Saldo Awal
        if(!empty($cuid) || $cuid !== '')
        {
            $this->ubahsaldoawal($id,$cuid); 
        } else {
            if(!empty($this->input->post('saldoawal')) && $this->input->post('saldoawal') !== 0) {
                $this->tambahsaldoawal($id);
            }             
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

        //Tambahkan validasi Saldo Jika <> 0
        //Code here .......
        //End Validasi

        $this->db->trans_begin();
        $this->db->where('cid', $id);
        $this->db->delete('bcoa');

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
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

        // Saldo Awal Akun
        if(!empty($this->input->post('saldoawal')) && $this->input->post('saldoawal') !== 0) 
        {
            $this->tambahsaldoawal($cid);
        } 

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function tambahsaldoawal($cid) {
            $nomor = $this->autonumber($_POST['tglsa']);
            // Insert Header Trans
            $data_header = array(
                            'cusumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal',NID)),
                            'cunotransaksi' => $nomor,
                            'cutanggal' => tgl_database($_POST['tglsa']),
                            'cuuraian' => "SALDO AWAL",
                            'cutotaltrans' => abs($_POST['saldoawal']),
                            'custatus' => 0,
                            'cucreateu' => $this->session->id                
            );        
            $this->db->insert('ctransaksiu',$data_header);
            $id = $this->db->insert_id();

            // Insert Detil Trans
            $coabalance = $this->getcoabalance();

            if($this->input->post('saldoawal') > 0)
            {
                $data_detil = array(
                        'cdidu' => $id,
                        'cdurutan' => 1,
                        'cdnocoa' => $cid,
                        'cddebit' => abs($_POST['saldoawal']),
                        'cdkredit' => 0,
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                        
                $data_detil = array(
                        'cdidu' => $id,
                        'cdurutan' => 2,
                        'cdnocoa' => $coabalance,
                        'cddebit' => 0,                        
                        'cdkredit' => abs($_POST['saldoawal']),
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                                        
            } else {
                $data_detil = array(
                        'cdidu' => $id,
                        'cdurutan' => 1,
                        'cdnocoa' => $coabalance,
                        'cddebit' => abs($_POST['saldoawal']),                        
                        'cdkredit' => 0,
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                                        
                $data_detil = array(
                        'cdidu' => $id,
                        'cdurutan' => 2,
                        'cdnocoa' => $cid,
                        'cddebit' => 0,
                        'cdkredit' => abs($_POST['saldoawal']),
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                        
            }

            //Update saldo awal & cuid master akun
            $data = array(
                    'CSALDOAWALGL' => $_POST['saldoawal'],
                    'CSALDOAWALTGL' => tgl_database($_POST['tglsa']),
                    'CSALDOAWALCUID' => $id
            );
            $this->db->where('cid', $cid);
            $this->db->update('bcoa',$data);
            
            // USERLOG
            $uactivity = _anomor(element('Saldo_Awal',NID));
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

    function ubahsaldoawal($cid,$cuid)
    {
            // Update Header Trans
            $data_header = array(
                            'cutanggal' => tgl_database($_POST['tglsa']),
                            'cutotaltrans' => abs($_POST['saldoawal']),
                            'cumodifu' => $this->session->id                
            );        
            $this->db->where('cuid',$cuid);
            $this->db->update('ctransaksiu',$data_header);

            $this->db->where('cdidu', $cuid);
            $this->db->delete('ctransaksid');

            $coabalance = $this->getcoabalance();

            if($this->input->post('saldoawal') > 0)
            {
                $data_detil = array(
                        'cdidu' => $cuid,
                        'cdurutan' => 1,
                        'cdnocoa' => $cid,
                        'cddebit' => abs($_POST['saldoawal']),
                        'cdkredit' => 0,
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                        
                $data_detil = array(
                        'cdidu' => $cuid,
                        'cdurutan' => 2,
                        'cdnocoa' => $coabalance,
                        'cddebit' => 0,                        
                        'cdkredit' => abs($_POST['saldoawal']),
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                                        
            } else {
                $data_detil = array(
                        'cdidu' => $cuid,
                        'cdurutan' => 1,
                        'cdnocoa' => $coabalance,
                        'cddebit' => abs($_POST['saldoawal']),                        
                        'cdkredit' => 0,
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                                        
                $data_detil = array(
                        'cdidu' => $cuid,
                        'cdurutan' => 2,
                        'cdnocoa' => $cid,
                        'cddebit' => 0,
                        'cdkredit' => abs($_POST['saldoawal']),
                        'cddvisi' => $_POST['divisi']
                );
                $this->db->insert('ctransaksid',$data_detil);                        
            }

            $data = array(
                    'CSALDOAWALGL' => $_POST['saldoawal'],
                    'CSALDOAWALTGL' => tgl_database($_POST['tglsa'])
            );
            $this->db->where('cid', $cid);
            $this->db->update('bcoa',$data);
            
            // USERLOG
            $nomor = $this->getnomorjurnal($cuid);            
            $uactivity = _anomor(element('Saldo_Awal',NID));
            $uactivity = $uactivity['keterangan'];        
            $userlog = array(
                'uluser' => $this->session->id,
                'ulusername' => $this->session->nama,
                'ulcomputer' => $this->input->ip_address(),
                'ulactivity' => $uactivity.' '.$nomor,
                'ullevel'=> 2                                                                                    
            );
            $this->db->insert('auserlog',$userlog);                   
    }

    function autonumber($tgl)
    {
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('Saldo_Awal',NID));
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