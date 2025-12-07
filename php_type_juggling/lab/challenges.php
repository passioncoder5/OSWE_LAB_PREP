<?php
require_once 'config.php';

echo "<h3>🎯 Type Juggling Challenges</h3>";

// Challenge 1: Authentication bypass
echo "<div class='result'>";
echo "<h4>Challenge 1: Can you make this TRUE?</h4>";
echo "<code>\$a == \$b && \$a !== \$b && md5(\$a) == md5(\$b)</code><br>";

$a = "0e123";
$b = "0e456";
if ($a == $b && $a !== $b && md5($a) == md5($b)) {
    echo "✅ Challenge solved!<br>";
    echo "\$a = '$a', \$b = '$b'<br>";
    echo "md5(\$a) = " . md5($a) . "<br>";
    echo "md5(\$b) = " . md5($b);
} else {
    echo "❌ Not solved yet. Try scientific notation strings!";
}
echo "</div>";

// Challenge 2: Array comparison
echo "<div class='result'>";
echo "<h4>Challenge 2: Array Type Juggling</h4>";
echo "<code>[] == false: " . ([] == false ? "TRUE" : "FALSE") . "</code><br>";
echo "<code>[] == 0: " . ([] == 0 ? "TRUE" : "FALSE") . "</code><br>";
echo "<code>[] == '': " . ([] == '' ? "TRUE" : "FALSE") . "</code><br>";
echo "<strong>Note:</strong> Empty arrays have interesting type juggling behavior";
echo "</div>";

// Challenge 3: MySQL boolean
echo "<div class='result'>";
echo "<h4>Challenge 3: MySQL Boolean Handling</h4>";
$conn = getDbConnection();
$result = $conn->query("SELECT name, is_active FROM products WHERE is_active = '1abc'");
echo "Products where is_active = '1abc': " . $result->num_rows . " found<br>";
echo "MySQL converts '1abc' to 1 for boolean comparison";
$conn->close();
echo "</div>";

// Interactive challenge form
echo "<h3>🔍 Test Your Own Comparisons</h3>";
echo "<form method='POST'>";
echo "Value 1: <input type='text' name='val1' value='0'><br>";
echo "Value 2: <input type='text' name='val2' value='0'><br>";
echo "<input type='submit' name='test' value='Test Comparison'>";
echo "</form>";

if (isset($_POST['test'])) {
    $val1 = $_POST['val1'];
    $val2 = $_POST['val2'];
    
    echo "<div class='result'>";
    echo "<strong>Testing:</strong> " . htmlspecialchars($val1) . " vs " . htmlspecialchars($val2) . "<br>";
    echo "<strong>Loose (==):</strong> " . ($val1 == $val2 ? "TRUE" : "FALSE") . "<br>";
    echo "<strong>Strict (===):</strong> " . ($val1 === $val2 ? "TRUE" : "FALSE") . "<br>";
    echo "<strong>Types:</strong> " . gettype($val1) . " vs " . gettype($val2) . "<br>";
    
    // Show what PHP sees
    echo "<strong>PHP sees val1 as:</strong> ";
    var_dump($val1);
    echo "<strong>PHP sees val2 as:</strong> ";
    var_dump($val2);
    echo "</div>";
    
    logTypeJuggling('user_test', 
        "$val1 vs $val2", 
        "Loose: " . ($val1 == $val2 ? 'TRUE' : 'FALSE') . 
        ", Strict: " . ($val1 === $val2 ? 'TRUE' : 'FALSE')
    );
}
?>
