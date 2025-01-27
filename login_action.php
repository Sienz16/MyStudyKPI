<?php
session_start();
include("config.php");
?>
<html>
<head>
<title>Login Action</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="mystyle.css" media="screen" />
</head>
<body>
    <h2>Login Details</h2>
    <?php
    //login values from login form
    $userName = $_POST['userName']; 
    $userPwd = $_POST['userPwd'];

    $sql = "SELECT * FROM user WHERE matricNo='$userName' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        $_SESSION['status_message'] = [
            'type' => 'error',
            'title' => 'Database Error',
            'message' => 'An error occurred while processing your request. Please try again.',
            'return_url' => 'index.php?login=1',
            'return_text' => 'Back to Login'
        ];
        header("Location: index.php?login=1");
        exit();
    }

    if(mysqli_num_rows($result) == 1){    
        //check password hash
        $row = mysqli_fetch_assoc($result);
        if(password_verify($_POST['userPwd'],$row['userPwd'])){
            $_SESSION["UID"] = $row["userID"];//the first record set, bind to userID
            $_SESSION["userName"] = $row["matricNo"];
            //set logged in time
            $_SESSION['loggedin_time'] = time();  
            header("location:home.php"); 
        } 
        else{
            $_SESSION['status_message'] = [
                'type' => 'error',
                'title' => 'Login Failed',
                'message' => 'Cannot login, your username or password is incorrect.',
                'return_url' => 'index.php?login=1',
                'return_text' => 'Try Again'
            ];
            header("Location: index.php?login=1");
            exit();
        }
    } 
    else{
        $_SESSION['status_message'] = [
            'type' => 'error',
            'title' => 'User Not Found',
            'message' => "Cannot login, user $userName does not exist in the system!",
            'return_url' => 'index.php?login=1',
            'return_text' => 'Back to Login'
        ];
        header("Location: index.php?login=1");
        exit();
    } 
    mysqli_close($conn);
    ?>
</body>
</html>