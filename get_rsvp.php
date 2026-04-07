<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "wedding_db");

$result = $conn->query("SELECT * FROM rsvp ORDER BY id DESC LIMIT 7");

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);