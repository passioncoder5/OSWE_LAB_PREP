<?php
require_once 'config.php';

echo "<h3>Loose Comparison (==) Examples</h3>";

$tests = [
    ['0', '0', "Two zeros as strings"],
    ['0', 0, "String '0' vs integer 0"],
    [0, false, "Integer 0 vs boolean false"],
    ['', false, "Empty string vs false"],
    ['abc', 0, "String 'abc' vs integer 0"],
    ['123abc', 123, "String '123abc' vs integer 123"],
    ['1e3', 1000, "Scientific notation vs integer"],
    [null, '', "Null vs empty string"],
    [[], false, "Empty array vs false"],
    ['0e123', '0e456', "Two scientific notation strings"]
];

foreach ($tests as $test) {
    $result = $test[0] == $test[1];
    echo "<div class='result'>";
    echo "<strong>Test:</strong> " . htmlspecialchars($test[2]) . "<br>";
    echo "<code>" . var_export($test[0], true) . " == " . var_export($test[1], true) . "</code><br>";
    echo "<strong>Result:</strong> " . ($result ? "TRUE" : "FALSE") . "<br>";
    echo "<strong>Types:</strong> " . gettype($test[0]) . " vs " . gettype($test[1]);
    echo "</div>";
    
    logTypeJuggling('basic_comparison', 
        $test[2] . ': ' . $test[0] . ' vs ' . $test[1], 
        $result ? 'TRUE' : 'FALSE'
    );
}

echo "<h3>Strict Comparison (===) Examples</h3>";
$test1 = '0';
$test2 = 0;

echo "<div class='result'>";
echo "<code>'0' === 0</code><br>";
echo "<strong>Result:</strong> " . ($test1 === $test2 ? "TRUE" : "FALSE");
echo "</div>";
?>
