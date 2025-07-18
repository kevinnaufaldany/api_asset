<?php
header("Content-Type: application/json");

$uploadDir = '../data_foto/';
$response = ['success' => false, 'message' => 'Parameter tidak lengkap.'];

if (isset($_FILES['photo']) && isset($_POST['asset_id'])) {
    $assetId = $_POST['asset_id'];
    $photo = $_FILES['photo'];

    $assetDir = $uploadDir . $assetId . '/';
    if (!file_exists($assetDir)) {
        mkdir($assetDir, 0777, true);
    }
    
    $fileName = basename($photo['name']);
    $targetPath = $assetDir . $fileName;

    if (move_uploaded_file($photo['tmp_name'], $targetPath)) {
        $response = ['success' => true, 'message' => 'Upload berhasil!'];
    } else {
        $response['message'] = 'Gagal memindahkan file.';
    }
}

echo json_encode($response);
?>