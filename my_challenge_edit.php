<?php
session_start();
include("config.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
        <meta name = "viewport" content = "width=device-width, initial-scale = 1.0">
        <title>My Study KPI</title>
        <link rel = "stylesheet" href = "css/style.css">
        <link rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body onLoad="show_AddEntry()">
    <div class = "container">
        <div class = "side-nav" id = "nav-blocks">
            <?php
            $activePage = 'challenge';
            include 'logged_menu.php';
            ?>
        </div>

        <div class = "content-section">
            <div class = "header">
                <div class = "nav-toggle" onclick = "toggleNav()">
                    <ion-icon name="list-outline"></ion-icon>
                </div>
            </div>
            <div class = "header-title">
                <div class = "title">
                    <span>My Study KPI</span>
                    <h2>List of Challenge and Plan</h2>
                </div>
            </div>
            <div class = "content-item">
                <div class = "challenge-table">
                    <?php
                    $id = "";
                    $sem = "";
                    $year = "";
                    $challenge =" ";
                    $plan = "";
                    $remark = "";
                    $img = "";

                    if(isset($_GET["id"]) && $_GET["id"] != ""){
                        $id = $_GET["id"];
                        $userID = $_SESSION["UID"];

                    $sql = "SELECT * FROM challenge WHERE ch_id = $id AND userID = $userID";
                        //echo $sql . "<br>";
                        $result = mysqli_query($conn, $sql);
                            
                        if (mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $id = $row["ch_id"];
                            $sem = $row["sem"];
                            $year = $row["year"];
                            $challenge = $row["challenge"];
                            $plan = $row["plan"];
                            $remark = $row["remark"];
                            $img = $row["img_path"];
                        }        
                    }
                    mysqli_close($conn);
                    ?>

                    <div style="padding:0 10px;" id="formDiv">
                        <h3 align="center">Edit Your Challenge and Plan</h3>
                        <p align="center">Required field with mark*</p>

                        <form method="POST" action="my_challenge_edit_action.php" id="myForm" enctype="multipart/form-data">
                            <!--hidden value: id to be submitted to action page-->
                            <input type="hidden" id="cid" name="cid" value="<?=$_GET['id']?>">
                            <table border="1" id="myTable">            
                                <tr>
                                    <td>Semester*</td>
                                    <td width="1px">:</td>
                                    <td>
                                        <select size="1" name="sem" id="sem" required>                        
                                            <option value="">&nbsp;</option>
                                            <?php
                                            if($sem=="1")
                                                echo '<option value="1" selected>1</option>';
                                            else
                                                echo '<option value="1">1</option>';
                                            
                                            if($sem=="2")
                                                echo '<option value="2" selected>2</option>';
                                            else
                                                echo '<option value="2">2</option>';
                                            ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Year*</td>
                                    <td>:</td>
                                    <td>
                                        <?php
                                        if($year!=""){
                                            echo '<input type="text" name="year" size="5" value="' . $year . '" required>';
                                        }
                                        else {
                                        ?>
                                            <input type="text" name="year" size="5" required>
                                        <?php
                                        }
                                        ?>                
                                    </td>
                                </tr>
                                <tr>
                                    <td>Challenge*</td>
                                    <td>:</td>
                                    <td>
                                        <textarea rows="4" name="challenge" cols="20" required><?php echo $challenge;?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Plan*</td>
                                    <td>:</td>
                                    <td>
                                        <textarea rows="4" name="plan" cols="20" required><?php echo $plan;?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Remark</td>
                                    <td>:</td>
                                    <td>
                                        <textarea rows="4" name="remark" cols="20"><?php  echo $remark;?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Photo</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" disabled value="<?=$img;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td>Upload photo</td>
                                    <td>:</td>
                                    <td>
                                        Max size: 488.28KB<br>
                                        <input type="file" name="fileToUpload" id="fileToUpload" accept=".jpg, .jpeg, .png">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" align="right">
                                    <input type="submit" value="Submit" name="B1">                
                                    <input type="button" value="Reset" name="B2" onclick="resetForm()">
                                    <input type="button" value="Clear" name="B3" onclick="clearForm()">
                                    <a href = "my_challenge.php"><input type="button" value="Cancel" name="B3"></a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <footer>
            <p>Copyright (c) 2023 - All Reserved to Fazli</p>
            </footer>
        </div>
    </div>
    <script src = "script.js"></script> <!-- All JavaScript Function in script.js files -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>