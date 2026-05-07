<h3 class="text-white">Data Gudang</h3>

<table class="table table-bordered bg-white">
<tr>
    <th>Nama Barang</th>
    <th>Stok</th>
</tr>

<?php foreach($barang as $b){ ?>
<tr>
    <td><?php echo $b->nama_barang; ?></td>
    <td><?php echo $b->stok; ?></td>
</tr>
<?php } ?>

</table>

<a href="<?php echo base_url('index.php/dashboard'); ?>" class="btn btn-light">Kembali</a>