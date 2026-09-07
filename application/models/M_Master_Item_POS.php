<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_Master_Item_POS extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }

    function tambahData()
    {
        $data = array(
                'ikode' => $_POST['kode'],
                'inama' => $_POST['nama'],
                'icomersialname' => $_POST['namaweb'],
                'ikategori' => $_POST['kategori'],
                'istatus' => $_POST['status'],
                'iserial' => $_POST['serial'],
                'iqtyperbox' => $_POST['qtyperbox'],
                'isatuand' => $_POST['satuand'],
                'isatuan' => $_POST['satuan'],
                'istockmaksimal' => $_POST['stokmaks'],
                'istockminimal' => $_POST['stokmin'],
                'istockreorder' => $_POST['stokreorder'],
                'imaxorder' => $_POST['maxorder'],
                'ihargajual1' => $_POST['hargajual1'],
                'ihargajual2' => $_POST['hargajual2'],
                'ihargabeli' => $_POST['hargabeli'],
                'ihargajualkaryawan' => $_POST['hargakaryawan'],
                'ihargadepo' => $_POST['hargadepo'],
                'ihargaweb' => $_POST['hargaweb'],
                'ihargaweb2' => $_POST['hargaweb2'],
                'ihargadifaktur' => $_POST['hargadifaktur'],
                'ihargaproduk' => $_POST['hargaproduk'],
                'ihargaalkes' => $_POST['hargaalkes'],
                'idiskon' => $_POST['diskon'],
                'icogs' => $_POST['cogs'],
                'icogs_po' => $_POST['cogspo'],
                'icogspabrik' => $_POST['cogsrii'],
                'ijenisitem' => $_POST['jenisitem'] == '' ? NULL : $_POST['jenisitem'],
                'itipeitem' => $_POST['tipepersediaan'],
                'ijenisitemcoa' => $_POST['jenisitemcoa'] == '' ? NULL : $_POST['jenisitemcoa'],
                'ikelompokbaru' => $_POST['kelompokbaru'] == '' ? NULL : $_POST['kelompokbaru'],
                'ikelompok2020' => $_POST['kelompok2020'] == '' ? NULL : $_POST['kelompok2020'],
                'ikelompok21' => $_POST['kelompok21'] == '' ? NULL : $_POST['kelompok21'],
                'ikelompok23' => $_POST['kelompok23'] == '' ? NULL : $_POST['kelompok23'],
                'icoa2021' => $_POST['coa2021'] == '' ? NULL : $_POST['coa2021'],
                'ikomisi2020' => $_POST['komisi2020'] == '' ? NULL : $_POST['komisi2020'],
                'ijenisdiweb' => $_POST['jenisweb'] == '' ? NULL : $_POST['jenisweb'],
                'imodel' => $_POST['model'],
                'ijenis' => $_POST['tidakdihitungjumlahpasien'],
                'ikomisimarketing' => $_POST['komisimarketing'],
                'ikomisipaket' => $_POST['komisipaket'],
                'isharing' => $_POST['bisasharing'],
                'isubkategori' => $_POST['cetak'],
                'ikecepatanjual' => $_POST['komisidokter'],
                'ipromo' => $_POST['promo'],
                'ihargablank' => $_POST['hargablank'],
                'ipaketdisemuacabang' => $_POST['paketsemuacabang'],
                'ipasienbarusaja' => $_POST['pasienbarusaja'],
                'itindakanproduk' => $_POST['tindakanproduk'],
                'ibhp' => $_POST['bhp'],
                'itidaktampildimedlib' => $_POST['tidaktampildimedlib'],
                'iresep' => $_POST['resep'],
                'ihargajual5' => $_POST['persentasekomisi'],
                'ihargajual6' => $_POST['nilaikomisi'],
                'ihargajual4' => $_POST['nilaikomisiperqty'],
                'ihargajual3' => $_POST['minimalqty'],
                'iberat' => $_POST['berat'],
                'ipoharga' => $_POST['hargapo'],
                'ipoqty' => $_POST['qtypo'],
                'ipokemasan' => $_POST['kemasan'],
                'icoding' => $_POST['coding'],
                'iponama' => $_POST['namapo'],
                'icabang' => $_POST['cabang'],
                'icreateu' => $this->session->id
        );

        $this->db->trans_start();
        $this->db->insert('bitem', $data);
        $iid = $this->db->insert_id();
        $this->_upsertBitem2CoaPendapatan($iid);
        $this->_upsertBitemDaps($iid);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function _upsertBitem2CoaPendapatan($iid)
    {
        $coapendapatan = $_POST['jeniscoapendapatan'] == '' ? NULL : $_POST['jeniscoapendapatan'];

        $cek = $this->db->get_where('bitem2', array('I2IDITEM' => $iid))->row();

        if ($cek) {
            $this->db->where('I2IDITEM', $iid);
            $this->db->update('bitem2', array('I2COAPENDAPATAN' => $coapendapatan));
        } else {
            $this->db->insert('bitem2', array('I2IDITEM' => $iid, 'I2COAPENDAPATAN' => $coapendapatan));
        }
    }

    function _upsertBitemDaps($iid)
    {
        $data = array(
                'IDDAPSFEEDOKTER' => $_POST['dapsfeedokter'],
                'IDDAPSFEEPERAWAT' => $_POST['dapsfeeperawat'],
                'IDDAPSALKES' => $_POST['dapsalkes'],
                'IDDAPSEQUIPMENT' => $_POST['dapsequipment'],
                'IDDAPSFACILITY' => $_POST['dapsfacility'],
                'IDDAPSJASAKLINIK' => $_POST['dapsjasaklinik'],
                'IDDAPSSALESCOM' => $_POST['dapssalescomm']
        );

        $cek = $this->db->get_where('bitemdaps', array('IDITEM' => $iid))->row();

        if ($cek) {
            $this->db->where('IDITEM', $iid);
            $this->db->update('bitemdaps', $data);
        } else {
            $data['IDITEM'] = $iid;
            $this->db->insert('bitemdaps', $data);
        }
    }

    function ubahData()
    {
        $id = $_POST['id'];
        $data = array(
                'ikode' => $_POST['kode'],
                'inama' => $_POST['nama'],
                'icomersialname' => $_POST['namaweb'],
                'ikategori' => $_POST['kategori'],
                'istatus' => $_POST['status'],
                'iserial' => $_POST['serial'],
                'iqtyperbox' => $_POST['qtyperbox'],
                'isatuand' => $_POST['satuand'],
                'isatuan' => $_POST['satuan'],
                'istockmaksimal' => $_POST['stokmaks'],
                'istockminimal' => $_POST['stokmin'],
                'istockreorder' => $_POST['stokreorder'],
                'imaxorder' => $_POST['maxorder'],
                'ihargajual1' => $_POST['hargajual1'],
                'ihargajual2' => $_POST['hargajual2'],
                'ihargabeli' => $_POST['hargabeli'],
                'ihargajualkaryawan' => $_POST['hargakaryawan'],
                'ihargadepo' => $_POST['hargadepo'],
                'ihargaweb' => $_POST['hargaweb'],
                'ihargaweb2' => $_POST['hargaweb2'],
                'ihargadifaktur' => $_POST['hargadifaktur'],
                'ihargaproduk' => $_POST['hargaproduk'],
                'ihargaalkes' => $_POST['hargaalkes'],
                'idiskon' => $_POST['diskon'],
                'icogs' => $_POST['cogs'],
                'icogs_po' => $_POST['cogspo'],
                'icogspabrik' => $_POST['cogsrii'],
                'ijenisitem' => $_POST['jenisitem'] == '' ? NULL : $_POST['jenisitem'],
                'itipeitem' => $_POST['tipepersediaan'],
                'ijenisitemcoa' => $_POST['jenisitemcoa'] == '' ? NULL : $_POST['jenisitemcoa'],
                'ikelompokbaru' => $_POST['kelompokbaru'] == '' ? NULL : $_POST['kelompokbaru'],
                'ikelompok2020' => $_POST['kelompok2020'] == '' ? NULL : $_POST['kelompok2020'],
                'ikelompok21' => $_POST['kelompok21'] == '' ? NULL : $_POST['kelompok21'],
                'ikelompok23' => $_POST['kelompok23'] == '' ? NULL : $_POST['kelompok23'],
                'icoa2021' => $_POST['coa2021'] == '' ? NULL : $_POST['coa2021'],
                'ikomisi2020' => $_POST['komisi2020'] == '' ? NULL : $_POST['komisi2020'],
                'ijenisdiweb' => $_POST['jenisweb'] == '' ? NULL : $_POST['jenisweb'],
                'imodel' => $_POST['model'],
                'ijenis' => $_POST['tidakdihitungjumlahpasien'],
                'ikomisimarketing' => $_POST['komisimarketing'],
                'ikomisipaket' => $_POST['komisipaket'],
                'isharing' => $_POST['bisasharing'],
                'isubkategori' => $_POST['cetak'],
                'ikecepatanjual' => $_POST['komisidokter'],
                'ipromo' => $_POST['promo'],
                'ihargablank' => $_POST['hargablank'],
                'ipaketdisemuacabang' => $_POST['paketsemuacabang'],
                'ipasienbarusaja' => $_POST['pasienbarusaja'],
                'itindakanproduk' => $_POST['tindakanproduk'],
                'ibhp' => $_POST['bhp'],
                'itidaktampildimedlib' => $_POST['tidaktampildimedlib'],
                'iresep' => $_POST['resep'],
                'ihargajual5' => $_POST['persentasekomisi'],
                'ihargajual6' => $_POST['nilaikomisi'],
                'ihargajual4' => $_POST['nilaikomisiperqty'],
                'ihargajual3' => $_POST['minimalqty'],
                'iberat' => $_POST['berat'],
                'ipoharga' => $_POST['hargapo'],
                'ipoqty' => $_POST['qtypo'],
                'ipokemasan' => $_POST['kemasan'],
                'icoding' => $_POST['coding'],
                'iponama' => $_POST['namapo'],
                'icabang' => $_POST['cabang'],
                'imodifu' => $this->session->id
        );

        $this->db->trans_start();
        $this->db->where('iid', $id);
        $this->db->update('bitem', $data);
        $this->_upsertBitem2CoaPendapatan($id);
        $this->_upsertBitemDaps($id);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    function hapusData()
    {
        $id = $_POST['id'];

        $this->db->trans_start();
        $this->db->where('iid', $id);
        $this->db->delete('bitem');
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    // Update Harga Marketplace: hanya kolom I2HARGAJUALMP di tabel bitem2
    function updateHargaMp()
    {
        $id    = (int) $this->input->post('id');
        $harga = $this->input->post('hargamp');
        $harga = ($harga === '' || $harga === null) ? 0 : str_replace(',', '', $harga);

        if ($id <= 0) {
            return "Item tidak valid";
        }
        if (!is_numeric($harga)) {
            return "Nilai harga tidak valid";
        }
        $harga = (float) $harga;

        $item = $this->db->query(
            "SELECT A.ikode, A.inama, IFNULL(B.I2HARGAJUALMP,0) AS hargalama
               FROM bitem A
          LEFT JOIN bitem2 B ON B.I2IDITEM = A.iid
              WHERE A.iid = ".$id." LIMIT 1"
        )->row();

        if (!$item) {
            return "Item tidak ditemukan";
        }
        $hargaLama = (float) $item->hargalama;

        $this->db->trans_start();

        $cek = $this->db->get_where('bitem2', array('I2IDITEM' => $id))->row();
        if ($cek) {
            $this->db->where('I2IDITEM', $id);
            $this->db->update('bitem2', array('I2HARGAJUALMP' => $harga));
        } else {
            $this->db->insert('bitem2', array('I2IDITEM' => $id, 'I2HARGAJUALMP' => $harga));
        }

        // USERLOG: catat produk yang diubah + harga lama & baru (hanya bila berubah)
        if ($hargaLama != $harga) {
            $keterangan = 'Update Harga MP: '.$item->ikode.' '.$item->inama
                        .' | Lama: '.number_format($hargaLama, 0, ',', '.')
                        .' -> Baru: '.number_format($harga, 0, ',', '.');
            $this->db->insert('aauserlog', array(
                'ULUSER'     => $this->session->id,
                'ULUSERNAME' => $this->session->nama,
                'ULCOMPUTER' => $this->input->ip_address(),
                'ULACTIVITY' => substr($keterangan, 0, 255),
                'ULLEVEL'    => 2,
            ));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

    // Update SKU Marketplace: kolom I2SKUSHOPEE & I2SKUTOKOPEDIA di tabel bitem2
    function updateSkuMp()
    {
        $id       = (int) $this->input->post('id');
        $shopee   = mb_substr(trim((string) $this->input->post('skushopee')), 0, 50);
        $tokopedia= mb_substr(trim((string) $this->input->post('skutokopedia')), 0, 50);

        if ($id <= 0) {
            return "Item tidak valid";
        }

        $item = $this->db->query(
            "SELECT A.ikode, A.inama,
                    IFNULL(B.I2SKUSHOPEE,'') AS skushopeelama,
                    IFNULL(B.I2SKUTOKOPEDIA,'') AS skutokopedialama
               FROM bitem A
          LEFT JOIN bitem2 B ON B.I2IDITEM = A.iid
              WHERE A.iid = ".$id." LIMIT 1"
        )->row();

        if (!$item) {
            return "Item tidak ditemukan";
        }

        $data = array(
            'I2SKUSHOPEE'    => $shopee,
            'I2SKUTOKOPEDIA' => $tokopedia,
        );

        $this->db->trans_start();

        $cek = $this->db->get_where('bitem2', array('I2IDITEM' => $id))->row();
        if ($cek) {
            $this->db->where('I2IDITEM', $id);
            $this->db->update('bitem2', $data);
        } else {
            $data['I2IDITEM'] = $id;
            $this->db->insert('bitem2', $data);
        }

        // USERLOG: catat perubahan SKU (hanya bila berubah)
        if ($item->skushopeelama !== $shopee || $item->skutokopedialama !== $tokopedia) {
            $keterangan = 'Update SKU MP: '.$item->ikode.' '.$item->inama
                        .' | Shopee: '.$item->skushopeelama.' -> '.$shopee
                        .' | Tokopedia: '.$item->skutokopedialama.' -> '.$tokopedia;
            $this->db->insert('aauserlog', array(
                'ULUSER'     => $this->session->id,
                'ULUSERNAME' => $this->session->nama,
                'ULCOMPUTER' => $this->input->ip_address(),
                'ULACTIVITY' => substr($keterangan, 0, 255),
                'ULLEVEL'    => 2,
            ));
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return "rollback";
        } else {
            return "sukses";
        }
    }

}
