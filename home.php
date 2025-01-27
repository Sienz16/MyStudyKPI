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
            $activePage = 'home';
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
                    <h2>Homepage Section</h2>
                </div>
            </div>
            <div class = "content-item">
                <div class = "row">
                    <div class = "col-avatar">
                        <div class="imgcontainer">
                        <?php
                        // Check if the user is logged in
                        if(isset($_SESSION["UID"])) {
                            $loggedInUserID = $_SESSION["UID"];

                            $sql = "SELECT user.*, profile.* FROM user
                                    INNER JOIN profile ON user.userID = profile.userID
                                    WHERE user.userID = $loggedInUserID";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);

                                $matricNo = $row["matricNo"];
                                $userEmail = $row["userEmail"];
                                $username = $row["username"];
                                $program = $row["program"];
                                $mentor = $row["mentor"];
                                $motto = $row["motto"];
                            }
                                // Check if the user has a profile photo
                                if(!empty($row['profile_photo'])){
                                    echo '<img class="avatar" src="' . $row['profile_photo'] . '" alt="Profile Picture">';
                                } else {
                                    // Display default avatar if no profile photo
                                    echo '<img class="avatar" src="image/avatar.png" alt="Profile Picture">';
                                }
                                echo '<p class="welcome-text">Welcome back!</p>';
                                // Check if username exists and is not empty
                                if (!empty($username)) {
                                    echo '<p class="user-info">' . $username . '</p>';
                                    echo '<p class="matric-no">' . $matricNo . '</p>';  // Show matric as secondary info
                                } else {
                                    echo '<p class="user-info">' . $matricNo . '</p>';  // Show matric as primary info
                                }
                                echo '<p class="catchphrase">Ready to achieve excellence! 🎯<br>Track, improve, succeed.</p>';
                        }
                        else {
                            // Redirect to login page
                            header("Location: index.php");
                            exit();
                        }
                        ?>
                        </div>
                    </div>
                    <div class="col-news">
                        <div id="newsDiv" style = "margin-top: 0px">
                            <b>📣 Important Announcement 📣</b></p><br>
                            <p><b>Dear Students,</b></p>
                            <p>Exciting news awaits you on our study KPI website! We've been working hard to bring you new features and improvements to enhance your academic journey.</p> 
                            <p><br><b>The following updates are now live:</b></p>
                            <p>1. Advanced CGPA Tracker 📊</p>
                            <p>2. Improved Activity Logging 🎓</p>
                            <p>3. Enhanced Competition Records 🏆</p>
                            <p>4. Certification Showcase 📜</p>
                            <p>5. Personalized Planning Tools 📅</p>
                            <br>
                            <p><b>📰 Latest News on My Study KPI 🌐</b></p><br>
                            <p><b>New Features Alert! 🆕</b></p><br>
                            <p><b>Explore the latest additions to our My Study KPI website:</b></p>
                            <p style = "line-height: 1.5"><b>*</b> Effortlessly track your CGPA with the advanced CGPA recording feature.</p>
                            <p style = "line-height: 1.5"><b>*</b> Log your joined activities more conveniently with our improved system.</p>
                            <p style = "line-height: 1.5"><b>*</b> Showcase your achievements in competitions, challenges, and certifications with enhanced recording tools.</p>
                            <p style = "line-height: 1.5"><b>*</b> Plan your academic journey effectively using our personalized planning tools.</p>
                        </div>
                    </div>
                </div>
            </div>
            <footer>
            <p>Copyright (c) 2023 - All Reserved to Fazli</p>
            </footer>
        </div>
    </div>
    <script src = "script.js"></script> <!-- All JavaScript Function in script.js files -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>