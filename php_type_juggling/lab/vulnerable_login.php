<?php
require_once 'config.php';

echo "<h3>⚠️ Vulnerable Login System (Demonstration Only!)</h3>";
echo "<p><strong>Warning:</strong> This page demonstrates insecure code for educational purposes.</p>";

echo "<form method='POST'>";
echo "Username: <input type='text' name='username' value='admin'><br>";
echo "Password: <input type='password' name='password'><br>";
echo "<input type='submit' name='login' value='Login (Vulnerable)'>";
echo "</form>";

if (isset($_POST['login'])) {
    $conn = getDbConnection();
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // VULNERABLE CODE - For demonstration only
    $sql = "SELECT * FROM users WHERE username = '" . $conn->real_escape_string($username) . "'";
    $result = $conn->query($sql);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $stored_hash = $user['password'];
        $input_hash = md5($password);
        
        echo "<div class='result vulnerable'>";
        echo "<h4>🔓 Login Attempt Analysis</h4>";
        echo "<strong>Username:</strong> " . htmlspecialchars($username) . "<br>";
        echo "<strong>Input Password:</strong> " . htmlspecialchars($password) . "<br>";
        echo "<strong>MD5 of Input:</strong> " . $input_hash . "<br>";
        echo "<strong>Stored Hash:</strong> " . $stored_hash . "<br><br>";
        
        echo "<strong>Comparison Test:</strong><br>";
        echo "md5(\$password) == \$stored_hash: <code>" . var_export($input_hash == $stored_hash, true) . "</code><br>";
        echo "md5(\$password) === \$stored_hash: <code>" . var_export($input_hash === $stored_hash, true) . "</code><br><br>";
        
        // VULNERABLE COMPARISON - Using loose comparison
        if ($input_hash == $stored_hash) {
            echo "<div style='background: #ff4444; color: white; padding: 10px; margin: 10px 0;'>";
            echo "<h3>🚨 AUTHENTICATION BYPASSED! 🚨</h3>";
            echo "Welcome Admin! You have successfully bypassed authentication using type juggling.<br>";
            echo "The system used loose comparison (==) which allowed:";
            echo "<ul>";
            echo "<li>'{$input_hash}' == '{$stored_hash}' evaluated to TRUE</li>";
            echo "<li>Even though the hashes are different!</li>";
            echo "</ul>";
            echo "</div>";
            
            // Show admin dashboard
            echo "<div style='background: #d4edda; border: 2px solid #28a745; padding: 15px;'>";
            echo "<h2>👑 ADMIN DASHBOARD</h2>";
            echo "<p>Welcome, Administrator!</p>";
            echo "<p><strong>User ID:</strong> " . $user['id'] . "</p>";
            echo "<p><strong>Email:</strong> " . $user['email'] . "</p>";
            echo "<p><strong>Admin Status:</strong> " . ($user['is_admin'] ? 'Yes' : 'No') . "</p>";
            echo "<hr>";
            echo "<h3>System Functions:</h3>";
            echo "<button>Manage Users</button> ";
            echo "<button>View Logs</button> ";
            echo "<button>System Settings</button>";
            echo "</div>";
        } else {
            echo "<div style='background: #ffcccc; padding: 10px;'>";
            echo "<strong>❌ Authentication Failed</strong><br>";
            echo "The hashes don't match (even with loose comparison).";
            echo "</div>";
        }
        
        echo "</div>";
        
        // Log the attempt
        logTypeJuggling('vulnerable_login_attempt', 
            "Username: $username, Password: $password, Hash: $input_hash", 
            $input_hash == $stored_hash ? 'BYPASS_SUCCESS' : 'FAILED'
        );
    } else {
        echo "<div class='result'>User not found</div>";
    }
    
    if ($conn) $conn->close();
}

// Add demonstration of magic hashes
echo "<div style='margin-top: 30px; padding: 20px; background: #fff3cd; border: 1px solid #ffeaa7;'>";
echo "<h4>🎯 Magic Hash Demonstration</h4>";
echo "<p>These passwords will bypass the vulnerable login:</p>";

$magic_hashes = [
    'QNKCDZO' => '0e830400451993494058024219903391',
    '240610708' => '0e462097431906509019562988736854',
    'aabg7XSs' => '0e087386482136013740957780965295',
    's878926199a' => '0e545993274517709034328855841020'
];

echo "<table border='1' cellpadding='8' style='width:100%;'>";
echo "<tr><th>Password</th><th>MD5 Hash</th><th>Starts with</th><th>Will Bypass?</th></tr>";

foreach ($magic_hashes as $password => $hash) {
    $will_bypass = (md5($password) == '0e830400451993494058024219903391') ? '✅ YES' : '❌ NO';
    echo "<tr>";
    echo "<td><code>" . htmlspecialchars($password) . "</code></td>";
    echo "<td><code>" . md5($password) . "</code></td>";
    echo "<td><code>" . substr(md5($password), 0, 2) . "...</code></td>";
    echo "<td>" . $will_bypass . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<p><strong>Why this works:</strong> PHP's loose comparison treats '0e123' as scientific notation (0 × 10^123 = 0).</p>";
echo "<p>So: <code>'0e830400...' == '0e462097...'</code> evaluates to <code>TRUE</code> because both equal 0!</p>";
echo "</div>";

// Show the secure alternative
echo "<div style='margin-top: 30px; padding: 20px; background: #d4edda; border: 1px solid #c3e6cb;'>";
echo "<h4>🔐 How to Fix This Vulnerability</h4>";
echo "<pre><code>";
echo "// VULNERABLE CODE:\n";
echo "if (md5(\$password) == \$stored_hash) { // authentication }\n\n";
echo "// SECURE CODE:\n";
echo "if (hash_equals(\$stored_hash, md5(\$password))) { // authentication }\n\n";
echo "// OR BETTER:\n";
echo "if (password_verify(\$password, \$stored_hash)) { // authentication }";
echo "</code></pre>";
echo "</div>";
?>
