<?php
include("config.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/style.css">
    <title>Registration Status</title>
</head>
<body>
    <div class="status-container">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userMatric = mysqli_real_escape_string($conn, $_POST['matricNo']);
            $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
            $userPwd = mysqli_real_escape_string($conn, $_POST['userPwd']);
            $confirmPwd = mysqli_real_escape_string($conn, $_POST['confirmPwd']);

            if ($userPwd !== $confirmPwd) {
                echo '<div class="status-card error">
                        <div class="status-icon">
                            <ion-icon name="close-circle-outline"></ion-icon>
                        </div>
                        <div class="status-content">
                            <h2>Password Mismatch</h2>
                            <p>Password and confirm password do not match.</p>
                            <a href="index.php" class="status-button">Try Again</a>
                        </div>
                    </div>';
            } else {
                $sql = "SELECT * FROM user WHERE userEmail='$userEmail' or matricNo='$userMatric' LIMIT 1";
                $result = mysqli_query($conn, $sql);
                
                if (mysqli_num_rows($result) == 1) {
                    echo '<div class="status-card error">
                            <div class="status-icon">
                                <ion-icon name="person-remove-outline"></ion-icon>
                            </div>
                            <div class="status-content">
                                <h2>Registration Failed</h2>
                                <p>User already exists. Please try with different credentials.</p>
                                <a href="index.php" class="status-button">Try Again</a>
                            </div>
                        </div>';
                } else {
                    $pwdHash = trim(password_hash($_POST['userPwd'], PASSWORD_DEFAULT));
                    $sql = "INSERT INTO user (matricNo, userEmail, userPwd) VALUES ('$userMatric', '$userEmail', '$pwdHash')";
                    $insertOK = 0;

                    if (mysqli_query($conn, $sql)) {
                        $insertOK = 1;
                        $lastInsertedId = mysqli_insert_id($conn);
                        $sql = "INSERT INTO profile (userID, username, program, mentor, motto) VALUES ('$lastInsertedId', '','', '','')";

                        if (mysqli_query($conn, $sql)) {
                            echo '<div class="status-card success">
                                    <div class="status-icon">
                                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                                    </div>
                                    <div class="status-content">
                                        <h2>Registration Successful!</h2>
                                        <p>Welcome to My Study KPI, ' . $userMatric . '!</p>
                                        <p class="status-message">Your account has been created successfully.</p>
                                        <a href="index.php" class="status-button">Proceed to Login</a>
                                    </div>
                                </div>';
                        } else {
                            echo '<div class="status-card error">
                                    <div class="status-icon">
                                        <ion-icon name="alert-circle-outline"></ion-icon>
                                    </div>
                                    <div class="status-content">
                                        <h2>Profile Creation Error</h2>
                                        <p>Error creating user profile. Please contact support.</p>
                                        <p class="error-details">' . mysqli_error($conn) . '</p>
                                        <a href="index.php" class="status-button">Back to Home</a>
                                    </div>
                                </div>';
                        }
                    } else {
                        echo '<div class="status-card error">
                                <div class="status-icon">
                                    <ion-icon name="alert-circle-outline"></ion-icon>
                                </div>
                                <div class="status-content">
                                    <h2>Registration Error</h2>
                                    <p>Error creating user account. Please try again.</p>
                                    <p class="error-details">' . mysqli_error($conn) . '</p>
                                    <a href="index.php" class="status-button">Try Again</a>
                                </div>
                            </div>';
                    }
                }
            }
        }
        mysqli_close($conn);
        ?>
    </div>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>