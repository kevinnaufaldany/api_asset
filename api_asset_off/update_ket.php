<?php
header("Content-Type: application/json");

// --- Koneksi Database ---
$host = 'localhost';
$db   = 'db_assets_temp'; // Updated
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=$charset", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit();
}

$response = ['success' => false, 'message' => 'ID atau catatan tidak diterima.'];

if (isset($_POST['id']) && isset($_POST['ket_1'])) {
    $assetId = $_POST['id'];
    $newNotes = $_POST['ket_1'];

    try {
        $sql = "UPDATE assets SET ket_1 = ? WHERE id = ?";
        $stmt= $pdo->prepare($sql);
        
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
