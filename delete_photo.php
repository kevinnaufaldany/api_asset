<?php
header("Content-Type: application/json");

$response = ['success' => false, 'message' => 'Parameter tidak lengkap.'];

if (isset($_POST['photo_url'])) {
    $photoUrl = $_POST['photo_url'];
    
    // Ubah URL menjadi path file di server
    $basePath = "http://" . $_SERVER['HTTP_HOST'] . "/";
    $filePath = str_replace($basePath, '../', $photoUrl);
    
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            $response = ['success' => true, 'message' => 'File berhasil dihapus.'];
        } else {
            $response['message'] = 'Gagal menghapus file.';
        }
    } else {
        $response['message'] = 'File tidak ditemukan.';
    }
}

echo json_encode($response);
?>