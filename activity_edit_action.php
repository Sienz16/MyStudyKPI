<?php
session_start();
include('config.php');

// Variables
$id = "";
$sem = "";
$year = "";
$activity = "";
$level = "";
$remark = "";

// For upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 1;
$imageFileType = "";
$uploadfileName = "";

// This block is called when the button Submit is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $id = $_POST["activity_id"];
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $activity = trim($_POST["activity_name"]);
    $level = $_POST["level"];
    $remark = trim($_POST["remarks"]);

    // Get the existing image file name
    $existingImagePath = "";
    $existingImagePathQuery = mysqli_query($conn, "SELECT img_path FROM activities WHERE activity_id = $id AND userID = {$_SESSION["UID"]}");
    $existingImageRow = mysqli_fetch_assoc($existingImagePathQuery);
    if ($existingImageRow) {
        $existingImagePath = $existingImageRow["img_path"];
    }

    // Handle file upload if new file selected
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] != "") {
        // Delete old photo if exists
        if (!empty($existingImagePath) && file_exists("uploads/" . $existingImagePath)) {
            unlink("uploads/" . $existingImagePath);
        }

        $target_dir = "uploads/";
        $uploadfileName = $_FILES["fileToUpload"]["name"];
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate file
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'File Too Large',
                'message' => 'The image file size should not exceed 488.28KB.',
                'return_url' => 'activity.php',
                'return_text' => 'Back to Activities'
            ];
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'Invalid File Type',
                'message' => 'Only JPG, JPEG & PNG files are allowed.',
                'return_url' => 'activity.php',
                'return_text' => 'Back to Activities'
            ];
            $uploadOk = 0;
        }
    }

    if ($uploadOk) {
        // Update database
        $sql = "UPDATE activities SET sem = ?, year = ?, activity_name = ?, level = ?, remarks = ?";
        if (isset($uploadfileName)) {
            $sql .= ", img_path = ?";
        }
        $sql .= " WHERE activity_id = ? AND userID = ?";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            if (isset($uploadfileName)) {
                mysqli_stmt_bind_param($stmt, "isssssii", $sem, $year, $activity, $level, $remark, $uploadfileName, $id, $_SESSION["UID"]);
            } else {
                mysqli_stmt_bind_param($stmt, "issssii", $sem, $year, $activity, $level, $remark, $id, $_SESSION["UID"]);
            }
            
            if (mysqli_stmt_execute($stmt)) {
                // Upload new file if exists
                if (isset($uploadfileName) && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $_SESSION['status_message'] = [
                        'type' => 'success',
                        'title' => 'Activity Updated Successfully!',
                        'message' => 'Your activity record has been updated with the new photo.',
                        'return_url' => 'activity.php',
                        'return_text' => 'View Activities'
                    ];
                } else {
                    $_SESSION['status_message'] = [
                        'type' => 'success',
                        'title' => 'Activity Updated Successfully!',
                        'message' => 'Your activity record has been updated.',
                        'return_url' => 'activity.php',
                        'return_text' => 'View Activities'
                    ];
                }
            } else {
                $_SESSION['status_message'] = [
                    'type' => 'error',
                    'title' => 'Update Failed',
                    'message' => 'There was an error updating your activity record.',
                    'return_url' => 'activity.php',
                    'return_text' => 'Back to Activities'
                ];
            }
            mysqli_stmt_close($stmt);
        }
    }
}

// Close the DB connection
mysqli_close($conn);

// Function to insert data into the database table
function update_DBTable($conn, $sql){
    if(mysqli_query($conn, $sql)){
        return true;
    } 
    else{
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}

header("Location: activity.php");
exit();
?>