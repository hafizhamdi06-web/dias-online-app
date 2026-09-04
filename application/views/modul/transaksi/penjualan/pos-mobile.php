<body id="<?= $id; ?>" class="pm-body">
  <link rel="stylesheet" href="<?= app_url('assets/dist/css/modul/pos-mobile.css');?>">

  <div id="loader" class="pm-loader d-none">
    <i class="fas fa-circle-notch fa-spin"></i>
  </div>

  <div class="pm-header">
    <h5 class="pm-title">POS Mobile</h5>
    <div class="pm-header-actions">
      <button type="button" id="briwayat" class="pm-btn-riwayat"><i class="fas fa-history"></i> Hari Ini</button>
      <button type="button" id="briwayat-sebelum" class="pm-btn-riwayat"><i class="fas fa-calendar-alt"></i> Sebelumnya</button>
    </div>
  </div>

  <div class="pm-subheader">
    <span class="pm-subheader-label">Ukuran Struk</span>
    <div class="pm-lebar-toggle">
      <button type="button" class="pm-lebar-btn" data-lebar="58">58mm</button>
      <button type="button" class="pm-lebar-btn" data-lebar="80">80mm</button>
    </div>
  </div>

  <div id="pm-riwayat-panel" class="pm-riwayat-panel d-none">
    <button type="button" id="briwayat-tutup" class="pm-btn-riwayat-tutup"><i class="fas fa-arrow-left"></i> Kembali</button>

    <h6 id="pm-riwayat-judul" class="pm-riwayat-judul">Riwayat Hari Ini</h6>

    <div id="pm-riwayat-filter" class="pm-riwayat-filter d-none">
      <div class="pm-riwayat-filter-row">
        <div class="pm-riwayat-filter-field">
          <label>Cabang</label>
          <select id="pm-riwayat-cabang" class="pm-riwayat-tgl"></select>
        </div>
      </div>
      <div class="pm-riwayat-filter-row">
        <div class="pm-riwayat-filter-field">
          <label>Dari Tanggal</label>
          <input type="date" id="pm-riwayat-tgldari" class="pm-riwayat-tgl">
        </div>
        <div class="pm-riwayat-filter-field">
          <label>Sampai Tanggal</label>
          <input type="date" id="pm-riwayat-tglsampai" class="pm-riwayat-tgl">
        </div>
      </div>
      <button type="button" id="pm-riwayat-tampilkan" class="pm-riwayat-tampilkan"><i class="fas fa-search"></i> Tampilkan</button>
    </div>

    <div id="pm-riwayat-list" class="pm-itemlist"></div>

    <div id="pm-riwayat-empty" class="pm-empty">
      <i class="fas fa-receipt"></i>
      <p>Belum ada transaksi hari ini.</p>
    </div>
  </div>

  <div class="pm-content">

    <div id="pm-edit-banner" class="pm-edit-banner d-none">
      <span>Mode Edit: <span id="pm-edit-nomor"></span></span>
      <button type="button" id="pm-edit-batal">Batal Edit</button>
    </div>

    <div class="pm-card pm-card-pasien">
      <label class="pm-label">Pasien</label>
      <select id="pasien" class="pm-select" style="width:100%"></select>
      <div id="pasien-info" class="pm-pasien-info d-none">
        <i class="fas fa-id-card"></i> <span id="pasien-idpasien"></span>
      </div>
      <span id="pasien-kategori" class="pm-badge-kategori d-none"></span>
      <span id="pasien-member" class="pm-badge-member d-none"></span>
      <span id="pasien-diskon" class="pm-badge-diskon d-none"></span>
    </div>

    <div class="pm-card">
      <label class="pm-label">Tambah Item</label>
      <select id="carinama" class="pm-select" style="width:100%"></select>
    </div>

    <div id="pm-itemlist" class="pm-itemlist"></div>

    <div id="pm-item-empty" class="pm-empty">
      <i class="fas fa-shopping-basket"></i>
      <p>Belum ada item ditambahkan.</p>
    </div>

    <div class="pm-card pm-card-bayar">
      <label class="pm-label">Pembayaran</label>
      <div class="pm-metode-list">
        <button type="button" class="pm-metode-btn" data-metode="tunai">Tunai</button>
        <button type="button" class="pm-metode-btn" data-metode="debit">Debit</button>
        <button type="button" class="pm-metode-btn" data-metode="kredit">Kredit</button>
        <button type="button" class="pm-metode-btn" data-metode="transfer">Transfer</button>
        <button type="button" class="pm-metode-btn" data-metode="merchant">Merchant</button>
      </div>

      <div class="pm-metode-fields d-none" data-metode-fields="tunai">
        <div class="pm-metode-row">
          <div class="pm-metode-field pm-metode-field-nilai">
            <label>Nilai Tunai</label>
            <input type="text" id="pm-tunai-nilai" class="pm-metode-nilai" data-metode="tunai" value="0" inputmode="numeric">
          </div>
          <button type="button" class="pm-btn-sisa" data-metode="tunai">Isi Sisa</button>
        </div>
      </div>

      <div class="pm-metode-fields d-none" data-metode-fields="debit">
        <div class="pm-metode-row">
          <div class="pm-metode-field pm-metode-field-nilai">
            <label>Nilai Debit</label>
            <input type="text" id="pm-debit-nilai" class="pm-metode-nilai" data-metode="debit" value="0" inputmode="numeric">
          </div>
          <button type="button" class="pm-btn-sisa" data-metode="debit">Isi Sisa</button>
        </div>
        <div class="pm-metode-row">
          <div class="pm-metode-field">
            <label>No Kartu Debit</label>
            <input type="text" id="pm-debit-no" class="pm-metode-text" inputmode="numeric">
          </div>
          <div class="pm-metode-field">
            <label>Bank Debit</label>
            <select id="pm-debit-bank" class="pm-select" style="width:100%"></select>
          </div>
        </div>
      </div>

      <div class="pm-metode-fields d-none" data-metode-fields="kredit">
        <div class="pm-metode-row">
          <div class="pm-metode-field pm-metode-field-nilai">
            <label>Nilai Kredit</label>
            <input type="text" id="pm-kredit-nilai" class="pm-metode-nilai" data-metode="kredit" value="0" inputmode="numeric">
          </div>
          <button type="button" class="pm-btn-sisa" data-metode="kredit">Isi Sisa</button>
        </div>
        <div class="pm-metode-row">
          <div class="pm-metode-field">
            <label>No Kartu Kredit</label>
            <input type="text" id="pm-kredit-no" class="pm-metode-text" inputmode="numeric">
          </div>
          <div class="pm-metode-field">
            <label>Bank Kredit</label>
            <select id="pm-kredit-bank" class="pm-select" style="width:100%"></select>
          </div>
        </div>
      </div>

      <div class="pm-metode-fields d-none" data-metode-fields="transfer">
        <div class="pm-metode-row">
          <div class="pm-metode-field pm-metode-field-nilai">
            <label>Nilai Transfer</label>
            <input type="text" id="pm-transfer-nilai" class="pm-metode-nilai" data-metode="transfer" value="0" inputmode="numeric">
          </div>
          <button type="button" class="pm-btn-sisa" data-metode="transfer">Isi Sisa</button>
        </div>
        <div class="pm-metode-row">
          <div class="pm-metode-field">
            <label>No Ref Transfer</label>
            <input type="text" id="pm-transfer-no" class="pm-metode-text" inputmode="numeric">
          </div>
          <div class="pm-metode-field">
            <label>Bank Transfer</label>
            <select id="pm-transfer-bank" class="pm-select" style="width:100%"></select>
          </div>
        </div>
      </div>

      <div class="pm-metode-fields d-none" data-metode-fields="merchant">
        <div class="pm-metode-row">
          <div class="pm-metode-field pm-metode-field-nilai">
            <label>Nilai Merchant</label>
            <input type="text" id="pm-merchant-nilai" class="pm-metode-nilai" data-metode="merchant" value="0" inputmode="numeric">
          </div>
          <button type="button" class="pm-btn-sisa" data-metode="merchant">Isi Sisa</button>
        </div>
        <div class="pm-metode-row">
          <div class="pm-metode-field">
            <label>No Ref Merchant</label>
            <input type="text" id="pm-merchant-no" class="pm-metode-text" inputmode="numeric">
          </div>
          <div class="pm-metode-field">
            <label>Jenis Merchant</label>
            <select id="pm-merchant-jenis" class="pm-select" style="width:100%"></select>
          </div>
        </div>
      </div>

      <div class="pm-bayar-summary">
        <div class="pm-footer-row"><span>Total Dibayar</span><span id="pm-totaldibayar">0,00</span></div>
        <div class="pm-footer-row d-none" id="pm-row-kembali"><span>Kembali</span><span id="pm-kembali" class="pm-selisih-ok">0,00</span></div>
        <div class="pm-footer-row d-none" id="pm-row-kurang"><span>Kurang Bayar</span><span id="pm-kurang" class="pm-selisih-bad">0,00</span></div>
      </div>
    </div>

  </div>

  <div class="pm-footer">
    <div class="pm-footer-row">
      <span>Total</span>
      <span id="pm-total">0,00</span>
    </div>
    <button type="button" id="bsimpan" class="pm-btn-simpan">Simpan Transaksi</button>
  </div>

  <input type="hidden" id="idkontak">
  <input type="hidden" id="kontaktipe">
  <input type="hidden" id="cabang" value="<? echo @$_SESSION['cabang']; ?>">
  <input type="hidden" id="karyawan" value="<? echo @$_SESSION['idkaryawan']; ?>">
  <input type="hidden" id="tgl" value="<? echo date('d-m-Y'); ?>">

  <div id="pm-struk-print" class="pm-struk-print"></div>
  <style id="pm-page-size-style"></style>

<!-- JS Vendor (minimal, halaman ini dibuka langsung dari HP tanpa lewat dashboard) -->
<script src="<? echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/select2/select2.full.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script src="<? echo base_url('assets/plugins/input-mask/jquery.inputmask.bundle.js'); ?>"></script>
<!-- JS Custom -->
<script type="module" src="<? echo app_url('assets/dist/js/modul/transaksi/penjualan/pos-mobile.js'); ?>"></script>
</body>
</html>
