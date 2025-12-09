<?php
session_start();
include("db_connection.php");
include("header.php");

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT n.notice_date, n.heading, n.description, n.event_type, n.event_detail, n.created_at, a.name AS admin_name 
        FROM notices n
        JOIN admin a ON n.admin_id = a.admin_id
        ORDER BY n.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notices</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('b6.jpg');
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
        }
        .glass-container {
            text-align: center;
            background-color: rgba(50, 50, 54, 0.6);
            backdrop-filter: blur(5px);
            padding: 20px;
            border-radius: 10px;
            max-width: 50%;
            margin: 20px auto;
            margin-top: 135px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            gap: 20px;
        }
        .col-md-4 {
            flex: 0 0 30%;
            max-width: 30%;
            margin: 10px 0;
            display: flex;
            justify-content: center;
        }
        .notice-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            background-color: rgba(50, 50, 54, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            padding: 20px;
            text-align: center;
            width: 100%;
            max-width: 350px;
            height: auto;
        }
        .notice-card h5 {
            color: #ffda79;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .notice-card p {
            margin-bottom: 10px;
            font-size: 0.9rem;
            line-height: 1.6;
        }
        .event-detail {
            font-style: italic;
            color: #c3e88d;
        }
        .card-footer {
            font-size: 0.8rem;
            color: #a4b0be;
            background-color: rgba(50, 50, 54, 0.7);
            border-top: none;
            padding: 5px;
            width: 100%;
        }
        .logo {
            margin: 10px auto;
        }
        .logo img {
            width: 150px;
        }
        @media (max-width: 768px) {
            .col-md-4 {
                flex: 0 0 80%;
                max-width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="glass-container">
        <h1>Notice Board</h1>
    </div>

    <div class="container">
        <div class="row">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="col-md-4">
                        <div class="notice-card">
                            <div class="logo">
                                <img src="Clogo.png" alt="EmpowerHub Logo">
                            </div>
                            <h5><?php echo htmlspecialchars($row['heading']); ?></h5>
                            <p><?php echo htmlspecialchars($row['description']); ?></p>
                            <p class="event-detail">
                                <?php 
                                if ($row['event_type'] === 'online') {
                                    echo "Online Event: <a href='" . htmlspecialchars($row['event_detail']) . "' target='_blank'>Join Here</a>";
                                } else {
                                    echo "Offline Event: " . htmlspecialchars($row['event_detail']);
                                }
                                ?>
                            </p>
                            <p><small>Event Date: <?php echo htmlspecialchars($row['notice_date']); ?></small></p>
                            <div class="card-footer">
                                <small>Posted by: <?php echo htmlspecialchars($row['admin_name']); ?> (Admin Panel)</small><br>
                                <small>Publishing Date: <?php echo htmlspecialchars($row['created_at']); ?></small>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-center">No notices available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
<?php include("footer.php"); ?>
</html>
