<?php
session_start();
include '../db.php';

// Check if the user is logged in and is a contributor
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'contributor') {
    header("Location: ../login.php");
    exit();
}

// Get the contributor ID from the session
$contributor_id = $_SESSION['user_id'];

// Default filter variables
$filter_status = $_GET['status'] ?? '';
$filter_category = $_GET['category'] ?? '';

// Initialize variables
$innovations = [];

try {
    // Fetch contributor information for the welcome message
    $stmt = $pdo->prepare("SELECT name FROM Contributor WHERE contributor_id = :contributor_id LIMIT 1");
    $stmt->execute(['contributor_id' => $contributor_id]);
    $contributor = $stmt->fetch();

    // Build query with optional filters (no pagination limit)
    $query = "SELECT * FROM Innovation";
    $params = [];

    if ($filter_status) {
        $query .= " WHERE status = :status";
        $params['status'] = $filter_status;
    }
    if ($filter_category) {
        $query .= ($filter_status ? " AND" : " WHERE") . " category = :category";
        $params['category'] = $filter_category;
    }

    // Execute query to get all records (no LIMIT, no OFFSET)
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
    <title>Contributor Dashboard - COLLECTHUB</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
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
            margin-left: 15px;
        }

        nav a:first-child {
            margin-left: 0;
        }

        nav a:hover {
            color: #2ecc71;
        }

        .container {
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 1200px;
        }

        h2 {
            font-family: 'Cooper Black', serif;
            color: #2d4a59;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            cursor: pointer;
            max-width: 100%; /* Ensures the image fits into the table cell */
        }

        .status-unclaimed {
            color: red;
            font-weight: bold;
        }

        .status-claimed {
            color: blue;
            font-weight: bold;
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
    <a href="../aboutus.php">
        <img src="collecthub-removebg-preview.png" alt="UITM Logo">
    </a>
    <nav>
        <a href="../logout.php">Log Out</a>
    </nav>
</header>

<div class="container">
    <h2>Welcome, <?= htmlspecialchars($contributor['name']) ?>!</h2>

    <div class="row mb-4">
        <div class="col-md-6">
            <form method="GET" class="d-flex">
                <select name="status" class="form-control mr-2">
                    <option value="">All Statuses</option>
                    <option value="Unclaimed" <?= $filter_status === 'Unclaimed' ? 'selected' : '' ?>>Unclaimed</option>
                    <option value="Claimed" <?= $filter_status === 'Claimed' ? 'selected' : '' ?>>Claimed</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>
        <div class="col-md-6 text-right">
            <a href="add_innovation.php" class="btn btn-primary btn-lg">Add New Collect Hub</a>
            <a href="report.php" class="btn btn-warning btn-lg ml-3">Report</a>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sender Name</th>
                <th>Time</th>
                <th>Date</th>
                <th>Total Items</th>
                <th>Status</th>
                <th>Evidence</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($innovations): ?>
                <?php foreach ($innovations as $innovation): ?>
                    <tr>
                        <td><?= htmlspecialchars($innovation['sender_name']) ?></td>
                        <td><?= htmlspecialchars($innovation['time']) ?></td>
                        <td><?= htmlspecialchars($innovation['date']) ?></td>
                        <td><?= htmlspecialchars($innovation['total_item']) ?></td>
                        <td>
                            <?php if ($innovation['status'] === 'Unclaimed'): ?>
                                <span class="status-unclaimed"><?= htmlspecialchars($innovation['status']) ?></span>
                            <?php else: ?>
                                <span class="status-claimed"><?= htmlspecialchars($innovation['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($innovation['image']) && file_exists('../uploads/' . $innovation['image'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($innovation['image']) ?>" alt="Evidence" data-toggle="modal" data-target="#imageModal" data-src="../uploads/<?= htmlspecialchars($innovation['image']) ?>">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="update_innovation.php?id=<?= $innovation['order_id'] ?>" class="btn btn-warning btn-sm">Update</a>
                            <a href="delete.php?id=<?= $innovation['order_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No records found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Evidence</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" alt="Enlarged Evidence" class="img-fluid" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>

<footer>
    <p>Contact us: <a href="mailto:uitm@collecthub.com" style="color: white;">uitm@collecthub.com</a></p>
</footer>

<script>
    $('#imageModal').on('show.bs.modal', function (event) {
        var img = $(event.relatedTarget);
        var src = img.data('src');
        $(this).find('.modal-body img').attr('src', src);
    });
</script>

</body>
</html>
