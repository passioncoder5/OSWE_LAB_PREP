<?php
require_once 'config.php';

// Check if user bypassed authentication via type juggling
$bypassed = isset($_GET['bypassed']) && $_GET['bypassed'] == 'true';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Welcome Admin - Type Juggling Demo</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f0f0f0; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px; border-radius: 10px 10px 0 0; text-align: center; }
        .content { padding: 30px; }
        .alert { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .danger { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .btn { display: inline-block; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; margin: 10px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>👑 Welcome, Administrator!</h1>
            <p>Type Juggling Authentication Bypass Demonstration</p>
        </div>
        
        <div class='content'>";

if ($bypassed) {
    echo "<div class='alert danger'>
            <h3>🚨 SECURITY ALERT 🚨</h3>
            <p>You have successfully bypassed authentication using PHP Type Juggling!</p>
            <p><strong>Vulnerability Used:</strong> Loose comparison (==) with MD5 hashes</p>
            <p><strong>Example:</strong> <code>'0e830400...' == '0e462097...'</code> evaluates to TRUE</p>
            <p>Both hashes are treated as scientific notation: 0 × 10^... = 0</p>
        </div>";
}

echo "    <div class='alert success'>
            <h3>Admin Dashboard</h3>
            <p>Welcome to the administrator control panel.</p>
        </div>
        
        <h3>System Information</h3>
        <ul>
            <li><strong>Session ID:</strong> " . session_id() . "</li>
            <li><strong>Login Time:</strong> " . date('Y-m-d H:i:s') . "</li>
            <li><strong>User Agent:</strong> " . $_SERVER['HTTP_USER_AGENT'] . "</li>
        </ul>
        
        <h3>Admin Functions</h3>
        <div style='margin: 20px 0;'>
            <button class='btn'>Manage Users</button>
            <button class='btn'>View System Logs</button>
            <button class='btn'>Server Configuration</button>
            <button class='btn'>Database Management</button>
        </div>
        
        <hr>
        
        <h3>About This Vulnerability</h3>
        <p>This demonstration shows how <strong>PHP Type Juggling</strong> can lead to authentication bypass:</p>
        <ol>
            <li>PHP uses loose comparison (<code>==</code>) by default</li>
            <li>MD5 hashes starting with '0e' are treated as scientific notation</li>
            <li><code>'0e123' == '0e456'</code> evaluates to TRUE (both = 0)</li>
            <li>Different passwords can produce '0e...' hashes (magic hashes)</li>
        </ol>
        
        <p><strong>Fix:</strong> Always use strict comparison (<code>===</code>) or <code>hash_equals()</code></p>
        
        <div style='text-align: center; margin-top: 30px;'>
            <a href='vulnerable_login.php' class='btn'>Back to Login</a>
            <a href='secure_login.php' class='btn'>View Secure Version</a>
        </div>
        </div>
    </div>
</body>
</html>";
?>
