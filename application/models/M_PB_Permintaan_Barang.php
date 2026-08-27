<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_PB_Permintaan_Barang extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function ubahTransaksi(){
        $id = $_POST['id'];

        $data_header = array(
                        'pbusumber' => $this->M_transaksi->prefixtrans(element('PB_Permintaan_Barang',NID)),
                        'pbunotransaksi' => $_POST['nomor'],
                        'pbutanggal' => tgl_database($_POST['tgl']),
                        'pbukontak' => $_POST['karyawan'],
                        'pbukaryawan' => $_POST['karyawan'],
                        'pbugudang' => $_POST['gudang'],
                        'pbugudangsumber' => $_POST['gudangsumber'],
                        'pbutipepermintaan' => $_POST['tujuan'],
                        'pbujenis' => $_POST['jenis'],
                        'pbuuraian' => $_POST['uraian'],
                        'pbustatus' => $_POST['status'],
                        'pbukonfirmasicatatan' => $_POST['catatanverifikasi'],
                        'pbumodifu' => $this->session->id,
                        'pbumodifd' => date('Y-m-d H:i:s')
        );
        $this->db->trans_start();
        $this->db->where('pbuid', $id);
        $this->db->update('fpermintaanbarangu',$data_header);

        $this->db->where('pbdidsu', $id);
        $this->db->delete('fpermintaanbarangd');

        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'pbdidsu' => $id,
                    'pbdurutan' => $r,
                    'pbditem' => $item->item,
                    'pbdqty' => $item->qty,
                    'pbdqtyd' => $item->qty,
                    'pbdsatuan' => $item->satuan,
                    'pbdsatuand' => $item->satuan,
                    'pbdstok' => $item->stokreal,
                    'pbdstokreal' => $item->stok,
                    'pbdcatatan' => $item->catatan
            );
            $this->db->insert('fpermintaanbarangd',$data_detil);
            $r++;
        }

        // USERLOG
        $uactivity = _anomor(element('PB_Permintaan_Barang',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$this->input->post('nomor'),
            'ullevel'=> 2
        );
        $this->db->insert('auserlog',$userlog);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            $callback = array(
                'pesan'=>'rollback',
                'nomor'=>$id
            );
            return json_encode($callback);
        } else {
            $callback = array(
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);
        }

    }

    function tambahTransaksi()
    {
        if(empty($_POST['nomor'])){
            $nomor = $this->autonumber($_POST['tgl']);
        }else{
            $nomor = $_POST['nomor'];
        }

        $data_header = array(
                        'pbusumber' => $this->M_transaksi->prefixtrans(element('PB_Permintaan_Barang',NID)),
                        'pbunotransaksi' => $nomor,
                        'pbutanggal' => tgl_database($_POST['tgl']),
                        'pbukontak' => $_POST['karyawan'],
                        'pbukaryawan' => $_POST['karyawan'],
                        'pbugudang' => $_POST['gudang'],
                        'pbugudangsumber' => $_POST['gudangsumber'],
                        'pbutipepermintaan' => $_POST['tujuan'],
                        'pbujenis' => $_POST['jenis'],
                        'pbuuraian' => $_POST['uraian'],
                        'pbustatus' => 0,
                        'pbukonfirmasicatatan' => $_POST['catatanverifikasi'],
                        'pbucreateu' => $this->session->id,
                        'pbumodifd' => date('Y-m-d H:i:s')
        );
        $this->db->trans_start();
        $this->db->insert('fpermintaanbarangu',$data_header);
        $id = $this->db->insert_id();

        $r=1;
        $d = json_decode($_POST['detil']);
        foreach($d as $item){
            $data_detil = array(
                    'pbdidsu' => $id,
                    'pbdurutan' => $r,
                    'pbditem' => $item->item,
                    'pbdqty' => $item->qty,
                    'pbdqtyd' => $item->qty,
                    'pbdsatuan' => $item->satuan,
                    'pbdsatuand' => $item->satuan,
                    'pbdstok' => $item->stokreal,
                    'pbdstokreal' => $item->stok,
                    'pbdcatatan' => $item->catatan
            );
            $this->db->insert('fpermintaanbarangd',$data_detil);
            $r++;
        }

        // USERLOG
        $uactivity = _anomor(element('PB_Permintaan_Barang',NID));
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
            $callback = array(
                'pesan'=>'rollback',
                'nomor'=>''
            );
            return json_encode($callback);
        } else {
            $callback = array(
                'pesan'=>'sukses',
                'nomor'=>$id
            );
            return json_encode($callback);
        }
    }

    function hapusTransaksi(){

        $id = $this->input->post('id');
        $nomor = $this->input->post('nomor');

        $this->db->trans_start();

        $this->db->where('pbuid', $id);
        $this->db->delete('fpermintaanbarangu');

        $this->db->where('pbdidsu', $id);
        $this->db->delete('fpermintaanbarangd');

        // USERLOG
        $uactivity = _anomor(element('PB_Permintaan_Barang',NID));
        $uactivity = $uactivity['keterangan'];
        $userlog = array(
            'uluser' => $this->session->id,
            'ulusername' => $this->session->nama,
            'ulcomputer' => $this->input->ip_address(),
            'ulactivity' => $uactivity.' '.$nomor,
            'ullevel'=> 0
        );
        $this->db->insert('auserlog',$userlog);

        $this->db->trans_complete();

        if($this->db->trans_status() === FALSE){
            return "rollback";
        } else {
            return "sukses";
        }

    }

    function autonumber($tgl){
        $cabang  = @$_SESSION['cabang'];
        $kodecabang  = @$_SESSION['kodecabang'];
        $nomor = 0;
        $nomor1 = $this->M_transaksi->prefixtrans(element('PB_Permintaan_Barang',NID));
        $nomor2 = tgl_notrans($tgl);

        $notrans_length = strlen($nomor1)+4;

        $sql = "SELECT MAX(RIGHT(pbunotransaksi,4)) as 'maks'
                  FROM fpermintaanbarangu
                 WHERE MID(pbunotransaksi,4,".$notrans_length.")='".$nomor1.$nomor2."' and pbugudang='".$cabang."'";

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
        $nomor=$kodecabang."-".$nomor ;
        return $nomor;
    }

}
