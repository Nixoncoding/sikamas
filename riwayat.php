<?php
if (isset($_POST['submitRiwayat'])) {
    $nama = $_POST['nama_warga'];
    $bulan = $_POST['bulan'];
    $nominal = $_POST['nominal'];
    $keterangan = $_POST['keterangan'];

    // Inisialisasi semua bulan ke 0
    $data_bulan = [
        'januari' => 0, 'februari' => 0, 'maret' => 0, 'april' => 0,
        'mei' => 0, 'juni' => 0, 'juli' => 0, 'agustus' => 0,
        'september' => 0, 'oktober' => 0, 'november' => 0, 'desember' => 0,
    ];
    $data_bulan[$bulan] = $nominal; // isi sesuai bulan yang dipilih

    // Cek apakah data sudah ada (berdasarkan nama_warga)
    $cek = $conn->query("SELECT * FROM riwayat_pembayaran WHERE nama_warga = '$nama'");
    if ($cek->num_rows > 0) {
        // Update data bulan yang dipilih
        $conn->query("UPDATE riwayat_pembayaran SET $bulan = $nominal WHERE nama_warga = '$nama'");
        $_SESSION['pesan'] = 'Data berhasil diperbarui!';
    } else {
        // Tambah data baru
        $sql = "INSERT INTO riwayat_pembayaran (nama_warga, januari, februari, maret, april, mei, juni, juli, agustus, september, oktober, november, desember, keterangan)
                VALUES ('$nama', 
                        {$data_bulan['januari']}, {$data_bulan['februari']}, {$data_bulan['maret']}, {$data_bulan['april']},
                        {$data_bulan['mei']}, {$data_bulan['juni']}, {$data_bulan['juli']}, {$data_bulan['agustus']},
                        {$data_bulan['september']}, {$data_bulan['oktober']}, {$data_bulan['november']}, {$data_bulan['desember']},
                        '$keterangan')";
        $conn->query($sql);
        $_SESSION['pesan'] = 'Data berhasil ditambahkan!';
    }

    header("Location: riwayat.php"); // balik ke halaman riwayat
    exit;
}

$riwayatKas = $conn->query("SELECT * FROM riwayat_pembayaran ORDER BY id DESC");

?>

<!-- HEADING PAGE -->
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Pembayaran </h1>
    <span class="d-inline"><span data-feather="bar-chart"></span>&nbsp;<span class="font-weight-bold"><?= 'Saldo : Rp ' . number_format($duitKas['saldo']) . ',-'; ?></span></span>
</div>

<?php
if (isset($_SESSION['pesan']) && $_SESSION['pesan'] <> '') {
    echo '<div id="pesan" class="alert alert-warning" style="display:none;">' . $_SESSION['pesan'] . '</div>';
}
$_SESSION['pesan'] = '';
?>

<!-- Tombol untuk Menambah Riwayat Pembayaran -->
<div class="d-block d-md-flex justify-content-between mb-3">
    <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#exampleModal">
        <span data-feather="plus-circle"></span>
        Tambah Riwayat Pembayaran
    </button>
</div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Riwayat Pembayaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                </div>
                <form action="index.php" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama Warga / Alamat Rumah</label>
                            <input type="text" class="form-control" name="nama_warga" required>
                        </div>
                        <div class="form-group">
                            <label for="bulan">Bulan</label>
                            <select class="form-control" name="bulan" required>
                                <option value="" disabled selected>Pilih Bulan</option>
                                <option value="januari">Januari</option>
                                <option value="februari">Februari</option>
                                <option value="maret">Maret</option>
                                <option value="april">April</option>
                                <option value="mei">Mei</option>
                                <option value="juni">Juni</option>
                                <option value="juli">Juli</option>
                                <option value="agustus">Agustus</option>
                                <option value="september">September</option>
                                <option value="oktober">Oktober</option>
                                <option value="november">November</option>
                                <option value="desember">Desember</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nominal">Nominal Pembayaran</label>
                            <input type="number" class="form-control" name="nominal" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea class="form-control" rows="3" name="keterangan"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="submitRiwayat">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- TABEL RIWAYAT PEMBAYARAN -->
