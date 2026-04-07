<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

$nama        = trim($data['nama'] ?? '');
$jumlah_tamu = intval($data['jumlah_tamu'] ?? 0);
$kehadiran   = $data['kehadiran'] ?? '';
$pesan       = trim($data['pesan'] ?? '');

// Validasi
if (!$nama || !$jumlah_tamu || !in_array($kehadiran, ['hadir', 'tidak'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Data tidak lengkap']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        INSERT INTO rsvp (nama, jumlah_tamu, kehadiran, pesan)
        VALUES (:nama, :jumlah_tamu, :kehadiran, :pesan)
    ");
    $stmt->execute([
        ':nama'        => $nama,
        ':jumlah_tamu' => $jumlah_tamu,
        ':kehadiran'   => $kehadiran,
        ':pesan'       => $pesan ?: null,
    ]);

    echo json_encode(['status' => 'success', 'message' => 'RSVP berhasil disimpan']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data']);
}