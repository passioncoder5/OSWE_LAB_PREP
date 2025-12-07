<?php
session_start();

// ============================================
// SIMULATED USER DATABASE (No MySQL needed!)
// ============================================
$users = [
    'admin' => [
        'password_hash' => '21232f297a57a5a743894a0e4a801fc3', // md5('admin')
        'email' => 'admin@lab.com',
        'is_admin' => true,
        'full_name' => 'System Administrator'
    ],
    'alice' => [
        'password_hash' => '5f4dcc3b5aa765d61d8327deb882cf99', // md5('password')
        'email' => 'alice@lab.com',
        'is_admin' => false,
        'full_name' => 'Alice Smith'
    ],
    'test' => [
        'password_hash' => '0e462097431906509019562988736854', // Magic hash - vulnerable!
        'email' => 'test@lab.com',
        'is_admin' => false,
        'full_name' => 'Test User'
    ]
];

// Magic hashes that demonstrate type juggling
$magic_hashes = [
    'QNKCDZO' => '0e830400451993494058024219903391',
    '240610708' => '0e462097431906509019562988736854',
    'aabg7XSs' => '0e087386482136013740957780965295',
    's878926199a' => '0e545993274517709034328855841020'
];

// ============================================
// HANDLE LOGIN
// ============================================
$login_result = null;
$show_admin_dashboard = false;
$bypass_detected = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    
    if (isset($users[$username])) {
        $user = $users[$username];
        $input_hash = md5($password);
        $stored_hash = $user['password_hash'];
        
        // VULNERABLE: Loose comparison (==)
        $loose_match = ($input_hash == $stored_hash);
        
        // SECURE: Strict comparison (===)
        $strict_match = ($input_hash === $stored_hash);
        
        // Check if it's a magic hash bypass
        $is_magic_hash = in_array($input_hash, $magic_hashes);
        
        $login_result = [
            'username' => $username,
            'password' => $password,
            'input_hash' => $input_hash,
            'stored_hash' => $stored_hash,
            'loose_match' => $loose_match,
            'strict_match' => $strict_match,
            'is_magic_hash' => $is_magic_hash,
            'bypassed' => ($loose_match && !$strict_match)
        ];
        
        if ($loose_match) {
            $show_admin_dashboard = true;
            $bypass_detected = ($loose_match && !$strict_match);
        }
    } else {
        $login_result = ['error' => 'User not found'];
    }
}

