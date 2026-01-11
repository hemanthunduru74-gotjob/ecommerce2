<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../includes/db.php';

$user_id = $_SESSION['user_id'];

/* ======================
   FETCH CART TOTAL
====================== */
$stmt = $conn->prepare("
    SELECT p.price, c.quantity
    FROM cart c
    JOIN products p ON p.id = c.product_id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($items as $i) {
    $total += $i['price'] * $i['quantity'];
}

/* ======================
   HANDLE PAYMENT
====================== */
$success = false;
if (isset($_POST['pay_now'])) {
    // demo payment success
    $conn->prepare("DELETE FROM cart WHERE user_id=?")->execute([$user_id]);
    $success = true;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Checkout</title>
<style>
body{
    font-family:Arial;
    background:#f4f6f8;
}
.box{
    width:450px;
    margin:60px auto;
    background:#fff;
    padding:30px;
    border-radius:12px;
    box-shadow:0 10px 30px rgba(0,0,0,.1);
}
h2{text-align:center}
input, textarea, select, button{
    width:100%;
    padding:12px;
    margin-top:10px;
    border-radius:6px;
    border:1px solid #ccc;
}
button{
    background:#28a745;
    color:white;
    font-size:16px;
    border:none;
    cursor:pointer;
}
.success{
    text-align:center;
}
.success h2{color:green}
.back{
    display:block;
    margin-top:20px;
    text-align:center;
    text-decoration:none;
    color:#0d6efd;
}
.total{
    font-size:18px;
    text-align:center;
    margin:15px 0;
    font-weight:bold;
}
</style>
</head>
<body>

<div class="box">

<?php if ($success): ?>
    <div class="success">
        <h2>✅ Payment Successful</h2>
        <p>Your order has been placed successfully.</p>
        <a class="back" href="index.php">Back to Shop</a>
    </div>

<?php else: ?>
    <h2>Checkout</h2>

    <div class="total">Total Amount: ₹<?= number_format($total,2) ?></div>

    <form method="POST">
        <input type="text" placeholder="Full Name" required>
        <textarea placeholder="Delivery Address" required></textarea>

        <select required>
            <option value="">Select Payment Method</option>
            <option>UPI</option>
            <option>Credit Card</option>
            <option>Debit Card</option>
            <option>Cash on Delivery</option>
        </select>

        <button type="submit" name="pay_now">Pay Now</button>
    </form>

    <a class="back" href="cart.php">← Back to Cart</a>
<?php endif; ?>

</div>

</body>
</html>