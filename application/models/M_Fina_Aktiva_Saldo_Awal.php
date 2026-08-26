<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Fina_Aktiva_Saldo_Awal extends CI_Model {

    function __construct()
    {
        parent::__construct();
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

    function getaktiva($id)
    {
        $this->db->where('aid', $id);
        return $this->db->get('baktiva');
    }

    function create()
    {

        if(empty($_POST['nomor'])){
            $nomor = $this->autonumber($_POST['tgl']);
        }else{
            $nomor = $_POST['nomor'];
        }        

        // Insert Header Trans
        $data_header = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('Fina_Aktiva_Saldo_Awal',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $nomor,
                        'sukontak' => $_POST['kontak'],
                        'sutotaltransaksi' => 0,    
                        'sujenis' => 1,
                        'suuraian' => 'Saldo awal aktiva tetap',
                        'sucreateu' => $this->session->id                
        );        

        $this->db->trans_start();

        $this->db->insert('fasetu',$data_header);
        $id = $this->db->insert_id();

        //===================================================
        // Detil Aktiva Tetap

        $data = json_decode($this->input->post('data'));
        $r=1;
        $totalsaldo=0;
        foreach($data as $item){
            $dataaktiva = $this->getaktiva($item->data);
            foreach ($dataaktiva->result() as $row) {
                $aktiva = array(
                    'id' => $row->AID,
                    'nama' => $row->ANAMA,
                    'qty' => $row->AJMLAKTIVA,
                    'harga' => $row->AHARGABELI,
                    'akumulasi' => $row->AAKUMBEBAN                
                );
            }

            $detil = array(
                    'sdidsu' => $id,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('Fina_Aktiva_Saldo_Awal',NID)),                    
                    'sditem' => $aktiva['id'],
                    'sdmasuk' => $aktiva['qty'],
                    'sdmasukd' => $aktiva['qty'],                    
                    'sdharga' => $aktiva['harga'],
                    'sdcatatan' => $aktiva['nama'],
                    'sdakumulasi' => $aktiva['akumulasi'],
                    'sdjenis' => 1,                    
            );
            $this->db->insert('fasetd',$detil);                        

            $this->db->where('aid', $aktiva['id']);
            $this->db->update('baktiva',array('apin' => 1));        

            $r++;     
            $totalsaldo += ($aktiva['harga']-$aktiva['akumulasi']);       
        }

        $this->db->where('suid', $id);
        $this->db->update('fasetu',array('sutotaltransaksi' => $totalsaldo));        

        $sql="CALL SP_JURNAL_SALDO_AWAL_AKTIVA_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('Fina_Aktiva_Saldo_Awal',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                                 

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }        
    }

    function delete()
    {

    }

    function autonumber($tgl)
    {
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('Fina_Aktiva_Saldo_Awal',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT IFNULL(MAX(RIGHT(sunotransaksi,4)),0) as 'maks' 
                  FROM fasetu 
                 WHERE LEFT(sunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
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

}