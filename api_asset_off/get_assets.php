<?php
header("Content-Type: application/json");

$valid_token = '4SS3T123K3Y';
$incoming_token = $_GET['token'] ?? '';
if ($incoming_token !== $valid_token) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Akses ditolak. Token tidak valid.']);
    exit;
}

$host = 'localhost'; 
$db = 'db_assets_temp'; // Changed
$user = 'root'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Koneksi database gagal.']);
    exit();
}

$photoBaseDir = '../data_foto_temp/';
$photoBaseUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/data_foto_temp/';

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

$plaintext = json_encode($assets);
$key = '12345678901234567890123456789012';
$iv = openssl_random_pseudo_bytes(16);
$encrypted = openssl_encrypt($plaintext, 'AES-256-CBC', $key, 0, $iv);

echo json_encode([
    'data' => $encrypted,
    'iv' => base64_encode($iv),
]);
?>
