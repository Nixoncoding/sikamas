<?php
$riwayatKas = $conn->query("SELECT kas.*, kegiatan.nama AS nama_kegiatan 
                            FROM kas 
                            LEFT JOIN kegiatan ON kas.id_kegiatan = kegiatan.id_kegiatan 
                            ORDER BY kas.tgl DESC");
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Pembayaran d🧾</h1>
</div>

<table class="table table-hover table-striped dtableExportResponsive">
    <thead>
        <tr class="text-center">
            <th>#</th>
            <th>Tanggal</th>
            <th>Jenis</th>
            <th>Kegiatan</th>
            <th>Masuk</th>
            <th>Keluar</th>
            <th>PJ</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1; foreach ($riwayatKas as $r) : ?>
            <tr class="<?= ($r['masuk'] > 0) ? 'table-success' : 'table-danger'; ?>">
                <td class="text-center"><?= $no++; ?></td>
                <td><?= date("d-m-Y", strtotime($r['tgl'])); ?></td>
                <td class="text-capitalize"><?= $r['jenis']; ?></td>
                <td><?= $r['nama_kegiatan'] ?: '-'; ?></td>
                <td align="right"><?= number_format($r['masuk']) . ",-"; ?></td>
                <td align="right"><?= number_format($r['keluar']) . ",-"; ?></td>
                <td><?= $r['pj']; ?></td>
                <td><?= $r['ket']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
