<h3 class="text-center">BURSA KERJA</h3>
<hr>
<?php
$jsonFile = 'bursa_kerja.json';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_perusahaan = htmlspecialchars($_POST['nama_perusahaan']);
    $posisi = htmlspecialchars($_POST['posisi']);
    $kualifikasi = htmlspecialchars($_POST['kualifikasi']);
    $kontak = htmlspecialchars($_POST['kontak']);
    $batas_waktu = htmlspecialchars($_POST['batas_waktu']);
    $waktu_posting = date('Y-m-d H:i:s');

    $bursaKerja = readData($jsonFile);
    $bursaKerja[] = [
        'nama_perusahaan' => $nama_perusahaan,
        'posisi' => $posisi,
        'kualifikasi' => $kualifikasi,
        'kontak' => $kontak,
        'batas_waktu' => $batas_waktu,
        'waktu_posting' => $waktu_posting
    ];
    
    saveData($jsonFile, $bursaKerja);
    echo "<div class='alert alert-success'>Lowongan kerja berhasil ditambahkan!</div>";
}

$bursaKerja = readData($jsonFile);
?>

<!-- Form Input Lowongan -->
<div class="container mt-4">
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
            <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required>
        </div>
        <div class="mb-3">
            <label for="posisi" class="form-label">Posisi yang Dibutuhkan</label>
            <input type="text" class="form-control" id="posisi" name="posisi" required>
        </div>
        <div class="mb-3">
            <label for="kualifikasi" class="form-label">Kualifikasi</label>
            <textarea class="form-control" id="kualifikasi" name="kualifikasi" rows="4" required></textarea>
        </div>
        <div class="mb-3">
            <label for="kontak" class="form-label">Kontak</label>
            <input type="text" class="form-control" id="kontak" name="kontak" required>
        </div>
        <div class="mb-3">
            <label for="batas_waktu" class="form-label">Batas Waktu Lamaran</label>
            <input type="date" class="form-control" id="batas_waktu" name="batas_waktu" required>
        </div>
        <button type="submit" class="btn btn-primary">Posting Lowongan</button>
    </form>
</div>

<!-- Daftar Lowongan -->
<h4 class="mt-5">Daftar Lowongan Kerja</h4>
<?php if (!empty($bursaKerja)) { ?>
    <div class="row mt-3">
        <?php foreach ($bursaKerja as $lowongan) { ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><?php echo $lowongan['posisi']; ?></h5>
                    </div>
                    <div class="card-body">
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo $lowongan['nama_perusahaan']; ?></h6>
                        <p class="card-text">
                            <strong>Kualifikasi:</strong><br>
                            <?php echo nl2br($lowongan['kualifikasi']); ?>
                        </p>
                        <p class="card-text"><strong>Kontak:</strong> <?php echo $lowongan['kontak']; ?></p>
                        <p class="card-text"><strong>Batas Waktu:</strong> <?php echo date('d F Y', strtotime($lowongan['batas_waktu'])); ?></p>
                        <p class="card-text"><small class="text-muted">Diposting: <?php echo $lowongan['waktu_posting']; ?></small></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="alert alert-info">Belum ada lowongan kerja yang tersedia.</div>
<?php } ?>