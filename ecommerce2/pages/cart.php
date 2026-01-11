<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'];

/* =========================
   ADD TO CART
========================= */
if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity   = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_id=? AND product_id=?");
    $stmt->execute([$user_id, $product_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $stmt = $conn->prepare(
            "UPDATE cart SET quantity = quantity + ? WHERE user_id=? AND product_id=?"
        );
        $stmt->execute([$quantity, $user_id, $product_id]);
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO cart (user_id, product_id, quantity) VALUES (?,?,?)"
        );
        $stmt->execute([$user_id, $product_id, $quantity]);
    }
}

/* =========================
   UPDATE QUANTITY
========================= */
if (isset($_POST['update_quantity'])) {
    $stmt = $conn->prepare(
        "UPDATE cart SET quantity=? WHERE user_id=? AND product_id=?"
    );
    $stmt->execute([
        (int)$_POST['quantity'],
        $user_id,
        (int)$_POST['product_id']
    ]);
}

/* =========================
   REMOVE ITEM
========================= */
if (isset($_POST['remove_from_cart'])) {
    $stmt = $conn->prepare(
        "DELETE FROM cart WHERE user_id=? AND product_id=?"
    );
    $stmt->execute([$user_id, (int)$_POST['product_id']]);
}

/* =========================
   FETCH CART
========================= */
$stmt = $conn->prepare("
    SELECT p.*, c.quantity
    FROM cart c
    JOIN products p ON p.id = c.product_id
    WHERE c.user_id=?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body{font-family:Arial;background:#f4f6f8}
        .box{width:80%;margin:40px auto;background:#fff;padding:25px;border-radius:10px}
        .item{display:flex;align-items:center;margin-bottom:20px}
        img{width:90px;border-radius:8px;margin-right:20px}
        .actions{margin-left:auto}
        button{padding:8px 12px;border:none;border-radius:5px;cursor:pointer}
        .update{background:#0d6efd;color:#fff}
        .remove{background:#dc3545;color:#fff}
        .total{font-size:22px;font-weight:bold;text-align:center}
        .links{display:flex;justify-content:space-between;margin-top:25px}
        a{padding:12px 20px;background:#28a745;color:#fff;border-radius:6px;text-decoration:none}
        .checkout{background:#ff9800}
    </style>
</head>
<body>

<div class="box">
    <h2>Your Cart</h2>

<?php if (!$items): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
<?php foreach ($items as $item):
    $total += $item['price'] * $item['quantity'];
?>
    <div class="item">
        <img src="../images/<?= htmlspecialchars($item['image']) ?>">
        <div>
            <b><?= htmlspecialchars($item['name']) ?></b><br>
            ₹<?= number_format($item['price'],2) ?> × <?= $item['quantity'] ?>
        </div>

        <div class="actions">
            <form method="post" style="display:inline">
                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                <button class="update" name="update_quantity">Update</button>
            </form>

            <form method="post" style="display:inline">
                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                <button class="remove" name="remove_from_cart">Remove</button>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<div class="total">Total: ₹<?= number_format($total,2) ?></div>
<?php endif; ?>

<div class="links">
    <!-- ✅ FIXED PATH -->
    <a href="index.php">Back to Shop</a>

    <!-- ✅ WILL WORK AFTER FILE CREATION -->
    <a href="checkout.php" class="checkout">Proceed to Checkout</a>
</div>

</div>
</body>
</html>