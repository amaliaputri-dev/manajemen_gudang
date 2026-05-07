<h3 class="text-white">Data Kurir</h3>

<table class="table table-bordered bg-white">
<tr>
    <th>Nama Kurir</th>
    <th>No HP</th>
</tr>

<?php foreach($kurir as $k){ ?>
<tr>
    <td><?php echo $k->nama_kurir; ?></td>
    <td><?php echo $k->no_hp; ?></td>
</tr>
<?php } ?>

</table>

<a href="<?php echo base_url('index.php/dashboard'); ?>" class="btn btn-light">Kembali</a>