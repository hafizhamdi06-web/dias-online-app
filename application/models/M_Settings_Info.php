<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Settings_Info extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
    	$id = $_POST['id'];
        $data = array(
                'inama' => $_POST['nama'],
                'ialamat1' => $_POST['alamat1'],
                'ialamat2' => $_POST['alamat2'],
                'ikota' => $_POST['kota'],
                'ipropinsi' => $_POST['propinsi'],
                'ikodepos' => $_POST['kodepos'],
                'inegara' => $_POST['negara'],
                'itelepon1' => $_POST['telp1'],   
                'itelepon2' => $_POST['telp2'],     
                'ifaks' => $_POST['faks'],
                'iemail' => $_POST['email'],                    
                'ibulanaktif' => $_POST['bulan'],                   
                'itahunaktif' => $_POST['tahun'],
                'icetakpos' => $_POST['icetakpos'],
                'ibarcodepos' => $_POST['ibarcodepos'],                   
                'ipajakpos' => $_POST['ipajakpos'],
                'ippnpos' => $_POST['ippnpos'],                
                'ikontakpos' => $_POST['ikontakpos'],
                'ipajakbeli' => $_POST['ipajakbeli'],
                'ippnbeli' => $_POST['ippnbeli'],
                'ipph22beli' => $_POST['ipph22beli'],                
                'ipajakjual' => $_POST['ipajakjual'],
                'ippnjual' => $_POST['ippnjual'],    
                'ipph22jual' => $_POST['ipph22jual'],
                'idivisi' => $_POST['idivisi'],
                'iproyek' => $_POST['iproyek'],                
                'isatuan' => $_POST['isatuan'],
                'imatauang' => $_POST['imatauang'],
                'idecimalqty' => $_POST['idecimalqty']                
        );        
        $this->db->trans_begin();
        $this->db->where('iid',$id);        
        $this->db->update('aainfo',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_begin();
        $this->db->where('iid', $id);
        $this->db->delete('aainfo');

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
        $data = array(
                'inama' => $_POST['nama'],
                'ialamat1' => $_POST['alamat1'],
                'ialamat2' => $_POST['alamat2'],
                'ikota' => $_POST['kota'],
                'ipropinsi' => $_POST['propinsi'],
                'ikodepos' => $_POST['kodepos'],
                'inegara' => $_POST['negara'],
                'itelepon1' => $_POST['telp1'],   
                'itelepon2' => $_POST['telp2'],     
                'ifaks' => $_POST['faks'],
                'iemail' => $_POST['email'],                    
                'ibulanaktif' => $_POST['bulan'],                   
                'itahunaktif' => $_POST['tahun'],
                'icetakpos' => $_POST['icetakpos'],
                'ibarcodepos' => $_POST['ibarcodepos'],                   
                'ipajakpos' => $_POST['ipajakpos'],
                'ippnpos' => $_POST['ippnpos'],                
                'ikontakpos' => $_POST['ikontakpos'],
                'ipajakbeli' => $_POST['ipajakbeli'],
                'ippnbeli' => $_POST['ippnbeli'],
                'ipph22beli' => $_POST['ipph22beli'],                
                'ipajakjual' => $_POST['ipajakjual'],
                'ippnjual' => $_POST['ippnjual'],    
                'ipph22jual' => $_POST['ipph22jual'],
                'idivisi' => $_POST['idivisi'],
                'iproyek' => $_POST['iproyek'],                
                'isatuan' => $_POST['isatuan'],
                'imatauang' => $_POST['imatauang'],
                'idecimalqty' => $_POST['idecimalqty']                
        );        
        $this->db->trans_begin();
        $this->db->insert('aainfo',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

    function simpanPeriode()
    {
        $data = array(
                'ptahun' => $_POST['tahun']
        );        
        $this->db->trans_begin();
        $this->db->insert('cperiode',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    

    function infoPerusahaan()
    {
        return $this->db->get('ainfo');
    }
}