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
                $name = $row["username"];
                $program = $row["program"];
                $mentor = $row["mentor"];
                $motto = $row["motto"];
            }
            ?>

            <div class = "content-item">
                <div class = "row">
                    <div class = "col-left">
                        <?php
                        // Check if the user has a profile photo
                        if (!empty($row['profile_photo'])) {
                            echo '<img class = "image" src = "' . $row['profile_photo'] . '" alt = "Profile Picture">';
                        } else {
                            // Display default avatar if no profile photo
                            echo '<img class = "image" src = "image/avatar.png" alt = "Profile Picture">';
                        }
                        ?>
                    </div>
                    <div class = "col-right">    
                    <form id="profile" action="profile_edit_action.php" method="post" enctype="multipart/form-data">
                        <table id="profile_table" width="100%">
                            <tr>
                                <td width="164">Matric No.</td>
                                <td><?=$matricNo?></td>
                            </tr>
                            <tr>
                                <td width="164">Email</td>
                                <td><?=$userEmail?></td>
                            </tr>
                            <tr>
                                <td width="164">Name</td>
                                <td><input type="text" name="username" size="20" value="<?=$name?>"></td>
                            </tr>                    
                            <tr>
                                <td width="164">Program</td>
                                <td><select size="1" name="program">
                                <option value="" <?php echo ($program == '') ? 'selected' : ''; ?> disabled >Select Program</option>   
                                <option <?php echo ($program == 'Software Engineering') ? 'selected' : ''; ?>>Software Engineering</option>
                                <option <?php echo ($program == 'Network Engineering') ? 'selected' : ''; ?>>Network Engineering</option>
                                <option <?php echo ($program == 'Data Science') ? 'selected' : ''; ?>>Data Science</option>
                                </select></td>
                            </tr>
                            <tr>
                                <td width="164">Mentor Name</td>
                                <td><input type="text" name="mentor" size="20" value="<?=$mentor?>"></td>
                            </tr>
                            <tr>
                                <td width="164">Profile Photo</td>
                                <td>
                                    <input type="file" name="profile_photo">
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2"> 
                                    My Study Motto:                           
                                    <textarea rows="2" name="motto" style="width:97%"><?=$motto?></textarea>
                                </td>
                            </tr>
                        </table>
                        <div class="form-buttons">
                            <button type="submit" value="Update">
                                <ion-icon name="save-outline"></ion-icon>
                                Save Changes
                            </button>
                            <button type="reset" value="Reset">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Reset
                            </button>
                            <a href="profile.php">
                                <button type="button">
                                    <ion-icon name="close-outline"></ion-icon>
                                    Cancel
                                </button>
                            </a>
                        </div>
                    </form>
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
</html>