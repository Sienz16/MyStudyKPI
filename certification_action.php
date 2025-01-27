<?php
session_start();
include('config.php');

// Variables
$id = "";
$sem = "";
$year = "";
$cert_name = "";
$level = "";
$remark = "";
$uploadOk = 0;
$uploadFileName = "";

// For upload
$target_dir = "uploads/";
$target_file = "";
$imageFileType = "";

// This block is called when the button Submit is clicked
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Values for add or edit
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $cert_name = trim($_POST["cert_name"]);
    $level = $_POST["level"];
    $remark = trim($_POST["remark"]);

    $filetmp = $_FILES["fileToUpload"];
    // File of the image/photo file
    $uploadFileName = $filetmp["name"];

    // Check if there is an image to be uploaded
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] != "") {
        // Variable to determine if image upload is OK
        $uploadOk = 1;
        $filetmp = $_FILES["fileToUpload"];

        // File of the image/photo file
        $uploadFileName = $filetmp["name"];

        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "[ERROR]: Sorry, the image file $uploadFileName already exists. :(<br>";
            $uploadOk = 0;
        }

        // Check file size <= 488.28KB or 500000 bytes
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "[ERROR]: Sorry, your file is too large. :( Tips: Try resizing your image!<br>";
            $uploadOk = 0;
        }

        // Allow only these file formats
        if (!in_array($imageFileType, ["jpg", "png", "jpeg", "gif"])) {
            echo "[ERROR]: Sorry, only JPG, JPEG, PNG & GIF files are allowed. :(<br>";
            $uploadOk = 0;
        }

        // If uploadOk, then try to add to the database first
        if ($uploadOk) {
            $sql = "INSERT INTO certification (userID, sem, year, cert_name, level, remark, img_path) 
            VALUES (" . $_SESSION["UID"] . ", " . $sem . ", '" . $year . "', '" . $cert_name . "', '" . $level . "', '" . $remark . "', '" . $uploadFileName . "')";

            $status = insertTo_DBTable($conn, $sql);

            if ($status) {
                if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                    // Image file successfully uploaded
                    echo "Your data has been saved successfully! :)<br>";
                    echo '<a href="certification.php">Go Back</a>';
                } 
                else {
                    // There is an error while uploading image
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
    else {
        // No image to upload
        $sql = "INSERT INTO certification (userID, sem, year, cert_name, level, remark, img_path) 
        VALUES (" . $_SESSION["UID"] . ", " . $sem . ", '" . $year . "', '" . $cert_name . "', '" . $level . "', '" . $remark . "', '" . $uploadFileName . "')";

        $status = insertTo_DBTable($conn, $sql);

        if ($status) {
            echo "Your data has been saved successfully! :)<br>";
            echo '<a href="certification.php">Go Back</a>';
        } 
        else {
            echo '<a href="certification.php">Go Back</a>';
        }
    }
}

// Close DB connection
mysqli_close($conn);

// Function to insert data into the database table
function insertTo_DBTable($conn, $sql)
{
    if (mysqli_query($conn, $sql)) {
        return true;
    } 
    else {
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}
?>