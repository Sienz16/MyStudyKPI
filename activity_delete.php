<?php
session_start();
include('config.php');

// This action is called when the Delete link is clicked
if(isset($_GET["id"]) && $_GET["id"] != ""){
    $id = $_GET["id"];

    // Find the image filename before deleting the record
    $sqlSelect = "SELECT img_path FROM activities WHERE activity_id = ? AND userID = ?";
    $stmt = mysqli_prepare($conn, $sqlSelect);
    
    if($stmt){
        mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION["UID"]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if($row = mysqli_fetch_assoc($result)){
            $imageFileName = $row['img_path'];

            // Delete the image file if it exists
            if($imageFileName){
                $imagePath = 'uploads/' . $imageFileName;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Delete the record
    $sql = "DELETE FROM activities WHERE activity_id = ? AND userID = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if($stmt){
        mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION["UID"]);
        if(mysqli_stmt_execute($stmt)){
            $_SESSION['status_message'] = [
                'type' => 'success',
                'title' => 'Activity Deleted',
                'message' => 'The activity record has been deleted successfully.'
            ];
        } else {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'Deletion Failed',
                'message' => 'There was an error deleting the activity record.'
            ];
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
header("Location: activity.php");
exit();
?>