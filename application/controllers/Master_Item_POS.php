<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Master_Item_POS extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        if (!$this->session->has_userdata('nama')) {
            redirect(base_url('exception'));
        }
        $this->load->model('M_Master_Item_POS');
        $this->load->model('M_transaksi');
    }

    function savedata(){
        if ($this->input->post('id') == '') {
            echo $this->M_Master_Item_POS->tambahData();
        } else {
            echo $this->M_Master_Item_POS->ubahData();
        }
    }

    function getdata(){
        if ($this->input->post('id') == '' || $this->input->post('id') == null) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.iid 'id', A.ikode 'kode', A.inama 'nama', A.icomersialname 'namaweb',
                         A.ikategori 'kategori', A.istatus 'status', A.iserial 'serial',
                         IFNULL(A.iqtyperbox,1) 'qtyperbox',
                         A.isatuand 'idsatuand', C.skode 'satuand',
                         A.isatuan 'idsatuan', B.skode 'satuan',
                         IFNULL(A.istockmaksimal,0) 'stokmaks', IFNULL(A.istockminimal,0) 'stokmin',
                         IFNULL(A.istockreorder,0) 'stokreorder', IFNULL(A.imaxorder,3) 'maxorder',
                         IFNULL(A.ihargajual1,0) 'hargajual1', IFNULL(A.ihargajual2,0) 'hargajual2',
                         IFNULL(A.ihargabeli,0) 'hargabeli', IFNULL(A.ihargajualkaryawan,0) 'hargakaryawan',
                         IFNULL(A.ihargadepo,0) 'hargadepo', IFNULL(A.ihargaweb,0) 'hargaweb',
                         IFNULL(A.ihargaweb2,0) 'hargaweb2', IFNULL(A.ihargadifaktur,0) 'hargadifaktur',
                         IFNULL(A.ihargaproduk,0) 'hargaproduk', IFNULL(A.ihargaalkes,0) 'hargaalkes',
                         IFNULL(A.idiskon,0) 'diskon', IFNULL(A.icogs,0) 'cogs',
                         IFNULL(A.icogs_po,0) 'cogspo', IFNULL(A.icogspabrik,0) 'cogsrii',
                         A.ijenisitem 'idjenisitem', D.jkode 'jenisitem',
                         IFNULL(A.itipeitem,0) 'tipepersediaan',
                         A.ijenisitemcoa 'idjenisitemcoa', E.ctnama 'jenisitemcoa',
                         A.ikelompokbaru 'idkelompokbaru', F.jkode 'kelompokbaru',
                         A.ikelompok2020 'idkelompok2020', G.ik2kode 'kelompok2020',
                         A.ikelompok21 'idkelompok21', H.ik21kode 'kelompok21',
                         A.ikelompok23 'idkelompok23', I.ik23kode 'kelompok23',
                         A.icoa2021 'idcoa2021', J.ctnama 'coa2021',
                         A.ikomisi2020 'idkomisi2020', K.ik2kode 'komisi2020',
                         A.ijenisdiweb 'idjenisweb', L.ijkode 'jenisweb',
                         IFNULL(A.imodel,'') 'model',
                         M.I2COAPENDAPATAN 'idjeniscoapendapatan', N.ctnama 'jeniscoapendapatan',
                         IFNULL(A.ijenis,0) 'tidakdihitungjumlahpasien', IFNULL(A.ikomisimarketing,0) 'komisimarketing',
                         IFNULL(A.ikomisipaket,0) 'komisipaket', IFNULL(A.isharing,0) 'bisasharing',
                         IFNULL(A.isubkategori,0) 'cetak', IFNULL(A.ikecepatanjual,0) 'komisidokter',
                         IFNULL(A.ipromo,0) 'promo', IFNULL(A.ihargablank,0) 'hargablank',
                         IFNULL(A.ipaketdisemuacabang,0) 'paketsemuacabang', IFNULL(A.ipasienbarusaja,0) 'pasienbarusaja',
                         IFNULL(A.itindakanproduk,0) 'tindakanproduk', IFNULL(A.ibhp,0) 'bhp',
                         IFNULL(A.itidaktampildimedlib,0) 'tidaktampildimedlib', IFNULL(A.iresep,0) 'resep',
                         IFNULL(A.ihargajual5,0) 'persentasekomisi', IFNULL(A.ihargajual6,0) 'nilaikomisi',
                         IFNULL(A.ihargajual4,0) 'nilaikomisiperqty', IFNULL(A.ihargajual3,0) 'minimalqty',
                         IFNULL(A.iberat,0) 'berat',
                         IFNULL(A.ipoharga,0) 'hargapo', IFNULL(A.ipoqty,0) 'qtypo',
                         IFNULL(A.ipokemasan,'') 'kemasan', IFNULL(A.icoding,'') 'coding',
                         IFNULL(A.iponama,'') 'namapo',
                         IFNULL(O.IDDAPSFEEDOKTER,0) 'dapsfeedokter', IFNULL(O.IDDAPSFEEPERAWAT,0) 'dapsfeeperawat',
                         IFNULL(O.IDDAPSALKES,0) 'dapsalkes', IFNULL(O.IDDAPSEQUIPMENT,0) 'dapsequipment',
                         IFNULL(O.IDDAPSFACILITY,0) 'dapsfacility', IFNULL(O.IDDAPSJASAKLINIK,0) 'dapsjasaklinik',
                         IFNULL(O.IDDAPSSALESCOM,0) 'dapssalescomm'
                    FROM bitem A
               LEFT JOIN bsatuan B ON A.isatuan=B.sid
               LEFT JOIN bsatuan C ON A.isatuand=C.sid
               LEFT JOIN bitemjenis D ON A.ijenisitem=D.jid
               LEFT JOIN bcoatipe_pendapatan E ON A.ijenisitemcoa=E.ctid
               LEFT JOIN bitemjenis F ON A.ikelompokbaru=F.jid
               LEFT JOIN bitemkelompok2020 G ON A.ikelompok2020=G.ik2id
               LEFT JOIN bitemkelompok2021 H ON A.ikelompok21=H.ik21id
               LEFT JOIN bitemkelompok2023 I ON A.ikelompok23=I.ik23id
               LEFT JOIN bcoatipe_perpt J ON A.icoa2021=J.ctid
               LEFT JOIN bitemkelompok2020 K ON A.ikomisi2020=K.ik2id
               LEFT JOIN bitemjenisweb L ON A.ijenisdiweb=L.ijid
               LEFT JOIN bitem2 M ON M.I2IDITEM=A.iid
               LEFT JOIN bcoatipe_pendapatan N ON M.I2COAPENDAPATAN=N.ctid
               LEFT JOIN bitemdaps O ON O.IDITEM=A.iid
                   WHERE A.iid='".$this->input->post('id')."'";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function getcabang(){
        $id = $this->input->post('id');
        $icabang = '';

        if ($id != '') {
            $row = $this->db->query("SELECT icabang FROM bitem WHERE iid=?", array($id))->row();
            if ($row && $row->icabang !== null) $icabang = $row->icabang;
        }

        $pilih = array_filter(array_map('trim', explode('|', $icabang)));

        $query = "SELECT GID 'gid', GKODE 'gkode', GNAMA 'gnama'
                    FROM bgudang
                   WHERE GAKTIF<>0
                ORDER BY GID ASC";

        $data = $this->db->query($query)->result_array();
        foreach ($data as &$row2) {
            $row2['dipilih'] = in_array((string)$row2['gid'], $pilih) ? 1 : 0;
        }

        header('Content-Type: application/json');
        echo json_encode(array('data' => $data));
    }

    function deletedata(){
        echo $this->M_Master_Item_POS->hapusData();
    }

    // Update Harga Marketplace (bitem2.I2HARGAJUALMP)
    function gethargamp(){
        $id = (int) $this->input->post('id');
        if ($id <= 0) {
            echo _pesanError("Data tidak ditemukan !");
            exit;
        }

        $query = "SELECT A.iid 'id', A.ikode 'kode', A.inama 'nama',
                         IFNULL(A.ihargajual1,0) 'hargajual1',
                         IFNULL(B.I2HARGAJUALMP,0) 'hargamp'
                    FROM bitem A
               LEFT JOIN bitem2 B ON B.I2IDITEM=A.iid
                   WHERE A.iid='".$id."'";

        header('Content-Type: application/json');
        echo $this->M_transaksi->get_data_query($query);
    }

    function updhargamp(){
        echo $this->M_Master_Item_POS->updateHargaMp();
    }

}
