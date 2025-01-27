<?php
session_start();
include('config.php');

// Variables
$cert_id = "";
$sem = "";
$year = "";
$cert_name = "";
$level = "";
$remark = "";

// For upload
$target_dir = "uploads/";
$target_file = "";
$uploadOk = 0;
$imageFileType = "";
$uploadFileName = "";

// This block is called when the button Submit is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $cert_id = $_POST["cert_id"];
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $cert_name = trim($_POST["cert_name"]);
    $level = $_POST["level"];
    $remark = trim($_POST["remark"]);

    $id = mysqli_real_escape_string($conn, $cert_id);

    // Get the existing image file name
    $existingImagePath = "";
    $existingImagePathQuery = mysqli_query($conn, "SELECT img_path FROM certification WHERE cert_id = $id AND userID = {$_SESSION["UID"]}");
    $existingImageRow = mysqli_fetch_assoc($existingImagePathQuery);
    if ($existingImageRow) {
        $existingImagePath = $existingImageRow["img_path"];
    } 
    else {
        die('Query failed: ' . mysqli_error($conn));
    }

    $filetmp = $_FILES["fileToUpload"];
    // File of the image/photo file
    $uploadFileName = $filetmp["name"];

    // Delete the previous photo in the uploads folder
    if (!empty($existingImagePath) && file_exists("uploads/" . $existingImagePath)) {
        unlink("uploads/" . $existingImagePath);
    }
    // Check if there is an image to be uploaded
    // If no image
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] == "") {
        $sql = "UPDATE certification SET sem = $sem, year = '$year', cert_name = '$cert_name', level = '$level', remark = '$remark', img_path = '$uploadFileName' WHERE cert_id = " . $cert_id . " AND userID = " . $_SESSION["UID"];

        $status = update_DBTable($conn, $sql);

        if ($status) {
            echo "Your data has been updated successfully! :)<br>";
            echo '<a href="certification.php">Go Back</a>';
        } 
        else {
            echo '<a href="certification.php">Go Back</a>';
        }
    }
    // If there is an image
    else if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == UPLOAD_ERR_OK) {
        // Variable to determine if image upload is OK
        $uploadOk = 1;
        $filetmp = $_FILES["fileToUpload"];

        // File of the image/photo file
        $uploadFileName = $filetmp["name"];

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "[ERROR] : Sorry, the image file $uploadFileName already exists. :(<br>";
            $uploadOk = 0;
        }

        // Check file size <= 488.28KB or 500000 bytes
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "[ERROR] : Sorry, your file is too large. :( Tips:  Try resizing your image!<br>";
            $uploadOk = 0;
        }

        // Allow only these file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "[ERROR] : Sorry, only JPG, JPEG, PNG & GIF files are allowed. :(<br>";
            $uploadOk = 0;
        }

        // If uploadOk, then try to add to the database first
        // uploadOK=1 if there is an image to be uploaded, filename not exists, file size is ok, and format ok
        if ($uploadOk) {
            $sql = "UPDATE certification SET sem = $sem, year = '$year', cert_name = '$cert_name', level = '$level', remark = '$remark', img_path = '$uploadFileName' WHERE cert_id = " . $cert_id . " AND userID = " . $_SESSION["UID"];

            $status = update_DBTable($conn, $sql);

            if ($status) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    // Image file successfully uploaded

                    // Prompt successful record message
                    echo "Your data has been updated successfully! :)<br>";
                    echo '<a href="certification.php">Go Back</a>';
                } 
                else {
                    // There is an error while uploading the image
                    echo "Sorry, there was an error when trying to upload your file. :(<br>";
                    echo '<a href="javascript:history.back()">Go Back</a>';
                }
            } 
            else {
                echo '<a href="javascript:history.back()">Go Back</a>';
            }
        } 
        else {
            echo '<a href="javascript:history.back()">Go Back</a>';
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
?>