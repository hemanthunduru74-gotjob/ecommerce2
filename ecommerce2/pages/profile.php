<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$name  = $user['name'] ?? 'User';
$email = $user['email'] ?? 'Not Available';

$initial = strtoupper(substr($name, 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Profile</title>

<style>
/* ===== RESET ===== */
*{
    box-sizing:border-box;
    margin:0;
    padding:0;
}

/* ===== BODY ===== */
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#667eea,#764ba2);
    overflow:hidden;
}

/* ===== BACKGROUND BLUR ===== */
.bg-circle{
    position:absolute;
    width:400px;
    height:400px;
    background:rgba(255,255,255,0.15);
    border-radius:50%;
    filter:blur(60px);
    animation:float 8s infinite alternate ease-in-out;
}

.bg-circle.one{ top:-100px; left:-100px; }
.bg-circle.two{ bottom:-120px; right:-120px; animation-delay:2s; }

@keyframes float{
    from{ transform:translateY(0); }
    to{ transform:translateY(40px); }
}

/* ===== CARD ===== */
.profile-card{
    position:relative;
    width:420px;
    padding:40px 30px;
    background:rgba(255,255,255,0.85);
    backdrop-filter:blur(20px);
    border-radius:22px;
    box-shadow:0 30px 70px rgba(0,0,0,0.25);
    text-align:center;
    animation:slideUp .7s ease;
}

@keyframes slideUp{
    from{ opacity:0; transform:translateY(40px); }
    to{ opacity:1; transform:translateY(0); }
}

/* ===== AVATAR ===== */
.avatar{
    width:90px;
    height:90px;
    margin:0 auto 15px;
    border-radius:50%;
    background:linear-gradient(135deg,#667eea,#764ba2);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:38px;
    font-weight:bold;
    color:white;
    box-shadow:0 10px 30px rgba(0,0,0,0.25);
    animation:pulse 3s infinite;
}

@keyframes pulse{
    0%{ transform:scale(1); }
    50%{ transform:scale(1.05); }
    100%{ transform:scale(1); }
}

/* ===== TEXT ===== */
.profile-card h2{
    margin-top:10px;
    font-size:24px;
}

.profile-card .uid{
    font-size:13px;
    color:#666;
    margin-bottom:20px;
}

/* ===== INFO ===== */
.info{
    background:rgba(255,255,255,0.7);
    border-radius:14px;
    padding:18px;
    text-align:left;
    margin-bottom:20px;
}

.info-row{
    margin-bottom:14px;
}

.label{
    font-size:12px;
    color:#777;
}

.value{
    font-size:15px;
    font-weight:600;
}

/* ===== ACTION BUTTONS ===== */
.actions{
    display:flex;
    gap:12px;
}

.actions a{
    flex:1;
    padding:12px;
    border-radius:30px;
    text-decoration:none;
    text-align:center;
    font-size:14px;
    font-weight:600;
    color:white;
    transition:transform .3s, box-shadow .3s;
}

.actions a:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

.edit{
    background:linear-gradient(135deg,#00c853,#64dd17);
}

.pass{
    background:linear-gradient(135deg,#ff9800,#ff5722);
}

/* ===== BACK ===== */
.back{
    display:block;
    margin-top:18px;
    text-decoration:none;
    color:#555;
    font-size:14px;
}

.back:hover{
    text-decoration:underline;
}
</style>
</head>

<body>

<div class="bg-circle one"></div>
<div class="bg-circle two"></div>

<div class="profile-card">

    <div class="avatar"><?= $initial ?></div>

    <h2><?= htmlspecialchars($name) ?></h2>
    <div class="uid">User ID: <?= $user_id ?></div>

    <div class="info">
        <div class="info-row">
            <div class="label">Full Name</div>
            <div class="value"><?= htmlspecialchars($name) ?></div>
        </div>

        <div class="info-row">
            <div class="label">Email Address</div>
            <div class="value"><?= htmlspecialchars($email) ?></div>
        </div>
    </div>

    <div class="actions">
        <a href="edit_profile.php" class="edit">Edit Profile</a>
        <a href="change_password.php" class="pass">Change Password</a>
    </div>

    <a href="index.php" class="back">← Back to Store</a>

</div>

</body>
</html>