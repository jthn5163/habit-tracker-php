<?php
session_start();
include('../db.php');

if (!isset($_SESSION['user'])) {
  echo json_encode(["error" => "Session expired"]);
  exit;
}

$user = $_SESSION['user'];
$user_id = $user['id'];
$id = $_POST['id'] ?? '';

if ($id == '') {
  echo json_encode(["error" => "Invalid habit ID"]);
  exit;
}

$stmt = $conn->prepare("SELECT * FROM habits WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
  echo json_encode($row);
} else {
  echo json_encode(["error" => "habit not found"]);
}

$stmt->close();
$conn->close();
?>
