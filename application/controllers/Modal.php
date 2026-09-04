<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Modal extends CI_Controller {

    function __construct(){
        parent::__construct();     
		$this->load->helper('url');		        
		if(!$this->session->has_userdata('nama')){
?>
        <script>
              window.parent.location.href="<? echo base_url('login/aksi_logout');?>";    
        </script>
<?php
		}		
    }

    function loader($link){
		$this->load->view($link);		
    }

	// Modal Cari Transaksi
    function cari_kupon(){
    	$this->loader('include/modal-kupon-p');
    }

	// Modal Cari Transaksi
    function cari_transaksi(){
    	$this->loader('include/modal-transaksi');
    }

    // Modal Jurnal
    function lihat_jurnal(){
        $this->loader('include/modal-transaksi-jurnal');
    }

    // Modal Cari Transaksi Multi
    function cari_transaksi_multiple(){
        $this->loader('include/modal-transaksi-multiple');
    }

    // Modal Cari Uang Muka
    function cari_transaksi_uangmuka(){
        $this->loader('include/modal-uangmuka');
    }    

    // Modal Cari Transaksi Referensi
    function cari_transaksi_r(){
        $this->loader('include/modal-transaksi-referensi');
    }

    // Modal Cari Faktur
    function cari_faktur(){
        $this->loader('include/modal-carifaktur');
    }

    // Modal Cari Faktur
    function cari_faktur_web(){
        $this->loader('include/modal-carifaktur2');
    }

    // Modal Cari Faktur
    function cari_histori(){
        $this->loader('include/modal-transaksi-histori');
    }

    // Modal Cari Kontak Filter Laporan
    function cari_kontak_report(){
        $this->loader('include/modal-kontak');
    }

    // Modal Cari Kontak
    function cari_kontak(){
        $this->loader('include/modal-kontak-p');
    }

    // Modal Cari Kontak
    function cari_kontak_pos(){
        $this->loader('include/modal-kontak-pos');
    }


    // Modal Cari Kontak Attention
    function cari_kontak_attention(){
        $this->loader('include/modal-kontak-person-p');
    }  

    // Modal Cari paket
    function cari_paket(){
        $this->loader('include/modal-paket');
    }

    // Modal Cari paket
    function cari_promo(){
        $this->loader('include/modal-promo');
    }

    // Modal Cari paket
    function cari_barang(){
        $this->loader('include/modal-item-p');
    }

    // Modal Cari voucer
    function cari_diskonvocer(){
        $this->loader('include/modal-diskonvocer');
    }

    // Modal Cari Tujuan Permintaan Barang
    function cari_tujuan(){
        $this->loader('include/modal-cari-tujuan');
    }

    // Modal Cari Gudang
    function cari_gudang(){
        $this->loader('include/modal-cari-gudang');
    }

    // Modal Cari Item Permintaan Barang
    function cari_item(){
        $this->loader('include/modal-cari-item');
    }
  




	// Modal Cari Transaksi
    function form_editdepo(){
        $this->loader('modul/transaksi/penjualan/form-editdepo');
    }
    
    // Modal Form Penomoran
    function form_penomoran(){
        $this->loader('modul/administrator/form-penomoran');
    }        

    // Modal Form Aktiva Tetap
    function form_aktiva(){
        $this->loader('modul/master/form-aktiva');
    }        

    // Modal Form Kelompok Aktiva Tetap
    function form_kelompok_aktiva(){
        $this->loader('modul/master/form-kelompok-aktiva');
    }            

    // Modal Form COA
    function form_coa(){
        $this->loader('modul/master/form-coa');
    }        

    // Modal Form Item & Jasa
    function form_item(){
        $this->loader('modul/master/form-item');
    }

    function form_item_pos(){
        $this->loader('modul/master/form-item-pos');
    }

    // Modal Form Update Harga Marketplace
    function form_update_harga_mp(){
        $this->loader('modul/master/form-update-harga-mp');
    }

    // Modal Form Kontak
    function form_kontak(){
        $this->loader('modul/master/form-kontak');
    }            

    // Modal Form Bank
    function form_bank(){
        $this->loader('modul/master/form-bank');
    }            

    // Modal Form Mata Uang
    function form_uang(){
        $this->loader('modul/master/form-uang');
    }                

    // Modal Form Termin
    function form_termin(){
        $this->loader('modul/master/form-termin');
    }                

    // Modal Form pajak
    function form_pajak(){
        $this->loader('modul/master/form-pajak');
    }                    

    // Modal Form Proyek
    function form_proyek(){
        $this->loader('modul/master/form-proyek');
    }                        

    // Modal Form Gudang
    function form_gudang(){
        $this->loader('modul/master/form-gudang');
    }                        

    // Modal Form Satuan
    function form_satuan(){
        $this->loader('modul/master/form-satuan');
    }                        

    // Modal Form Kategori Kontak
    function form_kategori_kontak(){
        $this->loader('modul/master/form-kategori-kontak');
    }                            

    // Modal Form Divisi
    function form_divisi(){
        $this->loader('modul/master/form-divisi');
    }

    // Modal Form Data Pasien
    function form_pasien(){
        $this->loader('modul/master/form-pasien');
    }

    // Modal Form Data Karyawan
    function form_karyawan(){
        $this->loader('modul/master/form-karyawan');
    }

    // Modal Form Data Lain
    function form_lain(){
        $this->loader('modul/master/form-lain');
    }

    // Modal Form Master Data Role
    function form_role(){
        $this->loader('modul/master/form-role');
    }

    // Modal Profil Akun
    function profil_akun(){
        $this->loader('modul/administrator/form-profil');
    }

    // Modal Verifikasi Permintaan Barang
    function verifikasi_pmb(){
        $this->loader('include/modal-verifikasi-pmb');
    }

    function persetujuan(){
        $this->loader('include/modal-persetujuan');
    }

   // Modal Form Divisi
    function form_jenis_penyesuaian(){
        $this->loader('modul/master/form-jenis-penyesuaian');
    }                                    

    // Modal Uang Muka Pembelian
    function uang_muka_pembelian(){
        $this->loader('modul/transaksi/pembelian/uang-muka-pembelian');
    }        

    // Modal Potongan PPH Pembelian
    function pph_pembelian(){
        $this->loader('modul/transaksi/pembelian/potongan-pph');
    }            

    // Modal Potongan PPH Penjualan
    function pph_penjualan(){
        $this->loader('modul/transaksi/penjualan/potongan-pph');
    }                

    // Modal Pembayaran POS
    function pembayaran_pos(){
        $this->loader('modul/transaksi/penjualan/pembayaran-pos');
    }            

    // Modal Form Periode
    function form_periode(){
        $this->loader('modul/administrator/form-periode');
    }                                

    // Modal Form Admin Menu
    function form_menu(){
        $this->loader('modul/administrator/form-menu');
    }                                    

    // Modal Form Admin User
    function form_user(){
        $this->loader('modul/administrator/form-user');
    }                                        

   // Modal Form Admin Laporan
    function form_report(){
        $this->loader('modul/administrator/form-report');
    }    
    
    
	// Modal form Voucher
    function form_voucher(){
        $this->loader('modul/master/form-voucher');
    }

}