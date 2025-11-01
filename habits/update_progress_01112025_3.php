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
$action = $_POST['action'] ?? 'increment';

if ($id == '') {
  echo json_encode(["status" => "error", "message" => "Invalid habit ID"]);
  exit;
}

// Fetch habit details
$stmt = $conn->prepare("SELECT habit_name, type, frequency, progress FROM habits WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$habit = $result->fetch_assoc();

if (!$habit) {
  echo json_encode(["status" => "error", "message" => "Habit not found"]);
  exit;
}

$habit_name = $habit['habit_name'];
$habit_type = $habit['type'];
$frequency = $habit['frequency'];
$current_progress = floatval($habit['progress']);

// Calculate increment - DAILY HABITS ONLY
$increment = 100; // Daily = 100% for the day

// Calculate new progress based on habit type and action
$new_progress = $current_progress;

if ($habit_type === 'Bad') {
    // Bad habit logic - Binary for daily
    if ($action === 'decrement') {
        // Failed to resist - reset to 0
        $new_progress = 0;
    } else {
        // Resisted successfully - set to 100%
        $new_progress = 100;
    }
} else {
    // Good habit logic - Incremental Pomodoro sessions
    // 30 Pomodoro sessions to reach 100%
    $good_increment = 100 / 30; // ~3.33% per session
    $new_progress = min(100, $current_progress + $good_increment);
    
    // Log Pomodoro session (only for Good habits)
    $start_time = date('Y-m-d H:i:s');
    $end_time = date('Y-m-d H:i:s', strtotime('+25 minutes'));
    $duration = 25;

    $stmt2 = $conn->prepare("
      INSERT INTO pomodoro_sessions (hobby_id, hobby_name, session_start, session_end, duration_minutes)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt2->bind_param("isssi", $id, $habit_name, $start_time, $end_time, $duration);
    
    if (!$stmt2->execute()) {
        error_log("Failed to log Pomodoro session: " . $stmt2->error);
    }
    
    $stmt2->close();
}

// Update progress in database
$stmt3 = $conn->prepare("UPDATE habits SET progress = ? WHERE id=? AND user_id=?");
$stmt3->bind_param("dii", $new_progress, $id, $user_id);
$stmt3->execute();

if ($stmt3->affected_rows > 0 || $new_progress == $current_progress) {
  // Calculate sessions completed (for good habits)
  $sessions_completed = 0;
  if ($habit_type === 'Good') {
    $sessions_completed = round(($new_progress / 100) * 30);
  }
  
  echo json_encode([
    "status" => "success", 
    "message" => "Progress updated successfully",
    "new_progress" => round($new_progress, 2),
    "old_progress" => round($current_progress, 2),
    "action" => $action,
    "habit_type" => $habit_type,
    "sessions_completed" => $sessions_completed
  ]);
} else {
  echo json_encode(["status" => "error", "message" => "Failed to update progress"]);
}

$stmt->close();
$stmt3->close();
$conn->close();
?>
