<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracer Alumni</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <style>
        body {
            margin: 0;
        }

        .jumbotron-bg {
            background-image: url('Header-Home.jpg');  
            background-size: cover;
            background-position: center;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Bagian Atas: Jumbotron dengan Latar Belakang Gambar -->
    <header class="jumbotron-bg text-white text-center py-5">
        <div class="container">
            <h1 class="display-4">Selamat Datang di Website Tracer Alumni</h1>
            <p class="lead">Mengelola data alumni dengan mudah dan cepat.</p>
        </div>
    </header>

    <div class="container-fluid my-4">
        <div class="row">
            <!-- Bagian Kiri: Menu -->
            <aside class="col-md-2 p-0">
                <nav class="nav flex-column bg-light p-3 m-0">
                    <a class="nav-link active" href="?menu=d">Tracer Alumni</a> 
                </nav>
            </aside>

            <!-- Bagian Tengah: Artikel -->
            <main class="col-md-10">
                <article>
                    <?php
                        session_start();
                        $file = 'alumni.csv';

                        // Fungsi untuk membaca data dari file CSV 
                        function readAlumniData($file) {
                            $data = [];
                            if (file_exists($file)) {
                                $handle = fopen($file, 'r');
                                while (($row = fgetcsv($handle)) !== false) {
                                    $data[] = $row;
                                }
                                fclose($handle);
                            }
                            return $data;
                        }

                        // Fungsi untuk menulis data ke file CSV 
                        function writeAlumniData($file, $data) {
                            $handle = fopen($file, 'w');
                            foreach ($data as $row) {
                                fputcsv($handle, $row);
                            }
                            fclose($handle);
                        }

                        // Fungsi untuk mencari data alumni
                        function searchAlumni($data, $searchTerm, $searchField) {
                            $results = [];
                            foreach ($data as $alumnus) {
                                switch($searchField) {
                                    case 'nim':
                                        if (stripos($alumnus[0], $searchTerm) !== false) $results[] = $alumnus;
                                        break;
                                    case 'name':
                                        if (stripos($alumnus[1], $searchTerm) !== false) $results[] = $alumnus;
                                        break;
                                    case 'major':
                                        if (stripos($alumnus[2], $searchTerm) !== false) $results[] = $alumnus;
                                        break;
                                    case 'year':
                                        if (stripos($alumnus[3], $searchTerm) !== false) $results[] = $alumnus;
                                        break;
                                    case 'all':
                                        if (stripos(implode(' ', $alumnus), $searchTerm) !== false) $results[] = $alumnus;
                                        break;
                                }
                            }
                            return $results;
                        }

                        // Mengelola Create (Tambah Data Alumni)
                        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add'])) {
                            $nim = $_POST['nim'];
                            $name = $_POST['name'];
                            $major = $_POST['major'];
                            $year = $_POST['year'];

                            $data = readAlumniData($file);
                            $data[] = [$nim, $name, $major, $year];
                            writeAlumniData($file, $data);
                            echo "<div class='alert alert-success' role='alert'>Data alumni berhasil ditambahkan!</div>";
                        }

                        // Mengelola Delete (Hapus Data Alumni)
                        if (isset($_POST['delete'])) {
                            $index = $_POST['index'];
                            $data = readAlumniData($file);
                            unset($data[$index]);
                            $data = array_values($data);
                            writeAlumniData($file, $data);
                            echo "<div class='alert alert-success' role='alert'>Data alumni berhasil dihapus!</div>";
                        }

                        // Inisialisasi variabel pencarian
                        $searchResults = null;
                        if (isset($_GET['search']) && isset($_GET['searchField'])) {
                            $searchTerm = $_GET['search'];
                            $searchField = $_GET['searchField'];
                            $data = readAlumniData($file);
                            $searchResults = searchAlumni($data, $searchTerm, $searchField);
                        }
                    ?>

                    <div class="container">
                        <h1 class="text-center">Manajemen Data Alumni</h1>

                        <!-- Form Pencarian -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4>Cari Alumni</h4>
                                <form method="get" action="" class="row g-3">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" name="search" placeholder="Masukkan kata kunci..." required>
                                    </div>
                                    <div class="col-md-4">
                                        <select class="form-select" name="searchField">
                                            <option value="all">Semua Field</option>
                                            <option value="nim">NIM</option>
                                            <option value="name">Nama</option>
                                            <option value="major">Jurusan</option>
                                            <option value="year">Angkatan</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Formulir untuk Menambah Data Alumni -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4>Tambah Data Alumni</h4>
                                <form method="post" action="">
                                    <div class="form-group mb-2">
                                        <label for="nim">NIM:</label>
                                        <input type="text" class="form-control" id="nim" name="nim" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="name">Nama:</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="major">Jurusan:</label>
                                        <input type="text" class="form-control" id="major" name="major" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="year">Angkatan</label>
                                        <input type="number" class="form-control" id="year" name="year" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="add">Tambah Data</button>
                                </form>
                            </div>
                        </div>

                        <!-- Menampilkan Data Alumni -->
                        <div class="card">
                            <div class="card-body">
                                <h4><?php echo isset($searchResults) ? 'Hasil Pencarian' : 'Daftar Alumni'; ?></h4>
                                <?php
                                    $displayData = isset($searchResults) ? $searchResults : readAlumniData($file);
                                    if (!empty($displayData)):
                                ?>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>NIM</th>
                                                <th>Nama</th>
                                                <th>Jurusan</th>
                                                <th>Angkatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($displayData as $index => $alumnus): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($alumnus[0]); ?></td>
                                                    <td><?php echo htmlspecialchars($alumnus[1]); ?></td>
                                                    <td><?php echo htmlspecialchars($alumnus[2]); ?></td>
                                                    <td><?php echo htmlspecialchars($alumnus[3]); ?></td>
                                                    <td>
                                                        <form method="post" action="" style="display:inline;">
                                                            <input type="hidden" name="index" value="<?php echo $index; ?>">
                                                            <button type="submit" class="btn btn-danger btn-sm" name="delete">Hapus</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                <?php else: ?>
                                    <p>Tidak ada data untuk ditampilkan.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </article>
            </main>
        </div>
    </div>

    <!-- Bagian Bawah: Footer -->
    <footer class="bg-dark text-white text-center py-4">
        <p>&copy; 2024 Tracer Alumni. All rights reserved.</p>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>