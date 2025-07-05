<?php
header("Content-Type: application/json");

// --- Koneksi Database ---
$host = 'localhost'; $db = 'db_asset'; $user = 'root'; $pass = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit();
}

// --- Direktori Foto ---
$photoBaseDir = '../data_foto/';
$photoBaseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/data_foto/';

$stmt = $pdo->query("SELECT * FROM assets ORDER BY id ASC");
$assets = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $assetId = $row['id'];
    $assetPhotoDir = $photoBaseDir . $assetId . '/';
    $photoUrls = [];

    if (is_dir($assetPhotoDir)) {
        $files = array_diff(scandir($assetPhotoDir), array('.', '..'));
        foreach ($files as $file) {
            if (is_file($assetPhotoDir . $file)) {
                $photoUrls[] = $photoBaseUrl . $assetId . '/' . rawurlencode($file);
            }
        }
    }
    $row['photos'] = $photoUrls;
    $assets[] = $row;
}

echo json_encode($assets);
?>