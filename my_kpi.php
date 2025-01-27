<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale = 1.0">
        <title>My Study KPI</title>
        <link rel = "stylesheet" href = "css/style.css">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
    <div class = "container">
        <div class = "side-nav" id = "nav-blocks">
            <?php
            $activePage = 'kpi';
            include 'logged_menu.php';
            ?>
        </div>

        <div class = "content-section">
            <div class = "header">
                <div class = "nav-toggle" onclick = "toggleNav()">
                    <ion-icon name="list-outline"></ion-icon>
                </div>
            </div>
            <div class = "header-title">
                <div class = "title">
                    <span>My Study KPI</span>
                    <h2>My KPI Indicator</h2>
                </div>
            </div>
            <div class = "content-item">
                <div class="kpi-tab">
                    <button class="tab-button" data-tab="cgpa" onclick="window.location.href='cgpa.php'">
                        <ion-icon name="school-outline"></ion-icon>
                        CGPA
                    </button>
                    <button class="tab-button" data-tab="activity" onclick="window.location.href='activity.php'">
                        <ion-icon name="calendar-outline"></ion-icon>
                        Activity
                    </button>
                    <button class="tab-button" data-tab="competition" onclick="window.location.href='competition.php'">
                        <ion-icon name="trophy-outline"></ion-icon>
                        Competition
                    </button>
                    <button class="tab-button" data-tab="certification" onclick="window.location.href='certification.php'">
                        <ion-icon name="ribbon-outline"></ion-icon>
                        Certification
                    </button>
                    <button class="tab-button" data-tab="kpi" onclick="window.location.href='kpi_indicator.php'">
                        <ion-icon name="analytics-outline"></ion-icon>
                        KPI
                    </button>
                </div>
            </div>
            <div class="content-item welcome-section">
                <div class="welcome-message">
                    <ion-icon name="bar-chart-outline"></ion-icon>
                    <h2>Manage Your Academic Progress</h2>
                    <p>Track and monitor your academic journey through various key performance indicators</p>
                </div>
                <div class="kpi-categories">
                    <div class="kpi-category">
                        <ion-icon name="school-outline"></ion-icon>
                        <h3>CGPA Tracking</h3>
                        <p>Monitor your academic performance</p>
                    </div>
                    <div class="kpi-category">
                        <ion-icon name="calendar-outline"></ion-icon>
                        <h3>Activities</h3>
                        <p>Record your extracurricular involvement</p>
                    </div>
                    <div class="kpi-category">
                        <ion-icon name="trophy-outline"></ion-icon>
                        <h3>Competitions</h3>
                        <p>Track your competitive achievements</p>
                    </div>
                    <div class="kpi-category">
                        <ion-icon name="ribbon-outline"></ion-icon>
                        <h3>Certifications</h3>
                        <p>Document your professional growth</p>
                    </div>
                </div>
            </div>
        </div>
            <footer>
            <p>Copyright (c) 2023 - All Reserved to Fazli</p>
            </footer>
    </div>
    <script src = "script.js"></script> <!-- All JavaScript Function in script.js files -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>