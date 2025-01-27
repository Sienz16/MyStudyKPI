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

            <div class="content-item">
                <div class="kpi-tab">
                    <button class="tab-button" onclick="window.location.href='cgpa.php'">
                        <ion-icon name="school-outline"></ion-icon>
                        CGPA
                    </button>
                    <button class="tab-button" onclick="window.location.href='activity.php'">
                        <ion-icon name="calendar-outline"></ion-icon>
                        Activity
                    </button>
                    <button class="tab-button active" onclick="window.location.href='competition.php'">
                        <ion-icon name="trophy-outline"></ion-icon>
                        Competition
                    </button>
                    <button class="tab-button" onclick="window.location.href='certification.php'">
                        <ion-icon name="ribbon-outline"></ion-icon>
                        Certification
                    </button>
                    <button class="tab-button" onclick="window.location.href='kpi_indicator.php'">
                        <ion-icon name="analytics-outline"></ion-icon>
                        KPI
                    </button>
                </div>
            </div>

            <div class="content-item">
                <div class="activity-stats">
                    <?php
                    // Get competition statistics
                    $sql = "SELECT 
                            COUNT(*) as total_competitions,
                            COUNT(CASE WHEN level = 'International' THEN 1 END) as international,
                            COUNT(CASE WHEN level = 'National' THEN 1 END) as national,
                            COUNT(CASE WHEN level = 'University' THEN 1 END) as university,
                            COUNT(CASE WHEN level = 'Faculty' THEN 1 END) as faculty
                            FROM competition 
                            WHERE userID=" . $_SESSION["UID"];
                    
                    $result = mysqli_query($conn, $sql);
                    $stats = mysqli_fetch_assoc($result);
                    ?>
                    <div class="activity-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="globe-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>International Level</h3>
                            <p><?php echo $stats['international']; ?></p>
                        </div>
                    </div>

                    <div class="activity-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="flag-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>National Level</h3>
                            <p><?php echo $stats['national']; ?></p>
                        </div>
                    </div>

                    <div class="activity-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="school-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>University Level</h3>
                            <p><?php echo $stats['university']; ?></p>
                        </div>
                    </div>

                    <div class="activity-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="business-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>Faculty Level</h3>
                            <p><?php echo $stats['faculty']; ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content-item" id="listAndButton">
                <div class="section-header">
                    <h2>
                        <ion-icon name="list-outline"></ion-icon>
                        Competition Records
                    </h2>
                    <button class="add-button" onclick="toggleFormVisibility()">
                        <ion-icon name="add-outline"></ion-icon>
                        Add New Competition
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Semester & Year</th>
                                <th>Competition Name</th>
                                <th>Level</th>
                                <th>Photo</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM competition WHERE userID=" . $_SESSION["UID"] . " ORDER BY year ASC, sem ASC";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $numrow = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $numrow; ?></td>
                                        <td>Semester <?php echo $row["sem"] . " " . $row["year"]; ?></td>
                                        <td><?php echo $row["comp_name"]; ?></td>
                                        <td>
                                            <span class="level-badge <?php echo strtolower($row["level"]); ?>">
                                                <?php echo $row["level"]; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if($row["img_path"]) { ?>
                                                <a href="uploads/<?php echo $row["img_path"]; ?>" target="_blank" class="photo-link">
                                                    <ion-icon name="image-outline"></ion-icon>
                                                    View Photo
                                                </a>
                                            <?php } else { ?>
                                                <span class="no-photo">No Photo</span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $row["remark"]; ?></td>
                                        <td class="action-buttons">
                                            <a href="competition_edit.php?id=<?php echo $row["comp_id"]; ?>" class="edit-btn">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                            <a href="competition_delete.php?id=<?php echo $row["comp_id"]; ?>" 
                                               class="delete-btn" 
                                               onclick="return confirm('Are you sure you want to delete this competition?');">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $numrow++;
                                }
                            } else {
                                echo '<tr><td colspan="7" class="no-records">No competitions recorded yet</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-item" id="formDiv" style="display: none;">
                <div class="section-header">
                    <h2>
                        <ion-icon name="add-circle-outline"></ion-icon>
                        Add New Competition
                    </h2>
                </div>
                
                <div class="form-container">
                    <form method="POST" action="competition_action.php" enctype="multipart/form-data" id="myForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Semester*</label>
                                <select name="sem" required>
                                    <option value="">Select Semester</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Year*</label>
                                <input type="text" name="year" placeholder="e.g., 2023/2024" 
                                       pattern="\d{4}(/\d{4})?" title="Enter year as YYYY or YYYY/YYYY" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Competition Name*</label>
                            <textarea name="comp_name" rows="3" 
                                    placeholder="Enter the name or description of your competition" required></textarea>
                        </div>

                        <div class="form-group">
                            <label>Level*</label>
                            <select name="level" required>
                                <option value="">Select Level</option>
                                <option value="Faculty">Faculty</option>
                                <option value="University">University</option>
                                <option value="National">National</option>
                                <option value="International">International</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Remarks</label>
                            <textarea name="remark" rows="4" 
                                    placeholder="Enter any additional notes about the competition"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Upload Photo</label>
                            <div class="file-upload">
                                <input type="file" name="fileToUpload" accept=".jpg, .jpeg, .png">
                                <p class="file-hint">Max size: 488.28KB. Formats: JPG, JPEG, PNG</p>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="submit-button">
                                <ion-icon name="add-circle-outline"></ion-icon>
                                Add Competition
                            </button>
                            <button type="reset" class="reset-button">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Reset
                            </button>
                            <button type="button" class="cancel-button" onclick="toggleFormVisibility()">
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
    <?php 
    if(isset($_SESSION['status_message'])) {
        if($_SESSION['status_message']['type'] === 'error') {
            include 'error_modal.php';
        } else {
            include 'modal.php';
        }
    }
    ?>
</body>
</html>