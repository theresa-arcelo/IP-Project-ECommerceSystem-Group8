<?php
session_start();
include 'db.php';

if (isset($_POST['checkout'])) {
    if (!empty($_SESSION['cart'])) {
        $total = 0;
        foreach ($_SESSION['cart'] as $productID => $quantity) {
            $stmt = $conn->prepare("SELECT price, stockquantity FROM products WHERE productID = ?");
            $stmt->bind_param("i", $productID);
            $stmt->execute();
            $res = $stmt->get_result();

            if ($res && $res->num_rows > 0) {
                $row = $res->fetch_assoc();
                $price = $row['price'];
                $currentStock = $row['stockquantity'];

                if ($currentStock >= $quantity) {
                    $newStock = $currentStock - $quantity;
                    $upd = $conn->prepare("UPDATE products SET stockquantity = ? WHERE productID = ?");
                    $upd->bind_param("ii", $newStock, $productID);
                    $upd->execute();

                    $totalAmount = $price * $quantity;
                    $total += $totalAmount;

                    $ins = $conn->prepare("INSERT INTO orderlines (orderID, productID, quantity, totalamount) VALUES (NULL, ?, ?, ?)");
                    $ins->bind_param("iid", $productID, $quantity, $totalAmount);
                    $ins->execute();
                }
            }
        }
        $_SESSION['order_total'] = $total;
        header("Location: payment.php");
        exit();
    }
}

if (isset($_POST['add_to_cart'])) {
    $productID = $_POST['productID'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    if (isset($_SESSION['cart'][$productID])) {
        $_SESSION['cart'][$productID] += $quantity;
    } else {
        $_SESSION['cart'][$productID] = $quantity;
    }
    header("Location: user_cart.php");
    exit();
}

if (isset($_GET['remove'])) {
    $removeID = $_GET['remove'];
    unset($_SESSION['cart'][$removeID]);
    header("Location: user_cart.php");
    exit();
}

if (isset($_POST['update_cart'])) {
    foreach ($_POST['quantities'] as $pid => $qty) {
        if ($qty > 0) {
            $_SESSION['cart'][$pid] = $qty;
        } else {
            unset($_SESSION['cart'][$pid]);
        }
    }
    header("Location: user_cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Cart</title>
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
    <span class="active">1. Shopping Cart</span>
    <span>2. Payment Details</span>
  </div>

  <main class="cart-container">
    <form method="POST" action="user_cart.php" class="cart-section">
      <div class="cart-title-row">
        <h2>Shopping Cart</h2>
        <span class="cart-count">
          <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?> items
        </span>
      </div>
      <p style="margin-bottom:20px; font-size:13px;">
        <a href="user_home.php" class="continue-link" style="color: #8a0154; font-weight: 600;">← Continue Shopping</a>
      </p>

      <?php
      $total = 0;
      if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $id => $qty) {
              $stmt = $conn->prepare("SELECT * FROM products WHERE productID = ?");
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $res = $stmt->get_result();
              if ($res && $res->num_rows > 0) {
                  $row = $res->fetch_assoc();
                  $price = $row['price'];
                  $name = $row['productname'];
                  $img = $row['img'];
                  $subtotal = $price * $qty;
                  $total += $subtotal;

                  echo "<div class='cart-item'>";
                  echo "<img src='products/$img' alt='Product'>";
                  echo "<div class='item-details'>";
                  echo "<h4>$name</h4>";
                  echo "<p class='item-price'>₱" . number_format($price, 2) . " each</p>";
                  echo "<div class='item-meta'>";
                  echo "<input type='number' name='quantities[$id]' value='$qty' min='1'>";
                  echo "<button type='submit' name='update_cart' class='btn btn-outline' style='padding:6px 12px; font-size:12px;'>Update</button>";
                  echo "<a href='user_cart.php?remove=$id' class='remove-link'>Remove</a>";
                  echo "</div>";
                  echo "<p class='item-subtotal'>Subtotal: ₱" . number_format($subtotal, 2) . "</p>";
                  echo "</div></div>";
              }
          }
      } else {
          echo "<div class='empty-cart'><h3>Your cart is empty</h3><p>Looks like you haven't added anything yet.</p><a href='user_home.php' class='btn'>Start Shopping</a></div>";
      }
      ?>
    </form>

    <aside class="summary-section">
      <h3>ORDER SUMMARY</h3>
      <hr class="summary-divider">
      <?php
      if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
          foreach ($_SESSION['cart'] as $id => $qty) {
              $stmt = $conn->prepare("SELECT productname, price FROM products WHERE productID = ?");
              $stmt->bind_param("i", $id);
              $stmt->execute();
              $res = $stmt->get_result();
              if ($res && $res->num_rows > 0) {
                  $row = $res->fetch_assoc();
                  $subtotal = $row['price'] * $qty;
                  echo "<div class='summary-item'><span>{$row['productname']} × $qty</span><span>₱" . number_format($subtotal, 2) . "</span></div>";
              }
          }
          echo "<hr class='summary-divider'>";
          echo "<div class='summary-item'><span>Subtotal</span><span>₱" . number_format($total, 2) . "</span></div>";
          echo "<div class='summary-item'><span>Shipping</span><span>Calculated at next step</span></div>";
          echo "<hr class='summary-divider'>";
          echo "<div class='summary-total'><span>TOTAL</span><span>₱" . number_format($total, 2) . "</span></div>";
      } else {
          echo "<p style='text-align:center; color:#6b7280;'>No items in cart.</p>";
      }
      ?>
      <form method="POST" action="user_cart.php" style="margin-bottom: 10px;">
        <button type="submit" name="checkout" class="btn" style="width:100%;" <?php echo empty($_SESSION['cart']) ? 'disabled' : ''; ?>>Continue to Checkout</button>
      </form>
      <a href="user_home.php"><button class="btn btn-outline" style="width:100%;">Cancel</button></a>
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