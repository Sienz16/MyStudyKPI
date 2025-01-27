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
    <body onLoad = "showAddEntry()">
    <div class = "container">
        <div class = "side-nav" id = "nav-blocks">
            <?php
            $activePage = 'kpi';
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
                    <h2>My KPI Indicator</h2>
                </div>
            </div>
            <div class = "content-item">
                <div class="kpi-tab">
                    <button class="tab-button" onclick="window.location.href='cgpa.php'">CGPA</button>
                    <button class="tab-button" onclick="window.location.href='activity.php'">Activity</button>
                    <button class="tab-button" onclick="window.location.href='competition.php'">Competition</button>
                    <button class="tab-button" id = "tabActive" onclick="window.location.href='certification.php'">Certification</button>
                    <button class="tab-button" onclick="window.location.href='kpi_indicator.php'">KPI</button>
                </div>
                <?php
                $cert_id = "";
                $sem = "";
                $year = "";
                $cert_name = "";
                $level = ""; 
                $remark = ""; 
                $img_path = "";

                if (isset($_GET["id"]) && $_GET["id"] != "") {
                    $cert_id = $_GET["id"];
                    $userID = $_SESSION["UID"];

                    $sql = "SELECT * FROM certification WHERE cert_id = ? AND userID = ?";

                    // Using prepared statement to prevent SQL injection
                    $stmt = mysqli_prepare($conn, $sql);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ii", $cert_id, $userID);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($result) {
                            if ($row = mysqli_fetch_assoc($result)) {
                                $cert_id = $row["cert_id"];
                                $sem = $row["sem"];
                                $year = $row["year"];
                                $cert_name = $row["cert_name"];
                                $level = $row["level"];
                                $remark = $row["remark"];
                                $img_path = $row["img_path"];
                            }
                        }
                        mysqli_stmt_close($stmt);
                    }
                }
                mysqli_close($conn);
                ?>

                <div style="padding:0 10px;" id="formDiv">
                    <h3 align="center">Edit Your Certification</h3>
                    <p align="center">Required fields are marked with *</p>

                    <form method="POST" action="certification_edit_action.php" id="myForm" enctype="multipart/form-data">
                        <!-- Hidden value: cert_id to be submitted to action page -->
                        <input type="hidden" id="cert_id" name="cert_id" value="<?= $_GET['id'] ?>">
                        <table border="1" id="myTable">
                            <tr>
                                <td>Semester*</td>
                                <td width="1px">:</td>
                                <td>
                                    <select size="1" name="sem" id="sem" required>
                                        <option value="" <?php echo ($sem == "") ? 'selected' : ''; ?>>&nbsp;</option>
                                        <option value="1" <?php echo ($sem == "1") ? 'selected' : ''; ?>>1</option>
                                        <option value="2" <?php echo ($sem == "2") ? 'selected' : ''; ?>>2</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Year*</td>
                                <td>:</td>
                                <td>
                                    <?php
                                    if ($year != "") {
                                        echo '<input type="text" name="year" size="5" value="' . $year . '" required>';
                                    } else {
                                    ?>
                                        <input type="text" name="year" size="5" required>
                                    <?php
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Name of Certification*</td>
                                <td>:</td>
                                <td>
                                    <textarea rows="4" name="cert_name" cols="20" required><?php echo $cert_name; ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <td>Level*</td>
                                <td>:</td>
                                <td>
                                    <select size="1" name="level" required>
                                        <option value="">&nbsp;</option>
                                        <?php
                                        if ($level == "Faculty")
                                            echo '<option value="Faculty" selected>Faculty</option>';
                                        else
                                            echo '<option value="Faculty">Faculty</option>';

                                        if ($level == "University")
                                            echo '<option value="University" selected>University</option>';
                                        else
                                            echo '<option value="University">University</option>';
                                        
                                        if ($level == "National")
                                            echo '<option value="National" selected>National</option>';
                                        else
                                            echo '<option value="National">National</option>';
                                            
                                        if ($level == "International")
                                            echo '<option value="International" selected>International</option>';
                                        else
                                            echo '<option value="International">International</option>';
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Remark</td>
                                <td>:</td>
                                <td>
                                    <textarea rows="4" name="remark" cols="20"><?php echo $remark; ?></textarea>
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
                                    <a href = "certification.php"><input type="button" value="Cancel" name="B3"></a>
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
    <script src = "script.js"></script> <!-- All JavaScript Function in script.js files -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>