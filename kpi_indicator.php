<?php
session_start();
include("config.php");

// Function to get cumulative cgpa
function getCumulativeCGPA($column) {
    global $conn;
    $userID = $_SESSION["UID"];
    $query = "SELECT AVG($column) AS cumulative_cgpa FROM cgpa WHERE userID = $userID";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return number_format($row['cumulative_cgpa'], 2);
}

// Function to get unique semesters and years
function getUniqueSemesters($conn, $userID) {
    $semesters = [];
    
    // Get unique semester/year combinations from all tables
    $tables = ['activities', 'competition', 'certification'];
    
    foreach ($tables as $table) {
        $sql = "SELECT DISTINCT sem, year FROM $table 
                WHERE userID = $userID 
                ORDER BY year ASC, sem ASC";
        $result = mysqli_query($conn, $sql);
        
        while ($row = mysqli_fetch_assoc($result)) {
            $key = $row['year'] . '_' . $row['sem'];
            if (!isset($semesters[$key])) {
                $semesters[$key] = [
                    'sem' => $row['sem'],
                    'year' => $row['year']
                ];
            }
        }
    }
    
    // Sort by year and semester
    ksort($semesters);
    return array_values($semesters);
}

// Function to get activity count for a specific semester
function getActivityCountBySemester($conn, $userID, $level, $sem, $year) {
    $tables = ['activities', 'competition', 'certification'];
    $totalCount = 0;
    
    foreach ($tables as $table) {
        $sql = "SELECT COUNT(*) as count FROM $table 
                WHERE userID = $userID 
                AND level = '$level' 
                AND sem = $sem 
                AND year = '$year'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $totalCount += $row['count'];
    }
    
    return $totalCount;
}

// Add this after your existing functions
function getKPITargets($conn, $userID) {
    $sql = "SELECT * FROM activity_kpi WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return $row;
    }
    
    return [
        'faculty_target' => 2,
        'university_target' => 2,
        'national_target' => 1,
        'international_target' => 1
    ];
}

// Add this new function at the top with other functions
function getCGPABySemester($conn, $userID, $sem, $year) {
    $sql = "SELECT result_cgpa FROM cgpa 
            WHERE userID = ? AND sem = ? AND year = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iis", $userID, $sem, $year);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return number_format($row['result_cgpa'], 2);
    }
    return "-";
}

// Add this function after getKPITargets function
function getKPITarget($conn, $userID, $level) {
    $sql = "SELECT {$level}_target as target FROM activity_kpi WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($row = mysqli_fetch_assoc($result)) {
        return $row['target'];
    }
    
    // Default values if no custom KPI set
    $defaults = [
        'faculty' => 2,
        'university' => 2,
        'national' => 1,
        'international' => 1
    ];
    
    return $defaults[$level];
}

// Add this new function to check CGPA achievement
function checkCGPAAchievement($conn, $userID) {
    // Get target CGPA (my KPI)
    $sql = "SELECT AVG(mykpi_cgpa) as target_cgpa FROM cgpa WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $target_cgpa = $row['target_cgpa'];

    // Get actual cumulative CGPA
    $sql = "SELECT AVG(result_cgpa) as current_cgpa FROM cgpa WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $current_cgpa = $row['current_cgpa'];

    return [
        'target' => number_format($target_cgpa, 2),
        'current' => number_format($current_cgpa, 2),
        'achieved' => $current_cgpa >= $target_cgpa
    ];
}

// Add this function to check activity achievements
function checkActivityAchievement($conn, $userID, $level) {
    // Get target from activity_kpi table
    $sql = "SELECT {$level}_target as target FROM activity_kpi WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $target = $row ? $row['target'] : 0;

    // Count total activities across all tables for this level
    $tables = ['activities', 'competition', 'certification'];
    $total_count = 0;
    
    foreach ($tables as $table) {
        $sql = "SELECT COUNT(*) as count FROM $table 
                WHERE userID = ? AND level = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "is", $userID, $level);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        $total_count += $row['count'];
    }

    return [
        'target' => $target,
        'current' => $total_count,
        'achieved' => $total_count >= $target
    ];
}

// Get unique semesters
$semesters = getUniqueSemesters($conn, $_SESSION["UID"]);

