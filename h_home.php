<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Habit Tracker</title>

  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      min-height: 100vh;
      padding: 20px 0;
    }

    .container {
      margin-top: 40px;
      max-width: 1200px;
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    }

    .header-section {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
      padding-bottom: 20px;
      border-bottom: 2px solid #e0e0e0;
    }

    .welcome-text {
      font-size: 1.8rem;
      font-weight: 600;
      color: #333;
      margin: 0;
    }

    .logout-btn {
      color: #dc3545;
      font-size: 1.5rem;
      transition: all 0.3s;
      text-decoration: none;
    }

    .logout-btn:hover {
      color: #bd2130;
      transform: scale(1.1);
    }

    .btn-custom {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
      border-radius: 50px;
      border: none;
      padding: 12px 30px;
      font-weight: 600;
      transition: all 0.3s;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }

    .btn-custom:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
      color: #fff;
    }

    .frequency-section {
      margin-bottom: 40px;
      background: #f8f9fa;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .frequency-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 3px solid;
    }

    .frequency-header.daily {
      border-bottom-color: #4CAF50;
    }

    .frequency-header.weekly {
      border-bottom-color: #2196F3;
    }

    .frequency-header.monthly {
      border-bottom-color: #FF9800;
    }

    .frequency-icon {
      width: 45px;
      height: 45px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      color: white;
    }

    .frequency-icon.daily {
      background: linear-gradient(135deg, #4CAF50, #81C784);
    }

    .frequency-icon.weekly {
      background: linear-gradient(135deg, #2196F3, #64B5F6);
    }

    .frequency-icon.monthly {
      background: linear-gradient(135deg, #FF9800, #FFB74D);
    }

    .frequency-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin: 0;
      color: #333;
    }

    .habit-count {
      font-size: 0.9rem;
      color: #6c757d;
      font-weight: 500;
    }

    .habit-card {
      background: #fff;
      border-radius: 12px;
      padding: 18px;
      margin-bottom: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      transition: all 0.3s;
      border-left: 4px solid transparent;
    }

    .habit-card:hover {
      transform: translateX(5px);
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.12);
    }

    .habit-card.good {
      border-left-color: #28a745;
    }

    .habit-card.bad {
      border-left-color: #dc3545;
    }

    .habit-name {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 8px;
    }

    .habit-name.good {
      color: #28a745;
    }

    .habit-name.bad {
      color: #dc3545;
    }

    .habit-meta {
      display: flex;
      gap: 15px;
      margin-top: 8px;
      font-size: 0.85rem;
      color: #6c757d;
    }

    .habit-meta span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .progress-container {
      cursor: pointer;
      padding: 5px;
      border-radius: 8px;
      transition: all 0.3s;
    }

    .progress-container:hover {
      background: #f1f3f5;
    }

    .progress {
      height: 10px;
      border-radius: 10px;
      background: #e9ecef;
      overflow: visible;
    }

    .progress-bar {
      border-radius: 10px;
      transition: width 0.6s ease;
    }

    .action-buttons {
      display: flex;
      gap: 8px;
    }

    .btn-action {
      width: 35px;
      height: 35px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      transition: all 0.3s;
      font-size: 1rem;
    }

    .btn-action:hover {
      transform: scale(1.15);
    }

    .btn-edit {
      background: #e3f2fd;
      color: #2196f3;
    }

    .btn-edit:hover {
      background: #2196f3;
      color: #fff;
    }

    .btn-delete {
      background: #ffebee;
      color: #f44336;
    }

    .btn-delete:hover {
      background: #f44336;
      color: #fff;
    }

    .empty-state {
      text-align: center;
      padding: 40px 20px;
      color: #9e9e9e;
    }

    .empty-state i {
      font-size: 3rem;
      margin-bottom: 15px;
      opacity: 0.4;
    }

    .empty-state p {
      margin: 0;
      font-size: 0.95rem;
    }

    .pomodoro-timer {
      font-size: 4rem;
      font-weight: 700;
      color: #667eea;
      font-family: 'Courier New', monospace;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    }

    .pomodoro-habit-name {
      color: #764ba2;
      font-weight: 600;
      font-size: 1.3rem;
    }

    .modal-content {
      border-radius: 20px;
      border: none;
    }

    .modal-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border-radius: 20px 20px 0 0;
      padding: 20px 30px;
    }

    .modal-header.bg-danger {
      background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .modal-title {
      font-weight: 600;
    }

    .btn-close {
      filter: brightness(0) invert(1);
    }

    .form-label {
      font-weight: 600;
      color: #495057;
      margin-bottom: 8px;
    }

    .form-control,
    .form-select {
      border-radius: 10px;
      border: 2px solid #e0e0e0;
      padding: 10px 15px;
      transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .time-select {
      border-radius: 10px;
      padding: 10px 20px;
      font-weight: 600;
      transition: all 0.3s;
    }

    .time-select.active {
      background-color: #667eea;
      color: white;
      border-color: #667eea;
    }

    @keyframes pulse {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.5;
      }
    }

    .pomodoro-active {
      animation: pulse 2s infinite;
    }

    .habits-grid {
      display: grid;
      gap: 12px;
    }

    @media (max-width: 768px) {
      .habit-card {
        padding: 15px;
      }

      .frequency-title {
        font-size: 1.2rem;
      }

      .pomodoro-timer {
        font-size: 3rem;
      }
    }

    /* Mini Floating Pomodoro Widget */
    .mini-pomodoro {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 15px 20px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      z-index: 9999;
      min-width: 250px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: none;
    }

    .mini-pomodoro:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .mini-pomodoro.paused {
      background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    .mini-pomodoro-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 10px;
    }

    .mini-pomodoro-title {
      font-size: 0.9rem;
      font-weight: 600;
      opacity: 0.9;
    }

    .mini-pomodoro-close {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
      width: 24px;
      height: 24px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 0.9rem;
      transition: all 0.3s;
    }

    .mini-pomodoro-close:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.1);
    }

    .mini-pomodoro-time {
      font-size: 2rem;
      font-weight: 700;
      font-family: 'Courier New', monospace;
      text-align: center;
      margin: 10px 0;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .mini-pomodoro-habit {
      font-size: 0.85rem;
      text-align: center;
      opacity: 0.9;
      margin-bottom: 10px;
    }

    .mini-pomodoro-controls {
      display: flex;
      gap: 8px;
      justify-content: center;
      margin-top: 10px;
    }

    .mini-btn {
      background: rgba(255, 255, 255, 0.2);
      border: none;
      color: white;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .mini-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: scale(1.05);
    }

    @keyframes pulse-mini {

      0%,
      100% {
        opacity: 1;
      }

      50% {
        opacity: 0.8;
      }
    }

    .mini-pomodoro-time.active {
      animation: pulse-mini 2s infinite;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header-section">
      <h3 class="welcome-text">🎯 Welcome, <?= htmlspecialchars($user['name']); ?></h3>
      <a href="logout.php" class="logout-btn" title="Logout">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </div>

    <button class="btn btn-custom mb-4" data-bs-toggle="modal" data-bs-target="#habitModal">
      <i class="bi bi-plus-circle"></i> Add New Habit
    </button>

    <div id="habitContainers">
      <div class="text-center text-muted py-5">
        <div class="spinner-border" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-3">Loading habits...</p>
      </div>
    </div>
  </div>

  <!-- Habit Modal -->
  <div class="modal fade" id="habitModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Add New Habit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-4">
          <form id="habitForm">
            <input type="hidden" id="habit_id">

            <div class="mb-3">
              <label class="form-label">Habit Name *</label>
              <input type="text" id="habit_name" class="form-control" placeholder="e.g., Morning Exercise" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Frequency *</label>
              <select id="frequency" class="form-select" required>
                <option value="" disabled selected>Choose frequency</option>
                <option value="Daily">Daily</option>
                <option value="Weekly">Weekly</option>
                <option value="Monthly">Monthly</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Type *</label>
              <select id="type" class="form-select" required>
                <option value="" disabled selected>Choose type</option>
                <option value="Good">Good Habit</option>
                <option value="Bad">Bad Habit (to break)</option>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Notes</label>
              <textarea id="notes" class="form-control" rows="3" placeholder="Add any additional notes..."></textarea>
            </div>

            <button type="submit" class="btn btn-custom w-100">
              <i class="bi bi-check-circle"></i> Save Habit
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Pomodoro Modal (Good Habits) -->
  <div class="modal fade" id="pomodoroModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header justify-content-center">
          <h5 class="modal-title">⏱️ Pomodoro Timer</h5>
          <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-5">
          <h4 id="pomodoroHabitName" class="pomodoro-habit-name mb-4"></h4>

          <div id="pomodoroSetup">
            <label class="form-label">Select Focus Time</label>
            <div class="d-flex gap-2 justify-content-center mb-4 flex-wrap">
              <button class="btn btn-outline-primary time-select" data-time="15">15 min</button>
              <button class="btn btn-outline-primary time-select active" data-time="25">25 min</button>
              <button class="btn btn-outline-primary time-select" data-time="45">45 min</button>
              <button class="btn btn-outline-primary time-select" data-time="60">60 min</button>
            </div>
          </div>

          <h2 id="pomodoroTimer" class="pomodoro-timer mb-3">25:00</h2>
          <p id="pomodoroStatus" class="text-muted mb-4">Select time and click Start</p>

          <!-- <div class="d-flex gap-3 justify-content-center">
            <button id="startPomodoro" class="btn btn-success btn-lg px-5">
              <i class="bi bi-play-fill"></i> Start
            </button>
            <button id="stopPomodoro" class="btn btn-danger btn-lg px-5 d-none">
              <i class="bi bi-stop-fill"></i> Stop
            </button>
          </div> -->

          <div class="d-flex gap-3 justify-content-center">
            <button id="startPomodoro" class="btn btn-success btn-lg px-5">
              <i class="bi bi-play-fill"></i> Start
            </button>
            <button id="pausePomodoro" class="btn btn-warning btn-lg px-5 d-none">
              <i class="bi bi-pause-fill"></i> Pause
            </button>
            <button id="stopPomodoro" class="btn btn-danger btn-lg px-5 d-none">
              <i class="bi bi-stop-fill"></i> Stop
            </button>
          </div>


        </div>
      </div>
    </div>
  </div>

  <!-- Break Streak Modal (Bad Habits) -->
  <div class="modal fade" id="breakStreakModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header justify-content-center bg-danger">
          <h5 class="modal-title text-white">🚫 Break the Habit</h5>
          <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-5">
          <h4 id="breakHabitName" class="text-danger mb-4"></h4>
          <p class="lead mb-4">Did you successfully resist this habit today?</p>

          <div class="mb-4">
            <i class="bi bi-shield-check text-success" style="font-size: 4rem;"></i>
          </div>

          <p class="text-muted mb-4">Marking progress will increase your resistance streak!</p>

          <div class="d-grid gap-3">
            <button id="confirmBreakStreak" class="btn btn-success btn-lg">
              <i class="bi bi-check-circle-fill"></i> Yes, I Resisted Today!
            </button>
            <button class="btn btn-outline-secondary" data-bs-dismiss="modal">
              <i class="bi bi-x-circle"></i> Not Today
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Mini Floating Pomodoro Widget -->
<div id="miniPomodoro" class="mini-pomodoro">
  <div class="mini-pomodoro-header">
    <span class="mini-pomodoro-title">⏱️ Pomodoro Timer</span>
    <button class="mini-pomodoro-close" id="closeMiniPomodoro">×</button>
  </div>
  <div class="mini-pomodoro-time active" id="miniTimer">25:00</div>
  <div class="mini-pomodoro-habit" id="miniHabitName">Focus Session</div>
  <div class="mini-pomodoro-controls">
    <button class="mini-btn" id="miniPause">
      <i class="bi bi-pause-fill"></i> Pause
    </button>
    <button class="mini-btn" id="miniStop">
      <i class="bi bi-stop-fill"></i> Stop
    </button>
  </div>
</div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>




<script>
  $(document).ready(function() {
    loadHabits();

    // Reset form when modal closes
    $('#habitModal').on('hidden.bs.modal', function() {
      $("#habitForm")[0].reset();
      $("#habit_id").val('');
      $("#modalTitle").text('Add New Habit');
    });

    // Save or update habit
    $("#habitForm").on("submit", function(e) {
      e.preventDefault();

      const formData = {
        id: $("#habit_id").val(),
        habit_name: $("#habit_name").val().trim(),
        frequency: $("#frequency").val(),
        type: $("#type").val(),
        notes: $("#notes").val().trim()
      };

      if (!formData.habit_name || !formData.frequency || !formData.type) {
        alert("Please fill all required fields!");
        return;
      }

      $.ajax({
        url: "./habits/add_habit.php",
        type: "POST",
        data: formData,
        success: function(response) {
          alert(response);
          $("#habitModal").modal("hide");
          loadHabits();
        },
        error: function() {
          alert("Error: Could not save habit!");
        }
      });
    });

    // Edit habit
    $(document).on("click", ".edithabit", function() {
      const id = $(this).data("id");
      $.ajax({
        url: "./habits/edit_habit.php",
        type: "GET",
        data: { id },
        dataType: "json",
        success: function(data) {
          if (data.status === "success") {
            const h = data.habit;
            $("#habit_id").val(h.id);
            $("#habit_name").val(h.habit_name);
            $("#frequency").val(h.frequency);
            $("#type").val(h.type);
            $("#notes").val(h.notes);
            $("#modalTitle").text('Edit Habit');
            $("#habitModal").modal("show");
          } else {
            alert("Habit not found!");
          }
        }
      });
    });

    // Delete habit
    $(document).on("click", ".deletehabit", function() {
      const id = $(this).data("id");
      if (confirm("Are you sure you want to delete this habit?")) {
        $.ajax({
          url: "./habits/delete_habit.php",
          type: "POST",
          data: { id },
          success: function(response) {
            alert(response);
            loadHabits();
          },
          error: function() {
            alert("Error deleting habit!");
          }
        });
      }
    });

    // Load habits and organize by frequency
    function loadHabits() {
      $.ajax({
        url: './habits/fetch_habits.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
          if (response.status === "success" && response.count > 0) {
            const dailyHabits = response.data.filter(h => h.frequency === 'Daily');
            const weeklyHabits = response.data.filter(h => h.frequency === 'Weekly');
            const monthlyHabits = response.data.filter(h => h.frequency === 'Monthly');

            let html = '';
            html += createFrequencySection('Daily', 'bi-calendar-day', 'daily', dailyHabits);
            html += createFrequencySection('Weekly', 'bi-calendar-week', 'weekly', weeklyHabits);
            html += createFrequencySection('Monthly', 'bi-calendar-month', 'monthly', monthlyHabits);

            $('#habitContainers').html(html);
          } else {
            $('#habitContainers').html(`
              <div class="empty-state">
                <i class="bi bi-clipboard-check"></i>
                <h4>No habits yet!</h4>
                <p>Start building better habits by adding your first one.</p>
              </div>
            `);
          }
        },
        error: function() {
          $('#habitContainers').html('<p class="text-danger text-center">Failed to load habits.</p>');
        }
      });
    }

    function createFrequencySection(frequencyName, iconClass, colorClass, habits) {
      if (habits.length === 0) {
        return `
          <div class="frequency-section">
            <div class="frequency-header ${colorClass}">
              <div class="frequency-icon ${colorClass}">
                <i class="${iconClass}"></i>
              </div>
              <div>
                <h4 class="frequency-title">${frequencyName} Habits</h4>
                <span class="habit-count">0 habits</span>
              </div>
            </div>
            <div class="empty-state">
              <i class="bi bi-inbox"></i>
              <p>No ${frequencyName.toLowerCase()} habits yet</p>
            </div>
          </div>
        `;
      }

      let habitCards = '';
      habits.forEach(habit => {
        habitCards += createHabitCard(habit);
      });

      return `
        <div class="frequency-section">
          <div class="frequency-header ${colorClass}">
            <div class="frequency-icon ${colorClass}">
              <i class="${iconClass}"></i>
            </div>
            <div>
              <h4 class="frequency-title">${frequencyName} Habits</h4>
              <span class="habit-count">${habits.length} ${habits.length === 1 ? 'habit' : 'habits'}</span>
            </div>
          </div>
          <div class="habits-grid">
            ${habitCards}
          </div>
        </div>
      `;
    }

    function createHabitCard(habit) {
      const typeClass = habit.type === "Good" ? "good" : "bad";
      const progressColor = habit.type === "Good" ? "bg-success" : "bg-danger";

      return `
        <div class="habit-card ${typeClass}">
          <div class="row align-items-center g-2">
            <div class="col-md-5">
              <div class="habit-name ${typeClass}">${habit.habit_name}</div>
              <div class="habit-meta">
                <span><i class="bi bi-tag-fill"></i> ${habit.type}</span>
              </div>
            </div>
            <div class="col-md-5">
              <div class="progress-container" data-id="${habit.id}" data-name="${habit.habit_name}" data-type="${habit.type}">
                <div class="progress">
                  <div class="progress-bar ${progressColor}" style="width: ${habit.progress}%"></div>
                </div>
                <small class="text-muted">${habit.progress}% ${habit.type === "Good" ? "complete" : "resistance"}</small>
              </div>
            </div>
            <div class="col-md-2 text-end">
              <div class="action-buttons justify-content-end">
                <button class="btn-action btn-edit edithabit" data-id="${habit.id}" title="Edit">
                  <i class="bi bi-pencil"></i>
                </button>
                <button class="btn-action btn-delete deletehabit" data-id="${habit.id}" title="Delete">
                  <i class="bi bi-trash"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      `;
    }

    // ========================================
    // POMODORO TIMER WITH MINI MODAL
    // ========================================

    let pomodoroInterval = null;
    let selectedMinutes = 25;
    let remainingTime = selectedMinutes * 60;
    let activeHabitId = null;
    let activeHabitName = null;
    let isPaused = false;

    // Format time as MM:SS
    function formatTime(seconds) {
      const mins = Math.floor(seconds / 60);
      const secs = seconds % 60;
      return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
    }

    // Update both main and mini timer displays
    function updateTimerDisplay() {
      const timeStr = formatTime(remainingTime);
      $("#pomodoroTimer").text(timeStr);
      $("#miniTimer").text(timeStr);
    }

    // Show mini floating modal
    function showMiniPomodoro() {
      $("#miniPomodoro").fadeIn(300);
      $("#miniHabitName").text(activeHabitName);
      updateTimerDisplay();
      
      if (isPaused) {
        $("#miniPomodoro").addClass("paused");
        $("#miniTimer").removeClass("active");
        $("#miniPause").html('<i class="bi bi-play-fill"></i> Resume');
      } else {
        $("#miniPomodoro").removeClass("paused");
        $("#miniTimer").addClass("active");
        $("#miniPause").html('<i class="bi bi-pause-fill"></i> Pause');
      }
    }

    // Hide mini floating modal
    function hideMiniPomodoro() {
      $("#miniPomodoro").fadeOut(300);
    }

    // Time selection buttons
    $(document).on("click", ".time-select", function() {
      if (!pomodoroInterval && !isPaused) {
        $(".time-select").removeClass("active");
        $(this).addClass("active");
        selectedMinutes = parseInt($(this).data("time"));
        remainingTime = selectedMinutes * 60;
        updateTimerDisplay();
      }
    });

    // Start Pomodoro timer
    function startPomodoroTimer() {
      $("#pomodoroSetup").hide();
      $("#startPomodoro").addClass("d-none");
      $("#pausePomodoro").removeClass("d-none").html('<i class="bi bi-pause-fill"></i> Pause');
      $("#stopPomodoro").removeClass("d-none");
      $("#pomodoroStatus").text("⏱️ Focus time! Stay concentrated...");
      $("#pomodoroTimer").addClass("pomodoro-active");

      isPaused = false;

      pomodoroInterval = setInterval(() => {
        remainingTime--;
        updateTimerDisplay();

        if (remainingTime <= 0) {
          finishPomodoro();
        }
      }, 1000);
    }

    // Pause/Resume Pomodoro timer
    function pauseResumePomodoro() {
      if (!isPaused) {
        // Pause the timer
        clearInterval(pomodoroInterval);
        pomodoroInterval = null;
        isPaused = true;

        $("#pausePomodoro").html('<i class="bi bi-play-fill"></i> Resume');
        $("#pomodoroStatus").text("⏸️ Timer paused");
        $("#pomodoroTimer").removeClass("pomodoro-active");
        
        // Update mini modal
        $("#miniPomodoro").addClass("paused");
        $("#miniTimer").removeClass("active");
        $("#miniPause").html('<i class="bi bi-play-fill"></i> Resume');
      } else {
        // Resume the timer
        isPaused = false;

        $("#pausePomodoro").html('<i class="bi bi-pause-fill"></i> Pause');
        $("#pomodoroStatus").text("⏱️ Focus time! Stay concentrated...");
        $("#pomodoroTimer").addClass("pomodoro-active");
        
        // Update mini modal
        $("#miniPomodoro").removeClass("paused");
        $("#miniTimer").addClass("active");
        $("#miniPause").html('<i class="bi bi-pause-fill"></i> Pause');

        pomodoroInterval = setInterval(() => {
          remainingTime--;
          updateTimerDisplay();

          if (remainingTime <= 0) {
            finishPomodoro();
          }
        }, 1000);
      }
    }

    // Stop Pomodoro timer
    function stopPomodoroTimer() {
      clearInterval(pomodoroInterval);
      pomodoroInterval = null;
      isPaused = false;

      $("#pomodoroSetup").show();
      $("#startPomodoro").removeClass("d-none");
      $("#pausePomodoro").addClass("d-none");
      $("#stopPomodoro").addClass("d-none");
      $("#pomodoroStatus").text("❌ Timer stopped");
      $("#pomodoroTimer").removeClass("pomodoro-active");

      // Reset to selected time
      remainingTime = selectedMinutes * 60;
      updateTimerDisplay();
      
      // Hide mini modal
      hideMiniPomodoro();
    }

    // Finish Pomodoro and update progress
    function finishPomodoro() {
      clearInterval(pomodoroInterval);
      pomodoroInterval = null;
      isPaused = false;
      remainingTime = 0;

      $("#pomodoroTimer").text("00:00").removeClass("pomodoro-active");
      $("#pomodoroStatus").text("🎉 Pomodoro complete! Updating progress...");
      $("#startPomodoro").addClass("d-none");
      $("#pausePomodoro").addClass("d-none");
      $("#stopPomodoro").addClass("d-none");

      // Hide mini modal
      hideMiniPomodoro();

      $.ajax({
        url: "./habits/update_progress.php",
        type: "POST",
        data: { id: activeHabitId },
        dataType: "json",
        success: function(response) {
          if (response.status === "success") {
            loadHabits();
            $("#pomodoroStatus").text("✅ Progress updated! Great work!");

            setTimeout(() => {
              $("#pomodoroModal").modal("hide");
            }, 2000);
          } else {
            $("#pomodoroStatus").text("⚠️ " + response.message);
          }
        },
        error: function() {
          $("#pomodoroStatus").text("❌ Error updating progress!");
        }
      });
    }

    // Click on progress bar - open appropriate modal
    $(document).on("click", ".progress-container", function() {
      activeHabitId = $(this).data("id");
      activeHabitName = $(this).data("name");
      const habitType = $(this).data("type");

      if (habitType === "Good") {
        // Reset to default state
        selectedMinutes = 25;
        remainingTime = selectedMinutes * 60;
        $(".time-select").removeClass("active");
        $(".time-select[data-time='25']").addClass("active");

        $("#pomodoroHabitName").text(activeHabitName);
        updateTimerDisplay();
        $("#pomodoroTimer").removeClass("pomodoro-active");
        $("#pomodoroStatus").text("Select time and click Start");
        $("#pomodoroSetup").show();
        $("#startPomodoro").removeClass("d-none");
        $("#pausePomodoro").addClass("d-none");
        $("#stopPomodoro").addClass("d-none");
        $("#pomodoroModal").modal("show");
      } else {
        // Bad habits - break streak modal
        $("#breakHabitName").text(activeHabitName);
        $("#breakStreakModal").modal("show");
      }
    });

    // Button event handlers - Main Modal
    $("#startPomodoro").on("click", startPomodoroTimer);
    $("#pausePomodoro").on("click", pauseResumePomodoro);
    $("#stopPomodoro").on("click", stopPomodoroTimer);

    // Button event handlers - Mini Modal
    $("#miniPause").on("click", pauseResumePomodoro);
    $("#miniStop").on("click", stopPomodoroTimer);

    // Click mini modal to reopen main modal
    $("#miniPomodoro").on("click", function(e) {
      // Don't open if clicking buttons
      if ($(e.target).closest('.mini-btn, .mini-pomodoro-close').length === 0) {
        $("#pomodoroModal").modal("show");
      }
    });

    // Close mini modal button (X)
    $("#closeMiniPomodoro").on("click", function(e) {
      e.stopPropagation();
      if (confirm("Do you want to stop the Pomodoro timer?")) {
        stopPomodoroTimer();
      }
    });

    // When main modal closes, show mini modal if timer is running
    $("#pomodoroModal").on("hidden.bs.modal", function() {
      if (pomodoroInterval || isPaused) {
        showMiniPomodoro();
      } else {
        // Only reset if timer is completely stopped
        selectedMinutes = 25;
        remainingTime = selectedMinutes * 60;
        isPaused = false;
        hideMiniPomodoro();
      }
    });

    // When main modal opens, hide mini modal
    $("#pomodoroModal").on("shown.bs.modal", function() {
      hideMiniPomodoro();
    });

    // Confirm breaking bad habit streak
    $("#confirmBreakStreak").on("click", function() {
      $.ajax({
        url: "./habits/update_progress.php",
        type: "POST",
        data: { id: activeHabitId },
        dataType: "json",
        success: function(response) {
          if (response.status === "success") {
            loadHabits();
            alert("✅ Great job resisting! Your streak is growing!");
            $("#breakStreakModal").modal("hide");
          } else {
            alert("⚠️ " + response.message);
          }
        },
        error: function() {
          alert("❌ Error updating progress!");
        }
      });
    });
  });
</script>





</body>