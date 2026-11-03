<?php 
session_start();
$orderID = isset($_SESSION['last_order_id']) ? $_SESSION['last_order_id'] : '0000';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Delivery Details</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
<header>
  <div class="logo"><a href="user_home.php">★<strong> TalaTrade</strong></a></div>
  <nav class="nav-links">
    <a href="user_home.php" class="<?php echo ($current_page == 'user_home.php' || $current_page == 'user_product.php') ? 'active' : ''; ?>">Home</a>
    <a href="user_cart.php" class="<?php echo ($current_page == 'user_cart.php') ? 'active' : ''; ?>">Cart</a>
    <a href="user_account.php" class="<?php echo ($current_page == 'user_account.php') ? 'active' : ''; ?>">Account</a>
  </nav>
  <div class="actions">
    <a href="logout.php" class="btn">Log Out</a>
  </div>
</header>

  <main class="delivery" style="padding: 60px 20px;">
    <img src="images/delivery.gif" alt="Delivery" style="width: 150px; margin-bottom: 20px;">
    <h1>Order Submitted Successfully!</h1> 
    <p style="font-size: 18px; margin-bottom: 20px;">Your Order ID is: <strong>#<?php echo $orderID; unset($_SESSION['last_order_id']); ?></strong></p> 
    <div style="display:flex; gap: 15px; justify-content: center;">
      <a href="user_account.php" class="btn">Track Order</a>
      <a href="user_home.php" class="btn btn-outline">Back to Shopping</a>
    </div>
  </main>

  <footer class="site-footer">
    <div>
      <h4>★ ABOUT US</h4>
      <p><strong>May's Sari-Sari Store</strong> is located in <strong>Poblacion, Liloan</strong>, open from <strong>7:30 AM to 8:30 PM</strong>. With TalaTrade, we're now accepting online orders!</p>
    </div>
    <div class="footer-right">
      <div><h4>★ EMAIL US</h4><p>emailaddress@gmail.com</p></div>
      <div><h4>★ CALL US</h4><p>123 456 7890</p></div>
      <div><h4>★ PRESENTED BY</h4><p>Arcelo, Calunsag, Dorego, & Jankovic</p></div>
    </div>
  </footer>
</body>
</html>