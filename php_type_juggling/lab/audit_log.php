<?php
require_once 'config.php';

$conn = getDbConnection();
$session_id = session_id();

// Get logs for current session
$result = $conn->query("
    SELECT test_type, input_data, result, created_at 
    FROM type_juggling_logs 
    WHERE session_id = '$session_id' 
    ORDER BY created_at DESC 
    LIMIT 20
");

echo "<table border='1' cellpadding='8' style='width:100%;'>";
echo "<tr><th>Time</th><th>Test Type</th><th>Input Data</th><th>Result</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $row['created_at'] . "</td>";
    echo "<td>" . htmlspecialchars($row['test_type']) . "</td>";
    echo "<td>" . htmlspecialchars(substr($row['input_data'], 0, 100)) . "</td>";
    echo "<td style='color:" . (strpos($row['result'], 'BYPASS') !== false ? 'red' : 'green') . "'>";
    echo htmlspecialchars($row['result']);
    echo "</td>";
    echo "</tr>";
}

echo "</table>";

if ($result->num_rows == 0) {
    echo "<p>No logs yet. Perform some tests to see them here.</p>";
}

$conn->close();
?>
