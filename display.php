<?php
// Start the session
session_start();

// Include the database connection file
include 'db.php';

// Initialize the filter variable
$filter_date = $_GET['filter_date'] ?? '';

// Fetch innovations from the database with optional date filtering
try {
    $query = "SELECT * FROM innovation";
    $params = [];

    if (!empty($filter_date)) {
        $query .= " WHERE date = :filter_date";
        $params['filter_date'] = $filter_date;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $innovations = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COLLECT HUB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
/* General Styles */
body {
    background: linear-gradient(to right, #1a237e, #4a148c);
    font-family: 'Arial', sans-serif;
    color: white;
    margin: 0;
    padding: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
}

/* Header */
header {
    padding: 10px 20px;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    box-sizing: border-box;
}

header img {
    width: 90px;
    height: auto;
}

nav a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    transition: all 0.3s;
}

nav a:hover {
    color: #f5a623;
}

/* Container */
.container {
    margin-top: 20px;
    background: rgba(0, 0, 0, 0.6);
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    width: 95%;
    max-width: 1200px;
}

/* Table */
.table {
    background-color: transparent;
}

.table th, .table td {
    text-align: center;
    color: white;
    vertical-align: middle;
}

.table img {
    width: 100px;
    height: auto;
    object-fit: cover;
}

/* Footer */
footer {
    margin-top: auto;
    background-color: #000;
    color: white;
    text-align: center;
    padding: 10px 20px;
    width: 100%;
    box-sizing: border-box;
}
</style>

</head>
<body>

<!-- Header -->
<header>
    
	<img src="LOGOCOLLECTHUB.jpg" alt="UITM Logo">
    <nav>
        <a href="login.php">Log In</a>
    </nav>
</header>

<!-- Filter Form -->
<div class="container">
    <h2 class="text-center mb-4">COLLECT HUB</h2>

    <form method="GET" class="mb-4">
        <div class="form-row">
            <div class="col-md-10">
                <input type="date" name="filter_date" class="form-control" value="<?= htmlspecialchars($filter_date) ?>" placeholder="Select Date">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Table of Innovations -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Order_id</th>
                <th>Sender Name</th>
                <th>Time</th>
                <th>Date</th>
                <th>Total Item</th>
                <th>Status</th>
                <th>Evidence</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($innovations): ?>
                <?php foreach ($innovations as $innovation): ?>
                    <tr>
                        <td><?= htmlspecialchars($innovation['order_id']) ?></td>
                        <td><?= htmlspecialchars($innovation['sender_name']) ?></td>
                        <td><?= htmlspecialchars($innovation['time']) ?></td>
                        <td><?= htmlspecialchars($innovation['date']) ?></td>
                        <td><?= htmlspecialchars($innovation['total_item']) ?></td>
                        <td><?= htmlspecialchars($innovation['status']) ?></td>
                        <td>
                            <?php if (!empty($innovation['image'])): ?>
                                <img src="uploads/<?= htmlspecialchars($innovation['image']) ?>" alt="Evidence">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No orders found for the selected date.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</div>

<!-- Footer -->
<footer>
    <p>Contact us: <a href="mailto:uitm@collecthub.com" style="color: white;">uitm@collecthub.com</a> | Phone: +036273628</p>
</footer>

</body>
</html>
