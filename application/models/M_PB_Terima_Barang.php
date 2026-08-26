<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_PB_Terima_Barang extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];

        //Update Header Trans
        $data_header = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('PB_Terima_Barang',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $_POST['nomor'],
                        'sukontak' => $_POST['kontak'],
                        'suattention' => $_POST['person'],                           
                        'sukaryawan' => $_POST['karyawan'],
                        'sutermin' => $_POST['termin'],
                        'supajak' => $_POST['pajak'],                                                
                        'sunofakturpajak' => $_POST['nopajak'], 
                        'sutglpajak' => tgl_database($_POST['tglpajak']),
                        'sualamat' => $_POST['alamat'],
                        'sustatus' => $_POST['status'],
                        'sugudangtujuan' => $_POST['gudang'],                        
                        'sutotalpajak' => $_POST['totalpajak'],
                        'sutotaltransaksi' => $_POST['totaltrans'],    
                        'sutotaldp' => $_POST['totaldp'],    
                        'sutotalsisa' => $_POST['totalsisa'],
                        'sudpid' => $_POST['iddp'],                            
                        'sumodifu' => $this->session->id                
        );        
        $this->db->trans_start();

        $sqldp = "CALL SP_JURNAL_UM_PEMBELIAN_DEL(".$id.",0)";
        $this->db->query($sqldp);

        $sql = "CALL SP_JURNAL_TERIMA_BARANG_DEL(".$id.")";
        $this->db->query($sql);

        $this->db->where('suid', $id);
        $this->db->update('fstoku',$data_header);

        //Delete Old Detil Trans
        $this->db->where('sdidsu', $id);
        $this->db->delete('fstokd');

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            if(empty($item->gudang)){
                $gudangd = $_POST['gudang'];
            }else{
                $gudangd = $item->gudang;
            }            
            $data_detil = array(
                    'sdidsu' => $id,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('PB_Terima_Barang',NID)),                    
                    'sditem' => $item->item,
                    'sdmasuk' => $item->qty,
                    'sdmasukd' => $item->qty,                    
                    'sdharga' => $item->harga,
                    'sddiskon' => $item->diskon,
                    'sddiskonpersen' => $item->persen,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $item->satuan,
                    'sddiskonpersen' => $item->persen,                                        
                    'sdcatatan' => $item->catatan,
                    'sdsodid' => $item->noorder,
                    'sdgudang' => $gudangd,
                    'sdproyek' => $item->proyek                                        
            );
            $this->db->insert('fstokd',$data_detil);                        
            $r++;
        }


        // Jika ada DP -> Insert DP
        if($_POST['totaldp']>0){
            if(empty($_POST['nomordp'])){
                $nomordp = $this->autonumberdp($_POST['tgldp']);
                $data_dp = array(
                                'dptanggal' => tgl_database($_POST['tgldp']),                        
                                'dpnotransaksi' => $nomordp,
                                'dpkontak' => $_POST['kontak'],
                                'dpjumlah' => $_POST['totaldp'],                           
                                'dpjumlahv' => 0,
                                'dpketerangan' => $_POST['uraiandp'],
                                'dpsumber' => $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID)),
                                'dpcdp' => $_POST['coadp'],                                                        
                                'dpckas' => $_POST['coadpkas'],  
                                'dppajak' => $_POST['pajakdp'],                                                                 
                                'dppajakn' => $_POST['tdppajak'],                 
                                'dpnofaktupajak' => $_POST['fakturpajakdp'],                                                        
                                'dpcreateu' => $this->session->id               
                );        
                $this->db->insert('ddp',$data_dp);
                $iddp = $this->db->insert_id();            
                $data_iddp = array(
                    'sudpid' => $iddp
                );
                $this->db->where('suid', $id);
                $this->db->update('fstoku',$data_iddp);

                // Create Jurnal Uang Muka
                $sqldp = "CALL SP_JURNAL_UM_PEMBELIAN_ADD(".$iddp.")";
                $this->db->query($sqldp);

            }else{

                //Update DP
                $data_dp = array(
                                'dptanggal' => tgl_database($_POST['tgldp']),                        
                                'dpnotransaksi' => $_POST['nomordp'],
                                'dpkontak' => $_POST['kontak'],
                                'dpjumlah' => $_POST['totaldp'],                           
                                'dpjumlahv' => 0,
                                'dpketerangan' => $_POST['uraiandp'],
                                'dpsumber' => $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID)),
                                'dpcdp' => $_POST['coadp'],                                                        
                                'dpckas' => $_POST['coadpkas'],
                                'dppajak' => $_POST['pajakdp'],                                                                 
                                'dppajakn' => $_POST['tdppajak'],                 
                                'dpnofaktupajak' => $_POST['fakturpajakdp'],
                                'dpmodifu' => $this->session->id               
                );        

                $this->db->where('dpid', $_POST['iddp']);        
                $this->db->update('ddp',$data_dp);

                // Create Jurnal Uang Muka
                $sqldp = "CALL SP_JURNAL_UM_PEMBELIAN_ADD(".$_POST['iddp'].")";
                $this->db->query($sqldp);

            }                    
        }

        $sql = "CALL SP_JURNAL_TERIMA_BARANG_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Terima_Barang',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }

    }

    function tambahTransaksi()
    {
        if(empty($_POST['nomor'])){
            $nomor = $this->autonumber($_POST['tgl']);
        }else{
            $nomor = $_POST['nomor'];
        }        

        // Insert Header Trans
        $data_header = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('PB_Terima_Barang',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $nomor,
                        'sukontak' => $_POST['kontak'],
                        'suattention' => $_POST['person'],                           
                        'sukaryawan' => $_POST['karyawan'],
                        'sutermin' => $_POST['termin'],
                        'supajak' => $_POST['pajak'],                                                
                        'sunofakturpajak' => $_POST['nopajak'], 
                        'sutglpajak' => tgl_database($_POST['tglpajak']),
                        'sualamat' => $_POST['alamat'],
                        'sustatus' => 1,
                        'sugudangtujuan' => $_POST['gudang'],                        
                        'sutotalpajak' => $_POST['totalpajak'],
                        'sutotaltransaksi' => $_POST['totaltrans'],    
                        'sutotaldp' => $_POST['totaldp'],    
                        'sutotalsisa' => $_POST['totalsisa'],                            
                        'sucreateu' => $this->session->id               
        );        
        $this->db->trans_start();
        $this->db->insert('fstoku',$data_header);
        $id = $this->db->insert_id();

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            if(empty($item->gudang)){
                $gudangd = $_POST['gudang'];
            }else{
                $gudangd = $item->gudang;
            }

            $data_detil = array(
                    'sdidsu' => $id,
                    'sdurutan' => $r,
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('PB_Terima_Barang',NID)),                    
                    'sditem' => $item->item,
                    'sdmasuk' => $item->qty,
                    'sdmasukd' => $item->qty,                    
                    'sdharga' => $item->harga,
                    'sddiskon' => $item->diskon,
                    'sddiskonpersen' => $item->persen,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $item->satuan,
                    'sddiskonpersen' => $item->persen,                                        
                    'sdcatatan' => $item->catatan,
                    'sdsodid' => $item->noorder,
                    'sdgudang' => $gudangd,
                    'sdproyek' => $item->proyek                                        
            );
            $this->db->insert('fstokd',$data_detil);                        
            $r++;
        }

        // Jika ada DP -> Insert DP
        if($_POST['totaldp']>0){
            if(empty($_POST['nomordp'])){
                $nomordp = $this->autonumberdp($_POST['tgldp']);
            }else{
                $nomordp = $_POST['nomordp'];
            }                    
            $data_dp = array(
                            'dptanggal' => tgl_database($_POST['tgldp']),                        
                            'dpnotransaksi' => $nomordp,
                            'dpkontak' => $_POST['kontak'],
                            'dpjumlah' => $_POST['totaldp'],                           
                            'dpjumlahv' => 0,
                            'dpketerangan' => $_POST['uraiandp'],
                            'dpsumber' => $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID)),
                            'dpcdp' => $_POST['coadp'],                                                        
                            'dpckas' => $_POST['coadpkas'],  
                            'dppajak' => $_POST['pajakdp'],                                                                 
                            'dppajakn' => $_POST['tdppajak'],                 
                            'dpnofaktupajak' => $_POST['fakturpajakdp'],                                                        
                            'dpcreateu' => $this->session->id               
            );        
            $this->db->insert('ddp',$data_dp);
            $iddp = $this->db->insert_id();            

            $data_iddp = array(
                            'sudpid' => $iddp
            );
            $this->db->where('suid', $id);
            $this->db->update('fstoku',$data_iddp);

            // Create Jurnal Uang Muka
            $sqldp = "CALL SP_JURNAL_UM_PEMBELIAN_ADD(".$iddp.")";
            $this->db->query($sqldp);
        }

        // Create Jurnal Penerimaan Persediaan
        $sql = "CALL SP_JURNAL_TERIMA_BARANG_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('PB_Terima_Barang',NID));
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
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else {
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }
    }

    function hapusTransaksi(){

        $id = $this->input->post('id');
        $nomor = $this->input->post('nomor'); 

        $this->db->trans_start();

        $sqldp = "CALL SP_JURNAL_UM_PEMBELIAN_DEL(".$id.",1)";
        $this->db->query($sqldp);

        $sql = "CALL SP_JURNAL_TERIMA_BARANG_DEL(".$id.")";
        $this->db->query($sql);

        //hapus Header Transaksi
        $this->db->where('suid', $id);
        $this->db->delete('fstoku');

        //hapus Detil Transaksi
        $this->db->where('sdidsu', $id);
        $this->db->delete('fstokd');

        // USERLOG
        $uactivity = _anomor(element('PB_Terima_Barang',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0                                    
        );
        $this->db->insert('auserlog',$userlog);                               

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

    function hapusUangMuka(){
        $id = $this->input->post('id');
        
        $this->db->trans_start();

        $sqldp = "CALL SP_JURNAL_UM_PEMBELIAN_DEL(".$id.",1)";
        $this->db->query($sqldp);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

    function autonumber($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Terima_Barang',NID));
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

    function autonumberdp($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Uang_Muka_Pembelian',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT IFNULL(MAX(RIGHT(dpnotransaksi,4)),0) as 'maks' 
                  FROM ddp 
                 WHERE LEFT(dpnotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
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