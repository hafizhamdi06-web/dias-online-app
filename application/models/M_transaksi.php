<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_transaksi extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function get_data_query($query)
    {
        $sql = $this->db->query($query);        
        $data = $sql->result_array();
        
        $callback = array(    
            'data'=>$data
        );
        return json_encode($callback);
    }

    function get_data_query_second($query1,$query2)
    {

        $sql = $this->db->query($query1);        
        $data = $sql->result_array();

        $sql2 = $this->db->query($query2);        
        $data2 = $sql2->result_array();        

        $callback = array(    
            'data'=>$data,
            'data2'=>$data2            
        );
        return json_encode($callback);
    }

    function prefixtrans($id){
        $pref='';
        $this->db->select('nkode');
        $sql = $this->db->get_where("aanomor","nid='".$id."'");
        foreach ($sql->result() as $res) {
            $pref = $res->nkode;
        }        
        return $pref;
    }   

    function get_table_field()
    {
        $transcode = $_POST['jenis'];            

        if(empty($transcode)) $transcode = element('Fina_Jurnal_Umum',NID);

        $query  = "SELECT A.ntabel,A.nfldid,A.nfldsumber,A.nfldnotransaksi,A.nfldtanggal,A.nfldkontak,A.nflduraian,
                          A.nfldtotaltrans   
                     FROM aanomor A WHERE A.nid = '".$transcode."'";
        $sql = $this->db->query($query);        
        $data = $sql->row_array();        
        return $data;
    }

    function column_value($select,$table,$where){
        $this->db->select($select);
        return $this->db->get_where($table,$where);     
    }  

}