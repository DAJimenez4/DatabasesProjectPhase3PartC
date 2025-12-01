<?php

// Get user input
$uid = $_GET['uid'];
$password = $_GET['password'];

// Construct a deliberately vulnerable SQL query (string only!)
$sql = "SELECT * FROM users WHERE uid='$uid' AND password='$password'";

// Display this SQL for your assignment
echo "<h2>Constructed SQL Query (Vulnerable)</h2>";
echo "<pre>$sql</pre>";

// Load fake database
$users = file('users.dat', FILE_IGNORE_NEW_LINES);

// This is where we simulate SQL injection vulnerability.
// Instead of parsing SQL safely, we simply check if the line
// "contains" the injected fragments.
$found = false;

foreach ($users as $line) {

    // Split fields
    $f = explode("\t", $line);

    // Expected positions:
    // 0 = uid
    // 5 = password

    $db_uid = $f[0] ?? "";
    $db_pass = $f[5] ?? "";

    // Vulnerable logic:
    // If the SQL injection bypasses password, ANY match is allowed
    // E.g. password: ' OR '1'='1
    if (
        $uid === $db_uid &&
        ( $password === $db_pass ||
          strpos($password, "' OR '") !== false )
    ) {
        $found = $f;
        break;
    }
}

echo "<h2>Search Results</h2>";

if ($found) {
    echo "<p><strong>Name:</strong> {$found[1]} {$found[2]}</p>";
    echo "<p><strong>Email:</strong> {$found[3]}</p>";
    echo "<p><strong>U_ID:</strong> {$found[0]}</p>";
    echo "<p><strong>Role:</strong> {$found[6]}</p>";
} else {
    echo "<p style='color:red;'>No matching user found.</p>";
}

echo "<p><a href='loginInfo.html'>Back to login</a></p>";

?>
