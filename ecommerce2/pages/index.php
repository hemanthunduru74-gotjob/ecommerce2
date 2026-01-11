<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../includes/db.php';

/* ===== AUTH CHECK ===== */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

/* ===== LOGOUT ===== */
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

/* ===== FETCH PRODUCTS ===== */
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>hemanth Online Store</title>

<style>
*{box-sizing:border-box}

body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#eef2f3,#d9e4f5);
}

/* ===== HEADER ===== */
.main-header{
    position:sticky;
    top:0;
    z-index:100;
    background:linear-gradient(135deg,#141e30,#243b55);
    color:white;
    padding:18px 30px;
    display:grid;
    grid-template-columns:auto 1fr auto;
    align-items:center;
}

/* STORE NAME */
.store-title{
    text-align:center;
    margin:0;
    font-size:26px;
}

/* RIGHT ACTIONS */
.right-actions{
    display:flex;
    align-items:center;
    gap:15px;
}

.cart-link{
    color:white;
    text-decoration:none;
    font-size:15px;
}

.logout-btn{
    background:#ff5c5c;
    border:none;
    padding:8px 14px;
    border-radius:6px;
    color:white;
    cursor:pointer;
}

/* PROFILE */
.profile-wrapper{
    position:relative;
}

.profile-circle{
    width:40px;
    height:40px;
    background:white;
    color:#333;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    font-size:18px;
}

.profile-dropdown{
    display:none;
    position:absolute;
    left:0;
    top:50px;
    background:white;
    width:180px;
    border-radius:8px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    overflow:hidden;
}

.profile-dropdown a{
    display:block;
    padding:12px;
    text-decoration:none;
    color:#333;
}

.profile-dropdown a:hover{
    background:#f1f1f1;
}

/* HERO */
.hero{
    padding:80px 30px;
    text-align:center;
    background:linear-gradient(135deg,#667eea,#764ba2);
    color:white;
}

.hero h2{
    font-size:40px;
}

/* PRODUCTS */
.container{
    padding:40px;
}

.product-list{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:30px;
}

.product{
    background:rgba(255,255,255,0.9);
    border-radius:18px;
    padding:20px;
    box-shadow:0 15px 35px rgba(0,0,0,0.12);
    transition:transform .3s;
}

.product:hover{
    transform:translateY(-8px);
}

.product img{
    width:100%;
    height:200px;
    object-fit:cover;
    border-radius:14px;
}

.price{
    font-size:18px;
    font-weight:bold;
    color:#2f855a;
}

.add-btn{
    width:100%;
    padding:12px;
    border-radius:30px;
    border:none;
    background:linear-gradient(135deg,#00c853,#64dd17);
    color:white;
    cursor:pointer;
}

/* FOOTER */
footer{
    margin-top:60px;
    background:#111;
    color:white;
    text-align:center;
    padding:18px;
}
</style>
</head>

<body>

<!-- ===== HEADER ===== -->
<header class="main-header">

    <!-- LEFT : PROFILE -->
    <div class="profile-wrapper">
        <div class="profile-circle" onclick="toggleProfile()">👤</div>
        <div class="profile-dropdown" id="profileMenu">
            <a href="profile.php">My Profile</a>
            <a href="edit_profile.php">Edit Profile</a>
            <a href="change_password.php">Change Password</a>
        </div>
    </div>

    <!-- CENTER : STORE NAME -->
    <h1 class="store-title">✨ hemanth Online Store</h1>

    <!-- RIGHT : CART + LOGOUT -->
    <div class="right-actions">
        <a href="cart.php" class="cart-link">Cart</a>
        <form method="POST">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>

</header>

<!-- HERO -->
<section class="hero">
    <h2>Shop Smarter. Live Better.</h2>
    <p>Premium products with smooth experience</p>
</section>

<!-- PRODUCTS -->
<div class="container">
    <div class="product-list">
        <?php foreach ($products as $product): ?>
            <div class="product">
                <img src="../images/<?= htmlspecialchars($product['image']) ?>">
               <h3><?= htmlspecialchars($product['product_name']) ?></h3>
                <p class="price">₹<?= number_format($product['price'],2) ?></p>
                <p><?= htmlspecialchars($product['description']) ?></p>
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                    <button type="submit" name="add_to_cart" class="add-btn">
                        Add to Cart
                    </button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<footer>
    <p>&copy; <?= date('Y') ?> hemanth Online Store</p>
</footer>

<!-- PROFILE DROPDOWN JS -->
<script>
function toggleProfile(){
    const menu=document.getElementById("profileMenu");
    menu.style.display = menu.style.display==="block"?"none":"block";
}

document.addEventListener("click",function(e){
    if(!e.target.closest(".profile-wrapper")){
        document.getElementById("profileMenu").style.display="none";
    }
});
</script>

</body>
</html>