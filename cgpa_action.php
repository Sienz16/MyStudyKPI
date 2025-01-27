<?php
session_start();
include('config.php');

// Variables
$action = "";
$id = "";
$sem = "";
$year = "";
$mykpi_cgpa = "";
$result_cgpa = "";
$remark = "";

// This block is called when the Submit button is clicked
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Values for add or edit
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $mykpi_cgpa = trim($_POST["mykpi_cgpa"]);
    $result_cgpa = trim($_POST["result_cgpa"]);
    $remark = trim($_POST["remark"]);
    $userID = $_SESSION["UID"];

    $sql = "INSERT INTO cgpa (sem, year, mykpi_cgpa, result_cgpa, remark, userID) 
            VALUES (?, ?, ?, ?, ?, ?)";
            
    // Using prepared statement
    $stmt = mysqli_prepare($conn, $sql);
    
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "issssi", $sem, $year, $mykpi_cgpa, $result_cgpa, $remark, $userID);
        
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['status_message'] = [
                'type' => 'success',
                'title' => 'GPA Added Successfully!',
                'message' => 'Your new GPA record has been added.'
            ];
        } else {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'Addition Failed',
                'message' => 'There was an error adding your GPA record. Please try again.'
            ];
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['status_message'] = [
            'type' => 'error',
            'title' => 'System Error',
            'message' => 'A database error occurred. Please try again later.'
        ];
    }
}

// Close the database connection
mysqli_close($conn);

// Function to insert data into the database table
function insertTo_DBTable($conn, $sql){
    if(mysqli_query($conn, $sql)){
        return true;
    } 
    else{
        echo "Error: " . $sql . " : " . mysqli_error($conn) . "<br>";
        return false;
    }
}

header("Location: cgpa.php");
exit();
?>