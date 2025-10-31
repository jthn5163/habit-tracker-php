<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user'])) {
  echo "Session expired. Please login again.";
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$habit_id = $_POST['id'] ?? '';
$habit_name = trim($_POST['habit_name'] ?? '');
$frequency = trim($_POST['frequency'] ?? '');
$type = trim($_POST['type'] ?? '');  // ✅ new field added
$notes = trim($_POST['notes'] ?? '');

if ($habit_name == '') {
  echo "⚠️ Habit name is required!";
  exit;
}

if ($habit_id == '') {
  // ✅ Insert new habit
  $stmt = $conn->prepare("INSERT INTO habits (user_id, habit_name, frequency, type, notes, progress, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())");
  $stmt->bind_param("issss", $user_id, $habit_name, $frequency, $type, $notes);

  if ($stmt->execute()) {
    echo "✅ Habit added successfully!";
  } else {
    echo "❌ Error adding habit!";
  }
  $stmt->close();

} else {
  // ✅ Update existing habit
  $stmt = $conn->prepare("UPDATE habits SET habit_name=?, frequency=?, type=?, notes=? WHERE id=? AND user_id=?");
  $stmt->bind_param("ssssii", $habit_name, $frequency, $type, $notes, $habit_id, $user_id);

  if ($stmt->execute()) {
    echo "✅ Habit updated successfully!";
  } else {
    echo "❌ Error updating habit!";
  }
  $stmt->close();
}

$conn->close();
?>
