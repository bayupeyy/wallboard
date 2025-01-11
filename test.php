<?php
$url = "http://10.60.175.132/ideas_new_pds_ok_dev/wall_agent/walls.php";

// Ambil data JSON
$json_data = @file_get_contents($url);

if ($json_data === FALSE) {
    echo "Gagal mengakses API. Periksa koneksi atau URL.";
} else {
    echo "<pre>";
    print_r(json_decode($json_data, true));
    echo "</pre>";
}
?>
