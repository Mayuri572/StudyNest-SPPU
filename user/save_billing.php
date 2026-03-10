<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $_SESSION['billing'] = [
        'full_name' => htmlspecialchars(trim($data['full_name'] ?? '')),
        'email'     => htmlspecialchars(trim($data['email'] ?? '')),
        'address'   => htmlspecialchars(trim($data['address'] ?? '')),
        'city'      => htmlspecialchars(trim($data['city'] ?? 'Pune')),
        'pincode'   => htmlspecialchars(trim($data['pincode'] ?? '')),
    ];
    echo json_encode(['status' => 'ok']);
} else {
    echo json_encode(['status' => 'error']);
}
?>