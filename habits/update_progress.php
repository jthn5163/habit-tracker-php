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
$action = $_POST['action'] ?? 'increment'; // Get action (increment or decrement)

if ($id == '') {
  echo json_encode(["status" => "error", "message" => "Invalid habit ID"]);
  exit;
}

// Step 1: Fetch habit details
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

// Step 2: Calculate increment based on frequency
$increment = 0;
switch ($frequency) {
    case 'Daily':
        $increment = 100; // 1 day = 100% (complete for that day)
        break;
    case 'Weekly':
        $increment = 100 / 7; // 1 day out of 7 = ~14.29% per day
        break;
    case 'Monthly':
        $increment = 100 / 30; // 1 day out of 30 = ~3.33% per day
        break;
    default:
        $increment = 10;
}

// Step 3: Calculate new progress based on habit type and action
$new_progress = $current_progress;

if ($habit_type === 'Bad') {
    // Bad habit logic
    if ($action === 'decrement') {
        // User failed to resist - decrease progress (double penalty)
        $new_progress = max(0, $current_progress - ($increment * 2));
    } else {
        // User resisted successfully - increase progress
        $new_progress = min(100, $current_progress + $increment);
    }
} else {
    // Good habit logic (always increment for Pomodoro completion)
    $new_progress = min(100, $current_progress + $increment);
    
    // Step 4: Log Pomodoro session (only for Good habits)
    $start_time = date('Y-m-d H:i:s');
    $end_time = date('Y-m-d H:i:s', strtotime('+25 minutes'));
    $duration = 25;

    $stmt2 = $conn->prepare("
      INSERT INTO pomodoro_sessions (hobby_id, hobby_name, session_start, session_end, duration_minutes)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt2->bind_param("isssi", $id, $habit_name, $start_time, $end_time, $duration);
    $stmt2->execute();
    $stmt2->close();
}

// Step 5: Update progress in database
$stmt3 = $conn->prepare("UPDATE habits SET progress = ? WHERE id=? AND user_id=?");
$stmt3->bind_param("dii", $new_progress, $id, $user_id);
$stmt3->execute();

if ($stmt3->affected_rows > 0 || $new_progress == $current_progress) {
  echo json_encode([
    "status" => "success", 
    "message" => "Progress updated successfully",
    "new_progress" => round($new_progress, 2),
    "old_progress" => round($current_progress, 2),
    "action" => $action,
    "habit_type" => $habit_type
  ]);
} else {
  echo json_encode(["status" => "error", "message" => "Failed to update progress"]);
}

$stmt->close();
$stmt3->close();
$conn->close();
?>
