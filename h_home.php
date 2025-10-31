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
  <title>Hobby Tracker</title>

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

    <button class="btn btn-custom mb-3" data-bs-toggle="modal" data-bs-target="#hobbyModal">+ Add Hobby</button>
    <div id="hobbyList" class="table-responsive text-center text-muted">
      <p>Loading hobbies...</p>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="hobbyModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add / Edit Hobby</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form id="hobbyForm">
            <input type="hidden" id="hobby_id">
            <div class="mb-3">
              <label>Hobby Name</label>
              <input type="text" id="hobby_name" class="form-control" required>
            </div>
            <div class="mb-3">
              <label>Category</label>
              <select id="category" class="form-select">
                <option>Fitness</option>
                <option>Music</option>
                <option>Reading</option>
                <option>Travel</option>
                <option>Cooking</option>
              </select>
            </div>
            <div class="mb-3">
              <label>Frequency</label>
              <select id="frequency" class="form-select">
                <option>Daily</option>
                <option>Weekly</option>
                <option>Monthly</option>
              </select>
            </div>
            <div class="mb-3">
              <label>Notes</label>
              <textarea id="notes" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-custom w-100">Save Hobby</button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
$(document).ready(function() {

  // Load hobbies when page opens
  loadHobbies();

  // Handle Add / Edit form submission via AJAX
  $("#hobbyForm").on("submit", function(e) {
    e.preventDefault();

    const formData = {
      id: $("#hobby_id").val(),
      hobby_name: $("#hobby_name").val().trim(),
      category: $("#category").val(),
      frequency: $("#frequency").val(),
      notes: $("#notes").val().trim()
    };

    if (formData.hobby_name === "") {
      alert("Please enter a hobby name!");
      return;
    }

    $.ajax({
      url: "./hobbies/add_hobby.php", // your backend PHP file
      type: "POST",
      data: formData,
      success: function(response) {
        alert(response); // ✅ show success or error message
        $("#hobbyModal").modal("hide");
        $("#hobbyForm")[0].reset();
        loadHobbies(); // reload table
      },
      error: function() {
        alert("Error: Could not save hobby!");
      }
    });
  });

  // Edit hobby
  $(document).on("click", ".editHobby", function() {
    const id = $(this).data("id");
    $.ajax({
      url: "./hobbies/edit_hobby.php",
      type: "GET",
      data: { id },
      dataType: "json",
      success: function(data) {
        if (data.status === "success") {
          const h = data.hobby;
          $("#hobby_id").val(h.id);
          $("#hobby_name").val(h.hobby_name);
          $("#category").val(h.category);
          $("#frequency").val(h.frequency);
          $("#notes").val(h.notes);
          $("#hobbyModal").modal("show");
        } else {
          alert("Hobby not found!");
        }
      }
    });
  });

  // Delete hobby
  $(document).on("click", ".deleteHobby", function() {
    const id = $(this).data("id");
    if (confirm("Are you sure you want to delete this hobby?")) {
      $.ajax({
        url: "./hobbies/delete_hobby.php",
        type: "POST",
        data: { id },
        success: function(response) {
          alert(response);
          loadHobbies();
        },
        error: function() {
          alert("Error deleting hobby!");
        }
      });
    }
  });

});
</script>


  <script>
    function loadHobbies() {
      $.ajax({
        url: './hobbies/fetch_hobbies.php',
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
                <th>Hobby</th>
                <th>Category</th>
                <th>Frequency</th>
                <th>Progress</th>
                <th>Notes</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
        `;

            response.data.forEach(hobby => {
              tableHTML += `
            <tr>
              <td>${hobby.hobby_name}</td>
              <td>${hobby.category}</td>
              <td>${hobby.frequency}</td>
              <td>
                <div class='progress'>
                  <div class='progress-bar bg-success' style='width: ${hobby.progress}%'></div>
                </div>
                <small>${hobby.progress}%</small>
              </td>
              <td>${hobby.notes || ''}</td>
              <td>
                <button class='btn btn-sm btn-light editHobby' data-id='${hobby.id}' title='Edit'>
                  <i class='bi bi-pencil-square text-primary'></i>
                </button>
                <button class='btn btn-sm btn-light deleteHobby' data-id='${hobby.id}' title='Delete'>
                  <i class='bi bi-trash text-danger'></i>
                </button>
              </td>
            </tr>`;
            });

            tableHTML += '</tbody></table>';
            $('#hobbyList').html(tableHTML);
          } else {
            $('#hobbyList').html("<p class='text-muted'>No hobbies added yet. Start by adding one!</p>");
          }
        },
        error: function() {
          $('#hobbyList').html('<p class="text-danger">Failed to load hobbies.</p>');
        }
      });
    }
  </script>


</body>

</html>