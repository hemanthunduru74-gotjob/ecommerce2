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
$message = "";
$error = "";

if (isset($_POST['update_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];

    // Fetch current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($old_password, $user['password'])) {
        $error = "❌ Old password is incorrect";
    } else {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$hashed, $user_id]);
        $message = "✅ Password updated successfully";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Change Password</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#667eea,#764ba2);
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    background:white;
    width:480px;
    padding:30px;
    border-radius:16px;
    box-shadow:0 25px 60px rgba(0,0,0,0.3);
    animation:fadeIn .6s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:translateY(30px)}
    to{opacity:1; transform:translateY(0)}
}

.card h2{
    text-align:center;
    margin-bottom:20px;
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

label{
    font-size:14px;
    color:#444;
}

input{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:14px;
}

.full{
    grid-column:1 / -1;
}

.btn{
    margin-top:10px;
    padding:12px;
    background:linear-gradient(135deg,#00c853,#64dd17);
    border:none;
    color:white;
    border-radius:30px;
    font-size:15px;
    cursor:pointer;
    width:100%;
}

.btn:hover{
    transform:scale(1.03);
}

.msg{
    text-align:center;
    margin-bottom:10px;
    font-weight:600;
}

.error{color:#d32f2f;}
.success{color:#2e7d32;}

.back{
    text-align:center;
    margin-top:15px;
}

.back a{
    text-decoration:none;
    color:#555;
}
</style>
</head>

<body>

<div class="card">
    <h2>🔐 Change Password</h2>

    <?php if($error): ?>
        <div class="msg error"><?= $error ?></div>
    <?php endif; ?>

    <?php if($message): ?>
        <div class="msg success"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-grid">
            <div>
                <label>Old Password</label>
                <input type="password" name="old_password" required>
            </div>

            <div>
                <label>New Password</label>
                <input type="password" name="new_password" required>
            </div>

            <div class="full">
                <button class="btn" name="update_password">
                    Update Password
                </button>
            </div>
        </div>
    </form>

    <div class="back">
        <a href="index.php">← Back to Store</a>
    </div>
</div>

</body>
</html>