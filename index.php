<?php
http_response_code(403); // kode akses ditolak
echo json_encode([
    "status" => false,
    "message" => "Anda terhubung dengan API asset."
]);
exit;
