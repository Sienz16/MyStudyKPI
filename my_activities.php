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
                    <h2>My Activities / Competiton / Certification Lists</h2>
                </div>
            </div>
            <div class = "content-item">
                <div style = "text-align: right" id="search">
                    <form action="my_activities_search.php" method="post">
                        <input type="text" placeholder="Search.." name="search" id="searchInput" oninput="liveSearchActivity()">
                        <button type="submit" id = "searchButton" style="background: #2A9D8F">
                            <ion-icon name="search-outline"></ion-icon>
                        </button>
                    </form>
                </div>
            </div>
            <div class = "content-item" style = "margin-top: 20px;">
                <div class = "activities-table">
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
                        // Query for activities
                        $sql_activities = "SELECT activity_id, sem, year, activity_name AS name, level, remarks, img_path
                                            FROM activities
                                            WHERE userID=" . $_SESSION["UID"];

                        $result_activities = mysqli_query($conn, $sql_activities);

                        // Query for competitions
                        $sql_competitions = "SELECT comp_id AS activity_id, sem, year, comp_name AS name, level, remark AS remarks, img_path
                                            FROM competition
                                            WHERE userID=" . $_SESSION["UID"];

                        $result_competitions = mysqli_query($conn, $sql_competitions);

                        // Query for certifications
                        $sql_certifications = "SELECT cert_id AS activity_id, sem, year, cert_name AS name, level, remark AS remarks, img_path
                                            FROM certification
                                            WHERE userID=" . $_SESSION["UID"];

                        $result_certifications = mysqli_query($conn, $sql_certifications);

                        // Combine the results
                        $combined_result = array_merge(
                            mysqli_fetch_all($result_activities, MYSQLI_ASSOC),
                            mysqli_fetch_all($result_competitions, MYSQLI_ASSOC),
                            mysqli_fetch_all($result_certifications, MYSQLI_ASSOC)
                        );

                        // Sort the combined result based on year and then sem
                        usort($combined_result, function ($a, $b) {
                            $yearComparison = strcmp($a['year'], $b['year']);
                            return $yearComparison !== 0 ? $yearComparison : strcmp($a['sem'], $b['sem']);
                        });

                        // output data of each row
                        $numrow = 1;
                        foreach ($combined_result as $row) {
                            echo "<tr>";
                            echo "<td>" . $numrow . "</td><td>" . $row["sem"] . " " . $row["year"] . "</td><td>" . $row["name"] . "</td><td>" . $row["level"] . "</td><td>" . $row["remarks"] . "</td><td>";

                            // Create a hyperlink around the photo if img_path is not empty
                            if (!empty($row["img_path"])) {
                                echo '<a href="uploads/' . $row["img_path"] . '" target="_blank">' . $row["img_path"] . '</a>';
                            }
                            echo "</td>";
                            echo "</tr>" . "\n\t\t";
                            $numrow++;
                        }
                        mysqli_close($conn);
                        ?>
                    </table>
                    </div>
                </div>
                <footer>
                    <p>Copyright (c) 2023 - All Reserved to Fazli</p>
                </footer>
            </div>
        </div>
    </div>
    <script src = "script.js"></script> <!-- All JavaScript Function in script.js files -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>