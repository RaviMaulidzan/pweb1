<h3 class="text-center">BUKU TAMU</h3>
<hr>
<?php
// Nama file JSON untuk menyimpan data buku tamu
$jsonFile = 'buku_tamu.json';

// Fungsi untuk membaca data dari file JSON
function readData($file) {
    if (file_exists($file)) {
        $data = file_get_contents($file);
        return json_decode($data, true);
    }
    return [];
}

// Fungsi untuk menyimpan data ke file JSON
function saveData($file, $data) {
    $jsonData = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents($file, $jsonData);
}

// Menangani pengiriman form buku tamu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $pesan = htmlspecialchars($_POST['pesan']);
    $waktu = date('Y-m-d H:i:s');

    // Membaca data yang sudah ada
    $bukuTamu = readData($jsonFile);

    // Menambahkan entri baru
    $bukuTamu[] = [
        'nama' => $nama,
        'email' => $email,
        'pesan' => $pesan,
        'waktu' => $waktu
    ];

    // Menyimpan data kembali ke file JSON
    saveData($jsonFile, $bukuTamu);
    echo "<div class='alert alert-success'>Pesan berhasil disimpan!</div>";
}

// Membaca data untuk ditampilkan
$bukuTamu = readData($jsonFile);
?>

<!-- Form Buku Tamu -->
<div class="container mt-4">
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="pesan" class="form-label">Pesan</label>
            <textarea class="form-control" id="pesan" name="pesan" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Kirim</button>
    </form>
</div>

<!-- Daftar Buku Tamu -->
<h4 class="mt-5">Daftar Buku Tamu</h4>
<?php if (!empty($bukuTamu)) { ?>
    <table class="table table-striped table-bordered mt-3">
        <thead class="table-dark">
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Pesan</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bukuTamu as $tamu) { ?>
                <tr>
                    <td><?php echo $tamu['nama']; ?></td>
                    <td><?php echo $tamu['email']; ?></td>
                    <td><?php echo $tamu['pesan']; ?></td>
                    <td><?php echo $tamu['waktu']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <div class="alert alert-info">Belum ada pesan di buku tamu.</div>
<?php } ?>