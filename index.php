<?php 
include('./db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login | MyApp</title>
  <link rel="stylesheet" href="style.css" />
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="./assets/styles/style.css">
</head>
<body>

  <div class="container">
    <h2>Login</h2>

    <div class="error" id="loginError">Please fill all fields correctly!</div>

    <div class="input-group">
      <label>Email</label>
      <input type="email" id="loginEmail" placeholder="Enter your email" />
    </div>

    <div class="input-group">
      <label>Password</label>
      <input type="password" id="loginPassword" placeholder="Enter your password" />
    </div>

    <button id="loginBtn">Login</button>

    <div class="bottom-text">
      Don't have an account? <a href="h_register.php">Register</a>
    </div>
  </div>

   <script>
  $(document).ready(function() {
    $("#loginBtn").click(function() {
      const email = $("#loginEmail").val().trim();
      const password = $("#loginPassword").val().trim();

      if (email === "" || password === "") {
        $("#loginError").text("Please fill all fields!").fadeIn().delay(2000).fadeOut();
        return;
      }

      $.ajax({
        url: "login_action.php",
        type: "POST",
        data: { email, password },
        dataType: "json",
        success: function(response) {
          if (response.status === "success") {
            alert(response.message);
            window.location.href = "h_home.php"; // redirect to dashboard
          } else {
            $("#loginError").text(response.message).fadeIn().delay(3000).fadeOut();
          }
        },
        error: function() {
          $("#loginError").text("Server error. Try again.").fadeIn().delay(3000).fadeOut();
        }
      });
    });
  });
</script>



</body>
</html>
