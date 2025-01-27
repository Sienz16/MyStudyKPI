<?php
session_start();
include('config.php');

// This action is called when the Delete link is clicked
if(isset($_GET["id"]) && $_GET["id"] != ""){
    $id = $_GET["id"];

    // Find the image filename before deleting the record
    $sqlSelect = "SELECT img_path FROM certification WHERE cert_id = " . $id; 
    $result = mysqli_query($conn, $sqlSelect);

    if($result){
        $row = mysqli_fetch_assoc($result);
        $imageFileName = $row['img_path'];

        // Delete the image file if it exists
        if($imageFileName){
            $imagePath = 'uploads/' . $imageFileName;
            if(file_exists($imagePath)){
                unlink($imagePath);
            }
        }
    }
    $sql = "DELETE FROM certification WHERE cert_id=" . $id . " AND userID=" . $_SESSION["UID"]; 
    
    if(mysqli_query($conn, $sql)){
        echo "Your Record Deleted Successfully.<br>";
        echo '<a href="certification.php">Go Back</a>';
    } 
    else{
        echo "Error when deleting your record: " . mysqli_error($conn) . "<br>";
        echo '<a href="certification.php">Go Back</a>';
    }
}

mysqli_close($conn);
?>