<?php
// Configuration and Connection
include 'config.php';

// Initialize variables
$product = null;
$error = null;

// VULNERABILITY POINT: Unsanitized User Input
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Constructing the query via concatenation
    // This allows the attacker to break out of the intended logic
    $sql = "SELECT name, description, price FROM products WHERE id = ". $id. " LIMIT 1";
    
    // Execute the query
    $result = $conn->query($sql);

    // Boolean Logic Oracle:
    // If query returns a row -> TRUE State ("Product Found")
    // If query returns empty -> FALSE State ("Product Not Found")
    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $error = "Product not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vulnerable Shop (XAMPP Edition)</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; padding: 50px; }
       .container { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 600px; margin: 0 auto; }
        h1 { color: #333; }
        input[type="text"] { padding: 10px; width: 70%; margin-right: 10px; border: 1px solid #ddd; border-radius: 4px; }
        button { padding: 10px 20px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #218838; }
       .success-box { margin-top: 20px; padding: 15px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px; }
       .error-box { margin-top: 20px; padding: 15px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px; }
       .price { font-weight: bold; font-size: 1.2em; }
    </style>
</head>
<body>
    <div class="container">
        <h1>OSWE Lab Shop</h1>
        <form action="index.php" method="GET">
            <label for="id">Enter Product ID:</label><br><br>
            <input type="text" id="id" name="id" placeholder="e.g., 1">
            <button type="submit">Search</button>
        </form>

        <div class="results">
            <?php if ($product):?>
                <div class="success-box">
                    <h2><?php echo htmlspecialchars($product['name']);?></h2>
                    <p><?php echo htmlspecialchars($product['description']);?></p>
                    <span class="price">$<?php echo htmlspecialchars($product['price']);?></span>
                </div>
            <?php elseif ($error):?>
                <div class="error-box">
                    <p><?php echo htmlspecialchars($error);?></p>
                </div>
            <?php endif;?>
        </div>
    </div>
</body>
</html>
