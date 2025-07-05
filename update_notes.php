<?php
header("Content-Type: application/json");

// --- Koneksi Database ---
$host = 'localhost';
$db   = 'db_asset';
$user = 'root';
$pass = ''; // Kosongkan jika tidak ada password
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit();
}
// -------------------------

$response = ['success' => false, 'message' => 'ID atau catatan tidak diterima.'];

// Pastikan data 'id' dan 'notes' dikirim dari Flutter
if (isset($_POST['id']) && isset($_POST['notes'])) {
    $assetId = $_POST['id'];
    $newNotes = $_POST['notes'];

    try {
        // Gunakan prepared statement agar aman dari SQL Injection
        $sql = "UPDATE assets SET notes = ? WHERE id = ?";
        $stmt= $pdo->prepare($sql);
        
        // Jalankan query dengan data yang diterima
        if ($stmt->execute([$newNotes, $assetId])) {
            $response = ['success' => true, 'message' => 'Catatan berhasil diperbarui.'];
        } else {
            $response['message'] = 'Gagal memperbarui catatan di database.';
        }
    } catch (PDOException $e) {
        http_response_code(500);
        $response['message'] = 'Error database: ' . $e->getMessage();
    }
}

echo json_encode($response);
?>