<?php
session_start();
include('config.php');

// variables
$action = "";
$id = "";
$sem = "";
$year = "";
$comp_name = ""; 
$level = "";
$remark = ""; 

// for upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 1;
$imageFileType = "";
$uploadfileName = "";

// this block is called when button Submit is clicked
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // values for add or edit
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $comp_name = trim($_POST["comp_name"]);
    $level = $_POST["level"];  
    $remark = trim($_POST["remark"]); 

    // Handle file upload
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] != "") {
        $uploadfileName = basename($_FILES["fileToUpload"]["name"]);
        $target_file = $target_dir . $uploadfileName;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'File Too Large',
                'message' => 'Sorry, your file is too large. Maximum size is 488.28KB.',
                'return_url' => 'competition.php',
                'return_text' => 'Back to Competition'
            ];
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png") {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'Invalid File Type',
                'message' => 'Sorry, only JPG, JPEG & PNG files are allowed.',
                'return_url' => 'competition.php',
                'return_text' => 'Back to Competition'
            ];
            $uploadOk = 0;
        }
    }

    if ($uploadOk == 1) {
        // Prepare SQL statement
        $sql = "INSERT INTO competition (userID, sem, year, comp_name, level, remark, img_path) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "iisssss", 
                $_SESSION["UID"],
                $sem,
                $year,
                $comp_name,
                $level,
                $remark,
                $uploadfileName
            );

            if (mysqli_stmt_execute($stmt)) {
                // If there's a file to upload
                if ($uploadfileName && move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    $_SESSION['status_message'] = [
                        'type' => 'success',
                        'title' => 'Competition Added Successfully!',
                        'message' => 'Your competition record has been added with the photo.',
                        'return_url' => 'competition.php',
                        'return_text' => 'View Competitions'
                    ];
                } else {
                    $_SESSION['status_message'] = [
                        'type' => 'success',
                        'title' => 'Competition Added Successfully!',
                        'message' => 'Your competition record has been added.',
                        'return_url' => 'competition.php',
                        'return_text' => 'View Competitions'
                    ];
                }
            } else {
                $_SESSION['status_message'] = [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'Failed to add competition: ' . mysqli_error($conn),
                    'return_url' => 'competition.php',
                    'return_text' => 'Back to Competition'
                ];
            }
            mysqli_stmt_close($stmt);
        } else {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'System Error',
                'message' => 'Database error: ' . mysqli_error($conn),
                'return_url' => 'competition.php',
                'return_text' => 'Back to Competition'
            ];
        }
    }
}

// close db connection
mysqli_close($conn);

header("Location: competition.php");
exit();
?>