<!DOCTYPE html>
<html>
<head>
    <title>PHP Type Juggling Lab</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { max-width: 1200px; margin: auto; }
        .card { background: #f4f4f4; padding: 20px; margin: 20px 0; border-radius: 5px; }
        .test-section { margin: 30px 0; padding: 20px; background: #fff; border: 1px solid #ddd; }
        .vulnerable { background: #ffe6e6; border-left: 4px solid #ff4444; }
        .secure { background: #e6ffe6; border-left: 4px solid #44ff44; }
        .result { padding: 10px; margin: 10px 0; background: #f8f9fa; border: 1px solid #ddd; }
        code { background: #eee; padding: 2px 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔬 PHP Type Juggling Laboratory</h1>
        <p>Explore PHP's type juggling behavior and security implications with MySQL backend</p>
        
        <div class="card">
            <h2>📊 Lab Statistics</h2>
            <?php include 'stats.php'; ?>
        </div>
        
        <div class="test-section">
            <h2>🧪 Basic Type Juggling Tests</h2>
            <?php include 'basic_tests.php'; ?>
        </div>
        
        <div class="test-section vulnerable">
            <h2>⚠️ Vulnerable Login Simulation</h2>
            <?php include 'vulnerable_login.php'; ?>
        </div>
        
        <div class="test-section">
            <h2>🔒 Secure Login (Comparison)</h2>
            <?php include 'secure_login.php'; ?>
        </div>
        
        <div class="test-section">
            <h2>🛒 SQL Query Type Juggling</h2>
            <?php include 'sql_tests.php'; ?>
        </div>
        
        <div class="test-section">
            <h2>📝 Type Conversion Challenges</h2>
            <?php include 'challenges.php'; ?>
        </div>
        
        <div class="card">
            <h2>📋 Audit Log</h2>
            <?php include 'audit_log.php'; ?>
        </div>
    </div>
</body>
</html>
