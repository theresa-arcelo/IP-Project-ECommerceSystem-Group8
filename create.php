<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Account Created</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    body { display: flex; justify-content: center; align-items: center; height: 100vh; padding: 0; background: #f7f8fa; }
    .success-card { width: 100%; max-width: 450px; padding: 40px; text-align: center; }
    .success-card img { max-width: 180px; margin: 0 auto 20px; }
    .success-card h2 { color: #1f2933; margin-bottom: 10px; }
    .success-card hr { border: none; border-top: 1px solid #e5e7eb; margin: 20px 0; }
    .success-card p { font-size: 16px; color: #4b5563; }
    .success-card .btn { display: inline-block; width: 100%; margin-top: 20px; text-align: center; padding: 12px; }
  </style>
</head>
<body>
  <div class="success-card form-card">
    <img src="images/create.gif" alt="Success">
    <hr>
    <h2>Account Successfully Created!</h2>
    <?php
      session_start();
      if (isset($_SESSION['userID'])) {
          $userID = htmlspecialchars($_SESSION['userID']);
          echo "<p><strong>Your User ID is: #$userID</strong></p>";
      } else {
          echo "<p style='color: #dc2626;'>User ID not found.</p>";
      }
    ?>
    <hr>
    <a href="login.php" class="btn">Click Here to Log In</a>
  </div>
</body>
</html>