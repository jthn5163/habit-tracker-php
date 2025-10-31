<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user'])) {
  echo json_encode(["status" => "error", "message" => "Session expired"]);
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$id = $_POST['id'] ?? '';
if ($id == '') {
  echo json_encode(["status" => "error", "message" => "Invalid habit ID"]);
  exit;
}

// Step 1: Fetch habit name
$stmt = $conn->prepare("SELECT habit_name FROM habits WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$habit = $result->fetch_assoc();

if (!$habit) {
  echo json_encode(["status" => "error", "message" => "Habit not found"]);
  exit;
}

$habit_name = $habit['habit_name'];

// Step 2: Log Pomodoro session
$start_time = date('Y-m-d H:i:s');
$end_time = date('Y-m-d H:i:s', strtotime('+25 minutes'));
$duration = 25;

$stmt2 = $conn->prepare("
  INSERT INTO pomodoro_sessions (hobby_id, hobby_name, session_start, session_end, duration_minutes)
  VALUES (?, ?, ?, ?, ?)
");
$stmt2->bind_param("isssi", $id, $habit_name, $start_time, $end_time, $duration);
$stmt2->execute();

// Step 3: Update progress (max 100)
$stmt3 = $conn->prepare("UPDATE habits SET progress = LEAST(progress + 10, 100) WHERE id=? AND user_id=?");
$stmt3->bind_param("ii", $id, $user_id);
$stmt3->execute();

if ($stmt3->affected_rows > 0) {
  echo json_encode(["status" => "success", "message" => "Pomodoro logged & progress updated"]);
} else {
  echo json_encode(["status" => "error", "message" => "Failed to update progress"]);
}

$stmt->close();
$stmt2->close();
$stmt3->close();
$conn->close();
?>
