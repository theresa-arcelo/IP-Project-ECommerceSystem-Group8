<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];

$stmt = $conn->prepare("SELECT fullname, shippingaddress, contactnumber FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

$fullNameParts = explode(' ', $user_data['fullname'] ?? '', 2);
$fname = $fullNameParts[0] ?? '';
$lname = $fullNameParts[1] ?? '';
$address = $user_data['shippingaddress'] ?? '';
$phone = $user_data['contactnumber'] ?? '';

$cart_items = [];
$total = isset($_SESSION['order_total']) ? $_SESSION['order_total'] : 0;

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $qty) {
        $stmt = $conn->prepare("SELECT productname, price FROM products WHERE productID = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            $cart_items[] = [
                'name' => $row['productname'],
                'qty' => $qty,
                'subtotal' => $row['price'] * $qty
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TalaTrade - Shipping Details</title>
  <link rel="stylesheet" href="css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .section-title-row {
      display: flex; justify-content: space-between; align-items: center;
      padding-bottom: 12px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px;
      flex-wrap: wrap; gap: 8px;
    }
    .section-title-row h2 { font-size: 22px; color: #1f2933; letter-spacing: -0.3px; }
    .section-title-row .subtext { font-size: 13px; color: #6b7280; font-weight: 600; }

    .shipping-form { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .form-group { display: flex; flex-direction: column; gap: 5px; }
    .form-group label {
      font-size: 12px; font-weight: 700; color: #4b5563;
      text-transform: uppercase; letter-spacing: 0.6px;
    }
    .shipping-form input {
      padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 8px;
      width: 100%; font-size: 14px; outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .shipping-form input:focus {
      border-color: #8a0154;
      box-shadow: 0 0 0 3px rgba(138,1,84,0.12);
    }
    .shipping-form .full { grid-column: span 2; }

    .delivery-title {
      margin-top: 24px; margin-bottom: 12px; font-size: 15px;
      color: #1f2933; font-weight: 700;
      padding-bottom: 8px; border-bottom: 1px solid #e5e7eb;
    }

    .delivery-option {
      display: flex; align-items: center; gap: 14px;
      padding: 18px; border: 1px solid #e5e7eb;
      border-radius: 10px; margin-top: 12px; cursor: pointer;
      background: #fff;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .delivery-option:hover { border-color: #8a0154; }
    .delivery-option.active {
      border-color: #8a0154;
      box-shadow: 0 0 0 3px rgba(138,1,84,0.10);
    }
    .delivery-option input[type="radio"] {
      width: 18px; height: 18px; cursor: pointer; accent-color: #8a0154;
    }
    .delivery-option .delivery-info strong {
      display: block; font-size: 15px; margin-bottom: 3px; color: #1f2933;
    }
    .delivery-option .delivery-info small { font-size: 12px; color: #6b7280; }

    .coupon-input {
      width: 100%; padding: 11px 14px; margin-bottom: 15px;
      border: 1px solid #e5e7eb; border-radius: 8px; font-size: 13px;
      outline: none; transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .coupon-input:focus {
      border-color: #8a0154;
      box-shadow: 0 0 0 3px rgba(138,1,84,0.12);
    }

    .summary-item {
      display: flex; justify-content: space-between; font-size: 13px;
      margin-bottom: 6px; color: #4b5563;
    }
    .summary-divider { border: none; border-top: 1px solid #e5e7eb; margin: 12px 0; }
    .summary-total {
      display: flex; justify-content: space-between; font-size: 16px;
      font-weight: 700; color: #8a0154; margin: 10px 0;
    }
    .summary-section h3 {
      font-size: 16px; color: #1f2933; text-align: center;
      margin-bottom: 15px; letter-spacing: 1px;
    }

    @media (max-width: 700px) {
      .shipping-form { grid-template-columns: 1fr; }
      .shipping-form .full { grid-column: span 1; }
    }
  </style>
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

<div class="steps">
  <span>1. Shopping Cart</span>
  <span class="active">2. Shipping Details</span>
  <span>3. Payment Details</span>
</div>

<main class="cart-container">
  <form method="POST" action="payment.php" class="cart-section" style="background:#fff; padding:24px; border-radius:10px; border:1px solid #e5e7eb;">
    <div class="section-title-row">
      <h2>Shipping Details</h2>
      <span class="subtext">Step 2 of 3</span>
    </div>

    <div class="shipping-form">
      <div class="form-group">
        <label for="fname">First Name</label>
        <input type="text" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" placeholder="Juan" required>
      </div>
      <div class="form-group">
        <label for="lname">Last Name</label>
        <input type="text" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" placeholder="Dela Cruz" required>
      </div>
      <div class="form-group full">
        <label for="address">Street Address</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" placeholder="House No., Street, Barangay" required>
      </div>
      <div class="form-group">
        <label for="country">Country</label>
        <input type="text" id="country" name="country" placeholder="Philippines" value="Philippines" required>
      </div>
      <div class="form-group">
        <label for="city">City / Municipality</label>
        <input type="text" id="city" name="city" placeholder="Liloan" required>
      </div>
      <div class="form-group">
        <label for="zip">ZIP / Postal Code</label>
        <input type="text" id="zip" name="zip" placeholder="6002" required>
      </div>
      <div class="form-group">
        <label for="phone">Phone Number</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" placeholder="0912 345 6789" required>
      </div>
    </div>

    <h3 class="delivery-title">Delivery Method</h3>

    <label class="delivery-option active">
      <input type="radio" name="delivery" value="standard" checked>
      <div class="delivery-info">
        <strong>Free Shipping</strong>
        <small>Estimated delivery: 7 to 21 days</small>
      </div>
    </label>

    <label class="delivery-option">
      <input type="radio" name="delivery" value="same_day">
      <div class="delivery-info">
        <strong>Same Day Delivery — ₱0.00</strong>
        <small>Within 24 hours from checkout</small>
      </div>
    </label>
    
    <div style="margin-top: 24px; display: none;">
        <button type="submit" name="save_shipping" class="btn">Hidden Submit</button>
    </div>
  </form>

  <aside class="summary-section">
    <h3>ORDER SUMMARY</h3>
    <hr class="summary-divider">

    <input type="text" class="coupon-input" placeholder="Enter coupon code here" disabled>

    <?php if (!empty($cart_items)): ?>
      <?php foreach ($cart_items as $item): ?>
        <div class="summary-item">
          <span><?php echo htmlspecialchars($item['name']); ?> × <?php echo $item['qty']; ?></span>
          <span>₱<?php echo number_format($item['subtotal'], 2); ?></span>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="summary-item"><span>No items in cart.</span><span></span></div>
    <?php endif; ?>

    <hr class="summary-divider">

    <div class="summary-item"><span>Subtotal</span><span>₱<?php echo number_format($total, 2); ?></span></div>
    <div class="summary-item"><span>Shipping</span><span>₱0.00</span></div>

    <hr class="summary-divider">

    <div class="summary-total"><span>TOTAL</span><span>₱<?php echo number_format($total, 2); ?></span></div>

    <button type="submit" form="" onclick="document.querySelector('form').submit();" class="btn" style="width: 100%; margin-bottom: 10px;">Continue to Payment</button>
    <a href="user_cart.php"><button class="btn btn-outline" style="width: 100%;">Back to Cart</button></a>
  </aside>
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