<?php
session_start();
include('config.php');

// Variables
$action = "";
$id = "";
$sem = "";
$year = "";
$activity_name = "";
$level = ""; 
$remarks = "";


// For upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 1;
$imageFileType = "";
$uploadfileName = "";

// This block is called when the Submit button is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $activity_name = trim($_POST["activity_name"]);
    $level = $_POST["level"];
    $remarks = trim($_POST["remarks"]);
    $uploadOk = 1;
    $uploadfileName = "";

    // Handle file upload
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] != "") {
        $target_dir = "uploads/";
        $uploadfileName = $_FILES["fileToUpload"]["name"];
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'File Already Exists',
                'message' => 'Sorry, a file with this name already exists. Please rename your file.'
            ];
            $uploadOk = 0;
        }

        // Check file size and type
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'File Too Large',
                'message' => 'The image file size should not exceed 488.28KB.'
            ];
            $uploadOk = 0;
        }
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'Invalid File Type',
                'message' => 'Only JPG, JPEG & PNG files are allowed.'
            ];
            $uploadOk = 0;
        }
    }

    if ($uploadOk) {
        $sql = "INSERT INTO activities (userID, sem, year, activity_name, level, remarks, img_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iisssss", $_SESSION["UID"], $sem, $year, $activity_name, $level, $remarks, $uploadfileName);
            
            if (mysqli_stmt_execute($stmt)) {
                // If there's a file to upload
                if ($uploadfileName && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $_SESSION['status_message'] = [
                        'type' => 'success',
                        'title' => 'Activity Added Successfully!',
                        'message' => 'Your activity record has been added with the photo.'
                    ];
                } else {
                    $_SESSION['status_message'] = [
                        'type' => 'success',
                        'title' => 'Activity Added Successfully!',
                        'message' => 'Your activity record has been added.'
                    ];
                }
            } else {
                $_SESSION['status_message'] = [
                    'type' => 'error',
                    'title' => 'Addition Failed',
                    'message' => 'There was an error adding your activity record: ' . mysqli_error($conn)
                ];
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'System Error',
                'message' => 'A database error occurred: ' . mysqli_error($conn)
            ];
        }
    }
}

mysqli_close($conn);
header("Location: activity.php");
exit();
?>