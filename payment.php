<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$total = isset($_SESSION['order_total']) ? $_SESSION['order_total'] : 0;
$selectedPaymentMethod = $_SESSION['selected_payment_method'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_payment_method'])) {
        $selectedPaymentMethod = $_POST['payment_method'] ?? '';
        $_SESSION['selected_payment_method'] = $selectedPaymentMethod;
    }

    if (isset($_POST['submit_order'])) {
        $selectedPaymentMethod = $_POST['payment_method'] ?? ($_SESSION['selected_payment_method'] ?? '');
        $couponCode = trim($_POST['coupon_code']);
        
        $discountID = null; 
        $finalTotal = $total;

        if (isset($_FILES['senior_pwd_id']) && $_FILES['senior_pwd_id']['error'] === UPLOAD_ERR_OK) {
            $discountID = 1; 
        } elseif (!empty($couponCode)) {
            $stmt = $conn->prepare("SELECT discountID, discountedamount FROM discounts WHERE discountID = ?");
            $stmt->bind_param("s", $couponCode);
            $stmt->execute();
            $res = $stmt->get_result();
            if ($res && $res->num_rows > 0) {
                $discount = $res->fetch_assoc();
                $discountID = $discount['discountID'];
                $finalTotal -= ($finalTotal * $discount['discountedamount']);
            }
        }

        if ($selectedPaymentMethod) {
            $proof = (isset($_FILES['proof']) && $_FILES['proof']['error'] === UPLOAD_ERR_OK) ? 1 : 0;
            $now = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("INSERT INTO orders (userID, orderdatetime, totalamount, status, completeddatetime, paymentmethod, proofofpayment, discountID) VALUES (?, ?, ?, 'Pending', NULL, ?, ?, ?)");
            $stmt->bind_param("isdssi", $userID, $now, $finalTotal, $selectedPaymentMethod, $proof, $discountID);

            if ($stmt->execute()) {
                $newOrderID = $conn->insert_id;
                $_SESSION['last_order_id'] = $newOrderID;
                
                $upd = $conn->prepare("UPDATE orderlines SET orderID = ? WHERE orderID IS NULL");
                $upd->bind_param("i", $newOrderID);
                $upd->execute();

                unset($_SESSION['cart'], $_SESSION['order_total'], $_SESSION['selected_payment_method']);
                header("Location: delivery.php");
                exit();
            } else {
                $error = "Error placing order: " . $conn->error;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Payment Details</title>
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

  <div class="steps">
    <span>1. Shopping Cart</span>
    <span class="active">2. Payment Details</span>
  </div>

  <main class="cart-container" style="align-items: flex-start;">
    <form method="POST" enctype="multipart/form-data" style="display: flex; gap: 30px; width: 100%;">
      <section class="cart-section" style="background: #fff; padding: 24px; border-radius: 10px; border: 1px solid #e5e7eb;">
        <div class="section-title-row">
          <h2>Payment Method</h2>
        </div>

        <?php if(isset($error)) echo "<p style='color:#dc2626;'>$error</p>"; ?>

        <label class="payment-option <?php if ($selectedPaymentMethod === 'COD') echo 'active'; ?>">
          <input type="radio" name="payment_method" value="COD" <?php if ($selectedPaymentMethod === 'COD') echo 'checked'; ?> />
          <div class="payment-info">
            <strong>Cash on Delivery (COD)</strong>
            <small>Pay in cash when your order arrives at your doorstep</small>
          </div>
        </label>

        <label class="payment-option <?php if ($selectedPaymentMethod === 'QR') echo 'active'; ?>">
          <input type="radio" name="payment_method" value="QR" <?php if ($selectedPaymentMethod === 'QR') echo 'checked'; ?> />
          <div class="payment-info">
            <strong>Pay via QR Code</strong>
            <small>Scan, pay, and upload your proof of payment</small>
          </div>
        </label>

        <div class="qr-block" style="margin-top: 20px;">
          <img src="images/qr.png" alt="QR Code">
          <div class="qr-info">
            <p><strong>After paying, please upload your Proof of Payment:</strong></p>
            <input type="file" name="proof" />
            <p style="font-size: 12px; color: #6b7280;">Accepted formats: JPG, PNG, PDF. Max size: 5MB.</p>
          </div>
        </div>

        <button type="submit" name="update_payment_method" class="btn" style="width: 100%; margin-top: 20px;">Update Payment Method</button>
      </section>

      <aside class="summary-section">
        <h3>ORDER SUMMARY</h3>
        <hr class="summary-divider">

        <input type="text" name="coupon_code" class="coupon-input" placeholder="Enter Discount Code Here" />
        
        <div style="margin-bottom: 15px;">
          <p style="font-size: 13px; color: #4b5563; margin-bottom: 5px;">Senior Citizen / PWD ID (Optional)</p>
          <input type="file" name="senior_pwd_id" style="font-size: 12px;" />
        </div>
        
        <hr class="summary-divider">

        <div class="summary-item">
          <span>USER ID:</span>
          <span><strong><?php echo $userID; ?></strong></span>
        </div>
        <div class="summary-item">
          <span>PAYMENT METHOD:</span>
          <span><strong><?php echo htmlspecialchars($selectedPaymentMethod ?: 'Not Selected'); ?></strong></span>
        </div>
        <hr class="summary-divider">
        <div class="summary-total">
          <span>TOTAL:</span>
          <span>₱<?php echo number_format($total, 2); ?></span>
        </div>
        <hr class="summary-divider">

        <button type="submit" name="submit_order" class="btn" style="width: 100%; margin-bottom: 10px;">Submit Order</button>
        <a href="user_cart.php"><button type="button" class="btn btn-outline" style="width: 100%;">Back to Cart</button></a>
      </aside>
    </form>
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