<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_STK_Penyesuaian_Barang extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];

        $sql="CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_DEL(".$id.")";
        $this->db->query($sql);

        $sql = "CALL SP_HITUNG_HPP_DEL(".$id.")";
        $this->db->query($sql);        

        //Update Header Trans
        $data_header = array(
                        'susumber' => $this->M_transaksi->prefixtrans(element('STK_Penyesuaian_Barang',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $_POST['nomor'],
                        'sukontak' => $_POST['kontak'],
                        'suuraian' => $_POST['uraian'],                        
                        'sustatus' => $_POST['status'],
                        'sujenispenyesuaian' => $_POST['jenis'],
                        'sunoref' => $_POST['noref'],
                        'sutotaltransaksi' => $_POST['total'],                        
                        'sumodifu' => $this->session->id               
        );        
        $this->db->trans_begin();
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
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('STK_Penyesuaian_Barang',NID)),                    
                    'sditem' => $item->item,
                    'sdmasuk' => $item->qtyin,
                    'sdmasukd' => $item->qtyin,                                        
                    'sdkeluar' => $item->qtyout,
                    'sdkeluard' => $item->qtyout,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $item->satuan,
                    'sdharga' => $item->harga,
                    'sdcatatan' => $item->catatan,
                    'sdgudang' => $gudangd,
                    'sdproyek' => $item->proyek                                        
            );
            $this->db->insert('fstokd',$data_detil);                        
            $r++;
        }

        $sql = "CALL SP_HITUNG_HPP_ADD(".$id.")";
        $this->db->query($sql);        

        $sql="CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('STK_Penyesuaian_Barang',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);            
        } else {
            $this->db->trans_commit();            
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
                        'susumber' => $this->M_transaksi->prefixtrans(element('STK_Penyesuaian_Barang',NID)),
                        'sutanggal' => tgl_database($_POST['tgl']),                        
                        'sunotransaksi' => $nomor,
                        'sukontak' => $_POST['kontak'],
                        'suuraian' => $_POST['uraian'],                        
                        'sustatus' => $_POST['status'],
                        'sujenispenyesuaian' => $_POST['jenis'], 
                        'sunoref' => $_POST['noref'],     
                        'sutotaltransaksi' => $_POST['total'],                                                
                        'sucreateu' => $this->session->id               
        );        
        $this->db->trans_begin();
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
                    'sdsumber' => $this->M_transaksi->prefixtrans(element('STK_Penyesuaian_Barang',NID)),                    
                    'sditem' => $item->item,
                    'sdmasuk' => $item->qtyin,
                    'sdmasukd' => $item->qtyin,                                        
                    'sdkeluar' => $item->qtyout,
                    'sdkeluard' => $item->qtyout,                    
                    'sdsatuan' => $item->satuan,
                    'sdsatuand' => $item->satuan,
                    'sdharga' => $item->harga,
                    'sdcatatan' => $item->catatan,
                    'sdgudang' => $gudangd,
                    'sdproyek' => $item->proyek                                        
            );
            $this->db->insert('fstokd',$data_detil);                        
            $r++;
        }

        $sql = "CALL SP_HITUNG_HPP_ADD(".$id.")";
        $this->db->query($sql);        

        $sql="CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_ADD(".$id.")";
        $this->db->query($sql);

        // USERLOG
        $uactivity = _anomor(element('STK_Penyesuaian_Barang',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                       

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            $callback = array(    
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);            
        } else {
            $this->db->trans_commit();            
            $callback = array(    
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);            
        }
    }

    function hapusTransaksi(){

        $id = $_POST['id'];
        $nomor = $_POST['nomor'];        

        $this->db->trans_begin();

        $sql="CALL SP_JURNAL_PENYESUAIAN_PERSEDIAAN_DEL(".$id.")";
        $this->db->query($sql);

        //hapus Header Transaksi
        $this->db->where('suid', $id);
        $this->db->delete('fstoku');

        //hapus Detil Transaksi
        $this->db->where('sdidsu', $id);
        $this->db->delete('fstokd');

        // USERLOG
        $uactivity = _anomor(element('STK_Penyesuaian_Barang',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                      

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }

    }

    function autonumber($tgl){
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('STK_Penyesuaian_Barang',NID));
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

}