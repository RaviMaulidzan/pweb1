<h3 class="text-center">PENCARIAN ALUMNI</h3>
<hr>
<?php
$jsonFile = 'data_alumni.json';

function readData($file) {
    if (file_exists($file)) {
        $data = file_get_contents($file);
        return json_decode($data, true);
    }
    return [];
}

function saveData($file, $data) {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file, $jsonData);
}

// Handle form submission untuk pendaftaran alumni baru
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'daftar') {
    $nama = htmlspecialchars($_POST['nama']);
    $tahun_lulus = htmlspecialchars($_POST['tahun_lulus']);
    $jurusan = htmlspecialchars($_POST['jurusan']);
    $pekerjaan = htmlspecialchars($_POST['pekerjaan']);
    $email = htmlspecialchars($_POST['email']);
    $telepon = htmlspecialchars($_POST['telepon']);

    $dataAlumni = readData($jsonFile);
    $dataAlumni[] = [
        'nama' => $nama,
        'tahun_lulus' => $tahun_lulus,
        'jurusan' => $jurusan,
        'pekerjaan' => $pekerjaan,
        'email' => $email,
        'telepon' => $telepon
    ];
    
    saveData($jsonFile, $dataAlumni);
    echo "<div class='alert alert-success'>Data alumni berhasil ditambahkan!</div>";
}

$dataAlumni = readData($jsonFile);

// Handle pencarian
$hasil_pencarian = [];
if (isset($_GET['keyword'])) {
    $keyword = strtolower($_GET['keyword']);
    foreach ($dataAlumni as $alumni) {
        if (strpos(strtolower($alumni['nama']), $keyword) !== false ||
            strpos(strtolower($alumni['jurusan']), $keyword) !== false ||
            strpos($alumni['tahun_lulus'], $keyword) !== false) {
            $hasil_pencarian[] = $alumni;
        }
    }
}
?>

<!-- Form Pencarian -->
<div class="container mt-4">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <form method="GET" action="" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="keyword" placeholder="Cari berdasarkan nama, jurusan, atau tahun lulus..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                    <button class="btn btn-primary" type="submit">Cari</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form Pendaftaran Alumni -->
<div class="container mt-4">
    <h4>Pendaftaran Alumni Baru</h4>
    <form method="POST" action="">
        <input type="hidden" name="action" value="daftar">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="nama" name="nama" required>
                </div>
                <div class="mb-3">
                    <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                    <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" required>
                </div>
                <div class="mb-3">
                    <label for="jurusan" class="form-label">Jurusan</label>
                    <input type="text" class="form-control" id="jurusan" name="jurusan" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="pekerjaan" class="form-label">Pekerjaan Saat Ini</label>
                    <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="telepon" class="form-label">No. Telepon</label>
                    <input type="tel" class="form-control" id="telepon" name="telepon" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Daftar</button>
    </form>
</div>

<!-- Hasil Pencarian -->
<?php if (isset($_GET['keyword'])) { ?>
    <div class="container mt-5">
        <h4>Hasil Pencarian</h4>
        <?php if (!empty($hasil_pencarian)) { ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Tahun Lulus</th>
                            <th>Jurusan</th>
                            <th>Pekerjaan</th>
                            <th>Kontak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasil_pencarian as $alumni) { ?>
                            <tr>
                                <td><?php echo $alumni['nama']; ?></td>
                                <td><?php echo $alumni['tahun_lulus']; ?></td>
                                <td><?php echo $alumni['jurusan']; ?></td>
                                <td><?php echo $alumni['pekerjaan']; ?></td>
                                <td>
                                    Email: <?php echo $alumni['email']; ?><br>
                                    Telp: <?php echo $alumni['telepon']; ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="alert alert-info">Tidak ada hasil yang ditemukan.</div>
        <?php } ?>
    </div>
<?php } ?>