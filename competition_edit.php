<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>My Study KPI</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <div class="container">
        <div class="side-nav" id="nav-blocks">
            <?php
            $activePage = 'kpi';
            include 'logged_menu.php';
            ?>
        </div>

        <div class="content-section">
            <div class="header">
                <div class="nav-toggle" onclick="toggleNav()">
                    <ion-icon name="list-outline"></ion-icon>
                </div>
            </div>
            <div class="header-title">
                <div class="title">
                    <span>My Study KPI</span>
                    <h2>My KPI Indicator</h2>
                </div>
            </div>

            <?php
            // Get existing competition data
            if(isset($_GET["id"]) && $_GET["id"] != "") {
                $comp_id = $_GET["id"];
                $sql = "SELECT * FROM competition WHERE comp_id = ? AND userID = ?";
                $stmt = mysqli_prepare($conn, $sql);
                
                if($stmt) {
                    mysqli_stmt_bind_param($stmt, "ii", $comp_id, $_SESSION["UID"]);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $row = mysqli_fetch_assoc($result);
                    mysqli_stmt_close($stmt);
                }
            }
            ?>

            <div class="content-item">
                <div class="section-header">
                    <h2>
                        <ion-icon name="create-outline"></ion-icon>
                        Edit Competition
                    </h2>
                </div>
                
                <div class="form-container">
                    <form method="POST" action="competition_edit_action.php" enctype="multipart/form-data" id="myForm">
                        <input type="hidden" name="comp_id" value="<?php echo $comp_id; ?>">
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Semester*</label>
                                <select name="sem" required>
                                    <option value="">Select Semester</option>
                                    <option value="1" <?php echo ($row['sem'] == 1) ? 'selected' : ''; ?>>1</option>
                                    <option value="2" <?php echo ($row['sem'] == 2) ? 'selected' : ''; ?>>2</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Year*</label>
                                <input type="text" name="year" value="<?php echo $row['year']; ?>" 
                                       placeholder="e.g., 2023/2024" pattern="\d{4}(/\d{4})?" 
                                       title="Enter year as YYYY or YYYY/YYYY" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Competition Name*</label>
                            <textarea name="comp_name" rows="3" 
                                    placeholder="Enter the name or description of your competition" 
                                    required><?php echo $row['comp_name']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Level*</label>
                            <select name="level" required>
                                <option value="">Select Level</option>
                                <?php
                                $levels = ['Faculty', 'University', 'National', 'International'];
                                foreach($levels as $level) {
                                    $selected = ($row['level'] == $level) ? 'selected' : '';
                                    echo "<option value='$level' $selected>$level</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remark" rows="4" 
                                    placeholder="Enter any additional notes about the competition"><?php echo $row['remark']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label>Current Photo</label>
                            <?php if($row['img_path']) { ?>
                                <div class="current-photo">
                                    <a href="uploads/<?php echo $row['img_path']; ?>" target="_blank" class="photo-link">
                                        <ion-icon name="image-outline"></ion-icon>
                                        View Current Photo
                                    </a>
                                </div>
                            <?php } ?>
                            
                            <label style="margin-top: 1rem;">Upload New Photo</label>
                            <div class="file-upload">
                                <input type="file" name="fileToUpload" accept=".jpg, .jpeg, .png">
                                <p class="file-hint">Max size: 488.28KB. Formats: JPG, JPEG, PNG</p>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="submit-button">
                                <ion-icon name="checkmark-outline"></ion-icon>
                                Update Competition
                            </button>
                            <button type="reset" class="reset-button">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Reset
                            </button>
                            <button type="button" class="cancel-button" onclick="window.location.href='competition.php'">
                                <ion-icon name="close-outline"></ion-icon>
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <footer>
                <p>Copyright (c) 2023 - All Reserved to Fazli</p>
            </footer>   
        </div>
    </div>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <?php include 'modal.php'; ?>
</body>
</html>