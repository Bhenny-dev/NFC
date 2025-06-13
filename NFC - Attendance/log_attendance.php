<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'nfc_attendance';

$conn = new mysqli($host, $user, $pass, $db);

$data = json_decode(file_get_contents("php://input"));

if (!$data || !isset($data->uid)) {
    http_response_code(400);
    echo json_encode(["error" => "UID missing"]);
    exit;
}

$uid = $conn->real_escape_string($data->uid);

// Find student
$res = $conn->query("SELECT id FROM students WHERE uid = '$uid'");

if ($res->num_rows > 0) {
    $student = $res->fetch_assoc();
    $student_id = $student['id'];
    $conn->query("INSERT INTO attendance (student_id) VALUES ($student_id)");
    echo json_encode(["status" => "Recorded"]);
} else {
    echo json_encode(["status" => "UID not found"]);
}
?>
