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
  <title>habit Tracker</title>

  <!-- Bootstrap + Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="../asset/style/style.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <style>
    body {
      /* background: #f7f9fb; */
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
    }

    .table th {
      background: #f0f4f8;
    }
  </style>
</head>

<body>
  <div class="container">
    <h3>🎯 Welcome, <?= htmlspecialchars($user['name']); ?>
      <a href="logout.php" class="logout"><i class="bi bi-box-arrow-right"></i></a>
    </h3>
    <hr>

    <button class="btn btn-custom mb-3" data-bs-toggle="modal" data-bs-target="#habitModal">+ Add habit</button>
    <div id="habitList" class="table-responsive text-center text-muted">
      <p>Loading habits...</p>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="habitModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add / Edit habit</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="habitForm">
            <input type="hidden" id="habit_id">
            <div class="mb-3">
              <label>habit Name</label>
              <input type="text" id="habit_name" class="form-control" required>
            </div>

            <div class="mb-3">
              <label>Frequency</label>
              <select id="frequency" class="form-select">
                <option value="" disabled selected>Select frequency</option> 
                <option>Daily</option>
                <option>Weekly</option>
                <option>Monthly</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Type</label>
              <select id="type" class="form-select">
                <option value="" disabled selected>Select type</option> 
                <option>Good</option>
                <option>Bad</option>
              </select>
            </div>

            <div class="mb-3">
              <label>Notes</label>
              <textarea id="notes" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-custom w-100">Save habit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {

      // Load habits when page opens
      loadhabits();

      // Handle Add / Edit form submission via AJAX
      $("#habitForm").on("submit", function(e) {
        e.preventDefault();

        const formData = {
          id: $("#habit_id").val(),
          habit_name: $("#habit_name").val().trim(),
          frequency: $("#frequency").val(),
          type: $("#type").val(),
          notes: $("#notes").val().trim()
        };

        if (formData.habit_name === "") {
          alert("Please enter a habit name!");
          return;
        }

        $.ajax({
          url: "./habits/add_habit.php", // your backend PHP file
          type: "POST",
          data: formData,
          success: function(response) {
            alert(response); // ✅ show success or error message
            $("#habitModal").modal("hide");
            $("#habitForm")[0].reset();
            loadhabits(); // reload table
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
              $("#category").val(h.category);
              $("#frequency").val(h.frequency);
              $("#notes").val(h.notes);
              $("#habitModal").modal("show");
            } else {
              alert("habit not found!");
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
              loadhabits();
            },
            error: function() {
              alert("Error deleting habit!");
            }
          });
        }
      });

    });
  </script>


  <script>
    function loadhabits() {
      $.ajax({
        url: './habits/fetch_habits.php',
        method: 'GET',
        dataType: 'json',
        success: function(response) {

          console.log(response);
          debugger;

          if (response.status === "success" && response.count > 0) {
            let tableHTML = `
          <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
              <tr>
                <th>habit</th>
                <th>Category</th>
                <th>Frequency</th>
                <th>Progress</th>
                <th>Notes</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
        `;

            response.data.forEach(habit => {
              tableHTML += `
            <tr>
              <td>${habit.habit_name}</td>
              <td>${habit.category}</td>
              <td>${habit.frequency}</td>
              <td>
                <div class='progress'>
                  <div class='progress-bar bg-success' style='width: ${habit.progress}%'></div>
                </div>
                <small>${habit.progress}%</small>
              </td>
              <td>${habit.notes || ''}</td>
              <td>
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
  </script>


</body>

</html>