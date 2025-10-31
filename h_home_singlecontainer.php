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
      max-width: 1000px;
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

    .habit-card {
      background: #fff;
      border-radius: 15px;
      padding: 20px;
      margin-bottom: 15px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
      transition: all 0.3s;
      border-left: 5px solid transparent;
    }

    .habit-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .habit-card.good {
      border-left-color: #28a745;
    }

    .habit-card.bad {
      border-left-color: #dc3545;
    }

    .habit-name {
      font-size: 1.2rem;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .habit-name.good {
      color: #28a745;
    }

    .habit-name.bad {
      color: #dc3545;
    }

    .progress-container {
      cursor: pointer;
      padding: 5px;
      border-radius: 8px;
      transition: all 0.3s;
    }

    .progress-container:hover {
      background: #f8f9fa;
    }

    .progress {
      height: 12px;
      border-radius: 10px;
      background: #e9ecef;
      overflow: visible;
    }

    .progress-bar {
      border-radius: 10px;
      transition: width 0.6s ease;
    }

    .habit-meta {
      display: flex;
      gap: 15px;
      margin-top: 10px;
      font-size: 0.9rem;
      color: #6c757d;
    }

    .habit-meta span {
      display: flex;
      align-items: center;
      gap: 5px;
    }

    .action-buttons {
      display: flex;
      gap: 10px;
    }

    .btn-action {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      transition: all 0.3s;
      font-size: 1.1rem;
    }

    .btn-action:hover {
      transform: scale(1.1);
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
      padding: 60px 20px;
      color: #6c757d;
    }

    .empty-state i {
      font-size: 4rem;
      margin-bottom: 20px;
      opacity: 0.3;
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

    .badge-frequency {
      background: #667eea;
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
    }

    @keyframes pulse {
      0%, 100% {
        opacity: 1;
      }
      50% {
        opacity: 0.5;
      }
    }

    .pomodoro-active {
      animation: pulse 2s infinite;
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

    <div id="habitList">
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

  <!-- Pomodoro Modal -->
  <div class="modal fade" id="pomodoroModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header justify-content-center">
          <h5 class="modal-title">⏱️ Pomodoro Timer</h5>
          <button type="button" class="btn-close position-absolute end-0 me-3" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-5">
          <h4 id="pomodoroHabitName" class="pomodoro-habit-name mb-4"></h4>
          <h2 id="pomodoroTimer" class="pomodoro-timer mb-3">25:00</h2>
          <p id="pomodoroStatus" class="text-muted mb-4"></p>
          <div class="d-flex gap-3 justify-content-center">
            <button id="startPomodoro" class="btn btn-success btn-lg px-5">
              <i class="bi bi-play-fill"></i> Start
            </button>
            <button id="stopPomodoro" class="btn btn-danger btn-lg px-5 d-none">
              <i class="bi bi-stop-fill"></i> Stop
            </button>
          </div>
        </div>
      </div>
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

      // Load habits
      function loadHabits() {
        $.ajax({
          url: './habits/fetch_habits.php',
          method: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response.status === "success" && response.count > 0) {
              let html = '';

              response.data.forEach(habit => {
                const typeClass = habit.type === "Good" ? "good" : "bad";
                const progressColor = habit.type === "Good" ? "bg-success" : "bg-danger";

                html += `
                  <div class="habit-card ${typeClass}">
                    <div class="row align-items-center">
                      <div class="col-md-6">
                        <div class="habit-name ${typeClass}">${habit.habit_name}</div>
                        <div class="habit-meta">
                          <span><i class="bi bi-calendar3"></i> ${habit.frequency}</span>
                          <span><i class="bi bi-tag"></i> ${habit.type}</span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="progress-container" data-id="${habit.id}" data-name="${habit.habit_name}">
                          <div class="progress">
                            <div class="progress-bar ${progressColor}" style="width: ${habit.progress}%"></div>
                          </div>
                          <small class="text-muted">${habit.progress}% complete</small>
                        </div>
                      </div>
                      <div class="col-md-2 text-end">
                        <div class="action-buttons">
                          <button class="btn-action btn-edit edithabit" data-id="${habit.id}" title="Edit">
                            <i class="bi bi-pencil"></i>
                          </button>
                          <button class="btn-action btn-delete deletehabit" data-id="${habit.id}" title="Delete">
                            <i class="bi bi-trash"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>`;
              });

              $('#habitList').html(html);
            } else {
              $('#habitList').html(`
                <div class="empty-state">
                  <i class="bi bi-clipboard-check"></i>
                  <h4>No habits yet!</h4>
                  <p>Start building better habits by adding your first one.</p>
                </div>
              `);
            }
          },
          error: function() {
            $('#habitList').html('<p class="text-danger text-center">Failed to load habits.</p>');
          }
        });
      }

      // Pomodoro functionality (using in-memory state)
      let pomodoroInterval;
      let remainingTime = 25 * 60;
      let activeHabitId = null;
      let activeHabitName = null;

      function formatTime(seconds) {
        const mins = Math.floor(seconds / 60);
        const secs = seconds % 60;
        return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
      }

      function startPomodoroTimer() {
        $("#startPomodoro").addClass("d-none");
        $("#stopPomodoro").removeClass("d-none");
        $("#pomodoroStatus").text("Focus time! Stay concentrated...");
        $("#pomodoroTimer").addClass("pomodoro-active");

        pomodoroInterval = setInterval(() => {
          remainingTime--;
          $("#pomodoroTimer").text(formatTime(remainingTime));

          if (remainingTime <= 0) {
            finishPomodoro();
          }
        }, 1000);
      }

      function stopPomodoroTimer() {
        clearInterval(pomodoroInterval);
        pomodoroInterval = null;
        $("#startPomodoro").removeClass("d-none");
        $("#stopPomodoro").addClass("d-none");
        $("#pomodoroStatus").text("Timer paused");
        $("#pomodoroTimer").removeClass("pomodoro-active");
      }

      function finishPomodoro() {
        clearInterval(pomodoroInterval);
        pomodoroInterval = null;
        remainingTime = 0;
        $("#pomodoroTimer").text("00:00").removeClass("pomodoro-active");
        $("#pomodoroStatus").text("🎉 Pomodoro complete! Updating progress...");
        $("#startPomodoro").addClass("d-none");
        $("#stopPomodoro").addClass("d-none");

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

      // Start pomodoro on progress click
      $(document).on("click", ".progress-container", function() {
        activeHabitId = $(this).data("id");
        activeHabitName = $(this).data("name");
        remainingTime = 25 * 60;

        $("#pomodoroHabitName").text(activeHabitName);
        $("#pomodoroTimer").text(formatTime(remainingTime)).removeClass("pomodoro-active");
        $("#pomodoroStatus").text("Click Start to begin your focus session");
        $("#startPomodoro").removeClass("d-none");
        $("#stopPomodoro").addClass("d-none");
        $("#pomodoroModal").modal("show");
      });

      $("#startPomodoro").on("click", startPomodoroTimer);
      $("#stopPomodoro").on("click", stopPomodoroTimer);

      // Reset timer when modal closes
      $("#pomodoroModal").on("hidden.bs.modal", function() {
        stopPomodoroTimer();
        remainingTime = 25 * 60;
      });
    });
  </script>
</body>

</html>