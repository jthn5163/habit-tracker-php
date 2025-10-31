<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user'])) {
  echo "Session expired. Please login again.";
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$id = $_POST['id'] ?? '';

if ($id == '') {
  echo "Invalid hobby ID!";
  exit;
}

$stmt = $conn->prepare("DELETE FROM hobbies WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute()) {
  echo "🗑️ Hobby deleted successfully!";
} else {
  echo "❌ Error deleting hobby!";
}

$stmt->close();
$conn->close();
?>
