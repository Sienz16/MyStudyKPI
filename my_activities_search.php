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
        $activePage = 'activities';
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
                <h2>My Activities/Competition/Certification Joined Lists</h2>
            </div>
        </div>
        <div class = "content-item">
            <div class = "activities-table">
                <div style="padding:0 10px;" id="listAndButton">
                    <div style="text-align: right; padding:10px;" id = "search">
                        <form action="my_activities_search.php" method="post">
                            <input type="icon" placeholder="Search.." name="search">
                            <button type = "submit" style = "background: #2A9D8F">
                                <ion-icon name="search-outline"></ion-icon>
                            </button>
                        </form>
                    </div>
                    <h2>Search Result:&nbsp;<?= $search ?></h2><br><br>
                    <table border="1" width="100%" id="projectable">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Sem & Year</th>
                            <th width="45%">List of Activities / Competition / Certification</th>
                            <th width="15%">Level</th>
                            <th width="15%">Remarks</th>
                            <th width="10%">Photo</th>
                        </tr>
                        <?php
                        if ($search != "") {
                            $search = $_POST["search"];

                            // Split the search string into individual words
                            $keywords = explode(" ", $search);

                            // Prepare the SQL query with multiple LIKE conditions
                            $sql = "SELECT * FROM activities WHERE (";

                            // Build the conditions dynamically for single keyword
                            $conditions = [];
                            foreach ($keywords as $index => $keyword) {
                                $conditions[] = "activity_name LIKE '%$keyword%' OR remarks LIKE '%$keyword%'";
                            }

                            // Combine
                            $sql .= implode(" OR ", $conditions);

                            // Select only with this userID
                            $sql .= ") AND userID=" . $_SESSION["UID"];

                            // Add UNION to combine results from other tables
                            $sql .= " UNION ";

                            // Add similar conditions for the competition table
                            $sql .= "SELECT * FROM competition WHERE (";

                            // Build the conditions dynamically for single keyword
                            $conditions = [];
                            foreach ($keywords as $index => $keyword) {
                                $conditions[] = "comp_name LIKE '%$keyword%' OR remark LIKE '%$keyword%'";
                            }

                            $sql .= implode(" OR ", $conditions);
                            $sql .= ") AND userID=" . $_SESSION["UID"];

                            // Add UNION to combine results from certification table
                            $sql .= " UNION ";

                            // Add similar conditions for the certification table
                            $sql .= "SELECT * FROM certification WHERE (";

                            // Build the conditions dynamically for single keyword
                            $conditions = [];
                            foreach ($keywords as $index => $keyword) {
                                $conditions[] = "cert_name LIKE '%$keyword%' OR remark LIKE '%$keyword%'";
                            }

                            $sql .= implode(" OR ", $conditions);
                            $sql .= ") AND userID=" . $_SESSION["UID"];

                            $result = mysqli_query($conn, $sql);
                            // Check for errors in the query execution
                            if (!$result) {
                                die("Error in SQL query: " . mysqli_error($conn));
                            }

                            if (mysqli_num_rows($result) > 0) {
                                // output data of each row
                                $numrow = 1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>" . $numrow . "</td><td>" . $row["sem"] . " " . $row["year"] . "</td><td>" . $row["activity_name"] .
                                        "</td><td>" . $row["remarks"] . "</td><td>" . $row["img_path"] . "</td>";
                                    echo '<td> <a href="my_activities_edit.php?id=' . $row["activity_id"] . '">Edit</a>&nbsp;|&nbsp;';
                                    echo '<a href="my_activities_delete.php?id=' . $row["activity_id"] . '" onClick="return confirm(\'Delete?\');">Delete</a> </td>';
                                    echo "</tr>" . "\n\t\t";
                                    $numrow++;
                                }
                            } else {
                                echo '<tr><td colspan="6">0 results</td></tr>';
                            }
                            mysqli_close($conn);
                        } else {
                            echo "Search query is empty<br>";
                        }
                        ?>
                    </table>
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