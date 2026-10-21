<?php
$conn = mysqli_connect('127.0.0.1', 'root', 'Ryan@071101', 'paw_jurnal2_lms');
mysqli_set_charset($conn, 'utf8mb4');
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>