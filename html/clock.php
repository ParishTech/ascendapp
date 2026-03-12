<?php
// Clock In/Out Page
// html/clock.php

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
    <title>Ascend - Clock In/Out</title>
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
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="clock.php" class="active">Clock In/Out</a></li>
                    <li><a href="prayers.php">Prayers</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <h2>Clock In/Out</h2>

            <div class="clock-container">
                <div class="clock-card">
                    <h3>Current Mass</h3>
                    <div id="mass-info" class="mass-info">
                        <select id="mass-select" class="form-control">
                            <option value="">Select a Mass...</option>
                        </select>
                    </div>

                    <div class="clock-status">
                        <p id="status-text" class="status-message off-duty">Not clocked in</p>
                        <p id="elapsed-time" class="elapsed-time"></p>
                    </div>

                    <div class="clock-buttons">
                        <button id="clock-in-btn" class="btn btn-success btn-large" onclick="clockIn()">
                            Clock In
                        </button>
                        <button id="clock-out-btn" class="btn btn-danger btn-large" style="display: none;" onclick="clockOut()">
                            Clock Out
                        </button>
                    </div>
                </div>

                <div class="clock-card">
                    <h3>Today's Sessions</h3>
                    <div id="today-sessions" class="sessions-list">
                        <p class="text-center">No sessions yet today</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="js/api-client.js"></script>
    <script src="js/clock.js"></script>
</body>
</html>
