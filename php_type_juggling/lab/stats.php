<?php
require_once 'config.php';

$conn = getDbConnection();

// Get total tests
$result = $conn->query("SELECT COUNT(*) as total FROM type_juggling_logs");
$total_tests = $result->fetch_assoc()['total'];

// Get tests by type
$result = $conn->query("
    SELECT test_type, COUNT(*) as count 
    FROM type_juggling_logs 
    GROUP BY test_type 
    ORDER BY count DESC
");

echo "<strong>Total Tests Performed:</strong> $total_tests<br><br>";
echo "<strong>Tests by Type:</strong><br>";
while ($row = $result->fetch_assoc()) {
    echo "{$row['test_type']}: {$row['count']}<br>";
}

// Get recent bypass attempts
$result = $conn->query("
    SELECT test_type, input_data, created_at 
    FROM type_juggling_logs 
    WHERE result LIKE '%BYPASS%' OR result LIKE '%TRUE%'
    ORDER BY created_at DESC 
    LIMIT 5
");

echo "<br><strong>Recent Potential Vulnerabilities:</strong><br>";
while ($row = $result->fetch_assoc()) {
    echo date('H:i:s', strtotime($row['created_at'])) . " - ";
    echo htmlspecialchars(substr($row['test_type'] . ': ' . $row['input_data'], 0, 50)) . "...<br>";
}

$conn->close();
?>
