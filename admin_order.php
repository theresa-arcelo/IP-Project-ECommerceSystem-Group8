<?php
include 'db.php';
session_start();

if (isset($_POST['update']) || isset($_POST['delete'])) {
    $orderID = $_POST['orderID'];

    if (isset($_POST['update'])) {
        $orderdate = $_POST['orderdate'];
        $total = $_POST['total'];
        $status = $_POST['status'];
        $completeddate = $_POST['completeddate'];
        $paymentmethod = $_POST['paymentmethod'];
        $proof = $_POST['proof'];

        $stmt = $conn->prepare("UPDATE orders SET orderdatetime=?, totalamount=?, status=?, completeddatetime=?, paymentmethod=?, proofofpayment=? WHERE orderID=?");
        $stmt->bind_param("sdssssi", $orderdate, $total, $status, $completeddate, $paymentmethod, $proof, $orderID);
        $stmt->execute();
    }

    if (isset($_POST['delete'])) {
        $stmt = $conn->prepare("DELETE FROM orders WHERE orderID=?");
        $stmt->bind_param("i", $orderID);
        $stmt->execute();
    }
    header("Location: admin_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Manage Orders</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .admin-main { width: 95%; max-width: 1500px; margin: 30px auto; }
    .admin-cats { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; margin-bottom: 25px; padding: 14px 20px; background: #fff; border-radius: 10px; border: 1px solid #e5e7eb; }
    .admin-cats a { padding: 7px 16px; background: #fff; color: #6b7280; border: 1px solid #e5e7eb; border-radius: 999px; font-size: 12px; font-weight: 500; transition: all 0.2s ease; }
    .admin-cats a:hover, .admin-cats a.active { background: #8a0154; color: #fff; border-color: #8a0154; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(16,24,40,0.08); }
    th, td { padding: 10px 8px; font-size: 12px; text-align: center; vertical-align: middle; border-bottom: 1px solid #e5e7eb; }
    th { background: #f9fafb; color: #1f2933; font-weight: 700; font-size: 11px; text-transform: uppercase; }
    td input { width: 100%; padding: 6px; text-align: center; font-size: 12px; border: 1px solid #e5e7eb; border-radius: 6px; background: #fff; outline: none; }
    td .btn { padding: 5px 10px; font-size: 10px; margin: 2px 1px; display: inline-block; }
    .btn-remove { background: #fff; color: #8a0154; border: 1px solid #e5e7eb; }
    .btn-remove:hover { background: #fdf2f8; border-color: #8a0154; color: #8a0154; }
  </style>
</head>
<body>
  <header>
    <div class="logo"><a href="admin_home.php">★<strong> TalaTrade</strong></a></div>
    <nav class="nav-links">
      <a href="admin_home.php">Home</a>
      <a href="admin_product.php">Products</a>
      <a href="admin_order.php" class="active">Orders</a>
      <a href="admin_discount.php">Discounts</a>
    </nav>
    <div class="actions">
      <a href="logout.php" class="btn btn-outline">Log Out</a>
    </div>
  </header>

  <main class="admin-main">
    <h2 style="font-size: 22px; color: #1f2933; margin-bottom: 20px;">Order Information</h2>

    <div class="admin-cats">
      <a href="admin_order.php" class="<?php echo !isset($_GET['category']) ? 'active' : ''; ?>">All Orders</a>
      <a href="admin_order.php?category=Completed" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Completed') ? 'active' : ''; ?>">Completed</a>
      <a href="admin_order.php?category=Cancelled" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Cancelled') ? 'active' : ''; ?>">Cancelled</a>
      <a href="admin_order.php?category=Pending" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Pending') ? 'active' : ''; ?>">Pending</a>
      <a href="admin_order.php?category=COD" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'COD') ? 'active' : ''; ?>">COD</a>
      <a href="admin_order.php?category=QR" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'QR') ? 'active' : ''; ?>">QR Code</a>
    </div>

    <?php
      if (isset($_GET['category']) && !empty($_GET['category'])) {
          $selectedCategory = $_GET['category'];
          $stmt = $conn->prepare("SELECT * FROM orders WHERE status = ? OR paymentmethod = ?");
          $stmt->bind_param("ss", $selectedCategory, $selectedCategory);
          $stmt->execute();
          $result = $stmt->get_result();
      } else {
          $result = $conn->query("SELECT * FROM orders");
      }

      if ($result && $result->num_rows > 0) {
          echo "<table>";
          echo "<thead><tr><th>Order ID</th><th>User ID</th><th>Order Date</th><th>Total</th><th>Status</th><th>Completed</th><th>Payment</th><th>Proof</th><th>Discount</th><th>Options</th></tr></thead>";
          echo "<tbody>";
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr><form method='POST'>";
              echo "<td><input type='hidden' name='orderID' value='{$row['orderID']}' />{$row['orderID']}</td>";
              echo "<td><input type='hidden' name='userID' value='{$row['userID']}' />{$row['userID']}</td>";
              echo "<td><input type='text' name='orderdate' value='" . htmlspecialchars($row['orderdatetime']) . "' required></td>";
              echo "<td><input type='number' step='0.01' name='total' value='{$row['totalamount']}' required></td>";
              echo "<td><input type='text' name='status' value='" . htmlspecialchars($row['status']) . "' required></td>";
              echo "<td><input type='text' name='completeddate' value='" . htmlspecialchars($row['completeddatetime']) . "'></td>";
              echo "<td><input type='text' name='paymentmethod' value='" . htmlspecialchars($row['paymentmethod']) . "'></td>";
              echo "<td><input type='text' name='proof' value='" . htmlspecialchars($row['proofofpayment']) . "'></td>";
              echo "<td><input type='hidden' name='discountID' value='{$row['discountID']}' />" . ($row['discountID'] ?? 'N/A') . "</td>";
              echo "<td><button type='submit' name='update' class='btn'>UPDATE</button><br><button type='submit' name='delete' class='btn btn-remove' onclick=\"return confirm('Delete?');\">REMOVE</button><br><a href='admin_orderline.php?orderID={$row['orderID']}' class='btn btn-outline' style='margin-top:2px;'>DETAILS</a></td>";
              echo "</form></tr>";
          }
          echo "</tbody></table>";
      } else {
          echo "<p style='text-align:center;'>No orders found.</p>";
      }
    ?>
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