<?php
include('./db.php');
session_start();

header('Content-Type: application/json'); // return JSON response

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Basic validation
    if ($email === '' || $password === '') {
        echo json_encode(['status' => 'error', 'message' => 'Please fill all fields!']);
        exit;
    }

    // Check user existence
    $query = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Start session
            $_SESSION['user'] = [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email']
            ];

            echo json_encode(['status' => 'success', 'message' => 'Login successful!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Incorrect password!']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No account found with this email!']);
    }
}
?>
