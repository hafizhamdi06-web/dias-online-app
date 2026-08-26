<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_login extends CI_Model{
    function cek_login($table,$where){     
        return $this->db->get_where($table,$where);
    }

    function get_user_info($select,$table,$where){
    	$this->db->select($select);
        return $this->db->get_where($table,$where);    	
    }  
    
 
    
    
}

?>