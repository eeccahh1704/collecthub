<?php
session_start();
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $affiliation = $_POST['affiliation'];
    $phone_number = $_POST['phone_number'];
    $bio = $_POST['bio'];

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT * FROM Contributor WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error = "Username or email already exists. Please try again.";
        } else {
            // Insert new contributor
            $stmt = $pdo->prepare("INSERT INTO Contributor (name, username, password, email, affiliation, phone_number, bio) 
                                   VALUES (:name, :username, :password, :email, :affiliation, :phone_number, :bio)");
            $stmt->execute([
                'name' => $name,
                'username' => $username,
                'password' => $hashed_password,
                'email' => $email,
                'affiliation' => $affiliation,
                'phone_number' => $phone_number,
                'bio' => $bio
            ]);

            echo "<script>alert('Registration successful! Redirecting to login page.'); window.location.href='../login.php';</script>";
            exit();
        }
    } catch (PDOException $e) {
        $error = "Registration failed: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - COLLECT HUB</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: linear-gradient(to right, #d6e3ea, #7aa6bc);
            font-family: 'Arial', sans-serif;
            color: black;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
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
            max-width: 600px;
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            margin: 50px auto;
        }
        .btn-primary {
            background-color: #3a6073;
            border-color: #3a6073;
        }
        .btn-primary:hover {
            background-color: #2d4a59;
            border-color: #2d4a59;
        }
        footer {
            margin-top: auto;
            background: linear-gradient(to right, #16222a, #3a6073);
            color: #ffffff;
            text-align: center;
            padding: 10px 20px;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <header>
        <img src="collecthub-removebg-preview.png" alt="UITM Logo">
        <nav>
            <a href="../login.php">Log In</a>
        </nav>
    </header>

    <div class="container">
        <h3 class="text-center">Register</h3>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="affiliation">Affiliation</label>
                <input type="text" class="form-control" id="affiliation" name="affiliation">
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="text" class="form-control" id="phone_number" name="phone_number">
            </div>
            <div class="form-group">
                <label for="bio">Biography</label>
                <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Register</button>
            <a href="../login.php" class="btn btn-secondary btn-block">Back to Login</a>
        </form>
    </div>

    <footer>
        <p>Contact us: <a href="mailto:support@collecthub.com" style="color: white;">support@collecthub.com</a> | Phone: +036273628</p>
    </footer>
</body>
</html>
