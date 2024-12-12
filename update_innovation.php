<?php
session_start();
include '../db.php';

// Check if the user is logged in and is a contributor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contributor') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $order_id = intval($_GET['id']); // Sanitize input
    $stmt = $pdo->prepare("SELECT * FROM Innovation WHERE order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);
    $innovation = $stmt->fetch();

    if (!$innovation) {
        echo "Innovation not found.";
        exit;
    }
}

if (isset($_POST['update'])) {
    $sender_name = $_POST['sender_name'];
    $time = $_POST['time'];
    $date = $_POST['date'];
    $total_item = $_POST['total_item'];
    $status = $_POST['status'];
    $image = $_FILES['image']['name'];
    $upload_path = 'uploads/' . $image;

    // Update the record
    if ($image) {
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_path);
        $stmt = $pdo->prepare("UPDATE Innovation SET sender_name = :sender_name, time = :time, date = :date, total_item = :total_item, status = :status, image = :image WHERE order_id = :order_id");
        $stmt->execute([
            'sender_name' => $sender_name,
            'time' => $time,
            'date' => $date,
            'total_item' => $total_item,
            'status' => $status,
            'image' => $upload_path,
            'order_id' => $order_id
        ]);
    } else {
        $stmt = $pdo->prepare("UPDATE Innovation SET sender_name = :sender_name, time = :time, date = :date, total_item = :total_item, status = :status WHERE order_id = :order_id");
        $stmt->execute([
            'sender_name' => $sender_name,
            'time' => $time,
            'date' => $date,
            'total_item' => $total_item,
            'status' => $status,
            'order_id' => $order_id
        ]);
    }

    header("Location: contributor_dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Innovation - COLLECTHUB</title>
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

        footer {
            margin-top: auto;
            background: linear-gradient(to right, #16222a, #3a6073);
            color: #ffffff;
            text-align: center;
            padding: 10px 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: #3a6073;
            border-color: #3a6073;
        }

        .btn-primary:hover {
            background-color: #2d4a59;
            border-color: #2d4a59;
        }

        .form-control {
            background-color: #f1f1f1;
            border: 1px solid #3a6073;
            color: black;
        }

        .form-control:focus {
            border-color: #2ecc71;
        }

        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <img src="collecthub-removebg-preview.png" alt="COLLECTHUB Logo">
    <nav>
        <a href="../logout.php">Log Out</a>
    </nav>
</header>

<!-- Update Form -->
<div class="container">
    <h3 class="text-center">Update Innovation</h3>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Sender Name</label>
            <input type="text" class="form-control" name="sender_name" value="<?= htmlspecialchars($innovation['sender_name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Time</label>
            <input type="text" class="form-control" name="time" value="<?= htmlspecialchars($innovation['time']) ?>" required>
        </div>
        <div class="form-group">
            <label>Date</label>
            <input type="text" class="form-control" name="date" value="<?= htmlspecialchars($innovation['date']) ?>" required>
        </div>
        <div class="form-group">
            <label>Total Items</label>
            <input type="number" class="form-control" name="total_item" value="<?= htmlspecialchars($innovation['total_item']) ?>" required>
        </div>
        <div class="form-group">
            <label>Status</label>
            <select class="form-control" name="status" required>
                <option value="Unclaimed" <?= $innovation['status'] === 'Unclaimed' ? 'selected' : '' ?>>Unclaimed</option>
                <option value="Claimed" <?= $innovation['status'] === 'Claimed' ? 'selected' : '' ?>>Claimed</option>
            </select>
        </div>
        <div class="form-group">
            <label>Upload New Image (Optional):</label>
            <input type="file" class="form-control" name="image" accept="image/*">
        </div>
        <button type="submit" name="update" class="btn btn-primary btn-block">Update Innovation</button>
    </form>
</div>

<!-- Footer -->
<footer>
    <p>Contact us: <a href="mailto:uitm@collecthub.com" style="color: white;">uitm@collecthub.com</a> | Phone: +032763628</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
