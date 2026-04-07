<?php
require 'config.php';

$rows = $pdo->query("SELECT * FROM rsvp ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$hadir = array_filter($rows, fn($r) => $r['kehadiran'] === 'hadir');
$total_tamu = array_sum(array_column(iterator_to_array((function() use($hadir) { yield from $hadir; })()), 'jumlah_tamu'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin RSVP</title>
  <style>
    body { font-family: sans-serif; padding: 2rem; background: #f5f5f5; }
    table { width: 100%; border-collapse: collapse; background: white; }
    th, td { padding: 0.75rem 1rem; border: 1px solid #ddd; text-align: left; }
    th { background: #c9a96e; color: white; }
    tr:nth-child(even) { background: #fafafa; }
    .badge-hadir { color: green; font-weight: bold; }
    .badge-tidak { color: red; font-weight: bold; }
    .summary { margin-bottom: 1.5rem; background: white; padding: 1rem 1.5rem; border-left: 4px solid #c9a96e; }
  </style>
</head>
<body>
  <h2>📋 Rekap RSVP — Fikri & Erna</h2>
  <div class="summary">
    Total respons: <strong><?= count($rows) ?></strong> &nbsp;|&nbsp;
    Hadir: <strong><?= count($hadir) ?></strong> &nbsp;|&nbsp;
    Total tamu hadir: <strong><?= $total_tamu ?></strong>
  </div>
  <table>
    <thead>
      <tr><th>#</th><th>Nama</th><th>Jumlah</th><th>Kehadiran</th><th>Pesan</th><th>Waktu</th></tr>
    </thead>
    <tbody>
      <?php foreach ($rows as $i => $r): ?>
      <tr>
        <td><?= $i + 1 ?></td>
        <td><?= htmlspecialchars($r['nama']) ?></td>
        <td><?= $r['jumlah_tamu'] ?></td>
        <td class="badge-<?= $r['kehadiran'] ?>"><?= $r['kehadiran'] === 'hadir' ? '✓ Hadir' : '✗ Tidak' ?></td>
        <td><?= htmlspecialchars($r['pesan'] ?? '-') ?></td>
        <td><?= $r['created_at'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>