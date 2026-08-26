<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_datatables_m extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

    function get_tables_query($query,$cari,$where,$iswhere,$isOrder=null)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars(@$_POST['search']['value']);
        // Ambil data limit per page
        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
        // Ambil data start
        $start =preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}"); 

        if($where != null)
        {
            $setWhere = array();
            foreach ($where as $key => $value)
            {
                $setWhere[] = $key."='".$value."'";
            }
            $fwhere = implode(' AND ', $setWhere);

            if(!empty($iswhere))
            {
                $sql = $this->db->query($query." WHERE  $iswhere AND ".$fwhere);
                
            }else{
                $sql = $this->db->query($query." WHERE ".$fwhere);
            }

            $sql_count = $sql->num_rows();

            $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
            

            if(empty($isOrder)){
                // Untuk mengambil nama field yg menjadi acuan untuk sorting
                $order_field = $_POST['order'][0]['column']; 
                // Untuk menentukan order by "ASC" atau "DESC"
                $order_ascdesc = $_POST['order'][0]['dir']; 
                $order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;
            } else {
                $order = " ORDER BY ".$isOrder;
            }

            if(!empty($iswhere))
            {
                $sql_data = $this->db->query($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
            }else{
                $sql_data = $this->db->query($query." WHERE ".$fwhere." AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
            }
            
            if(isset($search))
            {
                if(!empty($iswhere))
                {
                    $sql_cari =  $this->db->query($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")");
                }else{
                    $sql_cari =  $this->db->query($query." WHERE ".$fwhere." AND (".$cari.")");
                }
                $sql_filter_count = $sql_cari->num_rows();
            }else{
                if(!empty($iswhere))
                {
                    $sql_filter = $this->db->query($query." WHERE $iswhere AND ".$fwhere);
                }else{
                    $sql_filter = $this->db->query($query." WHERE ".$fwhere);
                }
                $sql_filter_count = $sql_filter->num_rows();
            }
            $data = $sql_data->result_array();

        }else{
            if(!empty($iswhere))
            {
                $sql = $this->db->query($query." WHERE  $iswhere ");
            }else{
                $sql = $this->db->query($query);
            }
            
            $sql_count = $sql->num_rows();

            $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
            
            if(empty($isOrder)){
                // Untuk mengambil nama field yg menjadi acuan untuk sorting
                $order_field = $_POST['order'][0]['column']; 
                // Untuk menentukan order by "ASC" atau "DESC"
                $order_ascdesc = $_POST['order'][0]['dir']; 
                $order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;                
            } else {
                $order = " ORDER BY ".$isOrder;
            }

            if(!empty($iswhere))
            {                
                $sql_data = $this->db->query($query." WHERE $iswhere AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
            }else{
                $sql_data = $this->db->query($query." WHERE (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
            }

            if(isset($search))
            {
                if(!empty($iswhere))
                {     
                    $sql_cari =  $this->db->query($query." WHERE $iswhere AND (".$cari.")");
                }else{
                    $sql_cari =  $this->db->query($query." WHERE (".$cari.")");
                }
                $sql_filter_count = $sql_cari->num_rows();
            }else{
                if(!empty($iswhere))
                {
                    $sql_filter = $this->db->query($query." WHERE $iswhere");
                }else{
                    $sql_filter = $this->db->query($query);
                }
                $sql_filter_count = $sql_filter->num_rows();
            }
            $data = $sql_data->result_array();
        }
        
        $callback = array(    
            'draw' => $_POST['draw'], // Ini dari datatablenya    
            'recordsTotal' => $sql_count,    
            'recordsFiltered'=>$sql_filter_count,    
            'data'=>$data
        );
        return json_encode($callback); // Convert array $callback ke json
    }
     

    function get_tables($tables,$cari,$iswhere)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars($_POST['search']['value']);
        // Ambil data limit per page
        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
        // Ambil data start
        $start =preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}"); 
        
        $query = $tables;
        
        if(!empty($iswhere)){
            $sql = $this->db->query("SELECT * FROM ".$query." WHERE ".$iswhere);
        }else{
            $sql = $this->db->query("SELECT * FROM ".$query);
        }

        $sql_count = $sql->num_rows();

        $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";

        
        // Untuk mengambil nama field yg menjadi acuan untuk sorting
        $order_field = $_POST['order'][0]['column']; 

        // Untuk menentukan order by "ASC" atau "DESC"
        $order_ascdesc = $_POST['order'][0]['dir']; 
        $order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;

        if(!empty($iswhere)){
            $sql_data = $this->db->query("SELECT * FROM ".$query." WHERE $iswhere AND (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
        }else{
            $sql_data = $this->db->query("SELECT * FROM ".$query." WHERE (".$cari.")".$order." LIMIT ".$limit." OFFSET ".$start);
        }

        if(isset($search))
        {
            if(!empty($iswhere)){
                $sql_cari =  $this->db->query("SELECT * FROM ".$query." WHERE $iswhere (".$cari.")");
            }else{
                $sql_cari =  $this->db->query("SELECT * FROM ".$query." WHERE (".$cari.")");
            }
            $sql_filter_count = $sql_cari->num_rows();
        }else{
            if(!empty($iswhere)){
                $sql_filter = $this->db->query("SELECT * FROM ".$query."WHERE ".$iswhere);
            }else{
                $sql_filter = $this->db->query("SELECT * FROM ".$query);
            }
            $sql_filter_count = $sql_filter->num_rows();
        }
        $data = $sql_data->result_array();

        $callback = array(    
            'draw' => $_POST['draw'], // Ini dari datatablenya    
            'recordsTotal' => $sql_count,    
            'recordsFiltered'=>$sql_filter_count,    
            'data'=>$data
        );
        return json_encode($callback); // Convert array $callback ke json
    }

    function get_tables_query_ledger()
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = "";
        // Ambil data limit per page
//        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
        // Ambil data start
//        $start =preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}"); 
       if(empty($_POST['akun'])){
         $akundari = 0;
       } else {
         $akundari = @$_POST['akun'];
       }

       if(empty($_POST['akunsampai'])){
         $akunsampai = 0;
       } else {
         $akunsampai = @$_POST['akunsampai'];        
       }
       if(empty($_POST['dari'])){
         $tgldari = '01-01-1600';       
       } else {
         $tgldari = @$_POST['dari'];
       }
       if(empty($_POST['sampai'])){
         $tglsampai = '01-01-1600';       
       } else {
         $tglsampai = @$_POST['sampai'];
       }


        $query = "CALL SP_HISTORI_BB(".$akundari.",".$akunsampai.",'".tgl_database($tgldari)."','".tgl_database($tglsampai)."')";        
        $this->db->query($query);

        $query = "CALL SP_HISTORI_BB_2('".tgl_database($tgldari)."',".$akundari.",".$akunsampai.")";
        $this->db->query($query);
        
        $query = "SELECT id,link,captionlink,nomor,sumber,DATE_FORMAT(tanggal,'%d-%m-%Y') 'tanggal',tanggal 'tgl',kontak,uraian,debit,kredit,saldo,idcoa,coa,cid FROM tmp_bb ORDER BY cid ASC,tgl ASC";
        $sql_data = $this->db->query($query);

        $data = $sql_data->result_array();

        $callback = array(    
            'draw' => @$_POST['draw'], // Ini dari datatablenya    
            'recordsTotal' => null,    
            'recordsFiltered'=>0,    
            'data'=>$data
        );
        return json_encode($callback); // Convert array $callback ke json
    }

    function get_tables_query_group($query,$cari,$where,$iswhere,$isGroup=null)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars(@$_POST['search']['value']);
        // Ambil data limit per page
        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
        // Ambil data start
        $start =preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}"); 

        if(!empty($iswhere))
        {
            $sql = $this->db->query($query." WHERE  $iswhere GROUP BY $isGroup ");
        }else{
            $sql = $this->db->query($query." GROUP BY $isGroup ");
        }
        
        $sql_count = $sql->num_rows();

        $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
        
        if(empty($isOrder)){
            // Untuk mengambil nama field yg menjadi acuan untuk sorting
            $order_field = $_POST['order'][0]['column']; 
            // Untuk menentukan order by "ASC" atau "DESC"
            $order_ascdesc = $_POST['order'][0]['dir']; 
            $order = " GROUP BY ".$isGroup." ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;                
        } else {
            $order = " GROUP BY ".$isGroup." ORDER BY ".$isOrder;
        }

        if(!empty($iswhere))
        {                
        $sql_data = $this->db->query($query." WHERE $iswhere AND (".$cari.") " .$order." LIMIT ".$limit." OFFSET ".$start);
        }else{
        $sql_data = $this->db->query($query." WHERE (".$cari.") ".$order." LIMIT ".$limit." OFFSET ".$start);
        }

        if(isset($search))
        {
            if(!empty($iswhere))
            {     
                $sql_cari =  $this->db->query($query." WHERE $iswhere AND (".$cari.") GROUP BY $isGroup ");
            }else{
                $sql_cari =  $this->db->query($query." WHERE (".$cari.") GROUP BY $isGroup ");
            }
            $sql_filter_count = $sql_cari->num_rows();
        }else{
            if(!empty($iswhere))
            {
                $sql_filter = $this->db->query($query." WHERE $iswhere GROUP BY $isGroup ");
            }else{
                $sql_filter = $this->db->query($query." GROUP BY $isGroup ");
            }
            $sql_filter_count = $sql_filter->num_rows();
        }
        $data = $sql_data->result_array();
        
        $callback = array(    
            'draw' => $_POST['draw'], // Ini dari datatablenya    
            'recordsTotal' => $sql_count,    
            'recordsFiltered'=>$sql_filter_count,    
            'data'=>$data
        );
        //return $sql_data;
        return json_encode($callback); // Convert array $callback ke json
    }
    
    
    
    
    
     function get_tables_query_tanpalimit($query,$cari,$where,$iswhere,$isOrder)
    {
        // Ambil data yang di ketik user pada textbox pencarian
        $search = htmlspecialchars(@$_POST['search']['value']);
        // Ambil data limit per page
        $limit = preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['length']}");
        // Ambil data start
        $start =preg_replace("/[^a-zA-Z0-9.]/", '', "{$_POST['start']}"); 

        if($where != null)
        {
            $setWhere = array();
            foreach ($where as $key => $value)
            {
                $setWhere[] = $key."='".$value."'";
            }
            $fwhere = implode(' AND ', $setWhere);

            if(!empty($iswhere))
            {
                $sql = $this->db->query($query." WHERE  $iswhere AND ".$fwhere);
                
            }else{
                $sql = $this->db->query($query." WHERE ".$fwhere);
            }

            $sql_count = $sql->num_rows();

            $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
            

            if(empty($isOrder)){
                // Untuk mengambil nama field yg menjadi acuan untuk sorting
                $order_field = $_POST['order'][0]['column']; 
                // Untuk menentukan order by "ASC" atau "DESC"
                $order_ascdesc = $_POST['order'][0]['dir']; 
                $order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;
            } else {
                $order = " ORDER BY ".$isOrder;
            }

            if(!empty($iswhere))
            {
                $sql_data = $this->db->query($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")".$order." LIMIT 20 ");
            }else{
                $sql_data = $this->db->query($query." WHERE ".$fwhere." AND (".$cari.")".$order." LIMIT 20 ");
            }
            
            if(isset($search))
            {
                if(!empty($iswhere))
                {
                    $sql_cari =  $this->db->query($query." WHERE $iswhere AND ".$fwhere." AND (".$cari.")");
                }else{
                    $sql_cari =  $this->db->query($query." WHERE ".$fwhere." AND (".$cari.")");
                }
                $sql_filter_count = $sql_cari->num_rows();
            }else{
                if(!empty($iswhere))
                {
                    $sql_filter = $this->db->query($query." WHERE $iswhere AND ".$fwhere);
                }else{
                    $sql_filter = $this->db->query($query." WHERE ".$fwhere);
                }
                $sql_filter_count = $sql_filter->num_rows();
            }
            $data = $sql_data->result_array();

        }else{
            if(!empty($iswhere))
            {
                $sql = $this->db->query($query." WHERE  $iswhere " ." LIMIT 10 " );
            }else{
                $sql = $this->db->query($query ." LIMIT 10 " );
            }
            
            $sql_count = $sql->num_rows();

            $cari = implode(" LIKE '%".$search."%' OR ", $cari)." LIKE '%".$search."%'";
            
            if(empty($isOrder)){
                // Untuk mengambil nama field yg menjadi acuan untuk sorting
                $order_field = $_POST['order'][0]['column']; 
                // Untuk menentukan order by "ASC" atau "DESC"
                $order_ascdesc = $_POST['order'][0]['dir']; 
                $order = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;                
            } else {
                $order = " ORDER BY ".$isOrder;
            }

            if(!empty($iswhere))
            {                
                $sql_data = $this->db->query($query." WHERE $iswhere AND (".$cari.")".$order." LIMIT 20 " );
            }else{
                $sql_data = $this->db->query($query." WHERE (".$cari.")".$order." LIMIT 20 " );
            }

            if(isset($search))
            {
                if(!empty($iswhere))
                {     
                    $sql_cari =  $this->db->query($query." WHERE $iswhere AND (".$cari.")" ." LIMIT 20 " );
                }else{
                    $sql_cari =  $this->db->query($query." WHERE (".$cari.")" ." LIMIT 20 " );
                }
                $sql_filter_count = $sql_cari->num_rows();
            }else{
                if(!empty($iswhere))
                {
                    $sql_filter = $this->db->query($query." WHERE $iswhere" ." LIMIT 20 " );
                }else{
                    $sql_filter = $this->db->query($query ." LIMIT 10 ");
                }
                $sql_filter_count = $sql_filter->num_rows();
            }
            $data = $sql_data->result_array();
        }
        
        $callback = array(    
            'draw' => $_POST['draw'], // Ini dari datatablenya    
            'recordsTotal' => $sql_count,    
            'recordsFiltered'=>$sql_filter_count,    
            'data'=>$data
        );
        return json_encode($callback); // Convert array $callback ke json
    }
     


}