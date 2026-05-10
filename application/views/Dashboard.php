<div class="row g-4">

    <!-- GUDANG -->
    <div class="col-12 col-md-6">
        <div class="card card-custom bg-primary text-white p-4 text-center h-100">
            <h4>Gudang</h4>
            <h2><?php echo $jumlah_barang; ?></h2>
            <p>Total Barang</p>
            <a href="<?php echo base_url('index.php/gudang'); ?>" class="btn btn-light">Lihat Data</a>
        </div>
    </div>

    <!-- KURIR -->
    <div class="col-12 col-md-6">
        <div class="card card-custom bg-success text-white p-4 text-center h-100">
            <h4>Kurir</h4>
            <h2><?php echo $jumlah_kurir; ?></h2>
            <p>Total Kurir</p>
            <a href="<?php echo base_url('index.php/kurir'); ?>" class="btn btn-light">Lihat Data</a>
        </div>
    </div>

</div>