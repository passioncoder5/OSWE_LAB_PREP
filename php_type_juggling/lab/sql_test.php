<?php
require_once 'config.php';

echo "<h3>🛒 Product Search (Vulnerable)</h3>";
echo "<form method='GET'>";
echo "Product ID: <input type='text' name='product_id' value='1'>";
echo "<input type='submit' value='Search'>";
echo "</form>";

if (isset($_GET['product_id'])) {
    $conn = getDbConnection();
    $product_id = $_GET['product_id'];
    
    echo "<h4>Vulnerable Query</h4>";
    echo "<code>SELECT * FROM products WHERE id = '$product_id' OR 1=1</code><br><br>";
    
    // VULNERABLE: Direct concatenation
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $conn->query($sql);
    
    echo "<strong>Products Found:</strong><br>";
    while ($row = $result->fetch_assoc()) {
        echo "ID: {$row['id']}, Name: {$row['name']}, Price: \${$row['price']}<br>";
    }
    
    // Demonstrate type juggling in WHERE clause
    echo "<h4>Type Juggling in WHERE Clauses</h4>";
    
    $tests = [
        "WHERE id = '1abc'",      // String vs integer
        "WHERE price = '0'",       // Decimal vs string
        "WHERE is_active = '1'",   // Boolean vs string
        "WHERE quantity = '100abc'" // Integer vs string
    ];
    
    foreach ($tests as $test) {
        $test_sql = "SELECT COUNT(*) as count FROM products $test";
        $test_result = $conn->query($test_sql);
        $count = $test_result->fetch_assoc()['count'];
        echo "<div class='result'>";
        echo "<strong>Query:</strong> $test_sql<br>";
        echo "<strong>Rows matched:</strong> $count<br>";
        echo "<strong>MySQL Behavior:</strong> Implicit type conversion occurs";
        echo "</div>";
    }
    
    $conn->close();
}

// Secure parameterized query example
echo "<h3>🔒 Secure Parameterized Query</h3>";
echo "<div class='result secure'>";
echo "<code>";
echo "\$stmt = \$conn->prepare('SELECT * FROM products WHERE id = ?');<br>";
echo "\$stmt->bind_param('i', \$product_id); // 'i' for integer<br>";
echo "\$stmt->execute();";
echo "</code>";
echo "</div>";
?>
