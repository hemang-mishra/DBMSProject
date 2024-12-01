<?php
session_start();
include("../db_connection.php");

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Query to fetch consumers
$sql = "SELECT * FROM farmer";
$result = $conn->query($sql);

if ($result === false) {
    die("Error fetching consumers: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="../css/consumer_list.css"> <!-- Link to external CSS in css folder-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="main-bar">
        <button class="redirect-button" onclick="location.href='admin.php'">&larr;</button>

        <h1>Farmer List</h1>
    </div>

    <div class="container">
        <div class="table-container">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Farmer ID</th>
                        <th>Name</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['f_id']); ?></td>
                                <td><?= htmlspecialchars($row['name']); ?></td>
                                <td><?= htmlspecialchars($row['address']); ?></td>
                                <td><?= htmlspecialchars($row['contact']); ?></td>
                                <td>
                                    <button
                                        class="btn btn-danger btn-sm"
                                        onclick="confirmDeletion(<?= htmlspecialchars($row['f_id']); ?>)">
                                        &#10060; <!-- Red Cross Icon -->
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No consumers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
<script>
    function confirmDeletion(userId) {
        if (confirm("Are you sure you want to delete this farmer?")) {
            window.location.href = `delete_user.php?id=${userId}`;
        }
    }
</script>
<?php
$conn->close();
?>