<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_STK_Stok_Opname extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];
        //Update Header Trans
        $data_header = array(
                        'sousumber' => $this->M_transaksi->prefixtrans(element('STK_Stok_Opname',NID)),
                        'soutanggal' => tgl_database($_POST['tgl']),                        
                        'sounotransaksi' => $_POST['nomor'],
                        'soukontak' => $_POST['kontak'],
                        'soustatus' => $_POST['status'],                        
                        'sougudang' => $_POST['gudang'], 
                        'souuraian' => $_POST['uraian'],
                        'sounoref' => $_POST['noref'],
                        'soumodifu' => $this->session->id                
        );        
        $this->db->trans_begin();
        $this->db->where('souid', $id);
        $this->db->update('fstokopnameu',$data_header);

        //Delete Old Detil Trans
        $this->db->where('sodidsou', $id);
        $this->db->delete('fstokopnamed');

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'sodidsou' => $id,
                    'sodurutan' => $r,
                    'soditem' => $item->item,
                    'sodqty' => $item->qty,
                    'sodqtyd' => $item->qty,                    
                    'sodsatuan' => $item->satuan,
                    'sodsatuand' => $item->satuan,
                    'sodcatatan' => $item->catatan                               
            );

            $this->db->insert('fstokopnamed',$data_detil);
            $r++;
        }

        // USERLOG
        $uactivity = _anomor(element('STK_Stok_Opname',NID));
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
                        'sousumber' => $this->M_transaksi->prefixtrans(element('STK_Stok_Opname',NID)),
                        'soutanggal' => tgl_database($_POST['tgl']),                        
                        'sounotransaksi' => $nomor,
                        'soukontak' => $_POST['kontak'],
                        'soustatus' => $_POST['status'],                        
                        'sougudang' => $_POST['gudang'], 
                        'souuraian' => $_POST['uraian'],
                        'sounoref' => $_POST['noref'],                                                   
                        'soucreateu' => $this->session->id               
        );        
        $this->db->trans_begin();
        $this->db->insert('fstokopnameu',$data_header);
        $id = $this->db->insert_id();

        // Insert Detil Trans
        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'sodidsou' => $id,
                    'sodurutan' => $r,
                    'soditem' => $item->item,
                    'sodqty' => $item->qty,
                    'sodqtyd' => $item->qty,                    
                    'sodsatuan' => $item->satuan,
                    'sodsatuand' => $item->satuan,
                    'sodcatatan' => $item->catatan                               
            );

            $this->db->insert('fstokopnamed',$data_detil);                                    
            $r++;

        }

        // USERLOG
        $uactivity = _anomor(element('STK_Stok_Opname',NID));
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

        //hapus Header Transaksi
        $this->db->where('souid', $id);
        $this->db->delete('fstokopnameu');

        //hapus Detil Transaksi
        $this->db->where('sodidsou', $id);
        $this->db->delete('fstokopnamed');

        // USERLOG
        $uactivity = _anomor(element('STK_Stok_Opname',NID));
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
        $nomor1 = $this->M_transaksi->prefixtrans(element('STK_Stok_Opname',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT IFNULL(MAX(RIGHT(sounotransaksi,4)),0) as 'maks' 
                  FROM fstokopnameu 
                 WHERE LEFT(sounotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
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