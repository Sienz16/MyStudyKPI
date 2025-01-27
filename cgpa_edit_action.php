<?php
session_start();
include('config.php');

// Variables
$id = "";
$sem = "";
$year = "";
$mykpi_cgpa = "";
$result_cgpa = "";
$remark = "";

// This block is called when the button Submit is clicked
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Values for edit
    $id = $_POST["cgpa_id"];
    $sem = $_POST["sem"];
    $year = $_POST["year"];
    $mykpi_cgpa = trim($_POST["mykpi_cgpa"]);
    $result_cgpa = trim($_POST["result_cgpa"]);
    $remark = trim($_POST["remark"]);

    $sql = "UPDATE cgpa SET sem = ?, year = ?, mykpi_cgpa = ?, result_cgpa = ?, remark = ? 
            WHERE cgpa_id = ? AND userID = ?";
            
    // Using prepared statement
    $stmt = mysqli_prepare($conn, $sql);
    
    if($stmt) {
        mysqli_stmt_bind_param($stmt, "issssii", $sem, $year, $mykpi_cgpa, $result_cgpa, $remark, $id, $_SESSION["UID"]);
        
        if(mysqli_stmt_execute($stmt)) {
            $_SESSION['status_message'] = [
                'type' => 'success',
                'title' => 'CGPA Updated Successfully!',
                'message' => 'Your CGPA record has been updated.',
                'return_url' => 'cgpa.php',
                'return_text' => 'View CGPA Records'
            ];
        } else {
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'Update Failed',
                'message' => 'There was an error updating your CGPA record: ' . mysqli_error($conn),
                'return_url' => 'cgpa.php',
                'return_text' => 'Back to CGPA'
            ];
        }
        
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['status_message'] = [
            'type' => 'error',
            'title' => 'System Error',
            'message' => 'A database error occurred: ' . mysqli_error($conn),
            'return_url' => 'cgpa.php',
            'return_text' => 'Back to CGPA'
        ];
    }
}

// Close the DB connection
mysqli_close($conn);

// Function to update data in the database table
function update_DBTable($conn, $sql){
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