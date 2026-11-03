<?php
session_start();
include 'db.php';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (password, fullname, shippingaddress, contactnumber, roleID) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $password, $name, $address, $phone, $role);

    if ($stmt->execute()) {
        $_SESSION['userID'] = $conn->insert_id;
        header("Location: create.php");
        exit();
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Sign Up</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body { display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; background: #f7f8fa; }
    .signup-container { width: 100%; max-width: 400px; padding: 30px; }
    .signup-container h2 { color: #8a0154; text-align: center; margin-bottom: 20px; }
    .signup-container hr { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
    .signup-container input { width: 100%; padding: 12px 14px; margin-bottom: 15px; border: 1px solid #e5e7eb; border-radius: 8px; outline: none; }
    .signup-container input:focus { border-color: #8a0154; box-shadow: 0 0 0 3px rgba(138,1,84,0.12); }
    .password-wrapper { position: relative; }
    .toggle-password { position: absolute; right: 15px; top: 15px; cursor: pointer; color: #6b7280; }
    .signup-container .btn { width: 100%; padding: 12px; font-size: 15px; }
    .signup-container .link { display: block; text-align: center; margin-top: 15px; color: #8a0154; font-weight: 600; font-size: 14px; }
    .error-msg { color: #dc2626; text-align: center; margin-bottom: 15px; font-weight: 600; font-size: 14px; }
  </style>
</head>
<body>
  <div class="signup-container form-card">
    <h2>★ TalaTrade ★</h2>
    <hr>
    <?php if (isset($error)) echo "<div class='error-msg'>$error</div>"; ?>
    <form method="POST" action="">
      <input type="text" placeholder="Full Name" name="name" required />
      <div class="password-wrapper">
        <input type="password" placeholder="Password" name="password" id="password" required />
        <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
      </div>
      <input type="text" placeholder="Shipping Address" name="address" required />
      <input type="text" placeholder="Contact Number" name="phone" required />
      <input type="text" placeholder="Role (1 = Admin; 2 = Customer)" name="role" required />
      <hr>
      <button type="submit" class="btn" name="submit">Create An Account</button>
    </form>
    <a href="login.php" class="link">Already Have an Account?</a>
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