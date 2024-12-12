<?php
session_start();

// Destroy all session variables
session_unset();

// Destroy the session
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script>
        // Show a pop-up message and redirect to the login page
        alert('Logout Successfully');
        window.location.href = 'index.php';
    </script>
</head>
<body>
</body>
</html>
