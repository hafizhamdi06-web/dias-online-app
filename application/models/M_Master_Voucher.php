<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Master_Voucher extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahData()
    {
        
         $timestamp = time();
        $currentDate = gmdate('Y-m-d', $timestamp);
        
        $umur=$_POST['masaberlaku'] ;
        $date=$_POST['tglexpired'];
        
        if($date=='')
        {
             if($_POST['masaberlaku']>0)
            {
                $date=date_create(($_POST['tglterbit']));
                date_add($date,date_interval_create_from_date_string("$umur days"));
                
                //$date=date_format($date, "Y-m-d"); // Outputs: 2023-04-24 
            }
        }
        $date=tgl_database($date);
        
    	$id = $_POST['id'];
        $data = array(
                
                
                'VNOMOR' => $_POST['nomor'],
                'VTGLTERBIT' => tgl_database($_POST['tglterbit']),
                'VTGLGUNA' => tgl_database($_POST['tglpakai']),
                'VKONTAK' => $_POST['pasien'],
                'VNILAI' => $_POST['diskon1'],
                'VJENIS' => $_POST['jenis'],
                'VITEM' => $_POST['item1'],
                'V1TRANSAKSI' => $_POST['penggunaan'],
                'VPRODUKSAJA' => $_POST['produksaja'],
                'VMASABERLAKU' => $_POST['masaberlaku'],
                'VTGLEXPIRED' => $date,
                'VITEM2' => $_POST['item2'],
                'VRUPIAH' => $_POST['rupiah'],
                'VNILAI2' => $_POST['diskon2'],
                'VPEMAKAIANBYTGL' => $_POST['pakaibytanggal'],
                'VFREEITEM' => $_POST['itemfree'],
                'VKONTAKTEMAN' => $_POST['teman'],  
                'VMODIFU' => $this->session->id, 
                'VMODIFD' => $currentDate              
        );        
        $this->db->trans_begin();
        $this->db->where('vid',$id);        
        $this->db->update('bvoucher',$data);

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
        $this->db->where('vid', $id);
        $this->db->delete('bvoucher');

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
        
        $timestamp = time();
        $currentDate = gmdate('Y-m-d', $timestamp);
        
        $date=tgl_database($_POST['tglexpired']);
        if($date=='')
        {
            if($_POST['masaberlaku']>0)
            {
                $date=date_create(($_POST['tglterbit']));
                date_add($date,date_interval_create_from_date_string("40 days"));
                
                $date=date_format($date, "Y-m-d"); // Outputs: 2023-04-24 
            }
        }

        
        //  zField = Array("VCABANG", "VNOMOR", "VTGLTERBIT", "VTGLGUNA", "VKONTAK", "VCREATEU", "VMODIFU", "VMODIFD", "VNILAI", "VJENIS", "VITEM", "V1TRANSAKSI", "VPRODUKSAJA", //"VMASABERLAKU", "VTGLEXPIRED", "VITEM2", "VRUPIAH", "VNILAI2", "VPEMAKAIANBYTGL", "VFREEITEM", "VKONTAKTEMAN") ', "VJENISDISKON"
 // zValue = Array(xCabang, txtKode, xUmum.Nz(txtTanggalTerbit, Null), xUmum.Nz(txtTanggalGuna, Null), xUmum.Nz(txtKontak.Tag, Null), IIf(Len(lblUserCreate) > 0, lblUserCreate, X_User.uID), X_User.uID, Now, CDbl(xUmum.Nz(txtNilai, 0)), xUmum.Nz(txtJenisVoucher.Tag, Null), txtItem, cboPenggunaan.ListIndex, chkProdukSaja.Value, CDbl(xUmum.Nz(txtMasaBerlaku, 0)), pTglJatuhTempo, txtItem2, chkRupiah.Value, CDbl(xUmum.Nz(txtNilai2, 0)), chkPemakaianByTgl.Value, xUmum.Nz(pMasterData.fIDItem(txtItemFree), Null), xUmum.Nz(txtTEman.Tag, Null)) ', cboJenisDiscount.ListIndex
        $cabang  = @$_SESSION['cabang'] ;
        $data = array(
                'VCABANG' => $cabang,
                'VNOMOR' => $_POST['nomor'],
                'VTGLTERBIT' => tgl_database($_POST['tglterbit']),
                'VTGLGUNA' => tgl_database($_POST['tglpakai']),
                'VKONTAK' => $_POST['pasien'],
                'VNILAI' => $_POST['diskon1'],
                'VJENIS' => $_POST['jenis'],
                'VITEM' => $_POST['item1'],
                'V1TRANSAKSI' => $_POST['penggunaan'],
                'VPRODUKSAJA' => $_POST['produksaja'],
                'VMASABERLAKU' => $_POST['masaberlaku'],
                'VTGLEXPIRED' => $date,
                'VITEM2' => $_POST['item2'],
                'VRUPIAH' => $_POST['rupiah'],
                'VNILAI2' => $_POST['diskon2'],
                'VPEMAKAIANBYTGL' => $_POST['pakaibytanggal'],
                'VFREEITEM' => $_POST['itemfree'],
                'VKONTAKTEMAN' => $_POST['teman'], 
                'VCREATEU' => $this->session->id, 
                'VMODIFU' => $this->session->id, 
                'VMODIFD' => $currentDate                 
        );  
      
        $this->db->trans_begin();
        $this->db->insert('bvoucher',$data);

        if($this->db->trans_status() === FALSE){
            $this->db->trans_rollback();
            return "rollback";
        } else {
            $this->db->trans_commit();            
            return "sukses";
        }
    }    
}