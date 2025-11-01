<?php
session_start();
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user'])) {
  echo json_encode(['status' => 'error', 'message' => 'Session expired']);
  exit;
}

require_once '../db.php';

$user_id = $_SESSION['user']['id'];
$period = $_GET['period'] ?? 'daily';
$custom_start = $_GET['start_date'] ?? null;
$custom_end = $_GET['end_date'] ?? null;

// ========================================
// CALCULATE DATE RANGE
// ========================================

$end_date = date('Y-m-d');

switch ($period) {
  case 'daily':
    $start_date = date('Y-m-d');
    break;
  case 'weekly':
    $start_date = date('Y-m-d', strtotime('-7 days'));
    break;
  case 'monthly':
    $start_date = date('Y-m-d', strtotime('-30 days'));
    break;
  case 'yearly':
    $start_date = date('Y-m-d', strtotime('-365 days'));
    break;
  case 'custom':
    if ($custom_start && $custom_end) {
      $start_date = $custom_start;
      $end_date = $custom_end;
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Invalid date range']);
      exit;
    }
    break;
  default:
    $start_date = date('Y-m-d');
}

// ========================================
// GET TOTAL HABITS
// ========================================

$stmt = $conn->prepare("SELECT COUNT(*) as total FROM habits WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$total_habits = $result->fetch_assoc()['total'];
$stmt->close();

// ========================================
// GET AVERAGE COMPLETION RATE
// ========================================

$stmt = $conn->prepare("SELECT AVG(progress) as avg_progress FROM habits WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$avg_progress = round($result->fetch_assoc()['avg_progress'] ?? 0, 2);
$stmt->close();

// ========================================
// GET POMODORO SESSIONS AND TIME
// ========================================

$stmt = $conn->prepare("
  SELECT 
    COUNT(*) as total_sessions, 
    SUM(duration_minutes) as total_minutes
  FROM pomodoro_sessions 
  WHERE hobby_id IN (SELECT id FROM habits WHERE user_id = ?)
  AND DATE(session_start) BETWEEN ? AND ?
");
$stmt->bind_param("iss", $user_id, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();
$pomodoro_data = $result->fetch_assoc();
$total_sessions = $pomodoro_data['total_sessions'] ?? 0;
$total_minutes = $pomodoro_data['total_minutes'] ?? 0;
$total_hours = round($total_minutes / 60, 1);
$stmt->close();

// ========================================
// GET HABITS WITH DETAILS
// ========================================

$stmt = $conn->prepare("
  SELECT 
    h.id,
    h.habit_name,
    h.type,
    h.progress,
    COUNT(ps.id) as session_count,
    COALESCE(SUM(ps.duration_minutes), 0) as total_time_minutes
  FROM habits h
  LEFT JOIN pomodoro_sessions ps ON h.id = ps.hobby_id 
    AND DATE(ps.session_start) BETWEEN ? AND ?
  WHERE h.user_id = ?
  GROUP BY h.id, h.habit_name, h.type, h.progress
  ORDER BY h.progress DESC
");
$stmt->bind_param("ssi", $start_date, $end_date, $user_id);
$stmt->execute();
$result = $stmt->get_result();

$habits_details = [];
while ($row = $result->fetch_assoc()) {
  // Format time
  $hours = floor($row['total_time_minutes'] / 60);
  $minutes = $row['total_time_minutes'] % 60;
  $time_formatted = $hours > 0 ? "{$hours}h {$minutes}m" : "{$minutes}m";
  
  $habits_details[] = [
    'id' => $row['id'],
    'habit_name' => $row['habit_name'],
    'type' => $row['type'],
    'progress' => round($row['progress'], 2),
    'sessions' => $row['session_count'],
    'focus_time' => $time_formatted,
    'total_minutes' => $row['total_time_minutes'],
    'completed' => $row['progress'] >= 100
  ];
}
$stmt->close();

// ========================================
// GET GOOD VS BAD HABITS BREAKDOWN
// ========================================

$stmt = $conn->prepare("
  SELECT 
    type,
    COUNT(*) as count,
    AVG(progress) as avg_progress
  FROM habits 
  WHERE user_id = ?
  GROUP BY type
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$breakdown = [
  'Good' => ['count' => 0, 'avg_progress' => 0],
  'Bad' => ['count' => 0, 'avg_progress' => 0]
];

while ($row = $result->fetch_assoc()) {
  $breakdown[$row['type']] = [
    'count' => $row['count'],
    'avg_progress' => round($row['avg_progress'], 2)
  ];
}
$stmt->close();

// ========================================
// GET CHART DATA (LAST 7 DAYS)
// ========================================

$stmt = $conn->prepare("
  SELECT 
    DATE(session_start) as date,
    COUNT(*) as sessions,
    SUM(duration_minutes) as total_minutes
  FROM pomodoro_sessions
  WHERE hobby_id IN (SELECT id FROM habits WHERE user_id = ?)
  AND DATE(session_start) >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
  GROUP BY DATE(session_start)
  ORDER BY DATE(session_start) ASC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$chart_data = [];
while ($row = $result->fetch_assoc()) {
  $chart_data[] = [
    'date' => $row['date'],
    'sessions' => $row['sessions'],
    'hours' => round($row['total_minutes'] / 60, 1)
  ];
}
$stmt->close();

// ========================================
// GET TOP PERFORMING HABITS
// ========================================

$stmt = $conn->prepare("
  SELECT habit_name, type, progress
  FROM habits
  WHERE user_id = ?
  ORDER BY progress DESC
  LIMIT 3
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$top_habits = [];
while ($row = $result->fetch_assoc()) {
  $top_habits[] = $row;
}
$stmt->close();

// ========================================
// GET STRUGGLING HABITS
// ========================================

$stmt = $conn->prepare("
  SELECT habit_name, type, progress
  FROM habits
  WHERE user_id = ? AND type = 'Good' AND progress < 50
  ORDER BY progress ASC
  LIMIT 3
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$struggling_habits = [];
while ($row = $result->fetch_assoc()) {
  $struggling_habits[] = $row;
}
$stmt->close();

// ========================================
// BUILD RESPONSE
// ========================================

$response = [
  'status' => 'success',
  'period' => $period,
  'date_range' => [
    'start' => $start_date,
    'end' => $end_date,
    'formatted_start' => date('M d, Y', strtotime($start_date)),
    'formatted_end' => date('M d, Y', strtotime($end_date))
  ],
  'stats' => [
    'total_habits' => $total_habits,
    'completion_rate' => $avg_progress,
    'pomodoro_sessions' => $total_sessions,
    'focus_hours' => $total_hours,
    'focus_minutes' => $total_minutes
  ],
  'breakdown' => $breakdown,
  'habits' => $habits_details,
  'chart_data' => $chart_data,
  'insights' => [
    'top_habits' => $top_habits,
    'struggling_habits' => $struggling_habits
  ]
];

echo json_encode($response);

$conn->close();
?>
