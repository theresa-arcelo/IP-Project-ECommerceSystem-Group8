<?php
include 'db.php';
session_start();
$orderID = isset($_GET['orderID']) ? (int) $_GET['orderID'] : 0;

if (isset($_POST['delete'])) {
    $orderlineID = $_POST['orderlineID'];
    $stmt = $conn->prepare("DELETE FROM orderlines WHERE orderlineID = ?");
    $stmt->bind_param("i", $orderlineID);
    $stmt->execute();
    header("Location: admin_order.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Order Details</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .admin-main { width: 85%; max-width: 1200px; margin: 30px auto; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(16,24,40,0.08); }
    th, td { padding: 12px 14px; font-size: 14px; text-align: center; border-bottom: 1px solid #e5e7eb; }
    th { background: #f9fafb; color: #1f2933; font-weight: 700; font-size: 11px; text-transform: uppercase; }
    .btn-remove { background: #fff; color: #8a0154; border: 1px solid #e5e7eb; padding: 6px 14px; font-size: 12px; }
    .btn-remove:hover { background: #fdf2f8; border-color: #8a0154; }
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
    <p style="font-size: 13px; color: #6b7280; margin-bottom: 15px;">
      <a href="admin_order.php" style="color: #8a0154; font-weight: 600;">Manage Orders</a> / Order #<?php echo htmlspecialchars($orderID); ?> Details
    </p>
    <h2 style="font-size: 22px; color: #1f2933; margin-bottom: 20px;">Order Details — #<?php echo htmlspecialchars($orderID); ?></h2>

    <?php
      $stmt = $conn->prepare("SELECT * FROM orderlines WHERE orderID = ?");
      $stmt->bind_param("i", $orderID);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result && $result->num_rows > 0) {
          echo "<table>";
          echo "<thead><tr><th>Order ID</th><th>Orderline ID</th><th>Product ID</th><th>Quantity</th><th>Total Amount</th><th>Options</th></tr></thead>";
          echo "<tbody>";
          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>";
              echo "<td>" . $row['orderID'] . "</td>";
              echo "<td>" . $row['orderlineID'] . "</td>";
              echo "<td>" . $row['productID'] . "</td>";
              echo "<td>" . $row['quantity'] . "</td>";
              echo "<td>₱" . number_format($row['totalamount'], 2) . "</td>";
              echo "<td><form method='POST' style='margin:0;'><input type='hidden' name='orderlineID' value='{$row['orderlineID']}'><button type='submit' name='delete' class='btn-remove' onclick=\"return confirm('Delete this orderline?');\">REMOVE</button></form></td>";
              echo "</tr>";
          }
          echo "</tbody></table>";
      } else {
          echo "<p>No orderlines found.</p>";
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