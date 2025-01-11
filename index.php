<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Agen</title>
    <!-- Link Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #eef2f7;
        }

        .table-container {
            margin-top: 30px;
            border-radius: 10px;
            overflow: hidden;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 20px;
        }

        .table th {
            background-color: #007bff;
            color: white;
            text-transform: uppercase;
        }

        h1 {
            margin-top: 20px;
            text-align: center;
            color: #007bff;
            font-weight: bold;
        }

        .on-call {
            background-color: #A8CD89 !important; /* Hijau muda */
        }

        .ready {
            background-color: #B1F0F7 !important; /* Biru muda */
        }

        .idle {
            background-color: #FFEB00 !important; /* Kuning */
        }

        .status-filter {
            margin: 20px 0;
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .status-filter button {
            text-transform: capitalize;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .status-filter button:hover {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Wallboard Agent</h1>

        <div class="status-filter">
            <button data-filter="all">Tampilkan Semua</button>
            <button data-filter="On Call">On Call</button>
            <button data-filter="Ready">Ready</button>
        </div>

        <div class="table-container">
            <table class="table table-striped table-hover" id="agentTable">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Login User</th>
                        <th>Dial Mode</th>
                        <th>Status Agen</th>
                        <th>Ekstensi</th>
                        <th>Total Panggilan</th>
                        <th>Eksekusi Terakhir</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    // URL API
    $url = "http://10.60.175.132/ideas_new_pds_ok_dev/wall_agent/walls.php";

    // Mengambil data JSON dari API
    $data = @file_get_contents($url);

    if ($data === false) {
        echo "<tr><td colspan='7' class='text-center text-danger'>Gagal Mengakses Data. Periksa API atau Koneksi Anda.</td></tr>";
    } else {
        // Mengurai data JSON menjadi array PHP
        $data = json_decode($data, true);

        // Memeriksa apakah data valid
        if ($data === null || !is_array($data)) {
            echo "<tr><td colspan='7' class='text-center text-danger'>Data JSON Tidak Valid.</td></tr>";
        } else {
            // Filter data untuk menyembunyikan "infobandung32 - 11001" dan "infobandung56 - 11002"
            $filteredData = array_filter($data, function ($agent) {
                $excludedUsers = ['infobandung32 - 11001',
                                'infobandung02 - 11079',
                                'infobandung04 - 11009',
                                'infobandung05 - 11008',
                                'infobandung07 - 11033',
                                'infobandung08 - 11010',
                                'infobandung09 - 11094',
                                'infobandung11 - 11023',
                                'infobandung12 - 11089',
                                'infobandung13 - 11061',
                                'infobandung14 - 11013',
                                'infobandung16 - 11095',
                                'infobandung17 - 11073',
                                'infobandung19 - 11017',
                                'infobandung38 - 11070',
                                'infobandung56 - 11002',
                                'infobandung67 - 11068',
                                'infobandung33 - 11005',
                                'infobandung73 - 11004',
                                'infobandung77 - 11007',
                                'infobandung69 - 11011',
                                'infobandung72 - 11014',
                                'infobandung35 - 11015',
                                'infobandung65 - 11016',
                                'infobandung25 - 11003',
                                'infobandung25 - 11003',
                                'infobandung25 - 11003',
                                'infobandung25 - 11003',
                                'infobandung25 - 11003',
                                'infobandung25 - 11003',
                                'infobandung25 - 11003',];
                return isset($agent['login_user']) && !in_array($agent['login_user'], $excludedUsers);
            });

            // Periksa jika data hasil filter kosong
            if (empty($filteredData)) {
                echo "<tr><td colspan='7' class='text-center text-warning'>Tidak ada data untuk ditampilkan.</td></tr>";
            } else {
                // Menampilkan data hasil filter dalam bentuk tabel
                foreach ($filteredData as $agent) {
                    echo "<tr class='agent-row' data-status-agent='" . htmlspecialchars($agent['agent_status']) . "'>";
                    echo "<td>" . htmlspecialchars($agent['username']) . "</td>";
                    echo "<td>" . htmlspecialchars($agent['login_user']) . "</td>";
                    echo "<td>" . htmlspecialchars($agent['dial_mode']) . "</td>";
                    echo "<td>" . htmlspecialchars($agent['agent_status']) . "</td>";
                    echo "<td>" . htmlspecialchars($agent['extension']) . "</td>";
                    echo "<td>" . (empty($agent['tot_call']) ? "N/A" : htmlspecialchars($agent['tot_call'])) . "</td>";
                    echo "<td>" . htmlspecialchars($agent['last_exec']) . "</td>";
                    echo "</tr>";
                }
            }
        }
    }
    ?>
</tbody>

            </table>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rows = document.querySelectorAll('#agentTable tbody tr');

            rows.forEach(row => {
                const statusCell = row.querySelector('td:nth-child(4)'); // Kolom ke-4 untuk "Status Agen"
                if (statusCell) {
                    const status = statusCell.textContent.trim();
                    if (status === 'On Call') {
                        row.classList.add('on-call');
                    } else if (status === 'Ready') {
                        row.classList.add('ready');
                    } else if (status === 'Idle') {
                        row.classList.add('idle');
                    }
                }
            });

            const buttons = document.querySelectorAll('.status-filter button');

            buttons.forEach(button => {
                button.addEventListener('click', () => {
                    const filter = button.getAttribute('data-filter');

                    rows.forEach(row => {
                        if (filter === 'all') {
                            row.style.display = '';
                        } else {
                            const statusCell = row.querySelector('td:nth-child(4)');
                            if (statusCell && statusCell.textContent.trim() !== filter) {
                                row.style.display = 'none';
                            } else {
                                row.style.display = '';
                            }
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
