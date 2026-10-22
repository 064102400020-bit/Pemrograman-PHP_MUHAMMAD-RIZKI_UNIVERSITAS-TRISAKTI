<?php
/**
 * ==============================================================================
 * TUGAS PEMROGRAMAN PHP: STRUKTUR KENDALI DAN FORM PROCESSING
 * ==============================================================================
 * Deskripsi:
 * File ini berisi form input data mahasiswa beserta pemrosesan data,
 * validasi input, sanitasi data dengan htmlspecialchars(), serta penentuan
 * status kelulusan menggunakan struktur kendali if-elseif-else.
 * ==============================================================================
 */

// Inisialisasi variabel awal untuk menyimpan nilai input dan pesan error
$nama = "";
$email = "";
$nilai = "";
$errors = [];
$status = "";
$badgeClass = "";
$formSubmitted = false;

// ------------------------------------------------------------------------------
// 1. PEMROSESAN FORM DENGAN METHOD POST
// ------------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Mengecek apakah tombol submit ditekan dan data tersedia di superglobal $_POST
    if (isset($_POST["submit"])) {
        // Mengambil dan membersihkan spasi di awal/akhir input
        $nama  = isset($_POST["nama"]) ? trim($_POST["nama"]) : "";
        $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
        $nilai = isset($_POST["nilai"]) ? trim($_POST["nilai"]) : "";

        // --------------------------------------------------------------------------
        // 2. VALIDASI INPUT
        // --------------------------------------------------------------------------
        
        // Validasi field Nama: tidak boleh kosong
        if (empty($nama)) {
            $errors[] = "Nama lengkap wajib diisi.";
        }

        // Validasi field Email: tidak boleh kosong dan harus format email yang valid
        if (empty($email)) {
            $errors[] = "Email wajib diisi.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid (contoh: nama@domain.com).";
        }

        // Validasi field Nilai Ujian: tidak boleh kosong, harus berupa angka, dan dalam rentang 0 - 100
        // Catatan: Menggunakan strlen() agar angka '0' tidak dianggap kosong oleh empty()
        if (strlen($nilai) === 0) {
            $errors[] = "Nilai ujian wajib diisi.";
        } elseif (!is_numeric($nilai)) {
            $errors[] = "Nilai ujian harus berupa angka.";
        } else {
            $nilaiFloat = (float) $nilai;
            if ($nilaiFloat < 0 || $nilaiFloat > 100) {
                $errors[] = "Nilai ujian harus berada dalam rentang 0 sampai 100.";
            }
        }

        // --------------------------------------------------------------------------
        // 3. STRUKTUR KENDALI (IF - ELSEIF - ELSE) UNTUK STATUS KELULUSAN
        // --------------------------------------------------------------------------
        // Jika tidak terdapat error pada validasi, tentukan status kelulusan
        if (empty($errors)) {
            $formSubmitted = true;
            $nilaiAngka = (float) $nilai;

            /**
             * Catatan Soal:
             * - Jika nilai > 70  => Status "Lulus"
             * - Jika nilai < 70  => Status "Remedial"
             * - Kasus nilai tepat 70 tidak didefinisikan secara khusus pada instruksi utama,
             *   sehingga sesuai ketentuan tugas, nilai tepat 70 dianggap "Lulus".
             */
            if ($nilaiAngka > 70) {
                $status = "Lulus";
                $badgeClass = "status-lulus";
            } elseif ($nilaiAngka == 70) {
                // Penanganan khusus nilai batas: nilai tepat 70 dianggap "Lulus"
                $status = "Lulus";
                $badgeClass = "status-lulus";
            } else {
                // Untuk kondisi nilai di bawah 70 (< 70)
                $status = "Remedial";
                $badgeClass = "status-remedial";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Kendali & Form Processing - Pemrograman PHP</title>
    <style>
        /* ==========================================================================
           RESET & GAYA DASAR (CSS NATIVE)
           ========================================================================== */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f0f2f5;
            color: #333333;
            line-height: 1.6;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        /* Container Pembungkus Kartu */
        .container {
            width: 100%;
            max-width: 650px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Header Kartu */
        .header {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: #ffffff;
            padding: 24px 28px;
            text-align: center;
        }

        .header h1 {
            font-size: 1.5rem;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .content {
            padding: 28px;
        }

        /* ==========================================================================
           ALERT ERROR & HASIL PROSES
           ========================================================================== */
        .alert-error {
            background-color: #fde8e8;
            border-left: 4px solid #e02424;
            color: #9b1c1c;
            padding: 14px 18px;
            border-radius: 6px;
            margin-bottom: 24px;
        }

        .alert-error h4 {
            margin-bottom: 6px;
            font-size: 0.95rem;
        }

        .alert-error ul {
            margin-left: 20px;
            font-size: 0.9rem;
        }

        /* Kartu Hasil Penilaian */
        .result-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 28px;
        }

        .result-box h3 {
            font-size: 1.1rem;
            color: #1e293b;
            margin-bottom: 14px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 8px;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px dashed #e2e8f0;
            font-size: 0.95rem;
        }

        .result-row:last-child {
            border-bottom: none;
        }

        .result-label {
            font-weight: 600;
            color: #64748b;
        }

        .result-value {
            font-weight: 600;
            color: #0f172a;
        }

        /* Badge Status Kelulusan */
        .badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Warna Hijau untuk Lulus */
        .status-lulus {
            background-color: #def7ec;
            color: #03543f;
            border: 1px solid #31c48d;
        }

        /* Warna Oranye/Merah untuk Remedial */
        .status-remedial {
            background-color: #feecdc;
            color: #9c4221;
            border: 1px solid #f98080;
        }

        /* ==========================================================================
           FORMULIR INPUT
           ========================================================================== */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            color: #374151;
        }

        .form-group label span.required {
            color: #e02424;
        }

        .form-control {
            width: 100%;
            padding: 10px 14px;
            font-size: 0.95rem;
            border: 1.5px solid #d1d5db;
            border-radius: 6px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        .form-text {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Tombol Submit */
        .btn-submit {
            width: 100%;
            background-color: #2563eb;
            color: #ffffff;
            border: none;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background-color: #1d4ed8;
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        /* Footer Informasi */
        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 0.8rem;
            color: #9ca3af;
            border-top: 1px solid #f3f4f6;
            padding-top: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Aplikasi -->
    <div class="header">
        <h1>Form Penilaian Mahasiswa</h1>
        <p>Topik: Struktur Kendali & Form Processing (PHP Native)</p>
    </div>

    <div class="content">
        <!-- ------------------------------------------------------------------ -->
        <!-- MENAMPILKAN PESAN ERROR VALIDASI (JIKA ADA)                        -->
        <!-- ------------------------------------------------------------------ -->
        <?php if (!empty($errors)): ?>
            <div class="alert-error">
                <h4>Terjadi Kesalahan Pengisian Form:</h4>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <!-- Mengamankan output pesan error dengan htmlspecialchars -->
                        <li><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- ------------------------------------------------------------------ -->
        <!-- MENAMPILKAN HASIL PEMROSESAN FORM (JIKA VALID)                     -->
        <!-- ------------------------------------------------------------------ -->
        <?php if ($formSubmitted && empty($errors)): ?>
            <div class="result-box">
                <h3>Hasil Penilaian Ujian</h3>
                
                <div class="result-row">
                    <span class="result-label">Nama Mahasiswa</span>
                    <!-- Mengamankan output string dari XSS dengan htmlspecialchars -->
                    <span class="result-value"><?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>

                <div class="result-row">
                    <span class="result-label">Email</span>
                    <!-- Mengamankan output email dengan htmlspecialchars -->
                    <span class="result-value"><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>

                <div class="result-row">
                    <span class="result-label">Nilai Ujian</span>
                    <!-- Menampilkan nilai yang sudah tervalidasi numerik -->
                    <span class="result-value"><?= htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8'); ?></span>
                </div>

                <div class="result-row">
                    <span class="result-label">Status Kelulusan</span>
                    <!-- Badge status dengan warna dinamis (hijau untuk lulus, oranye/merah untuk remedial) -->
                    <span class="badge <?= $badgeClass; ?>">
                        <?= htmlspecialchars($status, ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>

        <!-- ------------------------------------------------------------------ -->
        <!-- FORMULIR INPUT DENGAN METHOD POST                                  -->
        <!-- ------------------------------------------------------------------ -->
        <!-- Mengarahkan action ke file itu sendiri ($_SERVER['PHP_SELF']) -->
        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" method="POST" novalidate>
            <!-- Input Nama -->
            <div class="form-group">
                <label for="nama">Nama Lengkap <span class="required">*</span></label>
                <!-- Nilai value dipertahankan jika form gagal/berhasil disubmit -->
                <input 
                    type="text" 
                    id="nama" 
                    name="nama" 
                    class="form-control" 
                    placeholder="Masukkan nama lengkap" 
                    value="<?= htmlspecialchars($nama, ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
            </div>

            <!-- Input Email -->
            <div class="form-group">
                <label for="email">Alamat Email <span class="required">*</span></label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control" 
                    placeholder="contoh: mahasiswa@kampus.ac.id" 
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
            </div>

            <!-- Input Nilai Ujian (Rentang 0 - 100) -->
            <div class="form-group">
                <label for="nilai">Nilai Ujian (0 - 100) <span class="required">*</span></label>
                <input 
                    type="number" 
                    id="nilai" 
                    name="nilai" 
                    class="form-control" 
                    min="0" 
                    max="100" 
                    step="any"
                    placeholder="Contoh: 85 atau 70.5" 
                    value="<?= htmlspecialchars($nilai, ENT_QUOTES, 'UTF-8'); ?>"
                    required
                >
                <div class="form-text">Batas kelulusan adalah 70 ke atas.</div>
            </div>

            <!-- Tombol Kirim / Submit -->
            <button type="submit" name="submit" class="btn-submit">Proses Nilai</button>
        </form>

        <div class="footer">
            Tugas Pemrograman PHP &bull; Universitas Trisakti &bull; Muhammad Rizki
        </div>
    </div>
</div>

</body>
</html>
