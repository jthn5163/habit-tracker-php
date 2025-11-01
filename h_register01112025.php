<?php
include('./db.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | MyApp</title>
  <link rel="stylesheet" href="./assets/styles/style.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

  <div class="container">
    <h2>Register</h2>

    <div class="error" id="registerError">Please fill all fields correctly!</div>

    <div class="input-group">
      <label>Full Name</label>
      <input type="text" id="name" placeholder="Enter your name" />
    </div>

    <div class="input-group">
      <label>Email</label>
      <input type="email" id="email" placeholder="Enter your email" />
    </div>

    <div class="input-group">
      <label>Password</label>
      <input type="password" id="password" placeholder="Create a password" />
    </div>

    <div class="input-group">
      <label>Confirm Password</label>
      <input type="password" id="confirmPassword" placeholder="Re-enter password" />
    </div>

    <button id="registerBtn">Register</button>

    <div class="bottom-text">
      Already have an account? <a href="index.php">Login</a>
    </div>
  </div>


  <script>
    $(document).ready(function() {
      $("#registerBtn").click(function() {
        const name = $("#name").val().trim();
        const email = $("#email").val().trim();
        const password = $("#password").val().trim();
        const confirmPassword = $("#confirmPassword").val().trim();

        if (name === "" || email === "" || password === "" || confirmPassword === "") {
          $("#registerError").text("Please fill all fields!").fadeIn().delay(2000).fadeOut();
          return;
        }
        if (password !== confirmPassword) {
          $("#registerError").text("Passwords do not match!").fadeIn().delay(2000).fadeOut();
          return;
        }

        // Send data to backend using AJAX
        $.ajax({
          url: "register_action.php",
          type: "POST",
          data: {
            name,
            email,
            password
          },
          dataType: "json",
          success: function(response) {
            if (response.status === "success") {
              alert(response.message);
              window.location.href = "index.php"; // Redirect to login page
            } else {
              $("#registerError").text(response.message).fadeIn().delay(3000).fadeOut();
            }
          },
          error: function() {
            $("#registerError").text("Something went wrong. Try again.").fadeIn().delay(3000).fadeOut();
          }
        });
      });
    });
  </script>



</body>

</html>