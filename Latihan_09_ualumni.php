<?php
include "Latihan_09_config.php"; // Memasukkan file konfigurasi untuk koneksi database

// Memeriksa apakah parameter ID ada dalam URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Mengonversi ID menjadi integer untuk keamanan

    // Mengambil data dari database
    $sql = "SELECT * FROM alumni WHERE id=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
        exit;
    }
}

// Menangani pembaruan data ketika form di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nama = $_POST['nama'];
    $tahun_lulus = $_POST['tahun_lulus'];
    $jurusan = $_POST['jurusan'];
    $uploadOk = 1;
    $target_file = "";

    // Mengelola pembaruan foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Memeriksa apakah file benar-benar gambar
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if ($check === false) {
            echo "<div class='alert alert-danger'>File bukan gambar.</div>";
            $uploadOk = 0;
        }

        // Memeriksa ukuran file (5MB maks)
        if ($_FILES["foto"]["size"] > 5000000) {
            echo "<div class='alert alert-danger'>Ukuran file terlalu besar.</div>";
            $uploadOk = 0;
        }

        // Mengizinkan format file tertentu
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            echo "<div class='alert alert-danger'>Hanya file JPG, JPEG, PNG & GIF yang diizinkan.</div>";
            $uploadOk = 0;
        }

        // Cek apakah $uploadOk sudah di-set menjadi 0
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                $sql = "UPDATE alumni SET 
                        nama='$nama', 
                        tahun_lulus='$tahun_lulus', 
                        jurusan='$jurusan', 
                        foto='$target_file' 
                        WHERE id=$id";
            } else {
                echo "<div class='alert alert-danger'>Terjadi kesalahan saat mengunggah file.</div>";
                $uploadOk = 0;
            }
        }
    }

    // Jika tidak ada foto yang diunggah, hanya memperbarui data tanpa foto
    if ($uploadOk == 1 && empty($target_file)) {
        $sql = "UPDATE alumni SET 
                nama='$nama', 
                tahun_lulus='$tahun_lulus', 
                jurusan='$jurusan' 
                WHERE id=$id";
    }

    if ($uploadOk == 1 && $conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Data berhasil diperbarui.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }

    $conn->close();
}
?>

<div class="container mt-5">
    <h2 class="mb-4">Update Data Alumni</h2>
    <!-- Form update data -->
    <?php if (isset($row)) { ?>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" class="form-control" id="nama" name="nama" 
                       value="<?php echo $row['nama']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="tahun_lulus" class="form-label">Tahun Lulus</label>
                <input type="number" class="form-control" id="tahun_lulus" name="tahun_lulus" 
                       value="<?php echo $row['tahun_lulus']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="jurusan" class="form-label">Jurusan</label>
                <input type="text" class="form-control" id="jurusan" name="jurusan" 
                       value="<?php echo $row['jurusan']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto (biarkan kosong jika tidak diubah)</label>
                <input type="file" class="form-control" id="foto" name="foto">
            </div>

            <button type="submit" class="btn btn-primary">Perbarui Data</button>
        </form>
    <?php } ?>
</div>