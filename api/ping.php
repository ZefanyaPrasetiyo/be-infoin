<?php
// api/ping.php
echo json_encode([
    'status' => 'ok',
    'message' => 'API is reachable!',
    'timestamp' => date('Y-m-d H:i:s')
]);