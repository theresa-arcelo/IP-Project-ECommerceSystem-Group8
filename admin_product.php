<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_new'])) {
        $productname = $_POST['productname'];
        $category = $_POST['category'];
        $price = $_POST['price'];
        $stockquantity = $_POST['stockquantity'];
        $img = basename($_FILES["img_file"]["name"]);

        $stmt = $conn->prepare("INSERT INTO products (productname, category, price, stockquantity, img) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $productname, $category, $price, $stockquantity, $img);
        $stmt->execute();
        move_uploaded_file($_FILES["img_file"]["tmp_name"], "products/" . $img);
        header("Location: admin_product.php");
        exit();
    }

    if (isset($_POST['update']) || isset($_POST['delete'])) {
        $productID = $_POST['productID'];
        if (isset($_POST['update'])) {
            $productname = $_POST['productname'];
            $category = $_POST['category'];
            $price = $_POST['price'];
            $stockquantity = $_POST['stockquantity'];
            $img = !empty($_FILES["img_file"]["name"]) ? basename($_FILES["img_file"]["name"]) : $_POST['existing_img'];

            $stmt = $conn->prepare("UPDATE products SET productname=?, category=?, price=?, stockquantity=?, img=? WHERE productID=?");
            $stmt->bind_param("ssdisi", $productname, $category, $price, $stockquantity, $img, $productID);
            $stmt->execute();
            if (!empty($_FILES["img_file"]["name"])) {
                move_uploaded_file($_FILES["img_file"]["tmp_name"], "products/" . $img);
            }
        }
        if (isset($_POST['delete'])) {
            $stmt = $conn->prepare("DELETE FROM products WHERE productID=?");
            $stmt->bind_param("i", $productID);
            $stmt->execute();
        }
        header("Location: admin_product.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TalaTrade - Manage Products</title>
  <link rel="stylesheet" href="css/style.css" />
  <link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;500;700&display=swap" rel="stylesheet">
  <style>
    .admin-search { display: flex; gap: 10px; justify-content: center; margin: 30px 0 20px; flex-wrap: wrap; }
    .admin-search input { width: 380px; max-width: 100%; padding: 11px 20px; border-radius: 8px; border: 1px solid #e5e7eb; outline: none; }
    .admin-search input:focus { border-color: #8a0154; box-shadow: 0 0 0 3px rgba(138,1,84,0.12); }
    .admin-main { width: 95%; max-width: 1400px; margin: 0 auto 40px; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 16px rgba(16,24,40,0.08); }
    th, td { padding: 10px; font-size: 13px; text-align: center; vertical-align: middle; border-bottom: 1px solid #e5e7eb; }
    th { background: #f9fafb; color: #1f2933; font-weight: 700; font-size: 11px; text-transform: uppercase; }
    td input[type="text"], td input[type="number"] { width: 100%; padding: 8px; text-align: center; border: 1px solid #e5e7eb; border-radius: 6px; outline: none; }
    td input:focus { border-color: #8a0154; box-shadow: 0 0 0 2px rgba(138,1,84,0.12); }
    td img { width: 64px; height: 64px; object-fit: cover; border-radius: 8px; border: 1px solid #e5e7eb; margin-bottom: 5px; }
    td .btn { padding: 6px 12px; font-size: 11px; margin: 2px; }
    .btn-remove { background: #fff; color: #8a0154; border: 1px solid #e5e7eb; }
    .btn-remove:hover { background: #fdf2f8; border-color: #8a0154; color: #8a0154; }
  </style>
</head>
<body>
  <header>
    <div class="logo"><a href="admin_home.php">★<strong> TalaTrade</strong></a></div>
    <nav class="nav-links">
      <a href="admin_home.php">Home</a>
      <a href="admin_product.php" class="active">Products</a>
      <a href="admin_order.php">Orders</a>
      <a href="admin_discount.php">Discounts</a>
    </nav>
    <div class="actions">
      <a href="logout.php" class="btn btn-outline">Log Out</a>
    </div>
  </header>

  <main class="admin-main">
    <div style="margin-top: 30px;">
      <h2 style="font-size: 22px; color: #1f2933;">Product Information</h2>
    </div>

    <div class="admin-search">
      <form method="POST" action="" enctype="multipart/form-data" style="display:flex; gap:10px; flex-wrap:wrap; justify-content:center;">
        <input type="search" name="search" placeholder="Search Products" />
        <button type="submit" name="submit" class="btn">SEARCH</button>
        <button type="submit" name="clear" class="btn btn-outline">CLEAR</button>
        <button type="submit" name="add" class="btn">ADD PRODUCT</button>
      </form>
    </div>

    <div class="category-menu" style="border-radius: 10px; margin-bottom: 20px;">
      <a href="admin_product.php" class="<?php echo !isset($_GET['category']) ? 'active' : ''; ?>">All</a>
      <?php
        $categories = ['Cooking', 'Beverage', 'Biscuits', 'Canned Goods', 'Instant Drinks', 'Candy', 'Rice', 'Diapers', 'Laundry', 'Personal Hygiene'];
        $selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
        foreach ($categories as $cat) {
            echo "<a href='admin_product.php?category=" . urlencode($cat) . "' class='" . ($selectedCategory == $cat ? 'active' : '') . "'>" . ($cat == 'Personal Hygiene' ? 'Hygiene' : $cat) . "</a>";
        }
      ?>
    </div>

    <?php
      if (isset($_POST['clear'])) {
          header("Location: admin_product.php");
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
          $addingNew = isset($_POST['add']);
          echo "<table>";
          echo "<thead><tr><th>Product ID</th><th>Product Name</th><th>Category</th><th>Price</th><th>Stock</th><th>Image</th><th>Options</th></tr></thead>";
          echo "<tbody>";
          
          if ($addingNew) {
              echo "<tr><form method='POST' enctype='multipart/form-data'>";
              echo "<td>New</td>";
              echo "<td><input type='text' name='productname' placeholder='Name' required></td>";
              echo "<td><input type='text' name='category' placeholder='Category' required></td>";
              echo "<td><input type='number' step='0.01' name='price' placeholder='Price' required></td>";
              echo "<td><input type='number' name='stockquantity' placeholder='Qty' required></td>";
              echo "<td><input type='file' name='img_file' accept='image/*' required></td>";
              echo "<td><button type='submit' name='save_new' class='btn'>SAVE</button><button type='submit' class='btn btn-outline'>CANCEL</button></td>";
              echo "</form></tr>";
          }

          while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr><form method='POST' enctype='multipart/form-data'>";
              echo "<td><input type='hidden' name='productID' value='{$row['productID']}' />{$row['productID']}</td>";
              echo "<td><input type='text' name='productname' value='" . htmlspecialchars($row['productname']) . "' required></td>";
              echo "<td><input type='text' name='category' value='" . htmlspecialchars($row['category']) . "' required></td>";
              echo "<td><input type='number' step='0.01' name='price' value='{$row['price']}' required></td>";
              echo "<td><input type='number' name='stockquantity' value='{$row['stockquantity']}' required></td>";
              echo "<td><img src='products/" . htmlspecialchars($row['img']) . "' /><input type='file' name='img_file' accept='image/*'><input type='hidden' name='existing_img' value='" . htmlspecialchars($row['img']) . "'></td>";
              echo "<td><button type='submit' name='update' class='btn'>UPDATE</button><button type='submit' name='delete' class='btn btn-remove' onclick=\"return confirm('Delete this product?');\">REMOVE</button></td>";
              echo "</form></tr>";
          }
          echo "</tbody></table>";
      } else {
          echo "<p style='text-align:center;'>No products found.</p>";
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