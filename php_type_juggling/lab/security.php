<?php
echo "<div class='card secure'>";
echo "<h2>🛡️ Security Best Practices</h2>";
echo "<ol>";
echo "<li><strong>Always use strict comparison (===) instead of loose (==)</strong></li>";
echo "<li><strong>Use prepared statements for database queries</strong></li>";
echo "<li><strong>Use hash_equals() for string comparison (timing-safe)</strong></li>";
echo "<li><strong>Use password_hash() and password_verify() for passwords</strong></li>";
echo "<li><strong>Validate and sanitize all user input</strong></li>";
echo "<li><strong>Use filter_var() for data validation</strong></li>";
echo "<li><strong>Set PHP.ini: session.cookie_httponly = 1</strong></li>";
echo "<li><strong>Disable register_globals and magic_quotes if still enabled</strong></li>";
echo "</ol>";

echo "<h3>Dangerous Patterns to Avoid</h3>";
echo "<code>";
echo "// DANGEROUS<br>";
echo "if (\$_POST['password'] == \$stored_hash) { ... }<br><br>";
echo "// DANGEROUS<br>";
echo "\$sql = \"SELECT * FROM users WHERE id = \$_GET['id']\";<br><br>";
echo "// DANGEROUS<br>";
echo "if (md5(\$input) == '0e123...') { ... }<br>";
echo "</code>";
echo "</div>";
?>
