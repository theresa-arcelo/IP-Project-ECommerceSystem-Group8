<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Product Details</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .product-page { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
    .product-main { display: flex; gap: 40px; background: #fff; padding: 30px; border-radius: 12px; box-shadow: 0 4px 16px rgba(16,24,40,0.06); border: 1px solid #e5e7eb; }
    .product-image { flex: 1; }
    .product-image img { width: 100%; height: 400px; object-fit: cover; border-radius: 10px; background: #f3f4f6; }
    .product-details { flex: 1; display: flex; flex-direction: column; justify-content: center; gap: 15px; }
    .product-details h1 { font-size: 32px; color: #1f2933; }
    .product-details .subheading { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #8a0154; font-weight: 700; }
    .product-details .price { font-size: 36px; font-weight: 700; color: #8a0154; }
    .product-details .add-to-cart { width: 100%; padding: 14px; font-size: 16px; margin-top: 10px; }
    .product-details .fine-print { font-size: 13px; color: #6b7280; margin-top: 10px; }
    .related-products { margin-top: 50px; }
    .related-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .related-card { background: #fff; padding: 15px; border-radius: 10px; border: 1px solid #e5e7eb; text-align: center; box-shadow: 0 1px 3px rgba(16,24,40,0.06); }
    .related-card .related-image { height: 140px; overflow: hidden; border-radius: 8px; margin-bottom: 10px; }
    .related-card .related-image img { width: 100%; height: 100%; object-fit: cover; }
    .related-card h4 { font-size: 14px; color: #1f2933; margin: 8px 0; min-height: 36px; }
    .related-card .price { font-size: 18px; font-weight: 700; color: #8a0154; }
    @media (max-width: 900px) {
      .product-main { flex-direction: column; }
      .related-grid { grid-template-columns: repeat(2, 1fr); }
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

  <main>
    <?php
      include 'db.php';
      if (isset($_GET['productID']) && !empty($_GET['productID'])) {
          $productID = $_GET['productID'];
          $stmt = $conn->prepare("SELECT * FROM products WHERE productID = ?");
          $stmt->bind_param("i", $productID);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result && $result->num_rows > 0) {
              $product = $result->fetch_assoc();
              echo "<div class='product-page'>";
              echo "<section class='product-main'>";
              echo "<div class='product-image'><img src='products/" . htmlspecialchars($product['img']) . "' alt='Product Image'></div>";
              echo "<div class='product-details'>";
              echo "<p class='subheading'>" . htmlspecialchars($product['category']) . "</p>";
              echo "<h1>" . htmlspecialchars($product['productname']) . "</h1>";
              echo "<p class='price'>₱" . number_format($product['price'], 2) . "</p>";
              echo "<form method='POST' action='user_cart.php'>";
              echo "<input type='hidden' name='productID' value='" . $product['productID'] . "'>";
              echo "<input type='hidden' name='quantity' value='1'>";
              echo "<button type='submit' name='add_to_cart' class='btn add-to-cart'>ADD TO CART</button>";
              echo "</form>";
              echo "<p class='fine-print'>Only " . (int)$product['stockquantity'] . " left in stock!</p>";
              echo "</div></section>";
              echo "<hr style='border: none; border-top: 1px solid #e5e7eb; margin: 40px 0;'>";

              $category = $product['category'];
              $stmt_rel = $conn->prepare("SELECT * FROM products WHERE category = ? AND productID != ? AND stockquantity > 0 LIMIT 4");
              $stmt_rel->bind_param("si", $category, $productID);
              $stmt_rel->execute();
              $related_result = $stmt_rel->get_result();

              echo "<section class='related-products'>";
              echo "<h2 style='color:#1f2933; margin-bottom:20px; font-size:22px;'>Related Products</h2>";
              echo "<div class='related-grid'>";
              if ($related_result && $related_result->num_rows > 0) {
                  while ($related = $related_result->fetch_assoc()) {
                      echo "<div class='related-card'>";
                      echo "<div class='related-image'><img src='products/" . htmlspecialchars($related['img']) . "' alt='Related Product'></div>";
                      echo "<p class='price'>₱" . number_format($related['price'], 2) . "</p>";
                      echo "<h4>" . htmlspecialchars($related['productname']) . "</h4>";
                      echo "<a href='user_product.php?productID=" . $related['productID'] . "' class='btn' style='width:100%; margin-bottom:5px;'>VIEW DETAILS</a>";
                      echo "<form method='POST' action='user_cart.php' style='margin:0;'>";
                      echo "<input type='hidden' name='productID' value='" . $related['productID'] . "'>";
                      echo "<input type='hidden' name='quantity' value='1'>";
                      echo "<button type='submit' name='add_to_cart' class='btn' style='width:100%;'>ADD TO CART</button>";
                      echo "</form>";
                      echo "</div>";
                  }
              } else {
                  echo "<p>No related products found.</p>";
              }
              echo "</div></section></div>";
          } else {
              echo "<div style='text-align:center; padding: 50px;'><p>Product not found.</p></div>";
          }
      } else {
          echo "<div style='text-align:center; padding: 50px;'><p>No product selected.</p></div>";
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