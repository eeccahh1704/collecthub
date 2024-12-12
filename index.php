<?php
// Start the session
session_start();

// Include the database connection file
include 'db.php';

// Initialize filter variables
$filter_collect_id = $_GET['collect_id'] ?? '';
$filter_sender_name = $_GET['sender_name'] ?? '';
$filter_time = $_GET['time'] ?? '';
$filter_date = $_GET['date'] ?? '';
$filter_total_item = $_GET['total_item'] ?? '';
$filter_status = "Unclaimed"; // Always filter for "Unclaimed" status

try {
    // Base query
    $query = "SELECT * FROM innovation WHERE status = :status"; // Add a condition for status
    $params = ['status' => $filter_status];

    // Add filters dynamically if provided
    if (!empty($filter_collect_id)) {
        $query .= " AND order_id LIKE :collect_id";
        $params['collect_id'] = "%$filter_collect_id%";
    }

    if (!empty($filter_sender_name)) {
        $query .= " AND sender_name LIKE :sender_name";
        $params['sender_name'] = "%$filter_sender_name%";
    }

    if (!empty($filter_time)) {
        $query .= " AND time LIKE :time";
        $params['time'] = "%$filter_time%";
    }

    if (!empty($filter_date)) {
        $query .= " AND date = :date";
        $params['date'] = $filter_date;
    }

    if (!empty($filter_total_item)) {
        $query .= " AND total_item = :total_item";
        $params['total_item'] = $filter_total_item;
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
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
            margin-right: 15px;
            transition: all 0.3s;
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
            width: 95%;
            max-width: 1200px;
        }

        h2 {
            font-family: 'Cooper Black', serif;
            color: #2d4a59;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .table th, .table td {
            text-align: center;
            color: black;
            vertical-align: middle;
        }

        .table img {
            width: 100px; /* Fixed width for all images */
            height: 100px; /* Fixed height for all images */
            object-fit: cover; /* Crop image to fit */
            cursor: pointer; /* Make it look clickable */
        }

        .status-unclaimed {
            color: red;
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
        <img src="collecthub-removebg-preview.png" alt="UITM Logo">
        <nav>
            <a href="login.php">Log In</a>
            <a href="aboutus.php">About Us</a>
        </nav>
    </header>

    <div class="container">
        <h2>COLLECT HUB</h2>
        <form method="GET" class="mb-4">
            <div class="form-row">
                <div class="col-md-2">
                    <input type="text" name="sender_name" class="form-control" value="<?= htmlspecialchars($filter_sender_name) ?>" placeholder="Sender Name">
                </div>
                <div class="col-md-2">
                    <input type="text" name="time" class="form-control" value="<?= htmlspecialchars($filter_time) ?>" placeholder="Time">
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($filter_date) ?>">
                </div>
                <div class="col-md-2">
                    <input type="number" name="total_item" class="form-control" value="<?= htmlspecialchars($filter_total_item) ?>" placeholder="Total Items">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block">Filter</button>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Sender Name</th>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Total Items</th>
                    <th>Status</th>
                    <th>Evidence</th>
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
                                <span class="status-unclaimed"><?= htmlspecialchars($innovation['status']) ?></span>
                            </td>
                            <td>
                                <?php if (!empty($innovation['image'])): ?>
                                    <img src="uploads/<?= htmlspecialchars($innovation['image']) ?>" alt="Evidence" data-toggle="modal" data-target="#imageModal" data-src="uploads/<?= htmlspecialchars($innovation['image']) ?>">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Image Modal -->
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
                    <img src="" alt="Enlarged Image" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>Contact us: <a href="mailto:uitm@collecthub.com" style="color: white;">uitm@collecthub.com</a> | Phone: +036273628</p>
    </footer>

    <script>
        // Update modal image source when image is clicked
        $('#imageModal').on('show.bs.modal', function(event) {
            var img = $(event.relatedTarget); // The clicked image
            var src = img.data('src'); // Get the image source
            $(this).find('.modal-body img').attr('src', src); // Update modal image
        });
    </script>
</body>
</html>
