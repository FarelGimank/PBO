<?php
function cekKelulusan($nilai) {
    return ($nilai >= 60) ? "LULUS" : "TIDAK LULUS";
}

$hasil = null;
$warning = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hitung'])) {
    $nama      = $_POST['nama'];
    $tugas     = (int)$_POST['tugas'];
    $inputPertemuan = (int)$_POST['pertemuan'];
    $aktif     = isset($_POST['aktif']);
    
    if ($inputPertemuan > 16) {
        $warning = "Peringatan: Jumlah pertemuan hanya 16! Otomatis diset menjadi 16.";
        $pertemuan = 16;
    } else if ($inputPertemuan < 0) {
        $pertemuan = 0;
    } else {
        $pertemuan = $inputPertemuan;
    }

    $nilaiList = [
        "Tugas" => (float)$_POST['tugas'],
        "UTS" => (float)$_POST['uts'],
        "UAS" => (float)$_POST['uas']
    ];

    $total = 0;
    foreach ($nilaiList as $n) {
        $total += $n;
    }
    $rataRata = $total / count($nilaiList);

    if ($rataRata >= 80) { $grade = "A"; }
    elseif ($rataRata >= 70) { $grade = "B"; }
    elseif ($rataRata >= 60) { $grade = "C"; }
    else { $grade = "D"; }

    $status = cekKelulusan($rataRata);
    $hasil = compact('nama', 'pertemuan', 'aktif', 'nilaiList', 'rataRata', 'grade', 'status');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Aplikasi Nilai Mahasiswa</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f9; display: flex; justify-content: center; padding-top: 50px; }
        .box { background: white; padding: 25px; border-radius: 8px; width: 350px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input, button { width: 100%; padding: 10px; margin-top: 8px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background: #007bff; color: white; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background: #0056b3; }
        .btn-ulang { background: #6c757d; text-align: center; display: block; color: white; text-decoration: none; padding: 10px; border-radius: 4px; }
        .alert-warning { background: #fff3cd; color: #856404; padding: 10px; border: 1px solid #ffeeba; border-radius: 4px; margin-bottom: 15px; font-size: 0.9rem; }
    </style>
</head>
<body>

<div class="box">
    <h2>Program Nilai Mahasiswa</h2>

    <?php if (!empty($warning)): ?>
        <div class="alert-warning"><?= $warning ?></div>
    <?php endif; ?>

    <?php if ($hasil === null): ?>
        <form method="POST">
            <label>Nama Mahasiswa:</label>
            <input type="text" name="nama" required>

            <label>Jumlah Pertemuan:</label>
            <input type="number" name="pertemuan" required>

            <label>Nilai Tugas:</label>
            <input type="number" name="tugas" required>

            <label>Nilai UTS:</label>
            <input type="number" name="uts" required>

            <label>Nilai UAS:</label>
            <input type="number" name="uas" required>

            <div>
                <input type="checkbox" name="aktif" id="aktif" style="width:auto;">
                <label for="aktif" style="display:inline;">Mahasiswa Aktif</label>
            </div>

            <button type="submit" name="hitung">Hitung Nilai</button>
        </form>
    <?php else: ?>
        <p><strong>Nama:</strong> <?= htmlspecialchars($hasil['nama']) ?></p>
        <p><strong>Jumlah Pertemuan:</strong> <?= $hasil['pertemuan'] ?> / 16</p>
        <p><strong>Status Aktif:</strong> <?= $hasil['aktif'] ? 'Ya' : 'Tidak' ?></p>
        
        <p><strong>Rincian Nilai:</strong></p>
        <ul>
            <?php foreach ($nilaiList as $kategori => $val): ?>
                <li><?= $kategori ?>: <?= $val ?></li>
            <?php endforeach; ?>
        </ul>

        <p><strong>Rata-rata:</strong> <?= number_format($hasil['rataRata'], 2) ?></p>
        <p><strong>Grade:</strong> <?= $hasil['grade'] ?></p>
        <p><strong>Status:</strong> <?= $hasil['status'] ?></p>

        <a href="" class="btn-ulang">Hitung Ulang</a>
    <?php endif; ?>

</div>

</body>
</html>