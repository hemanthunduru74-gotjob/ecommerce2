<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/db.php';

if (isset($conn)) {
    echo "<h2 style='color:green'>✅ Database connected successfully!</h2>";
} else {
    echo "<h2 style='color:red'>❌ Database connection failed!</h2>";
}
?>  