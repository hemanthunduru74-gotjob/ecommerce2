
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
$success = "";

/* FETCH CURRENT USER DATA */
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$current_name  = $user['name'] ?? '';
$current_email = $user['email'] ?? '';

/* UPDATE PROFILE */
if (isset($_POST['update_profile'])) {
    $new_name  = trim($_POST['name']);
    $new_email = trim($_POST['email']);

    if ($new_name && $new_email) {
        $stmt = $conn->prepare(
            "UPDATE users SET name = ?, email = ? WHERE id = ?"
        );
        $stmt->execute([$new_name, $new_email, $user_id]);

        $success = "Profile updated successfully ✔";

        // refresh values
        $current_name = $new_name;
        $current_email = $new_email;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Profile</title>

<style>
body{
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#667eea,#764ba2);
}

.card{
    width:420px;
    background:rgba(255,255,255,0.9);
    backdrop-filter:blur(18px);
    padding:35px;
    border-radius:20px;
    box-shadow:0 25px 60px rgba(0,0,0,.3);
    animation:fadeUp .6s ease;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(30px);}
    to{opacity:1; transform:translateY(0);}
}

h2{
    text-align:center;
    margin-bottom:20px;
}

.section{
    margin-bottom:18px;
}

.label{
    font-size:12px;
    color:#777;
    margin-bottom:6px;
}

input{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:14px;
}

input[readonly]{
    background:#f1f1f1;
}

button{
    width:100%;
    padding:12px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#00c853,#64dd17);
    color:white;
    font-size:15px;
    cursor:pointer;
    margin-top:10px;
}

button:hover{
    opacity:.9;
}

.success{
    background:#e6fffa;
    color:#065f46;
    padding:10px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
    font-size:14px;
}

.back{
    display:block;
    text-align:center;
    margin-top:15px;
    color:#555;
    text-decoration:none;
}
</style>
</head>

<body>

<div class="card">

    <h2>✏️ Edit Profile</h2>

    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <!-- OLD DETAILS -->
    <div class="section">
        <div class="label">Current Name</div>
        <input type="text" value="<?= htmlspecialchars($current_name) ?>" readonly>
    </div>

    <div class="section">
        <div class="label">Current Email</div>
        <input type="email" value="<?= htmlspecialchars($current_email) ?>" readonly>
    </div>

    <hr style="margin:20px 0;">

    <!-- NEW DETAILS -->
    <form method="POST">
        <div class="section">
            <div class="label">New Name</div>
            <input type="text" name="name" required placeholder="Enter new name">
        </div>

        <div class="section">
            <div class="label">New Email</div>
            <input type="email" name="email" required placeholder="Enter new email">
        </div>

        <button type="submit" name="update_profile">
            Update Profile
        </button>
    </form>

    <a href="profile.php" class="back">← Back to Profile</a>

</div>

</body>
</html>
