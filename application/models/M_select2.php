<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_select2 extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function get_select_query($query,$cari,$iswhere,$isorder,$limit=20)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars(@$_POST['search']);

        // Jumlah total baris kandidat (belum difilter kata kunci)
        $baseQuery = !empty($iswhere) ? $query." WHERE  $iswhere " : $query;
        $countRes  = $this->db->query("SELECT COUNT(*) AS n FROM (".$baseQuery.") x");
        $total     = ($countRes && is_object($countRes)) ? (int) $countRes->row()->n : 0;

        $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";

        // Untuk mengambil nama field yg menjadi acuan untuk sorting
        $order_field = $isorder;
        $order = " ORDER BY ".$order_field." ASC";  // limit 10

        // Aturan limit:
        //  - $limit <= 0        => selalu tampil semua (tanpa LIMIT)
        //  - data <= 100 baris  => tampil semua (tanpa LIMIT)
        //  - data > 100 baris   => batasi $limit baris (default 20)
        $limitClause = (empty($limit) || (int)$limit <= 0 || $total <= 100)
                     ? ''
                     : ' LIMIT '.(int)$limit;

        if(!empty($iswhere))
        {
            $sql_data = $this->db->query($query." WHERE $iswhere AND (".$cari.")".$order.$limitClause);
        }else{
            $sql_data = $this->db->query($query." WHERE (".$cari.")".$order.$limitClause);
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