<div class="table-responsive" style="overflow-x: auto;">
    <table id="tabelRiwayat" class="table table-hover table-striped table-bordered" style="white-space: nowrap;">
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Nama Warga/Alamat Rumah</th>
                <th>Januari</th>
                <th>Februari</th>
                <th>Maret</th>
                <th>April</th>
                <th>Mei</th>
                <th>Juni</th>
                <th>Juli</th>
                <th>Agustus</th>
                <th>September</th>
                <th>Oktober</th>
                <th>November</th>
                <th>Desember</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $totalJanuari = $totalFebruari = $totalMaret = $totalApril = $totalMei = $totalJuni = $totalJuli = $totalAgustus = $totalSeptember = $totalOktober = $totalNovember = $totalDesember = 0;

            foreach ($riwayatKas as $r) :
                $totalJanuari += $r['januari'];
                $totalFebruari += $r['februari'];
                $totalMaret += $r['maret'];
                $totalApril += $r['april'];
                $totalMei += $r['mei'];
                $totalJuni += $r['juni'];
                $totalJuli += $r['juli'];
                $totalAgustus += $r['agustus'];
                $totalSeptember += $r['september'];
                $totalOktober += $r['oktober'];
                $totalNovember += $r['november'];
                $totalDesember += $r['desember'];
            ?>
               <tr class="<?= ($r['masuk'] > 0) ? 'table-success' : 'table-danger'; ?>">
                    <td class="text-center"><?= $no++; ?></td>
                    <td><?= $r['nama_warga'] . ' ' . $r['alamat_rumah']; ?></td>
                    <td><?= $r['januari'] ? number_format($r['januari']) : ''; ?></td>
<td><?= $r['februari'] ? number_format($r['februari']) : ''; ?></td>
<td><?= $r['maret'] ? number_format($r['maret']) : ''; ?></td>
<td><?= $r['april'] ? number_format($r['april']) : ''; ?></td>
<td><?= $r['mei'] ? number_format($r['mei']) : ''; ?></td>
<td><?= $r['juni'] ? number_format($r['juni']) : ''; ?></td>
<td><?= $r['juli'] ? number_format($r['juli']) : ''; ?></td>
<td><?= $r['agustus'] ? number_format($r['agustus']) : ''; ?></td>
<td><?= $r['september'] ? number_format($r['september']) : ''; ?></td>
<td><?= $r['oktober'] ? number_format($r['oktober']) : ''; ?></td>
<td><?= $r['november'] ? number_format($r['november']) : ''; ?></td>
<td><?= $r['desember'] ? number_format($r['desember']) : ''; ?></td>

                    <td>
                        <a href="edit_pembayaran.php?id=<?= $r['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="hapus_pembayaran.php?id=<?= $r['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin mau menghapus?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="table-primary font-weight-bold text-center">
                <td colspan="2">Total Saldo</td>
                <td><?= number_format($totalJanuari); ?></td>
                <td><?= number_format($totalFebruari); ?></td>
                <td><?= number_format($totalMaret); ?></td>
                <td><?= number_format($totalApril); ?></td>
                <td><?= number_format($totalMei); ?></td>
                <td><?= number_format($totalJuni); ?></td>
                <td><?= number_format($totalJuli); ?></td>
                <td><?= number_format($totalAgustus); ?></td>
                <td><?= number_format($totalSeptember); ?></td>
                <td><?= number_format($totalOktober); ?></td>
                <td><?= number_format($totalNovember); ?></td>
                <td><?= number_format($totalDesember); ?></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<!-- DataTables Export Scripts -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabelRiwayat').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Riwayat Pembayaran'
            },
              {
                extend: 'pdfHtml5',
                title: 'Riwayat Pembayaran',
                orientation: 'landscape',
                pageSize: 'A4'
            },
            {
                extend: 'print',
                title: 'Riwayat Pembayaran'
            }
        ],
        scrollX: true,
        paging: false
    });
});
</script>
