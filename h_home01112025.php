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
      background: linear-gradient(135deg, #007bff, #00d4ff);
      font-family: 'Poppins', sans-serif;
      background-position: center;
      background-repeat: no-repeat;
      height: 100vh;
    }

    .container {
      margin-top: 60px;
      max-width: 900px;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .btn-custom {
      background: linear-gradient(45deg, #36d1dc, #5b86e5);
      color: #fff;
      border-radius: 25px;
      border: none;
    }

    .btn-custom:hover {
      transform: scale(1.05);
    }

    .logout {
      float: right;
      color: #d9534f;
      text-decoration: none;
      font-size: 1.2rem;
    }

    .logout:hover {
      color: #b52b27;
    }

    .table th {
      background: #f0f4f8;
    }
  </style>
</head>

<body>
  <div class="container">
    <h3>🎯 Welcome, <?= htmlspecialchars($user['name']); ?>
      <a href="logout.php" class="logout" title="Logout">
        <i class="bi bi-box-arrow-right"></i>
      </a>
    </h3>
    <hr>

    <button class="btn btn-custom mb-3" data-bs-toggle="modal" data-bs-target="#habitModal">
      <i class="bi bi-plus-circle"></i> Add Habit
    </button>

    <div id="habitList" class="table-responsive text-center text-muted">
      <p>Loading habits...</p>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="habitModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add / Edit Habit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="habitForm">
            <input type="hidden" id="habit_id">

            <div class="mb-3">
              <label>Habit Name</label>
              <input type="text" id="habit_name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Frequency</label>
              <select id="frequency" class="form-select" required>
                <option value="" disabled selected>Select frequency</option>
                <option>Daily</option>
                <option>Weekly</option>
                <option>Monthly</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Type</label>
              <select id="type" class="form-select" required>
                <option value="" disabled selected>Select type</option>
                <option>Good</option>
                <option>Bad</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Notes</label>
              <textarea id="notes" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-custom w-100">Save Habit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Pomodoro Modal -->
  <div class="modal fade" id="pomodoroModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content text-center">
        <div class="modal-header">
          <h5 class="modal-title">Pomodoro Timer</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <h4 id="pomodoroHabitName" class="mb-3 text-primary"></h4>
          <h2 id="pomodoroTimer" class="display-4 fw-bold">25:00</h2>
          <p id="pomodoroStatus" class="text-muted"></p>
          <button id="startPomodoro" class="btn btn-success">Start</button>
          <button id="stopPomodoro" class="btn btn-danger d-none">Stop</button>
        </div>
      </div>
    </div>
  </div>


  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      loadHabits();

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

        if (!formData.habit_name) {
          alert("Please enter a habit name!");
          return;
        }

        $.ajax({
          url: "./habits/add_habit.php",
          type: "POST",
          data: formData,
          success: function(response) {
            alert(response);
            $("#habitModal").modal("hide");
            $("#habitForm")[0].reset();
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
          data: {
            id
          },
          dataType: "json",
          success: function(data) {
            if (data.status === "success") {
              const h = data.habit;
              $("#habit_id").val(h.id);
              $("#habit_name").val(h.habit_name);
              $("#frequency").val(h.frequency);
              $("#type").val(h.type);
              $("#notes").val(h.notes);
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
            data: {
              id
            },
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
              let tableHTML = `
          <table class="table table-borderless align-middle">
            <tbody>
        `;

              let count = 1;
              response.data.forEach(habit => {
                // Set color for Good/Bad
                const habitColor = habit.type === "Good" ? "text-success fw-semibold" : "text-danger fw-semibold";
                // Set progress bar color
                const progressColor = habit.type === "Good" ? "bg-success" : "bg-danger";

                tableHTML += `
            <tr class="shadow-sm rounded-3">
              <td style="width: 5%;"><strong>${count++}.</strong></td>
              <td class="${habitColor}" style="width: 25%;">${habit.habit_name}</td>
              <td style="width: 35%;">

                
                <div class='progress pomodoro-trigger' data-id='${habit.id}' style="height: 10px; cursor: pointer;">
                  <div class='progress-bar ${progressColor}' style='width: ${habit.progress}%;'></div>
                </div>


                <small>${habit.progress}%</small>
              </td>
              <td style="width: 20%;">
                <button class='btn btn-sm btn-light edithabit' data-id='${habit.id}' title='Edit'>
                  <i class='bi bi-pencil-square text-primary'></i>
                </button>
                <button class='btn btn-sm btn-light deletehabit' data-id='${habit.id}' title='Delete'>
                  <i class='bi bi-trash text-danger'></i>
                </button>
              </td>
            </tr>`;
              });

              tableHTML += '</tbody></table>';
              $('#habitList').html(tableHTML);
            } else {
              $('#habitList').html("<p class='text-muted'>No habits added yet. Start by adding one!</p>");
            }
          },
          error: function() {
            $('#habitList').html('<p class="text-danger">Failed to load habits.</p>');
          }
        });
      }
     

      let pomodoroInterval;
let remainingTime = 25 * 60; // 25 minutes in seconds
let activeHabitId = null;
let activeHabitName = null;

function formatTime(seconds) {
  const mins = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${String(mins).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

function savePomodoroState() {
  const state = {
    remainingTime,
    activeHabitId,
    activeHabitName,
    isRunning: !!pomodoroInterval,
    lastUpdated: Date.now()
  };
  localStorage.setItem("pomodoroState", JSON.stringify(state));
}

function loadPomodoroState() {
  const saved = localStorage.getItem("pomodoroState");
  if (!saved) return;

  const state = JSON.parse(saved);
  remainingTime = state.remainingTime;
  activeHabitId = state.activeHabitId;
  activeHabitName = state.activeHabitName;

  // Adjust for time passed if browser closed or refreshed
  const elapsed = Math.floor((Date.now() - state.lastUpdated) / 1000);
  if (state.isRunning) {
    remainingTime -= elapsed;
    if (remainingTime <= 0) {
      remainingTime = 0;
      finishPomodoro();
    } else {
      startPomodoroTimer();
    }
  }

  if (activeHabitId) {
    $("#pomodoroHabitName").text(activeHabitName);
    $("#pomodoroTimer").text(formatTime(remainingTime));
  }
}

function startPomodoroTimer() {
  $("#startPomodoro").addClass("d-none");
  $("#stopPomodoro").removeClass("d-none");
  $("#pomodoroStatus").text("Pomodoro in progress...");

  pomodoroInterval = setInterval(() => {
    remainingTime--;
    $("#pomodoroTimer").text(formatTime(remainingTime));
    savePomodoroState();

    if (remainingTime <= 0) {
      finishPomodoro();
    }
  }, 1000);

  savePomodoroState();
}

function stopPomodoroTimer() {
  clearInterval(pomodoroInterval);
  pomodoroInterval = null;
  $("#startPomodoro").removeClass("d-none");
  $("#stopPomodoro").addClass("d-none");
  $("#pomodoroStatus").text("Pomodoro paused");
  savePomodoroState();
}

function finishPomodoro() {
  clearInterval(pomodoroInterval);
  pomodoroInterval = null;
  remainingTime = 0;
  $("#pomodoroTimer").text("00:00");
  $("#pomodoroStatus").text("✅ Pomodoro complete!");
  localStorage.removeItem("pomodoroState");

  // Update backend
  $.ajax({
    url: "./habits/update_progress.php",
    type: "POST",
    data: { id: activeHabitId },
    dataType: "json",
    success: function (response) {
      if (response.status === "success") {
        loadHabits();
        $("#pomodoroStatus").text("✅ Pomodoro complete! Progress updated.");
      } else {
        $("#pomodoroStatus").text("⚠️ " + response.message);
      }
    },
    error: function () {
      $("#pomodoroStatus").text("❌ Error updating progress!");
    }
  });
}

// Event bindings
$(document).on("click", ".progress-bar", function () {
  const row = $(this).closest("tr");
  activeHabitId = row.find(".edithabit").data("id");
  activeHabitName = row.find("td:nth-child(2)").text();

  $("#pomodoroHabitName").text(activeHabitName);
  $("#pomodoroTimer").text(formatTime(remainingTime));
  $("#pomodoroModal").modal("show");
  savePomodoroState();
});

$("#startPomodoro").on("click", startPomodoroTimer);
$("#stopPomodoro").on("click", stopPomodoroTimer);

// Load saved state on page load
loadPomodoroState();




    });
  </script>
</body>

</html>