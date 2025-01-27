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
    <body>
    <div class = "container">
        <div class = "side-nav" id = "nav-blocks">
            <?php
            $activePage = 'challenge';
            include 'logged_menu.php';
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $search = $_POST["search"]; 
            }
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
                <div style="text-align: right; padding:10px;" id = "search">
                    <form action="my_challenge_search.php" method="post">
                        <input type="icon" placeholder="Search.." name="search">
                        <button type = "submit" style = "background: #2A9D8F">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </form> 
                </div>
            </div>
            <div class = "content-item" style = "margin-top: 20px">
                <div class = "challenge-table">
                    <div class = "search-result" style="padding:0 10px;">
                        <h2>Search Result:&nbsp;<?=$search?></h2><br><br>
                        <table border="1" width="100%" id="projectable">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Sem & Year</th>
                                <th width="30%">Challenge</th>
                                <th width="30%">Plan</th>
                                <th width="15%">Remark</th>
                                <th width="10%">Photo</th>
                                <th width="10%">Action</th>
                            </tr>
                            <?php
                                if ($search!="") {
                                    $search = $_POST["search"]; 
                                    
                                    // Split the search string into individual words
                                    $keywords = explode(" ", $search);

                                    // Prepare the SQL query with multiple LIKE conditions
                                    $sql = "SELECT * FROM challenge WHERE (";

                                    // Build the conditions dynamically for single keyword
                                    $conditions = [];
                                    foreach ($keywords as $index => $keyword) {
                                        $conditions[] = "challenge LIKE '%$keyword%'";
                                    }                
                                    
                                    // Combine
                                    $sql .= implode(" OR ", $conditions);

                                    // Select only with this userID
                                    $sql .= " OR challenge LIKE '%$search%') AND userID=" . $_SESSION["UID"]; 
                                    
                                    $result = mysqli_query($conn, $sql);
                                    
                                    if (mysqli_num_rows($result) > 0) {
                                        // output data of each row
                                        $numrow=1;
                                        while($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . $numrow . "</td><td>". $row["sem"] . " " . $row["year"]. "</td><td>" . $row["challenge"] .
                                                "</td><td>" . $row["plan"] . "</td><td>" . $row["remark"] . "</td><td>" . $row["img_path"] . "</td>";
                                            echo '<td> <a href="my_challenge_edit.php?id=' . $row["ch_id"] . '">Edit</a>&nbsp;|&nbsp;';
                                            echo '<a href="my_challenge_delete.php?id=' . $row["ch_id"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
                                            echo "</tr>" . "\n\t\t";
                                            $numrow++;
                                        }
                                    } else {
                                        echo '<tr><td colspan="7">0 results</td></tr>';
                                    } 
                                    mysqli_close($conn);
                                }
                                else{
                                    echo "Search query is empty<br>";
                                }
                            ?>
                        </table>
                        <?php
                            if(isset($_SESSION["UID"])){
                        ?>
                            <div style="margin-top: 20px">
                                <input type="button" value="Add New" onclick="show_AddEntry()">
                            </div>
                        
                        <?php
                        }
                        ?>
                    </div>

                    <div style="padding:0 10px; display: none;" id="formDiv">
                        <h3 align="center">Add Challenge and Plan</h3>
                        <p align="center">Required field with mark*</p>

                        <form method="POST" action="my_challenge_action.php" enctype="multipart/form-data" id="myForm">
                            <table border="1" id="myTable">
                                <tr>
                                    <td>Semester*</td>
                                    <td width="1px">:</td>
                                    <td>
                                        <select size="1" id="sem" name="sem" required>                        
                                            <option value="">&nbsp;</option>
                                            <option value="1">1</option>;                           
                                            <option value="2">2</option>;                        
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Year*</td>
                                    <td>:</td>
                                    <td>
                                        <input type="text" name="year" size="5" required>                                    
                                    </td>
                                </tr>
                                <tr>
                                    <td>Challenge*</td>
                                    <td>:</td>
                                    <td>
                                        <textarea rows="4" name="challenge" cols="20" required></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Plan*</td>
                                    <td>:</td>
                                    <td>
                                        <textarea rows="4" name="plan" cols="20" required></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Remark</td>
                                    <td>:</td>
                                    <td>
                                        <textarea rows="4" name="remark" cols="20"></textarea>
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
                                    <input type="reset" value="Reset" name="B2">
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