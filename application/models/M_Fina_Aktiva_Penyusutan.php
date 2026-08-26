<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
 
class M_Fina_Aktiva_Penyusutan extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function getaktiva($id)
    {
        $this->db->where('aid', $id);
        return $this->db->get('baktiva');
    }

    function create()
    {

        if(empty($this->input->post('nomor'))){
            $nomor = $this->autonumber($this->input->post('tgl'));
        }else{
            $nomor = $this->input->post('nomor');
        }        

        $uraian = "Penyusutan aktiva tetap bulan ".$this->input->post('bulan')." tahun ".$this->input->post('tahun');

        // Insert Header Trans
        $data_header = array(
                        'cusumber' => $this->M_transaksi->prefixtrans(element('Fina_Aktiva_Penyusutan',NID)),
                        'cunotransaksi' => $nomor,
                        'cutanggal' => tgl_database($_POST['tgl']),
                        'cukontak' => $_POST['kontak'],
                        'cuuraian' => $uraian,
                        'cudepresiasi' => 1,
                        'cucreateu' => $this->session->id                
        );        

        $this->db->trans_start();
        $this->db->insert('ctransaksiu',$data_header);
        $id = $this->db->insert_id();

        //===================================================
        // Detil Penyusutan

        $data = json_decode($this->input->post('data'));
        $r=1;
        $totalbiaya=0;
        foreach($data as $item){
            $dataaktiva = $this->getaktiva($item->data);
            foreach ($dataaktiva->result() as $row) {
                $aktiva = array(
                    'id' => $row->AID,
                    'nama' => $row->ANAMA,
                    'metode' => $row->ATIPEPENYUSUTAN,
                    'bebanperbulan' => $row->ABEBANPERBULAN,
                    'coabeban' => $row->ACOADEPRESIASI,
                    'coaakumulasi' => $row->ACOADEPRESIASIAKUM,
                    'akumbeban' => $row->AAKUMBEBAN,
                    'umur' => $row->AUMUR,
                    'perolehan' => $row->AHARGABELI-$row->ANILAIRESIDU
                );
            }

            if($aktiva['metode']==1){ // GARIS LURUS
                $bebanperbulan = $aktiva['bebanperbulan'];
            }elseif ($aktiva['metode']==2) { // SALDO MENURUN
                $bebanperbulan = (1/$aktiva['umur']) * ($aktiva['perolehan']-$aktiva['akumbeban']);
            }else{
                $bebanperbulan = 0;
            }

            // Debet
            $detil_db = array(
                        'cdidu' => $id,
                        'cdurutan' => $r,
                        'cdnocoa' => $aktiva['coabeban'],
                        'cddebit' => $bebanperbulan,
                        'cdkredit' => 0,
                        'cdcatatan' => $aktiva['nama']
            );
            $this->db->insert('ctransaksid',$detil_db);                        

            // Kredit
            $r++;     
            $detil_cr = array(
                        'cdidu' => $id,
                        'cdurutan' => $r,
                        'cdnocoa' => $aktiva['coaakumulasi'],
                        'cddebit' => 0,
                        'cdkredit' => $bebanperbulan,
                        'cdcatatan' => $aktiva['nama']
            );
            $this->db->insert('ctransaksid',$detil_cr);                                    

            $depr = array(
                    'apida' => $aktiva['id'],
                    'aptahun' => $this->input->post('tahun'),
                    'apbulan' => $this->input->post('bulan'),
                    'apnilai' => $bebanperbulan,
                    'apcuid' => $id
            );
            $this->db->insert('baktivapenyusutan',$depr);                        

            $this->db->where('aid', $aktiva['id']);
            $this->db->update('baktiva',array('aakumbeban' => $aktiva['akumbeban']+$bebanperbulan));        

            $r++;     
            $totalbiaya += $bebanperbulan;       
        }

        $this->db->where('cuid', $id);
        $this->db->update('ctransaksiu',array('cutotaltrans' => $totalbiaya));        

        // USERLOG
        $uactivity = _anomor(element('Fina_Aktiva_Penyusutan',NID));
        $uactivity = $uactivity['keterangan'];        
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 1                                                                                    
        );
        $this->db->insert('auserlog',$userlog);                                 

        $this->db->trans_complete();            

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }        
    }

    function autonumber($tgl)
    {
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('Fina_Aktiva_Penyusutan',NID));
        $nomor2 = tgl_notrans($tgl);  

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT IFNULL(MAX(RIGHT(cunotransaksi,4)),0) as 'maks' 
                  FROM ctransaksiu 
                 WHERE LEFT(cunotransaksi,".$notrans_length.")='".$nomor1.$nomor2."'";
        
        $query = $this->db->query($sql);
        foreach ($query->result() as $res) {
            $nomor = number_format($res->maks)+1;
        }

        switch(strlen($nomor)){
        case 1:
          $nomor=$nomor1.$nomor2."000".$nomor;
          break;
        case 2:
          $nomor=$nomor1.$nomor2."00".$nomor;
          break;
        case 3:
          $nomor=$nomor1.$nomor2."0".$nomor;
          break;
        case 4:
          $nomor=$nomor1.$nomor2.$nomor;
          break;
        }
        
        return $nomor;
    } 

}