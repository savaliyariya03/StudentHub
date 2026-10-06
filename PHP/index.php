<?php
$message = "";

// Step 1: Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Step 2: Grab and clean input values
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Step 3: Simple validation
    if (empty($name) || empty($email)) {
        $message = "<p style='color: red;'>Please fill in all fields!</p>";
    } else {
        // Prepare data as an associative array
        $new_entry = [
            "name"  => $name,
            "email" => $email
        ];

        // --- Save to JSON ---
        $json_file = "data.json";
        $list = [];

        // If data.json exists, read old items first
        if (file_exists($json_file)) {
            $list = json_decode(file_get_contents($json_file), true);
        }

        $list[] = $new_entry;
        file_put_contents($json_file, json_encode($list, JSON_PRETTY_PRINT));

        // --- Save to CSV ---
        $csv_file = fopen("data.csv", "a");
        fputcsv($csv_file, [$name, $email]);
        fclose($csv_file);

        $message = "<p style='color: green;'>Saved successfully to JSON and CSV!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Practical 7 - Form Processing</title>
</head>
<body style="font-family: Arial; margin: 40px;">

    <h2>Student Registration</h2>

    <!-- Show Success or Error Message -->
    <?php echo $message; ?>

    <!-- Registration Form -->
    <form method="POST" action="index.php">
        <label>Name:</label><br>
        <input type="text" name="name"><br><br>

        <label>Email:</label><br>
        <input type="text" name="email"><br><br>

        <button type="submit">Submit</button>
    </form>

</body>
</html>