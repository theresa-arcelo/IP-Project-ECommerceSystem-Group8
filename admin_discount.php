<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_new'])) {
        $discountedamount = $_POST['discountedamount'];
        $discountname = $_POST['discountname'];

        $stmt = $conn->prepare("INSERT INTO discounts (discountedamount, discountname) VALUES (?, ?)");
        $stmt->bind_param("ds", $discountedamount, $discountname);
        $stmt->execute();
        header("Location: admin_discount.php");
        exit();
    }

    if (isset($_POST['update']) || isset($_POST['delete'])) {
        $discountID = $_POST['discountID'];
        if (isset($_POST['update'])) {
            $discountedamount = $_POST['discountedamount'];
            $discountname = $_POST['discountname'];
            $stmt = $conn->prepare("UPDATE discounts SET discountedamount=?, discountname=? WHERE discountID=?");
            $stmt->bind_param("dsi", $discountedamount, $discountname, $discountID);
            $stmt->execute();
        }
        if (isset($_POST['delete'])) {
            $stmt = $conn->prepare("DELETE FROM discounts WHERE discountID=?");
            $stmt->bind_param("i", $discountID);
            $stmt->execute();
        }
        header("Location: admin_discount.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Manage Discounts</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .admin-main { width: 85%; max-width: 1200px; margin: 30px auto; }
    .top-btn { display: flex; justify-content: flex-end; margin-bottom: 15px; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(16,24,40,0.08); }
    th, td { padding: 12px 14px; font-size: 14px; text-align: center; border-bottom: 1px solid #e5e7eb; }
    th { background: #f9fafb; color: #1f2933; font-weight: 700; font-size: 11px; text-transform: uppercase; }
    td input { width: 100%; padding: 8px 10px; text-align: center; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; }
    td input:focus { border-color: #8a0154; box-shadow: 0 0 0 2px rgba(138,1,84,0.12); }
    td .btn { padding: 5px 12px; font-size: 11px; margin: 2px; }
    .btn-remove { background: #fff; color: #8a0154; border: 1px solid #e5e7eb; }
    .btn-remove:hover { background: #fdf2f8; border-color: #8a0154; }
  </style>
</head>
<body>
  <header>
    <div class="logo"><a href="admin_home.php">★<strong> TalaTrade</strong></a></div>
    <nav class="nav-links">
      <a href="admin_home.php">Home</a>
      <a href="admin_product.php">Products</a>
      <a href="admin_order.php">Orders</a>
      <a href="admin_discount.php" class="active">Discounts</a>
    </nav>
    <div class="actions">
      <a href="logout.php" class="btn btn-outline">Log Out</a>
    </div>
  </header>

  <main class="admin-main">
    <h2 style="font-size: 22px; color: #1f2933; margin-bottom: 20px;">Discount Information</h2>

    <div class="top-btn">
      <form method="POST" style="margin:0;">
        <button type="submit" name="add" class="btn">ADD DISCOUNT</button>
      </form>
    </div>

    <?php
      $result = $conn->query("SELECT * FROM discounts");
      if ($result && $result->num_rows > 0) {
          $addingNew = isset($_POST['add']);
          echo "<table>";
          echo "<thead><tr><th>Discount ID</th><th>Discounted Amount</th><th>Discount Name</th><th>Options</th></tr></thead>";
          echo "<tbody>";
          
          if ($addingNew) {
              echo "<tr><form method='POST'>";
              echo "<td>New</td>";
              echo "<td><input type='text' name='discountedamount' placeholder='e.g. 0.20' required></td>";
              echo "<td><input type='text' name='discountname' placeholder='e.g. Senior Discount' required></td>";
              echo "<td><button type='submit' name='save_new' class='btn'>SAVE</button><button type='submit' class='btn btn-outline'>CANCEL</button></td>";
              echo "</form></tr>";
          }

          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr><form method='POST'>";
              echo "<td><input type='hidden' name='discountID' value='{$row['discountID']}' />{$row['discountID']}</td>";
              echo "<td><input type='text' name='discountedamount' value='" . htmlspecialchars($row['discountedamount']) . "' required></td>";
              echo "<td><input type='text' name='discountname' value='" . htmlspecialchars($row['discountname']) . "' required></td>";
              echo "<td><button type='submit' name='update' class='btn'>UPDATE</button><button type='submit' name='delete' class='btn btn-remove' onclick=\"return confirm('Delete this discount?');\">REMOVE</button></td>";
              echo "</form></tr>";
          }
          echo "</tbody></table>";
      } else {
          echo "<p>No discounts found.</p>";
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