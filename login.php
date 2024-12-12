<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Check if the user is a contributor
        $stmt = $pdo->prepare("SELECT * FROM Contributor WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $contributor = $stmt->fetch();

        if ($contributor && password_verify($password, $contributor['password'])) {
            // Contributor found and password verified
            $_SESSION['user_id'] = $contributor['contributor_id'];
            $_SESSION['user_role'] = 'contributor';
            $_SESSION['username'] = $contributor['username'];
            header("Location: contributor/contributor_dashboard.php");
            exit();
        }

        // If no match is found, show an error
        $error = "Invalid username or password";

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - COLLECT HUB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #d6e3ea, #7aa6bc);
            font-family: 'Arial', sans-serif;
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        header {
            padding: 10px 20px;
            background: linear-gradient(to right, #16222a, #3a6073);
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            box-sizing: border-box;
            color: #ffffff;
            position: absolute;
            top: 0;
        }

        header img {
            width: 300px;
            height: auto;
        }

        nav a {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            transition: all 0.3s;
        }

        nav a:hover {
            color: #2ecc71;
        }

        .container {
            max-width: 400px;
            width: 90%;
            background: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .btn-primary {
            background-color: #16222a;
            border-color: #16222a;
        }

        .btn-primary:hover {
            background-color: #0d1b2d;
            border-color: #0d1b2d;
        }

        footer {
            margin-top: auto;
            background: linear-gradient(to right, #16222a, #3a6073);
            color: #ffffff;
            text-align: center;
            padding: 10px 20px;
            width: 100%;
            box-sizing: border-box;
            position: absolute;
            bottom: 0;
        }
    </style>
</head>
<body>
    <header>
        <img src="collecthub-removebg-preview.png" alt="COLLECT HUB Logo">
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </header>

    <div class="container">
        <h3 class="text-center">Login</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <a href="contributor/register.php" class="btn btn-secondary btn-block mt-3">Register</a>
        </form>
    </div>

    <footer>
        <p>Contact us: <a href="mailto:uitm@collecthub.com" style="color: white;">uitm@collecthub.com</a> | Phone: +03464678200</p>
    </footer>
</body>
</html>
