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
$category = trim($_POST['category'] ?? '');
$frequency = trim($_POST['frequency'] ?? '');
$notes = trim($_POST['notes'] ?? '');

if ($habit_name == '') {
  echo "habit name is required!";
  exit;
}

if ($habit_id == '') {
  // Insert new habit
  $stmt = $conn->prepare("INSERT INTO habits (user_id, habit_name, category, frequency, notes) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $user_id, $habit_name, $category, $frequency, $notes);

  if ($stmt->execute()) {
    echo "✅ habit added successfully!";
  } else {
    echo "❌ Error adding habit!";
  }
  $stmt->close();
} else {
  // Update existing habit
  $stmt = $conn->prepare("UPDATE habits SET habit_name=?, category=?, frequency=?, notes=? WHERE id=? AND user_id=?");
  $stmt->bind_param("ssssii", $habit_name, $category, $frequency, $notes, $habit_id, $user_id);

  if ($stmt->execute()) {
    echo "✅ habit updated successfully!";
  } else {
    echo "❌ Error updating habit!";
  }
  $stmt->close();
}

$conn->close();
?>
