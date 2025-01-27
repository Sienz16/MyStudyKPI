<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userID = $_SESSION["UID"];
    $faculty_target = $_POST['faculty_target'];
    $university_target = $_POST['university_target'];
    $national_target = $_POST['national_target'];
    $international_target = $_POST['international_target'];

    // Check if user already has KPI settings
    $check_sql = "SELECT kpi_id FROM activity_kpi WHERE userID = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "i", $userID);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        // Update existing KPI settings
        $sql = "UPDATE activity_kpi SET 
                faculty_target = ?, 
                university_target = ?, 
                national_target = ?, 
                international_target = ? 
                WHERE userID = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiiii", 
            $faculty_target, 
            $university_target, 
            $national_target, 
            $international_target,
            $userID
        );
    } else {
        // Insert new KPI settings
        $sql = "INSERT INTO activity_kpi 
                (userID, faculty_target, university_target, national_target, international_target) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiiii", 
            $userID,
            $faculty_target, 
            $university_target, 
            $national_target, 
            $international_target
        );
    }

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['status_message'] = [
            'type' => 'success',
            'title' => 'Success!',
            'message' => 'Your KPI targets have been updated successfully.',
            'return_url' => 'kpi_indicator.php',
            'return_text' => 'Continue'
        ];
    } else {
        $_SESSION['status_message'] = [
            'type' => 'error',
            'title' => 'Error!',
            'message' => 'Failed to update KPI targets. Please try again.',
            'return_url' => 'kpi_indicator.php',
            'return_text' => 'Try Again'
        ];
    }
}

mysqli_close($conn);
header("Location: kpi_indicator.php");
exit();
?>