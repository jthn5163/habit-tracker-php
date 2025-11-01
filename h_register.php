<?php
include('./db.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register | Habit Tracker</title>
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .container {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      padding: 50px 40px;
      width: 100%;
      max-width: 420px;
      animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .logo-section {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border-radius: 20px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: white;
      margin-bottom: 15px;
      box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
    }

    h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #333;
      margin-bottom: 10px;
      text-align: center;
    }

    .subtitle {
      text-align: center;
      color: #6c757d;
      font-size: 0.95rem;
      margin-bottom: 30px;
    }

    .error {
      background: #ffebee;
      color: #c62828;
      padding: 12px 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      display: none;
      font-size: 0.9rem;
      border-left: 4px solid #c62828;
      animation: shake 0.5s;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
      20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .input-group {
      margin-bottom: 20px;
      position: relative;
    }

    .input-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: #495057;
      font-size: 0.9rem;
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      font-size: 1.1rem;
    }

    .input-group input {
      width: 100%;
      padding: 12px 15px 12px 45px;
      border: 2px solid #e0e0e0;
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: #fff;
    }

    .input-group input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .input-group input::placeholder {
      color: #adb5bd;
    }

    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #6c757d;
      cursor: pointer;
      font-size: 1.1rem;
      transition: color 0.3s;
    }

    .password-toggle:hover {
      color: #495057;
    }

    .password-strength {
      margin-top: 8px;
      font-size: 0.85rem;
      display: none;
    }

    .password-strength.weak {
      color: #dc3545;
    }

    .password-strength.medium {
      color: #ffc107;
    }

    .password-strength.strong {
      color: #28a745;
    }

    button {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
      margin-top: 10px;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
    }

    button:active {
      transform: translateY(0);
    }

    button:disabled {
      opacity: 0.6;
      cursor: not-allowed;
      transform: none;
    }

    .bottom-text {
      text-align: center;
      margin-top: 25px;
      color: #6c757d;
      font-size: 0.95rem;
    }

    .bottom-text a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s;
    }

    .bottom-text a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    .loader {
      display: none;
      width: 20px;
      height: 20px;
      border: 3px solid rgba(255, 255, 255, 0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      margin: 0 auto;
    }

    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    @media (max-width: 480px) {
      .container {
        padding: 40px 25px;
      }

      h2 {
        font-size: 1.5rem;
      }

      .logo-icon {
        width: 70px;
        height: 70px;
        font-size: 2rem;
      }
    }
  </style>
</head>

<body>

  <div class="container">
    <div class="logo-section">
      <div class="logo-icon">
        <i class="bi bi-person-plus"></i>
      </div>
      <h2>Create Account</h2>
      <p class="subtitle">Join us to start tracking your habits</p>
    </div>

    <div class="error" id="registerError">
      <i class="bi bi-exclamation-circle"></i> Please fill all fields correctly!
    </div>

    <div class="input-group">
      <label>Full Name</label>
      <div class="input-wrapper">
        <i class="bi bi-person input-icon"></i>
        <input type="text" id="name" placeholder="John Doe" />
      </div>
    </div>

    <div class="input-group">
      <label>Email Address</label>
      <div class="input-wrapper">
        <i class="bi bi-envelope input-icon"></i>
        <input type="email" id="email" placeholder="you@example.com" />
      </div>
    </div>

    <div class="input-group">
      <label>Password</label>
      <div class="input-wrapper">
        <i class="bi bi-lock input-icon"></i>
        <input type="password" id="password" placeholder="Create a strong password" />
        <i class="bi bi-eye password-toggle" id="togglePassword"></i>
      </div>
      <div class="password-strength" id="passwordStrength"></div>
    </div>

    <div class="input-group">
      <label>Confirm Password</label>
      <div class="input-wrapper">
        <i class="bi bi-lock-fill input-icon"></i>
        <input type="password" id="confirmPassword" placeholder="Re-enter your password" />
        <i class="bi bi-eye password-toggle" id="toggleConfirmPassword"></i>
      </div>
    </div>

    <button id="registerBtn">
      <span id="btnText">Create Account</span>
      <div class="loader" id="btnLoader"></div>
    </button>

    <div class="bottom-text">
      Already have an account? <a href="index.php">Login</a>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      // Password toggle for password field
      $("#togglePassword").click(function() {
        const passwordInput = $("#password");
        const type = passwordInput.attr("type") === "password" ? "text" : "password";
        passwordInput.attr("type", type);
        $(this).toggleClass("bi-eye bi-eye-slash");
      });

      // Password toggle for confirm password field
      $("#toggleConfirmPassword").click(function() {
        const confirmPasswordInput = $("#confirmPassword");
        const type = confirmPasswordInput.attr("type") === "password" ? "text" : "password";
        confirmPasswordInput.attr("type", type);
        $(this).toggleClass("bi-eye bi-eye-slash");
      });

      // Password strength checker
      $("#password").on("input", function() {
        const password = $(this).val();
        const strengthDiv = $("#passwordStrength");

        if (password.length === 0) {
          strengthDiv.hide();
          return;
        }

        strengthDiv.show();

        if (password.length < 6) {
          strengthDiv.removeClass().addClass("password-strength weak").html('<i class="bi bi-x-circle"></i> Weak password (min 6 characters)');
        } else if (password.length < 10) {
          strengthDiv.removeClass().addClass("password-strength medium").html('<i class="bi bi-dash-circle"></i> Medium strength');
        } else {
          strengthDiv.removeClass().addClass("password-strength strong").html('<i class="bi bi-check-circle"></i> Strong password');
        }
      });

      // Register button click
      $("#registerBtn").click(function() {
        const name = $("#name").val().trim();
        const email = $("#email").val().trim();
        const password = $("#password").val().trim();
        const confirmPassword = $("#confirmPassword").val().trim();

        // Validation
        if (name === "" || email === "" || password === "" || confirmPassword === "") {
          $("#registerError").html('<i class="bi bi-exclamation-circle"></i> Please fill all fields!').fadeIn().delay(2500).fadeOut();
          return;
        }

        // Name validation
        if (name.length < 3) {
          $("#registerError").html('<i class="bi bi-exclamation-circle"></i> Name must be at least 3 characters!').fadeIn().delay(2500).fadeOut();
          return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
          $("#registerError").html('<i class="bi bi-exclamation-circle"></i> Please enter a valid email address!').fadeIn().delay(2500).fadeOut();
          return;
        }

        // Password length validation
        if (password.length < 6) {
          $("#registerError").html('<i class="bi bi-exclamation-circle"></i> Password must be at least 6 characters!').fadeIn().delay(2500).fadeOut();
          return;
        }

        // Password match validation
        if (password !== confirmPassword) {
          $("#registerError").html('<i class="bi bi-exclamation-circle"></i> Passwords do not match!').fadeIn().delay(2500).fadeOut();
          return;
        }

        // Show loader
        $("#btnText").hide();
        $("#btnLoader").show();
        $("#registerBtn").prop("disabled", true);

        // Send data to backend using AJAX
        $.ajax({
          url: "register_action.php",
          type: "POST",
          data: { name, email, password },
          dataType: "json",
          success: function(response) {
            if (response.status === "success") {
              $("#registerError").removeClass().addClass("error").css({
                "background": "#e8f5e9",
                "color": "#2e7d32",
                "border-left-color": "#2e7d32"
              }).html('<i class="bi bi-check-circle"></i> ' + response.message).fadeIn();
              
              setTimeout(() => {
                window.location.href = "index.php";
              }, 1500);
            } else {
              $("#registerError").html('<i class="bi bi-exclamation-circle"></i> ' + response.message).fadeIn().delay(3000).fadeOut();
              
              // Reset button
              $("#btnText").show();
              $("#btnLoader").hide();
              $("#registerBtn").prop("disabled", false);
            }
          },
          error: function() {
            $("#registerError").html('<i class="bi bi-x-circle"></i> Server error. Please try again.').fadeIn().delay(3000).fadeOut();
            
            // Reset button
            $("#btnText").show();
            $("#btnLoader").hide();
            $("#registerBtn").prop("disabled", false);
          }
        });
      });

      // Enter key to register
      $("#name, #email, #password, #confirmPassword").keypress(function(e) {
        if (e.which === 13) {
          $("#registerBtn").click();
        }
      });
    });
  </script>

</body>

</html>
