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
                    <button class="tab-button active" data-tab="cgpa" onclick="window.location.href='cgpa.php'">
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
            <div class = "content-item cgpa-overview">
                <div class="cgpa-stats">
                    <?php
                    // Get all CGPA records for calculations
                    $sql = "SELECT * FROM cgpa WHERE userID=" . $_SESSION["UID"] . " ORDER BY year ASC, sem ASC";
                    $result = mysqli_query($conn, $sql);
                    
                    // Initialize variables
                    $current_cgpa = 0;
                    $target_cgpa = 0;
                    $total_gpa = 0;
                    $count = 0;
                    $latest_target = 0;
                    $average_gpa = 0; // Initialize average_gpa here
                    
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $total_gpa += floatval($row["result_cgpa"]);
                            $count++;
                            // Keep track of the latest target
                            $latest_target = floatval($row["mykpi_cgpa"]);
                            // The last record will be our current CGPA
                            $current_cgpa = floatval($row["result_cgpa"]);
                        }
                        
                        // Calculate average GPA only if there are records
                        if ($count > 0) {
                            $average_gpa = round($total_gpa / $count, 2);
                            $target_cgpa = $latest_target;
                        }
                    }
                    
                    // Reset result pointer for table display
                    mysqli_data_seek($result, 0);
                    ?>
                    <div class="cgpa-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="trending-up-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>Current CGPA</h3>
                            <div class="stat-value"><?php echo number_format($current_cgpa, 2); ?></div>
                        </div>
                    </div>
                    <div class="cgpa-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="flag-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>Target CGPA</h3>
                            <div class="stat-value"><?php echo number_format($target_cgpa, 2); ?></div>
                        </div>
                    </div>
                    <div class="cgpa-stat-card">
                        <div class="stat-icon">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                        </div>
                        <div class="stat-info">
                            <h3>Average GPA</h3>
                            <div class="stat-value"><?php echo number_format($average_gpa, 2); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-item" id="listAndButton">
                <div class="section-header">
                    <h2>
                        <ion-icon name="list-outline"></ion-icon>
                        GPA Records
                    </h2>
                    <button class="add-button" onclick="toggleFormVisibility()">
                        <ion-icon name="add-outline"></ion-icon>
                        Add New Record
                    </button>
                </div>
                
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Semester & Year</th>
                                <th>Target GPA</th>
                                <th>Achieved GPA</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM cgpa WHERE userID=" . $_SESSION["UID"] . " ORDER BY year ASC, sem ASC";
                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                $numrow = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    // Calculate status
                                    $status = $row["result_cgpa"] >= $row["mykpi_cgpa"] ? "achieved" : "not-achieved";
                                    ?>
                                    <tr>
                                        <td><?php echo $numrow; ?></td>
                                        <td>Semester <?php echo $row["sem"] . " " . $row["year"]; ?></td>
                                        <td><?php echo $row["mykpi_cgpa"]; ?></td>
                                        <td><?php echo $row["result_cgpa"]; ?></td>
                                        <td>
                                            <span class="status-badge <?php echo $status; ?>">
                                                <?php echo $status == "achieved" ? "Achieved" : "Not Achieved"; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row["remark"]; ?></td>
                                        <td class="action-buttons">
                                            <a href="cgpa_edit.php?id=<?php echo $row["cgpa_id"]; ?>" class="edit-btn">
                                                <ion-icon name="create-outline"></ion-icon>
                                            </a>
                                            <a href="cgpa_delete.php?id=<?php echo $row["cgpa_id"]; ?>" 
                                               class="delete-btn" 
                                               onClick="return confirm('Are you sure you want to delete this record?');">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                    $numrow++;
                                }
                            } else {
                                echo '<tr><td colspan="7" class="no-records">No records found</td></tr>';
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
                        Add New GPA Record
                    </h2>
                </div>
                
                <div class="form-container">
                    <form method="POST" action="cgpa_action.php" id="myForm">
                        <div class="form-group">
                            <label for="semester">Semester*</label>
                            <select id="semester" name="sem" required>
                                <option value="">Select Semester</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="academic-year">Year*</label>
                            <input type="text" 
                                   id="academic-year" 
                                   name="year" 
                                   placeholder="e.g., 2023/2024" 
                                   pattern="\d{4}(/\d{4})?" 
                                   title="Enter year as YYYY or YYYY/YYYY" 
                                   required>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="target-gpa">Target GPA*</label>
                                <input type="number" 
                                       id="target-gpa" 
                                       name="mykpi_cgpa" 
                                       min="0" 
                                       max="4" 
                                       step="0.01" 
                                       placeholder="0.00" 
                                       required>
                            </div>

                            <div class="form-group">
                                <label for="achieved-gpa">Achieved GPA*</label>
                                <input type="number" 
                                       id="achieved-gpa" 
                                       name="result_cgpa" 
                                       min="0" 
                                       max="4" 
                                       step="0.01" 
                                       placeholder="0.00" 
                                       required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea id="remarks" 
                                      name="remark" 
                                      rows="4" 
                                      placeholder="Enter any additional notes or comments about your performance"></textarea>
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
</body>
</html>