// Add this before the HTML
$kpiTargets = getKPITargets($conn, $_SESSION["UID"]);
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
                    <button class="tab-button" onclick="window.location.href='competition.php'">
                        <ion-icon name="trophy-outline"></ion-icon>
                        Competition
                    </button>
                    <button class="tab-button" onclick="window.location.href='certification.php'">
                        <ion-icon name="ribbon-outline"></ion-icon>
                        Certification
                    </button>
                    <button class="tab-button active" onclick="window.location.href='kpi_indicator.php'">
                        <ion-icon name="analytics-outline"></ion-icon>
                        KPI
                    </button>
                </div>
            </div>

            <div class="content-item">
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Indicator</th>
                                <th>Faculty KPI</th>
                                <th>My KPI</th>
                                <?php foreach ($semesters as $sem): ?>
                                    <th>Sem <?php echo $sem['sem'] . " " . $sem['year']; ?></th>
                                <?php endforeach; ?>
                                <?php if (empty($semesters)): ?>
                                    <th>No Records</th>
                                <?php endif; ?>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>CGPA</td>
                                <?php 
                                $cgpa_achievement = checkCGPAAchievement($conn, $_SESSION["UID"]);
                                ?>
                                <td style="text-align: center;">>= <?php echo $cgpa_achievement['target']; ?></td>
                                <td style="text-align: center;"><?php echo $cgpa_achievement['current']; ?></td>
                                <?php foreach ($semesters as $sem): ?>
                                    <td style="text-align: center;">
                                        <?php echo getCGPABySemester($conn, $_SESSION["UID"], $sem['sem'], $sem['year']); ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php if (empty($semesters)): ?>
                                    <td style="text-align: center;">-</td>
                                <?php endif; ?>
                                <td>
                                    <?php if ($cgpa_achievement['achieved']): ?>
                                        <span class='kpi-badge success'>Achieved (CGPA: <?php echo $cgpa_achievement['current']; ?>)</span>
                                    <?php else: ?>
                                        <span class='kpi-badge warning'>Not Achieved (CGPA: <?php echo $cgpa_achievement['current']; ?>)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td colspan="<?php echo count($semesters) + 5; ?>" style="background-color: #f5f5f5;">
                                    <strong>Student Activity</strong>
                                </td>
                            </tr>
                            <?php
                            $levels = ['Faculty', 'University', 'National', 'International'];
                            
                            foreach ($levels as $level) {
                                $activity_achievement = checkActivityAchievement($conn, $_SESSION["UID"], $level);
                                ?>
                                <tr>
                                    <td></td>
                                    <td><?php echo $level; ?></td>
                                    <td style="text-align: center;"><?php echo $activity_achievement['target']; ?></td>
                                    <td style="text-align: center;"><?php echo $activity_achievement['current']; ?></td>
                                    <?php foreach ($semesters as $sem): ?>
                                        <td style="text-align: center;">
                                            <?php echo getActivityCountBySemester($conn, $_SESSION["UID"], $level, $sem['sem'], $sem['year']); ?>
                                        </td>
                                    <?php endforeach; ?>
                                    <?php if (empty($semesters)): ?>
                                        <td style="text-align: center;">-</td>
                                    <?php endif; ?>
                                    <td>
                                        <?php if ($activity_achievement['achieved']): ?>
                                            <span class='kpi-badge success'>Target Achieved (<?php echo $activity_achievement['current']; ?>/<?php echo $activity_achievement['target']; ?>)</span>
                                        <?php else: ?>
                                            <span class='kpi-badge warning'>Target Not Met (<?php echo $activity_achievement['current']; ?>/<?php echo $activity_achievement['target']; ?>)</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            mysqli_close($conn);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="content-item">
                <div class="section-header">
                    <h2>
                        <ion-icon name="settings-outline"></ion-icon>
                        Set My KPI Target
                    </h2>
                </div>
                
                <form method="POST" action="activity_kpi_action.php">
                    <div class="table-container">
                        <table class="custom-table">
                            <thead>
                                <tr>
                                    <th>Activity Level</th>
                                    <th>Set Target Per Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="level-label">
                                            <ion-icon name="business-outline"></ion-icon>
                                            Faculty Level
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="faculty_target" min="0" max="10" 
                                               value="<?php echo isset($kpiTargets['faculty_target']) ? $kpiTargets['faculty_target'] : 2; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="level-label">
                                            <ion-icon name="school-outline"></ion-icon>
                                            University Level
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="university_target" min="0" max="10" 
                                               value="<?php echo isset($kpiTargets['university_target']) ? $kpiTargets['university_target'] : 2; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="level-label">
                                            <ion-icon name="flag-outline"></ion-icon>
                                            National Level
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="national_target" min="0" max="10" 
                                               value="<?php echo isset($kpiTargets['national_target']) ? $kpiTargets['national_target'] : 1; ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="level-label">
                                            <ion-icon name="globe-outline"></ion-icon>
                                            International Level
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="international_target" min="0" max="10" 
                                               value="<?php echo isset($kpiTargets['international_target']) ? $kpiTargets['international_target'] : 1; ?>" required>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="kpi-action-buttons">
                        <button type="submit" class="submit-button">
                            <ion-icon name="save-outline"></ion-icon>
                            Save KPI Targets
                        </button>
                    </div>
                </form>
            </div>

            <!-- Add this before footer -->
            <?php 
            if (isset($_SESSION['status_message'])) {
                include 'modal.php';
                unset($_SESSION['status_message']); 
            }
            ?>

            <footer>
                <p>Copyright (c) 2023 - All Reserved to Fazli</p>
            </footer>
        </div>
    </div>
    <script src="script.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>