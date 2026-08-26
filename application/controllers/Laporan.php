<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends CI_Controller {

   function __construct() { 
    	parent::__construct();
      if(!$this->session->has_userdata('nama')){
        redirect(base_url('404'));
      }          
  	    $this->load->model('M_transaksi');
   }

   function index($idreport='',$id=''){

      $mode = $this->input->post('mode');

      if($idreport==''){
        $idreport = $_POST['id'];
      }

      $r = $this->gettabelreport($idreport);
      $r = json_decode($r);

      foreach ($r->data as $key) {
          $title = $key->ARNAME;
          $title2 = $key->ARNAME2;
          $link = $key->ARLINK;
          $orientasi = $key->ARPAPERORINTED == 1 ? 'P' : 'L' ;
          
          if ($key->ARPAPERSIZE == 1 )
          {   $size = 'Letter' ;}
          elseif ($key->ARPAPERSIZE == 2 )
          {   $size = 'Legal';}
          elseif ($key->ARPAPERSIZE == 3 )
          {   $size = 'A4'; }
          elseif ($key->ARPAPERSIZE == 4 )
          {   $size = 'A3' ;}
          else 
          {   $size = 'A4' ;}
          
          
          //$size = $key->ARPAPERSIZE == 1 ? 'Letter' : ( $key->ARPAPERSIZE == 2 ? 'Legal' ) : ($key->ARPAPERSIZE == 3 ? 'A4' : 'A3') ; 
          
          
          $ml = $key->ARMARGINLEFT;
          $mt = $key->ARMARGINTOP;
      }

      $this->load->model('M_Settings_Info');        
      $infosettings = $this->M_Settings_Info->infoPerusahaan();
      foreach ($infosettings->result() as $row) {
          $company = $row->inama;
          $addr = $row->ialamat1.' '.$row->ikota.' '.$row->ipropinsi;
          $kodepos = $row->ikodepos;
          $email = $row->iemail;
          $phone = $row->itelepon1;
          $digitqty = $row->idecimalqty;
      }

      $data['id'] = $id;      
      $data['title'] = $title;
      $data['title2'] = $title2;
      $data['company_name'] = $company;
      $data['company_addr'] = $addr;      
      $data['company_kodepos'] = $kodepos;            
      $data['company_email'] = $email; 
      $data['company_phone'] = $phone;
      $data['digitqty'] = $digitqty;                              

      if($mode==1 || empty($mode)){ // pdf print preview
          $report = $this->load->view('modul/laporan/'.$link, $data, TRUE);
          $filename = $title.".pdf";
          $mpdf = new \Mpdf\Mpdf(['format' => $size.'-'.$orientasi,'margin_left' => $ml,'margin_top' => $mt]);
          $mpdf->SetTitle($title);  
          $mpdf->SetAuthor($this->config->item('hak_cipta'));  
          //$mpdf->SetFooter('Page : {PAGENO}');    
          ini_set("pcre.backtrack_limit", "5000000");          
          $mpdf->WriteHTML($report);
          $mpdf->Output($filename,\Mpdf\Output\Destination::INLINE);    
      }elseif($mode==3){ // download excel
          header("Content-Type: application/vnd.ms-excel; charset=utf-8");
          header("Content-Disposition: attachment; filename=".$title.".xls");
          header("Expires: 0");
          header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
          header("Cache-Control: private",false);        
          echo $this->load->view('modul/laporan/xls/'.$link, $data, TRUE);      
      } else { // download pdf
          $report = $this->load->view('modul/laporan/'.$link, $data, TRUE);
          $filename = $title.".pdf";
          $mpdf = new \Mpdf\Mpdf(['format' => $size.'-'.$orientasi,'margin_left' => $ml,'margin_top' => $mt]);
          $mpdf->SetTitle($title);  
          $mpdf->SetAuthor($this->config->item('hak_cipta'));  
          //$mpdf->SetFooter('Page : {PAGENO}');    
          ini_set("pcre.backtrack_limit", "5000000");
          $mpdf->WriteHTML($report);
          $mpdf->Output($filename,\Mpdf\Output\Destination::DOWNLOAD);    
      }

   }

   function preview($link,$id){

      $m = $this->getreportid($link);
      $m = json_decode($m);
      $idreport = "";

      foreach ($m->data as $key) {
        $idreport = $key->mreport;
      }

      $this->index($idreport,$id);
   }

   function getparent(){
        $query = "SELECT A.* FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
        			 WHERE A.mtype=1 AND A.mactive=1 AND A.mparent=0 ORDER BY A.murutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }

   function getreportlist(){
        $query = "SELECT A.*,C.arname 'file',C.arid 'rptid' FROM aamenu A INNER JOIN aausermenu B ON A.MID=B.AUIDMENU AND B.AUAPPROVE=1 AND B.AUIDUSER=".$this->session->id." 
        			 LEFT JOIN aareport C ON A.mreport=C.arid  
                   WHERE A.mtype=1 AND A.mactive=1 AND A.mparent=".$_POST['id']." ORDER BY A.murutan ASC";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
   }   

   function getinforeport(){
        if($_POST['id'] == '' || $_POST['id'] == null) {
          echo _pesanError("Data tidak ditemukan !");
          exit;
        }

        $query = "SELECT A.*
                    FROM aareport A
                   WHERE A.arid='".$_POST['id']."'";
       
        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);    
   }

   function gettabelreport($id){
        $query = "SELECT A.*
                    FROM aareport A
                   WHERE A.arid='".$id."'";
       
        return $this->M_transaksi->get_data_query($query);    
   }

   function getreportid($param){
        $param = str_replace('-', '/', $param);
        $query = "SELECT A.mreport 
                    FROM aamenu A
                   WHERE A.mlink='".$param."'";
        return $this->M_transaksi->get_data_query($query);        
   }

   function gettabelvalue($tabel,$kolom,$where,$iswhere){
        $query = "SELECT $kolom 
                    FROM $tabel
                   WHERE $where='".$iswhere."'";
        return $this->M_transaksi->get_data_query($query);              
   }

}
