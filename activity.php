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
                    <button class="tab-button active" data-tab="activity" onclick="window.location.href='activity.php'">
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
            <div class = "content-item activity-overview">
                <div class="activity-stats">
                    <?php
                    // Get activity statistics
                    $sql = "SELECT 
                            COUNT(*) as total_activities,
                            COUNT(CASE WHEN level = 'International' THEN 1 END) as international,
                            COUNT(CASE WHEN level = 'National' THEN 1 END) as national,
                            COUNT(CASE WHEN level = 'University' THEN 1 END) as university,
                            COUNT(CASE WHEN level = 'Faculty' THEN 1 END) as faculty
                            FROM activities 
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
                            <div class="stat-value"><?php echo $stats['international']; ?></div>
                        </div>
                    </div>
                    <div class="activity-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="flag-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>National Level</h3>
                            <div class="stat-value"><?php echo $stats['national']; ?></div>
                        </div>
                    </div>
                    <div class="activity-stat-card">
                        <div class="stat-icon university">
                            <ion-icon name="school-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>University Level</h3>
                            <div class="stat-value"><?php echo $stats['university']; ?></div>
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
            <div class = "content-item" id="listAndButton">
                <div class="section-header">
                    <h2>
                        <ion-icon name="list-outline"></ion-icon>
                        Activity Records
                    </h2>
                    <button class="add-button" onclick="toggleFormVisibility()">
                        <ion-icon name="add-outline"></ion-icon>
                        Add New Activity
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Semester & Year</th>
                                <th>Activity Name</th>
                                <th>Level</th>
                                <th>Photo</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM activities WHERE userID=" . $_SESSION["UID"] . " ORDER BY year ASC, sem ASC";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $numrow = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $numrow; ?></td>
                                        <td>Semester <?php echo $row["sem"] . " " . $row["year"]; ?></td>
                                        <td><?php echo $row["activity_name"]; ?></td>
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
                                        <td><?php echo $row["remarks"]; ?></td>
                                        <td class="action-buttons">
                                            <a href="activity_edit.php?id=<?php echo $row["activity_id"]; ?>" class="edit-btn">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                            <a href="activity_delete.php?id=<?php echo $row["activity_id"]; ?>" 
                                               class="delete-btn" 
                                               onclick="return confirm('Are you sure you want to delete this activity?');">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $numrow++;
                                }
                            } else {
                                echo '<tr><td colspan="7" class="no-records">No activities recorded yet</td></tr>';
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
                        Add New Activity
                    </h2>
                </div>
                
                <div class="form-container">
                    <form method="POST" action="activity_action.php" enctype="multipart/form-data" id="myForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Semester*</label>
                                <select id="sem" name="sem" required>
                                    <option value="">Select Semester</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Year*</label>
                                <input type="text" name="year" placeholder="e.g., 2023/2024" pattern="\d{4}(/\d{4})?" title="Enter year as YYYY or YYYY/YYYY" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Activity Name*</label>
                            <textarea name="activity_name" rows="3" placeholder="Enter the name or description of your activity" required></textarea>
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
                            <textarea name="remarks" rows="4" placeholder="Enter any additional notes about the activity"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Upload Photo</label>
                            <div class="file-upload">
                                <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg, .jpeg, .png">
                                <p class="file-hint">Max size: 488.28KB. Formats: JPG, JPEG, PNG</p>
                            </div>
                        </div>

                        <div class="form-buttons">
                            <button type="submit" class="submit-button">
                                <ion-icon name="checkmark-outline"></ion-icon>
                                Submit
                            </button>
                            <button type="reset" class="reset-button">
                                <ion-icon name="refresh-outline"></ion-icon>
                                Reset
                            </button>
                            <button type="button" class="cancel-button" onclick="hideForm()">
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
    <script src = "script.js"></script> <!-- All JavaScript Function in script.js files -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <?php if(isset($_SESSION['status_message'])): ?>
    <div class="modal-overlay" id="statusModal">
        <div class="modal">
            <div class="modal-icon <?php echo $_SESSION['status_message']['type']; ?>">
                <?php if($_SESSION['status_message']['type'] === 'success'): ?>
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                <?php else: ?>
                    <ion-icon name="close-circle-outline"></ion-icon>
                <?php endif; ?>
            </div>
            <div class="modal-content">
                <h2 class="modal-title"><?php echo $_SESSION['status_message']['title']; ?></h2>
                <p class="modal-message"><?php echo $_SESSION['status_message']['message']; ?></p>
                <div class="modal-actions">
                    <a href="activity.php" class="modal-button primary-button">
                        <ion-icon name="list-outline"></ion-icon>
                        View Activities
                    </a>
                    <?php if($_SESSION['status_message']['type'] !== 'success'): ?>
                    <button onclick="history.back()" class="modal-button secondary-button">
                        <ion-icon name="arrow-back-outline"></ion-icon>
                        Go Back
                    </button>
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