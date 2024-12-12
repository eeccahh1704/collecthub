<?php
session_start();
include '../db.php'; // Include database connection file

// Check if the user is logged in and is a contributor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contributor') {
    header("Location: ../login.php");
    exit();
}

// Initialize variables for the form
$sender_name = $time = $date = $total_item = $status = $image = "";
$sender_name_err = $time_err = $date_err = $total_item_err = $status_err = $image_err = "";

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate sender name
    if (empty(trim($_POST["sender_name"]))) {
        $sender_name_err = "Please enter the sender's name.";
    } else {
        $sender_name = trim($_POST["sender_name"]);
    }

    // Validate time
    if (empty(trim($_POST["time"]))) {
        $time_err = "Please enter the time.";
    } else {
        $time = trim($_POST["time"]);
    }

    // Validate date
    if (empty(trim($_POST["date"]))) {
        $date_err = "Please enter the date.";
    } else {
        $date = trim($_POST["date"]);
    }

    // Validate total item
    if (empty(trim($_POST["total_item"]))) {
        $total_item_err = "Please enter the total number of items.";
    } elseif (!is_numeric($_POST["total_item"])) {
        $total_item_err = "Total items must be a number.";
    } else {
        $total_item = (int)trim($_POST["total_item"]);
    }

    // Validate status
    if (empty($_POST["status"])) {
        $status_err = "Please select a status.";
    } else {
        $status = trim($_POST["status"]);
    }

    // Validate image
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Check file type
        if (in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $image = $target_file;
            } else {
                $image_err = "Error uploading the file.";
            }
        } else {
            $image_err = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    }

    // Insert data into the "innovation" table if there are no errors
    if (empty($sender_name_err) && empty($time_err) && empty($date_err) && empty($total_item_err) && empty($status_err) && empty($image_err)) {
        try {
            $query = "INSERT INTO innovation (sender_name, time, date, total_item, status, image) 
                      VALUES (:sender_name, :time, :date, :total_item, :status, :image)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':sender_name' => $sender_name,
                ':time' => $time,
                ':date' => $date,
                ':total_item' => $total_item,
                ':status' => $status,
                ':image' => $image,
            ]);

            // Refresh the page to show updated table data
            header("Location: contributor_dashboard.php");
            exit();
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Collect - COLLECT HUB</title>
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

        /* Apply Cooper Black font and #16222a color to the title */
        h3 {
            font-family: 'Cooper Black', serif;
            color: #16222a;
            text-align: center;
            font-size: 36px;
        }
    </style>
</head>
<body>
    <header>
        <img src="collecthub-removebg-preview.png" alt="UITM Logo">
        <nav>
            <a href="../login.php">Log Out</a>
        </nav>
    </header>

    <div class="container">
        <h3 class="text-center">Add New Collect</h3>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Sender Name</label>
                <input type="text" name="sender_name" class="form-control" value="<?php echo htmlspecialchars($sender_name); ?>">
                <span class="text-danger"><?php echo $sender_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Time</label>
                <input type="time" name="time" class="form-control" value="<?php echo htmlspecialchars($time); ?>">
                <span class="text-danger"><?php echo $time_err; ?></span>
            </div>
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo htmlspecialchars($date); ?>">
                <span class="text-danger"><?php echo $date_err; ?></span>
            </div>
            <div class="form-group">
                <label>Total Items</label>
                <input type="number" name="total_item" class="form-control" value="<?php echo htmlspecialchars($total_item); ?>">
                <span class="text-danger"><?php echo $total_item_err; ?></span>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="">Select Status</option>
                    <option value="Unclaimed" <?php echo $status == "Unclaimed" ? "selected" : ""; ?>>Unclaimed</option>
                    <option value="Claimed" <?php echo $status == "Claimed" ? "selected" : ""; ?>>Claimed</option>
                </select>
                <span class="text-danger"><?php echo $status_err; ?></span>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" class="form-control">
                <span class="text-danger"><?php echo $image_err; ?></span>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Add Collect</button>
        </form>
    </div>

    <footer>
        <p>Contact us: <a href="mailto:support@collecthub.com" style="color: white;">support@collecthub.com</a> | Phone: +036273628</p>
    </footer>
</body>
</html>
