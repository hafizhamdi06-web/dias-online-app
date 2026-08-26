<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_select2 extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function get_select_query($query,$cari,$iswhere,$isorder)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars(@$_POST['search']); 

        if(!empty($iswhere))
        {
            $sql = $this->db->query($query." WHERE  $iswhere ");
        }else{
            $sql = $this->db->query($query);
        }

        $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
        
        // Untuk mengambil nama field yg menjadi acuan untuk sorting
        $order_field = $isorder; 
        $order = " ORDER BY ".$order_field." ASC";  // limit 10

        if(!empty($iswhere))
        {                
            $sql_data = $this->db->query($query." WHERE $iswhere AND (".$cari.")".$order);
        }else{
            $sql_data = $this->db->query($query." WHERE (".$cari.")".$order);
        }

        $list = array();
        $key=0;
        foreach($sql_data->result_array() as $r){
            $list[$key]['id'] = $r['id'];
            $list[$key]['text'] = $r['text'];
            $list[$key]['kode'] = $r['kode'];            
            $key++;
        }

        return json_encode($list);
    }

    function get_tabel_db()
    {
        $sql_data = $this->db->list_tables();
        $list = array();
        $key=0;
        foreach($sql_data as $r){
            $list[$key]['id'] = $r;
            $list[$key]['text'] = strtoupper($r);
            $list[$key]['kode'] = $r;            
            $key++;
        }
        return json_encode($list);
    }

    function get_field_tabel()
    {
        $sql_data = $this->db->list_fields($this->input->post('tabel'));
        $list = array();
        $key=0;
        foreach($sql_data as $r){
            $list[$key]['id'] = strtoupper($r);
            $list[$key]['text'] = strtoupper($r);
            $list[$key]['kode'] = $r;            
            $key++;
        }
        return json_encode($list);
    }    

}