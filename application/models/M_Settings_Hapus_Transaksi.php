<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Settings_Hapus_Transaksi extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function getnomor($id,$table,$kolomid,$kolomnomor){
        $nomor = '';
        $this->db->where($kolomid, $id);
        $hasil = $this->db->get($table);

        foreach ($hasil->result() as $row) {
        	$nomor = $row->$kolomnomor;
        }					
        return $nomor;
    }

    function hapus()
    {
    	$tabel = _anomor($this->input->post('tipe'));

        $data = json_decode($this->input->post('data'));

        $this->db->trans_begin();

        foreach($data as $item){
        	$id = $item->data;
    		$nomor = $this->getnomor($id,$tabel['namatabel'],$tabel['kolomid'],$tabel['kolomnomor']);        	
            $this->db->where($tabel['kolomid'], $id);
	        $this->db->delete($tabel['namatabel']);

	        if($tabel['isjurnal']==1){
	            $this->db->where('cunotransaksi', $nomor);
	            $this->db->where('cusumber', $tabel['sumber']);	            
		        $this->db->delete('ctransaksiu');				
	        }        	

	        // USERLOG
	        $userlog = array(
	            'uluser' => $this->session->id,
	            'ulusername' => $this->session->nama,
	            'ulcomputer' => $this->input->ip_address(),
	            'ulactivity' => $tabel['keterangan'].' '.$nomor,
	            'ullevel'=> 0                                                                                    
	        );
	        $this->db->insert('auserlog',$userlog);                      	        
        }

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }

    }

}