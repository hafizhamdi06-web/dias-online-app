<form method="post">
<input type="hidden" id="id" name="id">
<div class="modal-body">
<div class="row mx-1">
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Kode *</label>
<div class="col-sm-4">
  <input type="search" class="form-control form-control-sm" id="kode" name="kode" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Nama Web</label>
<div class="col-sm-4">
  <input type="search" class="form-control form-control-sm" id="namaweb" name="namaweb" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
</div>
<div class="row mx-1">
<label class="col-sm-2 col-form-label text-sm font-weight-normal">Nama *</label>
<div class="col-sm-10">
  <input type="search" class="form-control form-control-sm" id="nama" name="nama" autocomplete="off" data-trigger="manual" data-placement="auto">
</div>
</div>
<div class="row mt-3 mx-1">
<div class="col-sm-12">
  <div id="tabItemPos" class="card card-primary card-outline card-outline-tabs" style="box-shadow: none">
    <div class="card-header p-0">
      <ul class="nav nav-tabs" id="custom-tabs-itempos-tab" role="tablist">
        <li class="nav-item">
          <a class="nav-link text-sm active" id="btn-tab-detail" data-toggle="pill" href="#tab-detail" role="tab" aria-controls="tab-detail" aria-selected="true" tabindex="-1">Detail</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-harga" data-toggle="pill" href="#tab-harga" role="tab" aria-controls="tab-harga" aria-selected="false" tabindex="-1">Harga</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-pengelompokan" data-toggle="pill" href="#tab-pengelompokan" role="tab" aria-controls="tab-pengelompokan" aria-selected="false" tabindex="-1">Pengelompokan</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-infolain" data-toggle="pill" href="#tab-infolain" role="tab" aria-controls="tab-infolain" aria-selected="false" tabindex="-1">Info Lain</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-po" data-toggle="pill" href="#tab-po" role="tab" aria-controls="tab-po" aria-selected="false" tabindex="-1">PO</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-daps" data-toggle="pill" href="#tab-daps" role="tab" aria-controls="tab-daps" aria-selected="false" tabindex="-1">DAPS</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-sm" id="btn-tab-cabang" data-toggle="pill" href="#tab-cabang" role="tab" aria-controls="tab-cabang" aria-selected="false" tabindex="-1">Cabang</a>
        </li>
      </ul>
    </div>
    <div class="card-body card-outline-tabs-body px-0 mx-0">
      <div class="tab-content">
        <div class="tab-pane active show text-sm" id="tab-detail" role="tabpanel" aria-labelledby="btn-tab-detail">
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kategori</label>
            <div class="col-sm-4">
              <select id="kategori" name="kategori" class="form-control select2 w-100" style="width:100%">
                <option value="0">Biasa</option>
                <option value="1">Ada Penyusun</option>
                <option value="2">Penyusun Langsung</option>
              </select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Status</label>
            <div class="col-sm-4">
              <select id="status" name="status" class="form-control select2 w-100" style="width:100%">
                <option value="0">Aktif</option>
                <option value="1">Tidak Aktif</option>
                <option value="2">Tidak Terpakai</option>
              </select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Satuan Dasar *</label>
            <div class="col-sm-4">
              <select id="satuandasar" name="satuandasar" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Satuan Default *</label>
            <div class="col-sm-4">
              <select id="satuandefault" name="satuandefault" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Maksimal</label>
            <div class="col-sm-4">
              <input id="stokmaks" type="text" class="form-control form-control-sm qty" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Minimal</label>
            <div class="col-sm-4">
              <input id="stokmin" type="text" class="form-control form-control-sm qty" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Stok Reorder</label>
            <div class="col-sm-4">
              <input id="stokreorder" type="text" class="form-control form-control-sm qty" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Max Order</label>
            <div class="col-sm-4">
              <input id="maxorder" type="text" class="form-control form-control-sm qty" value="3">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Qty Per Box</label>
            <div class="col-sm-4">
              <input id="qtyperbox" type="text" class="form-control form-control-sm qty" value="1">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-2">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="serial">
                <label class="form-check-label text-sm" for="serial" role="button">Serial</label>
              </div>
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-harga" role="tabpanel" aria-labelledby="btn-tab-harga">
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Jual 1</label>
            <div class="col-sm-4">
              <input id="hargajual1" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Jual 2</label>
            <div class="col-sm-4">
              <input id="hargajual2" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Beli</label>
            <div class="col-sm-4">
              <input id="hargabeli" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Jual Karyawan</label>
            <div class="col-sm-4">
              <input id="hargakaryawan" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Depo</label>
            <div class="col-sm-4">
              <input id="hargadepo" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Web</label>
            <div class="col-sm-4">
              <input id="hargaweb" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Web 2</label>
            <div class="col-sm-4">
              <input id="hargaweb2" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Di Faktur</label>
            <div class="col-sm-4">
              <input id="hargadifaktur" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Produk</label>
            <div class="col-sm-4">
              <input id="hargaproduk" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga Alkes</label>
            <div class="col-sm-4">
              <input id="hargaalkes" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Discount</label>
            <div class="col-sm-4">
              <input id="diskon" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">COGS</label>
            <div class="col-sm-4">
              <input id="cogs" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">COGS PO</label>
            <div class="col-sm-4">
              <input id="cogspo" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">COGS RII (Pabrik)</label>
            <div class="col-sm-4">
              <input id="cogsrii" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-pengelompokan" role="tabpanel" aria-labelledby="btn-tab-pengelompokan">
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Jenis Item</label>
            <div class="col-sm-4">
              <select id="jenisitem" name="jenisitem" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Tipe Persediaan</label>
            <div class="col-sm-4">
              <select id="tipepersediaan" name="tipepersediaan" class="form-control select2 w-100" style="width:100%">
                <option value="0">Stok</option>
                <option value="1">Non Stok</option>
              </select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Jenis Item COA</label>
            <div class="col-sm-4">
              <select id="jenisitemcoa" name="jenisitemcoa" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Jenis COA Pendapatan</label>
            <div class="col-sm-4">
              <select id="jeniscoapendapatan" name="jeniscoapendapatan" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kelompok Baru</label>
            <div class="col-sm-4">
              <select id="kelompokbaru" name="kelompokbaru" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kelompok 2020</label>
            <div class="col-sm-4">
              <select id="kelompok2020" name="kelompok2020" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kelompok 2021</label>
            <div class="col-sm-4">
              <select id="kelompok21" name="kelompok21" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kelompok 2023</label>
            <div class="col-sm-4">
              <select id="kelompok23" name="kelompok23" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Jenis COA 2021</label>
            <div class="col-sm-4">
              <select id="coa2021" name="coa2021" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kelompok Komisi 2020</label>
            <div class="col-sm-4">
              <select id="komisi2020" name="komisi2020" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Jenis Web</label>
            <div class="col-sm-4">
              <select id="jenisweb" name="jenisweb" class="form-control select2" style="width:100%" data-trigger="manual" data-placement="auto"></select>
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Model / Kelompok Harga</label>
            <div class="col-sm-4">
              <select id="model" name="model" class="form-control select2 w-100" style="width:100%">
                <option value="">-</option>
                <option value="PRP">PRP</option>
                <option value="IPL">IPL</option>
                <option value="BOTOX">BOTOX</option>
                <option value="FILLER">FILLER</option>
                <option value="THREAD LIFT">THREAD LIFT</option>
                <option value="LASER">LASER</option>
                <option value="CRYOLIPO">CRYOLIPO</option>
                <option value="PROIONIC">PROIONIC</option>
                <option value="DFT">DFT</option>
                <option value="ADVANCE FACIAL">ADVANCE FACIAL</option>
                <option value="RF">RF</option>
                <option value="EIT">EIT</option>
                <option value="O2 FACE">O2 FACE</option>
                <option value="HYDRO FACE">HYDRO FACE</option>
                <option value="PDT">PDT</option>
              </select>
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-infolain" role="tabpanel" aria-labelledby="btn-tab-infolain">
          <div class="row mx-0">
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="tidakdihitungjumlahpasien">
                <label class="form-check-label text-sm" for="tidakdihitungjumlahpasien" role="button">Tidak Dihitung Jumlah Pasien</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="komisimarketing">
                <label class="form-check-label text-sm" for="komisimarketing" role="button">Komisi Marketing</label>
              </div>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="komisipaket">
                <label class="form-check-label text-sm" for="komisipaket" role="button">Komisi Paket</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="bisasharing">
                <label class="form-check-label text-sm" for="bisasharing" role="button">Bisa Sharing</label>
              </div>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="cetak">
                <label class="form-check-label text-sm" for="cetak" role="button">Cetak</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="komisidokter">
                <label class="form-check-label text-sm" for="komisidokter" role="button">Komisi Dokter</label>
              </div>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="promo">
                <label class="form-check-label text-sm" for="promo" role="button">Promo</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="hargablank">
                <label class="form-check-label text-sm" for="hargablank" role="button">Harga Blank</label>
              </div>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="paketsemuacabang">
                <label class="form-check-label text-sm" for="paketsemuacabang" role="button">Paket Semua Cabang</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="pasienbarusaja">
                <label class="form-check-label text-sm" for="pasienbarusaja" role="button">Pasien Baru Saja</label>
              </div>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="tindakanproduk">
                <label class="form-check-label text-sm" for="tindakanproduk" role="button">Tindakan Produk</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="bhp">
                <label class="form-check-label text-sm" for="bhp" role="button">BHP</label>
              </div>
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="tidaktampildimedlib">
                <label class="form-check-label text-sm" for="tidaktampildimedlib" role="button">Tidak Tampil Di Medlib</label>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-check mt-2">
                <input type="checkbox" class="form-check-input" id="resep">
                <label class="form-check-label text-sm" for="resep" role="button">Resep</label>
              </div>
            </div>
          </div>
          <div class="row mt-3 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Persentase Komisi</label>
            <div class="col-sm-4">
              <input id="persentasekomisi" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Nilai Komisi</label>
            <div class="col-sm-4">
              <input id="nilaikomisi" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Nilai Komisi Per Qty</label>
            <div class="col-sm-4">
              <input id="nilaikomisiperqty" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Minimal Qty</label>
            <div class="col-sm-4">
              <input id="minimalqty" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Berat</label>
            <div class="col-sm-4">
              <input id="berat" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-po" role="tabpanel" aria-labelledby="btn-tab-po">
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Harga PO</label>
            <div class="col-sm-4">
              <input id="hargapo" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Qty Satuan PO</label>
            <div class="col-sm-4">
              <input id="qtypo" type="text" class="form-control form-control-sm qty" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Kemasan</label>
            <div class="col-sm-4">
              <input type="search" class="form-control form-control-sm" id="kemasan" name="kemasan" autocomplete="off" data-trigger="manual" data-placement="auto">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Coding</label>
            <div class="col-sm-4">
              <input type="search" class="form-control form-control-sm" id="coding" name="coding" autocomplete="off" data-trigger="manual" data-placement="auto">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Nama PO</label>
            <div class="col-sm-4">
              <input type="search" class="form-control form-control-sm" id="namapo" name="namapo" autocomplete="off" data-trigger="manual" data-placement="auto">
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-daps" role="tabpanel" aria-labelledby="btn-tab-daps">
          <div class="row mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Fee Dokter</label>
            <div class="col-sm-4">
              <input id="dapsfeedokter" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Fee Perawat</label>
            <div class="col-sm-4">
              <input id="dapsfeeperawat" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Alkes</label>
            <div class="col-sm-4">
              <input id="dapsalkes" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Equipment</label>
            <div class="col-sm-4">
              <input id="dapsequipment" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Facility</label>
            <div class="col-sm-4">
              <input id="dapsfacility" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Jasa Klinik</label>
            <div class="col-sm-4">
              <input id="dapsjasaklinik" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div class="row mt-0 mx-0">
            <label class="col-sm-2 col-form-label text-sm font-weight-normal">Sales Commission</label>
            <div class="col-sm-4">
              <input id="dapssalescomm" type="text" class="form-control form-control-sm numeric" value="0">
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
        <div class="tab-pane fade text-sm" id="tab-cabang" role="tabpanel" aria-labelledby="btn-tab-cabang">
          <div class="row mx-0 px-0">
            <div class="table-responsive bg-light" tabindex="-1" style="outline:none;border:1px solid #dee2e6;height:calc(100vh - 500px);overflow: auto;">
                <table id="tcabang" class="table table-hover table-sm table-transaksi w-100">
                  <thead class="bg-primary" style="position: sticky; top:0px;z-index:999;">
                    <tr>
                      <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 10px"></th>
                      <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 150px">Kode</th>
                      <th class="text-sm text-label text-left border-0 font-weight-normal" style="width: 220px">Nama Gudang</th>
                      <th class="text-sm text-label text-center border-0 font-weight-normal" style="width: 70px">Pilih</th>
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  </tfoot>
                </table>
            </div>
          </div>
          <div style='clear:both'></div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
<div class="modal-footer">
  <div class="form-group">
      <div class="col-sm-offset-3">
          <a class="text-sm mx-4" data-dismiss="modal" aria-hidden="true" data-toggle='modal' href="#">Batal</a>
          <button type="button" id="submit" name="submit" class="btn btn-primary btn-sm">Simpan</button>
      </div>
  </div>
</div>
</form>
<script src="<? echo app_url('assets/dist/js/modul/master/form-item-pos.js'); ?>"></script>
