<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/db.php';

$message = "";

if (isset($_POST['register'])) {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // check existing email
    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $message = "❌ Email already exists";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        $message = "✅ Registration successful. Please login.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>

<style>
body{
    margin:0;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:'Segoe UI',sans-serif;
    background:linear-gradient(135deg,#667eea,#764ba2);
}

.card{
    width:420px;
    background:white;
    padding:35px;
    border-radius:20px;
    box-shadow:0 30px 60px rgba(0,0,0,0.3);
    animation:fadeIn .6s ease;
}

@keyframes fadeIn{
    from{opacity:0; transform:scale(.9)}
    to{opacity:1; transform:scale(1)}
}

h2{
    text-align:center;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:18px;
}

label{
    font-size:14px;
    font-weight:600;
    color:#444;
    display:block;
    margin-bottom:6px;
}

input{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ccc;
    font-size:15px;
}

input:focus{
    outline:none;
    border-color:#667eea;
}

.btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:30px;
    background:linear-gradient(135deg,#00c853,#64dd17);
    color:white;
    font-size:16px;
    cursor:pointer;
}

.btn:hover{
    transform:scale(1.03);
}

.msg{
    text-align:center;
    font-weight:600;
    margin-bottom:15px;
}

.error{color:#d32f2f;}
.success{color:#2e7d32;}

.links{
    text-align:center;
    margin-top:20px;
}

.links a{
    text-decoration:none;
    color:#555;
}
</style>
</head>

<body>

<div class="card">
    <h2>📝 Create Account</h2>

    <?php if($message): ?>
        <div class="msg <?= str_contains($message,'❌')?'error':'success' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Full Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button class="btn" name="register">Register</button>
    </form>

    <div class="links">
        <a href="login.php">Already have an account? Login</a>
    </div>
</div>

</body>
</html>