// ============================================
// HTML OUTPUT
// ============================================
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Type Juggling Lab - No Database Required</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px;
            background: #f0f0f0;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        h1 { color: #333; }
        h2 { color: #444; margin-top: 30px; }
        .vulnerable-box { 
            background: #ffe6e6; 
            border-left: 6px solid #ff4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .secure-box { 
            background: #e6ffe6; 
            border-left: 6px solid #44ff44;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box { 
            background: #e6f0ff; 
            border-left: 6px solid #4488ff;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .result-box { 
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            border: 2px solid #ddd;
            font-family: 'Courier New', monospace;
        }
        .success { 
            background: #d4edda; 
            border-color: #c3e6cb; 
            color: #155724; 
        }
        .danger { 
            background: #f8d7da; 
            border-color: #f5c6cb; 
            color: #721c24; 
        }
        .warning { 
            background: #fff3cd; 
            border-color: #ffeaa7; 
            color: #856404; 
        }
        .admin-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            margin: 30px 0;
        }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            background: #ff4444;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            margin: 10px 5px;
        }
        button.secure-btn {
            background: #44ff44;
            color: #333;
        }
        .hash-display {
            font-family: 'Courier New', monospace;
            background: #2c3e50;
            color: #ecf0f1;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
        }
        .true { color: #28a745; font-weight: bold; }
        .false { color: #dc3545; font-weight: bold; }
        .magic-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin: 15px 0;
        }
        .magic-item {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border: 2px solid #dee2e6;
            text-align: center;
            flex: 1;
            min-width: 150px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 30px;">
            <h1>🔬 PHP Type Juggling Laboratory</h1>
            <p style="color: #666;">No Database Required - Complete Standalone Demo</p>
            <p style="color: #ff4444; font-weight: bold;">⚠️ Demonstrates SECURITY VULNERABILITIES for educational purposes</p>
        </div>

        <!-- Vulnerable Login Form -->
        <div class="vulnerable-box">
            <h2>⚠️ Vulnerable Login System</h2>
            <p>This login uses <code>md5($password) == $stored_hash</code> (LOOSE comparison)</p>
            
            <form method="POST">
                <div class="form-group">
                    <label>Username:</label>
                    <input type="text" name="username" value="admin" required>
                </div>
                
                <div class="form-group">
                    <label>Password:</label>
                    <input type="password" name="password" required 
                           placeholder="Try: QNKCDZO or 240610708">
                    <small style="color: #666;">These are "magic hashes" that bypass authentication</small>
                </div>
                
                <button type="submit" name="login">🔓 Test Vulnerable Login</button>
            </form>
            
            <div style="margin-top: 20px;">
                <h3>Magic Passwords (Will Bypass Authentication):</h3>
                <div class="magic-list">
                    <?php foreach($magic_hashes as $pass => $hash): ?>
                        <div class="magic-item">
                            <strong><?php echo htmlspecialchars($pass); ?></strong><br>
                            <small>MD5: <?php echo substr($hash, 0, 10); ?>...</small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if ($login_result): ?>
            <!-- Login Results -->
            <div class="result-box <?php echo $login_result['loose_match'] ? 'warning' : 'danger'; ?>">
                <h3>🔍 Login Analysis</h3>
                
                <table>
                    <tr>
                        <th>Item</th>
                        <th>Value</th>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td><code><?php echo htmlspecialchars($login_result['username']); ?></code></td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td><code><?php echo htmlspecialchars($login_result['password']); ?></code></td>
                    </tr>
                    <tr>
                        <td>Input MD5 Hash</td>
                        <td class="hash-display"><?php echo $login_result['input_hash']; ?></td>
                    </tr>
                    <tr>
                        <td>Stored MD5 Hash</td>
                        <td class="hash-display"><?php echo $login_result['stored_hash']; ?></td>
                    </tr>
                    <tr>
                        <td>Loose Comparison (==)</td>
                        <td class="<?php echo $login_result['loose_match'] ? 'true' : 'false'; ?>">
                            <?php echo $login_result['loose_match'] ? 'TRUE' : 'FALSE'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Strict Comparison (===)</td>
                        <td class="<?php echo $login_result['strict_match'] ? 'true' : 'false'; ?>">
                            <?php echo $login_result['strict_match'] ? 'TRUE' : 'FALSE'; ?>
                        </td>
                    </tr>
                </table>
                
                <?php if ($login_result['bypassed']): ?>
                    <div class="warning" style="padding: 15px; margin: 15px 0; border: 3px solid #ff4444;">
                        <h3>🚨 TYPE JUGGLING BYPASS DETECTED! 🚨</h3>
                        <p>The hashes are <strong>different</strong> but PHP's loose comparison evaluates them as equal!</p>
                        <p><strong>Why this works:</strong></p>
                        <ul>
                            <li>Both hashes start with <code>0e</code> (scientific notation)</li>
                            <li>PHP treats <code>0e123</code> as 0 × 10^123 = 0</li>
                            <li>So <code>0eX == 0eY</code> → Both equal 0 → TRUE!</li>
                        </ul>
                        <p><strong>Input Hash:</strong> <code><?php echo $login_result['input_hash']; ?></code></p>
                        <p><strong>Stored Hash:</strong> <code><?php echo $login_result['stored_hash']; ?></code></p>
                        <p><strong>Both equal 0 in PHP's loose comparison!</strong></p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_admin_dashboard): ?>
            <!-- Admin Dashboard -->
            <div class="admin-dashboard">
                <h1 style="font-size: 3em; margin-bottom: 20px;">👑 WELCOME ADMIN!</h1>
                
                <?php if ($bypass_detected): ?>
                    <div style="background: rgba(255, 0, 0, 0.2); padding: 20px; border-radius: 10px; margin: 20px 0;">
                        <h2>⚠️ SECURITY BREACH DETECTED</h2>
                        <p>Authentication was <strong>bypassed</strong> using PHP Type Juggling!</p>
                        <p>This should NOT happen in a secure system.</p>
                    </div>
                <?php endif; ?>
                
                <div style="background: rgba(255, 255, 255, 0.1); padding: 30px; border-radius: 10px; margin: 20px 0;">
                    <h2>Admin Dashboard</h2>
                    <p>Welcome, <strong><?php echo htmlspecialchars($login_result['username'] ?? 'Admin'); ?></strong>!</p>
                    
                    <div style="display: flex; justify-content: center; gap: 15px; margin: 30px 0;">
                        <button style="background: #28a745;">👥 Manage Users</button>
                        <button style="background: #17a2b8;">📊 View Logs</button>
                        <button style="background: #ffc107; color: #333;">⚙️ System Settings</button>
                        <button style="background: #6c757d;">🗄️ Database Admin</button>
                    </div>
                    
                    <div style="text-align: left; background: rgba(255, 255, 255, 0.1); padding: 20px; border-radius: 8px;">
                        <h3>System Information</h3>
                        <p><strong>Login Method:</strong> <?php echo $bypass_detected ? 'Type Juggling Bypass' : 'Normal Authentication'; ?></p>
                        <p><strong>User Role:</strong> Administrator</p>
                        <p><strong>Session Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Type Juggling Explanation -->
        <div class="info-box">
            <h2>🎯 How PHP Type Juggling Works</h2>
            <p>PHP uses <strong>loose comparison (==)</strong> by default, which can lead to unexpected results:</p>
            
            <table>
                <tr>
                    <th>Comparison</th>
                    <th>Result (==)</th>
                    <th>Result (===)</th>
                    <th>Explanation</th>
                </tr>
                <tr>
                    <td><code>'0e123' == '0e456'</code></td>
                    <td class="true">TRUE</td>
                    <td class="false">FALSE</td>
                    <td>Both treated as 0 (scientific notation)</td>
                </tr>
                <tr>
                    <td><code>'0' == false</code></td>
                    <td class="true">TRUE</td>
                    <td class="false">FALSE</td>
                    <td>String '0' equals boolean false</td>
                </tr>
                <tr>
                    <td><code>'' == false</code></td>
                    <td class="true">TRUE</td>
                    <td class="false">FALSE</td>
                    <td>Empty string equals boolean false</td>
                </tr>
                <tr>
                    <td><code>'abc' == 0</code></td>
                    <td class="true">TRUE</td>
                    <td class="false">FALSE</td>
                    <td>Non-numeric string converts to 0</td>
                </tr>
            </table>
        </div>

        <!-- Secure Implementation -->
        <div class="secure-box">
            <h2>🔒 How to Fix This Vulnerability</h2>
            
            <h3>Vulnerable Code (DON'T USE):</h3>
            <div class="hash-display">
// BAD: Loose comparison allows bypass<br>
if (md5($password) == $stored_hash) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Authentication successful<br>
}
            </div>
            
            <h3>Secure Code (ALWAYS USE):</h3>
            <div class="hash-display">
// GOOD: Strict comparison<br>
if (md5($password) === $stored_hash) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Authentication successful<br>
}<br><br>
// BETTER: Timing-safe comparison<br>
if (hash_equals($stored_hash, md5($password))) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Authentication successful<br>
}<br><br>
// BEST: Modern password hashing<br>
if (password_verify($password, $stored_hash)) {<br>
&nbsp;&nbsp;&nbsp;&nbsp;// Authentication successful<br>
}
            </div>
            
            <button class="secure-btn">📚 Learn More About Secure Coding</button>
        </div>

        <!-- Test Area -->
        <div class="info-box">
            <h2>🧪 Test Your Own Comparisons</h2>
            <p>Try different values to see how PHP type juggling works:</p>
            
            <form method="POST" action="">
                <div style="display: flex; gap: 20px; margin: 20px 0;">
                    <div style="flex: 1;">
                        <label>Value 1:</label>
                        <input type="text" name="val1" value="0e123">
                    </div>
                    <div style="flex: 1;">
                        <label>Value 2:</label>
                        <input type="text" name="val2" value="0e456">
                    </div>
                </div>
                
                <?php
                if (isset($_POST['val1']) && isset($_POST['val2'])) {
                    $val1 = $_POST['val1'];
                    $val2 = $_POST['val2'];
                    
                    echo "<div class='result-box'>";
                    echo "<strong>Comparison Results:</strong><br>";
                    echo "<code>" . htmlspecialchars($val1) . " == " . htmlspecialchars($val2) . "</code>: ";
                    echo ($val1 == $val2) ? "<span class='true'>TRUE</span>" : "<span class='false'>FALSE</span>";
                    echo "<br>";
                    echo "<code>" . htmlspecialchars($val1) . " === " . htmlspecialchars($val2) . "</code>: ";
                    echo ($val1 === $val2) ? "<span class='true'>TRUE</span>" : "<span class='false'>FALSE</span>";
                    echo "</div>";
                }
                ?>
                
                <button type="submit" name="test">Test Comparison</button>
            </form>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd;">
            <p><strong>PHP Type Juggling Lab</strong> - Educational Demo</p>
            <p>Remember: Always use strict comparison (===) and proper password hashing in production!</p>
        </div>
    </div>
</body>
</html>
