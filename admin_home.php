<?php
include 'db.php';
session_start();

if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
$userID = $_SESSION['userID'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Admin Home</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
  <header>
    <div class="logo"><a href="admin_home.php">★<strong> TalaTrade</strong></a></div>
    <nav class="nav-links">
      <a href="admin_home.php" class="active">Home</a>
      <a href="admin_product.php">Products</a>
      <a href="admin_order.php">Orders</a>
      <a href="admin_discount.php">Discounts</a>
    </nav>
    <div class="actions">
      <a href="logout.php" class="btn btn-outline">Log Out</a>
    </div>
  </header>

  <section class="banner">
    <div class="banner-content">
      <p class="banner-eyebrow">Admin Dashboard</p>
      <h1>May's Sari-Sari Store</h1>
      <form method="POST" action="" class="search-bar">
        <input type="search" name="search" placeholder="Search for products, brands, or categories..." />
        <button type="submit" name="submit" class="btn">SEARCH</button>
        <button type="submit" name="clear" class="btn btn-outline">CLEAR</button>
      </form>
    </div>
  </section>

  <div class="category-menu">
    <a href="admin_home.php" class="<?php echo !isset($_GET['category']) ? 'active' : ''; ?>">All Products</a>
    <a href="admin_home.php?category=Cooking" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Cooking') ? 'active' : ''; ?>">Cooking</a>
    <a href="admin_home.php?category=Beverage" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Beverage') ? 'active' : ''; ?>">Beverage</a>
    <a href="admin_home.php?category=Biscuits" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Biscuits') ? 'active' : ''; ?>">Biscuits</a>
    <a href="admin_home.php?category=Canned Goods" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Canned Goods') ? 'active' : ''; ?>">Canned Goods</a>
    <a href="admin_home.php?category=Instant Drinks" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Instant Drinks') ? 'active' : ''; ?>">Instant Drinks</a>
    <a href="admin_home.php?category=Candy" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Candy') ? 'active' : ''; ?>">Candy</a>
    <a href="admin_home.php?category=Rice" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Rice') ? 'active' : ''; ?>">Rice</a>
    <a href="admin_home.php?category=Diapers" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Diapers') ? 'active' : ''; ?>">Diapers</a>
    <a href="admin_home.php?category=Laundry" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Laundry') ? 'active' : ''; ?>">Laundry</a>
    <a href="admin_home.php?category=Personal Hygiene" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Personal Hygiene') ? 'active' : ''; ?>">Hygiene</a>
  </div>

  <main class="products-grid">
    <?php
      if (isset($_POST['clear'])) {
          header("Location: admin_home.php");
          exit();
      }

      if (isset($_POST['submit']) && !empty($_POST['search'])) {
          $search = "%" . $_POST['search'] . "%";
          $stmt = $conn->prepare("SELECT * FROM products WHERE productname LIKE ? AND stockquantity > 0");
          $stmt->bind_param("s", $search);
          $stmt->execute();
          $result = $stmt->get_result();
      } elseif (isset($_GET['category']) && !empty($_GET['category'])) {
          $selectedCategory = $_GET['category'];
          $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? AND stockquantity > 0");
          $stmt->bind_param("s", $selectedCategory);
          $stmt->execute();
          $result = $stmt->get_result();
      } else {
          $result = $conn->query("SELECT * FROM products WHERE stockquantity > 0");
      }

      if ($result && $result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<div class='product-card'>";
              echo "<div class='product-image-wrap'><img src='products/" . htmlspecialchars($row['img']) . "' alt='Product Image'></div>";
              echo "<p class='product-category'>" . htmlspecialchars($row['category']) . "</p>";
              echo "<h3>" . htmlspecialchars($row['productname']) . "</h3>";
              echo "<p class='price'>₱" . number_format($row['price'], 2) . "</p>";
              echo "<div class='product-actions'>";
              echo "<a href='admin_product.php?edit=" . $row['productID'] . "' class='btn'>EDIT PRODUCT</a>";
              echo "</div></div>";
          }
      } else {
          echo "<p style='grid-column: 1/-1; text-align: center; color: #6b7280;'>No products found.</p>";
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