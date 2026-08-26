<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Datatable_Master extends CI_Controller {

   function __construct() { 
      parent::__construct();
      $this->load->model('M_datatables');
      if(!$this->session->has_userdata('nama')){
          redirect(base_url('exception'));
      }      
   }

   function view_table_coa() {
        $query  = "SELECT A.cid AS 'id',A.cnocoa AS 'nomor',A.cnama AS 'nama',B.usimbol AS 'uang',C.cgnama AS 'tipe' 
                     FROM bcoa A 
                LEFT JOIN buang B ON A.cuang=B.uid
               INNER JOIN bcoagrup C ON A.ctipe=C.cgid";
        $search = array('cnocoa','cnama');
        $where  = null;         
        $isWhere = "A.cnocoa LIKE '%".$_POST['kode']."%' AND A.cnama LIKE'%".$_POST['nama']."%'";

        if(!empty($this->input->post('tipe')) && $this->input->post('tipe') != null) {
          $isWhere .= " AND A.ctipe='".$this->input->post('tipe')."'";
        }

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }    

   function view_table_item() {
        $info = _ainfo(1);
        $digitqty = $info['idecimalqty'];        
        $query  = "SELECT A.iid AS 'id',A.ikode AS 'kode',A.inama AS 'nama',
                          CASE WHEN A.ijenisitem=0 THEN ROUND(IFNULL(A.istocktotal,0),$digitqty) 
                               WHEN A.ijenisitem=1 THEN 0
                               ELSE ROUND(IFNULL(A.istocktotal,0),$digitqty)
                          END AS 'jumlah',
                          B.skode AS 'satuan',
                          CASE WHEN A.ijenisitem=0 THEN 'Persediaan' 
                               WHEN A.ijenisitem=1 THEN 'Jasa' 
                               WHEN A.ijenisitem=2 THEN 'Konsinyasi' 
                          END AS 'jenis',
                          ROUND(IFNULL(A.ihargabeli,0),2) AS 'hbeli',ROUND(IFNULL(A.ihargajual1,0),2) AS 'hjual',
                          IFNULL(C.cnocoa,'') AS 'coa',IFNULL(C.cnama,'') AS 'coanama'        
                     FROM bitem A
                LEFT JOIN bsatuan B ON A.isatuan=B.sid
                LEFT JOIN bcoa C ON A.icoapendapatan=C.cid";
        $search = array('ikode','inama');
        $where  = null;         
        $isWhere = "A.ikode LIKE '%".$_POST['kode']."%' AND A.inama LIKE'%".$_POST['nama']."%'";

        if(!empty($this->input->post('jenis')) && $this->input->post('jenis') != null) {
          $isWhere .= " AND A.ijenisitem='".$this->input->post('jenis')."' ";
        }
        
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   function view_table_kontak($katId="") {
        $query  = "SELECT A.kid AS 'id',A.kkode AS 'kode',A.knama AS 'nama',B.ktnama AS 'tipe',
                          A.k1alamat 'alamat',A.k1kota AS 'kota',A.k1telp1 AS 'telp'
                     FROM bkontak A
               INNER JOIN bkontaktipe B ON A.ktipe=B.ktid";
        $search = array('kkode','knama','kidpasien','k1telp1');
        $where  = null;

        if($katId!==""){
          $isWhere = "A.ktipe='".$katId."'";
        }else{
          $isWhere = "A.kkode LIKE '%".@$_POST['kode']."%' AND A.knama LIKE'%".@$_POST['nama']."%' ";
        }

        if(!empty($this->input->post('kategori')) && $this->input->post('kategori') != null) {
          $isWhere .= " AND A.ktipe='".$this->input->post('kategori')."' ";
        }       
         

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   

   function view_table_item_pos($katId="") {
       
        $cabang  = @$_SESSION['cabang'] ;
        //$kodestok = code_gudang();
       //  $kodestok = $this->code_gudang();
         $kodestok = $this->code_gudang();
       // $nomor = $this->autonumber($_POST['tgl']); 
       
        $query  = "SELECT A.iid AS 'id',left(A.ikode,25) AS 'kode',left(A.inama,25)  AS 'nama',A.ihargajual1 'hargajual',
                          $kodestok 'stok', A.itipeitem 'tipeitem'
                     FROM bitem A
               INNER JOIN bitemkelompok2020 B ON A.ikelompok2020=B.ik2id";
        $search = array('ikode','inama');
        $where  = null;
        

        $isWhere = " A.istatus=0 and icabang like '%".$cabang."|%' ";
        
        if($katId!==""){
          $isWhere .= " and A.ikelompok2020='".$katId."'";
        }else{
          $isWhere .= " and A.ikode LIKE '%".@$_POST['kode']."%' AND A.inama LIKE'%".@$_POST['nama']."%' ";
        }

        if(!empty($this->input->post('kategori')) && $this->input->post('kategori') != null) {
          $isWhere .= " AND A.ikelompok2020='".$this->input->post('kategori')."' ";
        }       
         

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }

   

   function view_table_voucher2($idu='') {
       
       
      //  $query  = "SELECT vid AS 'id',vnomor AS 'kode',vtglterbit AS 'tgl', knama 'nama' , case VPAKAI when 0 then ' ' else 'Sudah Dipakai' end 'status'
      //              , vtglguna AS 'tglpakai'
       //              FROM bvoucher left join bkontak on kid=vkontak ";
       // $search = array('vnomor');
       // $where  = null;         
       // $isWhere = "vnomor LIKE '%".$_POST['kode']."%' AND knama LIKE'%".$_POST['nama']."%'";
       // $isOrder = " vid desc " ;
        
        
        //mSQL = "select VKID,VKNOMOR,' '  from bvoucherkupon WHERE VKPAKAI=0 and VKCABANG = " & xCabang & Filter & xFilter & "  order by  VKNOMOR "
        
      // $query  = "SELECT VKID AS 'id',VKNOMOR AS 'kode',VKNOMOR  AS 'nama',0 'hargajual',
       //                             0 'stok', 1 'tipeitem'
        //                         FROM  fstokkuponv left join bvoucherkupon on vkid=SVKIDVOUCHER   "; 
       // //$isWhere = "  SVKIDU = ".$idu." "; 
                    
                    
       
        $cabang  = @$_SESSION['cabang'] ; 
       
                    $query  = "SELECT VKID AS 'id',VKNOMOR AS 'kode',VKNOMOR  AS 'nama',0 'hargajual',
                                     0 'stok', 1 'tipeitem'
                                 FROM bvoucherkupon A "; 
                   
                    $search = array('VKNOMOR','VKNOMOR');
                    $where  = null; 
                  
            
                    $isWhere = "  VKPAKAI=0 and VKCABANG = ".$cabang." "; 
                    $isWhere .= " and VKNOMOR LIKE '%".@$_POST['kode']."%'  ";
                   

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }
    
    
    function view_table_voucher_sudahdapat($idu='') {
       
       
      //  $query  = "SELECT vid AS 'id',vnomor AS 'kode',vtglterbit AS 'tgl', knama 'nama' , case VPAKAI when 0 then ' ' else 'Sudah Dipakai' end 'status'
      //              , vtglguna AS 'tglpakai'
       //              FROM bvoucher left join bkontak on kid=vkontak ";
       // $search = array('vnomor');
       // $where  = null;         
       // $isWhere = "vnomor LIKE '%".$_POST['kode']."%' AND knama LIKE'%".$_POST['nama']."%'";
       // $isOrder = " vid desc " ;
        
        
        //mSQL = "select VKID,VKNOMOR,' '  from bvoucherkupon WHERE VKPAKAI=0 and VKCABANG = " & xCabang & Filter & xFilter & "  order by  VKNOMOR "
        
       
        $cabang  = @$_SESSION['cabang'] ; 
        
         $query  = "SELECT VKID AS 'id',VKNOMOR AS 'kode',VKNOMOR  AS 'nama',0 'hargajual',
                                     0 'stok', 1 'tipeitem'
                                 FROM bvoucherkupon A "; 
                                 
       
                     $query  = "SELECT VKID AS 'id',VKNOMOR AS 'kode',VKNOMOR  AS 'nama',0 'hargajual',
                                    0 'stok', 1 'tipeitem'
                                 FROM  fstokkuponv left join bvoucherkupon on vkid=SVKIDVOUCHER   ";
                      $search = array('VKNOMOR','VKNOMOR');
                    $where  = null;
                   
                   
                  
                    
                    $isWhere = "  SVKIDU = ".$idu." "; 
            
                   // $isWhere = "  VKPAKAI=0 and VKCABANG = ".$cabang." "; 
                    //$isWhere .= " and VKNOMOR LIKE '%".@$_POST['kode']."%'  ";
                     
            
            
            
         
                     

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }
    
     
   function code_gudang() {
       
        $cabang  = @$_SESSION['cabang'] ;
        $sql  = " select  F_KOLOMGUDANG('".$cabang."') as kolomstok ";
        
        $query = $this->db->query($sql);
        foreach ($query->result() as $res) {
            $codegudang =  $res->kolomstok ;
        }
         
         return $codegudang ;
 
    }


   function view_table_kontak_pos($katId="") {
        $query  = "SELECT A.kid AS 'id',A.kkode AS 'kode',A.knama AS 'nama',B.ktnama AS 'tipe',
                          A.k1alamat 'alamat',A.k1kota AS 'kota',A.k1telp1 AS 'telp'
                     FROM bkontak A
               INNER JOIN bkontaktipe B ON A.ktipe=B.ktid";
        $search = array('kkode','knama','kidpasien','k1telp1');
        $where  = null;
        

        if($katId!==""){
          $isWhere = "A.ktipe='".$katId."'";
        }else{
          $isWhere = "A.kkode LIKE '%".@$_POST['kode']."%' AND A.knama LIKE'%".@$_POST['nama']."%'";
        }

        if(!empty($this->input->post('kategori')) && $this->input->post('kategori') != null) {
          $isWhere .= " AND A.ktipe='".$this->input->post('kategori')."' ";
        }       
         
         $isOrder = " A.kid desc " ;

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_tanpalimit($query,$search,$where,$isWhere,$isOrder); 
    }

    function view_table_kontak_pos2($katId="" ) { 
        $query  = "SELECT A.kid AS 'id',A.kkode AS 'kode',A.knama AS 'nama',B.ktnama AS 'tipe',
                          A.k1alamat 'alamat',A.k1kota AS 'kota',A.k1telp1 AS 'telp', gkode 'cabang'
                     FROM bkontak A
               INNER JOIN bkontaktipe B ON A.ktipe=B.ktid left join bgudang on gid=kcabang ";
        $search = array('kkode','knama','kidpasien','k1telp1');
        $where  = null;
        

        if($katId!==""){
          $isWhere = "A.ktipe='".$katId."'";
        }else{
          $isWhere = "A.kkode LIKE '%".@$_POST['kode']."%' AND A.knama LIKE'%".@$_POST['nama']."%'";
        }
 
        

        if(!empty($this->input->post('kategori')) && $this->input->post('kategori') != null) {
          $isWhere .= " AND A.ktipe='".$this->input->post('kategori')."' ";
        }       
         
         $isOrder = " A.kid desc " ;

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query_tanpalimit($query,$search,$where,$isWhere,$isOrder); 
    }


   function view_table_bank() {
        $query  = "SELECT bid AS 'id',bkode AS 'kode',bnama AS 'nama' 
                     FROM bbank";
        $search = array('bkode','bnama');
        $where  = null;         
        $isWhere = "bkode LIKE '%".$_POST['kode']."%' AND bnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_table_uang() {
        $query  = "SELECT uid AS 'id',ukode AS 'kode',unama AS 'nama',usimbol AS 'simbol' 
                     FROM buang";
        $search = array('ukode','unama');
        $where  = null;         
        $isWhere = "ukode LIKE '%".$_POST['kode']."%' AND unama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_table_termin() {
        $query  = "SELECT tid AS 'id',tkode AS 'kode',tnama AS 'nama',ttempo AS 'tempo' 
                     FROM btermin";
        $search = array('tkode','tnama');
        $where  = null;         
        $isWhere = "tkode LIKE '%".$_POST['kode']."%' AND tnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                

   function view_table_pajak() {
        $query  = "SELECT pid AS 'id',pkode AS 'kode',pnama AS 'nama',pnilai AS 'nilai' 
                     FROM bpajak";
        $search = array('pkode','pnama');
        $where  = null;         
        $isWhere = "pkode LIKE '%".$_POST['kode']."%' AND pnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                    

   function view_table_fatipe() {
        $query  = "SELECT akid AS 'id',akkode AS 'kode',aknama AS 'nama',akumur AS 'umur' 
                     FROM baktivakelompok";
        $search = array('akkode','aknama');
        $where  = null;         
        $isWhere = "akkode LIKE '%".$_POST['kode']."%' AND aknama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }            

   function view_table_aktiva() {
        $query  = "SELECT A.aid AS 'id',A.akode AS 'kode',A.anama AS 'nama',B.aknama AS 'kelompok',
                          ROUND(A.ahargabeli,2) AS 'nilai', ROUND(A.aakumbeban,2) AS 'akumulasi',
                          A.aumur AS 'umur',ROUND((A.ahargabeli-A.aakumbeban),2) AS 'buku' 
                     FROM baktiva A
               INNER JOIN baktivakelompok B ON A.akelompok=B.akid";
        $search = array('akode','anama');
        $where  = null;         
        $isWhere = "akode LIKE '%".$_POST['kode']."%' AND anama LIKE'%".$_POST['nama']."%'";

        if(!empty($this->input->post('kelompok')) && $this->input->post('kelompok') != null) {
          $isWhere .= " AND A.akelompok='".$this->input->post('kelompok')."'";
        }             

        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                        

   function view_table_proyek() {
        $query  = "SELECT pid AS 'id',pkode AS 'kode',pnama AS 'nama' 
                     FROM bproyek";
        $search = array('pkode','pnama');
        $where  = null;         
        $isWhere = "pkode LIKE '%".$_POST['kode']."%' AND pnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                    

   function view_table_gudang() {
        $query  = "SELECT gid AS 'id',gkode AS 'kode',gnama AS 'nama',galamat1 AS 'alamat',gkota AS 'kota',gtelp AS 'telp' 
                     FROM bgudang";
        $search = array('gkode','gnama');
        $where  = null;         
        $isWhere = "gkode LIKE '%".$_POST['kode']."%' AND gnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                        

   function view_table_satuan() {
        $query  = "SELECT sid AS 'id',skode AS 'kode',snama AS 'nama',ssatuandasar AS 'dasar',snilai AS 'nilai' 
                     FROM bsatuan";
        $search = array('skode','snama');
        $where  = null;         
        $isWhere = "skode LIKE '%".$_POST['kode']."%' AND snama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                            

   function view_table_ckontak() {
        $query  = "SELECT ktid AS 'id',ktnama AS 'nama' 
                     FROM bkontaktipe";
        $search = array('ktnama');
        $where  = null;         
        $isWhere = "ktnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                                

   function view_table_divisi() {
        $query  = "SELECT did AS 'id',dkode AS 'kode',dnama AS 'nama' 
                     FROM bdivisi";
        $search = array('dkode','dnama');
        $where  = null;         
        $isWhere = "dkode LIKE '%".$_POST['kode']."%' AND dnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                                    

   function view_table_kattention($kontak="") {
        $query  = "SELECT kaid AS 'id',kanama AS 'nama',kajabatan AS 'jabatan' 
                     FROM bkontakatention";
        $search = array('kanama','kajabatan');
        $where  = null;         
        $isWhere = " kaidk ='".$kontak."'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }                                    

   function view_jenis_penyesuaian() {
        $query  = "SELECT jid AS 'id', jkode 'kode', jnama AS 'nama' 
                     FROM bjenispenyesuaian";
        $search = array('jnama');
        $where  = null;         
        $isWhere = "jkode LIKE '%".$_POST['kode']."%' AND jnama LIKE'%".$_POST['nama']."%'";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere);
    }   
    
   function view_table_paket() {
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT puid AS 'id',pukode AS 'kode', punama AS 'nama', pujumlah as 'jumlah'  
                     FROM epaketu  inner join epaketc on pcidu=puid ";
        $search = array('pukode','punama');
        $where  = null;         
        $isWhere = " puaktif=1 and pccabang = '".$cabang."'  ";
        $isWhere .= " and case when coalesce(pupakaijam,0)=1 then coalesce(pujam1,'00:00') <= CURRENT_TIME  and coalesce(pujam2,'00:00') >= CURRENT_TIME    else puid <> 0 end   ";
        
        
        $isWhere .= " AND COALESCE(PUTANGGAL1,current_date) <= current_date AND COALESCE(PUTANGGAL2,current_date) >= current_date   ";
        $isOrder="pukode";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere,$isOrder);
    }     
    
    
    //    mSQL = "select MPDID,MPUKODE,MPDKELITEM1,MPDKELITEM2,MPDKELITEM3,MPDDISKON,MPDDISKONITEM2,MPDDISKON1KE3,MPDBONUS,MPUJENISPROMO ,MPUJENISPROMO,MPUMINIMALTRANSAKSI,MPDTOTALINVOICE1,MPDTOTALINVOICE2,MPDMINIMALQTY3, CASE MPUJENISPROMO WHEN 0 THEN 'Biasa' when 1 then 'Kombinasi' when 2 then 'Kombinasi 2' when 3 then 'By Tipe' end,MPDMAX,MPDJAM1,MPDJAM2 from emasterpromou left join emasterpromod on mpuid=mpdidu left join emasterpromoc on mpuid=mpcidu left join emasterpromokontaktipe on MPKTIDU=mpuid WHERE MPCCABANG = " & xCabang & " and MPUAKTIF=1  " & Filter & xFilter & "  order by  MPUKODE,MPDKELITEM1,MPDKELITEM2  "

   function view_table_promo($jeniskontak="") {
       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT DISTINCT mpdid AS 'id',mpukode AS 'kode', mpdkelitem1 AS 'nama1', mpdkelitem2 AS 'nama2' , mpdkelitem3 AS 'nama3', 
        CASE MPUJENISPROMO WHEN 0 THEN 'Biasa' when 1 then 'Kombinasi' when 2 then 'Kombinasi 2' when 3 then 'By Tipe' end 'jenis'
                     From emasterpromou inner join emasterpromod on mpuid=mpdidu inner join emasterpromoc on mpuid=mpcidu inner join emasterpromokontaktipe on MPKTIDU=mpuid   ";
        $search = array('mpukode','mpdkelitem1','mpdkelitem2');
        $where  = null;         
        $isWhere = " mpuaktif=1 and MPCCABANG = '".$cabang."'  "; 
        $isWhere .= " AND COALESCE(MPUTANGGAL1,current_date) <= current_date AND COALESCE(MPUTANGGAL2,current_date) >= current_date   ";
        
        if($jeniskontak!==""){
          $isWhere .= " and mpkttipe='".$jeniskontak."' ";
        } 
            
            
        $isOrder="mpukode";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere,$isOrder);
    }
    
    

   function view_table_voucher() {
        $query  = "SELECT vid AS 'id',vnomor AS 'kode',vtglterbit AS 'tgl', knama 'nama' , case VPAKAI when 0 then ' ' else 'Sudah Dipakai' end 'status'
                    , vtglguna AS 'tglpakai'
                     FROM bvoucher left join bkontak on kid=vkontak ";
        $search = array('vnomor');
        $where  = null;         
        $isWhere = "vnomor LIKE '%".$_POST['kode']."%' AND knama LIKE'%".$_POST['nama']."%'";
        $isOrder = " vid desc " ;
        
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere,$isOrder);
    }  
    
    
   function view_diskonvocer($jeniskontak="") {
       
        //    hSQL = "select ' ',VNOMOR,VNILAI,VITEM,VJENIS,V1TRANSAKSI,VPRODUKSAJA,VTGLEXPIRED,VITEM2,VRUPIAH,VNILAI2,IKODE,VID   from bvoucher left join bitem on iid=vfreeitem WHERE VKONTAK = " & .txtKontak.Tag & "  and (  (VPAKAI = 0 and VPEMAKAIANBYTGL = 0) or (VPEMAKAIANBYTGL = 1 and VTGLTERBIT <= '" & Format(eFrmPOS2.txtTanggal, "yyyy/mm/dd") & "' )) " & lblFilter & "  order by VNOMOR"



       $cabang  = @$_SESSION['cabang'] ;
        $query  = "SELECT DISTINCT vid AS 'id',vnomor AS 'kode', vnilai AS 'nilai', vitem AS 'item' , vjenis AS 'jenis', 
                  v1transaksi 'v1transaksi', vproduksaja 'produksaja', vtglexpired 'tglexpired', vitem2 'vitem', vrupiah 'rupiah', vnilai2, ikode
                  from bvoucher left join bitem on iid=vfreeitem    ";
                  
        $search = array('vnomor');
        $where  = null;         
        $isWhere = "  VKONTAK = '".$jeniskontak."'   and (  (VPAKAI = 0 and VPEMAKAIANBYTGL = 0) or (VPEMAKAIANBYTGL = 1 and VTGLTERBIT <= current_date )) ";  
         
        $isOrder="vnomor";
        header('Content-Type: application/json');
        echo $this->M_datatables->get_tables_query($query,$search,$where,$isWhere,$isOrder);
    }

}