<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userID'];
$stmt = $conn->prepare("SELECT * FROM users WHERE userID = ?");
$stmt->bind_param("i", $userID);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();

$fullname = $user_data['fullname'];
$address = $user_data['shippingaddress'];
$contact = $user_data['contactnumber'];
$role = $user_data['roleID'];

$stmt = $conn->prepare("SELECT orders.*, discounts.discountname FROM orders LEFT JOIN discounts ON orders.discountID = discounts.discountID WHERE orders.userID = ? ORDER BY orders.orderID DESC");
$stmt->bind_param("i", $userID);
$stmt->execute();
$orders_res = $stmt->get_result();

$pending_orders = [];
$completed_orders = [];
$cancelled_orders = [];

while ($order = $orders_res->fetch_assoc()) {
    if ($order['status'] === 'Pending') $pending_orders[] = $order;
    elseif ($order['status'] === 'Completed') $completed_orders[] = $order;
    elseif ($order['status'] === 'Cancelled') $cancelled_orders[] = $order;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_fullname = $_POST['fullname'];
    $new_address = $_POST['address'];
    $new_contact = $_POST['contact'];

    $upd = $conn->prepare("UPDATE users SET fullname = ?, shippingaddress = ?, contactnumber = ? WHERE userID = ?");
    $upd->bind_param("sssi", $new_fullname, $new_address, $new_contact, $userID);
    if ($upd->execute()) {
        $fullname = $new_fullname;
        $address = $new_address;
        $contact = $new_contact;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $orderID = intval($_POST['orderID']);
    $upd = $conn->prepare("UPDATE orders SET status = 'Cancelled', totalamount = NULL, paymentmethod = NULL, proofofpayment = NULL, discountID = NULL WHERE orderID = ? AND userID = ?");
    $upd->bind_param("ii", $orderID, $userID);
    $upd->execute();
    header("Location: user_account.php"); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reorder'])) {
    $orderID = intval($_POST['orderID']);
    $stmt = $conn->prepare("SELECT totalamount, paymentmethod, discountID FROM orders WHERE orderID = ? AND userID = ?");
    $stmt->bind_param("ii", $orderID, $userID);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $order_data = $res->fetch_assoc();
        $total = is_null($order_data['totalamount']) ? null : $order_data['totalamount'];
        $method = is_null($order_data['paymentmethod']) ? null : $order_data['paymentmethod'];
        $discount = is_null($order_data['discountID']) ? null : $order_data['discountID'];

        $ins = $conn->prepare("INSERT INTO orders (userID, orderdatetime, totalamount, paymentmethod, discountID, status, completeddatetime) VALUES (?, NOW(), ?, ?, ?, 'Pending', NULL)");
        $ins->bind_param("isss", $userID, $total, $method, $discount);
        
        if ($ins->execute()) {
            $new_order_id = $conn->insert_id;
            $lines_stmt = $conn->prepare("SELECT productID, quantity, totalamount FROM orderlines WHERE orderID = ?");
            $lines_stmt->bind_param("i", $orderID);
            $lines_stmt->execute();
            $lines_res = $lines_stmt->get_result();

            $ins_line = $conn->prepare("INSERT INTO orderlines (orderID, productID, quantity, totalamount) VALUES (?, ?, ?, ?)");
            while ($line = $lines_res->fetch_assoc()) {
                $ins_line->bind_param("iiid", $new_order_id, $line['productID'], $line['quantity'], $line['totalamount']);
                $ins_line->execute();
            }
        }
    }
    header("Location: user_account.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Account Details</title>
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

  <main class="account-wrapper">
    <section class="account-left">
      <h2>ACCOUNT INFORMATION</h2>
      <img src="images/user_pfp.png" alt="Profile">
      <hr style="border:none; border-top: 1px solid #e5e7eb; margin: 15px 0;">
      <p class="user-meta"><strong>USER ROLE:</strong> <?php echo ($role == 1) ? 'Administrator' : 'User'; ?></p>
      <p class="user-meta"><strong>USER ID:</strong> <?php echo htmlspecialchars($userID); ?></p>
      <hr style="border:none; border-top: 1px solid #e5e7eb; margin: 15px 0;">
      <form method="POST">
        <input type="text" name="fullname" value="<?php echo htmlspecialchars($fullname); ?>" required placeholder="Full Name" />
        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required placeholder="Address" />
        <input type="text" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required placeholder="Contact" />
        <hr style="border:none; border-top: 1px solid #e5e7eb; margin: 15px 0;">
        <button type="submit" name="update_profile" class="btn">UPDATE PROFILE</button>
      </form>
    </section>

    <section class="account-right">
      <h2>PENDING ORDER HISTORY</h2>
      <table>
        <thead>
          <tr><th>Order ID</th><th>Date</th><th>Total (₱)</th><th>Status</th><th>Payment</th><th>Proof</th><th>Discount</th><th>Options</th></tr>
        </thead>
        <tbody>
          <?php if (!empty($pending_orders)): foreach ($pending_orders as $order): ?>
            <tr>
              <td>#<?php echo $order['orderID']; ?></td>
              <td><?php echo date('M d, Y', strtotime($order['orderdatetime'])); ?></td>
              <td><?php echo number_format($order['totalamount'], 2); ?></td>
              <td><span class="status-badge status-pending"><?php echo $order['status']; ?></span></td>
              <td><?php echo $order['paymentmethod']; ?></td>
              <td><?php echo ($order['proofofpayment'] == 0) ? 'N/A' : 'Received'; ?></td>
              <td><?php echo empty($order['discountname']) ? 'N/A' : htmlspecialchars($order['discountname']); ?></td>
              <td>
                <form method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');" style="margin:0;">
                  <input type="hidden" name="orderID" value="<?php echo $order['orderID']; ?>">
                  <button type="submit" name="cancel_order" class="btn btn-outline" style="padding: 4px 10px; font-size: 11px; color: #dc2626; border-color: #fca5a5;">Cancel</button>
                </form>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="8" class="empty-row">No pending orders.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <h2>COMPLETED ORDER HISTORY</h2>
      <table>
        <thead>
          <tr><th>Order ID</th><th>Date</th><th>Total (₱)</th><th>Status</th><th>Payment</th><th>Proof</th><th>Discount</th><th>Options</th></tr>
        </thead>
        <tbody>
          <?php if (!empty($completed_orders)): foreach ($completed_orders as $order): ?>
            <tr>
              <td>#<?php echo $order['orderID']; ?></td>
              <td><?php echo date('M d, Y', strtotime($order['orderdatetime'])); ?></td>
              <td><?php echo number_format($order['totalamount'], 2); ?></td>
              <td><span class="status-badge status-completed"><?php echo $order['status']; ?></span></td>
              <td><?php echo $order['paymentmethod']; ?></td>
              <td><?php echo ($order['proofofpayment'] == 0) ? 'N/A' : 'Yes'; ?></td>
              <td><?php echo empty($order['discountname']) ? 'N/A' : htmlspecialchars($order['discountname']); ?></td>
              <td>
                <form method="POST" style="margin:0;">
                  <input type="hidden" name="orderID" value="<?php echo $order['orderID']; ?>">
                  <button type="submit" name="reorder" class="btn" style="padding: 4px 10px; font-size: 11px;">Re-Order</button>
                </form>
              </td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="8" class="empty-row">No completed orders.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <h2>CANCELLED ORDER HISTORY</h2>
      <table>
        <thead>
          <tr><th>Order ID</th><th>Date</th><th>Total (₱)</th><th>Status</th><th>Payment</th><th>Proof</th><th>Discount</th></tr>
        </thead>
        <tbody>
          <?php if (!empty($cancelled_orders)): foreach ($cancelled_orders as $order): ?>
            <tr>
              <td>#<?php echo $order['orderID']; ?></td>
              <td><?php echo date('M d, Y', strtotime($order['orderdatetime'])); ?></td>
              <td><?php echo number_format($order['totalamount'], 2); ?></td>
              <td><span class="status-badge status-cancelled"><?php echo $order['status']; ?></span></td>
              <td><?php echo $order['paymentmethod']; ?></td>
              <td><?php echo ($order['proofofpayment'] == 0) ? 'N/A' : 'Yes'; ?></td>
              <td><?php echo empty($order['discountname']) ? 'N/A' : htmlspecialchars($order['discountname']); ?></td>
            </tr>
          <?php endforeach; else: ?>
            <tr><td colspan="7" class="empty-row">No cancelled orders.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </section>
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