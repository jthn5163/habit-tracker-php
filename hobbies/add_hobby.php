<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user'])) {
  echo "Session expired. Please login again.";
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];

$hobby_id = $_POST['id'] ?? '';
$hobby_name = trim($_POST['hobby_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$frequency = trim($_POST['frequency'] ?? '');
$notes = trim($_POST['notes'] ?? '');

if ($hobby_name == '') {
  echo "Hobby name is required!";
  exit;
}

if ($hobby_id == '') {
  // Insert new hobby
  $stmt = $conn->prepare("INSERT INTO hobbies (user_id, hobby_name, category, frequency, notes) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("issss", $user_id, $hobby_name, $category, $frequency, $notes);

  if ($stmt->execute()) {
    echo "✅ Hobby added successfully!";
  } else {
    echo "❌ Error adding hobby!";
  }
  $stmt->close();
} else {
  // Update existing hobby
  $stmt = $conn->prepare("UPDATE hobbies SET hobby_name=?, category=?, frequency=?, notes=? WHERE id=? AND user_id=?");
  $stmt->bind_param("ssssii", $hobby_name, $category, $frequency, $notes, $hobby_id, $user_id);

  if ($stmt->execute()) {
    echo "✅ Hobby updated successfully!";
  } else {
    echo "❌ Error updating hobby!";
  }
  $stmt->close();
}

$conn->close();
?>
