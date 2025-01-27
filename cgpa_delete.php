<?php
session_start();
include('config.php');

// This action is called when the Delete link is clicked
if(isset($_GET["id"]) && $_GET["id"] != ""){
    $id = $_GET["id"];

    $sql = "DELETE FROM cgpa WHERE cgpa_id=" . $id . " AND userID=" . $_SESSION["UID"];
    
    if(mysqli_query($conn, $sql)){
        echo "Your Record Deleted Successfully.<br>";
        echo '<a href="cgpa.php">Go Back</a>';
    } 
    else{
        echo "Error when deleting your record: " . mysqli_error($conn) . "<br>";
        echo '<a href="cgpa.php">Go Back</a>';
    }
}

mysqli_close($conn);
?>