<?php
// Dashboard Page
// html/dashboard.php

session_start();

if (!isset($_SESSION['token'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ascend - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <nav class="navbar">
                <div class="nav-brand">
                    <h1>Ascend</h1>
                </div>
                <ul class="nav-menu">
                    <li><a href="dashboard.php" class="active">Dashboard</a></li>
                    <li><a href="clock.php">Clock In/Out</a></li>
                    <li><a href="prayers.php">Prayers</a></li>
                    <li><a href="performance.php">Performance</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <div class="dashboard-header">
                <h2>Welcome, <?php echo htmlspecialchars($user['name']); ?></h2>
                <p class="role-badge"><?php echo ucfirst($user['role']); ?></p>
            </div>

            <div class="dashboard-grid">
                <!-- Stat Cards -->
                <div class="stat-card">
                    <h3>Total Hours</h3>
                    <p class="stat-value" id="total-hours">0</p>
                    <p class="stat-label">This month</p>
                </div>

                <div class="stat-card">
                    <h3>Sessions</h3>
                    <p class="stat-value" id="total-sessions">0</p>
                    <p class="stat-label">Masses served</p>
                </div>

                <div class="stat-card">
                    <h3>Average Rating</h3>
                    <p class="stat-value" id="avg-rating">--</p>
                    <p class="stat-label">From feedback</p>
                </div>

                <div class="stat-card">
                    <h3>Status</h3>
                    <p class="stat-value" id="clock-status">Off Duty</p>
                    <button id="clock-toggle" class="btn btn-primary">Clock In</button>
                </div>
            </div>

            <!-- Recent Activity -->
            <section class="recent-activity">
                <h3>Recent Clock Records</h3>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody id="activity-table">
                        <tr>
                            <td colspan="4" class="text-center">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Performance Feedback -->
            <?php if ($user['role'] === 'server') { ?>
            <section class="performance-section">
                <h3>Recent Feedback</h3>
                <div id="feedback-list">
                    <p class="text-center">Loading feedback...</p>
                </div>
            </section>
            <?php } ?>
        </main>
    </div>

    <script src="js/api-client.js"></script>
    <script src="js/dashboard.js"></script>
</body>
</html>
