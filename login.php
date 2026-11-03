<?php
session_start();
include 'db.php';

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT roleID, password FROM users WHERE userID = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['userID'] = $id;
            if ($row['roleID'] == 1) {
                header("Location: admin_home.php");
            } else {
                header("Location: user_home.php");
            }
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Log In</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body { display: flex; justify-content: center; align-items: center; height: 100vh; padding: 0; background: #f7f8fa; }
    .login-container { width: 100%; max-width: 400px; padding: 20px; }
    .login-container h2 { color: #8a0154; text-align: center; margin-bottom: 20px; }
    .login-container hr { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
    .login-container input { width: 100%; padding: 12px 14px; margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 8px; outline: none; }
    .login-container input:focus { border-color: #8a0154; box-shadow: 0 0 0 3px rgba(138,1,84,0.12); }
    .password-wrapper { position: relative; }
    .toggle-password { position: absolute; right: 15px; top: 15px; cursor: pointer; color: #6b7280; }
    .login-container .btn { width: 100%; padding: 12px; font-size: 15px; margin-top: 10px; }
    .login-container .link { display: block; text-align: center; margin-top: 15px; color: #8a0154; font-weight: 600; font-size: 14px; }
    .error-msg { color: #dc2626; text-align: center; margin-bottom: 15px; font-weight: 600; font-size: 14px; }
  </style>
</head>
<body>
  <div class="login-container form-card">
    <h2>★ TalaTrade ★</h2>
    <hr>
    <?php if (isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
    <form method="POST" action="">
      <input type="text" placeholder="User ID" name="id" required />
      <div class="password-wrapper">
        <input type="password" placeholder="Password" name="password" id="password" required />
        <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
      </div>
      <button type="submit" class="btn" name="submit">Log In</button>
    </form>
    <hr>
    <a href="signup.php" class="link">Don't Have An Account Yet?</a>
  </div>
  <script>
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    togglePassword.addEventListener('click', function () {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      this.classList.toggle('fa-eye-slash');
      this.classList.toggle('fa-eye');
    });
  </script>
</body>
</html>