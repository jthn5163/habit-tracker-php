<?php
session_start();
include('../db.php');

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
  echo json_encode(["status" => "error", "message" => "Session expired. Please log in again."]);
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$id = $_GET['id'] ?? '';

if ($id == '') {
  echo json_encode(["status" => "error", "message" => "Invalid habit ID."]);
  exit;
}

$stmt = $conn->prepare("SELECT id, habit_name, frequency, type, notes, progress, created_at FROM habits WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode([
    "status" => "success",
    "habit" => $row
  ]);
} else {
  echo json_encode(["status" => "error", "message" => "Habit not found."]);
}

$stmt->close();
$conn->close();
?>
