<?php
// Prayers Page
// html/prayers.php

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
    <title>Ascend - Prayers & Resources</title>
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
                    <li><a href="clock.php">Clock In/Out</a></li>
                    <li><a href="prayers.php" class="active">Prayers</a></li>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <h2>Prayers & Spiritual Resources</h2>

            <!-- Daily Prayer -->
            <section class="prayer-section">
                <h3>Today's Prayer</h3>
                <div class="prayer-card">
                    <div id="daily-prayer" class="prayer-content">
                        <p class="text-center">Loading today's prayer...</p>
                    </div>
                </div>
            </section>

            <!-- Scripture Reading -->
            <section class="prayer-section">
                <h3>Scripture Reading</h3>
                <div class="prayer-card">
                    <div id="daily-scripture" class="scripture-content">
                        <p class="text-center">Loading scripture...</p>
                    </div>
                </div>
            </section>

            <!-- Vesting Prayers -->
            <section class="prayer-section">
                <h3>Vesting Prayers</h3>
                <div class="prayer-list">
                    <div class="prayer-item">
                        <h4>Prayer Before Vesting</h4>
                        <p>
                            "Almighty God, I stand now to serve You in your sacred house. 
                            May I approach this holy work with reverence and devotion, 
                            giving witness to Your glory and your love for all people."
                        </p>
                    </div>

                    <div class="prayer-item">
                        <h4>After Vesting</h4>
                        <p>
                            "I am now vested to serve at the altar of the Lord. 
                            May my actions reflect the dignity of this calling, 
                            and may I be a faithful witness to the mystery of faith."
                        </p>
                    </div>
                </div>
            </section>

            <!-- Rosary Mysteries -->
            <section class="prayer-section">
                <h3>Rosary Mysteries</h3>
                <div class="prayer-list">
                    <div class="mystery-group">
                        <h4>Joyful Mysteries (Monday & Saturday)</h4>
                        <ol>
                            <li>The Annunciation</li>
                            <li>The Visitation</li>
                            <li>The Nativity</li>
                            <li>The Presentation</li>
                            <li>The Finding of Jesus in the Temple</li>
                        </ol>
                    </div>

                    <div class="mystery-group">
                        <h4>Sorrowful Mysteries (Tuesday & Friday)</h4>
                        <ol>
                            <li>The Agony in the Garden</li>
                            <li>The Scourging at the Pillar</li>
                            <li>The Crowning with Thorns</li>
                            <li>The Carrying of the Cross</li>
                            <li>The Crucifixion</li>
                        </ol>
                    </div>

                    <div class="mystery-group">
                        <h4>Glorious Mysteries (Wednesday & Sunday)</h4>
                        <ol>
                            <li>The Resurrection</li>
                            <li>The Ascension</li>
                            <li>The Descent of the Holy Spirit</li>
                            <li>The Assumption of Mary</li>
                            <li>The Coronation of Mary</li>
                        </ol>
                    </div>

                    <div class="mystery-group">
                        <h4>Luminous Mysteries (Thursday)</h4>
                        <ol>
                            <li>The Baptism of Jesus</li>
                            <li>The Wedding at Cana</li>
                            <li>The Proclamation of the Kingdom</li>
                            <li>The Transfiguration</li>
                            <li>The Institution of the Eucharist</li>
                        </ol>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="js/api-client.js"></script>
    <script src="js/prayers.js"></script>
</body>
</html>
