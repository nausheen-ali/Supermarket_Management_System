<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "supermarket");

// Handle Add to Cart
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = mysqli_query($conn, "SELECT * FROM products WHERE ProductID = '$id'");
    $product = mysqli_fetch_assoc($result);

    if ($product) {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        $found = false;
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantity']++;
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['cart'][] = [
                'id' => $product['ProductID'],
                'name' => $product['ProductName'],
                'price' => $product['Price'],
                'quantity' => 1
            ];
        }

        header("Location: index.php?cart");
        exit();
    }
}

// Remove from Cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $_GET['id']) {
            unset($_SESSION['cart'][$key]);
            break;
        }
    }
    header("Location: index.php?cart");
    exit();
}

// Update Cart
if (isset($_POST['update_qty'])) {
    foreach ($_POST['qty'] as $id => $qty) {
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['id'] == $id) {
                $item['quantity'] = max(1, intval($qty));
                break;
            }
        }
    }
    header("Location: index.php?cart");
    exit();
}

// Checkout
if (isset($_GET['action']) && $_GET['action'] == 'checkout') {
    $_SESSION['cart'] = [];
    $_SESSION['order_placed'] = true;
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Supermarket Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <div class="logo">🌿 Supermarket</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="index.php?categories">Categories</a>
            <a href="index.php?offers">Offers</a>
            <a href="index.php?cart">🛒 Cart (<?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>)</a>
        </div>
    </nav>

    <?php if (isset($_SESSION['order_placed'])): ?>
        <div class="confirmation">
            <h2>🎉 Order Placed Successfully!</h2>
            <p>Thank you for shopping with us.</p>
        </div>
        <?php unset($_SESSION['order_placed']); ?>

    <?php elseif (isset($_GET['cart'])): ?>
        <h3 class="p_title"><a href="index.php">← Back</a> 🛒 Your Cart</h3>
        <div class="cart_container">
            <?php if (!empty($_SESSION['cart'])): ?>
                <form method="post" action="index.php?cart">
                    <table>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                        <?php
                        $total = 0;
                        foreach ($_SESSION['cart'] as $item):
                            $subtotal = $item['price'] * $item['quantity'];
                            $total += $subtotal;
                        ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td>₹<?= $item['price'] ?></td>
                            <td><input type="number" name="qty[<?= $item['id'] ?>]" value="<?= $item['quantity'] ?>" min="1"></td>
                            <td>₹<?= $subtotal ?></td>
                            <td><a href="index.php?action=remove&id=<?= $item['id'] ?>">Remove</a></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr>
                            <th colspan="3">Total</th>
                            <th>₹<?= $total ?></th>
                            <td></td>
                        </tr>
                    </table>
                    <button class="checkout-btn" type="submit" name="update_qty">Update Cart</button>
                    <a class="checkout-btn" href="index.php?action=checkout">Checkout</a>
                </form>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>

    <?php elseif (isset($_GET['offers'])): ?>
        <h3 class="p_title"><a href="index.php">← Back</a> </h3>
        <h3 class="p_title">Offers Available</h3>
        <div class="my_card">
            <?php
            $sql = "SELECT p.*, s.QUANTITYAVAILABLE, o.Discount, o.QuantityReqd, o.OfferDescription 
                    FROM products p 
                    JOIN offers o ON p.ProductID = o.ProductID 
                    LEFT JOIN stocks s ON p.ProductID = s.ProductID";
            $result = mysqli_query($conn, $sql);
            
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $inStock = $row['QUANTITYAVAILABLE'] > 0;
                    echo '<div class="card" onclick="location.href=\'index.php?product=' . $row['ProductID'] . '\'' . '">';
                    echo '<img class="p_image" src="image/' . $row['imageURL'] . '">';
                    echo '<h4>' . $row['ProductName'] . '</h4>';
                    echo '<p>' . $row['Description'] . '</p>';
                    echo '<p class="price">₹' . $row['Price'] . '</p>';
                    echo '<p>' . $row['OfferDescription'] . '</p>';

                    if ($inStock) {
                        echo '<a href="index.php?action=add&id=' . $row['ProductID'] . '" class="checkout-btn" onclick="event.stopPropagation();">Buy Now</a>';
                    } else {
                        echo '<button class="checkout-btn out-of-stock" disabled onclick="event.stopPropagation();">Out of Stock</button>';
                    }

                    echo '</div>';
                }
            } else {
                echo '<p>No offers available at the moment.</p>';
            }
            ?>
        </div>

    <?php elseif (isset($_GET['categories'])): ?>
        <h3 class="p_title">Browse Categories</h3>
        <div class="category-container">
            <?php
            $cats = mysqli_query($conn, "SELECT DISTINCT Category FROM products");
            while ($cat = mysqli_fetch_assoc($cats)) {
                echo '<div class="category-card"><a href="index.php?category=' . urlencode($cat['Category']) . '">' . $cat['Category'] . '</a></div>';
            }
            ?>
        </div>

    <?php elseif (isset($_GET['category'])): ?>
        <h3 class="p_title"><a href="index.php?categories">← Back</a> Category: <?= htmlspecialchars($_GET['category']) ?></h3>
        <div class="my_card">
            <?php
            $cat = mysqli_real_escape_string($conn, $_GET['category']);
            $sql = "SELECT p.*, s.QUANTITYAVAILABLE FROM products p LEFT JOIN stocks s ON p.ProductID = s.PRODUCTID WHERE p.Category = '$cat'";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $inStock = $row['QUANTITYAVAILABLE'] > 0;
                echo '<div class="card" onclick="location.href=\'index.php?product=' . $row['ProductID'] . '\'' . '">';
                echo '<img class="p_image" src="image/' . $row['imageURL'] . '">';
                echo '<h4>' . $row['ProductName'] . '</h4>';
                echo '<p>' . $row['Description'] . '</p>';
                echo '<p class="price">₹' . $row['Price'] . '</p>';
                if ($inStock) {
                    echo '<a href="index.php?action=add&id=' . $row['ProductID'] . '" class="checkout-btn" onclick="event.stopPropagation();">Buy Now</a>';
                } else {
                    echo '<button class="checkout-btn out-of-stock" disabled onclick="event.stopPropagation();">Out of Stock</button>';
                }
                echo '</div>';
            }
            ?>
        </div>

    <?php elseif (isset($_GET['product'])): ?>
        <?php
        $id = intval($_GET['product']);
        $sql = "SELECT p.*, s.QUANTITYAVAILABLE, o.Discount, o.QuantityReqd, o.StartDate, o.EndDate, o.OfferDescription FROM products p 
                LEFT JOIN stocks s ON p.ProductID = s.ProductID 
                LEFT JOIN offers o ON p.ProductID = o.ProductID 
                WHERE p.ProductID = $id";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        ?>
        <h3 class="p_title"><a href="index.php">← Back</a> Product Details</h3>
        <div class="card">
            <img class="p_image" src="image/<?= $row['imageURL'] ?>">
            <h4><?= $row['ProductName'] ?></h4>
            <p><?= $row['Description'] ?></p>
            <p>Supplier: <?= $row['Supplier'] ?></p>
            <p class="price">Price: ₹<?= $row['Price'] ?></p>
            <p>Available: <?= $row['QUANTITYAVAILABLE'] ?> items</p>

            <?php if ($row['Discount']): ?>
                <div class="offer-box">
                    <p><strong>Offer:</strong> <?= $row['OfferDescription'] ?></p>
                    <p>Valid till <?= $row['EndDate'] ?></p>
                </div>
            <?php endif; ?>

            <?php if ($row['QUANTITYAVAILABLE'] > 0): ?>
                <a href="index.php?action=add&id=<?= $row['ProductID'] ?>" class="checkout-btn">Buy Now</a>
            <?php else: ?>
                <button class="checkout-btn out-of-stock" disabled>Out of Stock</button>
            <?php endif; ?>
        </div>

    <?php else: ?>
        <h3 class="p_title">🛒 Our Fresh Picks</h3>
        <div class="my_card">
            <?php
            $sql = "SELECT p.*, s.QUANTITYAVAILABLE FROM products p LEFT JOIN stocks s ON p.ProductID = s.ProductID";
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_assoc($result)) {
                $inStock = $row['QUANTITYAVAILABLE'] > 0;
                echo '<div class="card" onclick="location.href=\'index.php?product=' . $row['ProductID'] . '\'' . '">';
                echo '<img class="p_image" src="image/' . $row['imageURL'] . '">';
                echo '<h4>' . $row['ProductName'] . '</h4>';
                echo '<p>' . $row['Description'] . '</p>';
                echo '<p class="price">₹' . $row['Price'] . '</p>';
                if ($inStock) {
                    echo '<a href="index.php?action=add&id=' . $row['ProductID'] . '" class="checkout-btn" onclick="event.stopPropagation();">Buy Now</a>';
                } else {
                    echo '<button class="checkout-btn out-of-stock" disabled onclick="event.stopPropagation();">Out of Stock</button>';
                }
                echo '</div>';
            }
            ?>
        </div>
    <?php endif; ?>
</body>
</html>