<?php
// Home Page
// html/index.php

session_start();

if (isset($_SESSION['token'])) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ascend - Altar Server Management</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="nav-brand">
                <h1>Ascend</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="login.php" class="btn btn-primary">Login</a></li>
                <li><a href="register.php" class="btn btn-primary">Register</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <div class="container">
            <section class="hero">
                <h1>Ascend</h1>
                <p class="tagline">Open-source altar server management for Catholic parishes</p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn btn-primary btn-large">Login</a>
                    <a href="register.php" class="btn btn-success btn-large">Get Started</a>
                </div>
            </section>

            <section class="features">
                <h2>What Ascend Offers</h2>
                <div class="feature-grid">
                    <div class="feature-card">
                        <h3>Clock In/Out</h3>
                        <p>Track volunteer hours with automatic duration calculation. See your service history at a glance.</p>
                    </div>

                    <div class="feature-card">
                        <h3>Performance Tracking</h3>
                        <p>Receive detailed feedback from the Master of Ceremonies on your demeanor, timeliness, and accuracy.</p>
                    </div>

                    <div class="feature-card">
                        <h3>Training Management</h3>
                        <p>Get training referrals and track your progress. See when you last completed training sessions.</p>
                    </div>

                    <div class="feature-card">
                        <h3>Daily Prayers</h3>
                        <p>AI-generated prayers tailored to the liturgical calendar. Vesting prayers, rosary mysteries, and scripture.</p>
                    </div>

                    <div class="feature-card">
                        <h3>Scripture Readings</h3>
                        <p>Daily scripture passages to prepare spiritually. Connected to the liturgical calendar.</p>
                    </div>

                    <div class="feature-card">
                        <h3>Open Source</h3>
                        <p>Fully open-source under GNU GPL. Free forever. Customizable for your parish.</p>
                    </div>
                </div>
            </section>

            <section class="about">
                <h2>Why Ascend?</h2>
                <p>
                    Ascend is built specifically for Catholic altar servers and the parishes that support them. 
                    It combines practical ministry tools with spiritual resources, helping servers grow in their vocation 
                    while giving leaders better visibility and feedback mechanisms.
                </p>
                <p>
                    As an open-source project under the GNU GPL, any parish can access, customize, and deploy Ascend 
                    for their community at zero cost. No vendor lock-in. No subscription fees. Just software that serves the Church.
                </p>
            </section>

            <section class="cta">
                <h2>Ready to Ascend?</h2>
                <p>Join your parish in using Ascend to strengthen your altar server program.</p>
                <a href="register.php" class="btn btn-primary btn-large">Register Now</a>
            </section>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Ascend. Open-source under GNU GPL.</p>
        <p><a href="https://github.com/ParishTech/ascendapp">GitHub</a> | <a href="/phpmyadmin">phpMyAdmin</a></p>
    </footer>

    <style>
        .hero {
            text-align: center;
            padding: 4rem 0;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 8px;
            margin-bottom: 3rem;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .tagline {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .features {
            margin: 4rem 0;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .feature-card {
            background-color: var(--bg-primary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
        }

        .feature-card h3 {
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .about {
            background-color: var(--bg-secondary);
            padding: 3rem;
            border-radius: 8px;
            margin: 3rem 0;
        }

        .about p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 1.5rem;
        }

        .cta {
            text-align: center;
            padding: 3rem;
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            border-radius: 8px;
            margin: 3rem 0;
        }

        .cta h2 {
            color: white;
            margin-bottom: 1rem;
        }

        footer {
            text-align: center;
            padding: 2rem;
            border-top: 1px solid var(--border-color);
            margin-top: 4rem;
            color: var(--text-secondary);
        }

        footer a {
            color: var(--primary-color);
            text-decoration: none;
        }
    </style>
</body>
</html>
