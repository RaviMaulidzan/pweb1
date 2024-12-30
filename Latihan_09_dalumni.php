<?php
include 'Latihan_09_config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data
    $sql = "DELETE FROM alumni WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil dihapus";
        header("Location: Latihan_09_index.php?menu=alumni");
        exit; // Menambahkan exit agar header tidak dieksekusi bersama echo
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
}
?>