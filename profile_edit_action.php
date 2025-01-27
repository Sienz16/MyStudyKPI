<?php
session_start();
include('config.php');

if(!isset($_SESSION["UID"])){
    header("location:index.php"); 
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $loggedInUserID = $_SESSION["UID"];
    $username = $_POST["username"];
    $program = $_POST["program"];
    $mentor = $_POST["mentor"];
    $motto = $_POST["motto"];
    
    $success = true;
    $message = "";
    
    // Initialize SQL query for profile update
    $sql = "UPDATE profile SET 
            username = '$username', 
            program = '$program', 
            mentor = '$mentor', 
            motto = '$motto' 
            WHERE userID = $loggedInUserID";

    // Handle file upload if a file was selected
    if(!empty($_FILES["profile_photo"]["name"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_photo"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is actual image
        $check = getimagesize($_FILES["profile_photo"]["tmp_name"]);
        if($check === false) {
            $success = false;
            $message .= "File is not an image. ";
            $uploadOk = 0;
        }

        // Check file size (500KB limit)
        if($_FILES["profile_photo"]["size"] > 500000) {
            $success = false;
            $message .= "File is too large. ";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $success = false;
            $message .= "Only JPG, JPEG, PNG & GIF files are allowed. ";
            $uploadOk = 0;
        }

        // If file is OK, try to upload it
        if($uploadOk == 1) {
            if(move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file)) {
                // Update SQL query to include profile photo
                $sql = "UPDATE profile SET 
                        username = '$username', 
                        program = '$program', 
                        mentor = '$mentor', 
                        motto = '$motto',
                        profile_photo = '$target_file' 
                        WHERE userID = $loggedInUserID";
            } else {
                $success = false;
                $message .= "Error uploading file. ";
            }
        }
    }

    // Execute the SQL query
    if(mysqli_query($conn, $sql)) {
        if($success) {
            $_SESSION['status_message'] = [
                'type' => 'success',
                'title' => 'Profile Updated Successfully!',
                'message' => 'Your profile information has been updated. The changes will be reflected immediately.'
            ];
        } else {
            $_SESSION['status_message'] = [
                'type' => 'warning',
                'title' => 'Partial Update',
                'message' => 'Profile information updated, but there were some issues: ' . $message
            ];
        }
    } else {
        $_SESSION['status_message'] = [
            'type' => 'error',
            'title' => 'Update Failed',
            'message' => 'There was an error updating your profile: ' . mysqli_error($conn)
        ];
    }
    
    header("Location: profile.php");
    exit();
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Update Status</title>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            margin: 0;
            font-family: 'Ubuntu', sans-serif;
        }

        .status-container {
            display: flex;
            align-items: center;
            gap: 3rem;
            padding: 2rem;
        }

        .status-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .check-icon {
            color: #2A9D8F;
            font-size: 2rem;
        }

        .status-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .status-message {
            background: #f0f9f8;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: #2A9D8F;
            font-size: 0.95rem;
            margin: 0;
            line-height: 1.5;
        }

        .status-actions {
            display: flex;
            gap: 1rem;
            margin-left: 2rem;
        }

        .status-button {
            padding: 0.7rem 1.5rem;
            border-radius: 6px;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
        }

        .primary-button {
            background: #2A9D8F;
            color: white;
        }

        .secondary-button {
            background: #f1f3f5;
            color: #333;
        }

        .status-button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="status-container">
        <div class="status-left">
            <ion-icon name="checkmark-circle-outline" class="check-icon"></ion-icon>
            <div class="status-content">
                <h1 class="status-title">Profile Updated Successfully!</h1>
                <p class="status-message">
                    Your profile information has been updated.<br>
                    The changes will be reflected immediately.
                </p>
            </div>
        </div>
        <div class="status-actions">
            <a href="profile.php" class="status-button primary-button">
                <ion-icon name="person-outline"></ion-icon>
                View Profile
            </a>
            <a href="profile_edit.php" class="status-button secondary-button">
                <ion-icon name="create-outline"></ion-icon>
                Edit Again
            </a>
        </div>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
</body>
</html>