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
    <body>
    <div class = "container">
        <div class = "side-nav" id = "nav-blocks">
        <?php
        $activePage = 'profile';
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
                    <h2>My Profile</h2>
                </div>
            </div>

            <?php
            $loggedInUserID = $_SESSION["UID"];
            //query the user and profile table for this user
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
            ?>

            <div class = "content-item">
                <div class = "row">
                    <div class = "col-left">
                        <?php
                        if (!empty($row['profile_photo'])) {
                            echo '<img class="image" src="' . $row['profile_photo'] . '" alt="Profile Picture">';
                        } else {
                            echo '<img class="image" src="image/avatar.png" alt="Profile Picture">';
                        }
                        ?>
                        <div class="profile-edit">
                            <button class="profile-edit-button" onclick="window.location.href='profile_edit.php'">Edit Profile</button>
                        </div>
                    </div>
                    <div class = "col-right">    
                        <table border="1" width="100%" style="border-collapse: collapse;">
                            <tr>
                                <td class = "table-title" width="164">Name</td>
                                <td><?=$username?></td>
                            </tr>
                            <tr>
                                <td class = "table-title" width="164">Matric No.</td>
                                <td><?=$matricNo?></td>
                            </tr>
                            <tr>
                                <td class = "table-title" width="164">Email</td>
                                <td><?=$userEmail?></td>
                            </tr>
                            <tr>
                                <td class = "table-title" width="164">Program</td>
                                <td><?=$program?></td>
                            </tr>
                            <tr>
                                <td class = "table-title" width="164">Mentor Name</td>
                                <td><?=$mentor?></td>
                            </tr>
                        </table>
                        <p style = "font-weight: bold;">My Study Motto</p>
                        <table border="1" width="100%" style="border-collapse: collapse">
                            <tr>
                                <td width="164">
                                    <?php
                                    if($motto==""){
                                        echo "&nbsp;";
                                    }
                                    else{
                                        echo $motto;
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
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

    <?php if(isset($_SESSION['status_message'])): ?>
    <div class="modal-overlay" id="statusModal">
        <div class="modal">
            <div class="modal-icon <?php echo $_SESSION['status_message']['type']; ?>">
                <?php if($_SESSION['status_message']['type'] === 'success'): ?>
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                <?php elseif($_SESSION['status_message']['type'] === 'warning'): ?>
                    <ion-icon name="alert-circle-outline"></ion-icon>
                <?php else: ?>
                    <ion-icon name="close-circle-outline"></ion-icon>
                <?php endif; ?>
            </div>
            <div class="modal-content">
                <h2 class="modal-title"><?php echo $_SESSION['status_message']['title']; ?></h2>
                <p class="modal-message"><?php echo $_SESSION['status_message']['message']; ?></p>
                <div class="modal-actions">
                    <a href="profile.php" class="modal-button primary-button">
                        <ion-icon name="person-outline"></ion-icon>
                        View Profile
                    </a>
                    <?php if($_SESSION['status_message']['type'] !== 'success'): ?>
                    <a href="profile_edit.php" class="modal-button secondary-button">
                        <ion-icon name="create-outline"></ion-icon>
                        Edit Again
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('statusModal');
        if(modal) {
            modal.classList.add('show');
            // Auto close after 5 seconds for success
            <?php if($_SESSION['status_message']['type'] === 'success'): ?>
            setTimeout(() => {
                modal.classList.remove('show');
            }, 5000);
            <?php endif; ?>
        }
    });
    </script>

    <?php 
    unset($_SESSION['status_message']); 
    endif; 
    ?>
</body>
</html>