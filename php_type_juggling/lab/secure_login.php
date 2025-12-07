<?php
require_once 'config.php';

echo "<form method='POST'>";
echo "Username: <input type='text' name='secure_user'><br>";
echo "Password: <input type='password' name='secure_pass'><br>";
echo "<input type='submit' name='secure_login' value='Login (Secure)'>";
echo "</form>";

if (isset($_POST['secure_login'])) {
    $conn = getDbConnection();
    $username = $conn->real_escape_string($_POST['secure_user']);
    $password = $_POST['secure_pass'];
    
    // SECURE: Using prepared statements
    $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        echo "<div class='result secure'>";
        echo "<h4>🔐 Secure Login Test</h4>";
        
        // SECURE: Use hash_equals for timing-safe comparison
        if (hash_equals($user['password'], md5($password))) {
            echo "<strong>Result:</strong> Authentication SUCCESS (Secure)<br>";
            echo "<strong>Method:</strong> hash_equals() for comparison<br>";
            echo "<strong>Note:</strong> Even if MD5 is weak, comparison is secure";
        } else {
            echo "<strong>Result:</strong> Authentication FAILED";
        }
        echo "</div>";
        
        logTypeJuggling('secure_login', 
            "Username: $username", 
            hash_equals($user['password'], md5($password)) ? 'SUCCESS' : 'FAILED'
        );
    } else {
        echo "<div class='result'>User not found</div>";
    }
    
    $stmt->close();
    $conn->close();
}

// Show secure comparison examples
echo "<h4>🔧 Secure Comparison Functions</h4>";
echo "<div class='result secure'>";
echo "<code>hash_equals('known_string', 'user_input')</code> - Timing-safe string comparison<br>";
echo "<code>password_verify(\$password, \$hash)</code> - For password hashing<br>";
echo "<code>===</code> - Strict type comparison<br>";
echo "</div>";
?>
