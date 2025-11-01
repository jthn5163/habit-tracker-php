<?php
session_start();
include('../db.php');

// Check session
if (!isset($_SESSION['user'])) {
  echo "❌ Session expired. Please login again.";
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

// Sanitize and validate inputs
$habit_id = isset($_POST['id']) ? trim($_POST['id']) : '';
$habit_name = isset($_POST['habit_name']) ? trim($_POST['habit_name']) : '';
$frequency = isset($_POST['frequency']) ? trim($_POST['frequency']) : '';
$type = isset($_POST['type']) ? trim($_POST['type']) : '';
$notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

// Validation
if (empty($habit_name)) {
  echo "⚠️ Habit name is required!";
  exit;
}

if (empty($frequency)) {
  echo "⚠️ Frequency is required!";
  exit;
}

if (empty($type)) {
  echo "⚠️ Type is required!";
  exit;
}

// Validate frequency values
$valid_frequencies = ['Daily', 'Weekly', 'Monthly'];
if (!in_array($frequency, $valid_frequencies)) {
  echo "⚠️ Invalid frequency selected!";
  exit;
}

// Validate type values
$valid_types = ['Good', 'Bad'];
if (!in_array($type, $valid_types)) {
  echo "⚠️ Invalid type selected!";
  exit;
}

// Prevent SQL injection by limiting habit name length
if (strlen($habit_name) > 100) {
  echo "⚠️ Habit name is too long (max 100 characters)!";
  exit;
}

try {
  if (empty($habit_id)) {
    // Insert new habit
    $stmt = $conn->prepare("INSERT INTO habits (user_id, habit_name, frequency, type, notes, progress, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())");
    $stmt->bind_param("issss", $user_id, $habit_name, $frequency, $type, $notes);

    if ($stmt->execute()) {
      echo "✅ Habit added successfully!";
    } else {
      echo "❌ Error adding habit: " . $stmt->error;
    }
    $stmt->close();

  } else {
    // Update existing habit - verify ownership
    $check_stmt = $conn->prepare("SELECT id FROM habits WHERE id = ? AND user_id = ?");
    $check_stmt->bind_param("ii", $habit_id, $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows === 0) {
      echo "❌ Habit not found or access denied!";
      $check_stmt->close();
      exit;
    }
    $check_stmt->close();

    // Update habit
    $stmt = $conn->prepare("UPDATE habits SET habit_name=?, frequency=?, type=?, notes=? WHERE id=? AND user_id=?");
    $stmt->bind_param("sssiii", $habit_name, $frequency, $type, $notes, $habit_id, $user_id);

    if ($stmt->execute()) {
      if ($stmt->affected_rows > 0) {
        echo "✅ Habit updated successfully!";
      } else {
        echo "ℹ️ No changes made.";
      }
    } else {
      echo "❌ Error updating habit: " . $stmt->error;
    }
    $stmt->close();
  }
} catch (Exception $e) {
  echo "❌ Database error: " . $e->getMessage();
}

$conn->close();
?>