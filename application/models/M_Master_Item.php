<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Item extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'ikode' => $_POST['kode'],
                'ibarcode' => $_POST['barcode'],                
                'inama' => $_POST['nama'],
                'isatuan' => $_POST['satuan'],
                'isatuand' => $_POST['satuand'],                
                'ijenisitem' => $_POST['jenis'],
                'itipeitem' => $_POST['tipe'],
                'istatus' => $_POST['status'],
                'istockminimal' => $_POST['stokmin'],
                'istockmaksimal' => $_POST['stokmaks'],
                'istockreorder' => $_POST['stokreorder'],
                'ihargabeli' => $_POST['hargabeli'],
                'ihargajual1' => $_POST['hargajual1'],                                                                                
                'ihargajual2' => $_POST['hargajual2'],
                'ihargajual3' => $_POST['hargajual3'],
                'ihargajual4' => $_POST['hargajual4'],
                'icoapersediaan' => $_POST['coapersediaan'],
                'icoapendapatan' => $_POST['coapendapatan'],
                'icoahpp' => $_POST['coahpp'],                                
                'imodifu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->where('iid',$id);        
        $this->db->update('bitem',$data);

        // Saldo awal
        $d = json_decode($_POST['saldoawal']);

        // Hapus saldo awal item di fstoku dan ctransaksiu
        $query = "SELECT A.suid 
                    FROM fstoku A INNER JOIN fstokd B ON A.suid=B.sdidsu 
                   WHERE B.sditem='".$id."' AND A.susaldoawal=1";
        $hasil = $this->db->query($query);
        foreach ($hasil->result() as $row) {
            $query = "CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_DEL('".$row->suid."')";
            $this->db->query($query);
            $this->db->where('suid', $row->suid);
            $this->db->delete('fstoku');
        }

        foreach($d as $item){ 
            $this->tambahsaldoawal($id,$item->nomor,$item->tanggal,$item->gudang,$item->harga,$item->qty,$item->kontak,$_POST['satuan']);
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

        // Hapus saldo awal item di fstoku dan ctransaksiu
        $query = "SELECT A.suid 
                    FROM fstoku A INNER JOIN fstokd B ON A.suid=B.sdidsu 
                   WHERE B.sditem='".$id."' AND A.susaldoawal=1";
        $hasil = $this->db->query($query);
        foreach ($hasil->result() as $row) {
            $query = "CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_DEL('".$row->suid."')";
            $this->db->query($query);
            $this->db->where('suid', $row->suid);
            $this->db->delete('fstoku');
        }

        $this->db->where('iid', $id);
        $this->db->delete('bitem');
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
                'ikode' => $_POST['kode'],
                'ibarcode' => $_POST['barcode'],                                
                'inama' => $_POST['nama'],
                'isatuan' => $_POST['satuan'],
                'isatuand' => $_POST['satuand'],                
                'ijenisitem' => $_POST['jenis'],
                'itipeitem' => $_POST['tipe'],
                'istatus' => $_POST['status'],
                'istockminimal' => $_POST['stokmin'],
                'istockmaksimal' => $_POST['stokmaks'],
                'istockreorder' => $_POST['stokreorder'],
                'ihargabeli' => $_POST['hargabeli'],
                'ihargajual1' => $_POST['hargajual1'],                                                                                
                'ihargajual2' => $_POST['hargajual2'],
                'ihargajual3' => $_POST['hargajual3'],
                'ihargajual4' => $_POST['hargajual4'],
                'icoapersediaan' => $_POST['coapersediaan'],
                'icoapendapatan' => $_POST['coapendapatan'],
                'icoahpp' => $_POST['coahpp'],
                'icreateu' => $this->session->id                
        );        
        $this->db->trans_start();
        $this->db->insert('bitem',$data);
        $iid = $this->db->insert_id();
        
        // Saldo Awal
        $d = json_decode($_POST['saldoawal']);
        //return sizeof($d);
        foreach($d as $item){ 
            $this->tambahsaldoawal($iid,$item->nomor,$item->tanggal,$item->gudang,$item->harga,$item->qty,$item->kontak,$_POST['satuan']);        
        }

        $this->db->trans_complete();
        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function tambahsaldoawal($iid,$nomor,$tgl,$gudang,$harga,$qty,$kontak,$satuan) {
            if(empty($nomor) || $nomor=="") $nomor = $this->autonumber($tgl);

            // Insert Header Trans
            $data_header = array(
                            'susumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan',NID)),
                            'sutanggal' => tgl_database($tgl),                        
                            'sunotransaksi' => $nomor,
                            'sukontak' => $kontak,
                            'suuraian' => 'SALDO AWAL',
                            'sugudangtujuan' => $gudang,                        
                            'sutotaltransaksi' => $harga*$qty,
                            'susaldoawal' => 1,                      
                            'sucreateu' => $this->session->id                
            );        
            $this->db->insert('fstoku',$data_header);
            $id = $this->db->insert_id();
            
            $data_detil = array(
                    'sdidsu' => $id,
                    'sdurutan' => 1,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan',NID)),                    
                    'sditem' => $iid,
                    'sdmasuk' => $qty,
                    'sdmasukd' => $qty,                    
                    'sdharga' => $harga,
                    'sdsatuan' => $satuan,
                    'sdsatuand' => $satuan,
                    'sdgudang' => $gudang
            );
            $this->db->insert('fstokd',$data_detil);                        

            $query = "CALL SP_JURNAL_SALDO_AWAL_STOK_ADD('".$id."')";
            $this->db->query($query);

            // USERLOG
            $uactivity = _anomor(element('Saldo_Awal_Persediaan',NID));
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
        $nomor1 = $this->M_transaksi->prefixtrans(element('Saldo_Awal_Persediaan',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT IFNULL(MAX(RIGHT(sunotransaksi,4)),0) as 'maks' 
                  FROM fstoku 
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