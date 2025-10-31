<?php
session_start();
include('../db.php');

header('Content-Type: application/json'); // important for JSON response

if (!isset($_SESSION['user'])) {
  echo json_encode(["status" => "error", "message" => "Please login first."]);
  exit;
}

$user_id = $_SESSION['user']['id'];

$query = "SELECT * FROM habits WHERE user_id = ? ORDER BY id DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$habits = [];
while ($row = $result->fetch_assoc()) {
  $habits[] = $row;
}

echo json_encode([
  "status" => "success",
  "count" => count($habits),
  "data" => $habits
]);

$stmt->close();
$conn->close();
?>
