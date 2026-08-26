<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Kontak extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'kkode' => $_POST['kode'],
                'knama' => $_POST['nama'],
                'ktipe' => $_POST['tipe'],
                'k1alamat' => $_POST['alamat1'],
                'k1kodepos' => $_POST['kodepos1'],   
                'k1telp1' => $_POST['telp1'],    
                'k1fax' => $_POST['faks1'],  
                'k1email' => $_POST['email1'],
                'k1kontak' => $_POST['kontak1'],    
                'kpemtermin' => $_POST['terminbeli'],
                'kpentermin' => $_POST['terminjual'],
                'kpemkaryawan' => $_POST['bagbeli'],
                'kpenkaryawan' => $_POST['bagjual'],                
                'kpenpenagihan' => $_POST['bagtagih'],
                'kpenlevelharga' => $_POST['lvlharga'], 
                'knpwp' => $_POST['npwp'],                               
                'kpkp' => $_POST['pkp'],
                'kpembatashutang' => $_POST['batashutang'],                
                'kpenbataspiutang' => $_POST['bataspiutang'],
                'kdiskon' => $_POST['diskon'],
                'kjeniskelamin' => $_POST['kelamin'],
                'kmatauang' => $_POST['matauang'], 
                'kbank' => $_POST['bank'],
                'knoac' => $_POST['norekbank'],
                'kpemrekhutang' => $_POST['namarek'],                                                                
                'kmodifu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->where('kid',$id);        
        $this->db->update('bkontak',$data);

        $d = json_decode($_POST['saldoawal']);
        //return sizeof($d);

        // Hapus saldo awal di einvoicepenjualanu dan ctransaksiu
        $query = "SELECT A.ipuid 
                    FROM einvoicepenjualanu A  
                   WHERE A.ipukontak='".$id."' AND A.ipusaldoawal=1";
        $hasil = $this->db->query($query);
        foreach ($hasil->result() as $row) {
            $query = "CALL SP_JURNAL_INVOICE_PENJUALAN_DEL('".$row->ipuid."')";
            $this->db->query($query);
            $this->db->where('ipuid', $row->ipuid);
            $this->db->delete('einvoicepenjualanu');
        }

        foreach($d as $item){ 
            $this->tambahsaldoawal($id,$item->nomor,$item->tanggal,$item->termin,$item->jumlah,$item->catatan);
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
        $id = $_POST['id'];

        $this->db->trans_start();

        // Hapus saldo awal di einvoicepenjualanu dan ctransaksiu
        $query = "SELECT A.ipuid 
                    FROM einvoicepenjualanu A  
                   WHERE A.ipukontak='".$id."' AND A.ipusaldoawal=1";
        $hasil = $this->db->query($query);
        foreach ($hasil->result() as $row) {
            $query = "CALL SP_JURNAL_INVOICE_PENJUALAN_DEL('".$row->ipuid."')";
            $this->db->query($query);
            $this->db->where('ipuid', $row->ipuid);
            $this->db->delete('einvoicepenjualanu');
        }
        $this->db->where('kid', $id);
        $this->db->delete('bkontak');
        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }    	
    }

    function tambahData()
    {
        $data = array(
                'kkode' => $_POST['kode'],
                'knama' => $_POST['nama'],
                'ktipe' => $_POST['tipe'],
                'k1alamat' => $_POST['alamat1'],
                'k1kodepos' => $_POST['kodepos1'],   
                'k1telp1' => $_POST['telp1'],    
                'k1fax' => $_POST['faks1'],  
                'k1email' => $_POST['email1'],
                'k1kontak' => $_POST['kontak1'],    
                'kpemtermin' => $_POST['terminbeli'],
                'kpentermin' => $_POST['terminjual'],
                'kpemkaryawan' => $_POST['bagbeli'],
                'kpenkaryawan' => $_POST['bagjual'],                
                'kpenpenagihan' => $_POST['bagtagih'],
                'kpenlevelharga' => $_POST['lvlharga'], 
                'knpwp' => $_POST['npwp'],                               
                'kpkp' => $_POST['pkp'],
                'kpembatashutang' => $_POST['batashutang'],                
                'kpenbataspiutang' => $_POST['bataspiutang'],
                'kdiskon' => $_POST['diskon'],
                'kjeniskelamin' => $_POST['kelamin'],
                'kmatauang' => $_POST['matauang'], 
                'kbank' => $_POST['bank'],
                'knoac' => $_POST['norekbank'],
                'kpemrekhutang' => $_POST['namarek'],                                                                               
                'kcreateu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->insert('bkontak',$data);
        $id = $this->db->insert_id();
        // Saldo Awal
        $d = json_decode($_POST['saldoawal']);
        //return sizeof($d);
        foreach($d as $item){ 
            $this->tambahsaldoawal($id,$item->nomor,$item->tanggal,$item->termin,$item->jumlah,$item->catatan);
        }

        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function tambahsaldoawal($kid,$nomor,$tgl,$termin,$jumlah,$catatan) {
            if(empty($nomor) || $nomor=="") $nomor = $this->autonumber($tgl);

            // Insert Header Trans
            $data_header = array(
                            'ipusumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID)),
                            'iputanggal' => tgl_database($tgl),                        
                            'ipunotransaksi' => $nomor,
                            'ipukontak' => $kid,
                            'ipuuraian' => $catatan,
                            'iputotaltransaksi' => $jumlah,
                            'ipusaldoawal' => 1,           
                            'iputermin' => $termin,           
                            'ipucreateu' => $this->session->id                
            );        
            $this->db->insert('einvoicepenjualanu',$data_header);
            $id = $this->db->insert_id();

            $query = "CALL SP_JURNAL_SALDO_AWAL_TAGIHAN_ADD('".$id."')";
            $this->db->query($query);

            // USERLOG
            $uactivity = _anomor(element('Saldo_Awal_Tagihan',NID));
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
        $nomor1 = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Tagihan',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT IFNULL(MAX(RIGHT(ipunotransaksi,4)),0) as 'maks' 
                  FROM einvoicepenjualanu 
                 WHERE LEFT(ipunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
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

}